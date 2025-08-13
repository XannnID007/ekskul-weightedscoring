<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Rules\NisnIsRegistered; // <-- PENTING: Impor aturan validasi baru
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/siswa/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * Diubah untuk menggunakan aturan validasi kustom kita.
     */
    protected function validator(array $data)
    {
        // Gunakan aturan NisnIsRegistered yang telah kita buat
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nis' => ['required', 'string', new NisnIsRegistered()], // <-- PENTING: Terapkan aturan di sini
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Mengubah proses dari "Create" menjadi "Update".
     *
     * Fungsi ini tidak lagi membuat user baru, tapi mencari "akun bayangan"
     * yang cocok dengan NISN, lalu meng-update-nya dengan data baru.
     */
    protected function create(array $data)
    {
        // 1. Cari user "akun bayangan" berdasarkan NISN.
        // Kita bisa yakin user ini ada karena sudah lolos validasi.
        $user = User::where('nis', $data['nis'])->first();

        // 2. Update data user tersebut dengan email dan password asli.
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(), // Aktifkan akunnya!
        ]);

        // 3. Kembalikan user yang sudah di-update
        return $user;
    }

    /**
     * The user has been registered.
     */
    protected function registered($request, $user)
    {
        Log::info('Siswa mengaktifkan akun: ' . $user->name . ' (' . $user->email . ')');

        return redirect($this->redirectPath())
            ->with('success', 'Registrasi berhasil! Akun Anda kini telah aktif.');
    }
}
