<?php
// app/Http/Controllers/Siswa/PengumumanController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek apakah siswa sudah terdaftar ekstrakurikuler
        if (!$user->sudahTerdaftarEkstrakurikuler()) {
            return redirect()->route('siswa.dashboard')
                ->with('warning', 'Anda belum terdaftar pada ekstrakurikuler. Daftar terlebih dahulu untuk melihat pengumuman.');
        }

        // Ambil ekstrakurikuler yang diikuti siswa
        $pendaftaran = $user->pendaftarans()->where('status', 'disetujui')->with('ekstrakurikuler')->first();
        $ekstrakurikuler = $pendaftaran->ekstrakurikuler;

        // Ambil pengumuman dari ekstrakurikuler yang diikuti
        $pengumumans = Pengumuman::where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->with(['pembuat'])
            ->orderBy('is_penting', 'desc') // Pengumuman penting di atas
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Hitung pengumuman penting yang belum dibaca (bisa dikembangkan dengan read status)
        $pengumumanPenting = Pengumuman::where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('is_penting', true)
            ->count();

        return view('siswa.pengumuman.index', compact('pengumumans', 'ekstrakurikuler', 'pengumumanPenting'));
    }

    public function show(Pengumuman $pengumuman)
    {
        $user = Auth::user();

        // Cek apakah siswa sudah terdaftar ekstrakurikuler
        if (!$user->sudahTerdaftarEkstrakurikuler()) {
            abort(403, 'Anda belum terdaftar pada ekstrakurikuler.');
        }

        // Ambil ekstrakurikuler yang diikuti siswa
        $pendaftaran = $user->pendaftarans()->where('status', 'disetujui')->with('ekstrakurikuler')->first();
        $ekstrakurikuler = $pendaftaran->ekstrakurikuler;

        // Pastikan pengumuman milik ekstrakurikuler yang diikuti siswa
        if ($pengumuman->ekstrakurikuler_id !== $ekstrakurikuler->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengumuman ini.');
        }

        $pengumuman->load(['ekstrakurikuler', 'pembuat']);

        // Ambil pengumuman lainnya dari ekstrakurikuler yang sama
        $pengumumanLainnya = Pengumuman::where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('id', '!=', $pengumuman->id)
            ->orderBy('is_penting', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('siswa.pengumuman.show', compact('pengumuman', 'ekstrakurikuler', 'pengumumanLainnya'));
    }
}
