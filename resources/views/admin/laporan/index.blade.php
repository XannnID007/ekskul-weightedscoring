@extends('layouts.app')

@section('title', 'Laporan dan Analisis')
@section('page-title', 'Laporan dan Analisis')
@section('page-description', 'Dashboard analitik dan laporan sistem ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <button class="btn btn-success" onclick="exportAllData()">
            <i class="bi bi-download me-1"></i>Export Semua
        </button>
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel me-1"></i>Filter Periode
        </button>
    </div>
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <h2 class="mb-0">{{ \App\Models\User::siswa()->count() }}</h2>
                            <small class="opacity-75">Terdaftar aktif</small>
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
                            <h6 class="card-title mb-1">Ekstrakurikuler</h6>
                            <h2 class="mb-0">{{ \App\Models\Ekstrakurikuler::count() }}</h2>
                            <small class="opacity-75">Total tersedia</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-collection"></i>
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
                            <h6 class="card-title mb-1">Pendaftaran</h6>
                            <h2 class="mb-0">{{ \App\Models\Pendaftaran::count() }}</h2>
                            <small class="opacity-75">Total pendaftaran</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clipboard-check"></i>
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
                            <h6 class="card-title mb-1">Partisipasi</h6>
                            <h2 class="mb-0">
                                {{ round((\App\Models\Pendaftaran::disetujui()->count() / max(\App\Models\User::siswa()->count(), 1)) * 100) }}%
                            </h2>
                            <small class="opacity-75">Siswa aktif</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart Pendaftaran per Bulan -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tren Pendaftaran</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="periodChart" id="chart6months" checked>
                        <label class="btn btn-outline-primary" for="chart6months">6 Bulan</label>
                        <input type="radio" class="btn-check" name="periodChart" id="chart1year">
                        <label class="btn btn-outline-primary" for="chart1year">1 Tahun</label>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="chartPendaftaran" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Ekstrakurikuler -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ekstrakurikuler Terpopuler</h5>
                </div>
                <div class="card-body">
                    @php
                        $topEkstrakurikuler = \App\Models\Ekstrakurikuler::withCount([
                            'pendaftarans as total_pendaftar',
                        ])
                            ->orderBy('total_pendaftar', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @forelse($topEkstrakurikuler as $ekskul)
                        <div
                            class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <small class="text-white fw-bold">{{ $loop->iteration }}</small>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $ekskul->nama }}</h6>
                                    <small class="text-muted">{{ $ekskul->pembina->name ?? '-' }}</small>
                                </div>
                            </div>
                            <span class="badge bg-primary">{{ $ekskul->total_pendaftar }}</span>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-collection text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Status Pendaftaran -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartStatus" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Laporan Cepat -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Laporan Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="generateReport('siswa')">
                            <i class="bi bi-people me-2"></i>Laporan Data Siswa
                        </button>
                        <button class="btn btn-outline-success" onclick="generateReport('ekstrakurikuler')">
                            <i class="bi bi-collection me-2"></i>Laporan Ekstrakurikuler
                        </button>
                        <button class="btn btn-outline-warning" onclick="generateReport('pendaftaran')">
                            <i class="bi bi-clipboard-check me-2"></i>Laporan Pendaftaran
                        </button>
                        <button class="btn btn-outline-info" onclick="generateReport('kehadiran')">
                            <i class="bi bi-calendar-check me-2"></i>Laporan Kehadiran
                        </button>
                        <button class="btn btn-outline-danger" onclick="generateReport('rekomendasi')">
                            <i class="bi bi-stars me-2"></i>Analisis Rekomendasi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Detail -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Detail</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Distribusi Gender -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">Distribusi Gender Siswa</h6>
                            @php
                                $totalSiswa = \App\Models\User::siswa()->count();
                                $lakiLaki = \App\Models\User::siswa()->where('jenis_kelamin', 'L')->count();
                                $perempuan = \App\Models\User::siswa()->where('jenis_kelamin', 'P')->count();
                                $belumIsi = $totalSiswa - $lakiLaki - $perempuan;
                            @endphp

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Laki-laki</span>
                                    <span>{{ $lakiLaki }}
                                        ({{ $totalSiswa > 0 ? round(($lakiLaki / $totalSiswa) * 100) : 0 }}%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info"
                                        style="width: {{ $totalSiswa > 0 ? ($lakiLaki / $totalSiswa) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Perempuan</span>
                                    <span>{{ $perempuan }}
                                        ({{ $totalSiswa > 0 ? round(($perempuan / $totalSiswa) * 100) : 0 }}%)</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-pink"
                                        style="width: {{ $totalSiswa > 0 ? ($perempuan / $totalSiswa) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            @if ($belumIsi > 0)
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <span>Belum diisi</span>
                                        <span>{{ $belumIsi }}
                                            ({{ $totalSiswa > 0 ? round(($belumIsi / $totalSiswa) * 100) : 0 }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-secondary"
                                            style="width: {{ $totalSiswa > 0 ? ($belumIsi / $totalSiswa) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Distribusi Nilai -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">Distribusi Nilai Siswa</h6>
                            @php
                                $siswaWithNilai = \App\Models\User::siswa()->whereNotNull('nilai_rata_rata')->count();
                                $nilaiTinggi = \App\Models\User::siswa()->where('nilai_rata_rata', '>=', 80)->count();
                                $nilaiBaik = \App\Models\User::siswa()
                                    ->whereBetween('nilai_rata_rata', [70, 79.9])
                                    ->count();
                                $nilaiCukup = \App\Models\User::siswa()->where('nilai_rata_rata', '<', 70)->count();
                            @endphp

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>≥ 80 (Baik Sekali)</span>
                                    <span>{{ $nilaiTinggi }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success"
                                        style="width: {{ $siswaWithNilai > 0 ? ($nilaiTinggi / $siswaWithNilai) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>70-79 (Baik)</span>
                                    <span>{{ $nilaiBaik }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning"
                                        style="width: {{ $siswaWithNilai > 0 ? ($nilaiBaik / $siswaWithNilai) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between">
                                    <span>
                                        < 70 (Cukup)</span>
                                            <span>{{ $nilaiCukup }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger"
                                        style="width: {{ $siswaWithNilai > 0 ? ($nilaiCukup / $siswaWithNilai) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori Ekstrakurikuler -->
                        <div class="col-md-4">
                            <h6 class="text-primary mb-3">Kategori Ekstrakurikuler</h6>
                            @php
                                $kategoriStats = [];
                                $ekstrakurikulers = \App\Models\Ekstrakurikuler::all();
                                foreach ($ekstrakurikulers as $ekskul) {
                                    if ($ekskul->kategori && is_array($ekskul->kategori)) {
                                        foreach ($ekskul->kategori as $kat) {
                                            $kategoriStats[$kat] = ($kategoriStats[$kat] ?? 0) + 1;
                                        }
                                    }
                                }
                                arsort($kategoriStats);
                                $totalKategori = array_sum($kategoriStats);
                            @endphp

                            @foreach (array_slice($kategoriStats, 0, 5, true) as $kategori => $count)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>{{ ucfirst($kategori) }}</span>
                                        <span>{{ $count }}</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ $totalKategori > 0 ? ($count / $totalKategori) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Periode Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="end_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Laporan</label>
                            <select class="form-select" name="report_type">
                                <option value="all">Semua Data</option>
                                <option value="pendaftaran">Pendaftaran</option>
                                <option value="kehadiran">Kehadiran</option>
                                <option value="ekstrakurikuler">Ekstrakurikuler</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilter()">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Chart Pendaftaran
        const ctxPendaftaran = document.getElementById('chartPendaftaran').getContext('2d');
        const chartPendaftaran = new Chart(ctxPendaftaran, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Pendaftaran',
                    data: [12, 19, 15, 25, 22, 18],
                    borderColor: 'rgba(32, 178, 170, 1)',
                    backgroundColor: 'rgba(32, 178, 170, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
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

        // Chart Status Pendaftaran
        const ctxStatus = document.getElementById('chartStatus').getContext('2d');
        const chartStatus = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ \App\Models\Pendaftaran::disetujui()->count() }},
                        {{ \App\Models\Pendaftaran::pending()->count() }},
                        {{ \App\Models\Pendaftaran::ditolak()->count() }}
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

        // Functions
        function generateReport(type) {
            Swal.fire({
                title: 'Generate Laporan',
                text: `Membuat laporan ${type}...`,
                icon: 'info',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                // Simulate report generation
                window.open(`/admin/laporan/export/${type}`, '_blank');
            });
        }

        function exportAllData() {
            Swal.fire({
                title: 'Export Semua Data?',
                text: 'Proses ini akan membutuhkan waktu beberapa menit.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Export!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang mengexport data...',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    // Simulate export process
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Berhasil!',
                            text: 'File telah didownload.'
                        });
                        window.open('/admin/laporan/export/all', '_blank');
                    }, 3000);
                }
            });
        }

        function applyFilter() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            // Apply filters to charts and data
            Swal.fire({
                icon: 'success',
                title: 'Filter Diterapkan',
                text: 'Data telah difilter sesuai periode yang dipilih.',
                timer: 2000,
                showConfirmButton: false
            });

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
            modal.hide();

            // Refresh charts with filtered data (implement as needed)
            // updateChartsWithFilter(formData);
        }

        // Period toggle for chart
        document.querySelectorAll('input[name="periodChart"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.id === 'chart1year') {
                    // Update chart for 1 year period
                    chartPendaftaran.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug',
                        'Sep', 'Okt', 'Nov', 'Des'
                    ];
                    chartPendaftaran.data.datasets[0].data = [12, 19, 15, 25, 22, 18, 30, 28, 35, 32, 40,
                        38];
                } else {
                    // Update chart for 6 months period
                    chartPendaftaran.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
                    chartPendaftaran.data.datasets[0].data = [12, 19, 15, 25, 22, 18];
                }
                chartPendaftaran.update();
            });
        });
    </script>
@endpush
