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
            if ($user->sudahTerdaftarEkstrakurikuler()) {
                return redirect()->route('siswa.dashboard')
                    ->with('info', 'Anda sudah terdaftar pada ekstrakurikuler. Silakan lihat jadwal kegiatan Anda.');
            }
        }

        return $next($request);
    }
}
