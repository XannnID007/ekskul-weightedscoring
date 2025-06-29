<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        $ekstrakurikulers = Ekstrakurikuler::with('pembina')->paginate(10);
        return view('admin.ekstrakurikuler.index', compact('ekstrakurikulers'));
    }

    public function create()
    {
        $pembinas = User::pembina()->get();
        $kategori_options = [
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'akademik' => 'Akademik',
            'teknologi' => 'Teknologi',
            'bahasa' => 'Bahasa',
            'kepemimpinan' => 'Kepemimpinan',
            'budaya' => 'Budaya',
            'media' => 'Media'
        ];

        return view('admin.ekstrakurikuler.create', compact('pembinas', 'kategori_options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kapasitas_maksimal' => 'required|integer|min:1',
            'hari' => 'required|string',
            'waktu' => 'required|string',
            'kategori' => 'required|array',
            'nilai_minimal' => 'required|numeric|min:0|max:100',
            'pembina_id' => 'required|exists:users,id',
        ]);

        $data = $request->all();
        $data['jadwal'] = [
            'hari' => $request->hari,
            'waktu' => $request->waktu
        ];

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Simpan file tanpa resize - langsung store
            $path = $image->storeAs('ekstrakurikuler', $filename, 'public');
            $data['gambar'] = $path;
        }

        Ekstrakurikuler::create($data);

        return redirect()->route('admin.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan!');
    }

    public function show(Ekstrakurikuler $ekstrakurikuler)
    {
        // Load relasi wajib
        $ekstrakurikuler->load(['pembina', 'pendaftarans.user']);

        // Load relasi optional dengan pengecehan
        $relationships = [];

        // Cek jika tabel pengumumans ada
        if (Schema::hasTable('pengumumans')) {
            $relationships[] = 'pengumumans';
        }

        // Cek jika tabel galeris ada
        if (Schema::hasTable('galeris')) {
            $relationships[] = 'galeris';
        }

        // Load relasi yang tersedia
        if (!empty($relationships)) {
            $ekstrakurikuler->load($relationships);
        }

        return view('admin.ekstrakurikuler.show', compact('ekstrakurikuler'));
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler)
    {
        $pembinas = User::pembina()->get();
        $kategori_options = [
            'olahraga' => 'Olahraga',
            'seni' => 'Seni',
            'akademik' => 'Akademik',
            'teknologi' => 'Teknologi',
            'bahasa' => 'Bahasa',
            'kepemimpinan' => 'Kepemimpinan',
            'budaya' => 'Budaya',
            'media' => 'Media'
        ];

        return view('admin.ekstrakurikuler.edit', compact('ekstrakurikuler', 'pembinas', 'kategori_options'));
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kapasitas_maksimal' => 'required|integer|min:1',
            'hari' => 'required|string',
            'waktu' => 'required|string',
            'kategori' => 'required|array',
            'nilai_minimal' => 'required|numeric|min:0|max:100',
            'pembina_id' => 'required|exists:users,id',
        ]);

        $data = $request->all();
        $data['jadwal'] = [
            'hari' => $request->hari,
            'waktu' => $request->waktu
        ];

        // Handle file upload if new file provided
        if ($request->hasFile('gambar')) {
            // Delete old file
            if ($ekstrakurikuler->gambar) {
                Storage::disk('public')->delete($ekstrakurikuler->gambar);
            }

            $image = $request->file('gambar');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Simpan file tanpa resize - langsung store
            $path = $image->storeAs('ekstrakurikuler', $filename, 'public');
            $data['gambar'] = $path;
        }

        $ekstrakurikuler->update($data);

        return redirect()->route('admin.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil diperbarui!');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler)
    {
        if ($ekstrakurikuler->gambar) {
            Storage::disk('public')->delete($ekstrakurikuler->gambar);
        }

        $ekstrakurikuler->delete();

        return redirect()->route('admin.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif ekstrakurikuler
     */
    public function toggleStatus(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->update([
            'is_active' => $request->boolean('is_active')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'is_active' => $ekstrakurikuler->is_active
        ]);
    }
}
