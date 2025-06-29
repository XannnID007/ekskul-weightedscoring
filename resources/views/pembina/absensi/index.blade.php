@extends('layouts.app')

@section('title', 'Input Kehadiran')
@section('page-title', 'Input Kehadiran Digital')
@section('page-description', 'Kelola kehadiran siswa pada ekstrakurikuler yang Anda bina')

@section('content')
    <!-- Date Selector -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="bi bi-calendar-event text-primary me-2"></i>Pilih Tanggal Kegiatan
                            </h5>
                            <p class="text-muted mb-0">Pilih tanggal untuk input kehadiran siswa</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input type="date" class="form-control" id="tanggalAbsensi"
                                        value="{{ $today->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary w-100" onclick="loadAbsensiData()">
                                        <i class="bi bi-search me-1"></i>Muat Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <h2 class="mb-0" id="totalSiswa">{{ $pendaftarans->count() }}</h2>
                            <small class="opacity-75">Yang terdaftar</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Hadir</h6>
                            <h2 class="mb-0" id="totalHadir">{{ $todayAttendance->where('status', 'hadir')->count() }}
                            </h2>
                            <small class="opacity-75">Hari ini</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Izin/Terlambat</h6>
                            <h2 class="mb-0" id="totalIzin">
                                {{ $todayAttendance->whereIn('status', ['izin', 'terlambat'])->count() }}</h2>
                            <small class="opacity-75">Hari ini</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Alpa</h6>
                            <h2 class="mb-0" id="totalAlpa">{{ $todayAttendance->where('status', 'alpa')->count() }}</h2>
                            <small class="opacity-75">Hari ini</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Form -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check text-primary me-2"></i>Daftar Kehadiran
                    <span class="badge bg-primary ms-2" id="selectedDate">{{ $today->format('d M Y') }}</span>
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="markAllPresent()" id="markAllBtn">
                        <i class="bi bi-check-all me-1"></i>Tandai Semua Hadir
                    </button>
                    <button class="btn btn-success" onclick="saveAllAttendance()" id="saveBtn">
                        <i class="bi bi-save me-1"></i>Simpan Semua
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($pendaftarans->count() > 0)
                <form id="absensiForm">
                    @csrf
                    <input type="hidden" name="tanggal" id="hiddenTanggal" value="{{ $today->format('Y-m-d') }}">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Siswa</th>
                                    <th width="20%">Ekstrakurikuler</th>
                                    <th width="15%">Status Kehadiran</th>
                                    <th width="25%">Catatan</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="absensiTableBody">
                                @foreach ($pendaftarans->groupBy('ekstrakurikuler.nama') as $ekskulName => $pendaftaranGroup)
                                    <tr class="table-secondary">
                                        <td colspan="6" class="fw-bold">
                                            <i class="bi bi-collection me-2"></i>{{ $ekskulName }}
                                            <span class="badge bg-primary ms-2">{{ $pendaftaranGroup->count() }}
                                                siswa</span>
                                        </td>
                                    </tr>
                                    @foreach ($pendaftaranGroup as $index => $pendaftaran)
                                        @php
                                            $attendance = $todayAttendance->get($pendaftaran->id);
                                        @endphp
                                        <tr data-pendaftaran-id="{{ $pendaftaran->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar-sm bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                        @if ($pendaftaran->user->jenis_kelamin === 'P')
                                                            <i class="bi bi-person-dress text-white"></i>
                                                        @else
                                                            <i class="bi bi-person text-white"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $pendaftaran->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $pendaftaran->user->nis ?: 'NIS belum diisi' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-secondary">{{ $pendaftaran->ekstrakurikuler->nama }}</span>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm attendance-status"
                                                    name="attendance[{{ $pendaftaran->id }}][status]"
                                                    data-pendaftaran-id="{{ $pendaftaran->id }}">
                                                    <option value="">- Pilih Status -</option>
                                                    <option value="hadir"
                                                        {{ $attendance && $attendance->status === 'hadir' ? 'selected' : '' }}>
                                                        <i class="bi bi-check-circle"></i> Hadir
                                                    </option>
                                                    <option value="izin"
                                                        {{ $attendance && $attendance->status === 'izin' ? 'selected' : '' }}>
                                                        <i class="bi bi-info-circle"></i> Izin
                                                    </option>
                                                    <option value="terlambat"
                                                        {{ $attendance && $attendance->status === 'terlambat' ? 'selected' : '' }}>
                                                        <i class="bi bi-clock"></i> Terlambat
                                                    </option>
                                                    <option value="alpa"
                                                        {{ $attendance && $attendance->status === 'alpa' ? 'selected' : '' }}>
                                                        <i class="bi bi-x-circle"></i> Alpa
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="attendance[{{ $pendaftaran->id }}][catatan]"
                                                    placeholder="Catatan (opsional)"
                                                    value="{{ $attendance->catatan ?? '' }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="saveIndividualAttendance({{ $pendaftaran->id }})">
                                                    <i class="bi bi-save"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Belum ada siswa terdaftar</p>
                    <p class="text-muted mb-0">Siswa akan muncul di sini setelah pendaftaran disetujui</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize date change handler
            $('#tanggalAbsensi').change(function() {
                updateSelectedDate();
            });

            // Update stats when attendance status changes
            $('.attendance-status').change(function() {
                updateStats();
            });
        });

        function loadAbsensiData() {
            const selectedDate = $('#tanggalAbsensi').val();
            if (!selectedDate) {
                showError('Pilih tanggal terlebih dahulu');
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Memuat Data...',
                html: 'Sedang memuat data kehadiran',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Reload page with selected date
            const url = new URL(window.location.href);
            url.searchParams.set('date', selectedDate);
            window.location.href = url.toString();
        }

        function updateSelectedDate() {
            const selectedDate = $('#tanggalAbsensi').val();
            if (selectedDate) {
                const formattedDate = new Date(selectedDate).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
                $('#selectedDate').text(formattedDate);
                $('#hiddenTanggal').val(selectedDate);
            }
        }

        function markAllPresent() {
            Swal.fire({
                title: 'Tandai Semua Hadir?',
                text: 'Semua siswa akan ditandai sebagai hadir',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tandai Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.attendance-status').val('hadir');
                    updateStats();
                    showSuccess('Semua siswa berhasil ditandai hadir');
                }
            });
        }

        function saveAllAttendance() {
            const attendanceData = [];
            const tanggal = $('#hiddenTanggal').val();

            $('.attendance-status').each(function() {
                const pendaftaranId = $(this).data('pendaftaran-id');
                const status = $(this).val();
                const catatan = $(`input[name="attendance[${pendaftaranId}][catatan]"]`).val();

                if (status) {
                    attendanceData.push({
                        pendaftaran_id: pendaftaranId,
                        status: status,
                        catatan: catatan
                    });
                }
            });

            if (attendanceData.length === 0) {
                showError('Pilih status kehadiran untuk minimal satu siswa');
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                html: `Menyimpan ${attendanceData.length} data kehadiran`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            function saveIndividualAttendance(pendaftaranId) {
                const status = $(`.attendance-status[data-pendaftaran-id="${pendaftaranId}"]`).val();
                const catatan = $(`input[name="attendance[${pendaftaranId}][catatan]"]`).val();
                const tanggal = $('#hiddenTanggal').val();

                if (!status) {
                    showError('Pilih status kehadiran terlebih dahulu');
                    return;
                }

                // Show loading
                const btn = $(`.btn[onclick="saveIndividualAttendance(${pendaftaranId})"]`);
                const originalText = btn.html();
                btn.html('<i class="bi bi-hourglass-split"></i>').prop('disabled', true);

                fetch('{{ route('pembina.absensi.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pendaftaran_id: pendaftaranId,
                            tanggal: tanggal,
                            status: status,
                            catatan: catatan
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            btn.html('<i class="bi bi-check text-success"></i>');
                            setTimeout(() => {
                                btn.html(originalText).prop('disabled', false);
                            }, 2000);
                            updateStats();
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        btn.html(originalText).prop('disabled', false);
                        showError(error.message || 'Terjadi kesalahan saat menyimpan data');
                    });
            }

            function updateStats() {
                const totalSiswa = $('.attendance-status').length;
                const totalHadir = $('.attendance-status[value="hadir"]').length;
                const totalIzin = $('.attendance-status').filter(function() {
                    return $(this).val() === 'izin' || $(this).val() === 'terlambat';
                }).length;
                const totalAlpa = $('.attendance-status[value="alpa"]').length;

                $('#totalSiswa').text(totalSiswa);
                $('#totalHadir').text(totalHadir);
                $('#totalIzin').text(totalIzin);
                $('#totalAlpa').text(totalAlpa);
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + S to save all
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    saveAllAttendance();
                }

                // Ctrl + A to mark all present
                if (e.ctrlKey && e.key === 'a') {
                    e.preventDefault();
                    markAllPresent();
                }
            });
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
            width: 40px;
            height: 40px;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .attendance-status:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .table tbody tr:hover {
            background-color: rgba(32, 178, 170, 0.1);
        }

        .table-secondary {
            background-color: rgba(108, 117, 125, 0.2) !important;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.4em 0.6em;
        }

        /* Loading animation for buttons */
        .btn:disabled {
            opacity: 0.7;
        }

        /* Responsive table improvements */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endpush
