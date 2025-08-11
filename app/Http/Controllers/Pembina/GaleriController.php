<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $pembina = Auth::user();

        // 1. Ambil semua ID ekstrakurikuler yang dibina oleh user ini
        $ekstrakurikulerIds = $pembina->ekstrakurikulerSebagaiPembina()->pluck('id');

        // 2. Buat query untuk Galeri
        $galeriQuery = Galeri::with('ekstrakurikuler')
            ->whereIn('ekstrakurikuler_id', $ekstrakurikulerIds); // Gunakan whereIn, bukan where('pembina_id')

        // 3. Terapkan sorting berdasarkan request
        $sort = $request->input('sort', 'terbaru'); // Default ke 'terbaru'
        if ($sort == 'terlama') {
            $galeriQuery->orderBy('created_at', 'asc');
        } else {
            $galeriQuery->orderBy('created_at', 'desc');
        }

        // 4. Ambil data dengan paginasi
        $galeri = $galeriQuery->paginate(9);

        return view('pembina.galeri.index', compact('galeri'));
    }

    public function create()
    {
        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina;

        return view('pembina.galeri.create', compact('ekstrakurikulers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480', // 20MB
        ]);

        $user = Auth::user();

        // Pastikan ekstrakurikuler milik pembina
        $ekstrakurikuler = $user->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            abort(403, 'Unauthorized action.');
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Determine file type
        $mimeType = $file->getMimeType();
        $tipe = str_starts_with($mimeType, 'video/') ? 'video' : 'gambar';

        // Store file
        $path = $file->storeAs('galeri', $filename, 'public');

        Galeri::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
            'path_file' => $path,
            'tipe' => $tipe,
            'diupload_oleh' => $user->id
        ]);

        return redirect()->route('pembina.galeri.index')
            ->with('success', 'File galeri berhasil diupload!');
    }

    public function show(Galeri $galeri)
    {
        // Pastikan galeri milik ekstrakurikuler yang dibina user
        if ($galeri->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $galeri->load(['ekstrakurikuler', 'uploader']);
        return view('pembina.galeri.show', compact('galeri'));
    }

    public function edit(Galeri $galeri)
    {
        // Pastikan galeri milik ekstrakurikuler yang dibina user
        if ($galeri->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina;

        return view('pembina.galeri.edit', compact('galeri', 'ekstrakurikulers'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        // Pastikan galeri milik ekstrakurikuler yang dibina user
        if ($galeri->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
        ]);

        $user = Auth::user();

        // Pastikan ekstrakurikuler milik pembina
        $ekstrakurikuler = $user->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            abort(403, 'Unauthorized action.');
        }

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
        ];

        // Handle file upload if new file provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($galeri->path_file) {
                Storage::disk('public')->delete($galeri->path_file);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Determine file type
            $mimeType = $file->getMimeType();
            $tipe = str_starts_with($mimeType, 'video/') ? 'video' : 'gambar';

            // Store file
            $path = $file->storeAs('galeri', $filename, 'public');

            $data['path_file'] = $path;
            $data['tipe'] = $tipe;
        }

        $galeri->update($data);

        return redirect()->route('pembina.galeri.index')
            ->with('success', 'Galeri berhasil diperbarui!');
    }

    public function destroy(Galeri $galeri)
    {
        // Pastikan galeri milik ekstrakurikuler yang dibina user
        if ($galeri->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete file
        if ($galeri->path_file) {
            Storage::disk('public')->delete($galeri->path_file);
        }

        $galeri->delete();

        return redirect()->route('pembina.galeri.index')
            ->with('success', 'File galeri berhasil dihapus!');
    }

    public function download(Galeri $galeri)
    {
        // Pastikan galeri ini milik ekstrakurikuler yang dibina oleh user
        if ($galeri->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah file ada di storage
        if (!Storage::disk('public')->exists($galeri->path_file)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Lakukan proses download
        return Storage::disk('public')->download($galeri->path_file, $galeri->judul . '.' . pathinfo($galeri->path_file, PATHINFO_EXTENSION));
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480',
            'judul_prefix' => 'nullable|string|max:100'
        ]);

        $user = Auth::user();

        // Pastikan ekstrakurikuler milik pembina
        $ekstrakurikuler = $user->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            abort(403, 'Unauthorized action.');
        }

        $uploaded = 0;
        $prefix = $request->judul_prefix ?: 'Dokumentasi';

        foreach ($request->file('files') as $index => $file) {
            $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();

            // Determine file type
            $mimeType = $file->getMimeType();
            $tipe = str_starts_with($mimeType, 'video/') ? 'video' : 'gambar';

            // Store file
            $path = $file->storeAs('galeri', $filename, 'public');

            Galeri::create([
                'judul' => $prefix . ' ' . ($index + 1),
                'deskripsi' => 'Upload massal pada ' . now()->format('d M Y'),
                'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
                'path_file' => $path,
                'tipe' => $tipe,
                'diupload_oleh' => $user->id
            ]);

            $uploaded++;
        }

        return redirect()->route('pembina.galeri.index')
            ->with('success', "Berhasil mengupload {$uploaded} file!");
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            $galeri = Galeri::whereIn('id', $ids)
                ->where('pembina_id', Auth::id())
                ->get();

            foreach ($galeri as $item) {
                Storage::disk('public')->delete($item->media);
                $item->delete();
            }

            return redirect()->route('pembina.galeri.index')->with('success', 'Foto yang dipilih berhasil dihapus.');
        }
        return redirect()->route('pembina.galeri.index')->with('error', 'Tidak ada foto yang dipilih.');
    }
}
