<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendaftarans = $user->pendaftarans()
            ->with(['ekstrakurikuler.pembina'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.pendaftaran.index', compact('pendaftarans'));
    }

    public function cancel(Pendaftaran $pendaftaran)
    {
        // Pastikan pendaftaran milik user yang login
        if ($pendaftaran->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa dibatalkan jika masih pending
        if ($pendaftaran->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Pendaftaran yang sudah disetujui atau ditolak tidak dapat dibatalkan.');
        }

        $pendaftaran->delete();

        return redirect()->route('siswa.pendaftaran')
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }
}
