<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ ucfirst($type) }} - MA Modern Miftahussa'adah</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #20B2AA;
        }

        .header h1 {
            font-size: 24px;
            color: #20B2AA;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }

        .header .school-name {
            font-size: 14px;
            color: #888;
            margin-bottom: 5px;
        }

        .header .period {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }

        .summary-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #20B2AA;
            display: block;
        }

        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #20B2AA;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        table th {
            background-color: #20B2AA;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        table td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tbody tr:hover {
            background-color: #e8f4f8;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-disetujui {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }

        .gender-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }

        .gender-l {
            background-color: #cfe2ff;
            color: #084298;
        }

        .gender-p {
            background-color: #f7d6e6;
            color: #b02a5b;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            background: white;
        }

        .page-number:before {
            content: "Halaman " counter(page);
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN {{ strtoupper($type == 'all' ? 'LENGKAP' : $type) }}</h1>
        <h2>Sistem Manajemen Ekstrakurikuler</h2>
        <div class="school-name">MA Modern Miftahussa'adah</div>
        <div class="period">
            Periode: {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}
        </div>
        <div class="period">
            Digenerate pada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-stats">
        <div class="stat-item">
            <span class="stat-number">{{ $stats['total_siswa'] }}</span>
            <div class="stat-label">Total Siswa</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['total_ekstrakurikuler'] }}</span>
            <div class="stat-label">Ekstrakurikuler</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['total_pendaftaran'] }}</span>
            <div class="stat-label">Pendaftaran</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['partisipasi_persen'] }}%</span>
            <div class="stat-label">Partisipasi</div>
        </div>
    </div>

    <!-- Content based on type -->
    @if ($type == 'siswa' || $type == 'all')
        <div class="section">
            <h3 class="section-title">Data Siswa</h3>
            @if (isset($siswa) && $siswa->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama</th>
                            <th width="15%">Email</th>
                            <th width="10%">NIS</th>
                            <th width="8%">Gender</th>
                            <th width="10%">Nilai</th>
                            <th width="15%">Ekstrakurikuler</th>
                            <th width="12%">Status</th>
                            <th width="10%">Tgl Daftar</th>
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
                                <td class="text-center">
                                    @if ($s->jenis_kelamin == 'L')
                                        <span class="gender-badge gender-l">L</span>
                                    @elseif($s->jenis_kelamin == 'P')
                                        <span class="gender-badge gender-p">P</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{{ $s->nilai_rata_rata ?? '-' }}</td>
                                <td>{{ $pendaftaran ? $pendaftaran->ekstrakurikuler->nama : '-' }}</td>
                                <td class="text-center">
                                    @if ($pendaftaran)
                                        <span class="status-badge status-disetujui">Terdaftar</span>
                                    @else
                                        <span class="status-badge status-pending">Belum</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $s->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada data siswa yang ditemukan</div>
            @endif
        </div>
    @endif

    @if ($type == 'ekstrakurikuler' || $type == 'all')
        <div class="section">
            <h3 class="section-title">Data Ekstrakurikuler</h3>
            @if (isset($ekstrakurikulers) && $ekstrakurikulers->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Ekstrakurikuler</th>
                            <th width="20%">Pembina</th>
                            <th width="15%">Kategori</th>
                            <th width="8%">Kapasitas</th>
                            <th width="8%">Peserta</th>
                            <th width="12%">Jadwal</th>
                            <th width="7%">Status</th>
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
                                <td>{{ $ekskul->jadwal_string }}</td>
                                <td class="text-center">
                                    @if ($ekskul->is_active)
                                        <span class="status-badge status-disetujui">Aktif</span>
                                    @else
                                        <span class="status-badge status-ditolak">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada data ekstrakurikuler yang ditemukan</div>
            @endif
        </div>
    @endif

    @if ($type == 'pendaftaran' || $type == 'all')
        <div class="section">
            <h3 class="section-title">Data Pendaftaran</h3>
            @if (isset($pendaftarans) && $pendaftarans->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama Siswa</th>
                            <th width="25%">Ekstrakurikuler</th>
                            <th width="15%">Pembina</th>
                            <th width="10%">Status</th>
                            <th width="10%">Komitmen</th>
                            <th width="15%">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendaftarans as $index => $pendaftaran)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $pendaftaran->user->name }}</td>
                                <td>{{ $pendaftaran->ekstrakurikuler->nama }}</td>
                                <td>{{ $pendaftaran->ekstrakurikuler->pembina->name ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($pendaftaran->status == 'pending')
                                        <span class="status-badge status-pending">Pending</span>
                                    @elseif($pendaftaran->status == 'disetujui')
                                        <span class="status-badge status-disetujui">Disetujui</span>
                                    @else
                                        <span class="status-badge status-ditolak">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ ucfirst($pendaftaran->tingkat_komitmen ?? '-') }}</td>
                                <td class="text-center">{{ $pendaftaran->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada data pendaftaran yang ditemukan</div>
            @endif
        </div>
    @endif

    @if ($type == 'rekomendasi' || $type == 'all')
        <div class="section">
            <h3 class="section-title">Data Rekomendasi</h3>
            @if (isset($rekomendasis) && $rekomendasis->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Siswa</th>
                            <th width="25%">Ekstrakurikuler</th>
                            <th width="10%">Skor Minat</th>
                            <th width="10%">Skor Akademik</th>
                            <th width="10%">Total Skor</th>
                            <th width="15%">Tanggal</th>
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
                <div class="no-data">Tidak ada data rekomendasi yang ditemukan</div>
            @endif
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>MA Modern Miftahussa'adah - Sistem Manajemen Ekstrakurikuler</div>
        <div class="page-number"></div>
    </div>
</body>

</html>
