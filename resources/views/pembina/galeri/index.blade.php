@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('page-description', 'Kelola galeri kegiatan ekstrakurikuler Anda.')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Galeri Kegiatan</h5>
                <a href="{{ route('pembina.galeri.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Form untuk Filter dan Aksi --}}
            <form action="{{ route('pembina.galeri.index') }}" method="GET" id="filterForm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                    {{-- Opsi Pilih Semua & Hapus --}}
                    <div class="form-check mb-2 mb-md-0">
                        <input class="form-check-input" type="checkbox" id="pilihSemua">
                        <label class="form-check-label" for="pilihSemua">
                            Pilih Semua
                        </label>
                        <button type="button" class="btn btn-danger btn-sm ms-2" id="hapusTerpilihBtn"
                            style="display: none;">
                            <i class="bi bi-trash"></i> Hapus Terpilih
                        </button>
                    </div>

                    {{-- Filter Posisi/Urutan --}}
                    <div class="d-flex align-items-center">
                        <label for="sort" class="form-label me-2 mb-0">Urutkan:</label>
                        <select name="sort" id="sort" class="form-select"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru
                            </option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                </div>
            </form>

            {{-- Kontainer Galeri --}}
            <div class="row">
                @forelse ($galeri as $item)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            {{-- Perbaikan: Menggunakan path_file bukan media --}}
                            @if ($item->tipe === 'gambar')
                                <img src="{{ Storage::url($item->path_file) }}" class="card-img-top"
                                    alt="{{ $item->judul }}" style="height: 200px; object-fit: cover;"
                                    onerror="this.onerror=null; this.src='/img/placeholder.png'; this.alt='Gambar tidak ditemukan';">
                            @else
                                {{-- Untuk video, tampilkan thumbnail atau icon --}}
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-dark text-white"
                                    style="height: 200px;">
                                    <div class="text-center">
                                        <i class="bi bi-play-circle" style="font-size: 3rem;"></i>
                                        <div class="mt-2">Video</div>
                                    </div>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ Str::limit($item->judul, 50) }}</h5>
                                <p class="card-text text-muted small">
                                    <span class="badge bg-primary me-1">{{ $item->ekstrakurikuler->nama }}</span>
                                    <span class="badge bg-{{ $item->tipe === 'video' ? 'warning' : 'success' }} me-1">
                                        <i class="bi bi-{{ $item->tipe === 'video' ? 'play-circle' : 'image' }}"></i>
                                        {{ ucfirst($item->tipe) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                </p>

                                {{-- Deskripsi jika ada --}}
                                @if ($item->deskripsi)
                                    <p class="card-text">{{ Str::limit($item->deskripsi, 100) }}</p>
                                @endif

                                {{-- Spacer untuk mendorong tombol ke bawah --}}
                                <div class="mt-auto"></div>

                                {{-- Tombol Aksi yang Rapi --}}
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <input type="checkbox" class="form-check-input" name="selected_galeri[]"
                                        value="{{ $item->id }}">
                                    <div class="btn-group">
                                        {{-- Tombol Lihat --}}
                                        <a href="{{ route('pembina.galeri.show', $item) }}"
                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('pembina.galeri.edit', $item) }}"
                                            class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Tombol Download --}}
                                        <a href="{{ route('pembina.galeri.download', $item) }}"
                                            class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip"
                                            title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('pembina.galeri.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-images" style="font-size: 3rem;"></i>
                            <p class="mt-2">Anda belum mengunggah foto atau video kegiatan apapun.</p>
                            <a href="{{ route('pembina.galeri.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-lg me-1"></i>Upload File Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi --}}
            <div class="mt-4">
                {{ $galeri->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    {{-- Form untuk Hapus Massal --}}
    <form id="bulkDeleteForm" action="{{ route('pembina.galeri.bulkDestroy') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <div id="bulkDeleteIds"></div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            const pilihSemua = document.getElementById('pilihSemua');
            const checkboxes = document.querySelectorAll('input[name="selected_galeri[]"]');
            const hapusTerpilihBtn = document.getElementById('hapusTerpilihBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const bulkDeleteIds = document.getElementById('bulkDeleteIds');

            // Fungsi untuk menampilkan/menyembunyikan tombol hapus
            function toggleBulkDeleteButton() {
                const anyChecked = Array.from(checkboxes).some(c => c.checked);
                hapusTerpilihBtn.style.display = anyChecked ? 'inline-block' : 'none';
            }

            // Event listener untuk 'Pilih Semua'
            pilihSemua.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkDeleteButton();
            });

            // Event listener untuk setiap checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkDeleteButton);
            });

            // Event listener untuk tombol 'Hapus Terpilih'
            hapusTerpilihBtn.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus semua file yang dipilih?')) {
                    bulkDeleteIds.innerHTML = ''; // Kosongkan dulu
                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = checkbox.value;
                            bulkDeleteIds.appendChild(input);
                        }
                    });
                    bulkDeleteForm.submit();
                }
            });

            // Handle image load errors
            document.querySelectorAll('img').forEach(img => {
                img.addEventListener('error', function() {
                    this.src =
                        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik02MCA2MUw2MCA2MEw2MSA2MEw2MSA2MVoiIGZpbGw9IiM2Qjc0ODAiLz4KPHA+CjxyZWN0IHg9IjcwIiB5PSI5MCIgd2lkdGg9IjYwIiBoZWlnaHQ9IjIwIiBmaWxsPSIjNkI3NDgwIi8+CjxwYXRoIGQ9Ik04NSA2NUw4NSAxMDBMMTE1IDEwMEwxMTUgNjVMODUgNjVaIiBzdHJva2U9IiM2Qjc0ODAiIHN0cm9rZS13aWR0aD0iMiIgZmlsbD0ibm9uZSIvPgo8L3N2Zz4=';
                    this.alt = 'Gambar tidak ditemukan';
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            transition: all 0.3s ease;
        }

        .card-img-top:hover {
            transform: scale(1.02);
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

        .badge {
            font-size: 0.75rem;
        }

        /* Loading state */
        .card.loading {
            opacity: 0.6;
        }

        /* Error state styling */
        .error-image {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        /* Video thumbnail styling */
        .video-thumbnail {
            background: linear-gradient(45deg, #1a1a1a, #333);
            position: relative;
            overflow: hidden;
        }

        .video-thumbnail::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                border-radius: 4px;
                margin-bottom: 2px;
            }

            .btn-group .btn:last-child {
                margin-bottom: 0;
            }
        }

        /* Enhanced hover effects */
        @media (min-width: 768px) {
            .card:hover .btn-group {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Print styles */
        @media print {

            .btn-group,
            .form-check,
            #hapusTerpilihBtn {
                display: none !important;
            }
        }
    </style>
@endpush
