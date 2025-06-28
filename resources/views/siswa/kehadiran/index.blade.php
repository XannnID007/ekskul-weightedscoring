@extends('layouts.app')

@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')
@section('page-description', 'Monitor kehadiran dan absensi Anda')

@section('page-actions')
    <a href="{{ route('siswa.kehadiran.export') }}" class="btn btn-outline-light">
        <i class="bi bi-download me-1"></i>Export PDF
    </a>
@endsection

@section('content')
    @php
        $ekstrakurikuler = auth()->user()->ekstrakurikulers()->wherePivot('status', 'disetujui')->first();
    @endphp

    @if ($ekstrakurikuler)
        <div class="row g-4">
            <!-- Summary Cards -->
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Kehadiran</h6>
                                <h2 class="mb-0">10</h2>
                                <small class="opacity-75">Dari 12 kegiatan</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stats-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Izin</h6>
                                <h2 class="mb-0">1</h2>
                                <small class="opacity-75">Kali tidak hadir</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stats-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Alpa</h6>
                                <h2 class="mb-0">1</h2>
                                <small class="opacity-75">Tanpa keterangan</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Persentase</h6>
                                <h2 class="mb-0">83%</h2>
                                <small class="opacity-75">Tingkat kehadiran</small>
                            </div>
                            <div class="stats-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart me-2"></i>Tren Kehadiran Bulanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="attendanceChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Target Kehadiran</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="position-relative mb-4">
                            <svg width="150" height="150" viewBox="0 0 150 150">
                                <circle cx="75" cy="75" r="60" stroke="#e9ecef" stroke-width="12"
                                    fill="none" />
                                <circle cx="75" cy="75" r="60" stroke="#28a745" stroke-width="12"
                                    fill="none" stroke-dasharray="377" stroke-dashoffset="64"
                                    transform="rotate(-90 75 75)" class="progress-circle" />
                            </svg>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0">83%</h3>
                                <small class="text-muted">Kehadiran</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Target: 80%</small>
                                <small class="text-success">✓ Tercapai</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: 83%"></div>
                                <div class="progress-bar bg-warning" style="width: 17%"></div>
                            </div>
                        </div>

                        <p class="text-muted small mb-3">
                            Pertahankan kehadiran di atas 80% untuk mendapat sertifikat keaktifan.
                        </p>

                        <div class="alert alert-success">
                            <i class="bi bi-trophy me-2"></i>
                            <strong>Target Tercapai!</strong><br>
                            <small>Anda memenuhi syarat sertifikat keaktifan.</small>
                        </div>
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
                                    @php
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
                                                'time' => '15:30',
                                                'status' => 'hadir',
                                                'note' => '-',
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
                                                'status' => 'alpa',
                                                'note' => 'Tidak ada keterangan',
                                                'recorder' => 'Budi Santoso',
                                            ],
                                        ];
                                    @endphp

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
                        <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                            <i class="bi bi-collection me-1"></i>Jelajahi Ekstrakurikuler
                        </a>
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
                        data: [85, 90, 78, 88, 92, 83],
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
    </style>
@endpush
