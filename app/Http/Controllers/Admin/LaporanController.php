<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use App\Models\Ekstrakurikuler;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $data = [
            'stats' => $this->getOverallStats(),
            'pendaftaran_bulanan' => $this->getPendaftaranBulanan(),
            'top_ekstrakurikuler' => $this->getTopEkstrakurikuler(),
            'distribusi_gender' => $this->getDistribusiGender(),
            'distribusi_nilai' => $this->getDistribusiNilai(),
            'kategori_stats' => $this->getKategoriStats()
        ];

        return view('admin.laporan.index', $data);
    }

    public function export(Request $request)
    {
        $request->validate([
            'type' => 'nullable|string|in:all,siswa,ekstrakurikuler,pendaftaran,rekomendasi',
            'format' => 'nullable|string|in:excel,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $type = $request->input('type', 'all');
        $format = $request->input('format', 'excel');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        try {
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            if ($format === 'pdf') {
                return $this->exportPdf($type, $startDate, $endDate);
            } else {
                return $this->exportExcel($type, $startDate, $endDate);
            }
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    private function exportExcel($type, $startDate, $endDate)
    {
        $filename = 'laporan_' . $type . '_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.xlsx';
        return Excel::download(new LaporanExport($type, $startDate, $endDate), $filename);
    }

    private function exportPdf($type, $startDate, $endDate)
    {
        $data = $this->getPdfData($type, $startDate, $endDate);
        $filename = 'laporan_' . $type . '_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('admin.laporan.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename);
    }

    private function getPdfData($type, $startDate, $endDate)
    {
        $data = [
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'stats' => $this->getOverallStats()
        ];

        switch ($type) {
            case 'siswa':
                $data['siswa'] = User::siswa()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with(['pendaftarans.ekstrakurikuler'])
                    ->limit(100)
                    ->get();
                break;

            case 'ekstrakurikuler':
                $data['ekstrakurikulers'] = Ekstrakurikuler::with(['pembina', 'pendaftarans'])
                    ->withCount(['pendaftarans as total_pendaftar'])
                    ->get();
                break;

            case 'pendaftaran':
                $data['pendaftarans'] = Pendaftaran::with(['user', 'ekstrakurikuler.pembina'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'desc')
                    ->limit(200)
                    ->get();
                break;

            case 'rekomendasi':
                $data['rekomendasis'] = Rekomendasi::with(['user', 'ekstrakurikuler'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('total_skor', 'desc')
                    ->limit(100)
                    ->get();
                break;

            case 'all':
            default:
                $data['siswa'] = User::siswa()->with(['pendaftarans.ekstrakurikuler'])->limit(50)->get();
                $data['ekstrakurikulers'] = Ekstrakurikuler::with(['pembina'])->withCount('pendaftarans')->limit(20)->get();
                $data['pendaftarans'] = Pendaftaran::with(['user', 'ekstrakurikuler'])->whereBetween('created_at', [$startDate, $endDate])->limit(50)->get();
                $data['rekomendasis'] = Rekomendasi::with(['user', 'ekstrakurikuler'])->limit(30)->get();
                break;
        }

        return $data;
    }

    public function chartData(Request $request)
    {
        $period = $request->get('period', '6months');

        if ($period === '1year') {
            $data = $this->getPendaftaranTahunan();
        } else {
            $data = $this->getPendaftaranBulanan(6);
        }

        return response()->json($data);
    }

    private function getPendaftaranTahunan()
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');

            $count = Pendaftaran::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getOverallStats()
    {
        $totalSiswa = User::siswa()->count();
        $totalEkstrakurikuler = Ekstrakurikuler::count();
        $totalPendaftaran = Pendaftaran::count();
        $siswaAktif = Pendaftaran::disetujui()->distinct('user_id')->count();
        $totalPembina = User::pembina()->count();

        return [
            'total_siswa' => $totalSiswa,
            'total_pembina' => $totalPembina,
            'total_ekstrakurikuler' => $totalEkstrakurikuler,
            'total_pendaftaran' => $totalPendaftaran,
            'partisipasi_persen' => $totalSiswa > 0 ? round(($siswaAktif / $totalSiswa) * 100, 1) : 0,
            'pendaftaran_pending' => Pendaftaran::pending()->count(),
            'pendaftaran_disetujui' => Pendaftaran::disetujui()->count(),
            'pendaftaran_ditolak' => Pendaftaran::ditolak()->count(),
            'siswa_baru_hari_ini' => User::siswa()->whereDate('created_at', today())->count(),
            'profil_belum_lengkap' => User::siswa()->whereNull('minat')->count(),
        ];
    }

    private function getPendaftaranBulanan($months = 6)
    {
        $data = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');

            $count = Pendaftaran::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getTopEkstrakurikuler($limit = 5)
    {
        return Ekstrakurikuler::withCount(['pendaftarans as total_pendaftar'])
            ->with('pembina:id,name')
            ->orderBy('total_pendaftar', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->nama,
                    'pembina' => $item->pembina->name ?? '-',
                    'total_pendaftar' => $item->total_pendaftar
                ];
            });
    }

    private function getDistribusiGender()
    {
        $total = User::siswa()->count();
        $lakiLaki = User::siswa()->where('jenis_kelamin', 'L')->count();
        $perempuan = User::siswa()->where('jenis_kelamin', 'P')->count();
        $belumIsi = $total - $lakiLaki - $perempuan;

        return [
            'total' => $total,
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'belum_isi' => $belumIsi,
            'laki_laki_persen' => $total > 0 ? round(($lakiLaki / $total) * 100, 1) : 0,
            'perempuan_persen' => $total > 0 ? round(($perempuan / $total) * 100, 1) : 0,
            'belum_isi_persen' => $total > 0 ? round(($belumIsi / $total) * 100, 1) : 0
        ];
    }

    private function getDistribusiNilai()
    {
        $total = User::siswa()->whereNotNull('nilai_rata_rata')->count();
        $tinggi = User::siswa()->where('nilai_rata_rata', '>=', 80)->count();
        $baik = User::siswa()->whereBetween('nilai_rata_rata', [70, 79.9])->count();
        $cukup = User::siswa()->where('nilai_rata_rata', '<', 70)->count();

        return [
            'total' => $total,
            'tinggi' => $tinggi,
            'baik' => $baik,
            'cukup' => $cukup,
            'tinggi_persen' => $total > 0 ? round(($tinggi / $total) * 100, 1) : 0,
            'baik_persen' => $total > 0 ? round(($baik / $total) * 100, 1) : 0,
            'cukup_persen' => $total > 0 ? round(($cukup / $total) * 100, 1) : 0
        ];
    }

    private function getKategoriStats()
    {
        $stats = [];
        $ekstrakurikulers = Ekstrakurikuler::all();

        foreach ($ekstrakurikulers as $ekskul) {
            if ($ekskul->kategori && is_array($ekskul->kategori)) {
                foreach ($ekskul->kategori as $kategori) {
                    $stats[$kategori] = ($stats[$kategori] ?? 0) + 1;
                }
            }
        }

        arsort($stats);
        return array_slice($stats, 0, 8, true);
    }
}
