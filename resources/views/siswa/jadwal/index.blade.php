@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')
@section('page-title', 'Jadwal Kegiatan')
@section('page-description', 'Jadwal kegiatan ekstrakurikuler Anda')

@section('content')
    @php
        $ekstrakurikuler = auth()->user()->ekstrakurikulers()->wherePivot('status', 'disetujui')->first();
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
                                        class="rounded-3" width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="bg-white bg-opacity-20 rounded-3 d-inline-flex align-items-center justify-content-center"
                                        style="width: 120px; height: 120px;">
                                        <i class="bi bi-collection text-white fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h3 class="mb-2">{{ $ekstrakurikuler->nama }}</h3>
                                <p class="mb-3 opacity-90">{{ $ekstrakurikuler->deskripsi }}</p>
                                <div class="d-flex gap-3">
                                    <div>
                                        <small class="opacity-75 d-block">Pembina</small>
                                        <strong>{{ $ekstrakurikuler->pembina->name }}</strong>
                                    </div>
                                    <div>
                                        <small class="opacity-75 d-block">Jadwal Rutin</small>
                                        <strong>{{ $ekstrakurikuler->jadwal_string }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                    <i class="bi bi-calendar-event" style="font-size: 2rem;"></i>
                                    <div class="mt-2">
                                        <strong class="d-block">Hari Ini</strong>
                                        <small>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</small>
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
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="changeView('month')">Bulan</button>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="changeView('week')">Minggu</button>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="changeView('day')">Hari</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-clock text-warning me-2"></i>Kegiatan Mendatang
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $upcomingEvents = [
                                [
                                    'title' => 'Latihan Rutin',
                                    'date' => 'Senin, 1 Jul 2024',
                                    'time' => '15:30 - 17:00',
                                    'type' => 'rutin',
                                ],
                                [
                                    'title' => 'Pertandingan Antar Sekolah',
                                    'date' => 'Sabtu, 6 Jul 2024',
                                    'time' => '08:00 - 12:00',
                                    'type' => 'kompetisi',
                                ],
                                [
                                    'title' => 'Latihan Rutin',
                                    'date' => 'Senin, 8 Jul 2024',
                                    'time' => '15:30 - 17:00',
                                    'type' => 'rutin',
                                ],
                            ];
                        @endphp

                        @foreach ($upcomingEvents as $event)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                <div
                                    class="bg-{{ $event['type'] == 'kompetisi' ? 'warning' : 'primary' }} rounded-circle p-2 me-3">
                                    <i
                                        class="bi bi-{{ $event['type'] == 'kompetisi' ? 'trophy' : 'calendar-event' }} text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $event['title'] }}</h6>
                                    <p class="text-muted small mb-1">{{ $event['date'] }}</p>
                                    <small class="text-success">{{ $event['time'] }}</small>
                                </div>
                                @if ($event['type'] == 'kompetisi')
                                    <span class="badge bg-warning">Kompetisi</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Statistik Kehadiran</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $attendanceStats = [
                                'total_kegiatan' => 12,
                                'hadir' => 10,
                                'izin' => 1,
                                'alpa' => 1,
                            ];
                            $percentage = round(($attendanceStats['hadir'] / $attendanceStats['total_kegiatan']) * 100);
                        @endphp

                        <div class="text-center mb-3">
                            <div class="progress mx-auto" style="width: 120px; height: 120px;">
                                <svg width="120" height="120">
                                    <circle cx="60" cy="60" r="50" stroke="#e9ecef" stroke-width="10"
                                        fill="none" />
                                    <circle cx="60" cy="60" r="50" stroke="#28a745" stroke-width="10"
                                        fill="none" stroke-dasharray="{{ 2 * 3.14159 * 50 }}"
                                        stroke-dashoffset="{{ 2 * 3.14159 * 50 * (1 - $percentage / 100) }}"
                                        transform="rotate(-90 60 60)" />
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h4 class="mb-0">{{ $percentage }}%</h4>
                                    <small class="text-muted">Kehadiran</small>
                                </div>
                            </div>
                        </div>

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
                        <h4 class="mt-3 mb-2">Belum Terdaftar Ekstrakurikuler</h4>
                        <p class="text-muted mb-4">
                            Anda belum terdaftar pada ekstrakurikuler apapun. Daftar terlebih dahulu untuk melihat jadwal
                            kegiatan.
                        </p>
                        <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                            <i class="bi bi-collection me-1"></i>Jelajahi Ekstrakurikuler
                        </a>
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

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                height: 'auto',
                events: [{
                        title: 'Latihan {{ $ekstrakurikuler->nama ?? '' }}',
                        daysOfWeek: ['1'], // Senin
                        startTime: '15:30',
                        endTime: '17:00',
                        backgroundColor: '#6f42c1',
                        borderColor: '#6f42c1'
                    },
                    {
                        title: 'Pertandingan Antar Sekolah',
                        start: '2024-07-06T08:00:00',
                        end: '2024-07-06T12:00:00',
                        backgroundColor: '#ffc107',
                        borderColor: '#ffc107',
                        textColor: '#000'
                    }
                ],
                eventClick: function(info) {
                    Swal.fire({
                        title: info.event.title,
                        html: `
                        <p><strong>Waktu:</strong> ${info.event.start.toLocaleString('id-ID')}</p>
                        ${info.event.end ? `<p><strong>Selesai:</strong> ${info.event.end.toLocaleString('id-ID')}</p>` : ''}
                    `,
                        icon: 'info'
                    });
                }
            });

            calendar.render();
        });

        function changeView(view) {
            const viewMap = {
                'month': 'dayGridMonth',
                'week': 'timeGridWeek',
                'day': 'timeGridDay'
            };
            calendar.changeView(viewMap[view]);
        }
    </script>
@endpush
