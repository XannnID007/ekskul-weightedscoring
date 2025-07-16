<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ekstrakurikulers = $user->ekstrakurikulerSebagaiPembina()->pluck('id');

        $pendaftarans = Pendaftaran::with(['user', 'ekstrakurikuler'])
            ->whereIn('ekstrakurikuler_id', $ekstrakurikulers)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => $pendaftarans->total(),
            'pending' => Pendaftaran::whereIn('ekstrakurikuler_id', $ekstrakurikulers)->pending()->count(),
            'disetujui' => Pendaftaran::whereIn('ekstrakurikuler_id', $ekstrakurikulers)->disetujui()->count(),
            'ditolak' => Pendaftaran::whereIn('ekstrakurikuler_id', $ekstrakurikulers)->ditolak()->count(),
        ];

        return view('pembina.pendaftaran.index', compact('pendaftarans', 'stats'));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
        if ($pendaftaran->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $pendaftaran->load(['user', 'ekstrakurikuler']);
        return view('pembina.pendaftaran.show', compact('pendaftaran'));
    }

    public function approve(Request $request, Pendaftaran $pendaftaran)
    {
        try {
            // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
            if ($pendaftaran->ekstrakurikuler->pembina_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menyetujui pendaftaran ini.'
                ], 403);
            }

            // Cek apakah masih ada kapasitas
            if (!$pendaftaran->ekstrakurikuler->masihBisaDaftar()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kapasitas ekstrakurikuler sudah penuh.'
                ], 400);
            }

            // Cek apakah status masih pending
            if ($pendaftaran->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran ini sudah diproses sebelumnya.'
                ], 400);
            }

            // Gunakan database transaction untuk memastikan konsistensi data
            DB::beginTransaction();

            try {
                // Update status pendaftaran
                $pendaftaran->update([
                    'status' => 'disetujui',
                    'disetujui_pada' => now(),
                    'disetujui_oleh' => Auth::id()
                ]);

                // Update peserta saat ini
                $pendaftaran->ekstrakurikuler->increment('peserta_saat_ini');

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil disetujui!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        try {
            // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
            if ($pendaftaran->ekstrakurikuler->pembina_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menolak pendaftaran ini.'
                ], 403);
            }

            // Validasi input
            $request->validate([
                'alasan_penolakan' => 'required|string|min:10|max:500'
            ]);

            // Cek apakah status masih pending
            if ($pendaftaran->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran ini sudah diproses sebelumnya.'
                ], 400);
            }

            // Update status pendaftaran
            $pendaftaran->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
                'disetujui_oleh' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil ditolak.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses penolakan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkApprove(Request $request)
    {
        try {
            $request->validate([
                'pendaftaran_ids' => 'required|array',
                'pendaftaran_ids.*' => 'exists:pendaftarans,id'
            ]);

            $user = Auth::user();
            $ekstrakurikulerIds = $user->ekstrakurikulerSebagaiPembina()->pluck('id');

            $pendaftarans = Pendaftaran::with('ekstrakurikuler')
                ->whereIn('id', $request->pendaftaran_ids)
                ->whereIn('ekstrakurikuler_id', $ekstrakurikulerIds)
                ->where('status', 'pending')
                ->get();

            if ($pendaftarans->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pendaftaran yang valid untuk disetujui.'
                ], 400);
            }

            DB::beginTransaction();

            try {
                $approved = 0;
                $failed = [];

                foreach ($pendaftarans as $pendaftaran) {
                    if ($pendaftaran->ekstrakurikuler->masihBisaDaftar()) {
                        $pendaftaran->update([
                            'status' => 'disetujui',
                            'disetujui_pada' => now(),
                            'disetujui_oleh' => Auth::id()
                        ]);

                        $pendaftaran->ekstrakurikuler->increment('peserta_saat_ini');
                        $approved++;
                    } else {
                        $failed[] = $pendaftaran->user->name . ' (kapasitas penuh)';
                    }
                }

                DB::commit();

                $message = "Berhasil menyetujui {$approved} pendaftaran.";
                if (!empty($failed)) {
                    $message .= " Gagal: " . implode(', ', $failed);
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'approved' => $approved,
                    'failed' => count($failed)
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses bulk approval: ' . $e->getMessage()
            ], 500);
        }
    }
}
