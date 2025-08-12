@extends('layouts.app')

@section('title', 'Laporan dan Analisis')
@section('page-title', 'Laporan dan Analisis')
@section('page-description', 'Dashboard analitik dan laporan sistem ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel me-1"></i>Filter Periode
        </button>
    </div>
@endsection

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
            color: var(--bs-primary);
        }

        .stats-card h2 {
            font-weight: 700;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

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

        .report-card {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, var(--bs-dark) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
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

        .report-card.rekomendasi .report-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
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

        .format-excel .export-format-icon {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
        }

        .format-pdf .export-format-icon {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .quick-export-section {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, var(--bs-dark) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
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
        <div class="col-xl-12">
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
                                <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-01') }}">
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

            // Submit form untuk export
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.laporan.export') }}';

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
            formatInput.value = 'excel';
            form.appendChild(formatInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

            setTimeout(() => {
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
            `;
            btn.style.pointerEvents = 'none';

            // Submit form untuk export
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.laporan.export') }}';

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

            // Restore button after delay
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.style.pointerEvents = 'auto';

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
                        // Submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('admin.laporan.export') }}';

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        const typeInput = document.createElement('input');
                        typeInput.type = 'hidden';
                        typeInput.name = 'type';
                        typeInput.value = 'all';
                        form.appendChild(typeInput);

                        document.body.appendChild(form);
                        form.submit();
                        document.body.removeChild(form);

                        setTimeout(() => resolve(), 1000);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
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

        function applyFilterAndExport() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            // Validate dates
            const startDate = formData.get('start_date');
            const endDate = formData.get('end_date');

            if (startDate > endDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Memproses Export...',
                text: 'Mohon tunggu sebentar.',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create and submit form
            const exportForm = document.createElement('form');
            exportForm.method = 'POST';
            exportForm.action = '{{ route('admin.laporan.export') }}';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            exportForm.appendChild(csrfInput);

            // Add form data
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key === 'report_type' ? 'type' : (key === 'export_format' ? 'format' : key);
                input.value = value;
                exportForm.appendChild(input);
            }

            document.body.appendChild(exportForm);
            exportForm.submit();
            document.body.removeChild(exportForm);

            // Close modal and show success
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Export Berhasil!',
                    text: 'File telah didownload sesuai filter yang dipilih.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }, 1500);
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
    </script>
@endpush
