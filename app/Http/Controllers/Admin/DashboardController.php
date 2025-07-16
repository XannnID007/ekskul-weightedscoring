<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_siswa' => User::siswa()->count(),
            'total_pembina' => User::pembina()->count(),
            'total_ekstrakurikuler' => Ekstrakurikuler::count(),
            'total_pendaftaran' => Pendaftaran::count(),
            'pendaftaran_pending' => Pendaftaran::pending()->count(),
            'pendaftaran_disetujui' => Pendaftaran::disetujui()->count(),
            'siswa_baru_hari_ini' => User::siswa()->whereDate('created_at', today())->count(),
            'profil_belum_lengkap' => User::siswa()->whereNull('minat')->count(),
        ];

        // Data untuk chart ekstrakurikuler paling diminati
        $ekstrakurikuler_populer = Ekstrakurikuler::withCount(['pendaftarans as total_pendaftar'])
            ->orderBy('total_pendaftar', 'desc')
            ->limit(5)
            ->get();

        // Data pendaftaran per bulan (6 bulan terakhir)
        $pendaftaran_bulanan = Pendaftaran::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.dashboard', compact('stats', 'ekstrakurikuler_populer', 'pendaftaran_bulanan'));
    }
}
