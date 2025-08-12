<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentNotRegistered
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->role === 'siswa') {
            // ✅ PENGECEKAN KETAT: Cek total pendaftaran (termasuk pending, disetujui, ditolak)
            $totalPendaftaran = $user->pendaftarans()->count();

            if ($totalPendaftaran > 0) {
                // Jika request AJAX, return JSON error
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah memiliki pendaftaran ekstrakurikuler. Setiap siswa hanya dapat mendaftar satu ekstrakurikuler.',
                        'redirect' => route('siswa.pendaftaran')
                    ], 403);
                }

                // Jika request biasa, redirect dengan pesan
                return redirect()->route('siswa.pendaftaran')
                    ->with('warning', 'Anda sudah memiliki pendaftaran ekstrakurikuler. Lihat status pendaftaran Anda di halaman ini.');
            }

            // ✅ PENGECEKAN TAMBAHAN: Double check untuk sudah terdaftar (disetujui)
            if ($user->sudahTerdaftarEkstrakurikuler()) {
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda sudah terdaftar pada ekstrakurikuler. Silakan lihat jadwal kegiatan Anda.',
                        'redirect' => route('siswa.jadwal')
                    ], 403);
                }

                return redirect()->route('siswa.jadwal')
                    ->with('info', 'Anda sudah terdaftar pada ekstrakurikuler. Silakan lihat jadwal kegiatan Anda.');
            }
        }

        return $next($request);
    }
}
