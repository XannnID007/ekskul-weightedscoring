@extends('layouts.app')

@section('title', 'Edit ' . ucfirst($user->role))
@section('page-title', 'Edit ' . ucfirst($user->role))
@section('page-description', 'Ubah informasi pengguna')

@section('page-actions')
    <a href="{{ route('admin.user.index', ['role' => $user->role]) }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Form Edit {{ ucfirst($user->role) }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @if ($user->role == 'siswa')

                            {{-- FORMULIR EDIT KHUSUS UNTUK SISWA --}}
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-person-badge me-2"></i>Data Pokok Siswa
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nis" class="form-label">NISN *</label>
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        id="nis" name="nis" value="{{ old('nis', $user->nis) }}" required>
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin *</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="L"
                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="P"
                                            {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Menampilkan info status akun siswa --}}
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                @if ($user->email_verified_at)
                                    Akun ini sudah <strong>diaktifkan</strong> oleh siswa dengan email:
                                    <strong>{{ $user->email }}</strong>
                                @else
                                    Akun ini <strong>belum diaktifkan</strong> oleh siswa.
                                @endif
                            </div>
                        @else
                            {{-- FORMULIR EDIT LAMA UNTUK ADMIN DAN PEMBINA --}}
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-person me-2"></i>Data Dasar
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password Baru (Opsional)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                        id="telepon" name="telepon" value="{{ old('telepon', $user->telepon) }}">
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.user.index', ['role' => $user->role]) }}" class="btn btn-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Form validation untuk siswa
        @if ($user->role == 'siswa')
            document.querySelector('form').addEventListener('submit', function(e) {
                const checkboxes = document.querySelectorAll('input[name="minat[]"]:checked');
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Minat Belum Dipilih',
                        text: 'Pilih minimal satu minat untuk siswa ini.'
                    });
                    return false;
                }
            });
        @endif
    </script>
@endpush
