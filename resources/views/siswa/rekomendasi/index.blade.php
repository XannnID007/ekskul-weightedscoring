@extends('layouts.app')

@section('title', 'Rekomendasi Ekstrakurikuler')
@section('page-title', 'Rekomendasi Ekstrakurikuler')
@section('page-description', 'Temukan ekstrakurikuler yang paling cocok dengan minat dan bakatmu')

@section('page-actions')
    <button class="btn btn-outline-light" onclick="regenerateRekomendasi()">
        <i class="bi bi-arrow-clockwise me-1"></i>Perbarui Rekomendasi
    </button>
@endsection

@section('content')
    <!-- Profil Completion Alert -->
    @if ($profilCheck['persentase'] < 100)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">Lengkapi Profil untuk Rekomendasi Terbaik</h6>
                    <p class="mb-2">Profil Anda {{ $profilCheck['persentase'] }}% lengkap. Lengkapi untuk mendapat
                        rekomendasi yang lebih akurat!</p>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ $profilCheck['persentase'] }}%"></div>
                    </div>
                    <a href="{{ route('siswa.profil') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-person-gear me-1"></i>Lengkapi Profil
                    </a>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- How Algorithm Works -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-3">
                                <i class="bi bi-cpu me-2"></i>Bagaimana Sistem Rekomendasi Bekerja?
                            </h4>
                            <p class="mb-3 opacity-90">
                                Sistem kami menggunakan <strong>Weighted Scoring Algorithm</strong> yang menganalisis
                                berbagai faktor untuk memberikan rekomendasi terbaik:
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-heart-fill"></i>
                                        </div>
                                        <div>
                                            <strong>50% Minat</strong>
                                            <br><small class="opacity-75">Kecocokan dengan minat Anda</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-trophy-fill"></i>
                                        </div>
                                        <div>
                                            <strong>30% Akademik</strong>
                                            <br><small class="opacity-75">Nilai dan prestasi akademik</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-clock-fill"></i>
                                        </div>
                                        <div>
                                            <strong>20% Jadwal</strong>
                                            <br><small class="opacity-75">Kesesuaian waktu kegiatan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-stars" style="font-size: 6rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($rekomendasis->count() > 0)
        <!-- Top 3 Recommendations -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="bi bi-trophy text-warning me-2"></i>Top 3 Rekomendasi Terbaik
                </h5>
            </div>
            @foreach ($rekomendasis->take(3) as $index => $rekomendasi)
                <div class="col-lg-4">
                    <div class="card h-100 border-0 position-relative"
                        style="background: linear-gradient(135deg, {{ $index == 0 ? '#ffd700, #ffed4a' : ($index == 1 ? '#c0c0c0, #e2e8f0' : '#cd7f32, #f6ad55') }} 0%, rgba(255,255,255,0.1) 100%);">
                        <!-- Ranking Badge -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-dark fs-6 px-3 py-2">
                                <i class="bi bi-{{ $index == 0 ? 'trophy' : ($index == 1 ? 'award' : 'medal') }} me-1"></i>
                                #{{ $index + 1 }}
                            </span>
                        </div>

                        <!-- Match Score -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                {{ number_format($rekomendasi->total_skor, 1) }}% Match
                            </span>
                        </div>

                        <div class="card-body p-4 text-center">
                            <!-- Ekstrakurikuler Image -->
                            <div class="mb-4 mt-4">
                                @if ($rekomendasi->ekstrakurikuler->gambar)
                                    <img src="{{ Storage::url($rekomendasi->ekstrakurikuler->gambar) }}"
                                        alt="{{ $rekomendasi->ekstrakurikuler->nama }}" class="rounded-3 shadow"
                                        width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center shadow"
                                        style="width: 120px; height: 120px;">
                                        <i class="bi bi-collection text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>

                            <h4 class="card-title text-dark mb-2">{{ $rekomendasi->ekstrakurikuler->nama }}</h4>

                            <!-- Categories -->
                            <div class="mb-3">
                                @foreach ($rekomendasi->ekstrakurikuler->kategori as $kategori)
                                    <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                @endforeach
                            </div>

                            <!-- Score Breakdown -->
                            <div class="row g-2 mb-3 text-start">
                                <div class="col-4">
                                    <small class="text-muted d-block">Minat</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $rekomendasi->skor_minat }}%">
                                        </div>
                                    </div>
                                    <small class="fw-bold">{{ number_format($rekomendasi->skor_minat, 0) }}%</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Akademik</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ $rekomendasi->skor_akademik }}%"></div>
                                    </div>
                                    <small class="fw-bold">{{ number_format($rekomendasi->skor_akademik, 0) }}%</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Jadwal</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $rekomendasi->skor_jadwal }}%"></div>
                                    </div>
                                    <small class="fw-bold">{{ number_format($rekomendasi->skor_jadwal, 0) }}%</small>
                                </div>
                            </div>

                            <!-- Reason -->
                            <p class="text-muted small mb-4">{{ $rekomendasi->alasan }}</p>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}"
                                    class="btn btn-dark">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                </a>
                                @if ($rekomendasi->ekstrakurikuler->masihBisaDaftar())
                                    <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}#daftar"
                                        class="btn btn-primary">
                                        <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle me-1"></i>Kuota Penuh
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- All Recommendations -->
        @if ($rekomendasis->count() > 3)
            <div class="row g-4">
                <div class="col-12">
                    <h5 class="mb-3">
                        <i class="bi bi-list-stars text-primary me-2"></i>Semua Rekomendasi
                    </h5>
                </div>

                @foreach ($rekomendasis->skip(3) as $rekomendasi)
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        @if ($rekomendasi->ekstrakurikuler->gambar)
                                            <img src="{{ Storage::url($rekomendasi->ekstrakurikuler->gambar) }}"
                                                alt="{{ $rekomendasi->ekstrakurikuler->nama }}" class="rounded-3"
                                                width="80" height="80" style="object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center"
                                                style="width: 80px; height: 80px;">
                                                <i class="bi bi-collection text-white fs-3"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $rekomendasi->ekstrakurikuler->nama }}</h6>
                                            <span
                                                class="badge bg-primary">{{ number_format($rekomendasi->total_skor, 1) }}%</span>
                                        </div>

                                        <p class="text-muted small mb-2">{{ Str::limit($rekomendasi->alasan, 100) }}</p>

                                        <!-- Quick Info -->
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Pembina</small>
                                                <small
                                                    class="fw-medium">{{ $rekomendasi->ekstrakurikuler->pembina->name }}</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Jadwal</small>
                                                <small
                                                    class="fw-medium">{{ $rekomendasi->ekstrakurikuler->jadwal_string }}</small>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}"
                                                class="btn btn-outline-primary btn-sm flex-grow-1">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                            @if ($rekomendasi->ekstrakurikuler->masihBisaDaftar())
                                                <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}#daftar"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-person-plus me-1"></i>Daftar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <!-- No Recommendations -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 mb-2">Belum Ada Rekomendasi</h4>
                        <p class="text-muted mb-4">
                            Lengkapi profil Anda terlebih dahulu untuk mendapatkan rekomendasi ekstrakurikuler yang sesuai
                            dengan minat dan bakat Anda.
                        </p>
                        <a href="{{ route('siswa.profil') }}" class="btn btn-primary">
                            <i class="bi bi-person-gear me-1"></i>Lengkapi Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tips Section -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <h6 class="mb-3">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips untuk Mendapatkan Rekomendasi Terbaik
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-person-check text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Lengkapi Profil</h6>
                                    <small class="text-muted">Isi semua data profil termasuk minat, nilai, dan informasi
                                        pribadi lainnya.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-arrow-clockwise text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Update Berkala</h6>
                                    <small class="text-muted">Perbarui rekomendasi secara berkala jika ada perubahan minat
                                        atau nilai.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-eye text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Jelajahi Detail</h6>
                                    <small class="text-muted">Lihat detail setiap ekstrakurikuler untuk memahami kegiatan
                                        dan persyaratannya.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function regenerateRekomendasi() {
            Swal.fire({
                title: 'Perbarui Rekomendasi?',
                text: 'Sistem akan menganalisis ulang profil Anda dan memberikan rekomendasi terbaru.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6f42c1',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Perbarui!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('{{ route('siswa.rekomendasi.regenerate') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
@endpush

@push('styles')
    <style>
        .progress {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .badge {
            font-size: 0.8em;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(111, 66, 193, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(111, 66, 193, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(111, 66, 193, 0);
            }
        }

        .btn-primary {
            animation: pulse 2s infinite;
        }
    </style>
@endpush
