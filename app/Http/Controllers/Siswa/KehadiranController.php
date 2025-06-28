<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KehadiranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get pendaftaran yang disetujui
        $pendaftaran = $user->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler.pembina', 'absensis'])
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('siswa.dashboard')
                ->with('info', 'Anda belum terdaftar pada ekstrakurikuler manapun.');
        }

        // Get data kehadiran
        $absensis = $pendaftaran->absensis()
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        // Calculate statistics
        $stats = $this->calculateKehadiranStats($pendaftaran);

        // Get chart data untuk 6 bulan terakhir
        $chartData = $this->getKehadiranChartData($pendaftaran);

        return view('siswa.kehadiran.index', compact('pendaftaran', 'absensis', 'stats', 'chartData'));
    }

    public function export()
    {
        $user = Auth::user();

        $pendaftaran = $user->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler', 'absensis'])
            ->first();

        if (!$pendaftaran) {
            return redirect()->back()
                ->with('error', 'Data kehadiran tidak ditemukan.');
        }

        $filename = 'rekap_kehadiran_' . $pendaftaran->ekstrakurikuler->nama . '_' . $user->name . '_' . now()->format('Y-m-d') . '.pdf';

        // Generate PDF (implementation depends on your PDF library)
        // For now, we'll redirect back with success message
        return redirect()->back()
            ->with('success', 'Export kehadiran akan segera diproses.');
    }

    private function calculateKehadiranStats($pendaftaran)
    {
        $totalAbsensi = $pendaftaran->absensis()->count();

        if ($totalAbsensi === 0) {
            return [
                'total_pertemuan' => 0,
                'hadir' => 0,
                'izin' => 0,
                'terlambat' => 0,
                'alpa' => 0,
                'persentase_hadir' => 0,
                'persentase_izin' => 0,
                'persentase_terlambat' => 0,
                'persentase_alpa' => 0
            ];
        }

        $hadir = $pendaftaran->absensis()->where('status', 'hadir')->count();
        $izin = $pendaftaran->absensis()->where('status', 'izin')->count();
        $terlambat = $pendaftaran->absensis()->where('status', 'terlambat')->count();
        $alpa = $pendaftaran->absensis()->where('status', 'alpa')->count();

        return [
            'total_pertemuan' => $totalAbsensi,
            'hadir' => $hadir,
            'izin' => $izin,
            'terlambat' => $terlambat,
            'alpa' => $alpa,
            'persentase_hadir' => round(($hadir / $totalAbsensi) * 100, 1),
            'persentase_izin' => round(($izin / $totalAbsensi) * 100, 1),
            'persentase_terlambat' => round(($terlambat / $totalAbsensi) * 100, 1),
            'persentase_alpa' => round(($alpa / $totalAbsensi) * 100, 1)
        ];
    }

    private function getKehadiranChartData($pendaftaran)
    {
        $data = [];
        $labels = [];

        // Get data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $totalBulan = $pendaftaran->absensis()
                ->whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();

            $hadirBulan = $pendaftaran->absensis()
                ->whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->where('status', 'hadir')
                ->count();

            $persentase = $totalBulan > 0 ? round(($hadirBulan / $totalBulan) * 100, 1) : 0;
            $data[] = $persentase;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
