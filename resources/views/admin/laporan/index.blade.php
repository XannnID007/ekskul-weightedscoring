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

@push('styles')
    <style>
        .report-card {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, var(--bs-dark) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--bs-primary);
        }

        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .report-card:hover::before {
            opacity: 1;
        }

        .report-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .report-card:hover .report-icon {
            transform: scale(1.1);
        }

        .report-card.siswa .report-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .report-card.ekstrakurikuler .report-icon {
            background: linear-gradient(135deg, #10b981, #047857);
            color: white;
        }

        .report-card.pendaftaran .report-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .report-card.kehadiran .report-icon {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .report-card.rekomendasi .report-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .report-title {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .report-description {
            color: #9ca3af;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .report-stats {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--bs-primary);
        }

        .stats-label {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .export-loading {
            display: none;
        }

        .btn-loading {
            pointer-events: none;
            opacity: 0.6;
        }

        .quick-export-section {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, var(--bs-dark) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
        }

        .export-format-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .export-format-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--bs-primary);
            transform: translateY(-2px);
        }

        .export-format-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin: 0 auto 1rem;
        }

        .format-csv .export-format-icon {
            background: linear-gradient(135deg, #10b981, #047857);
            color: white;
        }

        .format-excel .export-format-icon {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
        }

        .format-pdf .export-format-icon {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }
    </style>
@endpush

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <h2 class="mb-0">{{ $stats['total_siswa'] ?? 0 }}</h2>
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
                            <h2 class="mb-0">{{ $stats['total_ekstrakurikuler'] ?? 0 }}</h2>
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
                            <h2 class="mb-0">{{ $stats['total_pendaftaran'] ?? 0 }}</h2>
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
                            <h2 class="mb-0">{{ $stats['partisipasi_persen'] ?? 0 }}%</h2>
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

        <!-- Quick Export -->
        <div class="col-xl-4">
            <div class="quick-export-section">
                <div class="d-flex align-items-center mb-3">
                    <div class="export-format-icon bg-primary me-3">
                        <i class="bi bi-download"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Export Cepat</h5>
                        <small class="text-muted">Pilih format export</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="export-format-card format-excel" onclick="exportData('all', 'excel')">
                            <div class="export-format-icon">
                                <i class="bi bi-file-earmark-excel"></i>
                            </div>
                            <h6 class="mb-1">Excel</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="export-format-card format-pdf" onclick="exportData('all', 'pdf')">
                            <div class="export-format-icon">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </div>
                            <h6 class="mb-1">PDF</h6>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-2"></i>Export dengan Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Laporan Detail Cards -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <h5 class="mb-4">
                <i class="bi bi-file-earmark-text me-2"></i>Laporan Detail
            </h5>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="report-card siswa" onclick="generateReport('siswa')">
                <div class="card-body p-4">
                    <div class="report-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6 class="report-title">Laporan Data Siswa</h6>
                    <p class="report-description">
                        Laporan lengkap data siswa termasuk profil, minat, dan status ekstrakurikuler
                    </p>
                    <div class="report-stats">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stats-number">{{ $stats['total_siswa'] ?? 0 }}</div>
                                <div class="stats-label">Total Siswa</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number">{{ $stats['siswa_baru_hari_ini'] ?? 0 }}</div>
                                <div class="stats-label">Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="report-card ekstrakurikuler" onclick="generateReport('ekstrakurikuler')">
                <div class="card-body p-4">
                    <div class="report-icon">
                        <i class="bi bi-collection"></i>
                    </div>
                    <h6 class="report-title">Laporan Ekstrakurikuler</h6>
                    <p class="report-description">
                        Data lengkap ekstrakurikuler, kapasitas, pembina, dan tingkat partisipasi
                    </p>
                    <div class="report-stats">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stats-number">{{ $stats['total_ekstrakurikuler'] ?? 0 }}</div>
                                <div class="stats-label">Total Ekskul</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number">{{ $stats['total_pembina'] ?? 0 }}</div>
                                <div class="stats-label">Pembina</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="report-card pendaftaran" onclick="generateReport('pendaftaran')">
                <div class="card-body p-4">
                    <div class="report-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h6 class="report-title">Laporan Pendaftaran</h6>
                    <p class="report-description">
                        Analisis pendaftaran siswa dengan status approval dan rejection
                    </p>
                    <div class="report-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stats-number text-warning">{{ $stats['pendaftaran_pending'] ?? 0 }}</div>
                                <div class="stats-label">Pending</div>
                            </div>
                            <div class="col-4">
                                <div class="stats-number text-success">{{ $stats['pendaftaran_disetujui'] ?? 0 }}</div>
                                <div class="stats-label">Disetujui</div>
                            </div>
                            <div class="col-4">
                                <div class="stats-number text-danger">{{ $stats['pendaftaran_ditolak'] ?? 0 }}</div>
                                <div class="stats-label">Ditolak</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="report-card rekomendasi" onclick="generateReport('rekomendasi')">
                <div class="card-body p-4">
                    <div class="report-icon">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h6 class="report-title">Analisis Rekomendasi</h6>
                    <p class="report-description">
                        Laporan efektivitas sistem rekomendasi dan preferensi siswa
                    </p>
                    <div class="report-stats">
                        <div class="text-center">
                            <div class="stats-number">{{ $stats['profil_belum_lengkap'] ?? 0 }}</div>
                            <div class="stats-label">Profil Belum Lengkap</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="report-card" style="background: linear-gradient(135deg, #6366f1, #4f46e5);"
                onclick="generateCustomReport()">
                <div class="card-body p-4">
                    <div class="report-icon" style="background: rgba(255,255,255,0.2);">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h6 class="report-title">Laporan Custom</h6>
                    <p class="report-description">
                        Buat laporan sesuai kebutuhan dengan filter dan parameter khusus
                    </p>
                    <div class="report-stats">
                        <div class="text-center">
                            <button class="btn btn-sm btn-outline-light">
                                <i class="bi bi-plus-circle me-1"></i>Buat Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-funnel me-2"></i>Filter Periode Laporan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date"
                                    value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="end_date" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Laporan</label>
                                <select class="form-select" name="report_type">
                                    <option value="all">Semua Data</option>
                                    <option value="siswa">Data Siswa</option>
                                    <option value="ekstrakurikuler">Ekstrakurikuler</option>
                                    <option value="pendaftaran">Pendaftaran</option>
                                    <option value="rekomendasi">Rekomendasi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Format Export</label>
                                <select class="form-select" name="export_format">
                                    <option value="excel">Excel (.xlsx)</option>
                                    <option value="pdf">PDF (.pdf)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Catatan:</strong> Export dengan filter periode akan menghasilkan file yang lebih
                                kecil dan spesifik sesuai rentang tanggal yang dipilih.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilterAndExport()">
                        <i class="bi bi-download me-1"></i>Export dengan Filter
                    </button>
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
                labels: {!! json_encode($pendaftaran_bulanan['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
                datasets: [{
                    label: 'Pendaftaran',
                    data: {!! json_encode($pendaftaran_bulanan['data'] ?? [12, 19, 15, 25, 22, 18]) !!},
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

        // Functions
        function generateReport(type) {
            Swal.fire({
                title: 'Generate Laporan',
                text: `Membuat laporan ${type}...`,
                icon: 'info',
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate report generation
            setTimeout(() => {
                window.open(`{{ route('admin.laporan.export') }}?type=${type}`, '_blank');
                Swal.fire({
                    icon: 'success',
                    title: 'Laporan Berhasil!',
                    text: 'File sedang didownload...',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 1500);
        }

        function exportData(type, format) {
            const btn = event.target.closest('.export-format-card');
            const originalContent = btn.innerHTML;

            // Show loading state
            btn.innerHTML = `
                <div class="export-format-icon">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                </div>
                <h6 class="mb-1">Memproses...</h6>
                <small class="text-muted">Sedang membuat file</small>
            `;
            btn.classList.add('btn-loading');

            // Simulate export process
            setTimeout(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('admin.laporan.export') }}';
                form.style.display = 'none';

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Add type
                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'type';
                typeInput.value = type;
                form.appendChild(typeInput);

                // Add format
                const formatInput = document.createElement('input');
                formatInput.type = 'hidden';
                formatInput.name = 'format';
                formatInput.value = format;
                form.appendChild(formatInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);

                // Restore button
                btn.innerHTML = originalContent;
                btn.classList.remove('btn-loading');

                Swal.fire({
                    icon: 'success',
                    title: 'Export Berhasil!',
                    text: 'File telah didownload.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 2000);
        }

        function exportAllData() {
            Swal.fire({
                title: 'Export Semua Data?',
                text: 'Proses ini akan membutuhkan waktu beberapa menit.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Export!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve();
                        }, 3000);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('{{ route('admin.laporan.export') }}?type=all', '_blank');
                    Swal.fire({
                        icon: 'success',
                        title: 'Export Berhasil!',
                        text: 'File telah didownload.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
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
                        38
                    ];
                } else {
                    // Update chart for 6 months period  
                    chartPendaftaran.data.labels = {!! json_encode($pendaftaran_bulanan['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!};
                    chartPendaftaran.data.datasets[0].data = {!! json_encode($pendaftaran_bulanan['data'] ?? [12, 19, 15, 25, 22, 18]) !!};
                }
                chartPendaftaran.update();
            });
        });

        function generateCustomReport() {
            Swal.fire({
                title: 'Laporan Custom',
                text: 'Fitur laporan custom akan segera tersedia!',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    </script>
@endpush
