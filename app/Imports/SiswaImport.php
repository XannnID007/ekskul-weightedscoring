<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToCollection, WithHeadingRow, WithValidation
{
     public function collection(Collection $rows)
     {
          foreach ($rows as $row) {
               User::create([
                    'name' => $row['nama'],
                    'email' => $row['email'],
                    'password' => Hash::make('siswa123'), // Default password
                    'role' => 'siswa',
                    'nis' => $row['nis'],
                    'jenis_kelamin' => $row['jenis_kelamin'],
                    'tanggal_lahir' => \Carbon\Carbon::createFromFormat('Y-m-d', $row['tanggal_lahir']),
                    'alamat' => $row['alamat'],
                    'telepon' => $row['telepon'],
                    'email_verified_at' => now(),
                    'is_active' => true,
               ]);
          }
     }

     public function rules(): array
     {
          return [
               'nama' => 'required|string|max:255',
               'email' => 'required|email|unique:users,email',
               'nis' => 'required|string|unique:users,nis',
               'jenis_kelamin' => 'required|in:L,P',
               'tanggal_lahir' => 'required|date',
               'alamat' => 'nullable|string',
               'telepon' => 'nullable|string',
          ];
     }

     public function customValidationMessages()
     {
          return [
               'nama.required' => 'Nama harus diisi',
               'email.required' => 'Email harus diisi',
               'email.email' => 'Format email tidak valid',
               'email.unique' => 'Email sudah terdaftar',
               'nis.required' => 'NIS harus diisi',
               'nis.unique' => 'NIS sudah terdaftar',
               'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
               'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
               'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
               'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
          ];
     }
}
