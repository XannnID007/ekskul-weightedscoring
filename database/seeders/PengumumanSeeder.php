<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run()
    {
        $ekstrakurikulers = Ekstrakurikuler::all();
        $pembinas = User::where('role', 'pembina')->get();

        if ($ekstrakurikulers->isEmpty() || $pembinas->isEmpty()) {
            $this->command->error('Ekstrakurikuler atau Pembina tidak ditemukan. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $pengumumans = [
            // Futsal
            [
                'judul' => 'Jadwal Latihan Rutin Futsal',
                'konten' => 'Kepada seluruh anggota ekstrakurikuler Futsal, diinformasikan bahwa latihan rutin akan dilaksanakan setiap hari Senin pukul 15:30-17:00 WIB di lapangan futsal sekolah. Harap membawa perlengkapan olahraga lengkap dan datang tepat waktu. Terima kasih.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'dibuat_oleh' => $pembinas->first()->id,
                'is_penting' => false,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'judul' => 'Turnamen Futsal Antar Sekolah',
                'konten' => 'PENGUMUMAN PENTING! Akan diadakan turnamen futsal antar sekolah pada tanggal 15-17 Juli 2024. Pendaftaran tim dibuka mulai hari ini. Syarat: anggota aktif minimal 3 bulan, nilai akademik minimal 75. Info lengkap hubungi pembina.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'dibuat_oleh' => $pembinas->first()->id,
                'is_penting' => true,
                'created_at' => Carbon::now()->subDays(2),
            ],

            // Basket
            [
                'judul' => 'Pembagian Tim Basket',
                'konten' => 'Pembagian tim untuk latihan basket telah selesai. Tim A: latihan Senin & Rabu, Tim B: latihan Selasa & Kamis. Jadwal dapat dilihat di papan pengumuman ekstrakurikuler. Semua anggota wajib mengikuti jadwal yang telah ditentukan.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Basket')->first()->id,
                'dibuat_oleh' => $pembinas->first()->id,
                'is_penting' => false,
                'created_at' => Carbon::now()->subDays(7),
            ],

            // Paduan Suara
            [
                'judul' => 'Persiapan Konser Paduan Suara',
                'konten' => 'Dalam rangka memperingati HUT sekolah, ekstrakurikuler Paduan Suara akan mengadakan konser pada tanggal 25 Agustus 2024. Latihan intensif dimulai minggu depan. Semua anggota wajib hadir. Kostum akan diinformasikan kemudian.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'dibuat_oleh' => $pembinas->get(1)->id,
                'is_penting' => true,
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'judul' => 'Pembelian Seragam Paduan Suara',
                'konten' => 'Untuk anggota baru, mohon segera melakukan pembayaran seragam paduan suara sebesar Rp 150.000. Pembayaran dapat dilakukan ke bendahara atau langsung ke pembina. Batas waktu pembayaran: 20 Juli 2024.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'dibuat_oleh' => $pembinas->get(1)->id,
                'is_penting' => false,
                'created_at' => Carbon::now()->subDays(6),
            ],

            // Tari Tradisional
            [
                'judul' => 'Workshop Tari Daerah',
                'konten' => 'Akan diadakan workshop tari daerah dengan mengundang penari profesional dari Bandung pada tanggal 22 Juli 2024, pukul 09:00-15:00. Seluruh anggota wajib hadir. Akan dipelajari tari Sunda dan Jawa. Biaya konsumsi Rp 25.000.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Tari Tradisional')->first()->id,
                'dibuat_oleh' => $pembinas->get(1)->id,
                'is_penting' => true,
                'created_at' => Carbon::now()->subDays(4),
            ],

            // Robotika
            [
                'judul' => 'Kompetisi Robotika Tingkat Nasional',
                'konten' => 'Tim robotika sekolah telah lolos ke babak final kompetisi robotika tingkat nasional! Kompetisi akan diadakan pada 5-7 September 2024 di Jakarta. Dukungan dan doa dari seluruh warga sekolah sangat diharapkan.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'dibuat_oleh' => $pembinas->get(2)->id,
                'is_penting' => true,
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'judul' => 'Pembelian Komponen Robot',
                'konten' => 'Untuk persiapan kompetisi, dibutuhkan dana tambahan untuk pembelian komponen robot sebesar Rp 2.500.000. Sumbangan sukarela dapat disalurkan melalui bendahara kelas atau langsung ke pembina. Terima kasih atas dukungannya.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'dibuat_oleh' => $pembinas->get(2)->id,
                'is_penting' => false,
                'created_at' => Carbon::now()->subDays(8),
            ],

            // English Club
            [
                'judul' => 'English Speech Contest',
                'konten' => 'Pendaftaran English Speech Contest tingkat sekolah telah dibuka! Tema: "Technology and Our Future". Hadiah menarik menanti para pemenang. Pendaftaran dibuka hingga 30 Juli 2024. Info lengkap hubungi pembina English Club.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'English Club')->first()->id,
                'dibuat_oleh' => $pembinas->get(3)->id,
                'is_penting' => true,
                'created_at' => Carbon::now(),
            ],

            // Pramuka
            [
                'judul' => 'Kemah Bakti Sosial Pramuka',
                'konten' => 'Kegiatan kemah bakti sosial akan dilaksanakan pada 12-14 Agustus 2024 di Desa Wisata Ciwidey. Kegiatan meliputi bakti sosial, outbound, dan perkemahan. Biaya Rp 275.000 per peserta. Pendaftaran maksimal 25 Juli 2024.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Pramuka')->first()->id,
                'dibuat_oleh' => $pembinas->first()->id,
                'is_penting' => true,
                'created_at' => Carbon::now()->subDays(3),
            ],

            // Jurnalistik
            [
                'judul' => 'Peluncuran Majalah Sekolah',
                'konten' => 'Majalah sekolah edisi pertama akan diluncurkan pada tanggal 1 Agustus 2024. Seluruh anggota jurnalistik telah berkontribusi dalam pembuatan majalah ini. Majalah akan dibagikan gratis ke seluruh warga sekolah.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Jurnalistik')->first()->id,
                'dibuat_oleh' => $pembinas->get(3)->id,
                'is_penting' => false,
                'created_at' => Carbon::now()->subDays(2),
            ],

            // Badminton
            [
                'judul' => 'Seleksi Tim Badminton',
                'konten' => 'Akan diadakan seleksi tim badminton untuk mengikuti kejuaraan antar sekolah. Seleksi dilakukan pada 20 Juli 2024, pukul 08:00-12:00. Seluruh anggota dapat mengikuti seleksi. Persiapkan kondisi fisik dengan baik.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Badminton')->first()->id,
                'dibuat_oleh' => $pembinas->get(4)->id,
                'is_penting' => true,
                'created_at' => Carbon::now()->subDays(5),
            ],

            // Desain Grafis
            [
                'judul' => 'Pameran Karya Desain Grafis',
                'konten' => 'Pameran karya desain grafis siswa akan diadakan bersamaan dengan HUT sekolah. Semua anggota wajib menyiapkan minimal 2 karya terbaik. Deadline pengumpulan karya: 15 Agustus 2024. Format file dan ketentuan akan diinformasikan kemudian.',
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Desain Grafis')->first()->id,
                'dibuat_oleh' => $pembinas->get(4)->id,
                'is_penting' => false,
                'created_at' => Carbon::now()->subDays(6),
            ],
        ];

        foreach ($pengumumans as $pengumuman) {
            Pengumuman::create($pengumuman);
        }

        $this->command->info('✓ Pengumuman data seeded successfully - ' . count($pengumumans) . ' pengumuman created');
    }
}
