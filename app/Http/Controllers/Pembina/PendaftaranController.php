<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
        if ($pendaftaran->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah masih ada kapasitas
        if (!$pendaftaran->ekstrakurikuler->masihBisaDaftar()) {
            return redirect()->back()
                ->with('error', 'Kapasitas ekstrakurikuler sudah penuh.');
        }

        $pendaftaran->update([
            'status' => 'disetujui',
            'disetujui_pada' => now(),
            'disetujui_oleh' => Auth::id()
        ]);

        // Update peserta saat ini
        $pendaftaran->ekstrakurikuler->increment('peserta_saat_ini');

        return redirect()->route('pembina.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil disetujui!');
    }

    public function reject(Request $request, Pendaftaran $pendaftaran)
    {
        // Pastikan pendaftaran milik ekstrakurikuler yang dibina user
        if ($pendaftaran->ekstrakurikuler->pembina_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        $pendaftaran->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'disetujui_oleh' => Auth::id()
        ]);

        return redirect()->route('pembina.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil ditolak.');
    }
}
