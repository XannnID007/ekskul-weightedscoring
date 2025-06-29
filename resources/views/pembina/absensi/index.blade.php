@extends('layouts.app')

@section('title', 'Input Kehadiran')
@section('page-title', 'Input Kehadiran Digital')
@section('page-description', 'Kelola kehadiran siswa pada ekstrakurikuler yang Anda bina')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.absensi.history') }}" class="btn btn-outline-light">
            <i class="bi bi-clock-history me-1"></i>Riwayat Absensi
        </a>
        <a href="{{ route('pembina.absensi.report') }}" class="btn btn-light">
            <i class="bi bi-graph-up me-1"></i>Laporan
        </a>
    </div>
@endsection

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
                                                        {{ $attendance && $attendance->status === 'hadir' ? 'selected' : '' }
