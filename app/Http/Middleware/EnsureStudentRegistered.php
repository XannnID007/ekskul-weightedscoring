<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentRegistered
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->role === 'siswa') {
            if (!$user->sudahTerdaftarEkstrakurikuler()) {
                return redirect()->route('siswa.dashboard')
                    ->with('warning', 'Anda belum terdaftar pada ekstrakurikuler. Daftar terlebih dahulu untuk mengakses jadwal kegiatan.');
            }
        }

        return $next($request);
    }
}
