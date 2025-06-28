@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')
@section('page-description', 'Selamat datang, ' . auth()->user()->name)

@section('content')
    <div class="row g-4">
        <!-- Status Cards -->
        <div class="col-md-6 col-xl-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Status Pendaftaran</h6>
                            <h2 class="mb-0">
                                @if (auth()->user()->pendaftarans()->where('status', 'disetujui')->exists())
                                    <i class="bi bi-check-circle text-success"></i>
                                @elseif(auth()->user()->pendaftarans()->where('status', 'pending')->exists())
                                    <i class="bi bi-clock text-warning"></i>
                                @else
                                    <i class="bi bi-x-circle text-danger"></i>
                                @endif
                            </h2>
                            <small class="opacity-75">
                                @if (auth()->user()->pendaftarans()->where('status', 'disetujui')->exists())
                                    Sudah Terdaftar
                                @elseif(auth()->user()->pendaftarans()->where('status', 'pending')->exists())
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
                                @if (auth()->user()->pendaftarans()->where('status', 'disetujui')->exists())
                                    {{ auth()->user()->pendaftarans()->where('status', 'disetujui')->first()->persentase_kehadiran ?? 0 }}%
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
                                @if (auth()->user()->sudahTerdaftarEkstrakurikuler())
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

        <!-- Rekomendasi Section -->
        @if (!auth()->user()->sudahTerdaftarEkstrakurikuler())
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
                                            style="background: linear-gradient(135deg, rgba(108, 66, 193, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);">
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
        @else
            <!-- Ekstrakurikuler yang Diikuti -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-collection text-primary me-2"></i>Ekstrakurikuler yang Diikuti
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $ekstrakurikuler_diikuti = auth()
                                ->user()
                                ->ekstrakurikulers()
                                ->wherePivot('status', 'disetujui')
                                ->first();
                        @endphp

                        @if ($ekstrakurikuler_diikuti)
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    @if ($ekstrakurikuler_diikuti->gambar)
                                        <img src="{{ Storage::url($ekstrakurikuler_diikuti->gambar) }}"
                                            alt="{{ $ekstrakurikuler_diikuti->nama }}" class="rounded-3" width="100"
                                            height="100" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center"
                                            style="width: 100px; height: 100px;">
                                            <i class="bi bi-collection text-white fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h4 class="mb-2">{{ $ekstrakurikuler_diikuti->nama }}</h4>
                                    <p class="text-muted mb-3">{{ Str::limit($ekstrakurikuler_diikuti->deskripsi, 150) }}
                                    </p>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">Pembina</small>
                                            <strong>{{ $ekstrakurikuler_diikuti->pembina->name }}</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted d-block">Jadwal</small>
                                            <strong>{{ $ekstrakurikuler_diikuti->jadwal_string }}</strong>
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

        .avatar-sm {
            width: 40px;
            height: 40px;
        }
    </style>
@endpush
