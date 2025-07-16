<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // Get user's ekstrakurikuler data if registered
        $pendaftaran = $user->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler.pembina', 'absensis'])
            ->first();

        $ekstrakurikuler = $pendaftaran ? $pendaftaran->ekstrakurikuler : null;

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

        // Get attendance statistics if registered
        $attendanceStats = null;
        $upcomingSchedule = [];

        if ($pendaftaran && $ekstrakurikuler) {
            $attendanceStats = $this->getAttendanceStats($pendaftaran);
            $upcomingSchedule = $this->getUpcomingSchedule($ekstrakurikuler);
        }

        return view('siswa.dashboard', compact(
            'rekomendasis',
            'ekstrakurikuler',
            'pendaftaran',
            'attendanceStats',
            'upcomingSchedule'
        ));
    }

    private function getAttendanceStats($pendaftaran)
    {
        $totalAbsensi = $pendaftaran->absensis()->count();

        if ($totalAbsensi === 0) {
            return [
                'total_kegiatan' => 0,
                'hadir' => 0,
                'izin' => 0,
                'terlambat' => 0,
                'alpa' => 0,
                'persentase_hadir' => 0
            ];
        }

        $hadir = $pendaftaran->absensis()->where('status', 'hadir')->count();
        $izin = $pendaftaran->absensis()->where('status', 'izin')->count();
        $terlambat = $pendaftaran->absensis()->where('status', 'terlambat')->count();
        $alpa = $pendaftaran->absensis()->where('status', 'alpa')->count();

        return [
            'total_kegiatan' => $totalAbsensi,
            'hadir' => $hadir,
            'izin' => $izin,
            'terlambat' => $terlambat,
            'alpa' => $alpa,
            'persentase_hadir' => round(($hadir / $totalAbsensi) * 100)
        ];
    }

    private function getUpcomingSchedule($ekstrakurikuler)
    {
        $schedule = [];
        $today = Carbon::today();

        // Check if there's activity in the next 7 days
        if ($ekstrakurikuler->jadwal && isset($ekstrakurikuler->jadwal['hari'])) {
            $hari = $ekstrakurikuler->jadwal['hari'];
            $waktu = $ekstrakurikuler->jadwal['waktu'] ?? '15:00 - 17:00';

            $dayMap = [
                'senin' => Carbon::MONDAY,
                'selasa' => Carbon::TUESDAY,
                'rabu' => Carbon::WEDNESDAY,
                'kamis' => Carbon::THURSDAY,
                'jumat' => Carbon::FRIDAY,
                'sabtu' => Carbon::SATURDAY,
                'minggu' => Carbon::SUNDAY,
            ];

            if (isset($dayMap[strtolower($hari)])) {
                $current = $today->copy();

                // Find next 2 occurrences
                $found = 0;
                for ($i = 0; $i < 14 && $found < 2; $i++) {
                    if ($current->dayOfWeek === $dayMap[strtolower($hari)]) {
                        $schedule[] = [
                            'title' => $ekstrakurikuler->nama . ' - Latihan Rutin',
                            'date' => $current->locale('id')->isoFormat('dddd, D MMMM Y'),
                            'time' => $waktu,
                            'type' => 'rutin',
                            'is_today' => $current->isToday(),
                            'is_tomorrow' => $current->isTomorrow()
                        ];
                        $found++;
                    }
                    $current->addDay();
                }
            }
        }

        // Add mock special events
        $schedule[] = [
            'title' => 'Pertandingan Antar Sekolah',
            'date' => 'Sabtu, 6 Juli 2024',
            'time' => '08:00 - 12:00',
            'type' => 'kompetisi',
            'is_today' => false,
            'is_tomorrow' => false
        ];

        return $schedule;
    }

    public function getNotifikasi()
    {
        $user = Auth::user();

        // Mock notifications for now - in real app, get from database
        $notifikasi = [
            [
                'id' => 1,
                'title' => 'Pendaftaran Disetujui',
                'message' => 'Selamat! Pendaftaran Anda pada ekstrakurikuler telah disetujui.',
                'created_at' => now()->subMinutes(30),
                'read' => false,
                'type' => 'success'
            ],
            [
                'id' => 2,
                'title' => 'Pengumuman Baru',
                'message' => 'Ada pengumuman baru dari pembina ekstrakurikuler Anda.',
                'created_at' => now()->subHours(2),
                'read' => false,
                'type' => 'info'
            ],
            [
                'id' => 3,
                'title' => 'Jadwal Berubah',
                'message' => 'Jadwal latihan hari Senin dipindah ke hari Selasa.',
                'created_at' => now()->subHours(5),
                'read' => true,
                'type' => 'warning'
            ],
            [
                'id' => 4,
                'title' => 'Reminder Kehadiran',
                'message' => 'Jangan lupa hadir pada kegiatan ekstrakurikuler besok.',
                'created_at' => now()->subDay(),
                'read' => true,
                'type' => 'info'
            ]
        ];

        return response()->json($notifikasi);
    }

    public function markAsRead($id)
    {
        // Implementation for marking notification as read
        // In real app, update database record
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
        ]);
    }

    public function getQuickStats()
    {
        $user = Auth::user();

        $stats = [
            'unread_notifications' => 3, // Mock data
            'upcoming_events' => 2,
            'attendance_percentage' => $user->sudahTerdaftarEkstrakurikuler() ? 85 : 0,
            'achievements' => [
                'perfect_attendance' => false,
                'early_bird' => true,
                'active_participant' => true
            ]
        ];

        return response()->json($stats);
    }

    public function getAttendanceChart()
    {
        $user = Auth::user();

        if (!$user->sudahTerdaftarEkstrakurikuler()) {
            return response()->json([
                'labels' => [],
                'data' => []
            ]);
        }

        // Mock data for 6 months
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');
            $data[] = rand(70, 95); // Mock attendance percentage
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
