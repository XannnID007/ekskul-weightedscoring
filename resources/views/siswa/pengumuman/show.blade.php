{{-- resources/views/siswa/pengumuman/show.blade.php --}}
@extends('layouts.app')

@section('title', $pengumuman->judul)
@section('page-title', 'Detail Pengumuman')
@section('page-description', 'Pengumuman dari ' . $ekstrakurikuler->nama)

@section('page-actions')
    <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Pengumuman
    </a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-white border-0 p-4">
                    @if ($pengumuman->is_penting)
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill mb-2">
                            Penting
                        </span>
                    @endif
                    <h2 class="card-title">{{ $pengumuman->judul }}</h2>
                    <div class="d-flex align-items-center text-muted small gap-3">
                        <span><i class="bi bi-person me-1"></i> {{ $pengumuman->pembuat->name ?? 'Admin' }}</span>
                        <span><i class="bi bi-clock me-1"></i> {{ $pengumuman->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="pengumuman-content">
                        {!! nl2br(e($pengumuman->konten)) !!}
                    </div>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Dipublikasikan: {{ $pengumuman->created_at->format('d M Y, H:i') }}
                    </small>
                    {{-- Tombol Aksi Tambahan --}}
                    <div>
                        {{-- Share and Print buttons can be added here if needed --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-collection text-primary me-2"></i>Ekstrakurikuler</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        @if ($ekstrakurikuler->gambar)
                            <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" alt="{{ $ekstrakurikuler->nama }}"
                                class="rounded-3 me-3" width="60" height="60" style="object-fit: cover;">
                        @else
                            <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center me-3"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-collection fs-3"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $ekstrakurikuler->nama }}</h6>
                            <small class="text-muted">{{ $ekstrakurikuler->pembina->name ?? 'Pembina' }}</small>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('siswa.jadwal') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-calendar3 me-1"></i>Lihat Jadwal Lengkap
                        </a>
                    </div>
                </div>
            </div>

            @if ($pengumumanLainnya->count() > 0)
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-megaphone-fill text-primary me-2"></i>Pengumuman Lainnya</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($pengumumanLainnya as $other)
                            <div class="info-list-item">
                                <div
                                    class="icon-wrapper bg-{{ $other->is_penting ? 'warning' : 'primary' }}-subtle text-{{ $other->is_penting ? 'warning' : 'primary' }}-emphasis">
                                    <i class="bi bi-{{ $other->is_penting ? 'exclamation-lg' : 'megaphone' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('siswa.pengumuman.show', $other) }}"
                                        class="text-decoration-none text-dark">
                                        <h6 class="mb-1 fw-bold stretched-link">{{ Str::limit($other->judul, 35) }}</h6>
                                    </a>
                                    <p class="text-muted small mb-0">{{ $other->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-list-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--bs-gray-200);
        }

        .info-list-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-list-item .icon-wrapper {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background-color: var(--bs-gray-100);
            color: var(--bs-gray-600);
            font-size: 1.25rem;
        }

        .pengumuman-content {
            font-size: 1.05rem;
            line-height: 1.8;
            color: var(--bs-gray-700);
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
