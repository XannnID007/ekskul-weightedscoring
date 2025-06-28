<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina()->with(['pendaftarans.user'])->get();

        $stats = [
            'total_ekstrakurikuler' => $ekstrakurikulers->count(),
            'total_siswa' => $ekstrakurikulers->sum('peserta_saat_ini'),
            'pendaftaran_pending' => Pendaftaran::whereHas('ekstrakurikuler', function ($query) use ($user) {
                $query->where('pembina_id', $user->id);
            })->pending()->count(),
            'total_pendaftaran' => Pendaftaran::whereHas('ekstrakurikuler', function ($query) use ($user) {
                $query->where('pembina_id', $user->id);
            })->count(),
        ];

        // Jadwal kegiatan minggu ini
        $jadwal_minggu_ini = $ekstrakurikulers->map(function ($ekskul) {
            return [
                'nama' => $ekskul->nama,
                'jadwal' => $ekskul->jadwal_string,
                'peserta' => $ekskul->peserta_saat_ini,
                'kapasitas' => $ekskul->kapasitas_maksimal
            ];
        });

        return view('pembina.dashboard', compact('stats', 'ekstrakurikulers', 'jadwal_minggu_ini'));
    }
}
