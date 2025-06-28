<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $rekomendasiService;

    public function __construct(RekomendasiService $rekomendasiService)
    {
        $this->rekomendasiService = $rekomendasiService;
    }

    public function index()
    {
        $user = Auth::user();

        // Get user's top 3 recommendations if not yet registered
        $rekomendasis = null;
        if (!$user->sudahTerdaftarEkstrakurikuler()) {
            $profilCheck = $this->rekomendasiService->cekKelengkapanProfil($user);

            if ($profilCheck['lengkap']) {
                // Generate rekomendasi jika belum ada
                $this->rekomendasiService->generateRekomendasi($user);

                // Get top 3 recommendations
                $rekomendasis = $user->rekomendasis()
                    ->with('ekstrakurikuler.pembina')
                    ->orderBy('total_skor', 'desc')
                    ->limit(3)
                    ->get();
            }
        }

        return view('siswa.dashboard', compact('rekomendasis'));
    }

    public function getNotifikasi()
    {
        $user = Auth::user();

        // Mock notifications for now
        $notifikasi = [
            [
                'id' => 1,
                'title' => 'Pendaftaran Disetujui',
                'message' => 'Selamat! Pendaftaran Anda pada ekstrakurikuler Futsal telah disetujui.',
                'created_at' => now()->subMinutes(30),
                'read' => false
            ],
            [
                'id' => 2,
                'title' => 'Pengumuman Baru',
                'message' => 'Ada pengumuman baru dari pembina ekstrakurikuler Anda.',
                'created_at' => now()->subHours(2),
                'read' => false
            ],
            [
                'id' => 3,
                'title' => 'Jadwal Berubah',
                'message' => 'Jadwal latihan hari Senin dipindah ke hari Selasa.',
                'created_at' => now()->subHours(5),
                'read' => true
            ]
        ];

        return response()->json($notifikasi);
    }

    public function markAsRead($id)
    {
        // Implementation for marking notification as read
        // For now just return success
        return response()->json(['success' => true]);
    }
}
