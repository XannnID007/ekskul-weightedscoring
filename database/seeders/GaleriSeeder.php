<?php

namespace Database\Seeders;

use App\Models\Galeri;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GaleriSeeder extends Seeder
{
    public function run()
    {
        $ekstrakurikulers = Ekstrakurikuler::all();
        $pembinas = User::where('role', 'pembina')->get();

        if ($ekstrakurikulers->isEmpty() || $pembinas->isEmpty()) {
            $this->command->error('Ekstrakurikuler atau Pembina tidak ditemukan. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $galeris = [
            // Futsal
            [
                'judul' => 'Latihan Rutin Futsal',
                'deskripsi' => 'Dokumentasi latihan rutin anggota futsal dengan materi dasar passing dan shooting',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'path_file' => 'galeri/futsal_latihan_1.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'judul' => 'Pertandingan Persahabatan',
                'deskripsi' => 'Pertandingan persahabatan dengan SMA Negeri 2 Cimahi yang berakhir dengan skor 3-2',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'path_file' => 'galeri/futsal_pertandingan_1.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'judul' => 'Highlight Gol Terbaik',
                'deskripsi' => 'Kompilasi gol-gol terbaik dari latihan dan pertandingan bulan ini',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'path_file' => 'galeri/futsal_highlight_video.mp4',
                'tipe' => 'video',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(3),
            ],

            // Basket
            [
                'judul' => 'Latihan Dribbling',
                'deskripsi' => 'Sesi latihan khusus dribbling dan ball handling untuk meningkatkan skill individual',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Basket')->first()->id,
                'path_file' => 'galeri/basket_dribbling.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(8),
            ],
            [
                'judul' => 'Turnamen Internal',
                'deskripsi' => 'Final turnamen internal basket antar kelas yang sangat seru dan kompetitif',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Basket')->first()->id,
                'path_file' => 'galeri/basket_turnamen.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(5),
            ],

            // Paduan Suara
            [
                'judul' => 'Latihan Harmonisasi',
                'deskripsi' => 'Latihan harmonisasi untuk persiapan konser dengan lagu-lagu daerah Indonesia',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'path_file' => 'galeri/paduan_suara_latihan.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'judul' => 'Penampilan di Acara Sekolah',
                'deskripsi' => 'Penampilan paduan suara dalam acara penyambutan siswa baru tahun ajaran 2024/2025',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'path_file' => 'galeri/paduan_suara_penampilan.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'judul' => 'Rekaman Lagu Sekolah',
                'deskripsi' => 'Proses rekaman mars dan hymne sekolah yang akan digunakan dalam upacara resmi',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'path_file' => 'galeri/paduan_suara_rekaman.mp4',
                'tipe' => 'video',
                'diupload_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(2),
            ],

            // Tari Tradisional
            [
                'judul' => 'Latihan Tari Saman',
                'deskripsi' => 'Latihan intensif tari Saman dari Aceh untuk persiapan festival budaya',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Tari Tradisional')->first()->id,
                'path_file' => 'galeri/tari_saman_latihan.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(9),
            ],
            [
                'judul' => 'Kostum Tari Tradisional',
                'deskripsi' => 'Koleksi kostum tari tradisional dari berbagai daerah Indonesia yang digunakan dalam penampilan',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Tari Tradisional')->first()->id,
                'path_file' => 'galeri/tari_kostum.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(4),
            ],

            // Robotika
            [
                'judul' => 'Robot Line Follower',
                'deskripsi' => 'Robot line follower buatan siswa yang berhasil memenangkan kompetisi tingkat kota',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'path_file' => 'galeri/robot_line_follower.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(2)->id,
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'judul' => 'Workshop Arduino',
                'deskripsi' => 'Workshop pemrograman Arduino untuk anggota baru ekstrakurikuler robotika',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'path_file' => 'galeri/workshop_arduino.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(2)->id,
                'created_at' => Carbon::now()->subDays(11),
            ],
            [
                'judul' => 'Demo Robot Humanoid',
                'deskripsi' => 'Demonstrasi robot humanoid yang dapat menari dan berinteraksi dengan penonton',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'path_file' => 'galeri/robot_humanoid_demo.mp4',
                'tipe' => 'video',
                'diupload_oleh' => $pembinas->get(2)->id,
                'created_at' => Carbon::now()->subDays(1),
            ],

            // English Club
            [
                'judul' => 'English Debate Competition',
                'deskripsi' => 'Kompetisi debat bahasa Inggris internal dengan tema lingkungan dan teknologi',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'English Club')->first()->id,
                'path_file' => 'galeri/english_debate.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(13),
            ],
            [
                'judul' => 'English Movie Night',
                'deskripsi' => 'Kegiatan menonton film berbahasa Inggris dengan diskusi dan review bersama',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'English Club')->first()->id,
                'path_file' => 'galeri/english_movie_night.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(8),
            ],

            // Pramuka
            [
                'judul' => 'Kemah Pramuka',
                'deskripsi' => 'Kegiatan perkemahan di Gunung Tangkuban Perahu dengan berbagai permainan dan pembelajaran alam',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Pramuka')->first()->id,
                'path_file' => 'galeri/kemah_pramuka.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'judul' => 'Upacara Pelantikan',
                'deskripsi' => 'Upacara pelantikan anggota baru pramuka dengan pengucapan janji dan pemberian tanda',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Pramuka')->first()->id,
                'path_file' => 'galeri/pelantikan_pramuka.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(14),
            ],
            [
                'judul' => 'Bakti Sosial Pramuka',
                'deskripsi' => 'Kegiatan bakti sosial membantu korban banjir dengan menyalurkan bantuan dan membersihkan lingkungan',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Pramuka')->first()->id,
                'path_file' => 'galeri/baksos_pramuka.mp4',
                'tipe' => 'video',
                'diupload_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(7),
            ],

            // Jurnalistik
            [
                'judul' => 'Workshop Fotografi',
                'deskripsi' => 'Workshop teknik fotografi jurnalistik dengan mengundang fotografer profesional',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Jurnalistik')->first()->id,
                'path_file' => 'galeri/workshop_fotografi.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(16),
            ],
            [
                'judul' => 'Liputan Acara Sekolah',
                'deskripsi' => 'Tim jurnalistik sedang meliput acara wisuda dan pelepasan siswa kelas XII',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Jurnalistik')->first()->id,
                'path_file' => 'galeri/liputan_wisuda.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(5),
            ],

            // Badminton
            [
                'judul' => 'Latihan Smash',
                'deskripsi' => 'Latihan teknik smash dan footwork untuk meningkatkan kemampuan menyerang',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Badminton')->first()->id,
                'path_file' => 'galeri/badminton_smash.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'judul' => 'Kejuaraan Antar Sekolah',
                'deskripsi' => 'Perwakilan sekolah dalam kejuaraan badminton antar SMA se-Kota Cimahi',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Badminton')->first()->id,
                'path_file' => 'galeri/badminton_kejuaraan.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(4),
            ],

            // Desain Grafis
            [
                'judul' => 'Workshop Photoshop',
                'deskripsi' => 'Workshop dasar-dasar Adobe Photoshop untuk editing foto dan manipulasi gambar',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Desain Grafis')->first()->id,
                'path_file' => 'galeri/workshop_photoshop.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(18),
            ],
            [
                'judul' => 'Karya Poster Siswa',
                'deskripsi' => 'Koleksi poster karya siswa dengan tema lingkungan dan pendidikan',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Desain Grafis')->first()->id,
                'path_file' => 'galeri/poster_karya_siswa.jpg',
                'tipe' => 'gambar',
                'diupload_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'judul' => 'Tutorial Design Logo',
                'deskripsi' => 'Video tutorial membuat logo sekolah menggunakan Adobe Illustrator',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Desain Grafis')->first()->id,
                'path_file' => 'galeri/tutorial_logo_design.mp4',
                'tipe' => 'video',
                'diupload_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($galeris as $galeri) {
            Galeri::create($galeri);
        }

        $this->command->info('✓ Galeri data seeded successfully - ' . count($galeris) . ' galeri items created');
    }
}
