@extends('layouts.app')

@section('title', 'Detail ' . $user->name)
@section('page-title', 'Detail ' . ucfirst($user->role))
@section('page-description', 'Informasi lengkap pengguna')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.user.index', ['role' => $user->role]) }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle p-4 d-inline-flex mb-3" style="font-size: 3rem;">
                            <i class="bi bi-person text-white"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ ucfirst($user->role) }}</p>

                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} fs-6 px-3 py-2">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    @if ($user->role == 'siswa' && $user->nilai_rata_rata)
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-info rounded p-3">
                                    <div class="text-white">
                                        <strong class="d-block fs-4">{{ $user->nilai_rata_rata }}</strong>
                                        <small>Nilai Rata-rata</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-warning rounded p-3">
                                    <div class="text-white">
                                        <strong class="d-block fs-4">{{ $user->age ?? '-' }}</strong>
                                        <small>Usia</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit Profil
                        </a>
                        @if ($user->id !== auth()->id())
                            <button class="btn btn-danger"
                                onclick="confirmDelete('{{ route('admin.user.destroy', $user) }}')">
                                <i class="bi bi-trash me-1"></i>Hapus Akun
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @if ($user->role == 'siswa')
                <!-- Quick Stats for Siswa -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Statistik</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $pendaftaran = $user->pendaftarans()->first();
                            $rekomendasi = $user->rekomendasis()->count();
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Status Ekstrakurikuler:</span>
                            @if ($pendaftaran)
                                <span
                                    class="badge bg-{{ $pendaftaran->status == 'disetujui' ? 'success' : ($pendaftaran->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($pendaftaran->status) }}
                                </span>
                            @else
                                <span class="text-muted">Belum Daftar</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span>Rekomendasi:</span>
                            <span class="badge bg-info">{{ $rekomendasi }} tersedia</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Details -->
        <div class="col-xl-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-lines-fill me-2"></i>Informasi Dasar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">NAMA LENGKAP</label>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">EMAIL</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">TELEPON</label>
                            <p class="mb-0">
                                @if ($user->telepon)
                                    <a href="tel:{{ $user->telepon }}" class="text-decoration-none">
                                        {{ $user->formatted_phone ?? $user->telepon }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">ROLE</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">STATUS AKUN</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">TERDAFTAR</label>
                            <p class="mb-0">{{ $user->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($user->role == 'siswa')
                <!-- Student Specific Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person-badge me-2"></i>Data Siswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">NIS</label>
                                <p class="mb-0">{{ $user->nis ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">JENIS KELAMIN</label>
                                <p class="mb-0">
                                    @if ($user->jenis_kelamin)
                                        <span class="badge bg-{{ $user->jenis_kelamin == 'L' ? 'info' : 'pink' }}">
                                            {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">TANGGAL LAHIR</label>
                                <p class="mb-0">
                                    @if ($user->tanggal_lahir)
                                        {{ $user->tanggal_lahir->format('d M Y') }}
                                        <small class="text-muted">({{ $user->age }} tahun)</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">NILAI RATA-RATA</label>
                                <p class="mb-0">
                                    @if ($user->nilai_rata_rata)
                                        <span
                                            class="badge bg-{{ $user->nilai_rata_rata >= 80 ? 'success' : ($user->nilai_rata_rata >= 70 ? 'warning' : 'danger') }}">
                                            {{ $user->nilai_rata_rata }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">ALAMAT</label>
                                <p class="mb-0">{{ $user->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interests & Achievements -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-star me-2"></i>Minat & Prestasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted">MINAT</label>
                                <div>
                                    @if ($user->minat_array && count($user->minat_array) > 0)
                                        @foreach ($user->minat_array as $minat)
                                            <span class="badge bg-secondary me-1 mb-1">{{ ucfirst($minat) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Belum mengisi minat</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">PRESTASI</label>
                                <p class="mb-0">
                                    @if ($user->prestasi)
                                        {{ $user->prestasi }}
                                    @else
                                        <span class="text-muted">Belum ada prestasi yang dicatat</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ekstrakurikuler Activity -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-collection me-2"></i>Aktivitas Ekstrakurikuler
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($user->pendaftarans->count() > 0)
                            @foreach ($user->pendaftarans as $pendaftaran)
                                <div class="d-flex align-items-start border-bottom pb-3 mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-collection text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $pendaftaran->ekstrakurikuler->nama }}</h6>
                                        <p class="text-muted mb-1">
                                            Pembina: {{ $pendaftaran->ekstrakurikuler->pembina->name }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span
                                                class="badge bg-{{ $pendaftaran->status == 'disetujui' ? 'success' : ($pendaftaran->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($pendaftaran->status) }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $pendaftaran->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                        @if ($pendaftaran->motivasi)
                                            <small class="text-muted d-block mt-2">
                                                "{{ Str::limit($pendaftaran->motivasi, 100) }}"
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-collection text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum pernah mendaftar ekstrakurikuler</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Hapus {{ ucfirst($user->role) }}?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
