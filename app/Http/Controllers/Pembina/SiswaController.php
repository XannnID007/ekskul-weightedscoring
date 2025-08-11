<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index()
    {
        $pembina = Auth::user();
        $ekstrakurikulers = $pembina->ekstrakurikulerSebagaiPembina()
            ->with(['siswaDisetujui' => function ($query) {
                $query->orderBy('name', 'asc');
            }])
            ->get();

        return view('pembina.siswa.index', compact('ekstrakurikulers'));
    }


    public function removeStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'ekstrakurikuler_id' => 'required|exists:ekstrakurikulers,id'
        ]);

        $pembina = Auth::user();

        // Cek apakah ekstrakurikuler milik pembina ini
        $ekstrakurikuler = $pembina->ekstrakurikulerSebagaiPembina()
            ->where('id', $request->ekstrakurikuler_id)
            ->first();

        if (!$ekstrakurikuler) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        // Cari pendaftaran siswa
        $pendaftaran = Pendaftaran::where('user_id', $request->student_id)
            ->where('ekstrakurikuler_id', $request->ekstrakurikuler_id)
            ->where('status', 'disetujui')
            ->first();

        if (!$pendaftaran) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
        }

        // Ubah status jadi ditolak (tidak hapus data)
        $pendaftaran->update([
            'status' => 'ditolak',
            'alasan_penolakan' => 'Dikeluarkan oleh pembina pada ' . now()->format('d M Y H:i'),
            'disetujui_oleh' => Auth::id()
        ]);

        return response()->json(['success' => true, 'message' => 'Siswa berhasil dikeluarkan']);
    }
}
