@extends('layouts.app')

@section('title', 'Jelajahi Ekstrakurikuler')
@section('page-title', 'Jelajahi Ekstrakurikuler')
@section('page-description', 'Temukan ekstrakurikuler yang sesuai dengan minat dan bakatmu')

@section('content')
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.ekstrakurikuler.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Ekstrakurikuler</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Nama atau deskripsi...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori_options as $key => $label)
                                <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="tersedia" name="tersedia" value="1"
                                {{ request('tersedia') ? 'checked' : '' }}>
                            <label class="form-check-label" for="tersedia">
                                Hanya yang tersedia
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-secondary btn-sm">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Info -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="mb-1">{{ $ekstrakurikulers->total() }} Ekstrakurikuler Ditemukan</h6>
            @if (request()->anyFilled(['search', 'kategori', 'tersedia']))
                <small class="text-muted">
                    Filter aktif:
                    @if (request('search'))
                        <span class="badge bg-primary me-1">Pencarian: "{{ request('search') }}"</span>
                    @endif
                    @if (request('kategori'))
                        <span class="badge bg-info me-1">Kategori: {{ $kategori_options[request('kategori')] }}</span>
                    @endif
                    @if (request('tersedia'))
                        <span class="badge bg-success me-1">Tersedia</span>
                    @endif
                </small>
            @endif
        </div>
        <div>
            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-outline-warning">
                <i class="bi bi-stars me-1"></i>Lihat Rekomendasi
            </a>
        </div>
    </div>

    @if ($ekstrakurikulers->count() > 0)
        <!-- Ekstrakurikuler Grid -->
        <div class="row g-4">
            @foreach ($ekstrakurikulers as $ekstrakurikuler)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 ekstrakurikuler-card">
                        <!-- Image -->
                        <div class="position-relative">
                            @if ($ekstrakurikuler->gambar)
                                <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" class="card-img-top"
                                    alt="{{ $ekstrakurikuler->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-primary text-white"
                                    style="height: 200px;">
                                    <i class="bi bi-collection" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                @if ($ekstrakurikuler->masihBisaDaftar())
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Penuh</span>
                                @endif
                            </div>

                            <!-- Popularity Badge -->
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-dark">{{ $ekstrakurikuler->total_pendaftar }} pendaftar</span>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Title -->
                            <h5 class="card-title">{{ $ekstrakurikuler->nama }}</h5>

                            <!-- Categories -->
                            <div class="mb-2">
                                @if ($ekstrakurikuler->kategori && is_array($ekstrakurikuler->kategori))
                                    @foreach ($ekstrakurikuler->kategori as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @elseif($ekstrakurikuler->kategori && is_string($ekstrakurikuler->kategori))
                                    @php
                                        $kategoriArray = json_decode($ekstrakurikuler->kategori, true);
                                        if (!$kategoriArray) {
                                            $kategoriArray = [$ekstrakurikuler->kategori];
                                        }
                                    @endphp
                                    @foreach ($kategoriArray as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($ekstrakurikuler->deskripsi, 100) }}
                            </p>

                            <!-- Info Grid -->
                            <div class="row g-2 mb-3 small">
                                <div class="col-6">
                                    <strong>Pembina:</strong><br>
                                    <span class="text-muted">{{ $ekstrakurikuler->pembina->name }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Jadwal:</strong><br>
                                    <span class="text-muted">{{ $ekstrakurikuler->jadwal_string }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Kapasitas:</strong><br>
                                    <span
                                        class="text-muted">{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Min. Nilai:</strong><br>
                                    <span class="text-muted">{{ $ekstrakurikuler->nilai_minimal }}</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Kapasitas</span>
                                    <span>{{ round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                        style="width: {{ ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('siswa.ekstrakurikuler.show', $ekstrakurikuler) }}"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                </a>

                                @if (auth()->user()->sudahTerdaftarEkstrakurikuler())
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-info-circle me-1"></i>Sudah Terdaftar Lain
                                    </button>
                                @elseif(!$ekstrakurikuler->masihBisaDaftar())
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle me-1"></i>Kuota Penuh
                                    </button>
                                @elseif(auth()->user()->nilai_rata_rata && auth()->user()->nilai_rata_rata < $ekstrakurikuler->nilai_minimal)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-exclamation-circle me-1"></i>Nilai Tidak Memenuhi
                                    </button>
                                @else
                                    <a href="{{ route('siswa.ekstrakurikuler.show', $ekstrakurikuler) }}#daftar"
                                        class="btn btn-primary">
                                        <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $ekstrakurikulers->withQueryString()->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 mb-2">Tidak Ada Ekstrakurikuler Ditemukan</h4>
            <p class="text-muted mb-4">
                @if (request()->anyFilled(['search', 'kategori', 'tersedia']))
                    Coba ubah kriteria pencarian atau filter Anda.
                @else
                    Belum ada ekstrakurikuler yang tersedia saat ini.
                @endif
            </p>
            @if (request()->anyFilled(['search', 'kategori', 'tersedia']))
                <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                </a>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Auto submit form on filter change
        document.getElementById('kategori').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('tersedia').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Card hover animations
        document.querySelectorAll('.ekstrakurikuler-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .ekstrakurikuler-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ekstrakurikuler-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            transition: all 0.3s ease;
        }

        .ekstrakurikuler-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .badge {
            font-size: 0.75em;
        }

        .progress {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
@endpush
