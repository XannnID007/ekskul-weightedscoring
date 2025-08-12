@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Overview sistem manajemen ekstrakurikuler')

{{-- CSS BARU UNTUK KARTU STATISTIK --}}
@push('styles')
    <style>
        .stats-card {
            border: 1px solid var(--bs-gray-200);
            border-left: 5px solid var(--bs-primary);
            transition: all 0.3s ease;
        }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            background-color: rgba(60, 154, 231, 0.1);
            /* Primary color with opacity */
            color: var(--bs-primary);
            transition: all 0.3s ease;
        }

        .stats-card h2 {
            color: var(--bs-gray-800);
            font-weight: 700;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Variasi Warna Kartu */
        .stats-card.success {
            border-left-color: var(--bs-success);
        }

        .stats-card.success .stats-icon {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--bs-success);
        }

        .stats-card.warning {
            border-left-color: var(--bs-warning);
        }

        .stats-card.warning .stats-icon {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--bs-warning);
        }

        .stats-card.danger {
            border-left-color: var(--bs-danger);
        }

        .stats-card.danger .stats-icon {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--bs-danger);
        }
    </style>
@endpush


@section('content')
    <div class="row g-4">
        <div class="col-xl-4 col-md-6">
            {{-- Kartu default menggunakan warna primary --}}
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 text-muted">Total Siswa</h6>
                            <h2 class="mb-0">{{ $stats['total_siswa'] }}</h2>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            {{-- Menambahkan class "success" --}}
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 text-muted">Total Pembina</h6>
                            <h2 class="mb-0">{{ $stats['total_pembina'] }}</h2>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            {{-- Menambahkan class "warning" --}}
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 text-muted">Ekstrakurikuler</h6>
                            <h2 class="mb-0">{{ $stats['total_ekstrakurikuler'] }}</h2>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-collection"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Ekstrakurikuler Paling Diminati</h5>
                </div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="chartEkstrakurikulerPopuler"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Statistik Pendaftaran</h5>
                </div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="chartPendaftaran"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === MENGAMBIL WARNA DARI VARIABEL CSS DI :root ===
            const rootStyles = getComputedStyle(document.documentElement);
            const colorPrimary = rootStyles.getPropertyValue('--bs-primary').trim();
            const colorSuccess = rootStyles.getPropertyValue('--bs-success').trim();
            const colorWarning = rootStyles.getPropertyValue('--bs-warning').trim();
            const colorDanger = rootStyles.getPropertyValue('--bs-danger').trim();
            const colorGrid = rootStyles.getPropertyValue('--bs-gray-200').trim();
            const colorTicks = rootStyles.getPropertyValue('--bs-gray-500').trim();
            const colorLabels = rootStyles.getPropertyValue('--bs-gray-700').trim();

            // Helper function untuk mengubah Hex ke RGBA
            function hexToRgba(hex, alpha = 1) {
                let r = 0,
                    g = 0,
                    b = 0;
                if (hex.length == 4) {
                    r = parseInt(hex[1] + hex[1], 16);
                    g = parseInt(hex[2] + hex[2], 16);
                    b = parseInt(hex[3] + hex[3], 16);
                } else if (hex.length == 7) {
                    r = parseInt(hex[1] + hex[2], 16);
                    g = parseInt(hex[3] + hex[4], 16);
                    b = parseInt(hex[5] + hex[6], 16);
                }
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            }

            // Chart Ekstrakurikuler Populer (Bar Chart)
            const ctxPopuler = document.getElementById('chartEkstrakurikulerPopuler').getContext('2d');
            const chartPopuler = new Chart(ctxPopuler, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($ekstrakurikuler_populer->pluck('nama')) !!},
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        data: {!! json_encode($ekstrakurikuler_populer->pluck('total_pendaftar')) !!},
                        backgroundColor: hexToRgba(colorPrimary, 0.8), // Menggunakan warna Primary
                        borderColor: colorPrimary,
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Legenda tidak perlu untuk 1 data
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: colorGrid
                            },
                            ticks: {
                                color: colorTicks,
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: colorTicks,
                                padding: 10
                            }
                        }
                    }
                }
            });

            // Chart Pendaftaran Status (Doughnut Chart)
            const ctxPendaftaran = document.getElementById('chartPendaftaran').getContext('2d');
            const chartPendaftaran = new Chart(ctxPendaftaran, {
                type: 'doughnut',
                data: {
                    labels: ['Disetujui', 'Pending', 'Ditolak'],
                    datasets: [{
                        data: [
                            {{ $stats['pendaftaran_disetujui'] }},
                            {{ $stats['pendaftaran_pending'] }},
                            {{ $stats['total_pendaftaran'] - $stats['pendaftaran_disetujui'] - $stats['pendaftaran_pending'] }}
                        ],
                        backgroundColor: [
                            hexToRgba(colorSuccess, 0.85), // Warna Success
                            hexToRgba(colorWarning, 0.85), // Warna Warning
                            hexToRgba(colorDanger, 0.85) // Warna Danger
                        ],
                        borderColor: [
                            colorSuccess,
                            colorWarning,
                            colorDanger
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%', // Membuat lubang di tengah lebih besar
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: colorLabels, // Warna label
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'rectRounded'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
