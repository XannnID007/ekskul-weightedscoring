<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - MA Modern Miftahussa'adah</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #3c9ae7;
            --bs-primary-dark: #3362e4;
            --bs-primary-light: #63d0f1;
            --bs-secondary: #64748b;
            --bs-success: #10b981;
            --bs-info: #06b6d4;
            --bs-warning: #f59e0b;
            --bs-danger: #ef4444;
            --bs-light: #f8fafc;
            --bs-white: #ffffff;
            --bs-gray-50: #f9fafb;
            --bs-gray-100: #f1f5f9;
            --bs-gray-200: #e2e8f0;
            --bs-gray-300: #cbd5e1;
            --bs-gray-400: #94a3b8;
            --bs-gray-500: #64748b;
            --bs-gray-600: #475569;
            --bs-gray-700: #334155;
            --bs-gray-800: #1e293b;
            --bs-gray-900: #0f172a;
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
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bs-gray-50) 0%, var(--bs-gray-100) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 100vh;
            padding: 20px 0;
            color: var(--bs-gray-800);
        }

        /* Background Decoration */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 20%, rgba(79, 70, 229, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 70% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(6, 182, 212, 0.05) 0%, transparent 50%);
            z-index: -1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        /* Logo Sekolah di pojok kanan atas */
        .school-logo {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--bs-white);
            border: 1px solid var(--bs-gray-200);
            border-radius: 16px;
            padding: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .school-logo:hover {
            background: var(--bs-gray-50);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .school-logo img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid var(--bs-primary);
            transition: all 0.3s ease;
        }

        .school-logo:hover img {
            border-color: var(--bs-primary-dark);
            transform: scale(1.05);
        }

        .school-logo-text {
            position: absolute;
            right: 80px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: all 0.3s ease;
            background: var(--bs-white);
            color: var(--bs-gray-700);
            padding: 10px 15px;
            border-radius: 10px;
            border: 1px solid var(--bs-gray-200);
            white-space: nowrap;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            z-index: 10;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: var(--bs-white);
            border: 1px solid var(--bs-gray-200);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-primary-light) 50%, var(--bs-success) 100%);
        }

        .auth-header {
            text-align: center;
            padding: 2rem 1.5rem 1rem;
            background: var(--bs-white);
            position: relative;
        }

        .auth-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%);
            border-radius: 2px;
        }

        .auth-header h3 {
            color: var(--bs-gray-800);
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            position: relative;
        }

        .auth-header h3 i {
            color: var(--bs-primary);
            margin-right: 0.5rem;
        }

        .auth-header p {
            color: var(--bs-gray-500);
            font-size: 0.9rem;
            margin-bottom: 0;
            font-weight: 400;
        }

        .auth-body {
            padding: 1.5rem;
            background: var(--bs-white);
        }

        .form-label {
            color: var(--bs-gray-700);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            color: var(--bs-primary);
            margin-right: 0.5rem;
        }

        .form-control {
            background-color: var(--bs-gray-50);
            border: 2px solid var(--bs-gray-200);
            color: var(--bs-gray-800);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-control:focus {
            background-color: var(--bs-white);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
            color: var(--bs-gray-800);
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: var(--bs-gray-400);
            font-weight: 400;
        }

        .input-group {
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group .form-control {
            border-radius: 10px 0 0 10px;
            border-right: none;
        }

        .input-group-text {
            background-color: var(--bs-gray-100);
            border: 2px solid var(--bs-gray-200);
            border-left: none;
            color: var(--bs-gray-500);
            border-radius: 0 10px 10px 0;
            transition: all 0.3s ease;
        }

        .input-group:focus-within .input-group-text {
            background-color: var(--bs-white);
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        .btn-outline-secondary {
            border-color: var(--bs-gray-200);
            color: var(--bs-gray-500);
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background-color: var(--bs-gray-100);
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
            border-left: 4px solid var(--bs-success);
            color: #047857;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
            border-left: 4px solid var(--bs-danger);
            color: #dc2626;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);
            border-left: 4px solid var(--bs-info);
            color: #0891b2;
        }

        .form-check-input {
            border: 2px solid var(--bs-gray-300);
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        .form-check-label {
            color: var(--bs-gray-600);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .school-info {
            background: linear-gradient(135deg, var(--bs-gray-50) 0%, var(--bs-gray-100) 100%);
            border-top: 1px solid var(--bs-gray-200);
            padding: 1rem;
            text-align: center;
            font-size: 0.85rem;
            border-radius: 0 0 20px 20px;
        }

        .school-info small {
            color: var(--bs-gray-500);
            font-weight: 500;
        }

        .school-info i {
            color: var(--bs-primary);
        }

        .text-decoration-none {
            color: var(--bs-primary);
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .text-decoration-none:hover {
            color: var(--bs-primary-dark);
            text-decoration: underline !important;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
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

        /* Floating Shapes */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
            animation: floatShape 15s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            background: linear-gradient(45deg, var(--bs-primary), var(--bs-primary-light));
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            right: 10%;
            background: linear-gradient(45deg, var(--bs-success), var(--bs-info));
            animation-delay: 5s;
        }

        .floating-shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            background: linear-gradient(45deg, var(--bs-info), var(--bs-primary-light));
            animation-delay: 10s;
        }

        @keyframes floatShape {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.4;
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.6;
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .auth-container {
                padding: 15px;
            }

            .auth-card {
                max-width: none;
                margin: 0 10px;
            }

            .auth-header {
                padding: 1.5rem 1rem 1rem;
            }

            .auth-body {
                padding: 1rem;
            }

            .auth-header h3 {
                font-size: 1.3rem;
            }

            .row .col-md-6 {
                margin-bottom: 1rem;
            }

            .school-logo {
                top: 15px;
                right: 15px;
                padding: 8px;
            }

            .school-logo img {
                width: 40px;
                height: 40px;
            }

            .school-logo-text {
                display: none;
            }
        }

        @media (max-height: 700px) {
            .auth-container {
                align-items: flex-start;
                padding-top: 20px;
            }

            body {
                padding: 10px 0;
            }
        }

        /* Form Animation */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control:focus+.form-label,
        .form-control:not(:placeholder-shown)+.form-label {
            transform: translateY(-1.5rem) scale(0.85);
            color: var(--bs-primary);
        }

        /* Loading Animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: loading 2s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Password Strength Indicator */
        .password-strength {
            height: 4px;
            background-color: var(--bs-gray-200);
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }

        .password-strength-weak {
            background-color: var(--bs-danger);
            width: 33%;
        }

        .password-strength-medium {
            background-color: var(--bs-warning);
            width: 66%;
        }

        .password-strength-strong {
            background-color: var(--bs-success);
            width: 100%;
        }

        /* Validation States */
        .form-control.is-valid {
            border-color: var(--bs-success);
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.15);
        }

        .form-control.is-invalid {
            border-color: var(--bs-danger);
            box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.15);
        }

        .valid-feedback {
            color: var(--bs-success);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .invalid-feedback {
            color: var(--bs-danger);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Subtle animations */
        .auth-card {
            animation: cardSlideIn 0.6s ease-out;
        }

        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .form-control,
        .btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Focus Ring Improvements */
        .form-control:focus,
        .btn:focus,
        .form-check-input:focus {
            outline: none;
        }

        /* Terms checkbox styling */
        .form-check {
            padding-left: 1.5rem;
        }

        .form-check-input {
            margin-left: -1.5rem;
            margin-top: 0.125rem;
        }

        /* Link hover effects */
        a:not(.btn) {
            position: relative;
            transition: all 0.3s ease;
        }

        a:not(.btn):hover {
            transform: translateY(-1px);
        }

        /* Custom HR styling */
        hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--bs-gray-300), transparent);
            margin: 1.5rem 0;
        }

        /* Social Login Buttons (if needed in future) */
        .btn-social {
            border: 2px solid var(--bs-gray-200);
            background: var(--bs-white);
            color: var(--bs-gray-700);
            border-radius: 10px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            border-color: var(--bs-primary);
            background: var(--bs-gray-50);
            color: var(--bs-primary);
            transform: translateY(-2px);
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
                    <i class="bi bi-mortarboard-fill"></i>
                    MiftahXKull
                </h3>
                <p>Sistem Ekstrakurikuler Modern</p>
            </div>

            <!-- Auth Body -->
            <div class="auth-body">
                @yield('content')
            </div>

            <!-- School Info -->
            <div class="school-info">
                <small>
                    <i class="bi bi-shield-check me-2"></i>
                    MA Modern Miftahussa'adah - Platform Pembelajaran Digital
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
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Add loading state to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('loading');
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Show password toggle functionality
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

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus effects to form groups
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                const parent = control.closest('.form-group') || control.parentElement;

                control.addEventListener('focus', function() {
                    parent.classList.add('focused');
                });

                control.addEventListener('blur', function() {
                    parent.classList.remove('focused');
                });
            });

            // Animate form elements on load
            const formElements = document.querySelectorAll('.form-group, .btn, .alert');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 100 * index);
            });

            // Real-time validation feedback
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.addEventListener('input', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (this.value && emailRegex.test(this.value)) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            // Confirm password validation
            const confirmPasswordField = document.getElementById('password_confirmation');
            if (confirmPasswordField && passwordField) {
                confirmPasswordField.addEventListener('input', function() {
                    const password = passwordField.value;
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
            }
        });

        // Easter egg: Konami code
        let konamiCode = [];
        const correctKonami = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft',
            'ArrowRight', 'KeyB', 'KeyA'
        ];

        document.addEventListener('keydown', function(e) {
            konamiCode.push(e.code);
            konamiCode = konamiCode.slice(-10);

            if (konamiCode.join(',') === correctKonami.join(',')) {
                // Easter egg activation
                document.body.style.animation = 'rainbow 2s infinite';
                setTimeout(() => {
                    document.body.style.animation = '';
                }, 2000);
            }
        });

        // Particle effect on successful actions
        function createParticles(element) {
            for (let i = 0; i < 10; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: var(--bs-primary);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 9999;
                `;

                const rect = element.getBoundingClientRect();
                particle.style.left = rect.left + rect.width / 2 + 'px';
                particle.style.top = rect.top + rect.height / 2 + 'px';

                document.body.appendChild(particle);

                const angle = (i / 10) * Math.PI * 2;
                const velocity = 100;
                const vx = Math.cos(angle) * velocity;
                const vy = Math.sin(angle) * velocity;

                particle.animate([{
                        transform: 'translate(0, 0) scale(1)',
                        opacity: 1
                    },
                    {
                        transform: `translate(${vx}px, ${vy}px) scale(0)`,
                        opacity: 0
                    }
                ], {
                    duration: 1000,
                    easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
                }).onfinish = () => particle.remove();
            }
        }

        // Accessibility improvements
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Add CSS for keyboard navigation
        const style = document.createElement('style');
        style.textContent = `
            .keyboard-navigation *:focus {
                outline: 2px solid var(--bs-primary) !important;
                outline-offset: 2px !important;
            }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
</body>

</html>
