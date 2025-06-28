@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
    <h4 class="text-center mb-4">
        <i class="bi bi-person-plus me-2 text-primary"></i>
        Daftar Akun Baru
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
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i>
                Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" placeholder="Masukkan alamat email">

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
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
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="password-confirm" class="form-label">
                <i class="bi bi-lock-fill me-1"></i>
                Konfirmasi Password
            </label>
            <div class="input-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password" placeholder="Ulangi password yang sama">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password-confirm')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- Terms Agreement -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    Saya setuju dengan
                    <a href="#" class="text-decoration-none">Syarat dan Ketentuan</a>
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
                Pendaftaran hanya untuk siswa. Admin dan pembina didaftarkan oleh sistem.
            </small>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strength = document.getElementById('password-strength');

            if (!strength) {
                const strengthDiv = document.createElement('div');
                strengthDiv.id = 'password-strength';
                strengthDiv.className = 'mt-1';
                this.parentNode.parentNode.appendChild(strengthDiv);
            }

            let score = 0;
            let feedback = '';
            let color = '';

            if (password.length >= 8) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;

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
                document.getElementById('password-strength').innerHTML = '';
            } else {
                document.getElementById('password-strength').innerHTML =
                    `<small class="text-${color}">Kekuatan password: ${feedback}</small>`;
            }
        });

        // Confirm password validation
        document.getElementById('password-confirm').addEventListener('input', function() {
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
