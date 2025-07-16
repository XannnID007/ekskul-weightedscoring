<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected $redirectTo = '/siswa/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nis' => ['required', 'string', 'unique:users', 'regex:/^[0-9]{6,10}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nis.regex' => 'Format NIS tidak valid (harus 6-10 digit angka)',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nis' => $data['nis'],
            'password' => Hash::make($data['password']),
            'role' => 'siswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * The user has been registered.
     */

    protected function registered($request, $user)
    {
        // Optional: Log atau notifikasi untuk admin
        Log::info('Siswa baru mendaftar: ' . $user->name . ' (' . $user->email . ')');

        return redirect($this->redirectPath())
            ->with('success', 'Akun berhasil dibuat! Silakan lengkapi profil Anda untuk mendapatkan rekomendasi ekstrakurikuler.');
    }
}
