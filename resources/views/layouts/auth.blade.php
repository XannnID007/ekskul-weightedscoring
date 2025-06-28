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

        body {
            background: linear-gradient(135deg, var(--bs-gray-900) 0%, var(--bs-gray-800) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 360px;
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
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

        .auth-body {
            padding: 1.5rem;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(108, 66, 193, 0.25);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
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

        .school-info {
            background: rgba(32, 178, 170, 0.1);
            border: 1px solid rgba(32, 178, 170, 0.3);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 1rem;
            text-align: center;
        }

        .demo-accounts {
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .demo-accounts .demo-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .demo-accounts .demo-item:last-child {
            border-bottom: none;
        }

        .demo-accounts .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
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
            z-index: -1;
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
            .auth-card {
                margin: 1rem;
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="auth-card fade-in">
                    <!-- Auth Header -->
                    <div class="auth-header">
                        <h3>
                            MiftahXCool
                        </h3>
                        <p class="text-muted mb-0">MA Modern Miftahussa'adah</p>
                    </div>

                    <!-- Auth Body -->
                    <div class="auth-body">
                        @yield('content')
                    </div>

                    <!-- School Info -->
                    <div class="school-info">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Sistem Manajemen Ekstrakurikuler Modern
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto dismiss alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
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
