<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EkstrakurikulerSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus semua data yang ada
        Ekstrakurikuler::truncate();

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil pembina yang ada
        $pembinas = User::where('role', 'pembina')->get();

        if ($pembinas->isEmpty()) {
            $this->command->error('Tidak ada pembina yang ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $ekstrakurikulers = [
            [
                'nama' => 'Futsal',
                'deskripsi' => 'Ekstrakurikuler futsal untuk mengembangkan kemampuan bermain sepak bola dalam ruangan. Melatih kerjasama tim, koordinasi, dan kebugaran fisik. Cocok untuk siswa yang menyukai olahraga dan kompetisi.',
                'kapasitas_maksimal' => 20,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'senin', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['olahraga', 'tim']),
                'nilai_minimal' => 75.0,
                'pembina_id' => $pembinas->get(0)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Basket',
                'deskripsi' => 'Ekstrakurikuler basket untuk mengembangkan teknik dasar basket, strategi permainan, dan sportivitas. Cocok untuk siswa yang menyukai tantangan dan kerjasama tim dalam olahraga yang dinamis.',
                'kapasitas_maksimal' => 16,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'rabu', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['olahraga', 'tim']),
                'nilai_minimal' => 75.0,
                'pembina_id' => $pembinas->get(0)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Paduan Suara',
                'deskripsi' => 'Ekstrakurikuler paduan suara untuk mengembangkan kemampuan vokal, harmonisasi, dan apresiasi musik. Membentuk karakter disiplin dan kepercayaan diri melalui seni musik.',
                'kapasitas_maksimal' => 25,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'selasa', 'waktu' => '15:00-16:30']),
                'kategori' => json_encode(['seni', 'musik']),
                'nilai_minimal' => 70.0,
                'pembina_id' => $pembinas->get(1)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Tari Tradisional',
                'deskripsi' => 'Ekstrakurikuler tari tradisional Indonesia untuk melestarikan budaya dan mengembangkan kemampuan seni gerak. Melatih kelenturan, koordinasi, dan kecintaan terhadap budaya nusantara.',
                'kapasitas_maksimal' => 20,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'kamis', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['akademik', 'media']),
                'nilai_minimal' => 75.0,
                'pembina_id' => $pembinas->get(3)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Badminton',
                'deskripsi' => 'Ekstrakurikuler badminton untuk mengembangkan kemampuan olahraga raket, refleks, dan stamina. Cocok untuk siswa yang ingin meningkatkan kebugaran dan kemampuan individual dalam olahraga.',
                'kapasitas_maksimal' => 22,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'rabu', 'waktu' => '15:00-16:30']),
                'kategori' => json_encode(['olahraga', 'individual']),
                'nilai_minimal' => 72.0,
                'pembina_id' => $pembinas->get(4)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Desain Grafis',
                'deskripsi' => 'Ekstrakurikuler desain grafis untuk mengembangkan kreativitas dalam bidang visual dan digital. Mempelajari software design, tipografi, dan konsep visual komunikasi modern.',
                'kapasitas_maksimal' => 14,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'jumat', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['seni', 'teknologi']),
                'nilai_minimal' => 76.0,
                'pembina_id' => $pembinas->get(4)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Robotika',
                'deskripsi' => 'Ekstrakurikuler robotika untuk mengembangkan kemampuan STEM (Science, Technology, Engineering, Mathematics). Melatih logika, kreativitas, dan problem solving melalui teknologi modern.',
                'kapasitas_maksimal' => 15,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'jumat', 'waktu' => '15:00-17:00']),
                'kategori' => json_encode(['teknologi', 'akademik']),
                'nilai_minimal' => 80.0,
                'pembina_id' => $pembinas->get(2)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'English Club',
                'deskripsi' => 'Ekstrakurikuler English Club untuk meningkatkan kemampuan berbahasa Inggris melalui conversation, debate, dan presentation. Mempersiapkan siswa menghadapi era global.',
                'kapasitas_maksimal' => 18,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'selasa', 'waktu' => '15:30-17:00']),
                'kategori' => json_encode(['akademik', 'bahasa']),
                'nilai_minimal' => 78.0,
                'pembina_id' => $pembinas->get(3)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Pramuka',
                'deskripsi' => 'Ekstrakurikuler Pramuka untuk membentuk karakter kepemimpinan, kemandirian, dan cinta alam. Mengembangkan soft skills dan life skills yang berguna untuk masa depan.',
                'kapasitas_maksimal' => 30,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'sabtu', 'waktu' => '08:00-11:00']),
                'kategori' => json_encode(['kepemimpinan', 'karakter']),
                'nilai_minimal' => 70.0,
                'pembina_id' => $pembinas->get(0)->id,
                'is_active' => true,
            ],
            [
                'nama' => 'Jurnalistik',
                'deskripsi' => 'Ekstrakurikuler jurnalistik untuk mengembangkan kemampuan menulis, fotografi, dan dokumentasi. Melatih kepekaan sosial dan kemampuan komunikasi massa melalui media.',
                'kapasitas_maksimal' => 12,
                'peserta_saat_ini' => 0,
                'jadwal' => json_encode(['hari' => 'kamis', 'waktu' => '15:00-16:30']),
                'kategori' => json_encode(['seni', 'budaya']),
                'nilai_minimal' => 70.0,
                'pembina_id' => $pembinas->get(1)->id,
                'is_active' => true,
            ],
        ];

        foreach ($ekstrakurikulers as $ekskul) {
            Ekstrakurikuler::create($ekskul);
        }

        $this->command->info('✓ Ekstrakurikuler data seeded successfully - 10 ekstrakurikuler created');
    }
}
