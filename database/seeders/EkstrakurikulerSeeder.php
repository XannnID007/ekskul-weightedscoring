<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Database\Seeder;

class EkstrakurikulerSeeder extends Seeder
{
    public function run()
    {
        // Ambil pembina yang ada atau buat jika tidak ada
        $pembinas = User::where('role', 'pembina')->get();

        // Jika tidak ada pembina, buat beberapa pembina dulu
        if ($pembinas->isEmpty()) {
            $this->createPembinas();
            $pembinas = User::where('role', 'pembina')->get();
        }

        // Pastikan ada minimal 3 pembina
        if ($pembinas->count() < 3) {
            $this->createAdditionalPembinas($pembinas->count());
            $pembinas = User::where('role', 'pembina')->get();
        }

        $ekstrakurikulers = [
            [
                'nama' => 'Futsal',
                'deskripsi' => 'Ekstrakurikuler futsal untuk mengembangkan kemampuan bermain sepak bola dalam ruangan. Melatih kerjasama tim, koordinasi, dan kebugaran fisik.',
                'kapasitas_maksimal' => 20,
                'jadwal' => json_encode(['hari' => 'senin', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['olahraga', 'tim']),
                'nilai_minimal' => 75.0,
                'pembina_id' => $pembinas->first()->id,
            ],
            [
                'nama' => 'Basket',
                'deskripsi' => 'Ekstrakurikuler basket untuk mengembangkan teknik dasar basket, strategi permainan, dan sportivitas. Cocok untuk siswa yang menyukai tantangan dan kerjasama tim.',
                'kapasitas_maksimal' => 16,
                'jadwal' => json_encode(['hari' => 'rabu', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['olahraga', 'tim']),
                'nilai_minimal' => 75.0,
                'pembina_id' => $pembinas->first()->id,
            ],
            [
                'nama' => 'Paduan Suara',
                'deskripsi' => 'Ekstrakurikuler paduan suara untuk mengembangkan kemampuan vokal, harmonisasi, dan apresiasi musik. Membentuk karakter disiplin dan kepercayaan diri.',
                'kapasitas_maksimal' => 25,
                'jadwal' => json_encode(['hari' => 'selasa', 'waktu' => '15:00-16:30']),
                'kategori' => json_encode(['seni', 'musik']),
                'nilai_minimal' => 70.0,
                'pembina_id' => $pembinas->count() >= 2 ? $pembinas->skip(1)->first()->id : $pembinas->first()->id,
            ],
            [
                'nama' => 'Tari Tradisional',
                'deskripsi' => 'Ekstrakurikuler tari tradisional Indonesia untuk melestarikan budaya dan mengembangkan kemampuan seni gerak. Melatih kelenturan, koordinasi, dan kecintaan terhadap budaya.',
                'kapasitas_maksimal' => 20,
                'jadwal' => json_encode(['hari' => 'kamis', 'waktu' => '15:00-16:30']),
                'kategori' => json_encode(['seni', 'budaya']),
                'nilai_minimal' => 70.0,
                'pembina_id' => $pembinas->count() >= 2 ? $pembinas->skip(1)->first()->id : $pembinas->first()->id,
            ],
            [
                'nama' => 'Robotika',
                'deskripsi' => 'Ekstrakurikuler robotika untuk mengembangkan kemampuan STEM (Science, Technology, Engineering, Mathematics). Melatih logika, kreativitas, dan problem solving.',
                'kapasitas_maksimal' => 15,
                'jadwal' => json_encode(['hari' => 'jumat', 'waktu' => '15:00-17:00']),
                'kategori' => json_encode(['teknologi', 'akademik']),
                'nilai_minimal' => 80.0,
                'pembina_id' => $pembinas->count() >= 3 ? $pembinas->skip(2)->first()->id : $pembinas->first()->id,
            ],
            [
                'nama' => 'English Club',
                'deskripsi' => 'Ekstrakurikuler English Club untuk meningkatkan kemampuan berbahasa Inggris melalui conversation, debate, dan presentation. Mempersiapkan siswa menghadapi era global.',
                'kapasitas_maksimal' => 18,
                'jadwal' => json_encode(['hari' => 'selasa', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['akademik', 'bahasa']),
                'nilai_minimal' => 78.0,
                'pembina_id' => $pembinas->count() >= 3 ? $pembinas->skip(2)->first()->id : $pembinas->first()->id,
            ],
            [
                'nama' => 'Pramuka',
                'deskripsi' => 'Ekstrakurikuler Pramuka untuk membentuk karakter kepemimpinan, kemandirian, dan cinta alam. Mengembangkan soft skills dan life skills yang berguna untuk masa depan.',
                'kapasitas_maksimal' => 30,
                'jadwal' => json_encode(['hari' => 'sabtu', 'waktu' => '08:00-11:00']),
                'kategori' => json_encode(['kepemimpinan', 'karakter']),
                'nilai_minimal' => 70.0,
                'pembina_id' => $pembinas->first()->id,
            ],
            [
                'nama' => 'Jurnalistik',
                'deskripsi' => 'Ekstrakurikuler jurnalistik untuk mengembangkan kemampuan menulis, fotografi, dan dokumentasi. Melatih kepekaan sosial dan kemampuan komunikasi massa.',
                'kapasitas_maksimal' => 12,
                'jadwal' => json_encode(['hari' => 'kamis', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['akademik', 'media']),
                'nilai_minimal' => 75.0,
                'pembina_id' => $pembinas->count() >= 2 ? $pembinas->skip(1)->first()->id : $pembinas->first()->id,
            ],
        ];

        foreach ($ekstrakurikulers as $ekskul) {
            Ekstrakurikuler::create($ekskul);
        }
    }

    /**
     * Buat pembina jika belum ada
     */
    private function createPembinas()
    {
        $pembinas = [
            [
                'name' => 'Budi Santoso, S.Pd',
                'email' => 'budisantoso@miftah.com',
                'password' => bcrypt('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567890',
                'jenis_kelamin' => 'L',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Siti Nurhaliza, S.Sn',
                'email' => 'sitinurhaliza@miftah.com',
                'password' => bcrypt('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567891',
                'jenis_kelamin' => 'P',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Ahmad Rifai, M.Pd',
                'email' => 'ahmadrifai@miftah.com',
                'password' => bcrypt('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567892',
                'jenis_kelamin' => 'L',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
        ];

        foreach ($pembinas as $pembina) {
            User::create($pembina);
        }
    }

    /**
     * Buat pembina tambahan jika kurang
     */
    private function createAdditionalPembinas($existingCount)
    {
        $additionalPembinas = [
            [
                'name' => 'Dr. Fatimah Zahra, M.Ed',
                'email' => 'fatimahzahra@miftah.com',
                'password' => bcrypt('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567893',
                'jenis_kelamin' => 'P',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'Muhammad Yusuf, S.Kom',
                'email' => 'muhammadyusuf@miftah.com',
                'password' => bcrypt('pembina123'),
                'role' => 'pembina',
                'telepon' => '081234567894',
                'jenis_kelamin' => 'L',
                'email_verified_at' => now(),
                'is_active' => true,
            ],
        ];

        $needed = 3 - $existingCount;
        for ($i = 0; $i < $needed && $i < count($additionalPembinas); $i++) {
            User::create($additionalPembinas[$i]);
        }
    }
}
