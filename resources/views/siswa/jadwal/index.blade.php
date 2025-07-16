@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')
@section('page-title', 'Jadwal Kegiatan')
@section('page-description', 'Jadwal kegiatan ekstrakurikuler Anda')

@section('content')
    @php
        $user = auth()->user();
        $pendaftaran = $user
            ->pendaftarans()
            ->where('status', 'disetujui')
            ->with(['ekstrakurikuler.pembina'])
            ->first();
        $ekstrakurikuler = $pendaftaran ? $pendaftaran->ekstrakurikuler : null;
    @endphp

    @if ($ekstrakurikuler)
        <div class="row g-4">
            <!-- Info Card -->
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
                                        <small class="opacity-75 d-block">Jadwal Rutin</small>
                                        <strong>{{ $ekstrakurikuler->jadwal_string ?? 'Belum ditentukan' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar3 me-2"></i>Kalender Kegiatan
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm active"
                                id="monthView">Bulan</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="weekView">Minggu</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="dayView">Hari</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events & Stats -->
            <div class="col-xl-4">
                <!-- Upcoming Events -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-clock text-warning me-2"></i>Kegiatan Mendatang
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $upcomingEvents = [
                                [
                                    'title' => $ekstrakurikuler->nama . ' - Latihan Rutin',
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

                        @forelse ($upcomingEvents as $event)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                <div
                                    class="bg-{{ $event['type'] == 'kompetisi' ? 'warning' : 'primary' }} rounded-circle p-2 me-3">
                                    <i
                                        class="bi bi-{{ $event['type'] == 'kompetisi' ? 'trophy' : 'calendar-event' }} text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $event['title'] }}</h6>
                                    <p class="text-muted small mb-1">
                                        {{ $event['date'] }}
                                        @if ($event['is_today'])
                                            <span class="badge bg-success ms-1">Hari Ini</span>
                                        @elseif($event['is_tomorrow'])
                                            <span class="badge bg-info ms-1">Besok</span>
                                        @endif
                                    </p>
                                    <small class="text-success">{{ $event['time'] }}</small>
                                </div>
                                @if ($event['type'] == 'kompetisi')
                                    <span class="badge bg-warning">Kompetisi</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x text-muted fs-2"></i>
                                <p class="text-muted mt-2 mb-0">Tidak ada kegiatan mendatang</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No Ekstrakurikuler -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-4 mb-3">Belum Terdaftar Ekstrakurikuler</h4>
                        <p class="text-muted mb-4">
                            Anda belum terdaftar pada ekstrakurikuler apapun. Daftar terlebih dahulu untuk melihat jadwal
                            kegiatan.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-primary">
                                <i class="bi bi-stars me-1"></i>Lihat Rekomendasi
                            </a>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-collection me-1"></i>Jelajahi Ekstrakurikuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>

    <script>
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            if (calendarEl) {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    height: 'auto',
                    themeSystem: 'bootstrap5',
                    events: [
                        @if ($ekstrakurikuler)
                            {
                                title: '{{ $ekstrakurikuler->nama }} - Latihan Rutin',
                                daysOfWeek: ['1'], // Senin
                                startTime: '15:30',
                                endTime: '17:00',
                                backgroundColor: '#20b2aa',
                                borderColor: '#20b2aa',
                                textColor: '#ffffff'
                            }, {
                                title: 'Pertandingan Antar Sekolah',
                                start: '2024-07-06T08:00:00',
                                end: '2024-07-06T12:00:00',
                                backgroundColor: '#ffc107',
                                borderColor: '#ffc107',
                                textColor: '#000000'
                            }
                        @endif
                    ],
                    eventClick: function(info) {
                        Swal.fire({
                            title: info.event.title,
                            html: `
                                <div class="text-start">
                                    <p><strong>Waktu:</strong> ${info.event.start ? info.event.start.toLocaleString('id-ID') : 'Tidak ditentukan'}</p>
                                    ${info.event.end ? `<p><strong>Selesai:</strong> ${info.event.end.toLocaleString('id-ID')}</p>` : ''}
                                    <p><strong>Deskripsi:</strong> Kegiatan rutin {{ $ekstrakurikuler ? $ekstrakurikuler->nama : '' }}</p>
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonColor: '#20b2aa'
                        });
                    },
                    eventDidMount: function(info) {
                        // Add hover effect
                        info.el.style.cursor = 'pointer';
                    }
                });

                calendar.render();
            }

            // View toggle buttons
            document.getElementById('monthView').addEventListener('click', function() {
                calendar.changeView('dayGridMonth');
                setActiveButton(this);
            });

            document.getElementById('weekView').addEventListener('click', function() {
                calendar.changeView('timeGridWeek');
                setActiveButton(this);
            });

            document.getElementById('dayView').addEventListener('click', function() {
                calendar.changeView('timeGridDay');
                setActiveButton(this);
            });

            function setActiveButton(activeBtn) {
                document.querySelectorAll('.btn-group .btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                activeBtn.classList.add('active');
            }
        });

        // Animate progress ring
        window.addEventListener('load', function() {
            const progressRing = document.querySelector('.progress-ring');
            if (progressRing) {
                progressRing.style.transition = 'stroke-dashoffset 1.5s ease-in-out';
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .circular-progress {
            transform: rotate(-90deg);
        }

        .progress-ring {
            transition: stroke-dashoffset 1.5s ease-in-out;
        }

        .fc-theme-bootstrap5 .fc-button-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .fc-theme-bootstrap5 .fc-button-primary:hover {
            background-color: var(--bs-primary-dark);
            border-color: var(--bs-primary-dark);
        }

        .fc-event {
            border-radius: 6px;
            padding: 2px 4px;
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

        .row>.col-12,
        .row>.col-xl-8,
        .row>.col-xl-4 {
            animation: fadeInUp 0.6s ease-out;
        }

        .row>.col-xl-4 {
            animation-delay: 0.2s;
        }
    </style>
@endpush
