<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ekstrakurikuler App') - MA Modern Miftahussa'adah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

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

            /* Sidebar Variables */
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --sidebar-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--bs-gray-100);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--bs-gray-800);
            overflow-x: hidden;
        }

        /* ======================
            SIDEBAR STYLES
         ====================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--bs-white);
            z-index: 1030;
            transition: var(--sidebar-transition);
            border-right: 1px solid var(--bs-gray-200);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--bs-gray-200);
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            min-height: 80px;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-logo {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
            transition: var(--sidebar-transition);
        }

        .sidebar-logo img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            object-fit: cover;
        }

        .sidebar-logo i {
            font-size: 1.5rem;
            color: white;
        }

        .sidebar-brand {
            transition: var(--sidebar-transition);
            min-width: 0;
            flex: 1;
        }

        .sidebar-brand h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
            white-space: nowrap;
        }

        .sidebar-brand small {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8rem;
            display: block;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-brand {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-logo {
            margin: 0 auto;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);

            /* 1. Ukuran diperkecil dari 40px menjadi 34px */
            width: 34px;
            height: 34px;

            /* 2. Posisi 'left' disesuaikan dengan ukuran baru (setengah dari width) */
            left: calc(var(--sidebar-width) - 17px);

            z-index: 1031;
            background: var(--bs-white);
            border: 1px solid var(--bs-gray-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--sidebar-transition);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            color: var(--bs-gray-600);
        }

        .sidebar-toggle:hover {
            background: var(--bs-primary);
            color: white;
            transform: translateY(-50%) scale(1.1);
            /* Efek hover dikembalikan */
            box-shadow: 0 6px 20px rgba(60, 154, 231, 0.3);
        }

        .sidebar.collapsed .sidebar-toggle {
            /* 3. Posisi saat collapsed juga disesuaikan */
            left: calc(var(--sidebar-collapsed-width) - 17px);
        }

        .sidebar-toggle i {
            transition: transform 0.3s ease;
            font-size: 0.9rem;
            /* Ukuran ikon sedikit dikecilkan */
        }

        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* Sidebar Menu */
        .sidebar-menu {
            padding: 1rem 0;
            background: var(--bs-white);
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: var(--bs-gray-300);
            border-radius: 2px;
        }

        .sidebar-menu .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: var(--bs-gray-600);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            margin: 0.25rem 0.75rem;
            border-radius: 12px;
            position: relative;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-menu .nav-link:hover {
            background: linear-gradient(135deg, rgba(60, 154, 231, 0.1) 0%, rgba(99, 208, 241, 0.1) 100%);
            color: var(--bs-primary);
        }

        .sidebar-menu .nav-link.active {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(60, 154, 231, 0.4);
        }

        .sidebar-menu .nav-link i {
            margin-right: 0.875rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Collapsed sidebar menu */
        .sidebar.collapsed .nav-link {
            padding: 0.875rem;
            margin: 0.25rem 0.5rem;
            justify-content: center;
        }

        .sidebar.collapsed .nav-link .nav-text,
        .sidebar.collapsed .nav-link .badge {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: var(--sidebar-transition);
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Sidebar Section Headers */
        .sidebar-section-header {
            margin-top: 0.5rem;
            padding: 0 1.5rem 0.5rem;
        }

        .sidebar-section-header small {
            color: var(--bs-gray-400);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 0.7rem;
        }

        .sidebar.collapsed .sidebar-section-header {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* ======================
            MAIN CONTENT STYLES
         ====================== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: var(--bs-gray-100);
            transition: var(--sidebar-transition);
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Top Navbar */
        .top-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--bs-gray-200);
            position: sticky;
            top: 0;
            z-index: 1020;
            padding: 0.75rem 2rem;
        }

        .content-wrapper {
            padding: 2rem;
            flex: 1;
        }

        .page-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-light) 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        /* ======================
            RESPONSIVE & OTHER
         ====================== */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1040;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: none;
                /* We will use a different toggle for mobile */
            }

            .main-content,
            .main-content.sidebar-collapsed {
                margin-left: 0;
                width: 100%;
            }

            .content-wrapper,
            .top-navbar {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        .mobile-sidebar-toggle {
            font-size: 1.5rem;
            color: var(--bs-gray-600);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar.collapsed .nav-link::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 15px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--bs-gray-800);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1045;
        }

        .sidebar.collapsed .nav-link:hover::after {
            opacity: 1;
        }

        .card {
            background: var(--bs-white);
            border: 1px solid var(--bs-gray-200);
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .dropdown-menu {
            border: 1px solid var(--bs-gray-200);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            background: var(--bs-white);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bs-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
            flex-shrink: 0;
            /* Mencegah avatar menyusut */
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .dropdown-header {
            padding: 0.75rem 1.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-item {
            color: var(--bs-gray-700);
            padding: 0.625rem 1rem;
            border-radius: 8px;
            margin: 0.25rem 0.5rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--bs-gray-100);
            color: var(--bs-primary);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        #userDropdown::after {
            display: none !important;
        }

        /* 2. Atur gaya ikon kustom kita */
        .user-dropdown-icon {
            font-size: 0.8rem;
            font-weight: bold;
            /* Dibuat tebal agar mirip versi ::before */
            color: var(--bs-gray-600);
            transition: transform 0.2s ease-in-out;
        }

        /* 3. Putar ikon saat dropdown terbuka */
        #userDropdown.show .user-dropdown-icon {
            transform: rotate(180deg);
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
    </style>

    @stack('styles')
</head>

<body>
    @auth
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <div class="sidebar" id="sidebar">
            <div class="sidebar-toggle d-none d-lg-flex" onclick="toggleSidebar()">
                <i class="bi bi-chevron-left"></i>
            </div>

            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="{{ asset('img/logo.jpeg') }}" alt="Logo"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <i class="bi bi-mortarboard-fill" style="display: none;"></i>
                </div>
                <div class="sidebar-brand">
                    <h4>MiftahXKull</h4>
                    <small>MA Miftahussa'adah</small>
                </div>
            </div>

            <div class="sidebar-menu">
                {{-- Menu untuk Admin --}}
                @if (auth()->user()->role === 'admin')
                    <div class="sidebar-section-header"><small>Admin Menu</small></div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        data-tooltip="Dashboard"><i class="bi bi-speedometer2"></i><span
                            class="nav-text">Dashboard</span></a>
                    <a href="{{ route('admin.ekstrakurikuler.index') }}"
                        class="nav-link {{ request()->routeIs('admin.ekstrakurikuler.*') ? 'active' : '' }}"
                        data-tooltip="Ekstrakurikuler"><i class="bi bi-collection"></i><span class="nav-text">Kelola
                            Ekstrakurikuler</span></a>
                    <a href="{{ route('admin.user.index', ['role' => 'siswa']) }}"
                        class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}" data-tooltip="Pengguna"><i
                            class="bi bi-people"></i><span class="nav-text">Kelola Pengguna</span></a>
                    <a href="{{ route('admin.laporan') }}"
                        class="nav-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" data-tooltip="Laporan"><i
                            class="bi bi-file-earmark-text"></i><span class="nav-text">Generate Laporan</span></a>

                    {{-- Menu untuk Pembina --}}
                @elseif(auth()->user()->role === 'pembina')
                    <div class="sidebar-section-header"><small>Pembina Menu</small></div>
                    <a href="{{ route('pembina.dashboard') }}"
                        class="nav-link {{ request()->routeIs('pembina.dashboard') ? 'active' : '' }}"
                        data-tooltip="Dashboard"><i class="bi bi-speedometer2"></i><span
                            class="nav-text">Dashboard</span></a>
                    <a href="{{ route('pembina.pendaftaran.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.pendaftaran.*') ? 'active' : '' }}"
                        data-tooltip="Pendaftaran"><i class="bi bi-person-plus"></i><span class="nav-text">Kelola
                            Pendaftaran</span></a>
                    <a href="{{ route('pembina.siswa.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.siswa.*') ? 'active' : '' }}"
                        data-tooltip="Data Siswa"><i class="bi bi-people"></i><span class="nav-text">Kelola Siswa</span></a>
                    <a href="{{ route('pembina.pengumuman.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.pengumuman.*') ? 'active' : '' }}"
                        data-tooltip="Pengumuman"><i class="bi bi-megaphone"></i><span class="nav-text">Kelola
                            Pengumuman</span></a>
                    <a href="{{ route('pembina.galeri.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.galeri.*') ? 'active' : '' }}"
                        data-tooltip="Galeri"><i class="bi bi-images"></i><span class="nav-text">Kelola Galeri</span></a>

                    {{-- Menu untuk Siswa --}}
                @else
                    @if (auth()->user()->role === 'siswa')
                        <div class="sidebar-section-header"><small>Navigasi Utama</small></div>
                        <a href="{{ route('siswa.dashboard') }}"
                            class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"
                            data-tooltip="Dashboard">
                            <i class="bi bi-speedometer2"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>

                        <a href="{{ route('siswa.profil') }}"
                            class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}"
                            data-tooltip="Profil Saya">
                            <i class="bi bi-person-circle"></i>
                            <span class="nav-text">Profil Saya</span>
                        </a>

                        {{-- Cek apakah user sudah pernah mendaftar ekstrakurikuler --}}
                        @if (auth()->user()->pendaftarans->count() > 0)
                            {{-- Status Pendaftaran - Tampil jika ada pendaftaran --}}
                            <a href="{{ route('siswa.pendaftaran') }}"
                                class="nav-link {{ request()->routeIs('siswa.pendaftaran') ? 'active' : '' }}"
                                data-tooltip="Status Pendaftaran">
                                <i class="bi bi-clipboard-check"></i>
                                <span class="nav-text">Status Pendaftaran</span>
                                @php
                                    $pendingCount = auth()->user()->pendaftarans()->where('status', 'pending')->count();
                                    $approvedCount = auth()
                                        ->user()
                                        ->pendaftarans()
                                        ->where('status', 'disetujui')
                                        ->count();
                                @endphp
                            </a>
                        @endif

                        {{-- Menu berdasarkan status ekstrakurikuler --}}
                        @if (!auth()->user()->sudahTerdaftarEkstrakurikuler())
                            <div class="sidebar-section-header"><small>Pendaftaran</small></div>
                            <a href="{{ route('siswa.rekomendasi') }}"
                                class="nav-link {{ request()->routeIs('siswa.rekomendasi*') ? 'active' : '' }}"
                                data-tooltip="Rekomendasi AI">
                                <i class="bi bi-stars"></i>
                                <span class="nav-text">Rekomendasi</span>
                                <span class="badge bg-primary ms-auto">AI</span>
                            </a>

                            <a href="{{ route('siswa.ekstrakurikuler.index') }}"
                                class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') ? 'active' : '' }}"
                                data-tooltip="Lihat Ekstrakurikuler">
                                <i class="bi bi-collection"></i>
                                <span class="nav-text">Jelajahi Ekstrakurikuler</span>
                            </a>
                        @else
                            <div class="sidebar-section-header"><small>Kegiatan Saya</small></div>
                            <a href="{{ route('siswa.jadwal') }}"
                                class="nav-link {{ request()->routeIs('siswa.jadwal*') ? 'active' : '' }}"
                                data-tooltip="Jadwal">
                                <i class="bi bi-calendar3"></i>
                                <span class="nav-text">Jadwal</span>
                            </a>

                            <a href="{{ route('siswa.galeri.index') }}"
                                class="nav-link {{ request()->routeIs('siswa.galeri*') ? 'active' : '' }}"
                                data-tooltip="Galeri">
                                <i class="bi bi-images"></i>
                                <span class="nav-text">Galeri</span>
                            </a>

                            <a href="{{ route('siswa.pengumuman.index') }}"
                                class="nav-link {{ request()->routeIs('siswa.pengumuman*') ? 'active' : '' }}"
                                data-tooltip="Pengumuman">
                                <i class="bi bi-megaphone"></i>
                                <span class="nav-text">Pengumuman</span>
                            </a>

                            <div class="sidebar-section-header"><small>Lainnya</small></div>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}"
                                class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') && !request()->routeIs('siswa.ekstrakurikuler.show') ? 'active' : '' }}"
                                data-tooltip="Lihat Ekstrakurikuler">
                                <i class="bi bi-eye"></i>
                                <span class="nav-text">Lihat Ekstrakurikuler</span>
                            </a>
                        @endif
                    @endif
                @endif
            </div>
        </div>

        <div class="main-content" id="mainContent">
            <nav class="top-navbar d-flex justify-content-between align-items-center">
                <button class="btn border-0 d-lg-none mobile-sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-lg-block">
                    <h5 class="mb-0">@yield('page-title', 'Selamat Datang!')</h5>
                </div>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="user-avatar">
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        </div>

                        <div class="d-none d-md-block">
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                            <small class="text-muted text-capitalize">{{ auth()->user()->role }}</small>
                        </div>

                        <i class="bi bi-chevron-down user-dropdown-icon ms-2"></i>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <div class="dropdown-header">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <div class="text-muted small">{{ auth()->user()->email }}</div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i>Profil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
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
            <footer class="text-center p-3 mt-auto bg-white border-top">
                <small>&copy; {{ date('Y') }} MiftahXKull - MA Modern Miftahussa'adah. All Rights Reserved.</small>
            </footer>
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check localStorage for sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('sidebar-collapsed');
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                // Mobile view: show/hide sidebar
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                // Desktop view: collapse/expand sidebar
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            // Update badge status setiap 30 detik jika ada pendaftaran pending
            @if (auth()->user()->pendaftarans()->where('status', 'pending')->count() > 0)
                setInterval(function() {
                    // Check for status updates via AJAX
                    fetch('{{ route('siswa.pendaftaran.status') }}', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update badge berdasarkan status terbaru
                                const statusLink = document.querySelector('a[href*="pendaftaran"]');
                                if (statusLink) {
                                    const currentBadge = statusLink.querySelector('.badge');

                                    if (data.stats.pending > 0) {
                                        if (currentBadge) {
                                            currentBadge.textContent = data.stats.pending;
                                            currentBadge.className = 'badge bg-warning ms-auto';
                                        }
                                    } else if (data.stats.approved > 0) {
                                        if (currentBadge) {
                                            currentBadge.innerHTML = '<i class="bi bi-check"></i>';
                                            currentBadge.className = 'badge bg-success ms-auto';
                                        }
                                    }

                                    // Show notification jika ada perubahan status
                                    if (data.stats.has_changes) {
                                        showStatusChangeNotification();
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.log('Status check failed:', error);
                        });
                }, 30000); // Check setiap 30 detik
            @endif
        });

        function showStatusChangeNotification() {
            // Create toast notification for status changes
            const toastHTML = `
        <div class="toast align-items-center text-white bg-info border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-info-circle me-2"></i>
                    Status pendaftaran Anda telah diperbarui!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

            // Add to toast container or create one
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '1060';
                document.body.appendChild(toastContainer);
            }

            toastContainer.innerHTML = toastHTML;
            const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
            toast.show();
        }
    </script>

    @stack('scripts')
</body>

</html>
