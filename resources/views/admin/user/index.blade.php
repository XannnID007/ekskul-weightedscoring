@extends('layouts.app')

@section('title', 'Kelola ' . ucfirst($role))
@section('page-title', 'Kelola ' . ucfirst($role))
@section('page-description', 'Manajemen data ' . $role)

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.user.create', ['role' => $role]) }}" class="btn btn-light">
            <i class="bi bi-person-plus me-1"></i>Tambah {{ ucfirst($role) }}
        </a>
        @if ($role == 'siswa')
            <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i>Import Excel
            </button>
        @endif
    </div>
@endsection

@section('content')
    <!-- Role Tabs -->
    <div class="card mb-4">
        <div class="card-body">
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
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar {{ ucfirst($role) }}</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <button class="btn btn-outline-success btn-sm" onclick="exportData()">
                    <i class="bi bi-download me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama</th>
                            <th width="20%">Email</th>
                            @if ($role == 'siswa')
                                <th width="10%">NIS</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="10%">Nilai Rata-rata</th>
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
                                        <div class="bg-primary rounded-circle p-2 me-3">
                                            <i class="bi bi-person text-white"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if ($user->telepon)
                                                <br><small class="text-muted">{{ $user->telepon }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                @if ($role == 'siswa')
                                    <td>{{ $user->nis ?? '-' }}</td>
                                    <td>
                                        @if ($user->jenis_kelamin)
                                            <span class="badge bg-{{ $user->jenis_kelamin == 'L' ? 'info' : 'pink' }}">
                                                {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->nilai_rata_rata)
                                            <span
                                                class="badge bg-{{ $user->nilai_rata_rata >= 80 ? 'success' : ($user->nilai_rata_rata >= 70 ? 'warning' : 'danger') }}">
                                                {{ $user->nilai_rata_rata }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $user->is_active ? 'checked' : '' }}
                                            onchange="toggleUserStatus({{ $user->id }}, this.checked)">
                                        <label class="form-check-label">
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.user.show', $user) }}">
                                                    <i class="bi bi-eye me-2"></i>Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.user.edit', $user) }}">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </a>
                                            </li>
                                            @if ($user->id !== auth()->id())
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        onclick="confirmDelete('{{ route('admin.user.destroy', $user) }}')">
                                                        <i class="bi bi-trash me-2"></i>Hapus
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <!-- Import Modal -->
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
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls"
                                    required>
                                <div class="form-text">
                                    Format: Nama, Email, NIS, Jenis Kelamin, Tanggal Lahir, Alamat, Telepon
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Template:</strong>
                                <a href="{{ asset('templates/template-siswa.xlsx') }}" class="alert-link">Download
                                    template Excel</a>
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
            fetch(`/api/admin/user/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        is_active: isActive
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Status berhasil diubah');
                        // Update badge
                        const badge = document.querySelector(`input[onchange*="${id}"]`).nextElementSibling
                            .querySelector('.badge');
                        badge.textContent = isActive ? 'Aktif' : 'Nonaktif';
                        badge.className = `badge bg-${isActive ? 'success' : 'secondary'}`;
                    } else {
                        showError('Gagal mengubah status');
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan');
                    console.error('Error:', error);
                });
        }

        function confirmDelete(url) {
            Swal.fire({
                title: 'Hapus {{ ucfirst($role) }}?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function applyFilter() {
            // Get filter values and apply to current URL
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

        function exportData() {
            window.location.href = `/admin/user/export?role={{ $role }}`;
        }
    </script>
@endpush
