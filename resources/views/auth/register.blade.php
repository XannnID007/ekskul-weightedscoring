@extends('layouts.auth')

@section('title', 'Daftar Akun Siswa')

@section('content')
    <h4 class="text-center mb-4">
        <i class="bi bi-person-plus me-2 text-primary"></i>
        Daftar Akun Siswa
    </h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="bi bi-person me-1"></i>
                Nama Lengkap
            </label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Masukkan nama lengkap Anda">

            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- NIS Field -->
        <div class="mb-3">
            <label for="nis" class="form-label">
                <i class="bi bi-card-text me-1"></i>
                NIS (Nomor Induk Siswa)
            </label>
            <input id="nis" type="text" class="form-control @error('nis') is-invalid @enderror" name="nis"
                value="{{ old('nis') }}" required placeholder="Contoh: 2024001">
            <div class="form-text">Sesuai dengan NIS di kartu siswa Anda</div>

            @error('nis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i>
                Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" placeholder="nama@gmail.com">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="bi bi-lock me-1"></i>
                Password
            </label>
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-lock-fill me-1"></i>
                Konfirmasi Password
            </label>
            <div class="input-group">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password" placeholder="Ulangi password yang sama">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    Saya setuju dengan Syarat dan Ketentuan penggunaan sistem
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-person-plus me-2"></i>
                Daftar Sekarang
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-3">
            <span class="text-muted">Sudah punya akun? </span>
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="bi bi-box-arrow-in-right me-1"></i>
                Masuk di sini
            </a>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Sistem ekstrakurikuler MA Modern Miftahussa'adah
            </small>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            let strengthDiv = document.getElementById('password-strength');

            if (!strengthDiv) {
                strengthDiv = document.createElement('div');
                strengthDiv.id = 'password-strength';
                strengthDiv.className = 'mt-1';
                this.parentNode.parentNode.appendChild(strengthDiv);
            }

            let score = 0;
            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

            let feedback = '';
            let color = '';

            switch (score) {
                case 0:
                case 1:
                    feedback = 'Lemah';
                    color = 'danger';
                    break;
                case 2:
                case 3:
                    feedback = 'Sedang';
                    color = 'warning';
                    break;
                case 4:
                case 5:
                    feedback = 'Kuat';
                    color = 'success';
                    break;
            }

            if (password.length === 0) {
                strengthDiv.innerHTML = '';
            } else {
                strengthDiv.innerHTML = `<small class="text-${color}">Kekuatan password: ${feedback}</small>`;
            }
        });

        // Confirm password validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    </script>
@endpush
