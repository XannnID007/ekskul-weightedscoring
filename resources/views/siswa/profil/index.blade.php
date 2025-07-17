@extends('layouts.app')

@section('title', 'Lengkapi Profil')
@section('page-title', 'Lengkapi Profil')
@section('page-description', 'Lengkapi profil Anda untuk mendapatkan rekomendasi ekstrakurikuler yang akurat')

@push('styles')
    <style>
        /* Custom Checkbox Card Styles */
        .interest-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .interest-item {
            position: relative;
        }

        .interest-checkbox {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .interest-label {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 60px;
        }

        .interest-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(32, 178, 170, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .interest-label:hover {
            border-color: var(--bs-primary);
            background: rgba(32, 178, 170, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(32, 178, 170, 0.2);
        }

        .interest-label:hover::before {
            left: 100%;
        }

        .interest-checkbox:checked+.interest-label {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            border-color: var(--bs-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 178, 170, 0.4);
        }

        .interest-icon {
            width: 24px;
            height: 24px;
            margin-right: 0.75rem;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .interest-checkbox:checked+.interest-label .interest-icon {
            opacity: 1;
            transform: scale(1.1);
        }

        .interest-text {
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .interest-checkbox:checked+.interest-label .interest-text {
            font-weight: 600;
        }

        /* Checkmark animation */
        .interest-label::after {
            content: '✓';
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%) scale(0);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .interest-checkbox:checked+.interest-label::after {
            transform: translateY(-50%) scale(1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .interest-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.75rem;
            }

            .interest-label {
                padding: 0.75rem 1rem;
                min-height: 50px;
            }

            .interest-text {
                font-size: 0.85rem;
            }
        }

        /* Form improvements */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .progress {
            transition: all 0.3s ease;
        }

        /* Section headers */
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(32, 178, 170, 0.2);
        }

        .section-header i {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        /* Enhanced card styling */
        .profile-card {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .profile-card .card-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            border-bottom: none;
        }

        /* Interest requirement info */
        .interest-info {
            background: rgba(32, 178, 170, 0.1);
            border: 1px solid rgba(32, 178, 170, 0.3);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .interest-info i {
            color: var(--bs-primary);
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Progress Card -->
            <div class="card mb-4">
                <div class="card-body">
                    @php
                        // Pastikan menggunakan logic yang sama dengan sidebar
                        $requiredFields = ['name', 'jenis_kelamin', 'tanggal_lahir', 'nilai_rata_rata'];
                        $filledFields = 0;

                        // Cek setiap field required
                        foreach ($requiredFields as $field) {
                            if (!empty($user->$field)) {
                                $filledFields++;
                            }
                        }

                        // Cek minat (khusus handling untuk array)
                        $userMinat = $user->minat_array; // Menggunakan accessor yang sudah ada
                        if (!empty($userMinat) && count($userMinat) > 0) {
                            $filledFields++;
                        }

                        $totalFields = count($requiredFields) + 1; // +1 untuk minat
                        $currentPercentage = round(($filledFields / $totalFields) * 100);

                        // Update data untuk tampilan
                        $profilCheck = [
                            'lengkap' => $currentPercentage == 100,
                            'persentase' => $currentPercentage,
                            'fields_kosong' => [],
                        ];

                        // Generate fields kosong untuk display
                        foreach ($requiredFields as $field) {
                            if (empty($user->$field)) {
                                $profilCheck['fields_kosong'][] = $field;
                            }
                        }

                        if (empty($userMinat) || count($userMinat) == 0) {
                            $profilCheck['fields_kosong'][] = 'minat';
                        }
                    @endphp

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Kelengkapan Profil</h6>
                        <span class="badge bg-{{ $currentPercentage == 100 ? 'success' : 'warning' }}" id="progress-badge">
                            {{ $currentPercentage }}%
                        </span>
                    </div>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar bg-{{ $currentPercentage == 100 ? 'success' : 'warning' }}"
                            id="progress-bar" style="width: {{ $currentPercentage }}%"></div>
                    </div>
                    @if (!$profilCheck['lengkap'])
                        <small class="text-muted" id="missing-fields">
                            Lengkapi data berikut:
                            @foreach ($profilCheck['fields_kosong'] as $field)
                                <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                            @endforeach
                        </small>
                    @else
                        <small class="text-success" id="complete-message">
                            <i class="bi bi-check-circle me-1"></i>Profil Anda sudah lengkap!
                        </small>
                    @endif
                </div>
            </div>

            <!-- Form Card -->
            <div class="card profile-card">
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
                        <div class="section-header">
                            <i class="bi bi-person"></i>
                            <h6 class="mb-0 text-primary">Data Pribadi</h6>
                        </div>

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
                        <div class="section-header">
                            <i class="bi bi-trophy"></i>
                            <h6 class="mb-0 text-primary">Data Akademik</h6>
                        </div>

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
                        <div class="section-header">
                            <i class="bi bi-heart"></i>
                            <h6 class="mb-0 text-primary">Minat & Hobi *</h6>
                        </div>

                        <div class="mb-4">
                            <div class="interest-info">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <span class="small">Pilih minimal 1 minat yang sesuai dengan Anda untuk mendapatkan
                                        rekomendasi ekstrakurikuler yang akurat</span>
                                </div>
                            </div>

                            <div class="interest-grid">
                                @php
                                    $icons = [
                                        'olahraga' => 'bi-trophy',
                                        'seni' => 'bi-palette',
                                        'akademik' => 'bi-book',
                                        'teknologi' => 'bi-laptop',
                                        'bahasa' => 'bi-translate',
                                        'kepemimpinan' => 'bi-star',
                                        'sosial' => 'bi-people',
                                        'musik' => 'bi-music-note',
                                        'tari' => 'bi-person-arms-up',
                                        'teater' => 'bi-mask',
                                        'jurnalistik' => 'bi-newspaper',
                                        'fotografi' => 'bi-camera',
                                        'memasak' => 'bi-egg-fried',
                                        'berkebun' => 'bi-flower1',
                                    ];
                                @endphp

                                @foreach ($minat_options as $key => $label)
                                    <div class="interest-item">
                                        <input class="interest-checkbox" type="checkbox" id="minat_{{ $key }}"
                                            name="minat[]" value="{{ $key }}"
                                            {{ in_array($key, old('minat', $user->minat_array)) ? 'checked' : '' }}>
                                        <label class="interest-label" for="minat_{{ $key }}">
                                            <i class="bi {{ $icons[$key] ?? 'bi-heart' }} interest-icon"></i>
                                            <span class="interest-text">{{ $label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('minat')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keamanan -->
                        <div class="section-header">
                            <i class="bi bi-shield-lock"></i>
                            <h6 class="mb-0 text-primary">Ubah Password (Opsional)</h6>
                        </div>

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
            const progressBar = document.querySelector('#progress-bar');
            const progressBadge = document.querySelector('#progress-badge');

            progressBar.style.width = progress + '%';
            progressBadge.textContent = progress + '%';

            if (progress === 100) {
                progressBar.className = 'progress-bar bg-success';
                progressBadge.className = 'badge bg-success';

                // Update missing fields display
                const missingFields = document.querySelector('#missing-fields');
                const completeMessage = document.querySelector('#complete-message');

                if (missingFields) missingFields.style.display = 'none';
                if (!completeMessage) {
                    const completeHTML =
                        '<small class="text-success" id="complete-message"><i class="bi bi-check-circle me-1"></i>Profil Anda sudah lengkap!</small>';
                    progressBar.parentElement.parentElement.insertAdjacentHTML('beforeend', completeHTML);
                } else {
                    completeMessage.style.display = 'block';
                }
            } else {
                progressBar.className = 'progress-bar bg-warning';
                progressBadge.className = 'badge bg-warning';

                // Update missing fields
                const missingFields = document.querySelector('#missing-fields');
                const completeMessage = document.querySelector('#complete-message');

                if (completeMessage) completeMessage.style.display = 'none';
                if (missingFields) missingFields.style.display = 'block';
            }
        }

        // Add event listeners for real-time progress
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', updateProgress);
            element.addEventListener('change', updateProgress);
        });

        // Initial progress update
        updateProgress();

        // Add subtle animation when checkbox is clicked
        document.querySelectorAll('.interest-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.nextElementSibling;
                if (this.checked) {
                    label.style.transform = 'translateY(-2px) scale(1.02)';
                    setTimeout(() => {
                        label.style.transform = 'translateY(-2px)';
                    }, 200);
                }

                // Update progress when interest is changed
                updateProgress();
            });
        });
    </script>
@endpush
