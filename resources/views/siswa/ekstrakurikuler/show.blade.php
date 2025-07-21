@extends('layouts.app')

@section('title', $ekstrakurikuler->nama)
@section('page-title', $ekstrakurikuler->nama)
@section('page-description', 'Detail informasi ekstrakurikuler dan pendaftaran')

@section('page-actions')
    <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Hero Section -->
            <div class="card mb-4">
                <div class="position-relative">
                    @if ($ekstrakurikuler->gambar)
                        <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" class="card-img-top"
                            alt="{{ $ekstrakurikuler->nama }}" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient"
                            style="height: 300px; background: linear-gradient(135deg, var(--bs-primary) 0%, #8b5cf6 100%);">
                            <i class="bi bi-collection text-white" style="font-size: 5rem;"></i>
                        </div>
                    @endif

                    <!-- Overlay Info -->
                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-4">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <h3 class="mb-1">{{ $ekstrakurikuler->nama }}</h3>
                                <div class="mb-2">
                                    @if (is_array($ekstrakurikuler->kategori))
                                        @foreach ($ekstrakurikuler->kategori as $kategori)
                                            <span class="badge bg-light text-dark me-1">{{ ucfirst($kategori) }}</span>
                                        @endforeach
                                    @elseif($ekstrakurikuler->kategori)
                                        @php
                                            $kategoriArray = json_decode($ekstrakurikuler->kategori, true) ?: [];
                                        @endphp
                                        @foreach ($kategoriArray as $kategori)
                                            <span class="badge bg-light text-dark me-1">{{ ucfirst($kategori) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="mb-0 opacity-75">
                                    {{ $ekstrakurikuler->pembina->name ?? 'Pembina belum ditentukan' }}</p>
                            </div>
                            <div class="text-end">
                                @if ($ekstrakurikuler->masihBisaDaftar())
                                    <span class="badge bg-success fs-6 px-3 py-2">Tersedia</span>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-2">Penuh</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Deskripsi
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $ekstrakurikuler->deskripsi }}</p>
                </div>
            </div>

            <!-- Gallery -->
            @if ($ekstrakurikuler->galeris && $ekstrakurikuler->galeris->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-images me-2"></i>Galeri Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($ekstrakurikuler->galeris->take(6) as $galeri)
                                <div class="col-md-4">
                                    @if ($galeri->tipe == 'gambar')
                                        <img src="{{ Storage::url($galeri->path_file) }}"
                                            class="img-fluid rounded gallery-item" alt="{{ $galeri->judul }}"
                                            data-bs-toggle="modal" data-bs-target="#galleryModal"
                                            data-image="{{ Storage::url($galeri->path_file) }}"
                                            data-title="{{ $galeri->judul }}">
                                    @else
                                        <div class="position-relative">
                                            <video class="img-fluid rounded"
                                                style="width: 100%; height: 150px; object-fit: cover;">
                                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                            </video>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="bi bi-play-circle text-white" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block mt-1">{{ $galeri->judul }}</small>
                                </div>
                            @endforeach
                        </div>
                        @if ($ekstrakurikuler->galeris->count() > 6)
                            <div class="text-center mt-3">
                                <small class="text-muted">Dan {{ $ekstrakurikuler->galeris->count() - 6 }} foto/video
                                    lainnya</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Announcements -->
            @if ($ekstrakurikuler->pengumumans && $ekstrakurikuler->pengumumans->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-megaphone me-2"></i>Pengumuman Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($ekstrakurikuler->pengumumans as $pengumuman)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i
                                        class="bi bi-{{ $pengumuman->is_penting ? 'exclamation-triangle' : 'info-circle' }} text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $pengumuman->judul }}
                                        @if ($pengumuman->is_penting)
                                            <span class="badge bg-warning ms-2">Penting</span>
                                        @endif
                                    </h6>
                                    <p class="mb-1 text-muted">{{ $pengumuman->konten }}</p>
                                    <small class="text-muted">{{ $pengumuman->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Quick Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-primary rounded-circle p-3 d-inline-flex mb-2">
                                    <i class="bi bi-people text-white"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $ekstrakurikuler->peserta_saat_ini ?? 0 }}</strong>
                                    <small class="text-muted">Peserta</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-success rounded-circle p-3 d-inline-flex mb-2">
                                    <i class="bi bi-trophy text-white"></i>
                                </div>
                                <div>
                                    <strong class="d-block">{{ $ekstrakurikuler->nilai_minimal ?? 0 }}</strong>
                                    <small class="text-muted">Min. Nilai</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label small text-muted">PEMBINA</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle p-2 me-2">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <div>
                                <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                                @if ($ekstrakurikuler->pembina && $ekstrakurikuler->pembina->telepon)
                                    <br><small class="text-muted">{{ $ekstrakurikuler->pembina->telepon }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">JADWAL</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-2 me-2">
                                <i class="bi bi-calendar text-white"></i>
                            </div>
                            <strong>{{ $ekstrakurikuler->jadwal_string ?? 'Belum ditentukan' }}</strong>
                        </div>
                    </div>

                    <div>
                        <label class="form-label small text-muted">KAPASITAS</label>
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $ekstrakurikuler->peserta_saat_ini ?? 0 }}/{{ $ekstrakurikuler->kapasitas_maksimal ?? 0 }}</span>
                            @php
                                $capacity = $ekstrakurikuler->kapasitas_maksimal ?? 1;
                                $current = $ekstrakurikuler->peserta_saat_ini ?? 0;
                                $percentage = $capacity > 0 ? round(($current / $capacity) * 100) : 0;
                            @endphp
                            <span>{{ $percentage }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                style="width: {{ $percentage }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalTitle">Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="galleryModalImage" src="" class="img-fluid rounded" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Gallery modal
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('click', function() {
                const image = this.getAttribute('data-image');
                const title = this.getAttribute('data-title');

                document.getElementById('galleryModalImage').src = image;
                document.getElementById('galleryModalTitle').textContent = title;
            });
        });

        // Form character counter
        function setupCharCounter(textareaId, minChars) {
            const textarea = document.getElementById(textareaId);
            if (!textarea) return;

            const counter = document.createElement('div');
            counter.className = 'form-text';
            textarea.parentNode.insertBefore(counter, textarea.nextSibling);

            function updateCounter() {
                const length = textarea.value.length;
                const remaining = minChars - length;

                if (remaining > 0) {
                    counter.textContent = `Minimal ${minChars} karakter. Kurang ${remaining} karakter lagi.`;
                    counter.className = 'form-text text-warning';
                } else {
                    counter.textContent = `${length} karakter`;
                    counter.className = 'form-text text-success';
                }
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }

        // Setup counters
        setupCharCounter('motivasi', 50);
        setupCharCounter('harapan', 20);

        // Form validation
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const motivasi = document.getElementById('motivasi');
            const harapan = document.getElementById('harapan');

            if (motivasi && motivasi.value.length < 50) {
                e.preventDefault();
                motivasi.focus();
                showError('Motivasi harus minimal 50 karakter');
                return;
            }

            if (harapan && harapan.value.length < 20) {
                e.preventDefault();
                harapan.focus();
                showError('Harapan harus minimal 20 karakter');
                return;
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .gallery-item {
            cursor: pointer;
            transition: all 0.3s ease;
            height: 150px;
            object-fit: cover;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .progress {
            height: 8px;
        }

        .card-img-top {
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(108, 66, 193, 0.25);
        }

        .alert {
            border: none;
            border-radius: 10px;
        }
    </style>
@endpush
