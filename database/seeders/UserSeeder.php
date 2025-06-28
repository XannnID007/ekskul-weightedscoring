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
        User::updateOrCreate(
            ['email' => 'admin@miftah.com'],
            [
                'name' => 'Admin Miftah',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Pembina 1 - Olahraga
        User::updateOrCreate(
            ['email' => 'budisantoso@miftah.com'],
            [
                'name' => 'Budi Santoso, S.Pd',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567890',
                'jenis_kelamin' => 'L',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Pembina 2 - Seni
        User::updateOrCreate(
            ['email' => 'sitinurhaliza@miftah.com'],
            [
                'name' => 'Siti Nurhaliza, S.Sn',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567891',
                'jenis_kelamin' => 'P',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Pembina 3 - Akademik & Teknologi
        User::updateOrCreate(
            ['email' => 'ahmadrifai@miftah.com'],
            [
                'name' => 'Ahmad Rifai, M.Pd',
                'password' => Hash::make('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567892',
                'jenis_kelamin' => 'L',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

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
                'minat' => json_encode(['olahraga', 'kepemimpinan']),
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
            [
                'name' => 'Sari Dewi Lestari',
                'email' => 'saridewi@student.com',
                'nis' => '2024006',
                'jenis_kelamin' => 'P',
                'nilai_rata_rata' => 87.3,
                'minat' => json_encode(['seni', 'budaya']),
                'tanggal_lahir' => '2007-09-12',
            ],
            [
                'name' => 'Rahman Hakim',
                'email' => 'rahmanhakim@student.com',
                'nis' => '2024007',
                'jenis_kelamin' => 'L',
                'nilai_rata_rata' => 84.7,
                'minat' => json_encode(['akademik', 'bahasa']),
                'tanggal_lahir' => '2007-06-25',
            ],
            [
                'name' => 'Anisa Putri',
                'email' => 'anisaputri@student.com',
                'nis' => '2024008',
                'jenis_kelamin' => 'P',
                'nilai_rata_rata' => 91.2,
                'minat' => json_encode(['akademik', 'kepemimpinan']),
                'tanggal_lahir' => '2007-04-08',
            ],
            [
                'name' => 'Bayu Kusuma',
                'email' => 'bayukusuma@student.com',
                'nis' => '2024009',
                'jenis_kelamin' => 'L',
                'nilai_rata_rata' => 78.9,
                'minat' => json_encode(['olahraga', 'musik']),
                'tanggal_lahir' => '2007-11-30',
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewisartika@student.com',
                'nis' => '2024010',
                'jenis_kelamin' => 'P',
                'nilai_rata_rata' => 86.5,
                'minat' => json_encode(['seni', 'media']),
                'tanggal_lahir' => '2007-02-14',
            ],
        ];

        foreach ($siswas as $siswa) {
            User::updateOrCreate(
                ['email' => $siswa['email']],
                array_merge($siswa, [
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa',
                    'telepon' => '0812345678' . rand(10, 99),
                    'alamat' => 'Cimahi, Jawa Barat',
                    'email_verified_at' => now(),
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@miftah.com / admin123');
        $this->command->info('Pembina: budisantoso@miftah.com / pembina123');
        $this->command->info('Siswa: muhammadiqbal@student.com / siswa123');
    }
}
