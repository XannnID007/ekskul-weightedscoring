@extends('layouts.app')

@section('title', 'Lengkapi Profil')
@section('page-title', 'Lengkapi Profil')
@section('page-description', 'Lengkapi profil Anda untuk mendapatkan rekomendasi ekstrakurikuler yang akurat')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Progress Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Kelengkapan Profil</h6>
                        <span class="badge bg-{{ $profilCheck['persentase'] == 100 ? 'success' : 'warning' }}">
                            {{ $profilCheck['persentase'] }}%
                        </span>
                    </div>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar bg-{{ $profilCheck['persentase'] == 100 ? 'success' : 'warning' }}"
                            style="width: {{ $profilCheck['persentase'] }}%"></div>
                    </div>
                    @if (!$profilCheck['lengkap'])
                        <small class="text-muted">
                            Lengkapi data berikut:
                            @foreach ($profilCheck['fields_kosong'] as $field)
                                <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                            @endforeach
                        </small>
                    @else
                        <small class="text-success">
                            <i class="bi bi-check-circle me-1"></i>Profil Anda sudah lengkap!
                        </small>
                    @endif
                </div>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-gear me-2"></i>Informasi Profil
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('siswa.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Data Pribadi -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-person me-2"></i>Data Pribadi
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis"
                                    name="nis" value="{{ old('nis', $user->nis) }}">
                                @error('nis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                    id="telepon" name="telepon" value="{{ old('telepon', $user->telepon) }}">
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin *</label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                                    name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir *</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d')) }}" required>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Data Akademik -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-trophy me-2"></i>Data Akademik
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="nilai_rata_rata" class="form-label">Nilai Rata-rata *</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('nilai_rata_rata') is-invalid @enderror"
                                        id="nilai_rata_rata" name="nilai_rata_rata"
                                        value="{{ old('nilai_rata_rata', $user->nilai_rata_rata) }}" min="0"
                                        max="100" step="0.1" required>
                                    <span class="input-group-text">/ 100</span>
                                </div>
                                <div class="form-text">Masukkan nilai rata-rata rapor terbaru</div>
                                @error('nilai_rata_rata')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="prestasi" class="form-label">Prestasi & Penghargaan</label>
                                <textarea class="form-control @error('prestasi') is-invalid @enderror" id="prestasi" name="prestasi" rows="3"
                                    placeholder="Tulis prestasi akademik atau non-akademik yang pernah diraih">{{ old('prestasi', $user->prestasi) }}</textarea>
                                @error('prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Minat & Hobi -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-heart me-2"></i>Minat & Hobi *
                        </h6>

                        <div class="mb-4">
                            <div class="form-text mb-3">Pilih minimal 1 minat yang sesuai dengan Anda (untuk algoritma
                                rekomendasi)</div>
                            <div class="row g-2">
                                @foreach ($minat_options as $key => $label)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="minat_{{ $key }}" name="minat[]"
                                                value="{{ $key }}"
                                                {{ in_array($key, old('minat', $user->minat_array)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="minat_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('minat')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keamanan -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-shield-lock me-2"></i>Ubah Password (Opsional)
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" autocomplete="new-password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan Profil
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
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Check at least one interest selected
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="minat[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Minat Belum Dipilih',
                    text: 'Pilih minimal satu minat untuk mendapatkan rekomendasi yang akurat.'
                });
                return false;
            }
        });

        // Real-time progress update
        function updateProgress() {
            const requiredFields = ['name', 'jenis_kelamin', 'tanggal_lahir', 'nilai_rata_rata'];
            const minatChecked = document.querySelectorAll('input[name="minat[]"]:checked').length > 0;

            let filledFields = 0;
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && field.value.trim() !== '') {
                    filledFields++;
                }
            });

            if (minatChecked) filledFields++;

            const progress = Math.round((filledFields / (requiredFields.length + 1)) * 100);
            const progressBar = document.querySelector('.progress-bar');
            const progressBadge = document.querySelector('.badge');

            progressBar.style.width = progress + '%';
            progressBadge.textContent = progress + '%';

            if (progress === 100) {
                progressBar.className = 'progress-bar bg-success';
                progressBadge.className = 'badge bg-success';
            } else {
                progressBar.className = 'progress-bar bg-warning';
                progressBadge.className = 'badge bg-warning';
            }
        }

        // Add event listeners for real-time progress
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', updateProgress);
            element.addEventListener('change', updateProgress);
        });

        // Initial progress update
        updateProgress();
    </script>
@endpush

@push('styles')
    <style>
        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(108, 66, 193, 0.25);
        }

        .progress {
            transition: all 0.3s ease;
        }

        .form-check-label {
            cursor: pointer;
        }

        .form-check {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease;
        }

        .form-check:hover {
            background-color: rgba(108, 66, 193, 0.1);
        }
    </style>
@endpush
