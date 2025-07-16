@extends('layouts.app')

@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')
@section('page-description', 'Monitor kehadiran dan absensi Anda')

@section('page-actions')
    <div class="btn-group">
        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-download me-1"></i>Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('siswa.kehadiran.export') }}?format=pdf">
                    <i class="bi bi-file-pdf me-2"></i>Export PDF
                </a></li>
            <li><a class="dropdown-item" href="{{ route('siswa.kehadiran.export') }}?format=excel">
                    <i class="bi bi-file-excel me-2"></i>Export Excel
                </a></li>
        </ul>
    </div>
@endsection

@section('content')
    @php
        $user = auth()->user();
        $pendaftaran = $user
            ->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler.pembina'])
            ->first();
        $ekstrakurikuler = $pendaftaran ? $pendaftaran->ekstrakurikuler : null;

        // Mock data untuk demonstrasi
        $attendanceStats = [
            'total_kegiatan' => 15,
            'hadir' => 12,
            'izin' => 2,
            'terlambat' => 1,
            'alpa' => 0,
        ];

        $attendanceData = [
            [
                'date' => '2024-06-24',
                'day' => 'Senin',
                'time' => '15:30',
                'status' => 'hadir',
                'note' => '-',
                'recorder' => 'Budi Santoso',
            ],
            [
                'date' => '2024-06-17',
                'day' => 'Senin',
                'time' => '15:45',
                'status' => 'terlambat',
                'note' => 'Terlambat 15 menit',
                'recorder' => 'Budi Santoso',
            ],
            [
                'date' => '2024-06-10',
                'day' => 'Senin',
                'time' => '15:30',
                'status' => 'izin',
                'note' => 'Sakit demam',
                'recorder' => 'Budi Santoso',
            ],
            [
                'date' => '2024-06-03',
                'day' => 'Senin',
                'time' => '15:30',
                'status' => 'hadir',
                'note' => '-',
                'recorder' => 'Budi Santoso',
            ],
            [
                'date' => '2024-05-27',
                'day' => 'Senin',
                'time' => '15:30',
                'status' => 'hadir',
                'note' => '-',
                'recorder' => 'Budi Santoso',
            ],
            [
                'date' => '2024-05-20',
                'day' => 'Senin',
                'time' => '15:30',
                'status' => 'izin',
                'note' => 'Acara keluarga',
                'recorder' => 'Budi Santoso',
            ],
        ];
    @endphp

    @if ($ekstrakurikuler)
        <div class="row g-4">
            <!-- Summary Cards -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1 text-white">Total Kehadiran</h6>
                                <h2 class="mb-0 text-white">{{ $attendanceStats['hadir'] }}</h2>
                                <small class="opacity-75">Dari {{ $attendanceStats['total_kegiatan'] }} kegiatan</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            @php $hadirPercentage = $attendanceStats['total_kegiatan'] > 0 ? round(($attendanceStats['hadir'] / $attendanceStats['total_kegiatan']) * 100) : 0; @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-white" style="width: {{ $hadirPercentage }}%"></div>
                            </div>
                            <small class="text-white-50 mt-1">{{ $hadirPercentage }}% tingkat kehadiran</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stats-card warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1 text-white">Izin</h6>
                                <h2 class="mb-0 text-white">{{ $attendanceStats['izin'] }}</h2>
                                <small class="opacity-75">Kali tidak hadir</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            @php $izinPercentage = $attendanceStats['total_kegiatan'] > 0 ? round(($attendanceStats['izin'] / $attendanceStats['total_kegiatan']) * 100) : 0; @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-white" style="width: {{ $izinPercentage }}%"></div>
                            </div>
                            <small class="text-white-50 mt-1">{{ $izinPercentage }}% dari total kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stats-card bg-info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1 text-white">Terlambat</h6>
                                <h2 class="mb-0 text-white">{{ $attendanceStats['terlambat'] }}</h2>
                                <small class="opacity-75">Kali terlambat</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            @php $terlambatPercentage = $attendanceStats['total_kegiatan'] > 0 ? round(($attendanceStats['terlambat'] / $attendanceStats['total_kegiatan']) * 100) : 0; @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-white" style="width: {{ $terlambatPercentage }}%"></div>
                            </div>
                            <small class="text-white-50 mt-1">{{ $terlambatPercentage }}% dari total kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stats-card danger h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1 text-white">Alpa</h6>
                                <h2 class="mb-0 text-white">{{ $attendanceStats['alpa'] }}</h2>
                                <small class="opacity-75">Tanpa keterangan</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            @php $alpaPercentage = $attendanceStats['total_kegiatan'] > 0 ? round(($attendanceStats['alpa'] / $attendanceStats['total_kegiatan']) * 100) : 0; @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-white" style="width: {{ max($alpaPercentage, 5) }}%"></div>
                            </div>
                            <small class="text-white-50 mt-1">{{ $alpaPercentage }}% dari total kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart & Progress -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>Tren Kehadiran Bulanan
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active" data-period="6">6 Bulan</button>
                            <button class="btn btn-outline-primary" data-period="12">1 Tahun</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <!-- Progress Circle -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-target me-2"></i>Target Kehadiran
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="position-relative mb-4">
                            @php $overallPercentage = $attendanceStats['total_kegiatan'] > 0 ? round(($attendanceStats['hadir'] / $attendanceStats['total_kegiatan']) * 100) : 0; @endphp
                            <svg width="150" height="150" class="circular-chart">
                                <circle cx="75" cy="75" r="60" stroke="rgba(255,255,255,0.1)"
                                    stroke-width="12" fill="none" />
                                <circle cx="75" cy="75" r="60" stroke="#28a745" stroke-width="12"
                                    fill="none" stroke-dasharray="377"
                                    stroke-dashoffset="{{ 377 - (377 * $overallPercentage) / 100 }}"
                                    transform="rotate(-90 75 75)" class="progress-circle" />
                            </svg>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0 text-success">{{ $overallPercentage }}%</h3>
                                <small class="text-muted">Kehadiran</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Target: 80%</small>
                                @if ($overallPercentage >= 80)
                                    <small class="text-success">✓ Tercapai</small>
                                @else
                                    <small class="text-warning">Belum Tercapai</small>
                                @endif
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $overallPercentage >= 80 ? 'bg-success' : 'bg-warning' }}"
                                    style="width: {{ min($overallPercentage, 100) }}%"></div>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            Pertahankan kehadiran di atas 80% untuk mendapat sertifikat keaktifan.
                        </p>

                        @if ($overallPercentage >= 80)
                            <div class="alert alert-success">
                                <i class="bi bi-trophy me-2"></i>
                                <strong>Target Tercapai!</strong><br>
                                <small>Anda memenuhi syarat sertifikat keaktifan.</small>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-target me-2"></i>
                                <strong>Tingkatkan Kehadiran!</strong><br>
                                <small>Anda perlu {{ 80 - $overallPercentage }}% lagi untuk mencapai target.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Kehadiran -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>Detail Kehadiran
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="filterByMonth('all')">Semua</button>
                            <button class="btn btn-outline-primary" onclick="filterByMonth('current')">Bulan Ini</button>
                            <button class="btn btn-outline-primary active" onclick="filterByMonth('last')">Bulan
                                Lalu</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th>Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendanceData as $index => $attendance)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('d M Y') }}</td>
                                            <td>{{ $attendance['day'] }}</td>
                                            <td>{{ $attendance['time'] }}</td>
                                            <td>
                                                @if ($attendance['status'] == 'hadir')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Hadir
                                                    </span>
                                                @elseif($attendance['status'] == 'izin')
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-exclamation-circle me-1"></i>Izin
                                                    </span>
                                                @elseif($attendance['status'] == 'terlambat')
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-clock me-1"></i>Terlambat
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Alpa
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $attendance['note'] }}</td>
                                            <td>{{ $attendance['recorder'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No Ekstrakurikuler -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-graph-down text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 mb-2">Belum Ada Data Kehadiran</h4>
                        <p class="text-muted mb-4">
                            Anda belum terdaftar pada ekstrakurikuler apapun. Daftar terlebih dahulu untuk melihat rekap
                            kehadiran.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-primary">
                                <i class="bi bi-stars me-1"></i>Lihat Rekomendasi
                            </a>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-collection me-1"></i>Jelajahi Ekstrakurikuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Attendance Chart
        const ctx = document.getElementById('attendanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Kehadiran (%)',
                        data: [85, 90, 78, 88, 92, {{ $overallPercentage }}],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#e9ecef'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#e9ecef',
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#e9ecef'
                            }
                        }
                    }
                }
            });
        }

        function filterByMonth(period) {
            // Update active button
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter logic here
            console.log('Filter by:', period);
        }

        // Animate progress circle
        document.addEventListener('DOMContentLoaded', function() {
            const circle = document.querySelector('.progress-circle');
            if (circle) {
                setTimeout(() => {
                    circle.style.transition = 'stroke-dashoffset 2s ease-in-out';
                }, 500);
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .progress-circle {
            transition: stroke-dashoffset 2s ease-in-out;
        }

        .stats-card {
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #e9ecef;
        }

        .badge {
            font-size: 0.8em;
        }

        .circular-chart {
            transform: rotate(-90deg);
        }
    </style>
@endpush
