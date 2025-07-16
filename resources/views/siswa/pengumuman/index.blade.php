{{-- resources/views/siswa/pengumuman/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('page-description', 'Pengumuman terbaru dari ' . $ekstrakurikuler->nama)

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
                                    <small class="opacity-75 d-block">Total Pengumuman</small>
                                    <strong>{{ $pengumumans->total() }} Pengumuman</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($pengumumanPenting > 0)
        <!-- Alert Pengumuman Penting -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                <div>
                    <h6 class="alert-heading mb-1">Pengumuman Penting!</h6>
                    <p class="mb-0">Ada {{ $pengumumanPenting }} pengumuman penting yang perlu Anda baca.</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($pengumumans->count() > 0)
        <!-- Pengumuman List -->
        <div class="row g-4">
            @foreach ($pengumumans as $pengumuman)
                <div class="col-12">
                    <div class="card pengumuman-card {{ $pengumuman->is_penting ? 'border-warning' : '' }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div
                                                class="bg-{{ $pengumuman->is_penting ? 'warning' : 'primary' }} rounded-circle p-2">
                                                <i
                                                    class="bi bi-{{ $pengumuman->is_penting ? 'exclamation-triangle' : 'megaphone' }} text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="mb-0 me-2">{{ $pengumuman->judul }}</h5>
                                                @if ($pengumuman->is_penting)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-star-fill me-1"></i>Penting
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-muted mb-2">
                                                {{ Str::limit($pengumuman->konten, 200) }}
                                            </p>

                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="bi bi-person me-1"></i>
                                                <span class="me-3">{{ $pengumuman->pembuat->name ?? 'Admin' }}</span>
                                                <i class="bi bi-clock me-1"></i>
                                                <span>{{ $pengumuman->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="d-flex flex-md-column gap-2">
                                        <a href="{{ route('siswa.pengumuman.show', $pengumuman) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Baca Selengkapnya
                                        </a>

                                        @if ($pengumuman->is_penting)
                                            <button class="btn btn-warning btn-sm" disabled>
                                                <i class="bi bi-bookmark-star me-1"></i>Prioritas Tinggi
                                            </button>
                                        @endif
                                    </div>

                                    <div class="mt-2">
                                        <small class="text-muted">
                                            {{ $pengumuman->created_at->format('d M Y, H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($pengumuman->is_penting)
                            <div class="card-footer bg-warning bg-opacity-10 border-top-0">
                                <small class="text-warning">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pengumuman ini ditandai sebagai prioritas tinggi oleh pembina.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pengumumans->links() }}
        </div>
    @else
        <!-- No Announcements -->
        <div class="text-center py-5">
            <i class="bi bi-megaphone text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 mb-2">Belum Ada Pengumuman</h4>
            <p class="text-muted">Pembina belum membuat pengumuman untuk ekstrakurikuler ini.</p>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .pengumuman-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pengumuman-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .pengumuman-card.border-warning {
            border-left: 4px solid #ffc107 !important;
        }

        .badge {
            font-size: 0.75em;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pengumuman-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .pengumuman-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .pengumuman-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .pengumuman-card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto dismiss alert
        setTimeout(function() {
            const alert = document.querySelector('.alert-warning');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 10000); // 10 seconds

        // Add read indicator (bisa dikembangkan dengan AJAX)
        document.querySelectorAll('.pengumuman-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (!e.target.closest('.btn')) {
                    // Mark as read - bisa ditambahkan AJAX call
                    this.style.opacity = '0.8';
                }
            });
        });
    </script>
@endpush
