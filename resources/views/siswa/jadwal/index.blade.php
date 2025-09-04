@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')
@section('page-title', 'Jadwal Kegiatan')
@section('page-description', 'Jadwal kegiatan ekstrakurikuler Anda')

@section('content')
    @if (isset($ekstrakurikuler) && $ekstrakurikuler)
        <div class="row g-4">
            <!-- Info Ekstrakurikuler -->
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
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">PEMBINA</small>
                                        <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">JADWAL RUTIN</small>
                                        <strong>{{ $ekstrakurikuler->jadwal_string ?? 'Belum ditentukan' }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">PESERTA</small>
                                        <strong>{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">STATUS</small>
                                        <span class="badge bg-success">Aktif Mengikuti</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kalender dan Info -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar3 text-primary me-2"></i>Kalender Kegiatan
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm active" id="monthView">
                                Bulan
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="weekView">
                                Minggu
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <!-- Kegiatan Mendatang -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>Kegiatan Mendatang
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (isset($upcomingActivities) && count($upcomingActivities) > 0)
                            @foreach ($upcomingActivities as $activity)
                                <div class="info-list-item">
                                    <div
                                        class="icon-wrapper bg-{{ $activity['type'] == 'kompetisi' ? 'warning' : 'primary' }}-subtle text-{{ $activity['type'] == 'kompetisi' ? 'warning' : 'primary' }}-emphasis">
                                        <i
                                            class="bi bi-{{ $activity['type'] == 'kompetisi' ? 'trophy' : 'calendar-event' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $activity['title'] }}</h6>
                                        <p class="text-muted small mb-1">
                                            {{ $activity['date']->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                            @if ($activity['is_today'])
                                                <span class="badge bg-success ms-1">Hari Ini</span>
                                            @elseif($activity['is_tomorrow'])
                                                <span class="badge bg-info ms-1">Besok</span>
                                            @endif
                                        </p>
                                        <small class="text-success">
                                            <i class="bi bi-clock me-1"></i>{{ $activity['time'] }}
                                        </small>
                                        @if ($activity['type'] == 'kompetisi')
                                            <br><small class="text-warning">
                                                <i class="bi bi-star me-1"></i>{{ $activity['description'] }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-calendar-x fs-1"></i>
                                <p class="mt-2 mb-0">Tidak ada kegiatan mendatang</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pengumuman Terbaru -->
                @if (isset($announcements) && $announcements && $announcements->count() > 0)
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-megaphone text-warning me-2"></i>Pengumuman Terbaru
                            </h6>
                            <a href="{{ route('siswa.pengumuman.index') }}" class="btn btn-outline-primary btn-sm">
                                Lihat Semua
                            </a>
                        </div>
                        <div class="card-body">
                            @foreach ($announcements->take(3) as $announcement)
                                <div class="info-list-item">
                                    <div
                                        class="icon-wrapper {{ $announcement->is_penting ? 'bg-danger-subtle text-danger-emphasis' : 'bg-info-subtle text-info-emphasis' }}">
                                        <i
                                            class="bi bi-{{ $announcement->is_penting ? 'exclamation-triangle' : 'info-circle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('siswa.pengumuman.show', $announcement) }}"
                                                class="text-decoration-none">
                                                {{ $announcement->judul }}
                                            </a>
                                            @if ($announcement->is_penting)
                                                <span class="badge bg-danger ms-1">Penting</span>
                                            @endif
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            {{ $announcement->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal untuk Detail Event -->
        <div class="modal fade" id="eventModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalTitle">Detail Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="eventModalBody">
                        <!-- Content akan diisi via JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @else
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
                    eventSources: [{
                        url: '/api/siswa/jadwal/events',
                        method: 'GET',
                        extraParams: function() {
                            return {
                                '_token': '{{ csrf_token() }}'
                            };
                        },
                        failure: function(error) {
                            console.error('Error loading events:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memuat jadwal kegiatan.'
                            });
                        }
                    }],
                    eventClick: function(info) {
                        showEventDetail(info.event);
                    },
                    eventDidMount: function(info) {
                        info.el.style.cursor = 'pointer';

                        // Add tooltip
                        info.el.setAttribute('title', info.event.title);

                        // Add hover effect
                        info.el.addEventListener('mouseenter', function() {
                            this.style.transform = 'scale(1.02)';
                            this.style.transition = 'transform 0.2s ease';
                        });

                        info.el.addEventListener('mouseleave', function() {
                            this.style.transform = 'scale(1)';
                        });
                    },
                    loading: function(bool) {
                        if (bool) {
                            // Show loading indicator
                            calendarEl.style.opacity = '0.6';
                        } else {
                            calendarEl.style.opacity = '1';
                        }
                    }
                });

                calendar.render();
            }

            // View toggle buttons
            const monthViewBtn = document.getElementById('monthView');
            const weekViewBtn = document.getElementById('weekView');

            if (monthViewBtn) {
                monthViewBtn.addEventListener('click', function() {
                    calendar.changeView('dayGridMonth');
                    setActiveButton(this);
                });
            }

            if (weekViewBtn) {
                weekViewBtn.addEventListener('click', function() {
                    calendar.changeView('timeGridWeek');
                    setActiveButton(this);
                });
            }

            function setActiveButton(activeBtn) {
                document.querySelectorAll('.btn-group .btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                activeBtn.classList.add('active');
            }
        });

        function showEventDetail(event) {
            const modal = new bootstrap.Modal(document.getElementById('eventModal'));
            const modalTitle = document.getElementById('eventModalTitle');
            const modalBody = document.getElementById('eventModalBody');

            modalTitle.textContent = event.title;

            const extendedProps = event.extendedProps || {};
            const startTime = event.start ? event.start.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Tidak ditentukan';

            const endTime = event.end ? event.end.toLocaleString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Tidak ditentukan';

            let modalContent = `
        <div class="mb-3">
            <h6><i class="bi bi-calendar3 text-primary me-2"></i>Waktu</h6>
            <p class="mb-1"><strong>Mulai:</strong> ${startTime}</p>
            ${event.end ? `<p class="mb-0"><strong>Selesai:</strong> ${endTime}</p>` : ''}
        </div>
    `;

            if (extendedProps.pembina) {
                modalContent += `
            <div class="mb-3">
                <h6><i class="bi bi-person-check text-success me-2"></i>Pembina</h6>
                <p class="mb-0">${extendedProps.pembina}</p>
            </div>
        `;
            }

            if (extendedProps.description) {
                modalContent += `
            <div class="mb-3">
                <h6><i class="bi bi-info-circle text-info me-2"></i>Deskripsi</h6>
                <p class="mb-0">${extendedProps.description}</p>
            </div>
        `;
            }

            if (extendedProps.lokasi) {
                modalContent += `
            <div class="mb-3">
                <h6><i class="bi bi-geo-alt text-warning me-2"></i>Lokasi</h6>
                <p class="mb-0">${extendedProps.lokasi}</p>
            </div>
        `;
            }

            if (extendedProps.type) {
                const typeLabels = {
                    'regular': 'Kegiatan Rutin',
                    'announcement': 'Pengumuman Khusus',
                    'kompetisi': 'Kompetisi'
                };

                const typeColors = {
                    'regular': 'primary',
                    'announcement': 'warning',
                    'kompetisi': 'success'
                };

                const typeLabel = typeLabels[extendedProps.type] || 'Lainnya';
                const typeColor = typeColors[extendedProps.type] || 'secondary';

                modalContent += `
            <div class="mb-3">
                <h6><i class="bi bi-tag text-secondary me-2"></i>Jenis Kegiatan</h6>
                <span class="badge bg-${typeColor}">${typeLabel}</span>
            </div>
        `;
            }

            modalBody.innerHTML = modalContent;
            modal.show();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'j') {
                e.preventDefault();
                // Focus on calendar
                document.getElementById('calendar')?.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });

        // Auto refresh calendar setiap 5 menit
        setInterval(function() {
            if (calendar) {
                calendar.refetchEvents();
            }
        }, 300000); // 5 minutes
    </script>
@endpush

@push('styles')
    <style>
        /* Info List di sidebar kanan */
        .info-list-item {
            display: flex;
            align-items: flex-start;
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
            font-size: 1.1rem;
        }

        /* Kustomisasi FullCalendar */
        .fc-theme-bootstrap5 .fc-button-primary {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .fc-theme-bootstrap5 .fc-button-primary:hover {
            background-color: var(--bs-primary-dark) !important;
            border-color: var(--bs-primary-dark) !important;
        }

        .fc-theme-bootstrap5 .fc-button-primary:disabled {
            background-color: var(--bs-gray-400) !important;
            border-color: var(--bs-gray-400) !important;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(60, 154, 231, 0.1) !important;
        }

        .fc-event {
            border-radius: 6px !important;
            border: none !important;
            padding: 2px 4px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .fc-event-title {
            font-weight: 600;
        }

        .fc-event-time {
            font-weight: 400;
            opacity: 0.9;
        }

        /* Card hover effects */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Loading state */
        .fc-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Badge improvements */
        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.25em 0.5em;
        }

        /* Responsive calendar */
        @media (max-width: 768px) {
            .fc-toolbar {
                flex-direction: column;
                gap: 0.5rem;
            }

            .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
            }

            .fc-button-group {
                display: flex;
                gap: 0.25rem;
            }

            .fc-event {
                font-size: 0.75rem;
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

        .row>[class*="col-"] {
            animation: fadeInUp 0.6s ease-out;
        }

        .row>.col-xl-4 {
            animation-delay: 0.1s;
        }

        /* Button group active state */
        .btn-group .btn.active {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
        }
    </style>
@endpush
