{{-- resources/views/siswa/pengumuman/show.blade.php --}}
@extends('layouts.app')

@section('title', $pengumuman->judul)
@section('page-title', 'Detail Pengumuman')
@section('page-description', 'Pengumuman dari ' . $ekstrakurikuler->nama)

@section('page-actions')
    <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Pengumuman
    </a>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Pengumuman Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header {{ $pengumuman->is_penting ? 'bg-warning text-dark' : '' }}">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-{{ $pengumuman->is_penting ? 'dark' : 'primary' }} rounded-circle p-2">
                                <i
                                    class="bi bi-{{ $pengumuman->is_penting ? 'exclamation-triangle' : 'megaphone' }} text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $pengumuman->judul }}</h5>
                            <div class="d-flex align-items-center gap-3">
                                <small>
                                    <i class="bi bi-person me-1"></i>
                                    {{ $pengumuman->pembuat->name ?? 'Admin' }}
                                </small>
                                <small>
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $pengumuman->created_at->diffForHumans() }}
                                </small>
                                <small>
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $pengumuman->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>
                        @if ($pengumuman->is_penting)
                            <div>
                                <span class="badge bg-dark">
                                    <i class="bi bi-star-fill me-1"></i>Penting
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="card-body">
                    <div class="pengumuman-content">
                        {!! nl2br(e($pengumuman->konten)) !!}
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-eye me-1"></i>Dibaca pada {{ now()->format('d M Y, H:i') }}
                        </small>
                    </div>
                </div>
            </div>

            @if ($pengumuman->is_penting)
                <!-- Important Notice -->
                <div class="alert alert-warning mt-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Pengumuman Penting!</h6>
                            <p class="mb-0">Pengumuman ini ditandai sebagai prioritas tinggi. Pastikan Anda memahami dan
                                mengikuti instruksi yang diberikan.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Ekstrakurikuler Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi Ekstrakurikuler
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if ($ekstrakurikuler->gambar)
                            <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" alt="{{ $ekstrakurikuler->nama }}"
                                class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                        @else
                            <div class="bg-primary rounded d-flex align-items-center justify-content-center me-3"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-collection text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $ekstrakurikuler->nama }}</h6>
                            <small class="text-muted">{{ $ekstrakurikuler->pembina->name ?? 'Pembina' }}</small>
                        </div>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-12">
                            <strong>Jadwal Kegiatan:</strong><br>
                            <span class="text-muted">{{ $ekstrakurikuler->jadwal_string }}</span>
                        </div>
                        <div class="col-6">
                            <strong>Peserta:</strong><br>
                            <span class="text-muted">{{ $ekstrakurikuler->peserta_saat_ini }} siswa</span>
                        </div>
                        <div class="col-6">
                            <strong>Kapasitas:</strong><br>
                            <span class="text-muted">{{ $ekstrakurikuler->kapasitas_maksimal }} siswa</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid">
                        <a href="{{ route('siswa.jadwal') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-calendar3 me-1"></i>Lihat Jadwal Kegiatan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Other Announcements -->
            @if ($pengumumanLainnya->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-megaphone me-2"></i>Pengumuman Lainnya
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach ($pengumumanLainnya as $other)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="me-3">
                                    <div class="bg-{{ $other->is_penting ? 'warning' : 'primary' }} rounded-circle p-1"
                                        style="width: 32px; height: 32px;">
                                        <i
                                            class="bi bi-{{ $other->is_penting ? 'exclamation-triangle' : 'megaphone' }} text-white small"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('siswa.pengumuman.show', $other) }}"
                                            class="text-decoration-none">
                                            {{ Str::limit($other->judul, 50) }}
                                        </a>
                                        @if ($other->is_penting)
                                            <span class="badge bg-warning text-dark ms-1">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        @endif
                                    </h6>
                                    <p class="text-muted small mb-1">
                                        {{ Str::limit($other->konten, 80) }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $other->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-3">
                            <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-outline-primary btn-sm">
                                Lihat Semua Pengumuman
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pengumuman-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
        }

        .pengumuman-content p {
            margin-bottom: 1.2rem;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .alert-warning {
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
            border-left: 4px solid #ffc107;
        }

        .card-header.bg-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ffed4a 100%) !important;
            border-radius: 12px 12px 0 0;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }

        .btn-outline-success:hover {
            transform: translateY(-1px);
        }

        .btn-outline-info:hover {
            transform: translateY(-1px);
        }

        /* Print styles */
        @media print {

            .card-footer,
            .sidebar,
            .page-header,
            .btn,
            .alert {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .pengumuman-content {
                color: #000 !important;
            }

            body {
                background: white !important;
                color: #000 !important;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-header .flex-grow-1 {
                margin-top: 0.5rem;
                width: 100%;
            }

            .card-footer .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .pengumuman-content {
                font-size: 1rem;
            }
        }

        /* Animation */
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

        .card {
            animation: fadeInUp 0.6s ease-out;
        }

        .col-xl-4 .card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .col-xl-4 .card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .col-xl-4 .card:nth-child(4) {
            animation-delay: 0.3s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Share announcement function
        function shareAnnouncement() {
            const title = '{{ $pengumuman->judul }}';
            const text = 'Pengumuman dari {{ $ekstrakurikuler->nama }}: {{ Str::limit($pengumuman->konten, 100) }}';
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: text,
                    url: url
                }).then(() => {
                    console.log('Pengumuman berhasil dibagikan');
                }).catch((error) => {
                    console.log('Error sharing:', error);
                    fallbackShare(title, url);
                });
            } else {
                fallbackShare(title, url);
            }
        }

        // Fallback share function
        function fallbackShare(title, url) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Link Disalin!',
                        text: 'Link pengumuman berhasil disalin ke clipboard.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            } else {
                // Fallback untuk browser lama
                const tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                Swal.fire({
                    icon: 'success',
                    title: 'Link Disalin!',
                    text: 'Link pengumuman berhasil disalin.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }

        // Print announcement function
        function printAnnouncement() {
            // Set document title for print
            const originalTitle = document.title;
            document.title = '{{ $pengumuman->judul }} - {{ $ekstrakurikuler->nama }}';

            // Print
            window.print();

            // Restore original title
            document.title = originalTitle;
        }

        // Mark as read (untuk future development)
        function markAsRead() {
            // AJAX call untuk mark as read
            fetch(`{{ route('siswa.pengumuman.show', $pengumuman) }}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Pengumuman ditandai sebagai sudah dibaca');
                    }
                })
                .catch(error => {
                    console.error('Error marking as read:', error);
                });
        }

        // Auto mark as read when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate mark as read after 3 seconds
            setTimeout(() => {
                // markAsRead(); // Uncomment jika sudah implement di backend
            }, 3000);

            // Add smooth scroll behavior
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + P untuk print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                printAnnouncement();
            }

            // Ctrl + S untuk share
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                shareAnnouncement();
            }

            // Escape untuk kembali
            if (e.key === 'Escape') {
                window.location.href = '{{ route('siswa.pengumuman.index') }}';
            }
        });

        // Add reading progress indicator
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
        });
    </script>
@endpush
