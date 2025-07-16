@extends('layouts.app')

@section('title', 'Detail Rekomendasi')
@section('page-title', 'Detail Rekomendasi')
@section('page-description', 'Analisis mendalam mengapa ekstrakurikuler ini cocok untuk Anda')

@section('page-actions')
    <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Rekomendasi
    </a>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Hero Section -->
            <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if ($rekomendasi->ekstrakurikuler->gambar)
                                <img src="{{ Storage::url($rekomendasi->ekstrakurikuler->gambar) }}"
                                    alt="{{ $rekomendasi->ekstrakurikuler->nama }}" class="rounded-3 shadow" width="120"
                                    height="120" style="object-fit: cover;">
                            @else
                                <div class="bg-white bg-opacity-20 rounded-3 d-inline-flex align-items-center justify-content-center shadow"
                                    style="width: 120px; height: 120px;">
                                    <i class="bi bi-collection text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-2">{{ $rekomendasi->ekstrakurikuler->nama }}</h3>
                            <div class="mb-3">
                                @if ($rekomendasi->ekstrakurikuler->kategori && is_array($rekomendasi->ekstrakurikuler->kategori))
                                    @foreach ($rekomendasi->ekstrakurikuler->kategori as $kategori)
                                        <span class="badge bg-light text-dark me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <p class="mb-0 opacity-90">{{ $rekomendasi->ekstrakurikuler->deskripsi }}</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <h2 class="mb-1">{{ number_format($rekomendasi->total_skor, 1) }}%</h2>
                                <strong>Match Score</strong>
                                <div class="mt-2">
                                    <i class="bi bi-stars" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Algoritma Analysis -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-cpu me-2"></i>Analisis Algoritma Weighted Scoring
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Sistem rekomendasi menggunakan tiga kriteria utama dengan bobot yang berbeda untuk menentukan
                        tingkat kesesuaian ekstrakurikuler dengan profil Anda.
                    </p>

                    <!-- Skor Minat -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-heart text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Kesesuaian Minat (Bobot: 50%)</h6>
                                    <small class="text-muted">Seberapa cocok dengan minat dan hobi Anda</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 text-info">{{ number_format($rekomendasi->skor_minat, 1) }}%</h5>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 12px;">
                            <div class="progress-bar bg-info" style="width: {{ $rekomendasi->skor_minat }}%"></div>
                        </div>
                        <small class="text-muted">
                            @php
                                $minatUser = auth()->user()->minat_array ?? [];
                                $kategoriEkskul = $rekomendasi->ekstrakurikuler->kategori ?? [];
                                $kecocokan = array_intersect($minatUser, $kategoriEkskul);
                            @endphp
                            @if (count($kecocokan) > 0)
                                Minat yang cocok: {{ implode(', ', $kecocokan) }}
                            @else
                                Tidak ada minat yang secara langsung cocok, tetapi tetap relevan dengan profil Anda
                            @endif
                        </small>
                    </div>

                    <!-- Skor Akademik -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-trophy text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Kesesuaian Akademik (Bobot: 30%)</h6>
                                    <small class="text-muted">Berdasarkan nilai rata-rata dan prestasi</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 text-warning">{{ number_format($rekomendasi->skor_akademik, 1) }}%</h5>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 12px;">
                            <div class="progress-bar bg-warning" style="width: {{ $rekomendasi->skor_akademik }}%"></div>
                        </div>
                        <small class="text-muted">
                            Nilai rata-rata Anda: {{ auth()->user()->nilai_rata_rata ?? 'Belum diisi' }} |
                            Minimal: {{ $rekomendasi->ekstrakurikuler->nilai_minimal }}
                            @if (auth()->user()->nilai_rata_rata >= $rekomendasi->ekstrakurikuler->nilai_minimal)
                                ✓ Memenuhi syarat
                            @else
                                ⚠ Perlu peningkatan
                            @endif
                        </small>
                    </div>

                    <!-- Skor Jadwal -->
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-clock text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Kesesuaian Jadwal (Bobot: 20%)</h6>
                                    <small class="text-muted">Fleksibilitas waktu kegiatan</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 text-success">{{ number_format($rekomendasi->skor_jadwal, 1) }}%</h5>
                            </div>
                        </div>
                        <div class="progress mb-2" style="height: 12px;">
                            <div class="progress-bar bg-success" style="width: {{ $rekomendasi->skor_jadwal }}%"></div>
                        </div>
                        <small class="text-muted">
                            Jadwal kegiatan: {{ $rekomendasi->ekstrakurikuler->jadwal_string }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Reasoning -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Mengapa Ini Cocok untuk Anda?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Alasan Rekomendasi:</strong> {{ $rekomendasi->alasan }}
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <h6 class="text-success">
                                <i class="bi bi-check-circle me-2"></i>Keunggulan
                            </h6>
                            <ul class="list-unstyled">
                                @if ($rekomendasi->skor_minat >= 70)
                                    <li class="mb-1">
                                        <i class="bi bi-check text-success me-2"></i>
                                        Sangat sesuai dengan minat Anda
                                    </li>
                                @endif
                                @if (auth()->user()->nilai_rata_rata >= $rekomendasi->ekstrakurikuler->nilai_minimal)
                                    <li class="mb-1">
                                        <i class="bi bi-check text-success me-2"></i>
                                        Memenuhi persyaratan akademik
                                    </li>
                                @endif
                                @if ($rekomendasi->skor_jadwal >= 70)
                                    <li class="mb-1">
                                        <i class="bi bi-check text-success me-2"></i>
                                        Jadwal yang fleksibel
                                    </li>
                                @endif
                                @if ($rekomendasi->ekstrakurikuler->masihBisaDaftar())
                                    <li class="mb-1">
                                        <i class="bi bi-check text-success me-2"></i>
                                        Masih ada kuota tersedia
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>Pertimbangan
                            </h6>
                            <ul class="list-unstyled">
                                @if ($rekomendasi->skor_minat < 70)
                                    <li class="mb-1">
                                        <i class="bi bi-info text-warning me-2"></i>
                                        Mungkin perlu adaptasi dengan kegiatan
                                    </li>
                                @endif
                                @if (auth()->user()->nilai_rata_rata < $rekomendasi->ekstrakurikuler->nilai_minimal)
                                    <li class="mb-1">
                                        <i class="bi bi-info text-warning me-2"></i>
                                        Perlu meningkatkan nilai rata-rata
                                    </li>
                                @endif
                                @if (!$rekomendasi->ekstrakurikuler->masihBisaDaftar())
                                    <li class="mb-1">
                                        <i class="bi bi-info text-warning me-2"></i>
                                        Kuota sudah penuh
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Quick Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Ekstrakurikuler</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">PEMBINA</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle p-2 me-2">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <div>
                                <strong>{{ $rekomendasi->ekstrakurikuler->pembina->name }}</strong>
                                @if ($rekomendasi->ekstrakurikuler->pembina->telepon)
                                    <br><small
                                        class="text-muted">{{ $rekomendasi->ekstrakurikuler->pembina->telepon }}</small>
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
                            <strong>{{ $rekomendasi->ekstrakurikuler->jadwal_string }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">KAPASITAS</label>
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $rekomendasi->ekstrakurikuler->peserta_saat_ini }}/{{ $rekomendasi->ekstrakurikuler->kapasitas_maksimal }}</span>
                            <span>{{ round(($rekomendasi->ekstrakurikuler->peserta_saat_ini / $rekomendasi->ekstrakurikuler->kapasitas_maksimal) * 100) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar {{ $rekomendasi->ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                style="width: {{ ($rekomendasi->ekstrakurikuler->peserta_saat_ini / $rekomendasi->ekstrakurikuler->kapasitas_maksimal) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label small text-muted">NILAI MINIMAL</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-2">
                                <i class="bi bi-trophy text-white"></i>
                            </div>
                            <strong>{{ $rekomendasi->ekstrakurikuler->nilai_minimal }}/100</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}"
                            class="btn btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                        </a>

                        @if (auth()->user()->sudahTerdaftarEkstrakurikuler())
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-info-circle me-1"></i>Sudah Terdaftar Ekstrakurikuler Lain
                            </button>
                        @elseif(!$rekomendasi->ekstrakurikuler->masihBisaDaftar())
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-x-circle me-1"></i>Kuota Penuh
                            </button>
                        @elseif(auth()->user()->nilai_rata_rata && auth()->user()->nilai_rata_rata < $rekomendasi->ekstrakurikuler->nilai_minimal)
                            <button class="btn btn-warning" disabled>
                                <i class="bi bi-exclamation-circle me-1"></i>Nilai Tidak Memenuhi
                            </button>
                        @else
                            <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}#daftar"
                                class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                            </a>
                        @endif

                        <button class="btn btn-outline-warning" onclick="shareRecommendation()">
                            <i class="bi bi-share me-1"></i>Bagikan Rekomendasi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Similar Recommendations -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-stars me-2"></i>Rekomendasi Lainnya
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $otherRecommendations = auth()
                            ->user()
                            ->rekomendasis()
                            ->with('ekstrakurikuler')
                            ->where('id', '!=', $rekomendasi->id)
                            ->orderBy('total_skor', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp

                    @if ($otherRecommendations->count() > 0)
                        @foreach ($otherRecommendations as $other)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }}">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
                                    <small class="text-white fw-bold">{{ number_format($other->total_skor, 0) }}%</small>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $other->ekstrakurikuler->nama }}</h6>
                                    <small class="text-muted">{{ Str::limit($other->alasan, 50) }}</small>
                                </div>
                                <a href="{{ route('siswa.rekomendasi.detail', $other) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-info-circle text-muted"></i>
                            <p class="text-muted mt-2 mb-0 small">Tidak ada rekomendasi lain</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function shareRecommendation() {
            if (navigator.share) {
                navigator.share({
                    title: 'Rekomendasi Ekstrakurikuler - {{ $rekomendasi->ekstrakurikuler->nama }}',
                    text: 'Saya mendapat rekomendasi {{ number_format($rekomendasi->total_skor, 1) }}% untuk ekstrakurikuler {{ $rekomendasi->ekstrakurikuler->nama }}!',
                    url: window.location.href
                });
            } else {
                // Fallback - copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showSuccess('Link rekomendasi berhasil disalin!');
                });
            }
        }

        // Animate progress bars on load
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.transition = 'width 1.5s ease-in-out';
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .progress {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .progress-bar {
            transition: width 1.5s ease-in-out;
        }

        .card {
            transition: all 0.3s ease;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
@endpush
