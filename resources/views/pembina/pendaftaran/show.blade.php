@extends('layouts.app')

@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')
@section('page-description', 'Review lengkap pendaftaran siswa')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.pendaftaran.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @if ($pendaftaran->status === 'pending')
            <button class="btn btn-success" onclick="approvePendaftaran()">
                <i class="bi bi-check-circle me-1"></i>Setujui
            </button>
            <button class="btn btn-danger" onclick="rejectPendaftaran()">
                <i class="bi bi-x-circle me-1"></i>Tolak
            </button>
        @endif
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Status Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-lg bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-white fs-2"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1">{{ $pendaftaran->user->name }}</h4>
                                    <p class="text-muted mb-1">
                                        <i
                                            class="bi bi-card-text me-1"></i>{{ $pendaftaran->user->nis ?: 'NIS belum diisi' }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-collection me-1"></i>{{ $pendaftaran->ekstrakurikuler->nama }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if ($pendaftaran->status === 'pending')
                                <span class="badge bg-warning fs-6 px-3 py-2">
                                    <i class="bi bi-clock me-1"></i>Menunggu Review
                                </span>
                            @elseif($pendaftaran->status === 'disetujui')
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                                @if ($pendaftaran->disetujui_pada)
                                    <p class="text-muted mt-2 mb-0">
                                        <small>Disetujui pada:
                                            {{ $pendaftaran->disetujui_pada->format('d M Y H:i') }}</small>
                                    </p>
                                @endif
                            @else
                                <span class="badge bg-danger fs-6 px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                </span>
                            @endif
                            <p class="text-muted mt-2 mb-0">
                                <small>Mendaftar: {{ $pendaftaran->created_at->format('d M Y H:i') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge text-primary me-2"></i>Data Siswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nama Lengkap</label>
                        <p class="mb-0 fw-bold">{{ $pendaftaran->user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $pendaftaran->user->email }}" class="text-decoration-none">
                                {{ $pendaftaran->user->email }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">NIS</label>
                        <p class="mb-0">{{ $pendaftaran->user->nis ?: 'Belum diisi' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Jenis Kelamin</label>
                        <p class="mb-0">
                            @if ($pendaftaran->user->jenis_kelamin === 'L')
                                <span class="badge bg-male">Laki-laki</span>
                            @elseif($pendaftaran->user->jenis_kelamin === 'P')
                                <span class="badge bg-female">Perempuan</span>
                            @else
                                <span class="text-muted">Belum diisi</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Tanggal Lahir</label>
                        <p class="mb-0">
                            {{ $pendaftaran->user->tanggal_lahir ? $pendaftaran->user->tanggal_lahir->format('d M Y') : 'Belum diisi' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Nilai Rata-rata</label>
                        <p class="mb-0">
                            @if ($pendaftaran->user->nilai_rata_rata)
                                <span
                                    class="badge bg-{{ $pendaftaran->user->nilai_rata_rata >= 80 ? 'success' : ($pendaftaran->user->nilai_rata_rata >= 70 ? 'warning' : 'danger') }}">
                                    {{ number_format($pendaftaran->user->nilai_rata_rata, 1) }}
                                </span>
                            @else
                                <span class="text-muted">Belum diisi</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Telepon</label>
                        <p class="mb-0">
                            @if ($pendaftaran->user->telepon)
                                <a href="tel:{{ $pendaftaran->user->telepon }}" class="text-decoration-none">
                                    {{ $pendaftaran->user->telepon }}
                                </a>
                            @else
                                <span class="text-muted">Belum diisi</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Alamat</label>
                        <p class="mb-0">{{ $pendaftaran->user->alamat ?: 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pendaftaran -->
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-data text-primary me-2"></i>Form Pendaftaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Motivasi -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-heart-fill me-2"></i>Motivasi Bergabung
                                    </h6>
                                    <p class="mb-0 text-justify">{{ $pendaftaran->motivasi }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pengalaman -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-star-fill me-2"></i>Pengalaman Terkait
                                    </h6>
                                    <p class="mb-0 text-justify">
                                        {{ $pendaftaran->pengalaman ?: 'Tidak ada pengalaman khusus yang disebutkan.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Harapan -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-bullseye me-2"></i>Harapan dan Tujuan
                                    </h6>
                                    <p class="mb-0 text-justify">{{ $pendaftaran->harapan }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tingkat Komitmen -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-speedometer2 me-2"></i>Tingkat Komitmen
                                    </h6>
                                    @php
                                        $commitmentColors = [
                                            'tinggi' => 'success',
                                            'sedang' => 'warning',
                                            'rendah' => 'danger',
                                        ];
                                        $commitmentIcons = [
                                            'tinggi' => 'bi-emoji-laughing',
                                            'sedang' => 'bi-emoji-neutral',
                                            'rendah' => 'bi-emoji-frown',
                                        ];
                                        $color = $commitmentColors[$pendaftaran->tingkat_komitmen] ?? 'secondary';
                                        $icon = $commitmentIcons[$pendaftaran->tingkat_komitmen] ?? 'bi-emoji-neutral';
                                    @endphp
                                    <div class="mb-2">
                                        <i class="bi {{ $icon }} text-{{ $color }}"
                                            style="font-size: 2rem;"></i>
                                    </div>
                                    <span class="badge bg-{{ $color }} fs-6">
                                        {{ ucfirst($pendaftaran->tingkat_komitmen) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Kesesuaian Nilai -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-graph-up me-2"></i>Kesesuaian Nilai
                                    </h6>
                                    @php
                                        $nilaiSiswa = $pendaftaran->user->nilai_rata_rata ?? 0;
                                        $nilaiMinimal = $pendaftaran->ekstrakurikuler->nilai_minimal ?? 0;
                                        $memenuhi = $nilaiSiswa >= $nilaiMinimal;
                                    @endphp
                                    <div class="mb-2">
                                        <i class="bi {{ $memenuhi ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"
                                            style="font-size: 2rem;"></i>
                                    </div>
                                    <p class="mb-1">
                                        <strong>Nilai Siswa:</strong> {{ number_format($nilaiSiswa, 1) }}
                                    </p>
                                    <p class="mb-0">
                                        <strong>Minimal:</strong> {{ number_format($nilaiMinimal, 1) }}
                                    </p>
                                    <span class="badge bg-{{ $memenuhi ? 'success' : 'danger' }}">
                                        {{ $memenuhi ? 'Memenuhi' : 'Tidak Memenuhi' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minat Siswa -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart text-primary me-2"></i>Minat Siswa
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        // Pastikan $minatSiswa adalah array
                        $minatSiswa = $pendaftaran->user->minat ?? [];
                        if (is_string($minatSiswa)) {
                            $minatSiswa = json_decode($minatSiswa, true) ?? [];
                        }
                        if (!is_array($minatSiswa)) {
                            $minatSiswa = [];
                        }

                        // Pastikan $kategoriEkskul adalah array
                        $kategoriEkskul = $pendaftaran->ekstrakurikuler->kategori ?? [];
                        if (is_string($kategoriEkskul)) {
                            $kategoriEkskul = json_decode($kategoriEkskul, true) ?? [];
                        }
                        if (!is_array($kategoriEkskul)) {
                            $kategoriEkskul = [];
                        }

                        $kecocokan = [];
                        if (is_array($minatSiswa) && is_array($kategoriEkskul)) {
                            $kecocokan = array_intersect($minatSiswa, $kategoriEkskul);
                        }

                        // TAMBAHAN: Hitung tingkat kecocokan
                        $tingkatKecocokan = 0;
                        if (count($kategoriEkskul) > 0) {
                            $tingkatKecocokan = count($kecocokan) / count($kategoriEkskul);
                        }
                    @endphp
                    @if (!empty($minatSiswa))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($minatSiswa as $minat)
                                <span class="badge bg-primary fs-6 px-3 py-2">{{ ucfirst($minat) }}</span>
                            @endforeach
                        </div>

                        <!-- Analisis Kesesuaian -->
                        @if (!empty($minatSiswa))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($minatSiswa as $minat)
                                    <span class="badge bg-primary fs-6 px-3 py-2">{{ ucfirst($minat) }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if (!empty($kecocokan))
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Minat yang cocok: {{ implode(', ', $kecocokan) }}
                                </small>
                            </div>
                        @else
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tidak ada minat yang langsung cocok dengan kategori ekstrakurikuler
                                </small>
                            </div>
                        @endif

                        <div class="mt-3 p-3 bg-light-300 rounded">
                            <h6 class="text-success mb-2">
                                <i class="bi bi-puzzle me-1"></i>Analisis Kesesuaian
                            </h6>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-{{ $tingkatKecocokan >= 0.6 ? 'success' : ($tingkatKecocokan >= 0.3 ? 'warning' : 'danger') }}"
                                    style="width: {{ $tingkatKecocokan * 100 }}%"></div>
                            </div>
                            <p class="mb-0 small">
                                <strong>Tingkat Kesesuaian: {{ number_format($tingkatKecocokan * 100, 1) }}%</strong>
                                <br>
                                @if (count($kecocokan) > 0)
                                    Minat yang sesuai: {{ implode(', ', $kecocokan) }}
                                @else
                                    Tidak ada minat yang sesuai dengan kategori ekstrakurikuler
                                @endif
                            </p>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-heart text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Siswa belum mengisi data minat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Ekstrakurikuler -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-collection text-primary me-2"></i>Info Ekstrakurikuler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nama Ekstrakurikuler</label>
                        <p class="mb-0 fw-bold">{{ $pendaftaran->ekstrakurikuler->nama }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Jadwal</label>
                        <p class="mb-0">{{ $pendaftaran->ekstrakurikuler->jadwal_string }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Kapasitas</label>
                        <p class="mb-0">
                            {{ $pendaftaran->ekstrakurikuler->peserta_saat_ini }}/{{ $pendaftaran->ekstrakurikuler->kapasitas_maksimal }}
                            siswa
                            <span
                                class="badge bg-{{ $pendaftaran->ekstrakurikuler->masihBisaDaftar() ? 'success' : 'danger' }}">
                                {{ $pendaftaran->ekstrakurikuler->masihBisaDaftar() ? 'Tersedia' : 'Penuh' }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Nilai Minimal</label>
                        <p class="mb-0">{{ number_format($pendaftaran->ekstrakurikuler->nilai_minimal, 1) }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small">Kategori</label>
                        <div class="d-flex flex-wrap gap-1">
                            @if (is_array($pendaftaran->ekstrakurikuler->kategori))
                                @foreach ($pendaftaran->ekstrakurikuler->kategori as $kategori)
                                    <span class="badge bg-secondary">{{ ucfirst($kategori) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History & Timeline -->
        @if ($pendaftaran->status !== 'pending')
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Pendaftaran Dibuat</h6>
                                    <p class="text-muted mb-0">{{ $pendaftaran->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if ($pendaftaran->status === 'disetujui')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Pendaftaran Disetujui</h6>
                                        @if ($pendaftaran->disetujui_pada)
                                            <p class="text-muted mb-1">
                                                {{ $pendaftaran->disetujui_pada->format('d M Y H:i') }}</p>
                                        @endif
                                        @if ($pendaftaran->penyetuju)
                                            <small class="text-muted">Oleh: {{ $pendaftaran->penyetuju->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            @elseif($pendaftaran->status === 'ditolak')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Pendaftaran Ditolak</h6>
                                        <p class="text-muted mb-1">{{ $pendaftaran->updated_at->format('d M Y H:i') }}</p>
                                        @if ($pendaftaran->alasan_penolakan)
                                            <div class="alert alert-danger mt-2 mb-0">
                                                <strong>Alasan:</strong> {{ $pendaftaran->alasan_penolakan }}
                                            </div>
                                        @endif
                                        @if ($pendaftaran->penyetuju)
                                            <small class="text-muted">Oleh: {{ $pendaftaran->penyetuju->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function approvePendaftaran() {
            Swal.fire({
                title: 'Setujui Pendaftaran?',
                html: `
                <div class="text-start">
                    <p>Anda akan menyetujui pendaftaran:</p>
                    <ul>
                        <li><strong>Siswa:</strong> {{ $pendaftaran->user->name }}</li>
                        <li><strong>Ekstrakurikuler:</strong> {{ $pendaftaran->ekstrakurikuler->nama }}</li>
                    </ul>
                    <p class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>
                    Pastikan siswa memenuhi semua persyaratan sebelum menyetujui.</p>
                </div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang memproses persetujuan pendaftaran',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit approval
                    fetch(`{{ route('pembina.pendaftaran.approve', $pendaftaran) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pendaftaran berhasil disetujui!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
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
                                text: error.message || 'Terjadi kesalahan saat memproses persetujuan'
                            });
                        });
                }
            });
        }

        function rejectPendaftaran() {
            Swal.fire({
                title: 'Tolak Pendaftaran?',
                html: `
                <div class="text-start mb-3">
                    <p>Anda akan menolak pendaftaran:</p>
                    <ul>
                        <li><strong>Siswa:</strong> {{ $pendaftaran->user->name }}</li>
                        <li><strong>Ekstrakurikuler:</strong> {{ $pendaftaran->ekstrakurikuler->nama }}</li>
                    </ul>
                </div>
            `,
                input: 'textarea',
                inputLabel: 'Alasan penolakan',
                inputPlaceholder: 'Jelaskan alasan penolakan dengan jelas dan konstruktif...',
                inputAttributes: {
                    'aria-label': 'Masukkan alasan penolakan',
                    'rows': 4
                },
                inputValidator: (value) => {
                    if (!value || value.length < 15) {
                        return 'Alasan penolakan minimal 15 karakter dan harus jelas!';
                    }
                },
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang memproses penolakan pendaftaran',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit rejection
                    fetch(`{{ route('pembina.pendaftaran.reject', $pendaftaran) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                alasan_penolakan: result.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Pendaftaran berhasil ditolak!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
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
                                text: error.message || 'Terjadi kesalahan saat memproses penolakan'
                            });
                        });
                }
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .avatar-lg {
            width: 80px;
            height: 80px;
        }

        .text-justify {
            text-align: justify;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-content {
            padding-left: 20px;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .badge.fs-6 {
            font-size: 0.9rem !important;
        }
    </style>
@endpush
