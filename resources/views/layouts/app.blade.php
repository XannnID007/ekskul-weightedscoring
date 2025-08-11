<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ekstrakurikuler App') - MA Modern Miftahussa'adah</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        body {
            background-color: var(--bs-gray-100);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--bs-gray-800);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--bs-white);
            z-index: 1000;
            transition: all 0.3s ease;
            border-right: 1px solid var(--bs-gray-200);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--bs-gray-200);
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
        }

        .sidebar-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }

        .sidebar-header small {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
            background: var(--bs-white);
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--bs-gray-600);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
            margin: 0 0.5rem;
            border-radius: 0.5rem;
            border-left: none;
        }

        .sidebar-menu .nav-link:hover {
            background-color: var(--bs-gray-100);
            color: var(--bs-primary);
            transform: translateX(5px);
        }

        .sidebar-menu .nav-link.active {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .sidebar-menu .nav-link.active:hover {
            transform: translateX(0);
        }

        .sidebar-menu .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-section-header {
            margin-top: 1.5rem;
            padding: 0 1.5rem;
        }

        .sidebar-section-header:first-child {
            margin-top: 0;
        }

        .sidebar-section-header small {
            color: var(--bs-gray-400);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.7rem;
        }

        .nav-link .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
            margin-left: auto;
        }

        .sidebar-menu .nav-link:hover .badge {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        @keyframes pulse-badge {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .badge.bg-warning {
            animation: pulse-badge 2s infinite;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background-color: var(--bs-gray-100);
        }

        /* Navbar */
        .top-navbar {
            background: var(--bs-white);
            border-bottom: 1px solid var(--bs-gray-200);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1020;
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: var(--bs-white);
            border: 1px solid var(--bs-gray-200);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--bs-gray-50);
            border-bottom: 1px solid var(--bs-gray-200);
            font-weight: 600;
            color: var(--bs-gray-700);
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--bs-success) 0%, #059669 100%);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, var(--bs-warning) 0%, #d97706 100%);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--bs-danger) 0%, #dc2626 100%);
        }

        .stats-card.info {
            background: linear-gradient(135deg, var(--bs-info) 0%, #0891b2 100%);
        }

        .stats-card .stats-icon {
            font-size: 2.5rem;
            opacity: 0.9;
            z-index: 2;
            position: relative;
        }

        .stats-card .card-body {
            position: relative;
            z-index: 2;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-outline-primary {
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        /* Tables */
        .table {
            --bs-table-bg: var(--bs-white);
            --bs-table-striped-bg: var(--bs-gray-50);
            --bs-table-hover-bg: var(--bs-gray-100);
        }

        /* Forms */
        .form-control,
        .form-select {
            background-color: var(--bs-white);
            border: 1px solid var(--bs-gray-300);
            color: var(--bs-gray-700);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--bs-white);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
            color: var(--bs-gray-700);
        }

        .form-label {
            color: var(--bs-gray-700);
            font-weight: 500;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            z-index: 1010;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50px, -50px);
        }

        .page-header h2,
        .page-header p {
            position: relative;
            z-index: 2;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-left-color: var(--bs-success);
            color: #047857;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-left-color: var(--bs-danger);
            color: #dc2626;
        }

        .alert-warning {
            background-color: rgba(245, 158, 11, 0.1);
            border-left-color: var(--bs-warning);
            color: #d97706;
        }

        .alert-info {
            background-color: rgba(6, 182, 212, 0.1);
            border-left-color: var(--bs-info);
            color: #0891b2;
        }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid var(--bs-gray-200);
            border-radius: 10px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            background: var(--bs-white);
            animation: fadeInDown 0.3s ease-out;
            z-index: 1025;
            position: absolute;
            top: 100%;
            left: auto;
            right: 0;
            min-width: 200px;
        }

        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.5em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }

        .dropdown-toggle:empty::after {
            margin-left: 0;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            color: var(--bs-gray-700);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin: 0.25rem 0.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--bs-gray-100);
            color: var(--bs-primary);
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--bs-danger);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: var(--bs-white);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid var(--bs-gray-200);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: var(--bs-gray-400);
        }

        .breadcrumb-item.active {
            color: var(--bs-primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 1rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bs-gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bs-gray-400);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--bs-gray-500);
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
            border-radius: 6px;
            font-weight: 500;
        }

        /* Custom Badge Colors */
        .badge.bg-female {
            background-color: #ec4899 !important;
            color: white !important;
        }

        .badge.bg-male {
            background-color: var(--bs-primary) !important;
            color: white !important;
        }

        .badge.bg-pink {
            background-color: #ec4899 !important;
            color: white !important;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Data Table Customization */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--bs-gray-300);
            border-radius: 6px;
            padding: 0.375rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--bs-primary) !important;
            color: white !important;
            border: 1px solid var(--bs-primary) !important;
            border-radius: 6px !important;
        }

        /* Navigation Link Info Box */
        .nav-item .bg-primary {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%) !important;
            border: 1px solid rgba(79, 70, 229, 0.2);
        }

        /* Sidebar Divider */
        .sidebar hr.sidebar-divider {
            border-color: var(--bs-gray-200);
            margin: 1rem 1.5rem;
        }

        /* Success/Error Messages Improvements */
        .swal2-popup {
            border-radius: 12px !important;
        }

        .swal2-success .swal2-success-ring {
            border-color: var(--bs-success) !important;
        }

        .swal2-error .swal2-error-line {
            background-color: var(--bs-danger) !important;
        }

        /* Loading States */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Hover Effects for Interactive Elements */
        .clickable {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .clickable:hover {
            transform: translateY(-1px);
        }

        /* Profile Image Styling */
        .avatar {
            border: 2px solid var(--bs-white);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Progress Bars */
        .progress {
            border-radius: 6px;
            background-color: var(--bs-gray-200);
        }

        .progress-bar {
            border-radius: 6px;
        }

        /* Status Indicators */
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .status-indicator.online {
            background-color: var(--bs-success);
        }

        .status-indicator.offline {
            background-color: var(--bs-gray-400);
        }

        .status-indicator.pending {
            background-color: var(--bs-warning);
        }
    </style>

    @stack('styles')
</head>

<body>
    @auth
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h4>MiftahXCool</h4>
                <small>MA Modern Miftahussa'adah</small>
            </div>

            <div class="sidebar-menu">
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.ekstrakurikuler.index') }}"
                        class="nav-link {{ request()->routeIs('admin.ekstrakurikuler.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>
                        Kelola Ekstrakurikuler
                    </a>
                    <a href="{{ route('admin.user.index', ['role' => 'siswa']) }}"
                        class="nav-link {{ request()->routeIs('admin.user.*') && request()->get('role') === 'siswa' ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.laporan') }}"
                        class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        Laporan
                    </a>
                @elseif(auth()->user()->role === 'pembina')
                    <a href="{{ route('pembina.dashboard') }}"
                        class="nav-link {{ request()->routeIs('pembina.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('pembina.pendaftaran.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.pendaftaran.*') ? 'active' : '' }}">
                        <i class="bi bi-person-plus"></i>
                        Kelola Pendaftaran
                    </a>
                    <a href="{{ route('pembina.siswa.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.siswa.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Data Siswa
                    </a>
                    <a href="{{ route('pembina.pengumuman.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.pengumuman.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        Pengumuman
                    </a>
                    <a href="{{ route('pembina.galeri.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.galeri.*') ? 'active' : '' }}">
                        <i class="bi bi-images"></i>
                        Galeri Kegiatan
                    </a>
                @else
                    <a href="{{ route('siswa.dashboard') }}"
                        class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('siswa.profil') }}"
                        class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        Lengkapi Profil
                    </a>

                    @if (!auth()->user()->sudahTerdaftarEkstrakurikuler())
                        <div class="sidebar-section-header">
                            <small>CARI EKSTRAKURIKULER</small>
                        </div>

                        <a href="{{ route('siswa.rekomendasi') }}"
                            class="nav-link {{ request()->routeIs('siswa.rekomendasi*') ? 'active' : '' }}">
                            <i class="bi bi-stars"></i>
                            Rekomendasi
                            <span class="badge bg-primary">AI</span>
                        </a>

                        <a href="{{ route('siswa.ekstrakurikuler.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') ? 'active' : '' }}">
                            <i class="bi bi-collection"></i>
                            Ekstrakurikuler
                        </a>
                    @else
                        @php
                            $pendaftaran = auth()
                                ->user()
                                ->pendaftarans()
                                ->where('status', 'disetujui')
                                ->with('ekstrakurikuler')
                                ->first();
                            $ekstrakurikuler = $pendaftaran ? $pendaftaran->ekstrakurikuler : null;
                        @endphp

                        <div class="sidebar-section-header">
                            <small>KEGIATAN SAYA</small>
                        </div>

                        @if ($ekstrakurikuler)
                            <div class="nav-item px-3 py-2 mb-2">
                                <div class="bg-light border rounded p-2">
                                    <div class="d-flex align-items-center">
                                        @if ($ekstrakurikuler->gambar)
                                            <img src="{{ Storage::url($ekstrakurikuler->gambar) }}"
                                                alt="{{ $ekstrakurikuler->nama }}" class="rounded me-2" width="32"
                                                height="32" style="object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded d-flex align-items-center justify-content-center me-2"
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-collection text-white small"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small text-primary">{{ $ekstrakurikuler->nama }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ $ekstrakurikuler->pembina->name ?? 'Pembina' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('siswa.jadwal') }}"
                            class="nav-link {{ request()->routeIs('siswa.jadwal*') ? 'active' : '' }}">
                            <i class="bi bi-calendar3"></i>
                            Jadwal Kegiatan
                        </a>

                        <a href="{{ route('siswa.galeri.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.galeri*') ? 'active' : '' }}">
                            <i class="bi bi-images"></i>
                            Galeri Kegiatan
                        </a>

                        <a href="{{ route('siswa.pengumuman.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.pengumuman*') ? 'active' : '' }}">
                            <i class="bi bi-megaphone"></i>
                            Pengumuman
                        </a>

                        <hr class="sidebar-divider">

                        <div class="sidebar-section-header">
                            <small>LAINNYA</small>
                        </div>

                        <a href="{{ route('siswa.ekstrakurikuler.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') ? 'active' : '' }}">
                            <i class="bi bi-eye"></i>
                            Lihat Ekstrakurikuler Lain
                        </a>
                    @endif

                    <a href="{{ route('siswa.pendaftaran') }}"
                        class="nav-link {{ request()->routeIs('siswa.pendaftaran') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        Status Pendaftaran
                        @php
                            $pendingCount = auth()->user()->pendaftarans()->where('status', 'pending')->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span class="badge bg-warning">{{ $pendingCount }}</span>
                        @endif
                    </a>
                @endif
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg top-navbar">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-lg-none" type="button" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="navbar-nav ms-auto">
                        <!-- User Dropdown -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark d-flex align-items-center" href="#"
                                id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-2"></i>
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <h6 class="dropdown-header">
                                        <i class="bi bi-person-badge me-1"></i>
                                        {{ ucfirst(auth()->user()->role) }}
                                    </h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                @if (auth()->user()->role === 'siswa')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('siswa.profil') }}">
                                            <i class="bi bi-person-gear me-2"></i>Profil Saya
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif

                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Page Header -->
                <div class="page-header fade-in">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">@yield('page-title', 'Dashboard')</h2>
                            <p class="mb-0 opacity-75">@yield('page-description', 'Selamat datang di sistem manajemen ekstrakurikuler')</p>
                        </div>
                        <div>
                            @yield('page-actions')
                        </div>
                    </div>
                </div>

                <!-- Breadcrumb -->
                @if (isset($breadcrumbs))
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $breadcrumb)
                                @if ($loop->last)
                                    <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                                @else
                                    <li class="breadcrumb-item">
                                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                @endif

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Main Content -->
                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </div>
    @else
        <!-- Auth Layout -->
        <div class="auth-container">
            <div class="auth-card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="text-primary"><i class="bi bi-mortarboard-fill me-2"></i>EkstrakurikulerApp</h4>
                        <small class="text-muted">MA Modern Miftahussa'adah</small>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    @endauth

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Toggle Sidebar for mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Initialize DataTables
        $(document).ready(function() {
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                },
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
            });
        });

        // Auto dismiss alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete
        function confirmDelete(url, title = 'Apakah Anda yakin?') {
            Swal.fire({
                title: title,
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                color: '#1e293b'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // Success notification
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                background: '#ffffff',
                color: '#1e293b'
            });
        }

        // Error notification
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message,
                background: '#ffffff',
                color: '#1e293b'
            });
        }

        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function confirmLogout(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i>Ya, Logout',
                cancelButtonText: '<i class="bi bi-x-lg me-1"></i>Batal',
                reverseButtons: true,
                background: '#ffffff',
                color: '#1e293b',
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve();
                        }, 500);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Logout Berhasil',
                        text: 'Anda telah keluar dari sistem. Terima kasih!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        background: '#ffffff',
                        color: '#1e293b'
                    }).then(() => {
                        document.getElementById('logout-form').submit();
                    });
                }
            });
        }

        // Card hover animations
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (!this.classList.contains('stats-card')) {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                }
            });

            card.addEventListener('mouseleave', function() {
                if (!this.classList.contains('stats-card')) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.05)';
                }
            });
        });

        // Smooth scroll for sidebar links
        document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                // Add smooth transition effect
                this.style.transition = 'all 0.3s ease';
            });
        });

        // Auto close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.querySelector('.btn[onclick="toggleSidebar()"]');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + Shift + L for logout
            if (e.ctrlKey && e.shiftKey && e.key === 'L') {
                e.preventDefault();
                confirmLogout(e);
            }

            // Escape key to close mobile sidebar
            if (e.key === 'Escape' && window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('show');
            }
        });

        // Page loading animation
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');

            // Animate stats cards
            const statsCards = document.querySelectorAll('.stats-card');
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, observerOptions);

        // Observe all cards for animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card:not(.stats-card)');
            cards.forEach(card => {
                observer.observe(card);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
