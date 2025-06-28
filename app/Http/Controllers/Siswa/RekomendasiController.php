<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    protected $rekomendasiService;

    public function __construct(RekomendasiService $rekomendasiService)
    {
        $this->rekomendasiService = $rekomendasiService;
    }

    public function index()
    {
        $user = Auth::user();

        // Cek apakah sudah terdaftar ekstrakurikuler
        if ($user->sudahTerdaftarEkstrakurikuler()) {
            return redirect()->route('siswa.dashboard')
                ->with('info', 'Anda sudah terdaftar pada ekstrakurikuler. Sistem rekomendasi tidak tersedia.');
        }

        // Cek kelengkapan profil
        $profilCheck = $this->rekomendasiService->cekKelengkapanProfil($user);

        if (!$profilCheck['lengkap']) {
            return redirect()->route('siswa.profil')
                ->with('warning', 'Lengkapi profil Anda terlebih dahulu untuk mendapatkan rekomendasi yang akurat.');
        }

        // Generate rekomendasi
        $this->rekomendasiService->generateRekomendasi($user);

        // Ambil rekomendasi
        $rekomendasis = $user->rekomendasis()
            ->with('ekstrakurikuler.pembina')
            ->orderBy('total_skor', 'desc')
            ->get();

        return view('siswa.rekomendasi.index', compact('rekomendasis', 'profilCheck'));
    }

    public function regenerate()
    {
        $user = Auth::user();

        // Generate ulang rekomendasi
        $this->rekomendasiService->generateRekomendasi($user);

        return redirect()->route('siswa.rekomendasi')
            ->with('success', 'Rekomendasi berhasil diperbarui!');
    }

    public function detail($id)
    {
        $rekomendasi = Auth::user()->rekomendasis()
            ->with(['ekstrakurikuler.pembina', 'ekstrakurikuler.galeris'])
            ->findOrFail($id);

        return view('siswa.rekomendasi.detail', compact('rekomendasi'));
    }
}
