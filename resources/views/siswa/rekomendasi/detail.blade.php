@extends('layouts.app')

@section('title', 'Detail Rekomendasi')
@section('page-title', 'Detail Rekomendasi')
@section('page-description', 'Analisis mendalam mengapa ekstrakurikuler ini cocok untuk Anda')

@section('page-actions')
    <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Rekomendasi
    </a>
@endsection

@section('content')
    @php
        $user = auth()->user();
        $ekstrakurikuler = $rekomendasi->ekstrakurikuler;

        // Ambil data nilai dan minat
        $nilaiUser = $user->nilai_rata_rata ?? 0;
        $nilaiMinimal = $ekstrakurikuler->nilai_minimal ?? 0;
        $minatUserRaw = is_array($user->minat) ? $user->minat : json_decode($user->minat, true) ?? [];
        $kategoriEkskulRaw = $ekstrakurikuler->kategori ?? [];

        // Set nilai default untuk status akademik
        $statusAkademik = 'Data Belum Lengkap';
        $warnaAkademik = 'secondary';

        // Logika Status Akademik
        if ($nilaiUser > 0) {
            if ($nilaiUser >= 85) {
                $statusAkademik = 'Sangat Baik';
                $warnaAkademik = 'success';
            } elseif ($nilaiUser >= 75) {
                $statusAkademik = 'Baik';
                $warnaAkademik = 'info';
            } elseif ($nilaiUser >= 65) {
                $statusAkademik = 'Cukup';
                $warnaAkademik = 'warning';
            } else {
                $statusAkademik = 'Perlu Peningkatan';
                $warnaAkademik = 'danger';
            }
        }

        // --- PERBAIKAN JADWAL DAN MINAT ---
        // Fungsi untuk membersihkan dan menormalkan data (menghapus spasi dan membuat format "Title Case")
        $normalizer = function ($item) {
            return ucfirst(strtolower(trim($item)));
        };

        // Terapkan normalizer ke semua data array
        $minatUser = array_map($normalizer, $minatUserRaw);
        $kategoriEkskul = array_map($normalizer, $kategoriEkskulRaw);

        $jadwalUserRaw = is_array($user->jadwal_luang)
            ? $user->jadwal_luang
            : json_decode($user->jadwal_luang, true) ?? [];
        $jadwalEkskulRaw = is_array($ekstrakurikuler->jadwal)
            ? $ekstrakurikuler->jadwal
            : json_decode($ekstrakurikuler->jadwal, true) ?? [];
        $jadwalUser = array_map($normalizer, $jadwalUserRaw);
        $jadwalEkskul = array_map($normalizer, $jadwalEkskulRaw);

        // Proses data yang sudah bersih
        $kecocokanMinat = array_intersect($minatUser, $kategoriEkskul);
        $kecocokanJadwal = array_intersect($jadwalUser, $jadwalEkskul);
        $jadwalCocok = !empty($kecocokanJadwal);
    @endphp

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card mb-4">
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
                            <h2 class="mb-1">{{ $ekstrakurikuler->nama }}</h2>
                            <p class="text-muted mb-2">{{ $ekstrakurikuler->deskripsi_singkat }}</p>
                            <div>
                                @if (is_array($kategoriEkskul))
                                    @foreach ($kategoriEkskul as $kategori)
                                        <span
                                            class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="text-center ms-md-4 mt-3 mt-md-0">
                            <div class="display-4 text-primary fw-bold">{{ number_format($rekomendasi->total_skor, 0) }}%
                            </div>
                            <div class="text-muted fw-bold">SKOR KECOCOKAN</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Bagaimana Sistem Menghitung Rekomendasi Ini?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Sistem menggunakan <strong>3 kriteria utama</strong> dengan bobot berbeda untuk menentukan seberapa
                        cocok ekstrakurikuler ini dengan profil Anda:
                    </p>

                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3 mb-3">
                                <i class="bi bi-heart fs-1 text-info"></i>
                            </div>
                            <h6 class="text-info">Kesesuaian Minat</h6>
                            <div class="badge bg-info">Bobot: 50%</div>
                            <p class="small text-muted mt-2">Seberapa cocok dengan hobi dan ketertarikan Anda</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3 mb-3">
                                <i class="bi bi-trophy fs-1 text-warning"></i>
                            </div>
                            <h6 class="text-warning">Kemampuan Akademik</h6>
                            <div class="badge bg-warning">Bobot: 30%</div>
                            <p class="small text-muted mt-2">Apakah nilai Anda memenuhi syarat yang diperlukan</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-3">
                                <i class="bi bi-calendar-check fs-1 text-success"></i>
                            </div>
                            <h6 class="text-success">Kecocokan Jadwal</h6>
                            <div class="badge bg-success">Bobot: 20%</div>
                            <p class="small text-muted mt-2">Apakah jadwal ekskul sesuai dengan waktu luang Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calculator me-2"></i>Rincian Perhitungan Untuk Anda
                    </h5>
                </div>
                <div class="card-body">

                    <div class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-heart text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">1. Analisis Kesesuaian Minat</h6>
                                    <small class="text-muted">Membandingkan minat Anda dengan kategori
                                        ekstrakurikuler</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 text-info">{{ number_format($rekomendasi->skor_minat, 0) }}</h5>
                                <small class="text-muted">Kontribusi Skor</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong class="text-info">Minat Anda:</strong>
                                <div class="mt-1">
                                    @forelse ($minatUser as $minat)
                                        <span
                                            class="badge bg-info bg-opacity-20 text-white me-1 mb-1">{{ ucfirst($minat) }}</span>
                                    @empty
                                        <small class="text-muted">Anda belum mengisi minat di profil</small>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-6">
                                <strong class="text-info">Kategori Ekstrakurikuler:</strong>
                                <div class="mt-1">
                                    @forelse ($kategoriEkskul as $kategori)
                                        <span
                                            class="badge {{ in_array($kategori, $kecocokanMinat) ? 'bg-primary' : 'bg-secondary' }} me-1 mb-1">
                                            {{ ucfirst($kategori) }}
                                            @if (in_array($kategori, $kecocokanMinat))
                                                <i class="bi bi-check-lg ms-1"></i>
                                            @endif
                                        </span>
                                    @empty
                                        <small class="text-muted">Tidak ada kategori</small>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if (!empty($kecocokanMinat))
                            <div class="mt-3 p-2 bg-success bg-opacity-10 rounded">
                                <strong class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Kecocokan Ditemukan
                                </strong>
                                <p class="mb-0 small mt-1">Minat Anda pada bidang
                                    <strong>{{ implode(', ', array_map('ucfirst', $kecocokanMinat)) }}</strong> sesuai
                                    dengan kategori ekstrakurikuler ini.
                                </p>
                            </div>
                        @else
                            <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded">
                                <strong class="text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Tidak Ada Kecocokan Langsung
                                </strong>
                                <p class="mb-0 small mt-1">Meskipun tidak ada kecocokan minat langsung, sistem mungkin
                                    merekomendasikan ini berdasarkan faktor lain.</p>
                            </div>
                        @endif

                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $rekomendasi->skor_minat }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="bi bi-trophy text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">2. Analisis Kemampuan Akademik</h6>
                                    <small class="text-muted">Membandingkan nilai Anda dengan persyaratan minimum</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 text-warning">{{ number_format($rekomendasi->skor_akademik, 0) }}</h5>
                                <small class="text-muted">Kontribusi Skor</small>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-{{ $warnaAkademik }} bg-opacity-10 rounded">
                                    <h4 class="mb-1 text-{{ $warnaAkademik }}">{{ $nilaiUser ?: 'N/A' }}</h4>
                                    <small>Nilai Rata-rata Anda</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="bi bi-arrow-right text-muted fs-1 d-none d-md-inline-block"></i>
                                <i class="bi bi-arrow-down text-muted fs-1 d-md-none"></i>
                                <div class="mt-2">
                                    @if ($nilaiUser >= $nilaiMinimal)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-lg me-1"></i>Memenuhi Syarat
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-lg me-1"></i>Belum Memenuhi
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-secondary bg-opacity-10 rounded">
                                    <h4 class="mb-1 text-secondary">{{ $nilaiMinimal }}</h4>
                                    <small>Nilai Minimal</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 p-2 bg-{{ $warnaAkademik }} bg-opacity-10 rounded">
                            <strong class="text-{{ $warnaAkademik }}">Status Akademik Anda:
                                {{ $statusAkademik }}</strong>
                            @if ($nilaiUser >= $nilaiMinimal)
                                <p class="mb-0 small mt-1">Selamat! Nilai Anda sudah memenuhi persyaratan untuk mendaftar.
                                </p>
                            @else
                                <p class="mb-0 small mt-1">Anda perlu meningkatkan nilai rata-rata untuk dapat mendaftar
                                    ekstrakurikuler ini.</p>
                            @endif
                        </div>

                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $rekomendasi->skor_akademik }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-calendar-check text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">3. Analisis Kecocokan Jadwal</h6>
                                    <small class="text-muted">Membandingkan jadwal luang Anda dengan jadwal
                                        kegiatan</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0 text-success">{{ number_format($rekomendasi->skor_jadwal, 0) }}</h5>
                                <small class="text-muted">Kontribusi Skor</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong class="text-success">Jadwal Luang Anda:</strong>
                                <div class="mt-1">
                                    @forelse ($jadwalUser as $hari)
                                        <span
                                            class="badge bg-success bg-opacity-20 text-white me-1 mb-1">{{ $hari }}</span>
                                    @empty
                                        <small class="text-muted">Anda belum mengisi jadwal luang di profil</small>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-6">
                                <strong class="text-success">Jadwal Ekstrakurikuler:</strong>
                                <div class="mt-1">
                                    @forelse ($jadwalEkskul as $hari)
                                        <span
                                            class="badge {{ in_array($hari, $kecocokanJadwal) ? 'bg-primary' : 'bg-secondary' }} me-1 mb-1">
                                            {{ $hari }}
                                            @if (in_array($hari, $kecocokanJadwal))
                                                <i class="bi bi-check-lg ms-1"></i>
                                            @endif
                                        </span>
                                    @empty
                                        <small class="text-muted">Jadwal belum ditentukan</small>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if ($jadwalCocok)
                            <div class="mt-3 p-2 bg-success bg-opacity-10 rounded">
                                <strong class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Jadwal Cocok!
                                </strong>
                                <p class="mb-0 small mt-1">
                                    Jadwal ekstrakurikuler ini sesuai dengan waktu luang Anda pada hari:
                                    <strong>{{ implode(', ', $kecocokanJadwal) }}</strong>.
                                </p>
                            </div>
                        @else
                            <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded">
                                <strong class="text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Jadwal Tidak Cocok
                                </strong>
                                <p class="mb-0 small mt-1">
                                    Jadwal ekstrakurikuler ini tidak ada yang sesuai dengan jadwal luang yang Anda masukkan.
                                </p>
                            </div>
                        @endif

                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $rekomendasi->skor_jadwal }}%"></div>
                        </div>
                    </div>

                    <div class="p-4 bg-primary bg-opacity-10 rounded">
                        <h6 class="mb-3">
                            <i class="bi bi-calculator me-2"></i>Perhitungan Skor Total
                        </h6>
                        <div class="row g-3">
                            <div class="col-lg-9">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Kontribusi Minat (Bobot 50%)</span>
                                    <span><strong>{{ number_format($rekomendasi->skor_minat * 0.5, 1) }}</strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Kontribusi Akademik (Bobot 30%)</span>
                                    <span><strong>{{ number_format($rekomendasi->skor_akademik * 0.3, 1) }}</strong></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Kontribusi Jadwal (Bobot 20%)</span>
                                    <span><strong>{{ number_format($rekomendasi->skor_jadwal * 0.2, 1) }}</strong></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Skor Rekomendasi</span>
                                    <span><strong
                                            class="text-primary fs-5">{{ number_format($rekomendasi->total_skor, 1) }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Kesimpulan & Rekomendasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Alasan Sistem:</strong> {{ $rekomendasi->alasan }}
                    </div>

                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <h6 class="text-success">
                                <i class="bi bi-check-circle me-2"></i>Faktor Pendukung
                            </h6>
                            <ul class="list-unstyled">
                                @if (!empty($kecocokanMinat))
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-check text-success me-2 mt-1"></i>
                                        <span>Minat yang sangat sesuai dengan kategori ekstrakurikuler.</span>
                                    </li>
                                @endif
                                @if ($nilaiUser >= $nilaiMinimal)
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-check text-success me-2 mt-1"></i>
                                        <span>Nilai akademik Anda telah memenuhi persyaratan minimum.</span>
                                    </li>
                                @endif
                                @if ($jadwalCocok)
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-check text-success me-2 mt-1"></i>
                                        <span>Jadwal kegiatan cocok dengan waktu luang Anda.</span>
                                    </li>
                                @endif
                                @if (count($minatUser) > 0 &&
                                        $nilaiUser > 0 &&
                                        count($jadwalUser) > 0 &&
                                        empty($kecocokanMinat) &&
                                        $nilaiUser < $nilaiMinimal &&
                                        !$jadwalCocok)
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-info-circle text-muted me-2 mt-1"></i>
                                        <span>Tidak ada faktor pendukung utama.</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>Poin Pertimbangan
                            </h6>
                            <ul class="list-unstyled">
                                @if (empty($kecocokanMinat))
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-info-circle text-warning me-2 mt-1"></i>
                                        <span>Tidak ada kecocokan minat secara langsung, mungkin perlu adaptasi.</span>
                                    </li>
                                @endif
                                @if ($nilaiUser < $nilaiMinimal)
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-info-circle text-warning me-2 mt-1"></i>
                                        <span>Nilai rata-rata Anda masih di bawah persyaratan minimum.</span>
                                    </li>
                                @endif
                                @if (!$jadwalCocok)
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-info-circle text-warning me-2 mt-1"></i>
                                        <span>Jadwal kegiatan bentrok dengan waktu luang yang Anda miliki.</span>
                                    </li>
                                @endif
                                @if (empty($kecocokanMinat) || $nilaiUser < $nilaiMinimal || !$jadwalCocok)
                                    {{-- This condition is met, so nothing else will show. If you want a default message, add it outside --}}
                                @else
                                    <li class="mb-2 d-flex">
                                        <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                                        <span>Tidak ada poin pertimbangan khusus.</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Quick Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Ekstrakurikuler</h6>
                    </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">PEMBINA</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle p-2 me-2">
                                <i class="bi bi-person text-white"></i>
                                </div>
                            <div>
                                <strong>{{ $ekstrakurikuler->pembina->name }}</strong>
                                @if ($ekstrakurikuler->pembina->telepon)
                                    <br><small class="text-muted">{{ $ekstrakurikuler->pembina->telepon }}</small>
                                @endif
                                </div>
                            </div>
                        </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">JADWAL</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-2 me-2">
                                <i class="bi bi-calendar text-white"></i>
                                </div>
                            
                            <strong>{{ $ekstrakurikuler->jadwal_string ?? 'Belum ditentukan' }}</strong>
                            </div>
                        </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">KAPASITAS</label>
                        <div class="d-flex justify-content-between mb-1">
                            
                            <span>{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                            
                            <span>{{ round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) }}%</span>
                            </div>
                        <div class="progress">
                            <div
                                class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                
                                style="width: {{ ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    <div>
                        <label class="form-label small text-muted">NILAI MINIMAL</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-2">
                                <i class="bi bi-trophy text-white"></i>
                                </div>
                            <strong>{{ $ekstrakurikuler->nilai_minimal }}/100</strong>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('siswa.ekstrakurikuler.show', $ekstrakurikuler) }}"
                            class="btn btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                            </a>
                        @if (auth()->user()->sudahTerdaftarEkstrakurikuler())
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-info-circle me-1"></i>Sudah Terdaftar
                                Ekstrakurikuler Lain
                                </button>
                        @elseif(!$ekstrakurikuler->masihBisaDaftar())
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-x-circle me-1"></i>Kuota Penuh
                                </button>
                        @elseif(auth()->user()->nilai_rata_rata && auth()->user()->nilai_rata_rata < $ekstrakurikuler->nilai_minimal)
                            <button class="btn btn-warning" disabled>
                                <i class="bi bi-exclamation-circle me-1"></i>Nilai Tidak
                                Memenuhi
                                </button>
                        @else
                            <a href="{{ route('siswa.ekstrakurikuler.show', $ekstrakurikuler) }}#daftar"
                                class="btn btn-primary">
                                <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                </a>
                        @endif
                        <button class="btn btn-outline-warning" onclick="shareRecommendation()">
                            <i class="bi bi-share me-1"></i>Bagikan Rekomendasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    @endsection

    @push('scripts')
        <script>
            function shareRecommendation() {
                if (navigator.share) {
                    navigator.share({
                        title: 'Rekomendasi Ekstrakurikuler - {{ $rekomendasi->ekstrakurikuler->nama }}',
                        text: 'Saya mendapat rekomendasi {{ number_format($rekomendasi->total_skor, 1) }}% untuk ekstrakurikuler {{ $rekomendasi->ekstrakurikuler->nama }}!',
                        url: window.location.href
                    });
                } else {
                    // Fallback - copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        showSuccess('Link rekomendasi berhasil disalin!');
                    });
                }
            }

            // Animate progress bars on load
            document.addEventListener('DOMContentLoaded', function() {
                const progressBars = document.querySelectorAll('.progress-bar');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.transition = 'width 1.5s ease-in-out';
                        bar.style.width = width;
                    }, 500);
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .progress {
                background-color: rgba(255, 255, 255, 0.1);
            }

            .progress-bar {
                transition: width 1.5s ease-in-out;
            }

            .card {
                transition: all 0.3s ease;
            }

            .alert {
                border-radius: 10px;
            }

            /* Additional styling for calculation boxes */
            .border {
                border-color: var(--bs-gray-300) !important;
            }

            .bg-opacity-10 {
                --bs-bg-opacity: 0.1;
            }

            /* Badge styling improvements */
            .badge {
                font-size: 0.8em;
                padding: 0.5em 0.8em;
            }

            /* Hover effects for calculation boxes */
            .border:hover {
                border-color: var(--bs-primary) !important;
                transition: all 0.3s ease;
            }
        </style>
    @endpush
