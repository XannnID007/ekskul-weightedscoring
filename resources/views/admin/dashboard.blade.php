@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-description', 'Overview sistem manajemen ekstrakurikuler')

@section('content')
    <div class="row g-4">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <h2 class="mb-0">{{ $stats['total_siswa'] }}</h2>
                            <small class="opacity-75">Siswa terdaftar</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Pembina</h6>
                            <h2 class="mb-0">{{ $stats['total_pembina'] }}</h2>
                            <small class="opacity-75">Pembina aktif</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-person-badge"></i>
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
                            <h6 class="card-title mb-1">Ekstrakurikuler</h6>
                            <h2 class="mb-0">{{ $stats['total_ekstrakurikuler'] }}</h2>
                            <small class="opacity-75">Ekskul tersedia</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-collection"></i>
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
                            <h6 class="card-title mb-1">Pendaftaran Pending</h6>
                            <h2 class="mb-0">{{ $stats['pendaftaran_pending'] }}</h2>
                            <small class="opacity-75">Menunggu persetujuan</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ekstrakurikuler Paling Diminati</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEkstrakurikulerPopuler" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartPendaftaran" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    <a href="{{ route('admin.laporan') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-file-earmark-text me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Siswa</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <small class="text-muted">2 menit lalu</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Muhammad Iqbal</div>
                                                <small class="text-muted">2024001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Futsal</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small class="text-muted">15 menit lalu</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Fatimah Azzahra</div>
                                                <small class="text-muted">2024002</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Paduan Suara</td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Disetujui
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small class="text-muted">1 jam lalu</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Rizky Pratama</div>
                                                <small class="text-muted">2024003</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Basket</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.ekstrakurikuler.create') }}" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i>
                                Tambah Ekstrakurikuler
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.user.create', ['role' => 'pembina']) }}"
                                class="btn btn-success w-100">
                                <i class="bi bi-person-plus me-2"></i>
                                Tambah Pembina
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#importSiswaModal">
                                <i class="bi bi-upload me-2"></i>
                                Import Siswa
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laporan') }}" class="btn btn-warning w-100">
                                <i class="bi bi-download me-2"></i>
                                Export Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Siswa Modal -->
    <div class="modal fade" id="importSiswaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.user.import-siswa') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">File Excel (.xlsx/.xls)</label>
                            <input type="file" class="form-control" id="file" name="file"
                                accept=".xlsx,.xls" required>
                            <div class="form-text">
                                Format: Nama, Email, NIS, Jenis Kelamin, Tanggal Lahir, Alamat, Telepon
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Template:</strong>
                            <a href="{{ asset('templates/template-siswa.xlsx') }}" class="alert-link">Download template
                                Excel</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i>Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Chart Ekstrakurikuler Populer
        const ctxPopuler = document.getElementById('chartEkstrakurikulerPopuler').getContext('2d');
        const chartPopuler = new Chart(ctxPopuler, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ekstrakurikuler_populer->pluck('nama')) !!},
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: {!! json_encode($ekstrakurikuler_populer->pluck('total_pendaftar')) !!},
                    backgroundColor: 'rgba(108, 66, 193, 0.8)',
                    borderColor: 'rgba(108, 66, 193, 1)',
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
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#e9ecef'
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

        // Chart Pendaftaran Status
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
                        'rgba(32, 201, 151, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(32, 201, 151, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#e9ecef',
                            padding: 20
                        }
                    }
                }
            }
        });
    </script>
@endpush
