<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina;

        // Get today's date
        $today = Carbon::today();

        // Get all approved students for today's attendance
        $pendaftarans = Pendaftaran::with(['user', 'ekstrakurikuler'])
            ->whereHas('ekstrakurikuler', function ($query) use ($user) {
                $query->where('pembina_id', $user->id);
            })
            ->where('status', 'disetujui')
            ->get();

        // Get today's attendance records
        $todayAttendance = Absensi::whereDate('tanggal', $today)
            ->whereIn('pendaftaran_id', $pendaftarans->pluck('id'))
            ->get()
            ->keyBy('pendaftaran_id');

        return view('pembina.absensi.index', compact('pendaftarans', 'todayAttendance', 'today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,alpa,terlambat',
            'catatan' => 'nullable|string|max:255'
        ]);

        $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);

        // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
        if ($pendaftaran->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if attendance already exists for this date
        $existingAbsensi = Absensi::where('pendaftaran_id', $request->pendaftaran_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existingAbsensi) {
            $existingAbsensi->update([
                'status' => $request->status,
                'catatan' => $request->catatan,
                'dicatat_oleh' => Auth::id()
            ]);
            $message = 'Absensi berhasil diperbarui!';
        } else {
            Absensi::create([
                'pendaftaran_id' => $request->pendaftaran_id,
                'tanggal' => $request->tanggal,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'dicatat_oleh' => Auth::id()
            ]);
            $message = 'Absensi berhasil disimpan!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*.pendaftaran_id' => 'required|exists:pendaftarans,id',
            'absensi.*.status' => 'required|in:hadir,izin,alpa,terlambat',
            'absensi.*.catatan' => 'nullable|string|max:255'
        ]);

        $processed = 0;
        $user = Auth::user();

        foreach ($request->absensi as $item) {
            $pendaftaran = Pendaftaran::findOrFail($item['pendaftaran_id']);

            // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
            if ($pendaftaran->ekstrakurikuler->pembina_id !== $user->id) {
                continue;
            }

            // Check if attendance already exists
            $existingAbsensi = Absensi::where('pendaftaran_id', $item['pendaftaran_id'])
                ->whereDate('tanggal', $request->tanggal)
                ->first();

            if ($existingAbsensi) {
                $existingAbsensi->update([
                    'status' => $item['status'],
                    'catatan' => $item['catatan'] ?? null,
                    'dicatat_oleh' => $user->id
                ]);
            } else {
                Absensi::create([
                    'pendaftaran_id' => $item['pendaftaran_id'],
                    'tanggal' => $request->tanggal,
                    'status' => $item['status'],
                    'catatan' => $item['catatan'] ?? null,
                    'dicatat_oleh' => $user->id
                ]);
            }
            $processed++;
        }

        return redirect()->back()->with('success', "Berhasil menyimpan {$processed} data absensi!");
    }

    public function history()
    {
        $user = Auth::user();

        $absensis = Absensi::with(['pendaftaran.user', 'pendaftaran.ekstrakurikuler'])
            ->whereHas('pendaftaran.ekstrakurikuler', function ($query) use ($user) {
                $query->where('pembina_id', $user->id);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('pembina.absensi.history', compact('absensis'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'ekstrakurikuler_id' => 'nullable|exists:ekstrakurikulers,id'
        ]);

        $user = Auth::user();
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        $query = Absensi::with(['pendaftaran.user', 'pendaftaran.ekstrakurikuler'])
            ->whereHas('pendaftaran.ekstrakurikuler', function ($q) use ($user) {
                $q->where('pembina_id', $user->id);
            })
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->ekstrakurikuler_id) {
            $query->whereHas('pendaftaran', function ($q) use ($request) {
                $q->where('ekstrakurikuler_id', $request->ekstrakurikuler_id);
            });
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();

        // Generate report statistics
        $stats = [
            'total_pertemuan' => $absensis->groupBy('tanggal')->count(),
            'total_hadir' => $absensis->where('status', 'hadir')->count(),
            'total_izin' => $absensis->where('status', 'izin')->count(),
            'total_alpa' => $absensis->where('status', 'alpa')->count(),
            'total_terlambat' => $absensis->where('status', 'terlambat')->count(),
        ];

        if ($request->export) {
            return $this->exportReport($absensis, $stats, $startDate, $endDate);
        }

        return view('pembina.absensi.report', compact('absensis', 'stats', 'startDate', 'endDate'));
    }

    private function exportReport($absensis, $stats, $startDate, $endDate)
    {
        $filename = 'laporan_kehadiran_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($absensis, $stats) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Tanggal',
                'Nama Siswa',
                'NIS',
                'Ekstrakurikuler',
                'Status Kehadiran',
                'Catatan'
            ]);

            // Data CSV
            foreach ($absensis as $absensi) {
                fputcsv($file, [
                    $absensi->tanggal->format('d/m/Y'),
                    $absensi->pendaftaran->user->name,
                    $absensi->pendaftaran->user->nis ?? '-',
                    $absensi->pendaftaran->ekstrakurikuler->nama,
                    ucfirst($absensi->status),
                    $absensi->catatan ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
