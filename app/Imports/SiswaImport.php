<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMappedCells;

class SiswaImport implements ToCollection, WithHeadingRow, WithValidation
{
     /**
      * Fungsi ini akan dieksekusi setelah data dibaca dan divalidasi.
      */
     public function collection(Collection $rows)
     {
          foreach ($rows as $row) {
               User::create([
                    // 1. Data pokok yang diambil dari Excel
                    'name' => $row['nama'],
                    'nis' => (string) $row['nis'], // Paksa konversi ke string di sini
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'role' => 'siswa',

                    // 2. Buat email dan password DUMMY
                    'email' => $row['nis'] . '@placeholder.school',
                    'password' => Hash::make(Str::random(40)),

                    // 3. Pastikan akun BELUM aktif/terverifikasi
                    'email_verified_at' => null,
                    'is_active' => true,
               ]);
          }
     }

     /**
      * Aturan validasi untuk setiap baris di file Excel.
      */
     public function rules(): array
     {
          return [
               // *.nis berarti aturan ini berlaku untuk setiap baris di kolom 'nis'
               '*.nama' => 'required|string|max:255',
               '*.nis' => 'required|string|unique:users,nis',
               '*.jenis_kelamin' => 'required|in:L,P',
          ];
     }

     /**
      * Pesan error kustom untuk validasi.
      */
     public function customValidationMessages()
     {
          return [
               '*.nama.required' => 'Kolom "nama" tidak boleh kosong (pada baris :index).',
               '*.nis.required' => 'Kolom "nis" tidak boleh kosong (pada baris :index).',
               '*.nis.unique' => 'NIS pada baris :index (:value) sudah terdaftar.',
               '*.nis.string' => 'NIS pada baris :index (:value) harus berupa teks/string.',
               '*.jenis_kelamin.required' => 'Kolom "jenis_kelamin" tidak boleh kosong (pada baris :index).',
               '*.jenis_kelamin.in' => 'Kolom "jenis_kelamin" harus L atau P (pada baris :index).',
          ];
     }
}
