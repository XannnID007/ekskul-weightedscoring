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
                        <i class="bi bi-pencil me-2"></i>Form Edit {{ ucfirst($user->role) }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.user.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Data Dasar -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-person me-2"></i>Data Dasar
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
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                @error('password')
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
                        </div>

                        @if ($user->role == 'siswa')
                            <!-- Data Siswa -->
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-person-badge me-2"></i>Data Siswa
                            </h6>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                        id="nis" name="nis" value="{{ old('nis', $user->nis) }}">
                                    @error('nis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
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
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nilai_rata_rata" class="form-label">Nilai Rata-rata</label>
                                    <input type="number"
                                        class="form-control @error('nilai_rata_rata') is-invalid @enderror"
                                        id="nilai_rata_rata" name="nilai_rata_rata"
                                        value="{{ old('nilai_rata_rata', $user->nilai_rata_rata) }}" min="0"
                                        max="100" step="0.1">
                                    @error('nilai_rata_rata')
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

                            <!-- Minat & Prestasi -->
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-star me-2"></i>Minat & Prestasi
                            </h6>

                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label">Minat</label>
                                    <div class="row g-2">
                                        @php
                                            $minat_options = [
                                                'olahraga' => 'Olahraga',
                                                'seni' => 'Seni & Budaya',
                                                'akademik' => 'Akademik',
                                                'teknologi' => 'Teknologi',
                                                'bahasa' => 'Bahasa',
                                                'kepemimpinan' => 'Kepemimpinan',
                                                'sosial' => 'Sosial',
                                                'musik' => 'Musik',
                                                'tari' => 'Tari',
                                                'teater' => 'Teater',
                                                'jurnalistik' => 'Jurnalistik',
                                                'fotografi' => 'Fotografi',
                                                'memasak' => 'Memasak',
                                                'berkebun' => 'Berkebun',
                                            ];

                                            $userMinat = old('minat', $user->minat_array ?? []);
                                        @endphp
                                        @foreach ($minat_options as $key => $label)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="minat_{{ $key }}" name="minat[]"
                                                        value="{{ $key }}"
                                                        {{ in_array($key, $userMinat) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="minat_{{ $key }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="prestasi" class="form-label">Prestasi</label>
                                    <textarea class="form-control @error('prestasi') is-invalid @enderror" id="prestasi" name="prestasi" rows="3"
                                        placeholder="Tuliskan prestasi yang pernah diraih...">{{ old('prestasi', $user->prestasi) }}</textarea>
                                    @error('prestasi')
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
                                    value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Akun Aktif
                                </label>
                            </div>
                            <div class="form-text">Akun yang tidak aktif tidak dapat login ke sistem</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.user.index', ['role' => $user->role]) }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update {{ ucfirst($user->role) }}
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
