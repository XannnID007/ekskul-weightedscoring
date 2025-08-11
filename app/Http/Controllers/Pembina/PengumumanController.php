<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pembina = Auth::user();
        $ekstrakurikulerIds = $pembina->ekstrakurikulerSebagaiPembina()->pluck('id');

        $pengumuman = Pengumuman::whereIn('ekstrakurikuler_id', $ekstrakurikulerIds)
            ->with('ekstrakurikuler')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pembina.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        $pembina = Auth::user();
        $ekstrakurikulerPilihan = $pembina->ekstrakurikulerSebagaiPembina;

        // Jika pembina tidak membina ekskul apapun, jangan biarkan mereka membuat pengumuman
        if ($ekstrakurikulerPilihan->isEmpty()) {
            return redirect()->route('pembina.pengumuman.index')
                ->with('error', 'Anda harus menjadi pembina setidaknya satu ekstrakurikuler untuk membuat pengumuman.');
        }

        return view('pembina.pengumuman.create', compact('ekstrakurikulerPilihan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string', // Pastikan ini 'konten'
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'is_penting' => 'boolean'
        ]);

        $user = Auth::user();

        // Pastikan ekstrakurikuler milik pembina
        $ekstrakurikuler = $user->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            abort(403, 'Unauthorized action.');
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten, // Dan pastikan ini juga 'konten'
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
            'dibuat_oleh' => $user->id,
            'is_penting' => $request->boolean('is_penting')
        ]);

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function show(Pengumuman $pengumuman)
    {
        $pembinaEkstrakurikulerIds = Auth::user()->ekstrakurikulerSebagaiPembina()->pluck('id');
        if (!$pembinaEkstrakurikulerIds->contains($pengumuman->ekstrakurikuler_id)) {
            abort(403, 'AKSES DITOLAK.');
        }

        // Ambil juga pengumuman lainnya untuk ditampilkan di sidebar
        $pengumumanLainnya = Pengumuman::whereIn('ekstrakurikuler_id', $pembinaEkstrakurikulerIds)
            ->where('id', '!=', $pengumuman->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pembina.pengumuman.show', compact('pengumuman', 'pengumumanLainnya'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        // Otorisasi: Pastikan ini pengumuman dari ekskul yang dibina
        $pembinaEkstrakurikulerIds = Auth::user()->ekstrakurikulerSebagaiPembina()->pluck('id');
        if (!$pembinaEkstrakurikulerIds->contains($pengumuman->ekstrakurikuler_id)) {
            abort(403, 'AKSES DITOLAK.');
        }

        // Ambil daftar ekskul untuk pilihan dropdown
        $ekstrakurikulerPilihan = Auth::user()->ekstrakurikulerSebagaiPembina;

        return view('pembina.pengumuman.edit', compact('pengumuman', 'ekstrakurikulerPilihan'));
    }

    /**
     * Memperbarui pengumuman di database.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        // Otorisasi: Pastikan ini pengumuman dari ekskul yang dibina
        $pembinaEkstrakurikulerIds = Auth::user()->ekstrakurikulerSebagaiPembina()->pluck('id');
        if (!$pembinaEkstrakurikulerIds->contains($pengumuman->ekstrakurikuler_id)) {
            abort(403, 'AKSES DITOLAK.');
        }

        $request->validate([
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id',
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'is_penting' => 'boolean'
        ]);

        // Otorisasi lagi untuk ekskul tujuan jika diubah
        if (!$pembinaEkstrakurikulerIds->contains($request->ekstrakurikuler_id)) {
            return redirect()->back()->with('error', 'Anda tidak berhak memindahkan pengumuman ke ekstrakurikuler tersebut.');
        }

        $pengumuman->update([
            'ekstrakurikuler_id' => $request->ekstrakurikuler_id,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'is_penting' => $request->boolean('is_penting'),
        ]);

        return redirect()->route('pembina.pengumuman.show', $pengumuman)
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        // Otorisasi: Pastikan pembina hanya bisa menghapus pengumuman dari ekskul yang dibinanya
        $pembinaEkstrakurikulerIds = Auth::user()->ekstrakurikulerSebagaiPembina()->pluck('id');
        if (!$pembinaEkstrakurikulerIds->contains($pengumuman->ekstrakurikuler_id)) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $pengumuman->delete();

        return redirect()->route('pembina.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
