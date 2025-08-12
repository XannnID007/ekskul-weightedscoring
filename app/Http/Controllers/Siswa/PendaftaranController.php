<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftarans = $user->pendaftarans()
            ->with(['ekstrakurikuler.pembina'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik untuk progress tracker
        $stats = [
            'total' => $pendaftarans->count(),
            'pending' => $pendaftarans->where('status', 'pending')->count(),
            'approved' => $pendaftarans->where('status', 'disetujui')->count(),
            'rejected' => $pendaftarans->where('status', 'ditolak')->count(),
        ];

        return view('siswa.pendaftaran.index', compact('pendaftarans', 'stats'));
    }

    /**
     * Cancel/Delete a pending registration
     */
    public function cancel(Pendaftaran $pendaftaran)
    {
        try {
            // Pastikan pendaftaran milik user yang login
            if ($pendaftaran->user_id !== Auth::id()) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk membatalkan pendaftaran ini.');
            }

            // Hanya bisa dibatalkan jika masih pending
            if ($pendaftaran->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Hanya pendaftaran dengan status "Menunggu Review" yang dapat dibatalkan.');
            }

            // Log activity sebelum menghapus
            Log::info('Pendaftaran dibatalkan oleh siswa', [
                'pendaftaran_id' => $pendaftaran->id,
                'user_id' => Auth::id(),
                'ekstrakurikuler_id' => $pendaftaran->ekstrakurikuler_id,
                'ekstrakurikuler_nama' => $pendaftaran->ekstrakurikuler->nama,
                'cancelled_at' => now()
            ]);

            // Simpan nama ekstrakurikuler untuk pesan success
            $ekstrakurikulerNama = $pendaftaran->ekstrakurikuler->nama;

            // Hapus pendaftaran (akan trigger update kapasitas melalui model event)
            $pendaftaran->delete();

            return redirect()->route('siswa.pendaftaran')
                ->with('success', "Pendaftaran untuk ekstrakurikuler \"{$ekstrakurikulerNama}\" berhasil dibatalkan.");
        } catch (\Exception $e) {
            Log::error('Error saat membatalkan pendaftaran', [
                'pendaftaran_id' => $pendaftaran->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pendaftaran. Silakan coba lagi.');
        }
    }

    /**
     * Get registration status for AJAX calls
     */
    public function getStatus(Request $request)
    {
        $user = Auth::user();

        $pendaftarans = $user->pendaftarans()
            ->with(['ekstrakurikuler'])
            ->get();

        $stats = [
            'total' => $pendaftarans->count(),
            'pending' => $pendaftarans->where('status', 'pending')->count(),
            'approved' => $pendaftarans->where('status', 'disetujui')->count(),
            'rejected' => $pendaftarans->where('status', 'ditolak')->count(),
            'has_changes' => $request->session()->has('status_changed') // Flag untuk perubahan
        ];

        // Format data pendaftaran untuk response
        $data = $pendaftarans->map(function ($pendaftaran) {
            return [
                'id' => $pendaftaran->id,
                'ekstrakurikuler' => $pendaftaran->ekstrakurikuler->nama,
                'status' => $pendaftaran->status,
                'created_at' => $pendaftaran->created_at->diffForHumans(),
                'updated_at' => $pendaftaran->updated_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'data' => $data
        ]);
    }

    /**
     * Show detailed tracking for a specific registration
     */
    public function track(Pendaftaran $pendaftaran)
    {
        // Pastikan pendaftaran milik user yang login
        if ($pendaftaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pendaftaran->load(['ekstrakurikuler.pembina', 'penyetuju']);

        // Generate timeline based on status
        $timeline = $this->generateTimeline($pendaftaran);

        return response()->json([
            'success' => true,
            'pendaftaran' => $pendaftaran,
            'timeline' => $timeline
        ]);
    }

    /**
     * Generate timeline for tracking
     */
    private function generateTimeline($pendaftaran)
    {
        $timeline = [
            [
                'step' => 'Pendaftaran Dikirim',
                'status' => 'completed',
                'date' => $pendaftaran->created_at,
                'description' => 'Formulir pendaftaran berhasil dikirim ke sistem'
            ]
        ];

        if ($pendaftaran->status === 'pending') {
            $timeline[] = [
                'step' => 'Review Pembina',
                'status' => 'current',
                'date' => null,
                'description' => 'Pembina sedang meninjau aplikasi Anda'
            ];
            $timeline[] = [
                'step' => 'Keputusan',
                'status' => 'pending',
                'date' => null,
                'description' => 'Pembina akan memberikan keputusan akhir'
            ];
        } elseif ($pendaftaran->status === 'disetujui') {
            $timeline[] = [
                'step' => 'Review Pembina',
                'status' => 'completed',
                'date' => $pendaftaran->disetujui_pada,
                'description' => 'Aplikasi telah ditinjau oleh pembina'
            ];
            $timeline[] = [
                'step' => 'Disetujui',
                'status' => 'completed',
                'date' => $pendaftaran->disetujui_pada,
                'description' => 'Selamat! Anda diterima di ekstrakurikuler ini'
            ];
        } elseif ($pendaftaran->status === 'ditolak') {
            $timeline[] = [
                'step' => 'Review Pembina',
                'status' => 'completed',
                'date' => $pendaftaran->updated_at,
                'description' => 'Aplikasi telah ditinjau oleh pembina'
            ];
            $timeline[] = [
                'step' => 'Ditolak',
                'status' => 'rejected',
                'date' => $pendaftaran->updated_at,
                'description' => 'Aplikasi tidak dapat diterima saat ini'
            ];
        }

        return $timeline;
    }

    /**
     * Reapply for rejected applications
     */
    public function reapply(Request $request, Pendaftaran $pendaftaran)
    {
        try {
            // Pastikan pendaftaran milik user yang login
            if ($pendaftaran->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mendaftar ulang.'
                ], 403);
            }

            // Hanya bisa reapply jika ditolak
            if ($pendaftaran->status !== 'ditolak') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pendaftaran yang ditolak dapat didaftar ulang.'
                ], 400);
            }

            // Check apakah ekstrakurikuler masih tersedia
            if (!$pendaftaran->ekstrakurikuler->masihBisaDaftar()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ekstrakurikuler ini sudah penuh atau tidak aktif.'
                ], 400);
            }

            // Check apakah user sudah terdaftar di ekstrakurikuler lain
            if (Auth::user()->sudahTerdaftarEkstrakurikuler()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar pada ekstrakurikuler lain.'
                ], 400);
            }

            // Update status ke pending dan reset fields
            $pendaftaran->update([
                'status' => 'pending',
                'alasan_penolakan' => null,
                'disetujui_pada' => null,
                'disetujui_oleh' => null
            ]);

            Log::info('Pendaftaran didaftar ulang', [
                'pendaftaran_id' => $pendaftaran->id,
                'user_id' => Auth::id(),
                'ekstrakurikuler_id' => $pendaftaran->ekstrakurikuler_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil diajukan kembali. Menunggu review dari pembina.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat reapply pendaftaran', [
                'pendaftaran_id' => $pendaftaran->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendaftar ulang.'
            ], 500);
        }
    }
}
