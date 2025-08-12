@extends('layouts.app')

@section('title', 'Kelola ' . ucfirst($role))
@section('page-title', 'Kelola ' . ucfirst($role))
@section('page-description', 'Manajemen data ' . $role)

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.user.create', ['role' => $role]) }}" class="btn btn-light">
            <i class="bi bi-person-plus me-1"></i>Tambah {{ ucfirst($role) }}
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .btn-action {
            background-color: transparent;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bs-gray-500);
            transition: all 0.2s ease;
        }

        .btn-action::after {
            display: none;
        }

        .btn-action:hover,
        .btn-action:focus {
            background-color: var(--bs-gray-100);
            color: var(--bs-primary);
        }

        .btn-action.show {
            background-color: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.4);
        }

        .dropdown-menu .dropdown-item i {
            width: 1.25rem;
            display: inline-block;
            text-align: center;
            margin-right: 0.5rem;
        }

        /* Custom Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 1.5rem;
            padding: 1rem 0;
        }

        .pagination {
            margin: 0;
            gap: 0.25rem;
        }

        .pagination .page-link {
            border: 1px solid var(--bs-gray-300);
            border-radius: 8px;
            color: var(--bs-gray-600);
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            transition: all 0.2s ease;
            min-width: 40px;
            text-align: center;
        }

        .pagination .page-link:hover {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
            box-shadow: 0 2px 8px rgba(60, 154, 231, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            background-color: var(--bs-gray-100);
            border-color: var(--bs-gray-200);
            color: var(--bs-gray-400);
            cursor: not-allowed;
        }

        .pagination-info {
            color: var(--bs-gray-600);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Role Filter Buttons */
        .role-filter-container {
            background: var(--bs-white);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid var(--bs-gray-200);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .role-filter-container .btn-group .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s ease;
        }

        .role-filter-container .btn-outline-primary {
            border-color: var(--bs-gray-300);
            color: var(--bs-gray-600);
        }

        .role-filter-container .btn-outline-primary:hover {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
        }

        .role-filter-container .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        /* Table Improvements */
        .data-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .data-table thead th {
            background-color: var(--bs-gray-50);
            border-bottom: 2px solid var(--bs-gray-200);
            font-weight: 600;
            color: var(--bs-gray-700);
            padding: 1rem 0.75rem;
        }

        .data-table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--bs-gray-100);
        }

        .data-table tbody tr:hover {
            background-color: var(--bs-gray-50);
        }
    </style>
@endpush

