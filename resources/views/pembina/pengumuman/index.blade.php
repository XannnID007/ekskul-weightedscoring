@extends('layouts.app')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')
@section('page-description', 'Buat dan kelola pengumuman untuk siswa ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
        <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg me-1"></i>Buat Pengumuman
        </a>
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
                            <h6 class="card-title mb-1">Total Pengumuman</h6>
                            <h2 class="mb-0">{{ $pengumumans->total() }}</h2>
                            <small class="opacity-75">Semua pengumuman</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-megaphone"></i>
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
                            <h6 class="card-title mb-1">Pengumuman Penting</h6>
                            <h2 class="mb-0">{{ $pengumumans->where('is_penting', true)->count() }}</h2>
                            <small class="opacity-75">Prioritas tinggi</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-exclamation-triangle"></i>
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
                            <h6 class="card-title mb-1">Bulan Ini</h6>
                            <h2 class="mb-0">{{ $pengumumans->where('created_at', '>=', now()->startOfMonth())->count() }}</h2>
                            <small class="opacity-75">Pengumuman baru</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-calendar-month"></i>
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
                            <h6 class="card-title mb-1">Ekstrakurikuler</h6>
                            <h2 class="mb-0">{{ auth()->user()->ekstrakurikulerSebagaiPembina->count() }}</h2>
                            <small class="opacity-75">Yang dibina</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-collection"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Create Panel -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-lightning text-warning me-2"></i>Buat Pengumuman Cepat
            </h5>
        </div>
        <div class="card-body">
            <form id="quickAnnouncementForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" name="ekstrakurikuler_id" required>
                            <option value="">Pilih Ekstrakurikuler</option>
                            @foreach(auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="penting">Penting</option>
                            <option value="biasa">Biasa</option>
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
    $(document).ready(function() {
        // View mode toggle
        $('input[name="viewMode"]').change(function() {
            if ($(this).attr('id') === 'cardView') {
                $('#cardViewContainer').removeClass('d-none');
                $('#listViewContainer').addClass('d-none');
            } else {
                $('#cardViewContainer').addClass('d-none');
                $('#listViewContainer').removeClass('d-none');
            }
        });

        // Quick announcement form
        $('#quickAnnouncementForm').submit(function(e) {
            e.preventDefault();
            submitQuickAnnouncement();
        });

        // Auto-expand textarea in quick form
        $('input[name="konten"]').on('input', function() {
            if ($(this).val().length > 50) {
                $(this).attr('placeholder', 'Gunakan form lengkap untuk pengumuman panjang...');
            }
        });
    });

    function submitQuickAnnouncement() {
        const formData = new FormData(document.getElementById('quickAnnouncementForm'));
        
        // Show loading
        Swal.fire({
            title: 'Mengirim Pengumuman...',
            html: 'Sedang memproses pengumuman baru',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("pembina.pengumuman.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengumuman berhasil dibuat dan dikirim!',
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
                text: error.message || 'Terjadi kesalahan saat membuat pengumuman'
            });
        });
    }

    function deletePengumuman(id) {
        Swal.fire({
            title: 'Hapus Pengumuman?',
            text: 'Pengumuman yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit deletion
                fetch(`/pembina/pengumuman/${id}`, {
                    method: 'DELETE',
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
                            text: 'Pengumuman berhasil dihapus!',
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
                        text: error.message || 'Terjadi kesalahan saat menghapus pengumuman'
                    });
                });
            }
        });
    }

    function applyFilter() {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Quick action buttons
    function createUrgentAnnouncement() {
        Swal.fire({
            title: 'Pengumuman Mendesak',
            html: `
                <form id="urgentForm">
                    <div class="mb-3">
                        <select class="form-select" name="ekstrakurikuler_id" required>
                            <option value="">Pilih Ekstrakurikuler</option>
                            @foreach(auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="judul" placeholder="Judul pengumuman mendesak..." required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="konten" rows="3" placeholder="Isi pengumuman..." required></textarea>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kirim Sekarang',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const form = document.getElementById('urgentForm');
                const formData = new FormData(form);
                formData.append('is_penting', '1');
                
                return fetch('{{ route("pembina.pengumuman.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showSuccess('Pengumuman mendesak berhasil dikirim!');
                setTimeout(() => location.reload(), 1500);
            }
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + N for new announcement
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("pembina.pengumuman.create") }}';
        }
        
        // Ctrl + U for urgent announcement
        if (e.ctrlKey && e.key === 'u') {
            e.preventDefault();
            createUrgentAnnouncement();
        }
    });

    // Search functionality
    function searchAnnouncements() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.announcement-card');
        
        cards.forEach(card => {
            const title = card.querySelector('h6').textContent.toLowerCase();
            const content = card.querySelector('p').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || content.includes(searchTerm)) {
                card.closest('.col-xl-4').style.display = 'block';
            } else {
                card.closest('.col-xl-4').style.display = 'none';
            }
        });
    }

    // Auto-refresh for real-time updates (every 2 minutes)
    setInterval(function() {
        // Only refresh if user is actively viewing the page
        if (!document.hidden) {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update only the stats if there are changes
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                const newStats = newDoc.querySelectorAll('.stats-card h2');
                const currentStats = document.querySelectorAll('.stats-card h2');
                
                newStats.forEach((stat, index) => {
                    if (currentStats[index] && stat.textContent !== currentStats[index].textContent) {
                        currentStats[index].textContent = stat.textContent;
                        currentStats[index].parentElement.parentElement.classList.add('stats-updated');
                        setTimeout(() => {
                            currentStats[index].parentElement.parentElement.classList.remove('stats-updated');
                        }, 2000);
                    }
                });
            })
            .catch(error => console.log('Auto-refresh error:', error));
        }
    }, 120000); // 2 minutes
</script>
@endpush

@push('styles')
<style>
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stats-updated {
        animation: pulse 0.5s ease-in-out;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .announcement-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .announcement-card.border-warning {
        border-left: 4px solid #ffc107 !important;
    }

    .btn-group .btn {
        border-radius: 0;
        border-right: none;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        border-right: 1px solid;
    }

    .quick-announcement-panel {
        background: linear-gradient(135deg, rgba(32, 178, 170, 0.1) 0%, rgba(32, 178, 170, 0.05) 100%);
        border: 1px dashed rgba(32, 178, 170, 0.3);
        border-radius: 8px;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    /* Enhanced form styling */
    .form-control:focus,
    .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
    }

    /* Custom scrollbar for modal */
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f8f9fa;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: var(--bs-primary);
        border-radius: 3px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stats-card .stats-icon {
            font-size: 1.5rem;
        }
        
        .announcement-card {
            margin-bottom: 1rem;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            border-radius: 4px !important;
            border-right: 1px solid;
            margin-bottom: 2px;
        }
    }

    /* Loading states */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpushagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="judul" placeholder="Judul pengumuman..." required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="konten" placeholder="Isi pengumuman singkat..." required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-1"></i>Kirim
                        </button>
                    </div>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="is_penting" id="quickImportant">
                    <label class="form-check-label" for="quickImportant">
                        <i class="bi bi-exclamation-triangle text-warning me-1"></i>Tandai sebagai penting
                    </label>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul text-primary me-2"></i>Daftar Pengumuman
                </h5>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="viewMode" id="cardView" autocomplete="off" checked>
                        <label class="btn btn-outline-primary btn-sm" for="cardView">
                            <i class="bi bi-grid"></i>
                        </label>
                        
                        <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm" for="listView">
                            <i class="bi bi-list"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($pengumumans->count() > 0)
                <!-- Card View -->
                <div id="cardViewContainer" class="row g-4">
                    @foreach($pengumumans as $pengumuman)
                        <div class="col-xl-4 col-md-6">
                            <div class="card h-100 announcement-card {{ $pengumuman->is_penting ? 'border-warning' : '' }}">
                                @if($pengumuman->is_penting)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-warning">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Penting
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $pengumuman->judul }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-collection me-1"></i>{{ $pengumuman->ekstrakurikuler->nama }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body pt-2">
                                    <p class="text-muted mb-3">
                                        {{ Str::limit($pengumuman->konten, 120) }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $pengumuman->created_at->diffForHumans() }}
                                        </small>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pembina.pengumuman.show', $pengumuman) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('pembina.pengumuman.edit', $pengumuman) }}" 
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="deletePengumuman({{ $pengumuman->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View -->
                <div id="listViewContainer" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">Status</th>
                                    <th width="30%">Judul</th>
                                    <th width="20%">Ekstrakurikuler</th>
                                    <th width="25%">Konten</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengumumans as $pengumuman)
                                    <tr>
                                        <td>
                                            @if($pengumuman->is_penting)
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-info-circle"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $pengumuman->judul }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $pengumuman->ekstrakurikuler->nama }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($pengumuman->konten, 50) }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $pengumuman->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pembina.pengumuman.show', $pengumuman) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('pembina.pengumuman.edit', $pengumuman) }}" 
                                                   class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-outline-danger btn-sm" 
                                                        onclick="deletePengumuman({{ $pengumuman->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $pengumumans->firstItem() }}-{{ $pengumumans->lastItem() }} 
                        dari {{ $pengumumans->total() }} pengumuman
                    </div>
                    {{ $pengumumans->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Belum ada pengumuman</p>
                    <p class="text-muted mb-4">Buat pengumuman pertama untuk siswa ekstrakurikuler Anda</p>
                    <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Buat Pengumuman Pertama
                    </a>
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
                <h5 class="modal-title">Filter Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Ekstrakurikuler</label>
                        <select class="form-select" name="ekstrakurikuler">
                            <option value="">Semua Ekstrakurikuler</option>
                            @foreach(auth()->user()->ekstrakurikulerSeb