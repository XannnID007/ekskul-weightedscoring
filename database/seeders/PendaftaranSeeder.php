<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PendaftaranSeeder extends Seeder
{
    public function run()
    {
        $ekstrakurikulers = Ekstrakurikuler::all();
        $siswas = User::where('role', 'siswa')->get();
        $pembinas = User::where('role', 'pembina')->get();

        if ($ekstrakurikulers->isEmpty() || $siswas->isEmpty()) {
            $this->command->error('Ekstrakurikuler atau Siswa tidak ditemukan. Jalankan seeder lain terlebih dahulu.');
            return;
        }

        $pendaftarans = [
            // Futsal - 8 pendaftar
            [
                'user_id' => $siswas->get(0)->id, // Ahmad Rizki
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'motivasi' => 'Saya sangat menyukai olahraga futsal sejak SD. Futsal mengajarkan saya tentang kerjasama tim, strategi, dan sportivitas. Saya ingin mengembangkan skill bermain futsal dan berkontribusi untuk tim sekolah dalam berbagai kompetisi.',
                'pengalaman' => 'Pernah bermain di klub futsal lokal selama 2 tahun, menjadi kapten tim futsal SMP, juara 2 turnamen futsal antar SMP se-kota',
                'harapan' => 'Bisa menjadi pemain inti tim futsal sekolah, ikut serta dalam kompetisi antar sekolah, dan membantu mengharumkan nama sekolah',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(15),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'user_id' => $siswas->get(2)->id, // Dimas Ardiansyah
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'motivasi' => 'Futsal adalah passion saya. Saya percaya dengan bergabung di ekstrakurikuler ini, saya bisa belajar dari senior dan meningkatkan kemampuan bermain futsal sambil menjaga kebugaran tubuh.',
                'pengalaman' => 'Bermain futsal sejak kelas 5 SD, sering main di lapangan umum, pernah ikut turnamen RT',
                'harapan' => 'Ingin merasakan kompetisi yang lebih serius dan belajar teknik-teknik baru dari pembina',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(14),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(19),
            ],
            [
                'user_id' => $siswas->get(8)->id, // Bayu Kusuma
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'motivasi' => 'Saya ingin menyalurkan hobi bermain futsal dengan lebih terstruktur. Selain itu, saya juga ingin bertemu teman-teman baru yang memiliki minat yang sama.',
                'pengalaman' => 'Sering bermain futsal di kompleks rumah, mengikuti kelas futsal saat liburan',
                'harapan' => 'Bisa bermain futsal dengan teknik yang benar dan ikut turnamen sekolah',
                'tingkat_komitmen' => 'sedang',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(13),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(18),
            ],
            [
                'user_id' => $siswas->get(12)->id, // Galang Prasetyo
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'motivasi' => 'Sebagai mantan kapten basket SMP, saya ingin mencoba tantangan baru di futsal. Kedua olahraga ini sama-sama membutuhkan kerjasama tim yang baik.',
                'pengalaman' => 'Pernah bermain futsal sebagai cross-training untuk basket, memiliki dasar koordinasi yang baik',
                'harapan' => 'Bisa menguasai teknik futsal dan menjadi pemain yang berguna untuk tim',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $siswas->get(16)->id, // Kevin Pratama
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Futsal')->first()->id,
                'motivasi' => 'Futsal mengkombinasikan olahraga dan strategi yang saya sukai. Saya ingin belajar dari senior dan mengasah kemampuan bermain dalam tim.',
                'pengalaman' => 'Bermain futsal sejak SMP, pernah ikut les futsal, sering nonton pertandingan futsal profesional',
                'harapan' => 'Ingin menjadi bagian dari tim inti dan ikut kompetisi tingkat kota',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(3),
            ],

            // Basket - 5 pendaftar
            [
                'user_id' => $siswas->get(12)->id, // Galang Prasetyo (juga daftar basket)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Basket')->first()->id,
                'motivasi' => 'Basket adalah olahraga favorit saya sejak kecil. Saya pernah menjadi kapten tim basket SMP dan ingin melanjutkan passion saya di tingkat SMA.',
                'pengalaman' => 'Kapten tim basket SMP selama 2 tahun, juara 1 turnamen antar SMP, mengikuti club basket lokal',
                'harapan' => 'Ingin mempertahankan prestasi dan membawa tim basket sekolah meraih juara di kompetisi antar SMA',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(12),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(17),
            ],
            [
                'user_id' => $siswas->get(16)->id, // Kevin Pratama (juga daftar basket)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Basket')->first()->id,
                'motivasi' => 'Basket mengajarkan saya disiplin dan kerjasama. Saya ingin mengembangkan kemampuan atletik dan bermain dalam tim yang solid.',
                'pengalaman' => 'Bermain basket sejak kelas 6 SD, pernah ikut camp basket, tinggi badan mendukung untuk bermain basket',
                'harapan' => 'Bisa bermain di posisi yang tepat dan membantu tim meraih prestasi',
                'tingkat_komitmen' => 'sedang',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(11),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(16),
            ],

            // Paduan Suara - 6 pendaftar
            [
                'user_id' => $siswas->get(1)->id, // Fatimah Azzahra
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'motivasi' => 'Musik adalah bagian hidup saya. Saya suka bernyanyi dan ingin belajar harmonisasi dalam paduan suara. Menyanyi juga membantu saya mengekspresikan diri.',
                'pengalaman' => 'Pernah ikut paduan suara gereja, les vokal selama 1 tahun, sering tampil di acara keluarga',
                'harapan' => 'Ingin mengembangkan kemampuan vokal dan ikut konser paduan suara sekolah',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(10),
                'disetujui_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'user_id' => $siswas->get(5)->id, // Sari Dewi
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'motivasi' => 'Saya percaya musik dapat menyatukan hati. Melalui paduan suara, saya ingin berkontribusi menciptakan harmoni yang indah bersama teman-teman.',
                'pengalaman' => 'Anggota paduan suara SMP, pernah juara festival paduan suara tingkat kabupaten',
                'harapan' => 'Ingin menjadi solois dalam beberapa lagu dan ikut kompetisi paduan suara',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(9),
                'disetujui_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(14),
            ],
            [
                'user_id' => $siswas->get(8)->id, // Bayu Kusuma (juga daftar paduan suara)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Paduan Suara')->first()->id,
                'motivasi' => 'Musik adalah universal language. Saya ingin belajar teknik vokal yang benar dan merasakan pengalaman bernyanyi secara berkelompok.',
                'pengalaman' => 'Suka bermain gitar dan bernyanyi, pernah ikut band acoustic di SMP',
                'harapan' => 'Ingin belajar harmonisasi dan mungkin bisa kolaborasi dengan alat musik',
                'tingkat_komitmen' => 'sedang',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(6),
            ],

            // Tari Tradisional - 4 pendaftar
            [
                'user_id' => $siswas->get(5)->id, // Sari Dewi (juga daftar tari)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Tari Tradisional')->first()->id,
                'motivasi' => 'Saya sangat mencintai budaya Indonesia, khususnya tari tradisional. Melalui tari, saya bisa melestarikan budaya nenek moyang sambil mengembangkan bakat seni.',
                'pengalaman' => 'Juara 1 tari tradisional tingkat kabupaten, les tari Sunda selama 3 tahun, sering tampil di acara budaya',
                'harapan' => 'Ingin menguasai berbagai jenis tari daerah dan tampil di festival budaya',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(8),
                'disetujui_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(13),
            ],
            [
                'user_id' => $siswas->get(17)->id, // Laila Maharani
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Tari Tradisional')->first()->id,
                'motivasi' => 'Tari tradisional mengajarkan kedisiplinan, kelembutan, dan kekuatan. Saya ingin menjadi bagian dari pelestarian budaya Indonesia melalui seni tari.',
                'pengalaman' => 'Pernah belajar tari Bali dan Jawa, mengikuti sanggar tari, memiliki fleksibilitas tubuh yang baik',
                'harapan' => 'Ingin menjadi penari utama dan mengajar tari kepada adik-adik kelas',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(7),
                'disetujui_oleh' => $pembinas->get(1)->id,
                'created_at' => Carbon::now()->subDays(12),
            ],

            // Robotika - 3 pendaftar
            [
                'user_id' => $siswas->get(4)->id, // Rizky Maulana
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'motivasi' => 'Teknologi robotika adalah masa depan. Saya ingin belajar dari dasar tentang pemrograman, elektronika, dan mekanika untuk menciptakan robot yang bermanfaat.',
                'pengalaman' => 'Pernah belajar programming Python dan Arduino secara otodidak, ikut workshop robotika saat SMP',
                'harapan' => 'Ingin membuat robot inovatif dan ikut kompetisi robotika tingkat nasional',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(6),
                'disetujui_oleh' => $pembinas->get(2)->id,
                'created_at' => Carbon::now()->subDays(11),
            ],
            [
                'user_id' => $siswas->get(18)->id, // Muhammad Fadli
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Robotika')->first()->id,
                'motivasi' => 'Saya tertarik dengan AI dan robotika. Ekstrakurikuler ini bisa menjadi stepping stone untuk melanjutkan studi di bidang engineering dan teknologi.',
                'pengalaman' => 'Juara 2 robotika tingkat kota saat SMP, familiar dengan programming dan elektronika dasar',
                'harapan' => 'Ingin mengembangkan robot yang lebih canggih dan belajar teknologi terbaru',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(5),
                'disetujui_oleh' => $pembinas->get(2)->id,
                'created_at' => Carbon::now()->subDays(10),
            ],

            // English Club - 4 pendaftar
            [
                'user_id' => $siswas->get(6)->id, // Rahman Hakim
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'English Club')->first()->id,
                'motivasi' => 'English is a global language. Saya ingin meningkatkan kemampuan speaking dan writing untuk persiapan kuliah luar negeri dan karir internasional.',
                'pengalaman' => 'Juara 2 English Speech Contest, sering menonton film dan membaca buku berbahasa Inggris, TOEFL score 450',
                'harapan' => 'Ingin fasih berbahasa Inggris dan bisa ikut kompetisi debat tingkat nasional',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(4),
                'disetujui_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(9),
            ],
            [
                'user_id' => $siswas->get(15)->id, // Jasmine Aulia
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'English Club')->first()->id,
                'motivasi' => 'Bahasa Inggris membuka peluang komunikasi global. Saya ingin mengasah kemampuan berbahasa Inggris sambil belajar budaya negara lain.',
                'pengalaman' => 'Aktif di forum online berbahasa Inggris, pernah ikut English camp, suka menulis blog dalam bahasa Inggris',
                'harapan' => 'Ingin menjadi MC dalam acara berbahasa Inggris dan ikut pertukaran pelajar',
                'tingkat_komitmen' => 'sedang',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(3),
                'disetujui_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(8),
            ],

            // Pramuka - 6 pendaftar
            [
                'user_id' => $siswas->get(2)->id, // Dimas Ardiansyah (juga daftar pramuka)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Pramuka')->first()->id,
                'motivasi' => 'Pramuka mengajarkan kepemimpinan dan kemandirian. Sebagai mantan ketua OSIS, saya ingin mengembangkan jiwa kepemimpinan dan membantu adik-adik kelas.',
                'pengalaman' => 'Ketua OSIS SMP, anggota pramuka sejak SD, pernah ikut kemah nasional',
                'harapan' => 'Ingin menjadi pemimpin regu dan mengajar pramuka kepada generasi muda',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(16),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(21),
            ],
            [
                'user_id' => $siswas->get(7)->id, // Anisa Putri
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Pramuka')->first()->id,
                'motivasi' => 'Pramuka membentuk karakter yang kuat dan jiwa sosial yang tinggi. Saya ingin berkontribusi dalam kegiatan bakti sosial dan pengembangan masyarakat.',
                'pengalaman' => 'Anggota pramuka SMP, sering ikut kegiatan sosial, memiliki sertifikat P3K',
                'harapan' => 'Ingin mengorganisir kegiatan bakti sosial dan menjadi teladan bagi adik-adik',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(2),
                'disetujui_oleh' => $pembinas->first()->id,
                'created_at' => Carbon::now()->subDays(7),
            ],

            // Jurnalistik - 3 pendaftar
            [
                'user_id' => $siswas->get(9)->id, // Dewi Sartika
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Jurnalistik')->first()->id,
                'motivasi' => 'Saya tertarik dengan dunia media dan komunikasi. Jurnalistik mengajarkan cara menyampaikan informasi yang akurat dan menarik kepada masyarakat.',
                'pengalaman' => 'Juara 1 fotografi tingkat sekolah, sering menulis di blog pribadi, admin media sosial kelas',
                'harapan' => 'Ingin menjadi jurnalis profesional dan berkontribusi dalam media sekolah',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(1),
                'disetujui_oleh' => $pembinas->get(3)->id,
                'created_at' => Carbon::now()->subDays(6),
            ],
            [
                'user_id' => $siswas->get(15)->id, // Jasmine Aulia (juga daftar jurnalistik)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Jurnalistik')->first()->id,
                'motivasi' => 'Media adalah jembatan informasi. Saya ingin belajar teknik penulisan berita, fotografi jurnalistik, dan video editing untuk membuat konten yang berkualitas.',
                'pengalaman' => 'Aktif menulis di media sosial, punya channel YouTube kecil, sering dokumentasi acara sekolah',
                'harapan' => 'Ingin membuat dokumenter tentang kehidupan sekolah dan belajar jurnalisme investigasi',
                'tingkat_komitmen' => 'sedang',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(4),
            ],

            // Badminton - 4 pendaftar
            [
                'user_id' => $siswas->get(16)->id, // Kevin Pratama (juga daftar badminton)
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Badminton')->first()->id,
                'motivasi' => 'Badminton adalah olahraga yang menggabungkan kecepatan, akurasi, dan strategi. Saya ingin meningkatkan kemampuan bermain badminton dan ikut kompetisi.',
                'pengalaman' => 'Juara 1 badminton tingkat sekolah saat SMP, sering bermain di club lokal, memiliki teknik dasar yang baik',
                'harapan' => 'Ingin menjadi pemain tunggal terbaik sekolah dan ikut kejuaraan antar SMA',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now()->subDays(1),
                'disetujui_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $siswas->get(13)->id, // Hana Safitri
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Badminton')->first()->id,
                'motivasi' => 'Badminton melatih reflexes dan ketahanan fisik. Saya ingin olahraga yang bisa dilakukan jangka panjang dan membantu menjaga kesehatan.',
                'pengalaman' => 'Bermain badminton rekreasi sejak kecil, pernah les badminton selama 6 bulan',
                'harapan' => 'Ingin mahir bermain ganda dan bisa bermain kompetitif',
                'tingkat_komitmen' => 'sedang',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
            ],

            // Desain Grafis - 3 pendaftar
            [
                'user_id' => $siswas->get(14)->id, // Irfan Maulana
                'ekstrakurikuler_id' => $ekstrakurikulers->where('nama', 'Desain Grafis')->first()->id,
                'motivasi' => 'Desain grafis menggabungkan seni dan teknologi. Saya ingin belajar software design professional dan mengembangkan kreativitas visual untuk masa depan di industri kreatif.',
                'pengalaman' => 'Juara 2 desain grafis tingkat kota, familiar dengan Photoshop dan Illustrator, sering membuat poster untuk acara sekolah',
                'harapan' => 'Ingin menguasai semua software desain dan freelance sebagai graphic designer',
                'tingkat_komitmen' => 'tinggi',
                'status' => 'disetujui',
                'disetujui_pada' => Carbon::now(),
                'disetujui_oleh' => $pembinas->get(4)->id,
                'created_at' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($pendaftarans as $pendaftaran) {
            Pendaftaran::create($pendaftaran);
        }

        $this->command->info('✓ Pendaftaran data seeded successfully - ' . count($pendaftarans) . ' pendaftaran created');

        // Update kapasitas ekstrakurikuler berdasarkan pendaftaran yang disetujui
        foreach ($ekstrakurikulers as $ekstrakurikuler) {
            $jumlahDisetujui = Pendaftaran::where('ekstrakurikuler_id', $ekstrakurikuler->id)
                ->where('status', 'disetujui')
                ->count();

            $ekstrakurikuler->update(['peserta_saat_ini' => $jumlahDisetujui]);
        }

        $this->command->info('✓ Ekstrakurikuler capacity updated based on approved registrations');
    }
}
