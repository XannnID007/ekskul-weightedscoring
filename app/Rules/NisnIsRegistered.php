<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class NisnIsRegistered implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Cari user berdasarkan NISN yang diinput
        $user = User::where('nis', $value)->where('role', 'siswa')->first();

        // Pengecekan Gagal jika:
        // 1. User dengan NISN tersebut tidak ditemukan sama sekali.
        // 2. User ditemukan, TAPI akunnya sudah aktif (sudah pernah registrasi).
        if (!$user || $user->email_verified_at !== null) {
            return false;
        }

        // Pengecekan Berhasil jika user ditemukan dan akunnya masih "bayangan" (belum diverifikasi).
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'NISN tidak terdaftar atau akun ini sudah diaktifkan.';
    }
}
