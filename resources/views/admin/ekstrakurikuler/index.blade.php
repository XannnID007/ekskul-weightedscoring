@extends('layouts.app')

@section('title', 'Kelola Ekstrakurikuler')
@section('page-title', 'Kelola Ekstrakurikuler')
@section('page-description', 'Manajemen semua ekstrakurikuler sekolah')

@section('page-actions')
    <a href="{{ route('admin.ekstrakurikuler.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle me-1"></i>Tambah Ekstrakurikuler
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Ekstrakurikuler</h5>
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
                            <th width="10%">Gambar</th>
                            <th width="20%">Nama</th>
                            <th width="15%">Pembina</th>
                            <th width="10%">Kategori</th>
                            <th width="10%">Kapasitas</th>
                            <th width="10%">Status</th>
                            <th width="10%">Pendaftar</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ekstrakurikulers as $ekstrakurikuler)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($ekstrakurikuler->gambar)
                                        <img src="{{ Storage::url($ekstrakurikuler->gambar) }}"
                                            alt="{{ $ekstrakurikuler->nama }}" class="rounded" width="60" height="60"
                                            style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded d-flex align-items-center justify-content-center text-white"
                                            style="width: 60px; height: 60px;">
                                            <i class="bi bi-collection"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $ekstrakurikuler->nama }}</strong>
                                        <br><small
                                            class="text-muted">{{ Str::limit($ekstrakurikuler->deskripsi, 50) }}</small>
                                    </div>
                                </td>
                                <td>{{ $ekstrakurikuler->pembina->name }}</td>
                                <td>
                                    @foreach ($ekstrakurikuler->kategori as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="me-2">{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                                        <div class="progress flex-grow-1" style="height: 8px; width: 50px;">
                                            <div class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                                style="width: {{ ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            {{ $ekstrakurikuler->is_active ? 'checked' : '' }}
                                            onchange="toggleStatus({{ $ekstrakurikuler->id }}, this.checked)">
                                        <label class="form-check-label">
                                            <span
                                                class="badge bg-{{ $ekstrakurikuler->is_active ? 'success' : 'secondary' }}">
                                                {{ $ekstrakurikuler->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $ekstrakurikuler->pendaftarans->count() }} total</span>
                                    <br><small
                                        class="text-success">{{ $ekstrakurikuler->pendaftarans->where('status', 'disetujui')->count() }}
                                        disetujui</small>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.ekstrakurikuler.show', $ekstrakurikuler) }}">
                                                    <i class="bi bi-eye me-2"></i>Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.ekstrakurikuler.edit', $ekstrakurikuler) }}">
                                                    <i class="bi bi-pencil me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="confirmDelete('{{ route('admin.ekstrakurikuler.destroy', $ekstrakurikuler) }}')">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Ekstrakurikuler</h5>
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
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori">
                                <option value="">Semua Kategori</option>
                                <option value="olahraga">Olahraga</option>
                                <option value="seni">Seni</option>
                                <option value="akademik">Akademik</option>
                                <option value="teknologi">Teknologi</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pembina</label>
                            <select class="form-select" name="pembina">
                                <option value="">Semua Pembina</option>
                                @foreach ($ekstrakurikulers->pluck('pembina')->unique('id') as $pembina)
                                    <option value="{{ $pembina->id }}">{{ $pembina->name }}</option>
                                @endforeach
                            </select>
                        </div>
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
        function toggleStatus(id, isActive) {
            fetch(`/api/admin/ekstrakurikuler/${id}/toggle-status`, {
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
                    title: 'Hapus Ekstrakurikuler?',
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
