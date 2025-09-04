<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get pendaftaran yang disetujui
        $pendaftaran = $user->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler.pembina'])
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('siswa.dashboard')
                ->with('info', 'Anda belum terdaftar pada ekstrakurikuler manapun.');
        }

        $ekstrakurikuler = $pendaftaran->ekstrakurikuler;

        // Generate upcoming activities berdasarkan jadwal real
        $upcomingActivities = $this->getUpcomingActivities($ekstrakurikuler);

        // Get recent announcements
        $announcements = [];
        if (class_exists('App\Models\Pengumuman')) {
            $announcements = $ekstrakurikuler->pengumumans()
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('siswa.jadwal.index', compact(
            'pendaftaran',
            'ekstrakurikuler',
            'upcomingActivities',
            'announcements'
        ));
    }

    public function getCalendarEvents(Request $request)
    {
        $user = Auth::user();
        $pendaftaran = $user->pendaftarans()
            ->where('status', 'disetujui')
            ->with('ekstrakurikuler')
            ->first();

        if (!$pendaftaran) {
            return response()->json([]);
        }

        $ekstrakurikuler = $pendaftaran->ekstrakurikuler;
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $events = [];

        // Generate recurring events berdasarkan jadwal ekstrakurikuler
        if ($ekstrakurikuler->jadwal && isset($ekstrakurikuler->jadwal['hari'])) {
            $hari = strtolower($ekstrakurikuler->jadwal['hari']);
            $waktu = $ekstrakurikuler->jadwal['waktu'] ?? '15:00 - 17:00';

            // Parse waktu
            $waktuParts = explode(' - ', $waktu);
            $startTime = isset($waktuParts[0]) ? trim($waktuParts[0]) : '15:00';
            $endTime = isset($waktuParts[1]) ? trim($waktuParts[1]) : '17:00';

            // Validasi format waktu
            if (!$this->isValidTimeFormat($startTime)) $startTime = '15:00';
            if (!$this->isValidTimeFormat($endTime)) $endTime = '17:00';

            // Map hari ke Carbon day number
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

                // Start dari awal minggu dalam range
                $current = $start->copy()->startOfWeek();

                while ($current->lte($end)) {
                    // Cari hari yang sesuai dalam minggu ini
                    $eventDate = $current->copy();

                    // Adjust ke hari yang tepat
                    while ($eventDate->dayOfWeek !== $targetDay && $eventDate->lte($current->copy()->endOfWeek())) {
                        $eventDate->addDay();
                    }

                    // Jika hari ditemukan dan dalam range
                    if (
                        $eventDate->dayOfWeek === $targetDay &&
                        $eventDate->gte($start) &&
                        $eventDate->lte($end)
                    ) {

                        $events[] = [
                            'id' => 'regular_' . $eventDate->format('Y-m-d'),
                            'title' => $ekstrakurikuler->nama,
                            'start' => $eventDate->format('Y-m-d') . 'T' . $startTime,
                            'end' => $eventDate->format('Y-m-d') . 'T' . $endTime,
                            'backgroundColor' => '#20c997',
                            'borderColor' => '#20c997',
                            'textColor' => '#ffffff',
                            'extendedProps' => [
                                'type' => 'regular',
                                'pembina' => $ekstrakurikuler->pembina->name ?? '',
                                'description' => 'Kegiatan rutin ' . $ekstrakurikuler->nama,
                                'lokasi' => 'Sekolah'
                            ]
                        ];
                    }

                    $current->addWeek();
                }
            }
        }

        // Add special events dari pengumuman penting
        if (class_exists('App\Models\Pengumuman')) {
            $specialAnnouncements = $ekstrakurikuler->pengumumans()
                ->where('is_penting', true)
                ->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end)
                ->get();

            foreach ($specialAnnouncements as $announcement) {
                $events[] = [
                    'id' => 'announcement_' . $announcement->id,
                    'title' => '📢 ' . $announcement->judul,
                    'start' => $announcement->created_at->format('Y-m-d'),
                    'backgroundColor' => '#ffc107',
                    'borderColor' => '#ffc107',
                    'textColor' => '#000000',
                    'extendedProps' => [
                        'type' => 'announcement',
                        'description' => strip_tags($announcement->konten),
                        'is_penting' => true
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    public function exportCalendar(Request $request)
    {
        $user = Auth::user();
        $pendaftaran = $user->pendaftarans()
            ->where('status', 'disetujui')
            ->with('ekstrakurikuler')
            ->first();

        if (!$pendaftaran) {
            return redirect()->back()
                ->with('error', 'Anda belum terdaftar pada ekstrakurikuler manapun.');
        }

        $format = $request->get('format', 'ical');

        if ($format === 'ical') {
            return $this->exportToICalendar($pendaftaran->ekstrakurikuler);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($pendaftaran->ekstrakurikuler);
        }

        return redirect()->back()
            ->with('error', 'Format export tidak valid.');
    }

    private function getUpcomingActivities($ekstrakurikuler)
    {
        $activities = [];
        $today = Carbon::today();

        if (!$ekstrakurikuler->jadwal || !isset($ekstrakurikuler->jadwal['hari'])) {
            return $activities;
        }

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

        if (!isset($dayMap[$hari])) {
            return $activities;
        }

        $targetDay = $dayMap[$hari];
        $current = $today->copy();
        $found = 0;

        // Cari 3 kegiatan mendatang
        for ($i = 0; $i < 21 && $found < 3; $i++) { // Max 3 minggu ke depan
            if ($current->dayOfWeek === $targetDay) {
                $activities[] = [
                    'date' => $current->copy(),
                    'title' => $ekstrakurikuler->nama,
                    'time' => $waktu,
                    'pembina' => $ekstrakurikuler->pembina->name ?? '',
                    'is_today' => $current->isToday(),
                    'is_tomorrow' => $current->isTomorrow(),
                    'type' => 'rutin',
                    'description' => 'Kegiatan rutin ' . $ekstrakurikuler->nama
                ];
                $found++;
            }
            $current->addDay();
        }

        // Tambahkan event khusus (contoh)
        if ($found < 3) {
            $activities[] = [
                'date' => $today->copy()->addDays(10),
                'title' => 'Pertandingan Antar Sekolah',
                'time' => '08:00 - 12:00',
                'pembina' => $ekstrakurikuler->pembina->name ?? '',
                'is_today' => false,
                'is_tomorrow' => false,
                'type' => 'kompetisi',
                'description' => 'Kompetisi tingkat kabupaten'
            ];
        }

        // Sort by date
        usort($activities, function ($a, $b) {
            return $a['date']->timestamp - $b['date']->timestamp;
        });

        return $activities;
    }

    private function exportToICalendar($ekstrakurikuler)
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//EkstrakurikulerApp//Calendar//EN\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";

        if ($ekstrakurikuler->jadwal && isset($ekstrakurikuler->jadwal['hari'])) {
            $hari = strtolower($ekstrakurikuler->jadwal['hari']);
            $waktu = $ekstrakurikuler->jadwal['waktu'] ?? '15:00 - 17:00';

            $waktuParts = explode(' - ', $waktu);
            $startTime = isset($waktuParts[0]) ? str_replace(':', '', trim($waktuParts[0])) . '00' : '150000';
            $endTime = isset($waktuParts[1]) ? str_replace(':', '', trim($waktuParts[1])) . '00' : '170000';

            $dayMap = [
                'senin' => 'MO',
                'selasa' => 'TU',
                'rabu' => 'WE',
                'kamis' => 'TH',
                'jumat' => 'FR',
                'sabtu' => 'SA',
                'minggu' => 'SU',
            ];

            if (isset($dayMap[$hari])) {
                $ical .= "BEGIN:VEVENT\r\n";
                $ical .= "UID:" . md5($ekstrakurikuler->id . 'recurring') . "@ekstrakurikulerapp.com\r\n";
                $ical .= "DTSTART:" . Carbon::now()->next($hari)->format('Ymd') . "T" . $startTime . "\r\n";
                $ical .= "DTEND:" . Carbon::now()->next($hari)->format('Ymd') . "T" . $endTime . "\r\n";
                $ical .= "RRULE:FREQ=WEEKLY;BYDAY=" . $dayMap[$hari] . "\r\n";
                $ical .= "SUMMARY:" . $ekstrakurikuler->nama . "\r\n";
                $ical .= "DESCRIPTION:Kegiatan " . $ekstrakurikuler->nama . " dengan pembina " . ($ekstrakurikuler->pembina->name ?? '') . "\r\n";
                $ical .= "LOCATION:MA Modern Miftahussa'adah\r\n";
                $ical .= "END:VEVENT\r\n";
            }
        }

        $ical .= "END:VCALENDAR\r\n";

        $filename = 'jadwal_' . str_replace(' ', '_', strtolower($ekstrakurikuler->nama)) . '.ics';

        return response($ical)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function exportToPDF($ekstrakurikuler)
    {
        // Implementation untuk PDF export bisa ditambahkan nanti
        return redirect()->back()
            ->with('info', 'Export PDF akan segera tersedia.');
    }

    private function isValidTimeFormat($time)
    {
        return preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $time);
    }
}
