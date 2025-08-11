<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - MA Modern Miftahussa'adah</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #20b2aa;
            --bs-primary-dark: #17a2b8;
            --bs-gray-900: #1a1d20;
            --bs-gray-800: #343a40;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow-y: auto;
        }

        body {
            background: linear-gradient(135deg, var(--bs-gray-900) 0%, var(--bs-gray-800) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 100vh;
            padding: 20px 0;
        }

        /* Logo Sekolah di pojok kanan atas */
        .school-logo {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .school-logo:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .school-logo img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid rgba(32, 178, 170, 0.5);
            transition: all 0.3s ease;
        }

        .school-logo:hover img {
            border-color: var(--bs-primary);
            transform: scale(1.05);
        }

        .school-logo-text {
            position: absolute;
            right: 70px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            white-space: nowrap;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .school-logo:hover .school-logo-text {
            opacity: 1;
            transform: translateY(-50%) translateX(-10px);
        }

        .auth-container {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
        }

        .auth-header {
            text-align: center;
            padding: 1rem 1.25rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .auth-header h3 {
            color: var(--bs-primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 1.25rem;
        }

        .auth-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        .auth-body {
            padding: 1.25rem;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-secondary {
            border-color: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-outline-secondary:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(32, 178, 170, 0.3);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        .school-info {
            background: rgba(32, 178, 170, 0.1);
            border: 1px solid rgba(32, 178, 170, 0.3);
            border-radius: 0 0 16px 16px;
            padding: 0.625rem;
            text-align: center;
            font-size: 0.8rem;
        }

        .school-info small {
            color: rgba(255, 255, 255, 0.7);
        }

        .text-decoration-none {
            color: var(--bs-primary);
            transition: color 0.3s ease;
        }

        .text-decoration-none:hover {
            color: var(--bs-primary-dark);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--bs-primary), var(--bs-primary-dark));
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-shape:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 15%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @media (max-width: 576px) {
            .auth-container {
                padding: 15px;
            }

            .auth-card {
                max-width: none;
            }

            .auth-header,
            .auth-body {
                padding: 1rem;
            }

            .auth-header h3 {
                font-size: 1.1rem;
            }

            .row .col-md-6 {
                margin-bottom: 0.75rem;
            }

            .school-logo {
                top: 15px;
                right: 15px;
            }

            .school-logo img {
                width: 40px;
                height: 40px;
            }

            .school-logo-text {
                display: none;
                /* Hide text on mobile */
            }
        }

        @media (max-height: 700px) {
            .auth-container {
                align-items: flex-start;
                padding-top: 20px;
            }
        }

        /* Animation untuk logo */
        @keyframes logoGlow {

            0%,
            100% {
                box-shadow: 0 0 10px rgba(32, 178, 170, 0.3);
            }

            50% {
                box-shadow: 0 0 20px rgba(32, 178, 170, 0.6);
            }
        }

        .school-logo img {
            animation: logoGlow 3s ease-in-out infinite;
        }
    </style>
</head>

<body>
    <!-- Logo Sekolah di Pojok Kanan Atas -->
    <div class="school-logo">
        <img src="{{ asset('img/logo.jpeg') }}" alt="Logo MA Modern Miftahussa'adah">
        <div class="school-logo-text">
            MA Modern Miftahussa'adah
        </div>
    </div>

    <!-- Floating Background Shapes -->
    <div class="floating-shapes">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-card fade-in">
            <!-- Auth Header -->
            <div class="auth-header">
                <h3>
                    MiftahXKull
                </h3>
                <p>MA Modern Miftahussa'adah</p>
            </div>

            <!-- Auth Body -->
            <div class="auth-body">
                @yield('content')
            </div>

            <!-- School Info -->
            <div class="school-info">
                <small>
                    <i class="bi bi-info-circle me-1"></i>
                    Sistem Manajemen Ekstrakurikuler Modern
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Add loading state to login button
        const loginForm = document.querySelector('form');
        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
                    submitBtn.disabled = true;
                }
            });
        }

        // Show password toggle
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling?.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                if (icon) icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                if (icon) icon.className = 'bi bi-eye';
            }
        }

        // Logo click effect
        document.querySelector('.school-logo').addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });

        // Easter egg: Double click logo
        let logoClickCount = 0;
        document.querySelector('.school-logo').addEventListener('dblclick', function() {
            // Create welcome message
            const message = document.createElement('div');
            message.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
                color: white;
                padding: 20px 30px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 9999;
                text-align: center;
                animation: fadeIn 0.5s ease;
                font-weight: 500;
            `;
            message.innerHTML = `
                <i class="bi bi-heart-fill text-danger me-2"></i>
                Selamat datang di MiftahXKull!
                <br><small>Sistem Ekstrakurikuler Modern</small>
            `;
            document.body.appendChild(message);

            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            }, 2000);
        });
    </script>

    @stack('scripts')
</body>

</html>
