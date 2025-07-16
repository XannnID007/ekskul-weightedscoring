@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')
@section('page-description', 'Selamat datang, ' . auth()->user()->name)

@section('content')
    @php
        $user = auth()->user();
        $pendaftaran = $user
            ->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler.pembina'])
            ->first();
        $ekstrakurikuler = $pendaftaran ? $pendaftaran->ekstrakurikuler : null;

        // Mock data untuk statistik kehadiran
        $attendanceStats = [
            'total_kegiatan' => 15,
            'hadir' => 12,
            'izin' => 2,
            'terlambat' => 1,
            'alpa' => 0,
        ];
        $attendancePercentage =
            $attendanceStats['total_kegiatan'] > 0
                ? round(($attendanceStats['hadir'] / $attendanceStats['total_kegiatan']) * 100)
                : 0;

        // Mock data untuk jadwal mendatang
        $upcomingSchedule = [
            [
                'title' => $ekstrakurikuler ? $ekstrakurikuler->nama . ' - Latihan Rutin' : 'Kegiatan Ekstrakurikuler',
                'date' => 'Senin, 1 Jul 2024',
                'time' => '15:30 - 17:00',
                'type' => 'rutin',
                'is_today' => false,
                'is_tomorrow' => true,
            ],
            [
                'title' => 'Pertandingan Antar Sekolah',
                'date' => 'Sabtu, 6 Jul 2024',
                'time' => '08:00 - 12:00',
                'type' => 'kompetisi',
                'is_today' => false,
                'is_tomorrow' => false,
            ],
        ];
    @endphp

    <div class="row g-4">
        <!-- Status Cards -->
        <div class="col-md-6 col-xl-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Status Pendaftaran</h6>
                            <h2 class="mb-0">
                                @if ($user->pendaftarans()->where('status', 'disetujui')->exists())
                                    <i class="bi bi-check-circle text-success"></i>
                                @elseif($user->pendaftarans()->where('status', 'pending')->exists())
                                    <i class="bi bi-clock text-warning"></i>
                                @else
                                    <i class="bi bi-x-circle text-danger"></i>
                                @endif
                            </h2>
                            <small class="opacity-75">
                                @if ($user->pendaftarans()->where('status', 'disetujui')->exists())
                                    Sudah Terdaftar
                                @elseif($user->pendaftarans()->where('status', 'pending')->exists())
                                    Menunggu Persetujuan
                                @else
                                    Belum Mendaftar
                                @endif
                            </small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Tingkat Kehadiran</h6>
                            <h2 class="mb-0">
                                @if ($ekstrakurikuler)
                                    {{ $attendancePercentage }}%
                                @else
                                    -
                                @endif
                            </h2>
                            <small class="opacity-75">Persentase hadir</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Pengumuman Baru</h6>
                            <h2 class="mb-0">3</h2>
                            <small class="opacity-75">Belum dibaca</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-megaphone"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card stats-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Jadwal Hari Ini</h6>
                            <h2 class="mb-0">
                                @if ($ekstrakurikuler)
                                    1
                                @else
                                    0
                                @endif
                            </h2>
                            <small class="opacity-75">Kegiatan hari ini</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($ekstrakurikuler)
            <!-- Ekstrakurikuler yang Diikuti -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-collection text-primary me-2"></i>Ekstrakurikuler yang Diikuti
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                @if ($ekstrakurikuler->gambar)
                                    <img src="{{ Storage::url($ekstrakurikuler->gambar) }}"
                                        alt="{{ $ekstrakurikuler->nama }}" class="rounded-3" width="100" height="100"
                                        style="object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-collection text-white fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h4 class="mb-2">{{ $ekstrakurikuler->nama }}</h4>
                                <p class="text-muted mb-3">{{ Str::limit($ekstrakurikuler->deskripsi, 150) }}</p>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Pembina</small>
                                        <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Jadwal</small>
                                        <strong>{{ $ekstrakurikuler->jadwal_string ?? 'Belum ditentukan' }}</strong>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('siswa.jadwal') }}" class="btn btn-primary btn-sm me-2">
                                        <i class="bi bi-calendar3 me-1"></i>Lihat Jadwal
                                    </a>
                                    <a href="{{ route('siswa.kehadiran') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-graph-up me-1"></i>Rekap Kehadiran
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal & Kehadiran Sidebar -->
            <div class="col-xl-4">
                <!-- Jadwal Mendatang -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-week text-primary me-2"></i>Jadwal Mendatang
                        </h6>
                        <a href="{{ route('siswa.jadwal') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse ($upcomingSchedule as $schedule)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                <div
                                    class="bg-{{ $schedule['type'] == 'kompetisi' ? 'warning' : 'primary' }} rounded-circle p-2 me-3">
                                    <i
                                        class="bi bi-{{ $schedule['type'] == 'kompetisi' ? 'trophy' : 'calendar-event' }} text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $schedule['title'] }}</h6>
                                    <p class="text-muted small mb-1">
                                        {{ $schedule['date'] }}
                                        @if ($schedule['is_today'])
                                            <span class="badge bg-success ms-1">Hari Ini</span>
                                        @elseif($schedule['is_tomorrow'])
                                            <span class="badge bg-info ms-1">Besok</span>
                                        @endif
                                    </p>
                                    <small class="text-success">{{ $schedule['time'] }}</small>
                                </div>
                                @if ($schedule['type'] == 'kompetisi')
                                    <span class="badge bg-warning">Kompetisi</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x text-muted fs-2"></i>
                                <p class="text-muted mt-2 mb-0">Tidak ada jadwal mendatang</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Ringkasan Kehadiran -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-bar-chart text-success me-2"></i>Ringkasan Kehadiran
                        </h6>
                        <a href="{{ route('siswa.kehadiran') }}" class="btn btn-outline-success btn-sm">
                            Detail
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Progress Circle -->
                        <div class="text-center mb-3">
                            <div class="position-relative d-inline-block">
                                <svg width="100" height="100" class="circular-progress">
                                    <circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.1)"
                                        stroke-width="8" fill="none" />
                                    <circle cx="50" cy="50" r="40" stroke="#28a745" stroke-width="8"
                                        fill="none" stroke-dasharray="{{ 2 * 3.14159 * 40 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 40 * (1 - $attendancePercentage / 100) }}"
                                        transform="rotate(-90 50 50)" class="progress-ring" />
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h6 class="mb-0 text-success">{{ $attendancePercentage }}%</h6>
                                    <small class="text-muted">Hadir</small>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="row g-2 text-center">
                            <div class="col-3">
                                <div class="bg-primary rounded p-2">
                                    <strong class="text-white d-block">{{ $attendanceStats['total_kegiatan'] }}</strong>
                                    <small class="text-white">Total</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="bg-success rounded p-2">
                                    <strong class="text-white d-block">{{ $attendanceStats['hadir'] }}</strong>
                                    <small class="text-white">Hadir</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="bg-warning rounded p-2">
                                    <strong class="text-white d-block">{{ $attendanceStats['izin'] }}</strong>
                                    <small class="text-white">Izin</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="bg-danger rounded p-2">
                                    <strong class="text-white d-block">{{ $attendanceStats['alpa'] }}</strong>
                                    <small class="text-white">Alpa</small>
                                </div>
                            </div>
                        </div>

                        <!-- Achievement Badge -->
                        @if ($attendancePercentage >= 80)
                            <div class="alert alert-success mt-3 py-2">
                                <i class="bi bi-trophy me-2"></i>
                                <small><strong>Target Tercapai!</strong><br>Anda memenuhi syarat sertifikat
                                    keaktifan.</small>
                            </div>
                        @else
                            <div class="alert alert-warning mt-3 py-2">
                                <i class="bi bi-target me-2"></i>
                                <small><strong>Tingkatkan Kehadiran!</strong><br>Target: 80% untuk sertifikat
                                    keaktifan.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Rekomendasi Section -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-stars text-warning me-2"></i>Rekomendasi Ekstrakurikuler
                            </h5>
                            <small class="text-muted">Berdasarkan minat, nilai, dan jadwal yang cocok</small>
                        </div>
                        <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @if (isset($rekomendasis) && $rekomendasis->count() > 0)
                            <div class="row g-3">
                                @foreach ($rekomendasis as $rekomendasi)
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0"
                                            style="background: linear-gradient(135deg, rgba(32, 178, 170, 0.1) 0%, rgba(32, 178, 170, 0.2) 100%);">
                                            <div class="card-body text-center">
                                                <div class="mb-3">
                                                    @if ($rekomendasi->ekstrakurikuler->gambar)
                                                        <img src="{{ Storage::url($rekomendasi->ekstrakurikuler->gambar) }}"
                                                            alt="{{ $rekomendasi->ekstrakurikuler->nama }}"
                                                            class="rounded-circle" width="60" height="60"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                                            style="width: 60px; height: 60px;">
                                                            <i class="bi bi-collection text-white fs-4"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <h6 class="card-title">{{ $rekomendasi->ekstrakurikuler->nama }}</h6>
                                                <div class="mb-2">
                                                    <span
                                                        class="badge bg-success">{{ number_format($rekomendasi->total_skor, 1) }}%
                                                        Match</span>
                                                </div>
                                                <p class="card-text small text-muted">{{ $rekomendasi->alasan }}</p>
                                                <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">
                                    Lengkapi profil Anda terlebih dahulu untuk mendapatkan rekomendasi yang akurat
                                </p>
                                <a href="{{ route('siswa.profil') }}" class="btn btn-primary">
                                    <i class="bi bi-person-gear me-1"></i>Lengkapi Profil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .stats-card {
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .circular-progress {
            transform: rotate(-90deg);
        }

        .progress-ring {
            transition: stroke-dashoffset 1.5s ease-in-out;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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

        .row>.col-md-6,
        .row>.col-xl-3,
        .row>.col-xl-8,
        .row>.col-xl-4,
        .row>.col-xl-12 {
            animation: fadeInUp 0.6s ease-out;
        }

        .row>.col-xl-4 {
            animation-delay: 0.2s;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Animate progress ring on load
        window.addEventListener('load', function() {
            const progressRing = document.querySelector('.progress-ring');
            if (progressRing) {
                progressRing.style.transition = 'stroke-dashoffset 1.5s ease-in-out';
            }
        });

        // Auto refresh notifications every 30 seconds
        setInterval(function() {
            // You can implement AJAX call to get latest notifications here
            console.log('Checking for new notifications...');
        }, 30000);

        // Card hover animations
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (!this.classList.contains('stats-card')) {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
                }
            });

            card.addEventListener('mouseleave', function() {
                if (!this.classList.contains('stats-card')) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                }
            });
        });

        // Quick action shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + J for Jadwal
            if (e.ctrlKey && e.key === 'j') {
                e.preventDefault();
                @if ($ekstrakurikuler)
                    window.location.href = '{{ route('siswa.jadwal') }}';
                @endif
            }

            // Ctrl + K for Kehadiran
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                @if ($ekstrakurikuler)
                    window.location.href = '{{ route('siswa.kehadiran') }}';
                @endif
            }
        });

        // Show tooltips for keyboard shortcuts
        document.addEventListener('DOMContentLoaded', function() {
            @if ($ekstrakurikuler)
                // Add tooltip to jadwal button
                const jadwalBtn = document.querySelector('a[href="{{ route('siswa.jadwal') }}"]');
                if (jadwalBtn) {
                    jadwalBtn.setAttribute('title', 'Shortcut: Ctrl + J');
                }

                // Add tooltip to kehadiran button
                const kehadiranBtn = document.querySelector('a[href="{{ route('siswa.kehadiran') }}"]');
                if (kehadiranBtn) {
                    kehadiranBtn.setAttribute('title', 'Shortcut: Ctrl + K');
                }
            @endif
        });
    </script>
@endpush
