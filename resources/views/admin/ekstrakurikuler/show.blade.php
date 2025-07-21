@extends('layouts.app')

@section('title', $ekstrakurikuler->nama)
@section('page-title', $ekstrakurikuler->nama)
@section('page-description', 'Detail informasi ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.ekstrakurikuler.edit', $ekstrakurikuler) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.ekstrakurikuler.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Main Info -->
        <div class="col-xl-8">
            <!-- Header Card -->
            <div class="card mb-4">
                <div class="position-relative">
                    @if ($ekstrakurikuler->gambar)
                        <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" class="card-img-top"
                            alt="{{ $ekstrakurikuler->nama }}" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-gradient text-white"
                            style="height: 300px; background: linear-gradient(135deg, var(--bs-primary) 0%, #8b5cf6 100%);">
                            <i class="bi bi-collection" style="font-size: 5rem;"></i>
                        </div>
                    @endif

                    <!-- Overlay Info -->
                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-4">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <h3 class="mb-1">{{ $ekstrakurikuler->nama }}</h3>
                                <div class="mb-2">
                                    @if ($ekstrakurikuler->kategori && is_array($ekstrakurikuler->kategori))
                                        @foreach ($ekstrakurikuler->kategori as $kategori)
                                            <span class="badge bg-light text-dark me-1">{{ ucfirst($kategori) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="mb-0 opacity-75">Pembina:
                                    {{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</p>
                            </div>
                            <div class="text-end">
                                <span
                                    class="badge bg-{{ $ekstrakurikuler->is_active ? 'success' : 'secondary' }} fs-6 px-3 py-2">
                                    {{ $ekstrakurikuler->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Deskripsi
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $ekstrakurikuler->deskripsi }}</p>
                </div>
            </div>

            <!-- Pendaftaran -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Data Pendaftaran
                    </h5>
                    <span class="badge bg-primary">{{ $ekstrakurikuler->pendaftarans->count() }} Total</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-success rounded-circle p-3 d-inline-flex mb-2">
                                    <i class="bi bi-check-circle text-white"></i>
                                </div>
                                <div>
                                    <strong
                                        class="d-block fs-4">{{ $ekstrakurikuler->pendaftarans->where('status', 'disetujui')->count() }}</strong>
                                    <small class="text-muted">Disetujui</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-warning rounded-circle p-3 d-inline-flex mb-2">
                                    <i class="bi bi-clock text-white"></i>
                                </div>
                                <div>
                                    <strong
                                        class="d-block fs-4">{{ $ekstrakurikuler->pendaftarans->where('status', 'pending')->count() }}</strong>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-danger rounded-circle p-3 d-inline-flex mb-2">
                                    <i class="bi bi-x-circle text-white"></i>
                                </div>
                                <div>
                                    <strong
                                        class="d-block fs-4">{{ $ekstrakurikuler->pendaftarans->where('status', 'ditolak')->count() }}</strong>
                                    <small class="text-muted">Ditolak</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-info rounded-circle p-3 d-inline-flex mb-2">
                                    <i class="bi bi-percent text-white"></i>
                                </div>
                                <div>
                                    <strong
                                        class="d-block fs-4">{{ $ekstrakurikuler->kapasitas_maksimal > 0 ? round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) : 0 }}%</strong>
                                    <small class="text-muted">Terisi</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Siswa List -->
                    @if ($ekstrakurikuler->pendaftarans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Status</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ekstrakurikuler->pendaftarans->take(10) as $pendaftaran)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle p-2 me-2">
                                                        <i class="bi bi-person text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $pendaftaran->user->name }}</strong>
                                                        <br><small
                                                            class="text-muted">{{ $pendaftaran->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $pendaftaran->user->nis ?? '-' }}</td>
                                            <td>
                                                @if ($pendaftaran->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($pendaftaran->status == 'disetujui')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $pendaftaran->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="#" class="btn btn-outline-primary btn-sm"
                                                    onclick="showPendaftaranDetail({{ $pendaftaran->id }})">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($ekstrakurikuler->pendaftarans->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Dan {{ $ekstrakurikuler->pendaftarans->count() - 10 }}
                                    pendaftaran lainnya</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada pendaftaran</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Galeri -->
            @if (isset($ekstrakurikuler->galeris) && $ekstrakurikuler->galeris->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-images me-2"></i>Galeri Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($ekstrakurikuler->galeris->take(6) as $galeri)
                                <div class="col-md-4">
                                    @if ($galeri->tipe == 'gambar')
                                        <img src="{{ Storage::url($galeri->path_file) }}"
                                            class="img-fluid rounded gallery-item" alt="{{ $galeri->judul }}"
                                            style="height: 150px; width: 100%; object-fit: cover; cursor: pointer;"
                                            onclick="showImage('{{ Storage::url($galeri->path_file) }}', '{{ $galeri->judul }}')">
                                    @else
                                        <div class="position-relative">
                                            <video class="img-fluid rounded"
                                                style="height: 150px; width: 100%; object-fit: cover;">
                                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                            </video>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="bi bi-play-circle text-white" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block mt-1">{{ $galeri->judul }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pengumuman -->
            @if (isset($ekstrakurikuler->pengumumans) && $ekstrakurikuler->pengumumans->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-megaphone me-2"></i>Pengumuman
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($ekstrakurikuler->pengumumans->take(3) as $pengumuman)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i
                                        class="bi bi-{{ $pengumuman->is_penting ? 'exclamation-triangle' : 'info-circle' }} text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $pengumuman->judul }}
                                        @if ($pengumuman->is_penting)
                                            <span class="badge bg-warning ms-2">Penting</span>
                                        @endif
                                    </h6>
                                    <p class="mb-1 text-muted">{{ Str::limit($pengumuman->konten, 150) }}</p>
                                    <small class="text-muted">{{ $pengumuman->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Quick Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">PEMBINA</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle p-2 me-2">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            <div>
                                <strong>{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}</strong>
                                @if ($ekstrakurikuler->pembina && $ekstrakurikuler->pembina->telepon)
                                    <br><small class="text-muted">{{ $ekstrakurikuler->pembina->telepon }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">JADWAL</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-2 me-2">
                                <i class="bi bi-calendar text-white"></i>
                            </div>
                            <strong>{{ $ekstrakurikuler->jadwal_string }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">KAPASITAS</label>
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                            <span>{{ $ekstrakurikuler->kapasitas_maksimal > 0 ? round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) : 0 }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                style="width: {{ $ekstrakurikuler->kapasitas_maksimal > 0 ? ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">NILAI MINIMAL</label>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-2">
                                <i class="bi bi-trophy text-white"></i>
                            </div>
                            <strong>{{ $ekstrakurikuler->nilai_minimal }}</strong>
                        </div>
                    </div>

                    <div>
                        <label class="form-label small text-muted">STATUS</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                {{ $ekstrakurikuler->is_active ? 'checked' : '' }}
                                onchange="toggleStatus({{ $ekstrakurikuler->id }}, this.checked)">
                            <label class="form-check-label">
                                <span class="badge bg-{{ $ekstrakurikuler->is_active ? 'success' : 'secondary' }}">
                                    {{ $ekstrakurikuler->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </label>
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
