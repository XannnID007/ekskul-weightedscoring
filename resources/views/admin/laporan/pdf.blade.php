<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan {{ ucfirst($type) }} - MA Modern Miftahussa'adah</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #20B2AA;
        }

        .header h1 {
            font-size: 18px;
            color: #20B2AA;
            margin: 0 0 5px 0;
        }

        .header h2 {
            font-size: 14px;
            color: #666;
            margin: 0 0 8px 0;
        }

        .header .info {
            font-size: 9px;
            color: #888;
        }

        .stats {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .stats table {
            width: 100%;
            margin: 0;
        }

        .stats td {
            width: 25%;
            padding: 5px;
        }

        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #20B2AA;
        }

        .stat-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            background: #20B2AA;
            color: white;
            padding: 5px 10px;
            margin: 15px 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
            font-size: 7px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 15px;
            background: #f8f9fa;
        }

        .footer {
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN {{ strtoupper($type == 'all' ? 'LENGKAP' : $type) }}</h1>
        <h2>MA Modern Miftahussa'adah</h2>
        <div class="info">
            Periode: {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}<br>
            Digenerate: {{ $generated_at->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="stats">
        <table>
            <tr>
                <td>
                    <div class="stat-number">{{ $stats['total_siswa'] }}</div>
                    <div class="stat-label">Total Siswa</div>
                </td>
                <td>
                    <div class="stat-number">{{ $stats['total_ekstrakurikuler'] }}</div>
                    <div class="stat-label">Ekstrakurikuler</div>
                </td>
                <td>
                    <div class="stat-number">{{ $stats['total_pendaftaran'] }}</div>
                    <div class="stat-label">Pendaftaran</div>
                </td>
                <td>
                    <div class="stat-number">{{ $stats['partisipasi_persen'] }}%</div>
                    <div class="stat-label">Partisipasi</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Siswa -->
    @if ($type == 'siswa' || $type == 'all')
        <div class="section-title">Data Siswa</div>
        @if (isset($siswa) && $siswa->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">Nama</th>
                        <th style="width: 20%">Email</th>
                        <th style="width: 10%">NIS</th>
                        <th style="width: 8%">L/P</th>
                        <th style="width: 8%">Nilai</th>
                        <th style="width: 24%">Ekstrakurikuler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $index => $s)
                        @php
                            $pendaftaran = $s->pendaftarans()->where('status', 'disetujui')->first();
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->email }}</td>
                            <td class="text-center">{{ $s->nis ?? '-' }}</td>
                            <td class="text-center">{{ $s->jenis_kelamin ?? '-' }}</td>
                            <td class="text-center">{{ $s->nilai_rata_rata ?? '-' }}</td>
                            <td>{{ $pendaftaran ? $pendaftaran->ekstrakurikuler->nama : 'Belum terdaftar' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Tidak ada data siswa</div>
        @endif
    @endif

    <!-- Data Ekstrakurikuler -->
    @if ($type == 'ekstrakurikuler' || $type == 'all')
        @if ($type == 'all')
            <div class="page-break"></div>
        @endif
        <div class="section-title">Data Ekstrakurikuler</div>
        @if (isset($ekstrakurikulers) && $ekstrakurikulers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 30%">Nama</th>
                        <th style="width: 20%">Pembina</th>
                        <th style="width: 15%">Kategori</th>
                        <th style="width: 10%">Kapasitas</th>
                        <th style="width: 10%">Peserta</th>
                        <th style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ekstrakurikulers as $index => $ekskul)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $ekskul->nama }}</td>
                            <td>{{ $ekskul->pembina->name ?? '-' }}</td>
                            <td>{{ is_array($ekskul->kategori) ? implode(', ', $ekskul->kategori) : '-' }}</td>
                            <td class="text-center">{{ $ekskul->kapasitas_maksimal }}</td>
                            <td class="text-center">{{ $ekskul->peserta_saat_ini }}</td>
                            <td class="text-center">
                                <span class="badge badge-{{ $ekskul->is_active ? 'success' : 'danger' }}">
                                    {{ $ekskul->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Tidak ada data ekstrakurikuler</div>
        @endif
    @endif

    <!-- Data Pendaftaran -->
    @if ($type == 'pendaftaran' || $type == 'all')
        @if ($type == 'all')
            <div class="page-break"></div>
        @endif
        <div class="section-title">Data Pendaftaran</div>
        @if (isset($pendaftarans) && $pendaftarans->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">Nama Siswa</th>
                        <th style="width: 30%">Ekstrakurikuler</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 10%">Komitmen</th>
                        <th style="width: 15%">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendaftarans as $index => $pendaftaran)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $pendaftaran->user->name }}</td>
                            <td>{{ $pendaftaran->ekstrakurikuler->nama }}</td>
                            <td class="text-center">
                                <span
                                    class="badge badge-{{ $pendaftaran->status == 'disetujui' ? 'success' : ($pendaftaran->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($pendaftaran->status) }}
                                </span>
                            </td>
                            <td class="text-center">{{ ucfirst($pendaftaran->tingkat_komitmen ?? '-') }}</td>
                            <td class="text-center">{{ $pendaftaran->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Tidak ada data pendaftaran</div>
        @endif
    @endif

    <!-- Data Rekomendasi -->
    @if ($type == 'rekomendasi' || $type == 'all')
        @if ($type == 'all')
            <div class="page-break"></div>
        @endif
        <div class="section-title">Data Rekomendasi</div>
        @if (isset($rekomendasis) && $rekomendasis->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 25%">Nama Siswa</th>
                        <th style="width: 30%">Ekstrakurikuler</th>
                        <th style="width: 10%">Skor Minat</th>
                        <th style="width: 10%">Skor Akademik</th>
                        <th style="width: 10%">Total Skor</th>
                        <th style="width: 10%">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekomendasis as $index => $rekomendasi)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $rekomendasi->user->name }}</td>
                            <td>{{ $rekomendasi->ekstrakurikuler->nama }}</td>
                            <td class="text-center">{{ number_format($rekomendasi->skor_minat, 1) }}</td>
                            <td class="text-center">{{ number_format($rekomendasi->skor_akademik, 1) }}</td>
                            <td class="text-center">{{ number_format($rekomendasi->total_skor, 1) }}</td>
                            <td class="text-center">{{ $rekomendasi->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Tidak ada data rekomendasi</div>
        @endif
    @endif

    <!-- Footer -->
    <div class="footer">
        MA Modern Miftahussa'adah - Sistem Manajemen Ekstrakurikuler<br>
        Laporan {{ ucfirst($type) }} | {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}
    </div>
</body>

</html>
