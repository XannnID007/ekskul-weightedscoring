<?php
// app/Http/Controllers/Siswa/GaleriController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GaleriController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek apakah siswa sudah terdaftar ekstrakurikuler
        if (!$user->sudahTerdaftarEkstrakurikuler()) {
            return redirect()->route('siswa.dashboard')
                ->with('warning', 'Anda belum terdaftar pada ekstrakurikuler. Daftar terlebih dahulu untuk melihat galeri kegiatan.');
        }

        // Ambil ekstrakurikuler yang diikuti siswa
        $pendaftaran = $user->pendaftarans()->where('status', 'disetujui')->with('ekstrakurikuler')->first();
        $ekstrakurikuler = $pendaftaran->ekstrakurikuler;

        // Ambil galeri dari ekstrakurikuler yang diikuti
        $galeris = Galeri::where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('siswa.galeri.index', compact('galeris', 'ekstrakurikuler'));
    }

    public function show(Galeri $galeri)
    {
        $user = Auth::user();

        // Cek apakah siswa sudah terdaftar ekstrakurikuler
        if (!$user->sudahTerdaftarEkstrakurikuler()) {
            abort(403, 'Anda belum terdaftar pada ekstrakurikuler.');
        }

        // Ambil ekstrakurikuler yang diikuti siswa
        $pendaftaran = $user->pendaftarans()->where('status', 'disetujui')->with('ekstrakurikuler')->first();
        $ekstrakurikuler = $pendaftaran->ekstrakurikuler;

        // Pastikan galeri milik ekstrakurikuler yang diikuti siswa
        if ($galeri->ekstrakurikuler_id !== $ekstrakurikuler->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat galeri ini.');
        }

        $galeri->load(['ekstrakurikuler', 'uploader']);

        // Ambil galeri lainnya dari ekstrakurikuler yang sama
        $galeriLainnya = Galeri::where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('id', '!=', $galeri->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('siswa.galeri.show', compact('galeri', 'ekstrakurikuler', 'galeriLainnya'));
    }
}
