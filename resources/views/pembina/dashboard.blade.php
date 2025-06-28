@extends('layouts.app')

@section('title', 'Dashboard Pembina')
@section('page-title', 'Dashboard Pembina')
@section('page-description', 'Selamat datang, ' . auth()->user()->name)

@section('content')
    <div class="row g-4">
        <!-- Welcome Card -->
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                            <p class="mb-3 opacity-90">
                                Kelola ekstrakurikuler Anda dengan efisien. Monitor pendaftaran siswa, input kehadiran, dan
                                kelola konten kegiatan.
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('pembina.pendaftaran.index') }}" class="btn btn-light">
                                    <i class="bi bi-person-plus me-1"></i>Kelola Pendaftaran
                                </a>
                                <a href="{{ route('pembina.absensi.index') }}" class="btn btn-outline-light">
                                    <i class="bi bi-calendar-check me-1"></i>Input Kehadiran
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-person-badge" style="font-size: 6rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Ekstrakurikuler</h6>
                            <h2 class="mb-0">{{ $stats['total_ekstrakurikuler'] }}</h2>
                            <small class="opacity-75">Yang dibina</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-collection"></i>
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
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <h2 class="mb-0">{{ $stats['total_siswa'] }}</h2>
                            <small class="opacity-75">Siswa aktif</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
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
                            <h6 class="card-title mb-1">Pendaftaran Pending</h6>
                            <h2 class="mb-0">{{ $stats['pendaftaran_pending'] }}</h2>
                            <small class="opacity-75">Menunggu review</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock-history"></i>
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
                            <h6 class="card-title mb-1">Total Pendaftaran</h6>
                            <h2 class="mb-0">{{ $stats['total_pendaftaran'] }}</h2>
                            <small class="opacity-75">Semua status</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ekstrakurikuler yang Dibina -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-collection text-primary me-2"></i>Ekstrakurikuler yang Dibina
                    </h5>
                    <a href="{{ route('pembina.galeri.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-images me-1"></i>Kelola Galeri
                    </a>
                </div>
                <div class="card-body">
                    @if ($ekstrakurikulers->count() > 0)
                        <div class="row g-3">
                            @foreach ($ekstrakurikulers as $ekstrakurikuler)
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    @if ($ekstrakurikuler->gambar)
                                                        <img src="{{ Storage::url($ekstrakurikuler->gambar) }}"
                                                            alt="{{ $ekstrakurikuler->nama }}" class="rounded"
                                                            width="60" height="60" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary rounded d-flex align-items-center justify-content-center"
                                                            style="width: 60px; height: 60px;">
                                                            <i class="bi bi-collection text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $ekstrakurikuler->nama }}</h6>
                                                    <p class="text-muted small mb-2">
                                                        {{ Str::limit($ekstrakurikuler->deskripsi, 80) }}</p>

                                                    <div class="row g-2 mb-2">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Peserta</small>
                                                            <strong>{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Pendaftar</small>
                                                            <strong>{{ $ekstrakurikuler->pendaftarans->count() }}
                                                                total</strong>
                                                        </div>
                                                    </div>

                                                    <div class="progress mb-2" style="height: 6px;">
                                                        <div class="progress-bar bg-success"
                                                            style="width: {{ ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 }}%">
                                                        </div>
                                                    </div>

                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('pembina.pendaftaran.index') }}?ekstrakurikuler={{ $ekstrakurikuler->id }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="bi bi-person-plus me-1"></i>Pendaftaran
                                                        </a>
                                                        <a href="{{ route('pembina.absensi.index') }}?ekstrakurikuler={{ $ekstrakurikuler->id }}"
                                                            class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-calendar-check me-1"></i>Absensi
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-collection text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Anda belum ditugaskan sebagai pembina ekstrakurikuler.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pembina.pendaftaran.index') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>Review Pendaftaran
                            @if ($stats['pendaftaran_pending'] > 0)
                                <span class="badge bg-light text-dark ms-2">{{ $stats['pendaftaran_pending'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('pembina.absensi.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-check me-2"></i>Input Kehadiran Hari Ini
                        </a>
                        <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-megaphone me-2"></i>Buat Pengumuman
                        </a>
                        <a href="{{ route('pembina.galeri.create') }}" class="btn btn-outline-info">
                            <i class="bi bi-images me-2"></i>Upload Galeri
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jadwal Hari Ini -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-calendar-event text-warning me-2"></i>Jadwal Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $hariIni = strtolower(now()->locale('id')->dayName);
                        $jadwalHariIni = $jadwal_minggu_ini->filter(function ($jadwal) use ($hariIni) {
                            return str_contains(strtolower($jadwal['jadwal']), $hariIni);
                        });
                    @endphp

                    @if ($jadwalHariIni->count() > 0)
                        @foreach ($jadwalHariIni as $jadwal)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-clock text-white"></i>
                                </div>
                                <div>
                                    <strong>{{ $jadwal['nama'] }}</strong>
                                    <br><small class="text-muted">{{ $jadwal['jadwal'] }}</small>
                                    <br><small class="text-success">{{ $jadwal['peserta'] }} siswa</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">Tidak ada jadwal hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-info me-2"></i>Aktivitas Terbaru
                    </h5>
                    <a href="{{ route('pembina.pendaftaran.index') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Siswa</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <small class="text-muted">5 menit lalu</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Ahmad Rizki</div>
                                                <small class="text-muted">2024006</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Futsal</td>
                                    <td>Pendaftaran baru</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-success" title="Setujui">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small class="text-muted">1 jam lalu</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Siti Aminah</div>
                                                <small class="text-muted">2024007</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Paduan Suara</td>
                                    <td>Kehadiran dicatat</td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Hadir
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small class="text-muted">2 jam lalu</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">Budi Santoso</div>
                                                <small class="text-muted">2024008</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Basket</td>
                                    <td>Pendaftaran disetujui</td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Disetujui
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips untuk Pembina -->
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <h6 class="mb-3">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips untuk Pembina yang Efektif
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-people text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Review Pendaftaran</h6>
                                    <small class="text-muted">Selalu baca motivasi dan harapan siswa sebelum menerima
                                        pendaftaran.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-calendar-check text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Absensi Rutin</h6>
                                    <small class="text-muted">Input kehadiran secara konsisten untuk monitoring
                                        perkembangan siswa.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-camera text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Dokumentasi Aktif</h6>
                                    <small class="text-muted">Upload foto dan video kegiatan untuk memotivasi siswa lain
                                        bergabung.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto refresh stats setiap 5 menit
        setInterval(function() {
            // Reload halaman untuk update data terbaru
            location.reload();
        }, 300000); // 5 menit

        // Animate stats cards
        document.querySelectorAll('.stats-card').forEach((card, index) => {
            card.style.animationDelay = (index * 0.1) + 's';
            card.style.animation = 'fadeInUp 0.6s ease forwards';
        });

        // Quick action untuk approve/reject
        function quickApprove(pendaftaranId) {
            Swal.fire({
                title: 'Setujui Pendaftaran?',
                text: 'Siswa akan diterima dalam ekstrakurikuler.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX call to approve
                    fetch(`/pembina/pendaftaran/${pendaftaranId}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) {
                            showSuccess('Pendaftaran berhasil disetujui!');
                            setTimeout(() => location.reload(), 1500);
                        }
                    });
                }
            });
        }

        function quickReject(pendaftaranId) {
            Swal.fire({
                title: 'Tolak Pendaftaran?',
                input: 'textarea',
                inputLabel: 'Alasan penolakan',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan harus diisi!';
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX call to reject
                    fetch(`/pembina/pendaftaran/${pendaftaranId}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            alasan_penolakan: result.value
                        })
                    }).then(response => {
                        if (response.ok) {
                            showSuccess('Pendaftaran berhasil ditolak!');
                            setTimeout(() => location.reload(), 1500);
                        }
                    });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
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

        .stats-card {
            transition: all 0.3s ease;
            opacity: 0;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
        }

        .progress {
            transition: all 0.3s ease;
        }

        .card {
            border-radius: 12px;
        }

        .btn-group-sm .btn {
            border-radius: 6px;
        }
    </style>
@endpush
