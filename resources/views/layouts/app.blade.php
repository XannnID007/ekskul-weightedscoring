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
                    <a href="{{ route('pembina.absensi.index') }}"
                        class="nav-link {{ request()->routeIs('pembina.absensi.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        Input Kehadiran
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
                    <a href="{{ route('siswa.rekomendasi') }}"
                        class="nav-link {{ request()->routeIs('siswa.rekomendasi') ? 'active' : '' }}">
                        <i class="bi bi-stars"></i>
                        Rekomendasi
                    </a>
                    <a href="{{ route('siswa.ekstrakurikuler.index') }}"
                        class="nav-link {{ request()->routeIs('siswa.ekstrakurikuler.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i>
                        Ekstrakurikuler
                    </a>
                    <a href="{{ route('siswa.pendaftaran') }}"
                        class="nav-link {{ request()->routeIs('siswa.pendaftaran') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        Status Pendaftaran
                    </a>
                    <a href="{{ route('siswa.jadwal') }}"
                        class="nav-link {{ request()->routeIs('siswa.jadwal') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        Jadwal Kegiatan
                    </a>
                    <a href="{{ route('siswa.kehadiran') }}"
                        class="nav-link {{ request()->routeIs('siswa.kehadiran') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        Rekap Kehadiran
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
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                            class="bi bi-gear me-2"></i>Pengaturan</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
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
    </script>

    @stack('scripts')
</body>

</html>
