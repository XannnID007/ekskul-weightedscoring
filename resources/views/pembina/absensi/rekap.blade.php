@extends('layouts.app')

@section('title', 'Rekap Kehadiran')
@section('page-title', 'Rekap Kehadiran')
@section('page-description', 'Laporan dan analisis kehadiran siswa ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.absensi.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-filter me-1"></i>Filter
        </button>
        <button class="btn btn-light" onclick="exportReport()">
            <i class="bi bi-download me-1"></i>Export
        </button>
    </div>
@endsection

@section('content')
    <!-- Filter Panel -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Ekstrakurikuler</label>
                    <select class="form-select" name="ekstrakurikuler_id" id="filterEkskul">
                        <option value="">Semua Ekstrakurikuler</option>
                        @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                            <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <select class="form-select" name="periode" id="filterPeriode">
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="3_bulan">3 Bulan Terakhir</option>
                        <option value="6_bulan">6 Bulan Terakhir</option>
                        <option value="tahun_ini">Tahun Ini</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" name="start_date" id="startDate">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="end_date" id="endDate">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-primary w-100" onclick="applyFilter()">
                        <i class="bi bi-search me-1"></i>Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-primary">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Total Pertemuan</h6>
                            <h2 class="mb-0" id="totalPertemuan">0</h2>
                            <small class="opacity-75">Kegiatan</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-success">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Rata-rata Hadir</h6>
                            <h2 class="mb-0" id="avgKehadiran">0%</h2>
                            <small class="opacity-75">Persentase</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-warning">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Siswa Aktif</h6>
                            <h2 class="mb-0" id="siswaAktif">0</h2>
                            <small class="opacity-75">Terdaftar</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card bg-gradient-danger">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Tingkat Absensi</h6>
                            <h2 class="mb-0" id="tingkatAbsensi">0%</h2>
                            <small class="opacity-75">Tidak hadir</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Trend Kehadiran -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up text-primary me-2"></i>Trend Kehadiran
                        </h5>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="chartPeriod" id="weekly"
                                autocomplete="off" checked>
                            <label class="btn btn-outline-primary btn-sm" for="weekly">Mingguan</label>

                            <input type="radio" class="btn-check" name="chartPeriod" id="monthly"
                                autocomplete="off">
                            <label class="btn btn-outline-primary btn-sm" for="monthly">Bulanan</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribusi Status -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart text-primary me-2"></i>Distribusi Status
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="text-success" id="hadirCount">0</h6>
                                    <small class="text-muted">Hadir</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="text-danger" id="tidakHadirCount">0</h6>
                                <small class="text-muted">Tidak Hadir</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-table text-primary me-2"></i>Detail Kehadiran Siswa
                </h5>
                <div class="d-flex gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="showPercentage">
                        <label class="form-check-label" for="showPercentage">
                            Tampilkan Persentase
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="detailTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Ekstrakurikuler</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Alpa</th>
                            <th class="text-center">Total Pertemuan</th>
                            <th class="text-center">Persentase Hadir</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody">
                        <!-- Data akan dimuat via AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-5 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Memuat data kehadiran...</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-5 d-none">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3">Tidak ada data kehadiran</p>
                <p class="text-muted">Ubah filter atau periode untuk melihat data</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let trendChart, statusChart;

        $(document).ready(function() {
            initializeCharts();
            loadRekapData();

            // Event listeners
            $('#filterPeriode').change(function() {
                toggleCustomDateFields();
            });

            $('input[name="chartPeriod"]').change(function() {
                updateTrendChart();
            });

            $('#showPercentage').change(function() {
                togglePercentageView();
            });
        });

        function initializeCharts() {
            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Persentase Kehadiran',
                        data: [],
                        borderColor: '#20B2AA',
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
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Izin', 'Terlambat', 'Alpa'],
                    datasets: [{
                        data: [0, 0, 0, 0],
                        backgroundColor: [
                            '#28a745',
                            '#ffc107',
                            '#17a2b8',
                            '#dc3545'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    }
                }
            });
        }

        function loadRekapData() {
            showLoading(true);

            const formData = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams(formData);

            fetch(`{{ route('pembina.absensi.report') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    updateSummaryCards(data.summary);
                    updateCharts(data.charts);
                    updateDetailTable(data.details);
                    showLoading(false);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showEmptyState();
                    showLoading(false);
                });
        }

        function updateSummaryCards(summary) {
            $('#totalPertemuan').text(summary.total_pertemuan || 0);
            $('#avgKehadiran').text((summary.avg_kehadiran || 0) + '%');
            $('#siswaAktif').text(summary.siswa_aktif || 0);
            $('#tingkatAbsensi').text((summary.tingkat_absensi || 0) + '%');
        }

        function updateCharts(chartData) {
            // Update trend chart
            trendChart.data.labels = chartData.trend.labels;
            trendChart.data.datasets[0].data = chartData.trend.data;
            trendChart.update();

            // Update status chart
            statusChart.data.datasets[0].data = chartData.status.data;
            statusChart.update();

            // Update status counts
            $('#hadirCount').text(chartData.status.counts.hadir || 0);
            $('#tidakHadirCount').text(chartData.status.counts.tidak_hadir || 0);
        }

        function updateDetailTable(details) {
            const tbody = $('#detailTableBody');
            tbody.empty();

            if (!details || details.length === 0) {
                showEmptyState();
                return;
            }

            details.forEach((item, index) => {
                const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <div>
                                <strong>${item.nama}</strong>
                                <br><small class="text-muted">${item.nis || 'NIS belum diisi'}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary">${item.ekstrakurikuler}</span></td>
                    <td class="text-center">
                        <span class="badge bg-success">${item.hadir}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-warning">${item.izin}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info">${item.terlambat}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-danger">${item.alpa}</span>
                    </td>
                    <td class="text-center">
                        <strong>${item.total_pertemuan}</strong>
                    </td>
                    <td class="text-center">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-${getProgressColor(item.persentase_hadir)}" 
                                 style="width: ${item.persentase_hadir}%">
                                ${item.persentase_hadir}%
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-${getStatusBadge(item.persentase_hadir)}">
                            ${getStatusText(item.persentase_hadir)}
                        </span>
                    </td>
                </tr>
            `;
                tbody.append(row);
            });

            hideEmptyState();
        }

        function getProgressColor(percentage) {
            if (percentage >= 80) return 'success';
            if (percentage >= 60) return 'warning';
            return 'danger';
        }

        function getStatusBadge(percentage) {
            if (percentage >= 80) return 'success';
            if (percentage >= 60) return 'warning';
            return 'danger';
        }

        function getStatusText(percentage) {
            if (percentage >= 80) return 'Sangat Baik';
            if (percentage >= 60) return 'Baik';
            return 'Perlu Perhatian';
        }

        function applyFilter() {
            loadRekapData();
        }

        function toggleCustomDateFields() {
            const periode = $('#filterPeriode').val();
            const customFields = $('#startDate, #endDate');

            if (periode === 'custom') {
                customFields.prop('disabled', false).addClass('border-primary');
            } else {
                customFields.prop('disabled', true).removeClass('border-primary');
            }
        }

        function showLoading(show) {
            if (show) {
                $('#loadingState').removeClass('d-none');
                $('#detailTable, #emptyState').addClass('d-none');
            } else {
                $('#loadingState').addClass('d-none');
                $('#detailTable').removeClass('d-none');
            }
        }

        function showEmptyState() {
            $('#emptyState').removeClass('d-none');
            $('#detailTable').addClass('d-none');
        }

        function hideEmptyState() {
            $('#emptyState').addClass('d-none');
            $('#detailTable').removeClass('d-none');
        }

        function exportReport() {
            const formData = new FormData(document.getElementById('filterForm'));
            formData.append('export', 'true');

            const params = new URLSearchParams(formData);
            window.open(`{{ route('pembina.absensi.report') }}?${params.toString()}`, '_blank');
        }

        function togglePercentageView() {
            const showPercentage = $('#showPercentage').prop('checked');
            // Implementasi toggle view percentage
            console.log('Toggle percentage view:', showPercentage);
        }

        function updateTrendChart() {
            const period = $('input[name="chartPeriod"]:checked').attr('id');
            // Implementasi update chart berdasarkan periode
            console.log('Update trend chart:', period);
        }
    </script>
@endpush

@push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            border: none;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #20B2AA 0%, #17a2b8 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .avatar-sm {
            width: 35px;
            height: 35px;
        }

        .progress {
            border-radius: 10px;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
            border-radius: 6px;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #20B2AA;
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .btn-check:checked+.btn-outline-primary {
            background-color: #20B2AA;
            border-color: #20B2AA;
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
@endpush
