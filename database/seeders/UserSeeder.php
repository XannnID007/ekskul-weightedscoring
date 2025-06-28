<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin Miftah',
            'email' => 'admin@miftah.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Pembina 1 - Olahraga
        User::create([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'budisantoso@miftah.com',
            'password' => Hash::make('pembina123'),
            'role' => 'pembina',
            'telepon' => '081234567890',
            'jenis_kelamin' => 'L',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Pembina 2 - Seni
        User::create([
            'name' => 'Siti Nurhaliza, S.Sn',
            'email' => 'sitinurhaliza@miftah.com',
            'password' => Hash::make('pembina123'),
            'role' => 'pembina',
            'telepon' => '081234567891',
            'jenis_kelamin' => 'P',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Pembina 3 - Akademik
        User::create([
            'name' => 'Ahmad Dahlan, M.Pd',
            'email' => 'ahmaddahlan@miftah.com',
            'password' => Hash::make('pembina123'),
            'role' => 'pembina',
            'telepon' => '081234567892',
            'jenis_kelamin' => 'L',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Sample Siswa
        $siswas = [
            [
                'name' => 'Muhammad Iqbal',
                'email' => 'muhammadiqbal@student.com',
                'nis' => '2024001',
                'jenis_kelamin' => 'L',
                'nilai_rata_rata' => 85.5,
                'minat' => json_encode(['olahraga', 'teknologi']),
                'tanggal_lahir' => '2007-03-15',
            ],
            [
                'name' => 'Fatimah Azzahra',
                'email' => 'fatimahazzahra@student.com',
                'nis' => '2024002',
                'jenis_kelamin' => 'P',
                'nilai_rata_rata' => 88.2,
                'minat' => json_encode(['seni', 'akademik']),
                'tanggal_lahir' => '2007-07-22',
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'rizkypratama@student.com',
                'nis' => '2024003',
                'jenis_kelamin' => 'L',
                'nilai_rata_rata' => 82.0,
                'minat' => json_encode(['olahraga', 'organisasi']),
                'tanggal_lahir' => '2007-01-10',
            ],
            [
                'name' => 'Nur Aini Rahmawati',
                'email' => 'nuraini@student.com',
                'nis' => '2024004',
                'jenis_kelamin' => 'P',
                'nilai_rata_rata' => 90.1,
                'minat' => json_encode(['akademik', 'seni']),
                'tanggal_lahir' => '2007-05-18',
            ],
            [
                'name' => 'Dimas Ardiansyah',
                'email' => 'dimasardiansyah@student.com',
                'nis' => '2024005',
                'jenis_kelamin' => 'L',
                'nilai_rata_rata' => 79.5,
                'minat' => json_encode(['teknologi', 'olahraga']),
                'tanggal_lahir' => '2007-11-03',
            ],
        ];

        foreach ($siswas as $siswa) {
            User::create(array_merge($siswa, [
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'telepon' => '0812345678' . rand(10, 99),
                'alamat' => 'Cimahi, Jawa Barat',
                'email_verified_at' => now(),
                'is_active' => true,
            ]));
        }
    }
}
