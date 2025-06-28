<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekstrakurikulerIds = $user->ekstrakurikulerSebagaiPembina()->pluck('id');

        $galeris = Galeri::with('ekstrakurikuler')
            ->whereIn('ekstrakurikuler_id', $ekstrakurikulerIds)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pembina.galeri.index', compact('galeris'));
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
}
