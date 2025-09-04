<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ekstrakurikuler;
use App\Models\Rekomendasi;
use Carbon\Carbon;

class RekomendasiService
{
     // Bobot untuk weighted scoring
     const BOBOT_MINAT = 0.5;     // 50%
     const BOBOT_AKADEMIK = 0.3;  // 30%
     const BOBOT_JADWAL = 0.2;    // 20%

     public function generateRekomendasi(User $siswa)
     {
          Rekomendasi::where('user_id', $siswa->id)->delete();

          $ekstrakurikulers = Ekstrakurikuler::aktif()->tersedia()->get();
          $rekomendasis = [];
          foreach ($ekstrakurikulers as $ekstrakurikuler) {
               // Hitung skor untuk setiap kriteria
               $skorMinat = $this->hitungSkorMinat($siswa, $ekstrakurikuler);
               $skorAkademik = $this->hitungSkorAkademik($siswa, $ekstrakurikuler);
               $skorJadwal = $this->hitungSkorJadwal($siswa, $ekstrakurikuler);

               // Hitung total skor dengan weighted scoring
               $totalSkor = ($skorMinat * self::BOBOT_MINAT) +
                    ($skorAkademik * self::BOBOT_AKADEMIK) +
                    ($skorJadwal * self::BOBOT_JADWAL);

               $alasan = $this->generateAlasan($siswa, $ekstrakurikuler, $skorMinat, $skorAkademik, $skorJadwal);

               $rekomendasi = Rekomendasi::create([
                    'user_id' => $siswa->id,
                    'ekstrakurikuler_id' => $ekstrakurikuler->id,
                    'skor_minat' => $skorMinat,
                    'skor_akademik' => $skorAkademik,
                    'skor_jadwal' => $skorJadwal,
                    'total_skor' => $totalSkor,
                    'alasan' => $alasan
               ]);
               $rekomendasis[] = $rekomendasi;
          }
          return collect($rekomendasis)->sortByDesc('total_skor');
     }

     private function hitungSkorMinat(User $siswa, Ekstrakurikuler $ekstrakurikuler)
     {
          $minatSiswa = $this->getArrayFromJsonField($siswa->minat);
          $kategoriEkskul = $this->getArrayFromJsonField($ekstrakurikuler->kategori);

          if (empty($minatSiswa) || empty($kategoriEkskul)) {
               return 50;
          }
          if (!is_array($minatSiswa)) {
               $minatSiswa = [];
          }
          if (!is_array($kategoriEkskul)) {
               $kategoriEkskul = [];
          }
          $kecocokan = [];
          if (!empty($minatSiswa) && !empty($kategoriEkskul)) {
               $kecocokan = array_intersect($minatSiswa, $kategoriEkskul);
          }
          $jumlahKecocokan = count($kecocokan);
          $jumlahKategori = count($kategoriEkskul);
          if ($jumlahKategori === 0) {
               return 50;
          }
          $persentaseKecocokan = $jumlahKecocokan / $jumlahKategori;
          $skor = $persentaseKecocokan * 100;
          if ($persentaseKecocokan >= 0.8) {
               $skor += 10;
          }
          return min(100, $skor);
     }

     private function hitungSkorAkademik(User $siswa, Ekstrakurikuler $ekstrakurikuler)
     {
          $nilaiSiswa = $siswa->nilai_rata_rata ?? 0;
          $nilaiMinimal = $ekstrakurikuler->nilai_minimal ?? 0;

          if ($nilaiSiswa == 0) {
               return 50; // Skor netral jika nilai belum diisi
          }

          if ($nilaiSiswa < $nilaiMinimal) {
               return 20; // Skor rendah jika tidak memenuhi syarat minimal
          }

          // Hitung skor berdasarkan seberapa baik nilai siswa
          if ($nilaiSiswa >= 90) {
               return 100; // Nilai sangat baik
          } elseif ($nilaiSiswa >= 80) {
               return 85;  // Nilai baik
          } elseif ($nilaiSiswa >= 75) {
               return 70;  // Nilai cukup baik
          } else {
               return 55;  // Nilai cukup
          }
     }

     private function hitungSkorJadwal(User $siswa, Ekstrakurikuler $ekstrakurikuler)
     {
          // Untuk saat ini, berikan skor berdasarkan preferensi umum

          $jadwal = $this->getArrayFromJsonField($ekstrakurikuler->jadwal);
          $hari = $jadwal['hari'] ?? '';
          $waktu = $jadwal['waktu'] ?? '';

          $skor = 70; // Skor default

          // Preferensi berdasarkan hari
          $preferensiHari = [
               'senin' => 60,
               'selasa' => 80,
               'rabu' => 75,
               'kamis' => 85,
               'jumat' => 70,
               'sabtu' => 90, // Weekend lebih disukai
               'minggu' => 50
          ];

          if (isset($preferensiHari[strtolower($hari)])) {
               $skor = $preferensiHari[strtolower($hari)];
          }

          // Adjustasi berdasarkan waktu
          if (strpos($waktu, '15:') !== false || strpos($waktu, '16:') !== false) {
               $skor += 10; // Bonus untuk waktu pulang sekolah yang ideal
          }

          if (strpos($waktu, '08:') !== false && strpos($hari, 'sabtu') !== false) {
               $skor += 15; // Bonus untuk Sabtu pagi
          }

          return min(100, $skor);
     }

