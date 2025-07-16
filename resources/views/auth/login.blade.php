@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h5 class="text-center mb-4">
        <i class="bi bi-box-arrow-in-right me-2 text-primary"></i>
        Masuk ke Sistem
    </h5>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="bi bi-envelope me-1"></i>
                Email
            </label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Masukkan email Anda">

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
                    name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
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

        <!-- Remember Me -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-md">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Masuk
            </button>
        </div>

        <!-- Forgot Password Link -->
        @if (Route::has('password.request'))
            <div class="text-center mt-3">
                <a class="text-decoration-none" href="{{ route('password.request') }}">
                    <i class="bi bi-question-circle me-1"></i>
                    Lupa password?
                </a>
            </div>
        @endif

        <div class="text-center mt-3">
            <hr>
            <span class="text-muted">Belum punya akun? </span>
            <a href="{{ route('register') }}" class="text-decoration-none">
                <i class="bi bi-person-plus me-1"></i>
                Daftar sebagai siswa
            </a>
        </div>
    </form>

    @if (session('status'))
        <div class="alert alert-info mt-3" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
        </div>
    @endif
@endsection
