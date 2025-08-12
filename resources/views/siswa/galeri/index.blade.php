{{-- resources/views/siswa/galeri/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('page-description', 'Dokumentasi kegiatan ' . $ekstrakurikuler->nama)

@push('styles')
    <style>
        .galeri-card {
            transition: all 0.3s ease;
            border: 1px solid var(--bs-gray-200);
            overflow: hidden;
        }

        .galeri-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .galeri-thumbnail-wrapper {
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .galeri-thumbnail-wrapper img,
        .galeri-thumbnail-wrapper video {
            transition: transform 0.4s ease;
        }

        .galeri-thumbnail-wrapper:hover img,
        .galeri-thumbnail-wrapper:hover video {
            transform: scale(1.05);
        }

        .galeri-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, transparent 50%);
            display: flex;
            align-items: flex-end;
            padding: 0.75rem;
            transition: background 0.3s ease;
        }

        .galeri-thumbnail-wrapper:hover .galeri-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 70%);
        }

        .play-icon-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            color: white;
            opacity: 0.8;
            pointer-events: none;
            /* Agar tidak menghalangi klik */
            transition: opacity 0.3s ease;
        }

        .galeri-thumbnail-wrapper:hover .play-icon-overlay {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4 pb-2 border-bottom">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        @if ($ekstrakurikuler->gambar)
                            <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" alt="{{ $ekstrakurikuler->nama }}"
                                class="rounded-3 me-md-4 mb-3 mb-md-0" width="100" height="100"
                                style="object-fit: cover;">
                        @else
                            <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center me-md-4 mb-3 mb-md-0"
                                style="width: 100px; height: 100px;">
                                <i class="bi bi-collection fs-1"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h3 class="mb-1">{{ $ekstrakurikuler->nama }}</h3>
                            <p class="text-muted mb-2">{{ Str::limit($ekstrakurikuler->deskripsi, 120) }}</p>
                            <div class="d-flex gap-4">
                                <div>
                                    <small class="text-muted d-block">PEMBINA</small>
                                    <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                                </div>
                                <div>
                                    <small class="text-muted d-block">TOTAL MEDIA</small>
                                    <strong>{{ $galeris->total() }} File</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($galeris->count() > 0)
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="filter" id="semua" value="semua" checked>
                <label class="btn btn-outline-primary" for="semua"><i class="bi bi-grid-3x3-gap"></i> Semua</label>

                <input type="radio" class="btn-check" name="filter" id="gambar" value="gambar">
                <label class="btn btn-outline-primary" for="gambar"><i class="bi bi-image"></i> Foto</label>

                <input type="radio" class="btn-check" name="filter" id="video" value="video">
                <label class="btn btn-outline-primary" for="video"><i class="bi bi-play-btn"></i> Video</label>
            </div>
            <div class="mt-3 mt-md-0">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan judul...">
            </div>
        </div>

        <div class="row g-4" id="galeriGrid">
            @foreach ($galeris as $galeri)
                <div class="col-lg-3 col-md-4 col-sm-6 galeri-item" data-type="{{ $galeri->tipe }}"
                    data-title="{{ strtolower($galeri->judul) }}">
                    <div class="card galeri-card h-100" data-bs-toggle="modal" data-bs-target="#galeriModal"
                        data-src="{{ Storage::url($galeri->path_file) }}" data-title="{{ $galeri->judul }}"
                        data-description="{{ $galeri->deskripsi }}" data-type="{{ $galeri->tipe }}">
                        <div class="galeri-thumbnail-wrapper">
                            @if ($galeri->tipe == 'gambar')
                                <img src="{{ Storage::url($galeri->path_file) }}" class="card-img-top"
                                    alt="{{ $galeri->judul }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-dark d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <video class="w-100 h-100" style="object-fit: cover;" muted>
                                        <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                    </video>
                                    <div class="play-icon-overlay"><i class="bi bi-play-circle-fill"></i></div>
                                </div>
                            @endif
                            <div class="galeri-overlay">
                                <span
                                    class="badge bg-{{ $galeri->tipe == 'gambar' ? 'info' : 'warning' }}-subtle text-{{ $galeri->tipe == 'gambar' ? 'info' : 'warning' }}-emphasis rounded-pill">
                                    {{ ucfirst($galeri->tipe) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($galeri->judul, 35) }}</h6>
                            <small class="text-muted">{{ $galeri->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $galeris->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-images text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 mb-2">Belum Ada Media</h4>
            <p class="text-muted">Galeri untuk ekstrakurikuler ini masih kosong.</p>
        </div>
    @endif

    {{-- Modal Galeri tetap sama --}}
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
