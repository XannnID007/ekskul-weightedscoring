@extends('layouts.app')

@section('title', 'Tambah ' . ucfirst($role))
@section('page-title', 'Tambah ' . ucfirst($role))
@section('page-description', 'Buat akun ' . $role . ' baru')

@section('page-actions')
    <a href="{{ route('admin.user.index', ['role' => $role]) }}" class="btn btn-outline-light">
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

                        <!-- Data Dasar -->
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
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
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

                        @if ($role == 'siswa')
                            <!-- Data Siswa -->
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-person-badge me-2"></i>Data Siswa
                            </h6>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        id="nis" name="nis" value="{{ old('nis') }}">
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin">
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
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nilai_rata_rata" class="form-label">Nilai Rata-rata</label>
                                    <input type="number"
                                        class="form-control @error('nilai_rata_rata') is-invalid @enderror"
                                        id="nilai_rata_rata" name="nilai_rata_rata" value="{{ old('nilai_rata_rata') }}"
                                        min="0" max="100" step="0.1">
                                    @error('nilai_rata_rata')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Status -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-toggles me-2"></i>Status Akun
                        </h6>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Akun Aktif
                                </label>
                            </div>
                            <div class="form-text">Akun yang tidak aktif tidak dapat login ke sistem</div>
                        </div>

                        <!-- Submit Button -->
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
