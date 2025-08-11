<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Ekstrakurikuler;
use App\Models\Pendaftaran;
use App\Models\Rekomendasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LaporanExport implements WithMultipleSheets
{
     private $type;
     private $startDate;
     private $endDate;

     public function __construct($type, $startDate, $endDate)
     {
          $this->type = $type;
          $this->startDate = $startDate;
          $this->endDate = $endDate;
     }

     public function sheets(): array
     {
          $sheets = [];

          switch ($this->type) {
               case 'siswa':
                    $sheets[] = new SiswaSheet($this->startDate, $this->endDate);
                    break;
               case 'ekstrakurikuler':
                    $sheets[] = new EkstrakurikulerSheet();
                    break;
               case 'pendaftaran':
                    $sheets[] = new PendaftaranSheet($this->startDate, $this->endDate);
                    break;
               case 'rekomendasi':
                    $sheets[] = new RekomendasiSheet($this->startDate, $this->endDate);
                    break;
               case 'all':
               default:
                    $sheets[] = new SiswaSheet($this->startDate, $this->endDate);
                    $sheets[] = new EkstrakurikulerSheet();
                    $sheets[] = new PendaftaranSheet($this->startDate, $this->endDate);
                    $sheets[] = new RekomendasiSheet($this->startDate, $this->endDate);
                    break;
          }

          return $sheets;
     }
}

class SiswaSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
     private $startDate;
     private $endDate;

     public function __construct($startDate, $endDate)
     {
          $this->startDate = $startDate;
          $this->endDate = $endDate;
     }

     public function collection()
     {
          return User::siswa()
               ->whereBetween('created_at', [$this->startDate, $this->endDate])
               ->with(['pendaftarans.ekstrakurikuler'])
               ->orderBy('name')
               ->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'Nama',
               'Email',
               'NIS',
               'Jenis Kelamin',
               'Tanggal Lahir',
               'Nilai Rata-rata',
               'Alamat',
               'Telepon',
               'Status Ekstrakurikuler',
               'Ekstrakurikuler Diikuti',
               'Tanggal Daftar'
          ];
     }

     public function map($siswa): array
     {
          static $no = 0;
          $no++;

          $pendaftaran = $siswa->pendaftarans()->where('status', 'disetujui')->first();

          return [
               $no,
               $siswa->name,
               $siswa->email,
               $siswa->nis ?? '-',
               $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
               $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d/m/Y') : '-',
               $siswa->nilai_rata_rata ?? '-',
               $siswa->alamat ?? '-',
               $siswa->telepon ?? '-',
               $pendaftaran ? 'Terdaftar' : 'Belum Terdaftar',
               $pendaftaran ? $pendaftaran->ekstrakurikuler->nama : '-',
               $siswa->created_at->format('d/m/Y')
          ];
     }

     public function title(): string
     {
          return 'Data Siswa';
     }

     public function styles(Worksheet $sheet)
     {
          return [
               1 => [
                    'font' => [
                         'bold' => true,
                         'color' => ['argb' => Color::COLOR_WHITE],
                    ],
                    'fill' => [
                         'fillType' => Fill::FILL_SOLID,
                         'startColor' => ['argb' => '20B2AA'],
                    ],
               ],
          ];
     }
}

class EkstrakurikulerSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
     public function collection()
     {
          return Ekstrakurikuler::with(['pembina', 'pendaftarans'])
               ->withCount(['pendaftarans as total_pendaftar'])
               ->orderBy('nama')
               ->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'Nama Ekstrakurikuler',
               'Pembina',
               'Kategori',
               'Kapasitas Maksimal',
               'Peserta Saat Ini',
               'Total Pendaftar',
               'Jadwal',
               'Nilai Minimal',
               'Status',
               'Tingkat Okupansi (%)'
          ];
     }

     public function map($ekskul): array
     {
          static $no = 0;
          $no++;

          // Handle kategori safely
          $kategori = '';
          if ($ekskul->kategori) {
               if (is_array($ekskul->kategori)) {
                    $kategori = implode(', ', $ekskul->kategori);
               } elseif (is_string($ekskul->kategori)) {
                    $decoded = json_decode($ekskul->kategori, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                         $kategori = implode(', ', $decoded);
                    } else {
                         $kategori = $ekskul->kategori;
                    }
               }
          }

          $okupansi = $ekskul->kapasitas_maksimal > 0 ?
               round(($ekskul->peserta_saat_ini / $ekskul->kapasitas_maksimal) * 100, 1) : 0;

          return [
               $no,
               $ekskul->nama,
               $ekskul->pembina->name ?? '-',
               $kategori,
               $ekskul->kapasitas_maksimal,
               $ekskul->peserta_saat_ini,
               $ekskul->total_pendaftar,
               $ekskul->jadwal_string,
               $ekskul->nilai_minimal,
               $ekskul->is_active ? 'Aktif' : 'Nonaktif',
               $okupansi . '%'
          ];
     }

     public function title(): string
     {
          return 'Data Ekstrakurikuler';
     }

     public function styles(Worksheet $sheet)
     {
          return [
               1 => [
                    'font' => [
                         'bold' => true,
                         'color' => ['argb' => Color::COLOR_WHITE],
                    ],
                    'fill' => [
                         'fillType' => Fill::FILL_SOLID,
                         'startColor' => ['argb' => '10B981'],
                    ],
               ],
          ];
     }
}

class PendaftaranSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
     private $startDate;
     private $endDate;

     public function __construct($startDate, $endDate)
     {
          $this->startDate = $startDate;
          $this->endDate = $endDate;
     }

     public function collection()
     {
          return Pendaftaran::with(['user', 'ekstrakurikuler.pembina'])
               ->whereBetween('created_at', [$this->startDate, $this->endDate])
               ->orderBy('created_at', 'desc')
               ->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'Tanggal Daftar',
               'Nama Siswa',
               'Email',
               'NIS',
               'Ekstrakurikuler',
               'Pembina',
               'Status',
               'Motivasi',
               'Tingkat Komitmen',
               'Tanggal Disetujui',
               'Alasan Penolakan'
          ];
     }

     public function map($pendaftaran): array
     {
          static $no = 0;
          $no++;

          return [
               $no,
               $pendaftaran->created_at->format('d/m/Y H:i'),
               $pendaftaran->user->name,
               $pendaftaran->user->email,
               $pendaftaran->user->nis ?? '-',
               $pendaftaran->ekstrakurikuler->nama,
               $pendaftaran->ekstrakurikuler->pembina->name ?? '-',
               ucfirst($pendaftaran->status),
               $pendaftaran->motivasi,
               ucfirst($pendaftaran->tingkat_komitmen ?? '-'),
               $pendaftaran->disetujui_pada ? $pendaftaran->disetujui_pada->format('d/m/Y H:i') : '-',
               $pendaftaran->alasan_penolakan ?? '-'
          ];
     }

     public function title(): string
     {
          return 'Data Pendaftaran';
     }

     public function styles(Worksheet $sheet)
     {
          return [
               1 => [
                    'font' => [
                         'bold' => true,
                         'color' => ['argb' => Color::COLOR_WHITE],
                    ],
                    'fill' => [
                         'fillType' => Fill::FILL_SOLID,
                         'startColor' => ['argb' => 'F59E0B'],
                    ],
               ],
          ];
     }
}

class RekomendasiSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
     private $startDate;
     private $endDate;

     public function __construct($startDate, $endDate)
     {
          $this->startDate = $startDate;
          $this->endDate = $endDate;
     }

     public function collection()
     {
          return Rekomendasi::with(['user', 'ekstrakurikuler'])
               ->whereBetween('created_at', [$this->startDate, $this->endDate])
               ->orderBy('total_skor', 'desc')
               ->get();
     }

     public function headings(): array
     {
          return [
               'No',
               'Nama Siswa',
               'Email',
               'NIS',
               'Ekstrakurikuler',
               'Skor Minat',
               'Skor Akademik',
               'Skor Jadwal',
               'Total Skor',
               'Alasan',
               'Tanggal Generate'
          ];
     }

     public function map($rekomendasi): array
     {
          static $no = 0;
          $no++;

          return [
               $no,
               $rekomendasi->user->name,
               $rekomendasi->user->email,
               $rekomendasi->user->nis ?? '-',
               $rekomendasi->ekstrakurikuler->nama,
               $rekomendasi->skor_minat,
               $rekomendasi->skor_akademik,
               $rekomendasi->skor_jadwal,
               $rekomendasi->total_skor,
               $rekomendasi->alasan,
               $rekomendasi->created_at->format('d/m/Y H:i')
          ];
     }

     public function title(): string
     {
          return 'Data Rekomendasi';
     }

     public function styles(Worksheet $sheet)
     {
          return [
               1 => [
                    'font' => [
                         'bold' => true,
                         'color' => ['argb' => Color::COLOR_WHITE],
                    ],
                    'fill' => [
                         'fillType' => Fill::FILL_SOLID,
                         'startColor' => ['argb' => '8B5CF6'],
                    ],
               ],
          ];
     }
}
