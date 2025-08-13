@extends('layouts.app')

@section('title', 'Tambah ' . ucfirst($role))
@section('page-title', 'Tambah ' . ucfirst($role))
@section('page-description', 'Buat akun ' . $role . ' baru')

@section('page-actions')
    <a href="{{ route('admin.user.index', ['role' => $role]) }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-plus me-2"></i>Form Tambah {{ ucfirst($role) }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">

                        @if ($role == 'siswa')
                            {{-- FORMULIR KHUSUS JIKA ROLE ADALAH SISWA --}}
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-person-badge me-2"></i>Data Pokok Siswa
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nis" class="form-label">NISN *</label>
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        id="nis" name="nis" value="{{ old('nis') }}" required>
                                    <div class="form-text">NISN akan digunakan siswa untuk registrasi.</div>
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin *</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            {{-- FORMULIR LAMA UNTUK ADMIN DAN PEMBINA --}}
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-person me-2"></i>Data Dasar
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    <div class="form-text">Minimal 6 karakter</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                        id="telepon" name="telepon" value="{{ old('telepon') }}">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.user.index', ['role' => $role]) }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan {{ ucfirst($role) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($role == 'siswa')
                <!-- Import Siswa -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-upload me-2"></i>Import Data Siswa
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.user.import-siswa') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label for="file" class="form-label">File Excel (.xlsx/.xls)</label>
                                    <input type="file" class="form-control" id="file" name="file"
                                        accept=".xlsx,.xls" required>
                                    <div class="form-text">
                                        Format: Nama, Email, NIS, Jenis Kelamin, Tanggal Lahir, Alamat, Telepon
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-upload me-1"></i>Import
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Template:</strong>
                            <a href="{{ asset('templates/template-siswa.xlsx') }}" class="alert-link">Download template
                                Excel</a>
                            untuk format yang benar
                        </div>
                    </div>
                </div>
            @endif
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

        // Auto generate password suggestion
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value.toLowerCase().replace(/\s+/g, '');
            const suggestion = name + '123';

            if (name && !document.getElementById('password').value) {
                document.getElementById('password').placeholder = 'Saran: ' + suggestion;
            }
        });
    </script>
@endpush
