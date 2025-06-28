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

        // Generate calendar data
        $calendarData = $this->generateCalendarData($ekstrakurikuler);

        // Get upcoming activities (next 7 days)
        $upcomingActivities = $this->getUpcomingActivities($ekstrakurikuler);

        // Get recent announcements
        $announcements = $ekstrakurikuler->pengumumans()
            ->latest()
            ->limit(5)
            ->get();

        return view('siswa.jadwal.index', compact(
            'pendaftaran',
            'ekstrakurikuler',
            'calendarData',
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

        // Generate recurring events based on jadwal
        if ($ekstrakurikuler->jadwal && isset($ekstrakurikuler->jadwal['hari'])) {
            $hari = $ekstrakurikuler->jadwal['hari'];
            $waktu = $ekstrakurikuler->jadwal['waktu'] ?? '15:00 - 17:00';

            // Parse waktu
            $waktuParts = explode(' - ', $waktu);
            $startTime = $waktuParts[0] ?? '15:00';
            $endTime = $waktuParts[1] ?? '17:00';

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
                $current = $start->copy()->startOfWeek();

                while ($current->lte($end)) {
                    $eventDate = $current->copy()->next($dayMap[$hari]);

                    if ($eventDate->gte($start) && $eventDate->lte($end)) {
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
                                'description' => 'Kegiatan rutin ' . $ekstrakurikuler->nama
                            ]
                        ];
                    }

                    $current->addWeek();
                }
            }
        }

        // Add special events from announcements
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
                    'description' => $announcement->konten,
                    'is_penting' => true
                ]
            ];
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

    private function generateCalendarData($ekstrakurikuler)
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $events = [];

        // Generate events for current month
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

            if (isset($dayMap[$hari])) {
                $current = $startOfMonth->copy()->startOfWeek();

                while ($current->lte($endOfMonth->endOfWeek())) {
                    $eventDate = $current->copy()->next($dayMap[$hari]);

                    if ($eventDate->gte($startOfMonth) && $eventDate->lte($endOfMonth)) {
                        $events[] = [
                            'date' => $eventDate->format('Y-m-d'),
                            'title' => $ekstrakurikuler->nama,
                            'time' => $waktu,
                            'type' => 'regular'
                        ];
                    }

                    $current->addWeek();
                }
            }
        }

        return $events;
    }

    private function getUpcomingActivities($ekstrakurikuler)
    {
        $activities = [];
        $today = Carbon::today();
        $nextWeek = $today->copy()->addWeek();

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

            if (isset($dayMap[$hari])) {
                $current = $today->copy();

                for ($i = 0; $i < 7; $i++) {
                    if ($current->dayOfWeek === $dayMap[$hari]) {
                        $activities[] = [
                            'date' => $current->copy(),
                            'title' => $ekstrakurikuler->nama,
                            'time' => $waktu,
                            'pembina' => $ekstrakurikuler->pembina->name ?? '',
                            'is_today' => $current->isToday(),
                            'is_tomorrow' => $current->isTomorrow()
                        ];
                    }
                    $current->addDay();
                }
            }
        }

        return collect($activities)->sortBy('date')->values()->all();
    }

    private function exportToICalendar($ekstrakurikuler)
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//EkstrakurikulerApp//Calendar//EN\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";

        // Generate events for next 3 months
        $start = Carbon::now();
        $end = Carbon::now()->addMonths(3);

        if ($ekstrakurikuler->jadwal && isset($ekstrakurikuler->jadwal['hari'])) {
            $hari = $ekstrakurikuler->jadwal['hari'];
            $waktu = $ekstrakurikuler->jadwal['waktu'] ?? '15:00 - 17:00';

            $waktuParts = explode(' - ', $waktu);
            $startTime = str_replace(':', '', $waktuParts[0] ?? '15:00') . '00';
            $endTime = str_replace(':', '', $waktuParts[1] ?? '17:00') . '00';

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
                $current = $start->copy()->startOfWeek();

                while ($current->lte($end)) {
                    $eventDate = $current->copy()->next($dayMap[$hari]);

                    if ($eventDate->gte($start) && $eventDate->lte($end)) {
                        $ical .= "BEGIN:VEVENT\r\n";
                        $ical .= "UID:" . md5($eventDate->format('Y-m-d') . $ekstrakurikuler->id) . "@ekstrakurikulerapp.com\r\n";
                        $ical .= "DTSTART:" . $eventDate->format('Ymd') . "T" . $startTime . "\r\n";
                        $ical .= "DTEND:" . $eventDate->format('Ymd') . "T" . $endTime . "\r\n";
                        $ical .= "SUMMARY:" . $ekstrakurikuler->nama . "\r\n";
                        $ical .= "DESCRIPTION:Kegiatan " . $ekstrakurikuler->nama . " dengan pembina " . ($ekstrakurikuler->pembina->name ?? '') . "\r\n";
                        $ical .= "LOCATION:MA Modern Miftahussa'adah\r\n";
                        $ical .= "END:VEVENT\r\n";
                    }

                    $current->addWeek();
                }
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
        // This would require a PDF library like DomPDF
        // For now, we'll return a simple response
        return redirect()->back()
            ->with('info', 'Export PDF akan segera tersedia.');
    }
}
