@extends('layouts.app')

@section('title', 'Kelola Pendaftaran')
@section('page-title', 'Kelola Pendaftaran')
@section('page-description', 'Kelola pendaftaran siswa pada ekstrakurikuler yang Anda bina')

@section('content')
    <div class="row g-4 mb-4">
        {{-- Kartu-kartu ini sekarang akan otomatis menggunakan style yang baru ditambahkan --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Pendaftaran</h6>
                        <h2 class="mb-0">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="stats-icon"><i class="bi bi-clipboard-data"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card warning">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Menunggu Review</h6>
                        <h2 class="mb-0">{{ $stats['pending'] }}</h2>
                    </div>
                    <div class="stats-icon"><i class="bi bi-clock-history"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Disetujui</h6>
                        <h2 class="mb-0">{{ $stats['disetujui'] }}</h2>
                    </div>
                    <div class="stats-icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card danger">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Ditolak</h6>
                        <h2 class="mb-0">{{ $stats['ditolak'] }}</h2>
                    </div>
                    <div class="stats-icon"><i class="bi bi-x-circle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people text-primary me-2"></i>Daftar Pendaftaran</h5>
            <div>
                @if ($stats['pending'] > 0)
                    <button class="btn btn-success btn-sm" onclick="bulkApprove()">
                        <i class="bi bi-check-all me-1"></i>Setujui Semua Pending
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if ($pendaftarans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 5%;"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                                <th>Siswa</th>
                                <th>Ekstrakurikuler</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendaftarans as $pendaftaran)
                                <tr>
                                    <td><input type="checkbox" class="form-check-input pendaftaran-checkbox"
                                            value="{{ $pendaftaran->id }}"></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-3">
                                                <span>{{ strtoupper(substr($pendaftaran->user->name, 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <strong>{{ $pendaftaran->user->name }}</strong><br>
                                                <small
                                                    class="text-muted">{{ $pendaftaran->user->nis ?: 'NIS belum ada' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $pendaftaran->ekstrakurikuler->nama }}</strong>
                                    </td>
                                    <td>
                                        <small
                                            class="text-muted">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</small>
                                    </td>
                                    <td>
                                        @if ($pendaftaran->status == 'pending')
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill">Pending</span>
                                        @elseif($pendaftaran->status == 'disetujui')
                                            <span
                                                class="badge bg-success-subtle text-success-emphasis rounded-pill">Disetujui</span>
                                        @else
                                            <span
                                                class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pembina.pendaftaran.show', $pendaftaran) }}"
                                            class="btn btn-light btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if ($pendaftaran->status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="quickApprove({{ $pendaftaran->id }})">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="quickReject({{ $pendaftaran->id }})">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Menampilkan {{ $pendaftarans->firstItem() }}-{{ $pendaftarans->lastItem() }} dari
                        {{ $pendaftarans->total() }} data
                    </div>
                    {{ $pendaftarans->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-3">Belum ada pendaftaran masuk</p>
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

        /* Gaya untuk Avatar di Tabel */
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary-emphasis);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
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
