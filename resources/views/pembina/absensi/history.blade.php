@extends('layouts.app')

@section('title', 'Riwayat Absensi')
@section('page-title', 'Riwayat Absensi')
@section('page-description', 'Lihat riwayat kehadiran siswa ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.absensi.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Input
        </a>
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#filterModal">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
        <a href="{{ route('pembina.absensi.report') }}" class="btn btn-light">
            <i class="bi bi-download me-1"></i>Export Laporan
        </a>
    </div>
@endsection

@section('content')
    <!-- Filter Summary -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>Menampilkan Data Kehadiran
                    </h6>
                    <p class="text-muted mb-0 mt-1">
                        Total {{ $absensis->total() }} record dari semua ekstrakurikuler yang Anda bina
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        {{ $absensis->total() }} Records
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Kehadiran
                </h5>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="viewMode" id="tableView" autocomplete="off" checked>
                        <label class="btn btn-outline-primary btn-sm" for="tableView">
                            <i class="bi bi-table"></i> Tabel
                        </label>

                        <input type="radio" class="btn-check" name="viewMode" id="calendarView" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm" for="calendarView">
                            <i class="bi bi-calendar"></i> Kalender
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($absensis->count() > 0)
                <!-- Table View -->
                <div id="tableViewContainer">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Siswa</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Dicatat Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensis as $absensi)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <strong>{{ $absensi->tanggal->format('d M Y') }}</strong>
                                                <small class="text-muted">{{ $absensi->tanggal->format('l') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    @if ($absensi->pendaftaran->user->jenis_kelamin === 'P')
                                                        <i class="bi bi-person-dress text-white"></i>
                                                    @else
                                                        <i class="bi bi-person text-white"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $absensi->pendaftaran->user->name }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $absensi->pendaftaran->user->nis ?: 'NIS belum diisi' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary">{{ $absensi->pendaftaran->ekstrakurikuler->nama }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'hadir' => 'success',
                                                    'izin' => 'warning',
                                                    'terlambat' => 'info',
                                                    'alpa' => 'danger',
                                                ];
                                                $statusIcons = [
                                                    'hadir' => 'bi-check-circle',
                                                    'izin' => 'bi-info-circle',
                                                    'terlambat' => 'bi-clock',
                                                    'alpa' => 'bi-x-circle',
                                                ];
                                                $color = $statusColors[$absensi->status] ?? 'secondary';
                                                $icon = $statusIcons[$absensi->status] ?? 'bi-question-circle';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">
                                                <i class="bi {{ $icon }} me-1"></i>{{ ucfirst($absensi->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($absensi->catatan)
                                                <span class="text-muted">{{ Str::limit($absensi->catatan, 30) }}</span>
                                                @if (strlen($absensi->catatan) > 30)
                                                    <button class="btn btn-link btn-sm p-0 ms-1"
                                                        onclick="showFullNote('{{ addslashes($absensi->catatan) }}')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($absensi->pencatat)
                                                <small class="text-muted">{{ $absensi->pencatat->name }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm"
                                                    onclick="editAbsensi({{ $absensi->id }})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-info btn-sm"
                                                    onclick="viewDetail({{ $absensi->id }})">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Calendar View -->
                <div id="calendarViewContainer" class="d-none">
                    <div id="calendar"></div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $absensis->firstItem() }}-{{ $absensis->lastItem() }}
                        dari {{ $absensis->total() }} record kehadiran
                    </div>
                    {{ $absensis->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock-history text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Belum ada riwayat kehadiran</p>
                    <p class="text-muted mb-4">Data kehadiran akan muncul setelah Anda melakukan input absensi</p>
                    <a href="{{ route('pembina.absensi.index') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Mulai Input Kehadiran
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Riwayat Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Ekstrakurikuler</label>
                        <select class="form-select" name="ekstrakurikuler">
                            <option value="">Semua Ekstrakurikuler</option>
                            @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kehadiran</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-outline-secondary" onclick="resetFilter()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="applyFilter()">Terapkan Filter</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Absensi Modal -->
<div class="modal fade" id="editAbsensiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kehadiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editAbsensiForm">
                    <input type="hidden" id="editAbsensiId">

                    <div class="mb-3">
                        <label class="form-label">Siswa</label>
                        <input type="text" class="form-control" id="editSiswaName" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="editTanggal" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kehadiran</label>
                        <select class="form-select" id="editStatus" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="editCatatan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateAbsensi()">
                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- FullCalendar CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        let calendar;

        $(document).ready(function() {
            // View mode toggle
            $('input[name="viewMode"]').change(function() {
                if ($(this).attr('id') === 'tableView') {
                    $('#tableViewContainer').removeClass('d-none');
                    $('#calendarViewContainer').addClass('d-none');
                } else {
                    $('#tableViewContainer').addClass('d-none');
                    $('#calendarViewContainer').removeClass('d-none');
                    initializeCalendar();
                }
            });
        });

        function initializeCalendar() {
            if (calendar) {
                calendar.destroy();
            }

            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                locale: 'id',
                events: function(info, successCallback, failureCallback) {
                    fetch(
                            `{{ route('pembina.absensi.index') }}?calendar=1&start=${info.startStr}&end=${info.endStr}`)
                        .then(response => response.json())
                        .then(data => {
                            const events = data.map(absensi => ({
                                id: absensi.id,
                                title: `${absensi.user_name} - ${absensi.status}`,
                                date: absensi.tanggal,
                                backgroundColor: getStatusColor(absensi.status),
                                borderColor: getStatusColor(absensi.status),
                                extendedProps: {
                                    siswa: absensi.user_name,
                                    ekstrakurikuler: absensi.ekstrakurikuler_name,
                                    status: absensi.status,
                                    catatan: absensi.catatan
                                }
                            }));
                            successCallback(events);
                        })
                        .catch(error => failureCallback(error));
                },
                eventClick: function(info) {
                    showEventDetail(info.event);
                }
            });

            calendar.render();
        }

        function getStatusColor(status) {
            const colors = {
                'hadir': '#28a745',
                'izin': '#ffc107',
                'terlambat': '#17a2b8',
                'alpa': '#dc3545'
            };
            return colors[status] || '#6c757d';
        }

        function showEventDetail(event) {
            const props = event.extendedProps;
            Swal.fire({
                title: 'Detail Kehadiran',
                html: `
                    <div class="text-start">
                        <p><strong>Siswa:</strong> ${props.siswa}</p>
                        <p><strong>Ekstrakurikuler:</strong> ${props.ekstrakurikuler}</p>
                        <p><strong>Tanggal:</strong> ${event.startStr}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-${getStatusBadge(props.status)}">${props.status}</span>
                        </p>
                        ${props.catatan ? `<p><strong>Catatan:</strong> ${props.catatan}</p>` : ''}
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Edit',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#007bff'
            }).then((result) => {
                if (result.isConfirmed) {
                    editAbsensi(event.id);
                }
            });
        }

        function getStatusBadge(status) {
            const badges = {
                'hadir': 'success',
                'izin': 'warning',
                'terlambat': 'info',
                'alpa': 'danger'
            };
            return badges[status] || 'secondary';
        }

        function showFullNote(note) {
            Swal.fire({
                title: 'Catatan Lengkap',
                text: note,
                icon: 'info',
                confirmButtonText: 'Tutup'
            });
        }

        function editAbsensi(id) {
            // Show loading
            Swal.fire({
                title: 'Memuat Data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Fetch absensi data
            fetch(`/pembina/absensi/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    // Populate form
                    $('#editAbsensiId').val(data.id);
                    $('#editSiswaName').val(data.user_name);
                    $('#editTanggal').val(data.tanggal);
                    $('#editStatus').val(data.status);
                    $('#editCatatan').val(data.catatan || '');

                    // Show modal
                    $('#editAbsensiModal').modal('show');
                })
                .catch(error => {
                    Swal.close();
                    showError('Gagal memuat data kehadiran');
                });
        }

        function updateAbsensi() {
            const id = $('#editAbsensiId').val();
            const status = $('#editStatus').val();
            const catatan = $('#editCatatan').val();

            if (!status) {
                showError('Status kehadiran harus dipilih');
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/pembina/absensi/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        status: status,
                        catatan: catatan
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data kehadiran berhasil diperbarui!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#editAbsensiModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                });
        }

        function viewDetail(id) {
            // Implementation for view detail
            editAbsensi(id);
        }

        function applyFilter() {
            const formData = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams(formData);
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        function resetFilter() {
            document.getElementById('filterForm').reset();
            window.location.href = window.location.pathname;
        }

        // Auto refresh every 5 minutes
        setInterval(function() {
            if (!document.hidden && $('#tableView').is(':checked')) {
                location.reload();
            }
        }, 300000);
    </script>
@endpush

@push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .avatar-sm {
            width: 35px;
            height: 35px;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.4em 0.6em;
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        /* Calendar styling */
        .fc {
            font-family: inherit;
        }

        .fc-event {
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .fc-daygrid-event {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Modal improvements */
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .fc-toolbar {
                flex-direction: column;
                gap: 0.5rem;
            }

            .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
