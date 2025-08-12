@extends('layouts.app')

@section('title', $ekstrakurikuler->nama)
@section('page-title', $ekstrakurikuler->nama)
@section('page-description', 'Detail informasi ekstrakurikuler')

@push('styles')
    <style>
        /* Info di atas gambar header */
        .header-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0) 100%);
        }

        /* Kartu statistik mini untuk pendaftaran */
        .mini-stats-card {
            background-color: var(--bs-gray-50);
            border: 1px solid var(--bs-gray-200);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: all 0.2s ease-in-out;
        }

        .mini-stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
        }

        .mini-stats-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .mini-stats-card .icon.bg-success {
            background-color: var(--bs-success-bg-subtle) !important;
            color: var(--bs-success);
        }

        .mini-stats-card .icon.bg-warning {
            background-color: var(--bs-warning-bg-subtle) !important;
            color: var(--bs-warning);
        }

        .mini-stats-card .icon.bg-danger {
            background-color: var(--bs-danger-bg-subtle) !important;
            color: var(--bs-danger);
        }

        .mini-stats-card .icon.bg-info {
            background-color: var(--bs-info-bg-subtle) !important;
            color: var(--bs-info);
        }

        /* Info di sidebar kanan */
        .quick-info-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--bs-gray-200);
        }

        .quick-info-item:last-child {
            border-bottom: none;
        }

        .quick-info-item .icon-wrapper {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background-color: var(--bs-gray-100);
            color: var(--bs-gray-600);
        }
    </style>
@endpush

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.ekstrakurikuler.edit', $ekstrakurikuler) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.ekstrakurikuler.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card mb-4 overflow-hidden">
                <div class="position-relative">
                    @if ($ekstrakurikuler->gambar)
                        <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" class="card-img-top"
                            alt="{{ $ekstrakurikuler->nama }}" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                            style="height: 300px; background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));">
                            <i class="bi bi-collection" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                    <div class="position-absolute bottom-0 start-0 end-0 text-white p-4 header-overlay">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="mb-2">
                                    @if ($ekstrakurikuler->kategori && is_array($ekstrakurikuler->kategori))
                                        @foreach ($ekstrakurikuler->kategori as $kategori)
                                            <span class="badge bg-white text-dark me-1">{{ ucfirst($kategori) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <h2 class="mb-1 text-shadow">{{ $ekstrakurikuler->nama }}</h2>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-person-check-fill me-1"></i>
                                    {{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Deskripsi</h5>
                    <p class="text-muted mb-0">{{ $ekstrakurikuler->deskripsi }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-people me-2 text-primary"></i>Data Pendaftaran</h5>
                    <span
                        class="badge bg-primary-subtle text-primary-emphasis rounded-pill">{{ $ekstrakurikuler->pendaftarans->count() }}
                        Total Pendaftar</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="mini-stats-card">
                                <div class="icon bg-success"><i class="bi bi-check-lg"></i></div>
                                <strong
                                    class="d-block fs-4">{{ $ekstrakurikuler->pendaftarans->where('status', 'disetujui')->count() }}</strong>
                                <small class="text-muted">Disetujui</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mini-stats-card">
                                <div class="icon bg-warning"><i class="bi bi-clock"></i></div>
                                <strong
                                    class="d-block fs-4">{{ $ekstrakurikuler->pendaftarans->where('status', 'pending')->count() }}</strong>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mini-stats-card">
                                <div class="icon bg-danger"><i class="bi bi-x-lg"></i></div>
                                <strong
                                    class="d-block fs-4">{{ $ekstrakurikuler->pendaftarans->where('status', 'ditolak')->count() }}</strong>
                                <small class="text-muted">Ditolak</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mini-stats-card">
                                <div class="icon bg-info"><i class="bi bi-pie-chart"></i></div>
                                <strong
                                    class="d-block fs-4">{{ $ekstrakurikuler->kapasitas_maksimal > 0 ? round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) : 0 }}%</strong>
                                <small class="text-muted">Terisi</small>
                            </div>
                        </div>
                    </div>

                    @if ($ekstrakurikuler->pendaftarans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Status</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ekstrakurikuler->pendaftarans->take(5) as $pendaftaran)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar-sm me-3">
                                                        <span>{{ strtoupper(substr($pendaftaran->user->name, 0, 2)) }}</span>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $pendaftaran->user->name }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $pendaftaran->user->nis ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($pendaftaran->status == 'pending')
                                                    <span
                                                        class="badge bg-warning-subtle text-warning-emphasis rounded-pill">Pending</span>
                                                @elseif($pendaftaran->status == 'disetujui')
                                                    <span
                                                        class="badge bg-success-subtle text-success-emphasis rounded-pill">Disetujui</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Ditolak</span>
                                                @endif
                                            </td>
                                            <td><small
                                                    class="text-muted">{{ $pendaftaran->created_at->format('d M Y') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-people fs-1"></i>
                            <p class="mt-2">Belum ada pendaftaran</p>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Galeri & Pengumuman bisa ditambahkan kembali di sini jika perlu --}}
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="quick-info-item">
                        <div class="icon-wrapper"><i class="bi bi-person-check-fill fs-5"></i></div>
                        <div>
                            <small class="text-muted">PEMBINA</small><br>
                            <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="icon-wrapper"><i class="bi bi-calendar3 fs-5"></i></div>
                        <div>
                            <small class="text-muted">JADWAL</small><br>
                            <strong>{{ $ekstrakurikuler->jadwal_string }}</strong>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="icon-wrapper"><i class="bi bi-trophy fs-5"></i></div>
                        <div>
                            <small class="text-muted">NILAI MINIMAL</small><br>
                            <strong>{{ $ekstrakurikuler->nilai_minimal }}</strong>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="icon-wrapper"><i class="bi bi-pie-chart-fill fs-5"></i></div>
                        <div class="w-100">
                            <small class="text-muted">KAPASITAS</small>
                            <div class="d-flex justify-content-between">
                                <span>{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                                <span>{{ $ekstrakurikuler->kapasitas_maksimal > 0 ? round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) : 0 }}%</span>
                            </div>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                    style="width: {{ $ekstrakurikuler->kapasitas_maksimal > 0 ? ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="quick-info-item">
                        <div class="icon-wrapper"><i class="bi bi-toggles fs-5"></i></div>
                        <div class="w-100">
                            <small class="text-muted">STATUS</small><br>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    {{ $ekstrakurikuler->is_active ? 'checked' : '' }}
                                    onchange="toggleStatus({{ $ekstrakurikuler->id }}, this.checked)">
                                <label class="form-check-label fw-bold">
                                    {{ $ekstrakurikuler->is_active ? 'Aktif' : 'Nonaktif' }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imageModalImage" src="" class="img-fluid rounded" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showImage(src, title) {
            document.getElementById('imageModalImage').src = src;
            document.getElementById('imageModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        function toggleStatus(id, isActive) {
            fetch(`/admin/ekstrakurikuler/${id}/toggle-status`, {
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
                        const badge = document.querySelector('.form-check-label .badge');
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
                text: 'Data yang dihapus tidak dapat dikembalikan! Semua data siswa akan ikut terhapus.',
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

        function showPendaftaranDetail(id) {
            // This would show a modal with pendaftaran details
            Swal.fire({
                title: 'Detail Pendaftaran',
                text: 'Fitur detail pendaftaran akan ditampilkan di sini.',
                icon: 'info'
            });
        }
    </script>
@endpush
