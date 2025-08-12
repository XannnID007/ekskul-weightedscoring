@extends('layouts.app')

@section('title', 'Status Pendaftaran')
@section('page-title', 'Status Pendaftaran')
@section('page-description', 'Pantau status pendaftaran ekstrakurikuler Anda')

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 3rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 1rem;
            bottom: 1rem;
            width: 2px;
            background: var(--bs-gray-300);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.25rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: var(--bs-white);
            border: 3px solid var(--bs-gray-300);
            z-index: 1;
        }

        .timeline-item.completed::before {
            background: var(--bs-success);
            border-color: var(--bs-success);
        }

        .timeline-item.current::before {
            background: var(--bs-warning);
            border-color: var(--bs-warning);
            animation: pulse 2s infinite;
        }

        .timeline-item.pending::before {
            background: var(--bs-info);
            border-color: var(--bs-info);
        }

        .status-card {
            border-left: 4px solid var(--bs-gray-300);
            transition: all 0.3s ease;
        }

        .status-card.pending {
            border-left-color: var(--bs-warning);
            background: rgba(255, 193, 7, 0.05);
        }

        .status-card.approved {
            border-left-color: var(--bs-success);
            background: rgba(16, 185, 129, 0.05);
        }

        .status-card.rejected {
            border-left-color: var(--bs-danger);
            background: rgba(239, 68, 68, 0.05);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .progress-tracker {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-tracker::before {
            content: '';
            position: absolute;
            top: 1.5rem;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--bs-gray-200);
            z-index: 1;
        }

        .progress-step {
            background: var(--bs-white);
            border: 3px solid var(--bs-gray-300);
            border-radius: 50%;
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            font-weight: bold;
        }

        .progress-step.completed {
            background: var(--bs-success);
            border-color: var(--bs-success);
            color: white;
        }

        .progress-step.current {
            background: var(--bs-warning);
            border-color: var(--bs-warning);
            color: white;
            animation: pulse 2s infinite;
        }

        .step-label {
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--bs-gray-600);
        }

        .quick-actions {
            position: sticky;
            top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-xl-12">
            @php
                $user = auth()->user();
                $totalPendaftaran = $user->pendaftarans->count();
                $pendaftaranPending = $user->pendaftarans()->where('status', 'pending')->count();
                $pendaftaranDisetujui = $user->pendaftarans()->where('status', 'disetujui')->count();
                $pendaftaranDitolak = $user->pendaftarans()->where('status', 'ditolak')->count();
            @endphp

            <!-- Detail Pendaftaran -->
            @if ($user->pendaftarans->count() > 0)
                @foreach ($user->pendaftarans->sortByDesc('created_at') as $pendaftaran)
                    <div class="card status-card {{ $pendaftaran->status }} mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    @if ($pendaftaran->ekstrakurikuler->gambar)
                                        <img src="{{ Storage::url($pendaftaran->ekstrakurikuler->gambar) }}"
                                            alt="{{ $pendaftaran->ekstrakurikuler->nama }}" class="rounded-3 shadow-sm"
                                            width="80" height="80" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 80px; height: 80px;">
                                            <i class="bi bi-collection text-white fs-3"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-7">
                                    <h5 class="mb-1">{{ $pendaftaran->ekstrakurikuler->nama }}</h5>
                                    <p class="text-muted mb-2 small">
                                        {{ Str::limit($pendaftaran->ekstrakurikuler->deskripsi, 80) }}
                                    </p>

                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-calendar3 me-1"></i>Tanggal Daftar
                                            </small>
                                            <strong
                                                class="small">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-person me-1"></i>Pembina
                                            </small>
                                            <strong
                                                class="small">{{ $pendaftaran->ekstrakurikuler->pembina->name }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 text-center">
                                    <!-- Status Badge -->
                                    <div class="mb-2">
                                        @if ($pendaftaran->status == 'pending')
                                            <span class="badge bg-warning-subtle text-warning-emphasis fs-6 px-3 py-2">
                                                <i class="bi bi-clock me-1"></i>Menunggu Review
                                            </span>
                                        @elseif($pendaftaran->status == 'disetujui')
                                            <span class="badge bg-success-subtle text-success-emphasis fs-6 px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Diterima
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger-emphasis fs-6 px-3 py-2">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    @if ($pendaftaran->status == 'pending')
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-danger btn-sm"
                                                onclick="cancelPendaftaran({{ $pendaftaran->id }})">
                                                <i class="bi bi-x-lg me-1"></i>Batalkan
                                            </button>
                                        </div>
                                    @elseif($pendaftaran->status == 'disetujui')
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('siswa.jadwal') }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-calendar3 me-1"></i>Lihat Jadwal
                                            </a>
                                            <a href="{{ route('siswa.ekstrakurikuler.show', $pendaftaran->ekstrakurikuler) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                        </div>
                                    @else
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse"
                                            data-bs-target="#rejection-{{ $pendaftaran->id }}">
                                            <i class="bi bi-info-circle me-1"></i>Lihat Alasan
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Additional Info untuk Status Tertentu -->
                            @if ($pendaftaran->status == 'disetujui')
                                <hr class="my-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-success d-block">
                                            <i class="bi bi-check-circle me-1"></i>Disetujui pada
                                        </small>
                                        <strong>{{ $pendaftaran->disetujui_pada?->format('d M Y H:i') }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-success d-block">
                                            <i class="bi bi-person-check me-1"></i>Disetujui oleh
                                        </small>
                                        <strong>{{ $pendaftaran->penyetuju?->name }}</strong>
                                    </div>
                                </div>
                            @elseif($pendaftaran->status == 'ditolak')
                                <div class="collapse mt-3" id="rejection-{{ $pendaftaran->id }}">
                                    <div class="alert alert-danger border-0">
                                        <h6 class="alert-heading">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Alasan Penolakan
                                        </h6>
                                        <p class="mb-0">{{ $pendaftaran->alasan_penolakan }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Detail Pendaftaran (Collapsible) -->
                            <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#detail-{{ $pendaftaran->id }}">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail Pendaftaran
                                </button>
                            </div>

                            <div class="collapse mt-3" id="detail-{{ $pendaftaran->id }}">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted fw-bold">MOTIVASI</label>
                                                <p class="mb-0 small">{{ $pendaftaran->motivasi }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted fw-bold">HARAPAN</label>
                                                <p class="mb-0 small">{{ $pendaftaran->harapan }}</p>
                                            </div>
                                            @if ($pendaftaran->pengalaman)
                                                <div class="col-md-6">
                                                    <label class="form-label small text-muted fw-bold">PENGALAMAN</label>
                                                    <p class="mb-0 small">{{ $pendaftaran->pengalaman }}</p>
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted fw-bold">TINGKAT KOMITMEN</label>
                                                <span
                                                    class="badge bg-{{ $pendaftaran->tingkat_komitmen == 'tinggi' ? 'success' : ($pendaftaran->tingkat_komitmen == 'sedang' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($pendaftaran->tingkat_komitmen) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No Registration -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 mb-2">Belum Ada Pendaftaran</h4>
                        <p class="text-muted mb-4">
                            Anda belum mendaftar ekstrakurikuler apapun. Temukan ekstrakurikuler yang sesuai dengan minat
                            Anda.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-primary">
                                <i class="bi bi-stars me-1"></i>Lihat Rekomendasi
                            </a>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-collection me-1"></i>Jelajahi Semua
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        function cancelPendaftaran(id) {
            Swal.fire({
                title: 'Batalkan Pendaftaran?',
                text: 'Anda dapat mendaftar lagi nanti jika berubah pikiran.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Membatalkan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form to cancel
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/siswa/pendaftaran/${id}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Handle tracking modal
        document.addEventListener('DOMContentLoaded', function() {
            const trackingModal = document.getElementById('trackingModal');

            trackingModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const ekstrakurikulerName = button.getAttribute('data-ekstrakurikuler');
                const tanggalDaftar = button.getAttribute('data-tanggal');

                // Update modal content
                document.getElementById('modalEkstrakurikulerName').textContent = ekstrakurikulerName;
                document.getElementById('modalTanggalDaftar').textContent = 'Didaftarkan: ' + tanggalDaftar;
            });
        });

        function refreshStatus() {
            // Simulate refresh (in real app, this would make an AJAX call)
            Swal.fire({
                title: 'Memperbarui Status...',
                text: 'Memeriksa status terbaru',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                timer: 2000,
                willOpen: () => {
                    Swal.showLoading();
                }
            }).then(() => {
                // Reload page or update content
                location.reload();
            });
        }

        // Auto refresh every 30 seconds if there are pending applications
        @if ($pendaftaranPending > 0)
            setInterval(function() {
                // Silent check for status updates
                fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.ok) {
                        // Check if status changed (simplified)
                        console.log('Status checked at', new Date().toLocaleTimeString());
                    }
                });
            }, 30000); // Check every 30 seconds
        @endif

        // Animate cards on load
        document.addEventListener('DOMContentLoaded', function() {
            const statusCards = document.querySelectorAll('.status-card');

            statusCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Notification sound for status changes (optional)
        function playNotificationSound() {
            // Create audio context and play a simple beep
            const audioContext = new(window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.01);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.5);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }

        // Show welcome message for first-time users
        @if ($totalPendaftaran === 0)
            setTimeout(() => {
                Swal.fire({
                    title: 'Selamat Datang!',
                    text: 'Ini adalah halaman untuk memantau status pendaftaran ekstrakurikuler Anda. Mulai dengan mendapatkan rekomendasi yang sesuai!',
                    icon: 'info',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#3c9ae7'
                });
            }, 1000);
        @endif
    </script>
@endpush