@section('content')
    <!-- Role Tabs -->
    <div class="card role-filter-container mb-4">
        <div class="d-flex justify-content-center">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.user.index', ['role' => 'siswa']) }}"
                    class="btn btn-{{ $role == 'siswa' ? 'primary' : 'outline-primary' }}">
                    <i class="bi bi-people me-1"></i>Siswa
                </a>
                <a href="{{ route('admin.user.index', ['role' => 'pembina']) }}"
                    class="btn btn-{{ $role == 'pembina' ? 'primary' : 'outline-primary' }}">
                    <i class="bi bi-person-badge me-1"></i>Pembina
                </a>
                <a href="{{ route('admin.user.index', ['role' => 'admin']) }}"
                    class="btn btn-{{ $role == 'admin' ? 'primary' : 'outline-primary' }}">
                    <i class="bi bi-shield-check me-1"></i>Admin
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar {{ ucfirst($role) }}</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if ($role == 'siswa')
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama</th>
                                <th width="20%">Email</th>
                                @if ($role == 'siswa')
                                    <th width="10%">NIS</th>
                                    <th width="10%">Gender</th>
                                    <th width="10%">Nilai AVG</th>
                                @endif
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle p-2 me-3"
                                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <span
                                                    class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $user->name }}</strong>
                                                @if ($user->telepon)
                                                    <small class="text-muted">{{ $user->telepon }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    @if ($role == 'siswa')
                                        <td>{{ $user->nis ?? '-' }}</td>
                                        <td>
                                            @if ($user->jenis_kelamin)
                                                @if ($user->jenis_kelamin == 'L')
                                                    <span class="badge bg-info">Laki-laki</span>
                                                @else
                                                    <span class="badge text-white"
                                                        style="background-color: #e91e63;">Perempuan</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->nilai_rata_rata)
                                                <span
                                                    class="badge bg-{{ $user->nilai_rata_rata >= 80 ? 'success' : ($user->nilai_rata_rata >= 70 ? 'warning' : 'danger') }}">
                                                    {{ $user->nilai_rata_rata }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                {{ $user->is_active ? 'checked' : '' }}
                                                onchange="toggleUserStatus({{ $user->id }}, this.checked)">
                                            <label class="form-check-label ms-2">
                                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-action dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.user.show', $user) }}">
                                                        <i class="bi bi-eye"></i>Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.user.edit', $user) }}">
                                                        <i class="bi bi-pencil"></i>Edit
                                                    </a>
                                                </li>
                                                @if ($user->id !== auth()->id())
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#"
                                                            onclick="confirmDelete('{{ route('admin.user.destroy', $user) }}')">
                                                            <i class="bi bi-trash"></i>Hapus
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Improved Pagination -->
                @if ($users->hasPages())
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }}
                            dari {{ $users->total() }} data
                        </div>

                        <nav aria-label="User pagination">
                            <ul class="pagination">
                                {{-- Previous Button --}}
                                @if ($users->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @php
                                    $start = max($users->currentPage() - 2, 1);
                                    $end = min($start + 4, $users->lastPage());
                                    $start = max($end - 4, 1);
                                @endphp

                                @if ($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->url(1) }}">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $users->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endfor

                                @if ($end < $users->lastPage())
                                    @if ($end < $users->lastPage() - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
                                    </li>
                                @endif

                                {{-- Next Button --}}
                                @if ($users->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-people display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Belum ada data {{ $role }}</h5>
                    <p class="text-muted">Silakan tambah {{ $role }} baru dengan mengklik tombol di atas.</p>
                    <a href="{{ route('admin.user.create', ['role' => $role]) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah {{ ucfirst($role) }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Import Modal untuk Siswa -->
    @if ($role == 'siswa')
        <div class="modal fade" id="importModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Data Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.user.import-siswa') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">File Excel (.xlsx/.xls)</label>
                                <input type="file" class="form-control" id="file" name="file"
                                    accept=".xlsx,.xls" required>
                                <div class="form-text">
                                    Format: Nama, Email, NIS, Jenis Kelamin, Tanggal Lahir, Alamat, Telepon
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Template:</strong>
                                <a href="#" class="alert-link">Download template Excel</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i>Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter {{ ucfirst($role) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        @if ($role == 'siswa')
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin">
                                    <option value="">Semua</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rentang Nilai</label>
                                <select class="form-select" name="nilai_range">
                                    <option value="">Semua Nilai</option>
                                    <option value="90-100">90 - 100</option>
                                    <option value="80-89">80 - 89</option>
                                    <option value="70-79">70 - 79</option>
                                    <option value="0-69">
                                        < 70</option>
                                </select>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilter()">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleUserStatus(id, isActive) {
            // Implementasi toggle status user
            console.log('Toggle user status:', id, isActive);

            // Simulasi API call
            fetch(`/api/admin/user/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        is_active: isActive
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update badge
                        const badge = document.querySelector(`input[onchange*="${id}"]`).nextElementSibling
                            .querySelector('.badge');
                        badge.textContent = isActive ? 'Aktif' : 'Nonaktif';
                        badge.className = `badge bg-${isActive ? 'success' : 'secondary'}`;

                        // Show success message
                        showNotification('Status berhasil diubah', 'success');
                    } else {
                        showNotification('Gagal mengubah status', 'error');
                        // Reset checkbox
                        document.querySelector(`input[onchange*="${id}"]`).checked = !isActive;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan', 'error');
                    // Reset checkbox
                    document.querySelector(`input[onchange*="${id}"]`).checked = !isActive;
                });
        }

        function confirmDelete(url) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan!')) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function applyFilter() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            const currentUrl = new URL(window.location);
            params.set('role', '{{ $role }}');

            window.location.href = currentUrl.pathname + '?' + params.toString();
        }

        function showNotification(message, type = 'info') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alert);

            // Auto remove after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Initialize tooltips if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Add any initialization code here
            console.log('User management page loaded');
        });
    </script>
@endpush
