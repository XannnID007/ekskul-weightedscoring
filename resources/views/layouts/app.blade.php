<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

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
            --bs-primary: #20b2aa;
            --bs-primary-dark: #17a2b8;
            --bs-primary-light: #4dd0e1;
            --bs-secondary: #6c757d;
            --bs-success: #20c997;
            --bs-info: #0dcaf0;
            --bs-warning: #ffc107;
            --bs-danger: #dc3545;
            --bs-dark: #212529;
            --bs-gray-900: #1a1d20;
            --bs-gray-800: #343a40;
        }

        body {
            background-color: var(--bs-gray-900);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, var(--bs-dark) 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            color: var(--bs-primary);
            font-weight: 600;
            margin: 0;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #e9ecef;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu .nav-link:hover {
            background-color: rgba(32, 178, 170, 0.1);
            color: var(--bs-primary);
            border-left-color: var(--bs-primary);
        }

        .sidebar-menu .nav-link.active {
            background-color: rgba(32, 178, 170, 0.2);
            color: var(--bs-primary);
            border-left-color: var(--bs-primary);
        }

        .sidebar-menu .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-section-header {
            margin-top: 1rem;
        }

        .sidebar-section-header:first-child {
            margin-top: 0;
        }

        .nav-link .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
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
            background-color: var(--bs-gray-900);
        }

        /* Navbar */
        .top-navbar {
            background: linear-gradient(135deg, var(--bs-dark) 0%, var(--bs-gray-800) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, var(--bs-dark) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            border: none;
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--bs-success) 0%, #10b981 100%);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, var(--bs-warning) 0%, #f59e0b 100%);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--bs-danger) 0%, #ef4444 100%);
        }

        .stats-card .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 100%);
            transform: translateY(-1px);
        }

        /* Tables */
        .table-dark {
            --bs-table-bg: var(--bs-gray-800);
            --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
        }

        /* Forms */
        .form-control,
        .form-select {
            background-color: var(--bs-gray-800);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--bs-gray-800);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
            color: #fff;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
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
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bs-gray-800);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
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

        /* Auth Layout Styles */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bs-gray-900) 0%, var(--bs-gray-800) 100%);
        }

        .auth-card {
            width: 100%;
            max-width: 400px;
            background: var(--bs-gray-800);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logout-confirm-popup {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 16px !important;
            color: #fff !important;
        }

        .logout-confirm-title {
            color: #fff !important;
            font-weight: 600 !important;
        }

        .logout-confirm-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 0.5rem 1.25rem !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .logout-confirm-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4) !important;
        }

        .logout-cancel-btn {
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #fff !important;
            border-radius: 8px !important;
            padding: 0.5rem 1.25rem !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .logout-cancel-btn:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-2px) !important;
        }

        .logout-success-popup {
            background: linear-gradient(135deg, var(--bs-success) 0%, #198754 100%) !important;
            color: #fff !important;
            border-radius: 16px !important;
        }

        /* Dropdown item hover effect */
        .dropdown-item:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
            color: var(--bs-primary) !important;
            transition: all 0.3s ease !important;
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

        /* Animation for dropdown */
        .dropdown-menu {
            animation: fadeInDown 0.3s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .logout-confirm-popup {
                margin: 1rem !important;
            }

            .logout-confirm-btn,
            .logout-cancel-btn {
                padding: 0.75rem 1rem !important;
                font-size: 0.9rem !important;
            }
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
                <small class="text-muted">MA Modern Miftahussa'adah</small>
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
                        @if (class_exists('App\Services\RekomendasiService'))
                            @php
                                try {
                                    $profilCheck = app('App\Services\RekomendasiService')->cekKelengkapanProfil(
                                        auth()->user(),
                                    );
                                    $showBadge = $profilCheck['persentase'] < 100;
                                    $percentage = $profilCheck['persentase'];
                                } catch (Exception $e) {
                                    $showBadge = false;
                                    $percentage = 0;
                                }
                            @endphp
                        @endif
                    </a>

                    @if (!auth()->user()->sudahTerdaftarEkstrakurikuler())
                        {{-- Menu untuk siswa yang BELUM terdaftar --}}
                        <div class="sidebar-section-header">
                            <small class="text-muted px-3 py-2 d-block">CARI EKSTRAKURIKULER</small>
                        </div>

                        <a href="{{ route('siswa.rekomendasi') }}"
                            class="nav-link {{ request()->routeIs('siswa.rekomendasi*') ? 'active' : '' }}">
                            <i class="bi bi-stars"></i>
                            Rekomendasi
                            <span class="badge bg-primary ms-2">AI</span>
                        </a>

                        <a href="{{ route('siswa.ekstrakurikuler.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') ? 'active' : '' }}">
                            <i class="bi bi-collection"></i>
                            Ekstrakurikuler
                        </a>
                    @else
                        {{-- Menu untuk siswa yang SUDAH terdaftar --}}
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
                            <small class="text-muted px-3 py-2 d-block">KEGIATAN SAYA</small>
                        </div>

                        @if ($ekstrakurikuler)
                            {{-- Info Ekstrakurikuler yang diikuti --}}
                            <div class="nav-item px-3 py-2 mb-2">
                                <div class="bg-primary bg-opacity-10 rounded p-2">
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

                        {{-- MENU BARU: Galeri --}}
                        <a href="{{ route('siswa.galeri.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.galeri*') ? 'active' : '' }}">
                            <i class="bi bi-images"></i>
                            Galeri Kegiatan
                            @php
                                $totalGaleri = $ekstrakurikuler ? $ekstrakurikuler->galeris()->count() : 0;
                            @endphp
                            @if ($totalGaleri > 0)
                                <span class="badge bg-info ms-2">{{ $totalGaleri }}</span>
                            @endif
                        </a>

                        {{-- MENU BARU: Pengumuman --}}
                        <a href="{{ route('siswa.pengumuman.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.pengumuman*') ? 'active' : '' }}">
                            <i class="bi bi-megaphone"></i>
                            Pengumuman
                            @php
                                $pengumumanBaru = $ekstrakurikuler
                                    ? $ekstrakurikuler
                                        ->pengumumans()
                                        ->where('created_at', '>=', now()->subDays(7))
                                        ->count()
                                    : 0;
                            @endphp
                            @if ($pengumumanBaru > 0)
                                <span class="badge bg-warning ms-2">{{ $pengumumanBaru }}</span>
                            @endif
                        </a>

                        {{-- Divider --}}
                        <hr class="sidebar-divider">

                        <div class="sidebar-section-header">
                            <small class="text-muted px-3 py-2 d-block">LAINNYA</small>
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
                            <span class="badge bg-warning ms-2">{{ $pendingCount }}</span>
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
                    <button class="btn btn-outline-light d-lg-none" type="button" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="navbar-nav ms-auto">
                        <!-- User Dropdown -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <h6 class="dropdown-header">{{ ucfirst(auth()->user()->role) }}</h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <!-- Profile Link (sesuaikan dengan role) -->
                                @if (auth()->user()->role === 'siswa')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('siswa.profil') }}">
                                            <i class="bi bi-person-gear me-2"></i>Profil Saya
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <!-- Logout dengan Konfirmasi -->
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Hidden Logout Form -->
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
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
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
                showConfirmButton: false
            });
        }

        // Error notification
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: message
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
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i>Ya, Logout',
                cancelButtonText: '<i class="bi bi-x-lg me-1"></i>Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'logout-confirm-popup',
                    title: 'logout-confirm-title',
                    confirmButton: 'logout-confirm-btn',
                    cancelButton: 'logout-cancel-btn'
                },
                backdrop: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        // Show loading state
                        setTimeout(() => {
                            resolve();
                        }, 500);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show success message then logout
                    Swal.fire({
                        title: 'Logout Berhasil',
                        text: 'Anda telah keluar dari sistem. Terima kasih!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'logout-success-popup'
                        }
                    }).then(() => {
                        // Submit logout form
                        document.getElementById('logout-form').submit();
                    });
                }
            });
        }

        // Optional: Auto logout warning (session akan habis)
        let logoutWarningShown = false;

        function checkSessionTimeout() {
            // Cek setiap 5 menit (300000 ms)
            // Ini contoh untuk warning 5 menit sebelum session habis
            if (!logoutWarningShown) {
                // Implementasi sesuai kebutuhan session timeout Anda
                console.log('Session check...');
            }
        }

        // Check session every 5 minutes
        setInterval(checkSessionTimeout, 300000);

        // Optional: Keyboard shortcut for logout (Ctrl + Shift + L)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'L') {
                e.preventDefault();
                confirmLogout(e);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
