{{-- resources/views/siswa/galeri/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('page-description', 'Dokumentasi kegiatan ' . $ekstrakurikuler->nama)

@section('content')
    <!-- Info Ekstrakurikuler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if ($ekstrakurikuler->gambar)
                                <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" alt="{{ $ekstrakurikuler->nama }}"
                                    class="rounded-3 shadow" width="120" height="120" style="object-fit: cover;">
                            @else
                                <div class="bg-white bg-opacity-20 rounded-3 d-inline-flex align-items-center justify-content-center shadow"
                                    style="width: 120px; height: 120px;">
                                    <i class="bi bi-collection text-white fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-2">{{ $ekstrakurikuler->nama }}</h3>
                            <p class="mb-3 opacity-90">{{ Str::limit($ekstrakurikuler->deskripsi, 120) }}</p>
                            <div class="d-flex gap-4">
                                <div>
                                    <small class="opacity-75 d-block">Pembina</small>
                                    <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                                </div>
                                <div>
                                    <small class="opacity-75 d-block">Total Galeri</small>
                                    <strong>{{ $galeris->total() }} Media</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($galeris->count() > 0)
        <!-- Filter & Search -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="filter" id="semua" value="semua" checked>
                    <label class="btn btn-outline-primary" for="semua">Semua</label>

                    <input type="radio" class="btn-check" name="filter" id="gambar" value="gambar">
                    <label class="btn btn-outline-primary" for="gambar">
                        <i class="bi bi-image me-1"></i>Foto
                    </label>

                    <input type="radio" class="btn-check" name="filter" id="video" value="video">
                    <label class="btn btn-outline-primary" for="video">
                        <i class="bi bi-play-btn me-1"></i>Video
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan judul...">
                </div>
            </div>
        </div>

        <!-- Galeri Grid -->
        <div class="row g-4" id="galeriGrid">
            @foreach ($galeris as $galeri)
                <div class="col-lg-3 col-md-4 col-sm-6 galeri-item" data-type="{{ $galeri->tipe }}"
                    data-title="{{ strtolower($galeri->judul) }}">
                    <div class="card galeri-card h-100">
                        <div class="position-relative">
                            @if ($galeri->tipe == 'gambar')
                                <img src="{{ Storage::url($galeri->path_file) }}" class="card-img-top galeri-thumbnail"
                                    alt="{{ $galeri->judul }}" style="height: 200px; object-fit: cover;"
                                    data-bs-toggle="modal" data-bs-target="#galeriModal"
                                    data-src="{{ Storage::url($galeri->path_file) }}" data-title="{{ $galeri->judul }}"
                                    data-description="{{ $galeri->deskripsi }}" data-type="image">
                            @else
                                <div class="position-relative">
                                    <video class="card-img-top" style="height: 200px; object-fit: cover;" muted>
                                        <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                    </video>
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <button class="btn btn-primary btn-lg rounded-circle" data-bs-toggle="modal"
                                            data-bs-target="#galeriModal" data-src="{{ Storage::url($galeri->path_file) }}"
                                            data-title="{{ $galeri->judul }}" data-description="{{ $galeri->deskripsi }}"
                                            data-type="video">
                                            <i class="bi bi-play-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Type Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-{{ $galeri->tipe == 'gambar' ? 'info' : 'warning' }}">
                                    <i class="bi bi-{{ $galeri->tipe == 'gambar' ? 'image' : 'play-btn' }} me-1"></i>
                                    {{ ucfirst($galeri->tipe) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title">{{ $galeri->judul }}</h6>
                            @if ($galeri->deskripsi)
                                <p class="card-text text-muted small">{{ Str::limit($galeri->deskripsi, 80) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ $galeri->created_at->diffForHumans() }}
                                </small>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#galeriModal" data-src="{{ Storage::url($galeri->path_file) }}"
                                    data-title="{{ $galeri->judul }}" data-description="{{ $galeri->deskripsi }}"
                                    data-type="{{ $galeri->tipe == 'gambar' ? 'image' : 'video' }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $galeris->links() }}
        </div>
    @else
        <!-- No Media -->
        <div class="text-center py-5">
            <i class="bi bi-images text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 mb-2">Belum Ada Media</h4>
            <p class="text-muted">Pembina belum mengupload foto atau video kegiatan.</p>
        </div>
    @endif

    <!-- Modal Galeri -->
    <div class="modal fade" id="galeriModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galeriModalTitle">Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div id="galeriModalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <p class="text-muted mb-0" id="galeriModalDescription"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .galeri-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .galeri-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .galeri-thumbnail {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .galeri-thumbnail:hover {
            transform: scale(1.05);
        }

        .btn-check:checked+.btn-outline-primary {
            background-color: var(--bs-primary);
            color: white;
        }

        .modal-body img,
        .modal-body video {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const galeriModal = document.getElementById('galeriModal');
            const modalTitle = document.getElementById('galeriModalTitle');
            const modalContent = document.getElementById('galeriModalContent');
            const modalDescription = document.getElementById('galeriModalDescription');

            // Handle modal show
            galeriModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const src = button.getAttribute('data-src');
                const title = button.getAttribute('data-title');
                const description = button.getAttribute('data-description');
                const type = button.getAttribute('data-type');

                modalTitle.textContent = title;
                modalDescription.textContent = description || 'Tidak ada deskripsi';

                if (type === 'image') {
                    modalContent.innerHTML = `<img src="${src}" alt="${title}" class="img-fluid">`;
                } else {
                    modalContent.innerHTML = `
                        <video controls class="w-100" style="max-height: 70vh;">
                            <source src="${src}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    `;
                }
            });

            // Filter functionality
            const filterButtons = document.querySelectorAll('input[name="filter"]');
            const galeriItems = document.querySelectorAll('.galeri-item');
            const searchInput = document.getElementById('searchInput');

            function filterGaleri() {
                const activeFilter = document.querySelector('input[name="filter"]:checked').value;
                const searchTerm = searchInput.value.toLowerCase();

                galeriItems.forEach(item => {
                    const itemType = item.getAttribute('data-type');
                    const itemTitle = item.getAttribute('data-title');

                    const typeMatch = activeFilter === 'semua' || itemType === activeFilter;
                    const searchMatch = itemTitle.includes(searchTerm);

                    if (typeMatch && searchMatch) {
                        item.style.display = 'block';
                        item.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            filterButtons.forEach(button => {
                button.addEventListener('change', filterGaleri);
            });

            searchInput.addEventListener('input', filterGaleri);
        });
    </script>
@endpush
