@extends('layouts.app')

@section('title', 'Kelola Pendaftaran')
@section('page-title', 'Kelola Pendaftaran')
@section('page-description', 'Kelola pendaftaran siswa pada ekstrakurikuler yang Anda bina')

@section('page-actions')
    <div class="d-flex gap-2">
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-download me-1"></i>Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">
                        <i class="bi bi-file-pdf me-2"></i>PDF
                    </a></li>
                <li><a class="dropdown-item" href="#" onclick="exportData('excel')">
                        <i class="bi bi-file-excel me-2"></i>Excel
                    </a></li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Pendaftaran</h6>
                            <h2 class="mb-0">{{ $stats['total'] }}</h2>
                            <small class="opacity-75">Semua status</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clipboard-data"></i>
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
                            <h6 class="card-title mb-1">Menunggu Review</h6>
                            <h2 class="mb-0">{{ $stats['pending'] }}</h2>
                            <small class="opacity-75">Perlu diproses</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock-history"></i>
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
                            <h6 class="card-title mb-1">Disetujui</h6>
                            <h2 class="mb-0">{{ $stats['disetujui'] }}</h2>
                            <small class="opacity-75">Siswa aktif</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
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
                            <h6 class="card-title mb-1">Ditolak</h6>
                            <h2 class="mb-0">{{ $stats['ditolak'] }}</h2>
                            <small class="opacity-75">Tidak diterima</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people text-primary me-2"></i>Daftar Pendaftaran
                </h5>
                <div class="d-flex gap-2">
                    <!-- Quick Actions -->
                    @if ($stats['pending'] > 0)
                        <button class="btn btn-outline-success btn-sm" onclick="bulkApprove()">
                            <i class="bi bi-check-all me-1"></i>Setujui Semua
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($pendaftarans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th>Siswa</th>
                                <th>Ekstrakurikuler</th>
                                <th>Tanggal Daftar</th>
                                <th>Komitmen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendaftarans as $pendaftaran)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input pendaftaran-checkbox"
                                            value="{{ $pendaftaran->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $pendaftaran->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $pendaftaran->user->nis ?: 'NIS belum diisi' }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>{{ $pendaftaran->user->email }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $pendaftaran->ekstrakurikuler->nama }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $pendaftaran->ekstrakurikuler->jadwal_string }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $pendaftaran->created_at->format('d M Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $pendaftaran->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $commitmentColors = [
                                                'tinggi' => 'success',
                                                'sedang' => 'warning',
                                                'rendah' => 'danger',
                                            ];
                                            $color = $commitmentColors[$pendaftaran->tingkat_komitmen] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($pendaftaran->tingkat_komitmen) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($pendaftaran->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Pending
                                            </span>
                                        @elseif($pendaftaran->status === 'disetujui')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pembina.pendaftaran.show', $pendaftaran) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>

                                            @if ($pendaftaran->status === 'pending')
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="quickApprove({{ $pendaftaran->id }})">
                                                        <i class="bi bi-check me-1"></i>Setujui
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="quickReject({{ $pendaftaran->id }})">
                                                        <i class="bi bi-x me-1"></i>Tolak
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $pendaftarans->firstItem() }}-{{ $pendaftarans->lastItem() }}
                        dari {{ $pendaftarans->total() }} pendaftaran
                    </div>
                    {{ $pendaftarans->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Belum ada pendaftaran masuk</p>
                    <small class="text-muted">
                        Pendaftaran akan muncul di sini ketika siswa mendaftar ke ekstrakurikuler yang Anda bina
                    </small>
                </div>
            @endif
        </div>
    </div>
@endsection

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ekstrakurikuler</label>
                        <select class="form-select" name="ekstrakurikuler">
                            <option value="">Semua Ekstrakurikuler</option>
                            @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
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

@push('scripts')
    <script>
        // Quick Approve Function
        function quickApprove(pendaftaranId) {
            Swal.fire({
                title: 'Setujui Pendaftaran?',
                text: 'Siswa akan diterima dalam ekstrakurikuler ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    fetch(`/pembina/pendaftaran/${pendaftaranId}/approve`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pendaftaran berhasil disetujui!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat memproses pendaftaran'
                            });
                        });
                }
            });
        }

        // Quick Reject Function
        function quickReject(pendaftaranId) {
            Swal.fire({
                title: 'Tolak Pendaftaran?',
                input: 'textarea',
                inputLabel: 'Alasan penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan yang jelas dan konstruktif...',
                inputAttributes: {
                    'aria-label': 'Masukkan alasan penolakan'
                },
                inputValidator: (value) => {
                    if (!value || value.length < 10) {
                        return 'Alasan penolakan minimal 10 karakter!';
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit rejection
                    fetch(`/pembina/pendaftaran/${pendaftaranId}/reject`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                alasan_penolakan: result.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pendaftaran berhasil ditolak!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat memproses penolakan'
                            });
                        });
                }
            });
        }

        // Bulk Approve
        function bulkApprove() {
            const selectedIds = $('.pendaftaran-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Pendaftaran',
                    text: 'Silakan pilih pendaftaran yang ingin disetujui'
                });
                return;
            }

            Swal.fire({
                title: `Setujui ${selectedIds.length} Pendaftaran?`,
                text: 'Semua pendaftaran yang dipilih akan disetujui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implementation for bulk approve
                    console.log('Bulk approve:', selectedIds);
                    showSuccess('Fitur bulk approve akan segera tersedia');
                }
            });
        }

        // Select All Checkbox
        $('#selectAll').change(function() {
            $('.pendaftaran-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Apply Filter
        function applyFilter() {
            const formData = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams(formData);
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        // Export Data
        function exportData(format) {
            const params = new URLSearchParams(window.location.search);
            params.set('export', format);
            window.open(`${window.location.pathname}?${params.toString()}`, '_blank');
        }

        // Auto refresh every 30 seconds for pending status
        @if ($stats['pending'] > 0)
            setInterval(function() {
                // Only refresh if there are pending items
                if ({{ $stats['pending'] }} > 0) {
                    location.reload();
                }
            }, 30000);
        @endif
    </script>
@endpush

@push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
    </style>
@endpush
