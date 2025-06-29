@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('page-description', 'Kelola dokumentasi foto dan video kegiatan ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
            <i class="bi bi-cloud-upload me-1"></i>Upload Massal
        </button>
        <a href="{{ route('pembina.galeri.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg me-1"></i>Upload File
        </a>
    </div>
@endsection

@section('content')
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total File</h6>
                            <h2 class="mb-0">{{ $galeris->total() }}</h2>
                            <small class="opacity-75">Foto & Video</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-images"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Foto</h6>
                            <h2 class="mb-0">{{ $galeris->where('tipe', 'gambar')->count() }}</h2>
                            <small class="opacity-75">Gambar</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-image"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Video</h6>
                            <h2 class="mb-0">{{ $galeris->where('tipe', 'video')->count() }}</h2>
                            <small class="opacity-75">Video</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-play-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Bulan Ini</h6>
                            <h2 class="mb-0">{{ $galeris->where('created_at', '>=', now()->startOfMonth())->count() }}
                            </h2>
                            <small class="opacity-75">Upload baru</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & View Controls -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <select class="form-select" id="filterEkskul" style="max-width: 200px;">
                            <option value="">Semua Ekstrakurikuler</option>
                            @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                        <select class="form-select" id="filterTipe" style="max-width: 150px;">
                            <option value="">Semua Tipe</option>
                            <option value="gambar">Foto</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end gap-2">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="viewMode" id="gridView" autocomplete="off"
                                checked>
                            <label class="btn btn-outline-primary" for="gridView">
                                <i class="bi bi-grid-3x3"></i>
                            </label>

                            <input type="radio" class="btn-check" name="viewMode" id="listView" autocomplete="off">
                            <label class="btn btn-outline-primary" for="listView">
                                <i class="bi bi-list"></i>
                            </label>
                        </div>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-secondary" onclick="sortGallery('newest')">
                                <i class="bi bi-sort-down me-1"></i>Terbaru
                            </button>
                            <button class="btn btn-outline-secondary" onclick="sortGallery('oldest')">
                                <i class="bi bi-sort-up me-1"></i>Terlama
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-collection text-primary me-2"></i>Galeri Dokumentasi
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteSelected()" id="deleteSelectedBtn"
                        style="display: none;">
                        <i class="bi bi-trash me-1"></i>Hapus Terpilih
                    </button>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllFiles">
                        <label class="form-check-label" for="selectAllFiles">
                            Pilih Semua
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($galeris->count() > 0)
                <!-- Grid View -->
                <div id="gridViewContainer" class="row g-4">
                    @foreach ($galeris as $galeri)
                        <div class="col-xl-3 col-lg-4 col-md-6 galeri-item"
                            data-ekskul="{{ $galeri->ekstrakurikuler_id }}" data-tipe="{{ $galeri->tipe }}">
                            <div class="card galeri-card h-100">
                                <div class="position-relative">
                                    <!-- Selection Checkbox -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <input type="checkbox" class="form-check-input file-checkbox"
                                            value="{{ $galeri->id }}">
                                    </div>

                                    <!-- File Type Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-{{ $galeri->tipe === 'video' ? 'warning' : 'success' }}">
                                            <i
                                                class="bi bi-{{ $galeri->tipe === 'video' ? 'play-circle' : 'image' }}"></i>
                                        </span>
                                    </div>

                                    <!-- Media Content -->
                                    <div class="galeri-media" style="height: 200px; overflow: hidden;">
                                        @if ($galeri->tipe === 'gambar')
                                            <img src="{{ Storage::url($galeri->path_file) }}" alt="{{ $galeri->judul }}"
                                                class="w-100 h-100" style="object-fit: cover; cursor: pointer;"
                                                onclick="viewMedia('{{ Storage::url($galeri->path_file) }}', 'image', '{{ $galeri->judul }}')">
                                        @else
                                            <div
                                                class="position-relative w-100 h-100 bg-dark d-flex align-items-center justify-content-center">
                                                <video class="w-100 h-100" style="object-fit: cover;"
                                                    onclick="viewMedia('{{ Storage::url($galeri->path_file) }}', 'video', '{{ $galeri->judul }}')">
                                                    <source src="{{ Storage::url($galeri->path_file) }}"
                                                        type="video/mp4">
                                                </video>
                                                <div class="position-absolute">
                                                    <i class="bi bi-play-circle text-white"
                                                        style="font-size: 3rem; cursor: pointer;"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title mb-2">{{ $galeri->judul }}</h6>
                                    @if ($galeri->deskripsi)
                                        <p class="card-text text-muted small mb-2">
                                            {{ Str::limit($galeri->deskripsi, 80) }}</p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary">{{ $galeri->ekstrakurikuler->nama }}</span>
                                        <small class="text-muted">{{ $galeri->created_at->diffForHumans() }}</small>
                                    </div>

                                    <div class="d-flex gap-1">
                                        <button class="btn btn-outline-primary btn-sm flex-fill"
                                            onclick="viewMedia('{{ Storage::url($galeri->path_file) }}', '{{ $galeri->tipe }}', '{{ $galeri->judul }}')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="{{ route('pembina.galeri.edit', $galeri) }}"
                                            class="btn btn-outline-secondary btn-sm flex-fill">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-outline-success btn-sm flex-fill"
                                            onclick="downloadFile('{{ Storage::url($galeri->path_file) }}', '{{ $galeri->judul }}')">
                                            <i class="bi bi-download"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm flex-fill"
                                            onclick="deleteGaleri({{ $galeri->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- List View -->
                <div id="listViewContainer" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" class="form-check-input" id="selectAllList">
                                    </th>
                                    <th width="10%">Preview</th>
                                    <th width="25%">Judul</th>
                                    <th width="20%">Ekstrakurikuler</th>
                                    <th width="10%">Tipe</th>
                                    <th width="15%">Tanggal Upload</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($galeris as $galeri)
                                    <tr class="galeri-row" data-ekskul="{{ $galeri->ekstrakurikuler_id }}"
                                        data-tipe="{{ $galeri->tipe }}">
                                        <td>
                                            <input type="checkbox" class="form-check-input file-checkbox-list"
                                                value="{{ $galeri->id }}">
                                        </td>
                                        <td>
                                            <div class="galeri-thumbnail"
                                                style="width: 60px; height: 60px; overflow: hidden; border-radius: 8px;">
                                                @if ($galeri->tipe === 'gambar')
                                                    <img src="{{ Storage::url($galeri->path_file) }}"
                                                        alt="{{ $galeri->judul }}" class="w-100 h-100"
                                                        style="object-fit: cover; cursor: pointer;"
                                                        onclick="viewMedia('{{ Storage::url($galeri->path_file) }}', 'image', '{{ $galeri->judul }}')">
                                                @else
                                                    <div
                                                        class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center position-relative">
                                                        <i class="bi bi-play-circle text-white"
                                                            style="font-size: 1.5rem; cursor: pointer;"
                                                            onclick="viewMedia('{{ Storage::url($galeri->path_file) }}', 'video', '{{ $galeri->judul }}')"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $galeri->judul }}</strong>
                                            @if ($galeri->deskripsi)
                                                <br><small
                                                    class="text-muted">{{ Str::limit($galeri->deskripsi, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $galeri->ekstrakurikuler->nama }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $galeri->tipe === 'video' ? 'warning' : 'success' }}">
                                                <i
                                                    class="bi bi-{{ $galeri->tipe === 'video' ? 'play-circle' : 'image' }} me-1"></i>
                                                {{ ucfirst($galeri->tipe) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small
                                                class="text-muted">{{ $galeri->created_at->format('d M Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm"
                                                    onclick="viewMedia('{{ Storage::url($galeri->path_file) }}', '{{ $galeri->tipe }}', '{{ $galeri->judul }}')">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="{{ route('pembina.galeri.edit', $galeri) }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-outline-success btn-sm"
                                                    onclick="downloadFile('{{ Storage::url($galeri->path_file) }}', '{{ $galeri->judul }}')">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="deleteGaleri({{ $galeri->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $galeris->firstItem() }}-{{ $galeris->lastItem() }}
                        dari {{ $galeris->total() }} file
                    </div>
                    {{ $galeris->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Belum ada dokumentasi</p>
                    <p class="text-muted mb-4">Upload foto dan video kegiatan ekstrakurikuler untuk memulai galeri</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('pembina.galeri.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Upload Pertama
                        </a>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                            <i class="bi bi-cloud-upload me-1"></i>Upload Massal
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

<!-- Media Viewer Modal -->
<div class="modal fade" id="mediaViewerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="mediaTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <div id="mediaContainer"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" onclick="downloadCurrentMedia()">
                    <i class="bi bi-download me-1"></i>Download
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkUploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Ekstrakurikuler</label>
                        <select class="form-select" name="ekstrakurikuler_id" required>
                            <option value="">Pilih Ekstrakurikuler</option>
                            @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prefix Judul</label>
                        <input type="text" class="form-control" name="judul_prefix"
                            placeholder="Contoh: Kegiatan Latihan" value="Dokumentasi">
                        <div class="form-text">Judul akan menjadi: [Prefix] 1, [Prefix] 2, dst.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File (Foto & Video)</label>
                        <input type="file" class="form-control" name="files[]" multiple accept="image/*,video/*"
                            id="bulkFileInput" required>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Maksimal 20MB per file. Format: JPG, PNG, GIF, MP4, MOV, AVI
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <div id="filePreviewArea" class="mt-3" style="display: none;">
                        <h6>File yang akan diupload:</h6>
                        <div class="row" id="filePreviewContainer"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitBulkUpload()" id="bulkUploadBtn">
                    <i class="bi bi-cloud-upload me-1"></i>Upload Semua
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentMediaUrl = '';
        let currentMediaTitle = '';

        $(document).ready(function() {
            // View mode toggle
            $('input[name="viewMode"]').change(function() {
                if ($(this).attr('id') === 'gridView') {
                    $('#gridViewContainer').removeClass('d-none');
                    $('#listViewContainer').addClass('d-none');
                } else {
                    $('#gridViewContainer').addClass('d-none');
                    $('#listViewContainer').removeClass('d-none');
                }
            });

            // Filter functionality
            $('#filterEkskul, #filterTipe').change(function() {
                filterGallery();
            });

            // Select all functionality
            $('#selectAllFiles').change(function() {
                $('.file-checkbox').prop('checked', $(this).prop('checked'));
                updateDeleteButton();
            });

            $('#selectAllList').change(function() {
                $('.file-checkbox-list').prop('checked', $(this).prop('checked'));
                updateDeleteButton();
            });

            $('.file-checkbox, .file-checkbox-list').change(function() {
                updateDeleteButton();
            });

            // Bulk upload file preview
            $('#bulkFileInput').change(function() {
                previewBulkFiles(this.files);
            });
        });

        function viewMedia(url, type, title) {
            currentMediaUrl = url;
            currentMediaTitle = title;

            $('#mediaTitle').text(title);
            const container = $('#mediaContainer');
            container.empty();

            if (type === 'image' || type === 'gambar') {
                container.html(`
                <img src="${url}" class="img-fluid" style="max-height: 70vh; width: auto;" alt="${title}">
            `);
            } else {
                container.html(`
                <video controls class="w-100" style="max-height: 70vh;">
                    <source src="${url}" type="video/mp4">
                    Browser Anda tidak mendukung video.
                </video>
            `);
            }

            $('#mediaViewerModal').modal('show');
        }

        function downloadCurrentMedia() {
            downloadFile(currentMediaUrl, currentMediaTitle);
        }

        function downloadFile(url, filename) {
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function deleteGaleri(id) {
            Swal.fire({
                title: 'Hapus File?',
                text: 'File yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/pembina/galeri/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'File berhasil dihapus!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat menghapus file'
                            });
                        });
                }
            });
        }

        function deleteSelected() {
            const selectedIds = $('.file-checkbox:checked, .file-checkbox-list:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                showError('Pilih file yang ingin dihapus');
                return;
            }

            Swal.fire({
                title: `Hapus ${selectedIds.length} File?`,
                text: 'File yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implementation for bulk delete
                    console.log('Bulk delete:', selectedIds);
                    showSuccess('Fitur hapus massal akan segera tersedia');
                }
            });
        }

        function filterGallery() {
            const ekskulFilter = $('#filterEkskul').val();
            const tipeFilter = $('#filterTipe').val();

            $('.galeri-item, .galeri-row').each(function() {
                const ekskulId = $(this).data('ekskul');
                const tipe = $(this).data('tipe');

                let show = true;

                if (ekskulFilter && ekskulId != ekskulFilter) {
                    show = false;
                }

                if (tipeFilter && tipe !== tipeFilter) {
                    show = false;
                }

                if (show) {
                    $(this).removeClass('d-none').show();
                } else {
                    $(this).addClass('d-none').hide();
                }
            });
        }

        function sortGallery(order) {
            // Implementation for sorting
            showSuccess(`Mengurutkan berdasarkan ${order === 'newest' ? 'terbaru' : 'terlama'}`);
        }

        function updateDeleteButton() {
            const checkedItems = $('.file-checkbox:checked, .file-checkbox-list:checked').length;
            const deleteBtn = $('#deleteSelectedBtn');

            if (checkedItems > 0) {
                deleteBtn.show().find('span').text(`(${checkedItems})`);
            } else {
                deleteBtn.hide();
            }
        }

        function previewBulkFiles(files) {
            const container = $('#filePreviewContainer');
            const previewArea = $('#filePreviewArea');

            container.empty();

            if (files.length === 0) {
                previewArea.hide();
                return;
            }

            previewArea.show();

            Array.from(files).forEach((file, index) => {
                const isImage = file.type.startsWith('image/');
                const isVideo = file.type.startsWith('video/');

                if (!isImage && !isVideo) return;

                const col = $(`
                <div class="col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body p-2 text-center">
                            <div class="preview-container mb-2" style="height: 60px;">
                                ${isVideo ? 
                                    '<i class="bi bi-play-circle text-warning" style="font-size: 2rem;"></i>' :
                                    '<div class="img-preview bg-light" style="height: 100%; border-radius: 4px;"></div>'
                                }
                            </div>
                            <small class="text-muted">${file.name}</small>
                            <br>
                            <span class="badge bg-${isVideo ? 'warning' : 'success'}">${isVideo ? 'Video' : 'Foto'}</span>
                        </div>
                    </div>
                </div>
            `);

                container.append(col);

                // Preview image
                if (isImage) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        col.find('.img-preview').html(`
                        <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover; border-radius: 4px;">
                    `);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function submitBulkUpload() {
            const formData = new FormData(document.getElementById('bulkUploadForm'));
            const files = document.getElementById('bulkFileInput').files;

            if (files.length === 0) {
                showError('Pilih file yang akan diupload');
                return;
            }

            // Show loading with progress
            let uploadProgress = 0;
            Swal.fire({
                title: 'Mengupload...',
                html: `
                <div class="progress mb-2">
                    <div class="progress-bar" role="progressbar" style="width: ${uploadProgress}%"></div>
                </div>
                <small>Mengupload ${files.length} file...</small>
            `,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('pembina.galeri.bulkUpload') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `${files.length} file berhasil diupload!`,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#bulkUploadModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat mengupload file'
                    });
                });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Esc to close modals
            if (e.key === 'Escape') {
                $('#mediaViewerModal, #bulkUploadModal').modal('hide');
            }

            // Ctrl + A to select all (prevent default browser behavior)
            if (e.ctrlKey && e.key === 'a') {
                e.preventDefault();
                $('#selectAllFiles').prop('checked', true).trigger('change');
            }

            // Delete key to delete selected
            if (e.key === 'Delete') {
                e.preventDefault();
                deleteSelected();
            }
        });

        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .galeri-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .galeri-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .galeri-media {
            position: relative;
            overflow: hidden;
            border-radius: 8px 8px 0 0;
        }

        .galeri-media img,
        .galeri-media video {
            transition: transform 0.3s ease;
        }

        .galeri-media:hover img,
        .galeri-media:hover video {
            transform: scale(1.05);
        }

        .galeri-thumbnail {
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .galeri-thumbnail:hover {
            border-color: var(--bs-primary);
            transform: scale(1.05);
        }

        .btn-group .btn {
            border-radius: 0;
            flex: 1;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .modal-xl .modal-content {
            background: #000;
        }

        .modal-xl .modal-body {
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .file-checkbox,
        .file-checkbox-list {
            transform: scale(1.2);
        }

        /* Responsive grid */
        @media (max-width: 768px) {
            .galeri-item {
                margin-bottom: 1rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                border-radius: 4px !important;
                margin-bottom: 2px;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Custom scrollbar */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }

        /* Lazy loading placeholder */
        .lazy {
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lazy.loaded {
            opacity: 1;
        }

        /* Animation for new items */
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
    </style>
@endpush
