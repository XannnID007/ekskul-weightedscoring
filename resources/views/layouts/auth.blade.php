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
            overflow: hidden;
        }

        body {
            background: linear-gradient(135deg, var(--bs-gray-900) 0%, var(--bs-gray-800) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .auth-container {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 380px;
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
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .auth-header h3 {
            color: var(--bs-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.4rem;
        }

        .auth-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .auth-body {
            padding: 1.5rem;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
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
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
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
            font-size: 0.9rem;
        }

        .school-info {
            background: rgba(32, 178, 170, 0.1);
            border: 1px solid rgba(32, 178, 170, 0.3);
            border-radius: 0 0 16px 16px;
            padding: 0.75rem;
            text-align: center;
            font-size: 0.85rem;
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
                padding: 1.25rem;
            }

            .auth-header h3 {
                font-size: 1.2rem;
            }
        }

        @media (max-height: 600px) {
            .auth-container {
                align-items: flex-start;
                padding-top: 20px;
            }
        }
    </style>
</head>

<body>
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
                    <i class="bi bi-mortarboard-fill me-2"></i>
                    MiftahXCool
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
    </script>

    @stack('scripts')
</body>

</html>
