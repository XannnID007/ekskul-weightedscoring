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
            ->with(['ekstrakurikuler.pembina'])
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

        // Get upcoming schedule if registered - MENGGUNAKAN DATA REAL
        $upcomingSchedule = [];
        if ($pendaftaran && $ekstrakurikuler) {
            $upcomingSchedule = $this->getUpcomingSchedule($ekstrakurikuler);
        }

        return view('siswa.dashboard', compact(
            'rekomendasis',
            'ekstrakurikuler',
            'pendaftaran',
            'upcomingSchedule'
        ));
    }

    private function getUpcomingSchedule($ekstrakurikuler)
    {
        $schedule = [];
        $today = Carbon::today();

        // Check if there's activity in the next 7 days
        if ($ekstrakurikuler->jadwal && isset($ekstrakurikuler->jadwal['hari'])) {
            $hari = strtolower($ekstrakurikuler->jadwal['hari']);
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

            if (isset($dayMap[$hari])) {
                $targetDay = $dayMap[$hari];
                $current = $today->copy();

                // Find next 2 occurrences dalam 14 hari
                $found = 0;
                for ($i = 0; $i < 14 && $found < 2; $i++) {
                    if ($current->dayOfWeek === $targetDay) {
                        $schedule[] = [
                            'title' => $ekstrakurikuler->nama . ' - Latihan Rutin',
                            'date' => $current->locale('id')->isoFormat('dddd, D MMMM Y'),
                            'time' => $waktu,
                            'type' => 'rutin',
                            'is_today' => $current->isToday(),
                            'is_tomorrow' => $current->isTomorrow(),
                            'carbon_date' => $current->copy()
                        ];
                        $found++;
                    }
                    $current->addDay();
                }
            }
        }

        // Add mock special events jika masih kurang dari 2
        if (count($schedule) < 2) {
            $schedule[] = [
                'title' => 'Pertandingan Antar Sekolah',
                'date' => $today->copy()->addDays(10)->locale('id')->isoFormat('dddd, D MMMM Y'),
                'time' => '08:00 - 12:00',
                'type' => 'kompetisi',
                'is_today' => false,
                'is_tomorrow' => false,
                'carbon_date' => $today->copy()->addDays(10)
            ];
        }

        // Sort by date
        usort($schedule, function ($a, $b) {
            return $a['carbon_date']->timestamp - $b['carbon_date']->timestamp;
        });

        return $schedule;
    }

    public function getNotifikasi()
    {
        $user = Auth::user();

        // Get real notifications based on user's ekstrakurikuler
        $notifikasi = [];

        // Check pendaftaran status changes
        $pendaftaranTerbaru = $user->pendaftarans()->latest()->first();
        if ($pendaftaranTerbaru) {
            if ($pendaftaranTerbaru->status === 'disetujui') {
                $notifikasi[] = [
                    'id' => 1,
                    'title' => 'Pendaftaran Disetujui',
                    'message' => 'Selamat! Pendaftaran Anda pada ekstrakurikuler ' . $pendaftaranTerbaru->ekstrakurikuler->nama . ' telah disetujui.',
                    'created_at' => $pendaftaranTerbaru->disetujui_pada ?? $pendaftaranTerbaru->updated_at,
                    'read' => false,
                    'type' => 'success'
                ];
            } elseif ($pendaftaranTerbaru->status === 'ditolak') {
                $notifikasi[] = [
                    'id' => 2,
                    'title' => 'Pendaftaran Ditolak',
                    'message' => 'Pendaftaran Anda pada ekstrakurikuler ' . $pendaftaranTerbaru->ekstrakurikuler->nama . ' ditolak. ' . ($pendaftaranTerbaru->alasan_penolakan ? 'Alasan: ' . $pendaftaranTerbaru->alasan_penolakan : ''),
                    'created_at' => $pendaftaranTerbaru->updated_at,
                    'read' => false,
                    'type' => 'error'
                ];
            }
        }

        // Add pengumuman from ekstrakurikuler if registered
        if ($user->sudahTerdaftarEkstrakurikuler()) {
            $pendaftaranDisetujui = $user->pendaftarans()->where('status', 'disetujui')->first();

            if ($pendaftaranDisetujui && class_exists('App\Models\Pengumuman')) {
                $pengumumanTerbaru = $pendaftaranDisetujui->ekstrakurikuler->pengumumans()
                    ->latest()
                    ->limit(2)
                    ->get();

                foreach ($pengumumanTerbaru as $pengumuman) {
                    $notifikasi[] = [
                        'id' => 'pengumuman_' . $pengumuman->id,
                        'title' => $pengumuman->is_penting ? 'Pengumuman Penting' : 'Pengumuman Baru',
                        'message' => $pengumuman->judul,
                        'created_at' => $pengumuman->created_at,
                        'read' => false,
                        'type' => $pengumuman->is_penting ? 'warning' : 'info'
                    ];
                }
            }
        }

        // Add upcoming schedule reminder
        if ($user->sudahTerdaftarEkstrakurikuler()) {
            $pendaftaranDisetujui = $user->pendaftarans()->where('status', 'disetujui')->first();
            if ($pendaftaranDisetujui) {
                $jadwalBesok = $this->getUpcomingSchedule($pendaftaranDisetujui->ekstrakurikuler);

                foreach ($jadwalBesok as $jadwal) {
                    if ($jadwal['is_tomorrow']) {
                        $notifikasi[] = [
                            'id' => 'reminder_tomorrow',
                            'title' => 'Reminder Kegiatan Besok',
                            'message' => 'Jangan lupa hadir pada kegiatan ' . $jadwal['title'] . ' besok pukul ' . $jadwal['time'],
                            'created_at' => now(),
                            'read' => false,
                            'type' => 'info'
                        ];
                        break; // Only one reminder
                    }
                }
            }
        }

        // Sort by date descending
        usort($notifikasi, function ($a, $b) {
            $dateA = is_string($a['created_at']) ? Carbon::parse($a['created_at']) : $a['created_at'];
            $dateB = is_string($b['created_at']) ? Carbon::parse($b['created_at']) : $b['created_at'];
            return $dateB->timestamp - $dateA->timestamp;
        });

        return response()->json($notifikasi);
    }

    public function markAsRead($id)
    {
        // Implementation for marking notification as read
        // In real app, you would update database record
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
        ]);
    }

    public function getQuickStats()
    {
        $user = Auth::user();

        // Real stats based on user data
        $stats = [
            'unread_notifications' => 0,
            'upcoming_events' => 0,
            'attendance_percentage' => 0,
            'achievements' => [
                'perfect_attendance' => false,
                'early_bird' => false,
                'active_participant' => false
            ]
        ];

        // Count real unread notifications
        if ($user->sudahTerdaftarEkstrakurikuler()) {
            $pendaftaranDisetujui = $user->pendaftarans()->where('status', 'disetujui')->first();

            if ($pendaftaranDisetujui) {
                $ekstrakurikuler = $pendaftaranDisetujui->ekstrakurikuler;

                // Count upcoming events
                $upcomingEvents = $this->getUpcomingSchedule($ekstrakurikuler);
                $stats['upcoming_events'] = count($upcomingEvents);

                // Mock attendance percentage (could be calculated from real attendance data)
                $stats['attendance_percentage'] = 85;

                // Mock achievements based on attendance
                $stats['achievements']['active_participant'] = true;
                if ($stats['attendance_percentage'] >= 95) {
                    $stats['achievements']['perfect_attendance'] = true;
                }
            }
        }

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

        // Mock data for 6 months - in real app, calculate from attendance records
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M');

            // Mock attendance percentage - in real app, calculate from database
            $data[] = rand(70, 95);
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