     private function generateAlasan(User $siswa, Ekstrakurikuler $ekstrakurikuler, $skorMinat, $skorAkademik, $skorJadwal)
     {
          $alasan = [];

          // Alasan berdasarkan minat
          if ($skorMinat >= 80) {
               $alasan[] = "sangat sesuai dengan minat Anda";
          } elseif ($skorMinat >= 60) {
               $alasan[] = "cukup sesuai dengan minat Anda";
          }

          // Alasan berdasarkan akademik
          if ($skorAkademik >= 85) {
               $alasan[] = "nilai akademik Anda sangat mendukung";
          } elseif ($skorAkademik >= 70) {
               $alasan[] = "nilai akademik Anda mendukung";
          }

          // Alasan berdasarkan jadwal
          if ($skorJadwal >= 80) {
               $alasan[] = "jadwal kegiatan sangat fleksibel";
          } elseif ($skorJadwal >= 60) {
               $alasan[] = "jadwal kegiatan cukup fleksibel";
          }

          // Alasan tambahan berdasarkan karakteristik ekstrakurikuler
          $kategori = $this->getArrayFromJsonField($ekstrakurikuler->kategori);

          if (in_array('olahraga', $kategori)) {
               $alasan[] = "baik untuk kesehatan dan kebugaran";
          }
          if (in_array('seni', $kategori)) {
               $alasan[] = "mengembangkan kreativitas dan bakat seni";
          }
          if (in_array('akademik', $kategori)) {
               $alasan[] = "mendukung prestasi akademik";
          }
          if (in_array('teknologi', $kategori)) {
               $alasan[] = "mengikuti perkembangan teknologi modern";
          }

          // Gabungkan alasan
          if (empty($alasan)) {
               return "Ekstrakurikuler ini cocok untuk pengembangan diri Anda";
          }

          return ucfirst(implode(', ', $alasan)) . ".";
     }

     public function getTopRekomendasi(User $siswa, $limit = 3)
     {
          return Rekomendasi::where('user_id', $siswa->id)
               ->with('ekstrakurikuler')
               ->orderBy('total_skor', 'desc')
               ->limit($limit)
               ->get();
     }

     public function cekKelengkapanProfil(User $siswa)
     {
          $required_fields = ['minat', 'nilai_rata_rata', 'tanggal_lahir', 'jenis_kelamin'];
          $filled_fields = 0;

          foreach ($required_fields as $field) {
               if (!empty($siswa->$field)) {
                    $filled_fields++;
               }
          }

          return [
               'lengkap' => $filled_fields == count($required_fields),
               'persentase' => round(($filled_fields / count($required_fields)) * 100),
               'fields_kosong' => array_filter($required_fields, function ($field) use ($siswa) {
                    return empty($siswa->$field);
               })
          ];
     }

     /**
      * Helper method untuk mengkonversi JSON field ke array
      * Handle berbagai kemungkinan format data
      */
     private function getArrayFromJsonField($field)
     {
          // Jika null atau empty
          if (empty($field)) {
               return [];
          }

          // Jika sudah array, return langsung
          if (is_array($field)) {
               return array_filter($field); // Remove empty values
          }

          // Jika string, coba decode JSON
          if (is_string($field)) {
               // Trim whitespace
               $field = trim($field);

               // Jika empty setelah trim
               if (empty($field)) {
                    return [];
               }

               $decoded = json_decode($field, true);

               // Jika berhasil decode dan hasilnya array
               if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_filter($decoded); // Remove empty values
               }

               // Jika bukan JSON valid, coba split by comma (fallback)
               if (strpos($field, ',') !== false) {
                    $items = array_map('trim', explode(',', $field));
                    return array_filter($items); // Remove empty values
               }

               // Jika single value, wrap dalam array
               return [$field];
          }

          // Default return empty array
          return [];
     }

     /**
      * Debug method untuk troubleshooting
      */
     public function debugMinatData(User $siswa, Ekstrakurikuler $ekstrakurikuler)
     {
          $minatSiswa = $siswa->minat;
          $kategoriEkskul = $ekstrakurikuler->kategori;

          return [
               'minat_siswa_raw' => $minatSiswa,
               'minat_siswa_type' => gettype($minatSiswa),
               'minat_siswa_processed' => $this->getArrayFromJsonField($minatSiswa),
               'kategori_ekskul_raw' => $kategoriEkskul,
               'kategori_ekskul_type' => gettype($kategoriEkskul),
               'kategori_ekskul_processed' => $this->getArrayFromJsonField($kategoriEkskul),
          ];


          // Default return empty array
          return [];
     }
}
