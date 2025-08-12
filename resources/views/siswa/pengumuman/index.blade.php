{{-- resources/views/siswa/pengumuman/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('page-description', 'Pengumuman terbaru dari ' . $ekstrakurikuler->nama)

@section('content')
    <div class="row mb-4">
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
                                    <small class="text-muted d-block">TOTAL PENGUMUMAN</small>
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
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
            <div>
                Ada <strong>{{ $pengumumanPenting }} pengumuman penting</strong> yang perlu Anda perhatikan.
            </div>
        </div>
    @endif

    @if ($pengumumans->count() > 0)
        <div class="row g-3">
            @foreach ($pengumumans as $pengumuman)
                <div class="col-12">
                    <div class="card pengumuman-card {{ $pengumuman->is_penting ? 'penting' : '' }}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div
                                    class="icon-wrapper me-3 bg-{{ $pengumuman->is_penting ? 'warning' : 'primary' }}-subtle text-{{ $pengumuman->is_penting ? 'warning' : 'primary' }}-emphasis">
                                    <i
                                        class="bi bi-{{ $pengumuman->is_penting ? 'exclamation-lg' : 'megaphone-fill' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1">{{ $pengumuman->judul }}</h5>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> {{ $pengumuman->pembuat->name ?? 'Admin' }}
                                                &bull;
                                                <i class="bi bi-clock"></i> {{ $pengumuman->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if ($pengumuman->is_penting)
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill">Penting</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 mb-2">{{ $pengumuman->konten }}</p>
                                    <a href="{{ route('siswa.pengumuman.show', $pengumuman) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $pengumumans->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-megaphone-fill text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 mb-2">Belum Ada Pengumuman</h4>
            <p class="text-muted">Pembina belum membuat pengumuman untuk ekstrakurikuler ini.</p>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .pengumuman-card {
            border: 1px solid var(--bs-gray-200);
            transition: all 0.2s ease-in-out;
            animation: fadeInUp 0.6s ease-out forwards;
            /* Animasi */
        }

        .pengumuman-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-left-color: var(--bs-primary) !important;
        }

        .pengumuman-card.penting {
            border-left: 4px solid var(--bs-warning);
            background-color: var(--bs-warning-bg-subtle);
        }

        .icon-wrapper {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Animasi staggered */
        @for ($i = 1; $i <= 10; $i++)
            .pengumuman-card:nth-child({{ $i }}) {
                animation-delay: {{ $i * 0.07 }}s;
            }
        @endfor

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
