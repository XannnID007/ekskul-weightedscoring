<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\Absensi;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // Data untuk chart dan statistik
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

    public function export(Request $request, $type = 'all')
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        switch ($type) {
            case 'siswa':
                return $this->exportSiswa($startDate, $endDate);
            case 'ekstrakurikuler':
                return $this->exportEkstrakurikuler($startDate, $endDate);
            case 'pendaftaran':
                return $this->exportPendaftaran($startDate, $endDate);
            case 'kehadiran':
                return $this->exportKehadiran($startDate, $endDate);
            case 'rekomendasi':
                return $this->exportRekomendasi($startDate, $endDate);
            case 'all':
                return $this->exportAll($startDate, $endDate);
            default:
                return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }
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

    private function getOverallStats()
    {
        $totalSiswa = User::siswa()->count();
        $totalEkstrakurikuler = Ekstrakurikuler::count();
        $totalPendaftaran = Pendaftaran::count();
        $siswaAktif = Pendaftaran::disetujui()->distinct('user_id')->count();

        return [
            'total_siswa' => $totalSiswa,
            'total_ekstrakurikuler' => $totalEkstrakurikuler,
            'total_pendaftaran' => $totalPendaftaran,
            'partisipasi_persen' => $totalSiswa > 0 ? round(($siswaAktif / $totalSiswa) * 100, 1) : 0,
            'pendaftaran_pending' => Pendaftaran::pending()->count(),
            'pendaftaran_disetujui' => Pendaftaran::disetujui()->count(),
            'pendaftaran_ditolak' => Pendaftaran::ditolak()->count()
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

    private function exportSiswa($startDate, $endDate)
    {
        $siswa = User::siswa()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['pendaftarans.ekstrakurikuler'])
            ->get();

        // Generate CSV
        $filename = 'laporan_siswa_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($siswa) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Nama',
                'Email',
                'NIS',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Nilai Rata-rata',
                'Alamat',
                'Telepon',
                'Status Ekstrakurikuler',
                'Ekstrakurikuler Diikuti',
                'Tanggal Daftar'
            ]);

            // Data CSV
            foreach ($siswa as $s) {
                $pendaftaran = $s->pendaftarans()->where('status', 'disetujui')->first();

                fputcsv($file, [
                    $s->name,
                    $s->email,
                    $s->nis ?? '-',
                    $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
                    $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '-',
                    $s->nilai_rata_rata ?? '-',
                    $s->alamat ?? '-',
                    $s->telepon ?? '-',
                    $pendaftaran ? 'Terdaftar' : 'Belum Terdaftar',
                    $pendaftaran ? $pendaftaran->ekstrakurikuler->nama : '-',
                    $s->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportEkstrakurikuler($startDate, $endDate)
    {
        $ekstrakurikulers = Ekstrakurikuler::with(['pembina', 'pendaftarans'])
            ->withCount(['pendaftarans as total_pendaftar'])
            ->get();

        $filename = 'laporan_ekstrakurikuler_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($ekstrakurikulers) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Nama Ekstrakurikuler',
                'Pembina',
                'Kategori',
                'Kapasitas Maksimal',
                'Peserta Saat Ini',
                'Total Pendaftar',
                'Jadwal',
                'Nilai Minimal',
                'Status',
                'Tingkat Okupansi (%)'
            ]);

            // Data CSV
            foreach ($ekstrakurikulers as $ekskul) {
                $kategori = is_array($ekskul->kategori) ? implode(', ', $ekskul->kategori) : '';
                $okupansi = $ekskul->kapasitas_maksimal > 0 ?
                    round(($ekskul->peserta_saat_ini / $ekskul->kapasitas_maksimal) * 100, 1) : 0;

                fputcsv($file, [
                    $ekskul->nama,
                    $ekskul->pembina->name ?? '-',
                    $kategori,
                    $ekskul->kapasitas_maksimal,
                    $ekskul->peserta_saat_ini,
                    $ekskul->total_pendaftar,
                    $ekskul->jadwal_string,
                    $ekskul->nilai_minimal,
                    $ekskul->is_active ? 'Aktif' : 'Nonaktif',
                    $okupansi . '%'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPendaftaran($startDate, $endDate)
    {
        $pendaftarans = Pendaftaran::with(['user', 'ekstrakurikuler.pembina'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan_pendaftaran_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($pendaftarans) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Tanggal Daftar',
                'Nama Siswa',
                'Email',
                'NIS',
                'Ekstrakurikuler',
                'Pembina',
                'Status',
                'Motivasi',
                'Tingkat Komitmen',
                'Tanggal Disetujui',
                'Alasan Penolakan'
            ]);

            // Data CSV
            foreach ($pendaftarans as $pendaftaran) {
                fputcsv($file, [
                    $pendaftaran->created_at->format('d/m/Y H:i'),
                    $pendaftaran->user->name,
                    $pendaftaran->user->email,
                    $pendaftaran->user->nis ?? '-',
                    $pendaftaran->ekstrakurikuler->nama,
                    $pendaftaran->ekstrakurikuler->pembina->name ?? '-',
                    ucfirst($pendaftaran->status),
                    $pendaftaran->motivasi,
                    ucfirst($pendaftaran->tingkat_komitmen ?? '-'),
                    $pendaftaran->disetujui_pada ? $pendaftaran->disetujui_pada->format('d/m/Y H:i') : '-',
                    $pendaftaran->alasan_penolakan ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportKehadiran($startDate, $endDate)
    {
        $absensis = Absensi::with(['pendaftaran.user', 'pendaftaran.ekstrakurikuler'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = 'laporan_kehadiran_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($absensis) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Tanggal',
                'Nama Siswa',
                'NIS',
                'Ekstrakurikuler',
                'Status Kehadiran',
                'Catatan',
                'Dicatat Oleh'
            ]);

            // Data CSV
            foreach ($absensis as $absensi) {
                fputcsv($file, [
                    $absensi->tanggal->format('d/m/Y'),
                    $absensi->pendaftaran->user->name,
                    $absensi->pendaftaran->user->nis ?? '-',
                    $absensi->pendaftaran->ekstrakurikuler->nama,
                    ucfirst($absensi->status),
                    $absensi->catatan ?? '-',
                    $absensi->pencatat->name ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportRekomendasi($startDate, $endDate)
    {
        $rekomendasis = Rekomendasi::with(['user', 'ekstrakurikuler'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('total_skor', 'desc')
            ->get();

        $filename = 'laporan_rekomendasi_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rekomendasis) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Nama Siswa',
                'Email',
                'NIS',
                'Ekstrakurikuler',
                'Skor Minat',
                'Skor Akademik',
                'Skor Jadwal',
                'Total Skor',
                'Alasan',
                'Tanggal Generate'
            ]);

            // Data CSV
            foreach ($rekomendasis as $rekomendasi) {
                fputcsv($file, [
                    $rekomendasi->user->name,
                    $rekomendasi->user->email,
                    $rekomendasi->user->nis ?? '-',
                    $rekomendasi->ekstrakurikuler->nama,
                    $rekomendasi->skor_minat,
                    $rekomendasi->skor_akademik,
                    $rekomendasi->skor_jadwal,
                    $rekomendasi->total_skor,
                    $rekomendasi->alasan,
                    $rekomendasi->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportAll($startDate, $endDate)
    {
        // Create a ZIP file containing all reports
        $zipFileName = 'laporan_lengkap_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            // Add each report to ZIP
            $reports = [
                'siswa' => $this->generateCSVContent('siswa', $startDate, $endDate),
                'ekstrakurikuler' => $this->generateCSVContent('ekstrakurikuler', $startDate, $endDate),
                'pendaftaran' => $this->generateCSVContent('pendaftaran', $startDate, $endDate),
                'kehadiran' => $this->generateCSVContent('kehadiran', $startDate, $endDate),
                'rekomendasi' => $this->generateCSVContent('rekomendasi', $startDate, $endDate)
            ];

            foreach ($reports as $type => $content) {
                $zip->addFromString("laporan_{$type}.csv", $content);
            }

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Gagal membuat file laporan');
    }

    private function generateCSVContent($type, $startDate, $endDate)
    {
        ob_start();
        $file = fopen('php://output', 'w');

        switch ($type) {
            case 'siswa':
                $this->generateSiswaCSV($file, $startDate, $endDate);
                break;
            case 'ekstrakurikuler':
                $this->generateEkstrakurikulerCSV($file);
                break;
            case 'pendaftaran':
                $this->generatePendaftaranCSV($file, $startDate, $endDate);
                break;
            case 'kehadiran':
                $this->generateKehadiranCSV($file, $startDate, $endDate);
                break;
            case 'rekomendasi':
                $this->generateRekomendasiCSV($file, $startDate, $endDate);
                break;
        }

        fclose($file);
        return ob_get_clean();
    }

    private function generateSiswaCSV($file, $startDate, $endDate)
    {
        $siswa = User::siswa()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['pendaftarans.ekstrakurikuler'])
            ->get();

        fputcsv($file, [
            'Nama',
            'Email',
            'NIS',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Nilai Rata-rata',
            'Alamat',
            'Telepon',
            'Status Ekstrakurikuler',
            'Ekstrakurikuler Diikuti',
            'Tanggal Daftar'
        ]);

        foreach ($siswa as $s) {
            $pendaftaran = $s->pendaftarans()->where('status', 'disetujui')->first();

            fputcsv($file, [
                $s->name,
                $s->email,
                $s->nis ?? '-',
                $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
                $s->tanggal_lahir ? $s->tanggal_lahir->format('d/m/Y') : '-',
                $s->nilai_rata_rata ?? '-',
                $s->alamat ?? '-',
                $s->telepon ?? '-',
                $pendaftaran ? 'Terdaftar' : 'Belum Terdaftar',
                $pendaftaran ? $pendaftaran->ekstrakurikuler->nama : '-',
                $s->created_at->format('d/m/Y')
            ]);
        }
    }

    private function generateEkstrakurikulerCSV($file)
    {
        $ekstrakurikulers = Ekstrakurikuler::with(['pembina', 'pendaftarans'])
            ->withCount(['pendaftarans as total_pendaftar'])
            ->get();

        fputcsv($file, [
            'Nama Ekstrakurikuler',
            'Pembina',
            'Kategori',
            'Kapasitas Maksimal',
            'Peserta Saat Ini',
            'Total Pendaftar',
            'Jadwal',
            'Nilai Minimal',
            'Status',
            'Tingkat Okupansi (%)'
        ]);

        foreach ($ekstrakurikulers as $ekskul) {
            $kategori = is_array($ekskul->kategori) ? implode(', ', $ekskul->kategori) : '';
            $okupansi = $ekskul->kapasitas_maksimal > 0 ?
                round(($ekskul->peserta_saat_ini / $ekskul->kapasitas_maksimal) * 100, 1) : 0;

            fputcsv($file, [
                $ekskul->nama,
                $ekskul->pembina->name ?? '-',
                $kategori,
                $ekskul->kapasitas_maksimal,
                $ekskul->peserta_saat_ini,
                $ekskul->total_pendaftar,
                $ekskul->jadwal_string,
                $ekskul->nilai_minimal,
                $ekskul->is_active ? 'Aktif' : 'Nonaktif',
                $okupansi . '%'
            ]);
        }
    }

    private function generatePendaftaranCSV($file, $startDate, $endDate)
    {
        $pendaftarans = Pendaftaran::with(['user', 'ekstrakurikuler.pembina'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        fputcsv($file, [
            'Tanggal Daftar',
            'Nama Siswa',
            'Email',
            'NIS',
            'Ekstrakurikuler',
            'Pembina',
            'Status',
            'Motivasi',
            'Tingkat Komitmen',
            'Tanggal Disetujui',
            'Alasan Penolakan'
        ]);

        foreach ($pendaftarans as $pendaftaran) {
            fputcsv($file, [
                $pendaftaran->created_at->format('d/m/Y H:i'),
                $pendaftaran->user->name,
                $pendaftaran->user->email,
                $pendaftaran->user->nis ?? '-',
                $pendaftaran->ekstrakurikuler->nama,
                $pendaftaran->ekstrakurikuler->pembina->name ?? '-',
                ucfirst($pendaftaran->status),
                $pendaftaran->motivasi,
                ucfirst($pendaftaran->tingkat_komitmen ?? '-'),
                $pendaftaran->disetujui_pada ? $pendaftaran->disetujui_pada->format('d/m/Y H:i') : '-',
                $pendaftaran->alasan_penolakan ?? '-'
            ]);
        }
    }

    private function generateKehadiranCSV($file, $startDate, $endDate)
    {
        $absensis = Absensi::with(['pendaftaran.user', 'pendaftaran.ekstrakurikuler'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        fputcsv($file, [
            'Tanggal',
            'Nama Siswa',
            'NIS',
            'Ekstrakurikuler',
            'Status Kehadiran',
            'Catatan',
            'Dicatat Oleh'
        ]);

        foreach ($absensis as $absensi) {
            fputcsv($file, [
                $absensi->tanggal->format('d/m/Y'),
                $absensi->pendaftaran->user->name,
                $absensi->pendaftaran->user->nis ?? '-',
                $absensi->pendaftaran->ekstrakurikuler->nama,
                ucfirst($absensi->status),
                $absensi->catatan ?? '-',
                $absensi->pencatat->name ?? '-'
            ]);
        }
    }

    private function generateRekomendasiCSV($file, $startDate, $endDate)
    {
        $rekomendasis = Rekomendasi::with(['user', 'ekstrakurikuler'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('total_skor', 'desc')
            ->get();

        fputcsv($file, [
            'Nama Siswa',
            'Email',
            'NIS',
            'Ekstrakurikuler',
            'Skor Minat',
            'Skor Akademik',
            'Skor Jadwal',
            'Total Skor',
            'Alasan',
            'Tanggal Generate'
        ]);

        foreach ($rekomendasis as $rekomendasi) {
            fputcsv($file, [
                $rekomendasi->user->name,
                $rekomendasi->user->email,
                $rekomendasi->user->nis ?? '-',
                $rekomendasi->ekstrakurikuler->nama,
                $rekomendasi->skor_minat,
                $rekomendasi->skor_akademik,
                $rekomendasi->skor_jadwal,
                $rekomendasi->total_skor,
                $rekomendasi->alasan,
                $rekomendasi->created_at->format('d/m/Y H:i')
            ]);
        }
    }
}
