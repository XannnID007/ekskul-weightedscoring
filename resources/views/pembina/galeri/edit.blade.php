@extends('layouts.app')

@section('title', 'Edit File Galeri')
@section('page-title', 'Edit File Galeri')
@section('page-description', 'Ubah informasi file galeri kegiatan ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.galeri.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('pembina.galeri.show', $galeri) }}" class="btn btn-light">
            <i class="bi bi-eye me-1"></i>Lihat Detail
        </a>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Edit Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Informasi File
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembina.galeri.update', $galeri) }}" method="POST"
                        enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')

                        <!-- Current File Preview -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-file-earmark me-1"></i>File Saat Ini
                            </label>
                            <div class="current-file-preview">
                                <div class="card border-info">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="current-thumbnail">
                                                    @if ($galeri->tipe === 'gambar')
                                                        <img src="{{ Storage::url($galeri->path_file) }}"
                                                            alt="{{ $galeri->judul }}" class="img-fluid rounded"
                                                            style="max-height: 120px; width: 100%; object-fit: cover;"
                                                            onclick="viewCurrentMedia()">
                                                    @else
                                                        <div class="video-thumbnail position-relative"
                                                            style="height: 120px; background: #000; border-radius: 8px; cursor: pointer;"
                                                            onclick="viewCurrentMedia()">
                                                            <video class="w-100 h-100"
                                                                style="object-fit: cover; border-radius: 8px;">
                                                                <source src="{{ Storage::url($galeri->path_file) }}"
                                                                    type="video/mp4">
                                                            </video>
                                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                                <i class="bi bi-play-circle text-white"
                                                                    style="font-size: 3rem;"></i>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <h6 class="mb-2">{{ $galeri->judul }}</h6>
                                                <div class="d-flex gap-2 mb-2">
                                                    <span
                                                        class="badge bg-{{ $galeri->tipe === 'video' ? 'warning' : 'success' }}">
                                                        <i
                                                            class="bi bi-{{ $galeri->tipe === 'video' ? 'play-circle' : 'image' }} me-1"></i>
                                                        {{ ucfirst($galeri->tipe) }}
                                                    </span>
                                                    <span
                                                        class="badge bg-primary">{{ $galeri->ekstrakurikuler->nama }}</span>
                                                </div>
                                                <p class="text-muted mb-2 small">
                                                    <i class="bi bi-calendar me-1"></i>Diupload
                                                    {{ $galeri->created_at->diffForHumans() }}
                                                    <br>
                                                    <i class="bi bi-person me-1"></i>oleh
                                                    {{ $galeri->uploader->name ?? 'Unknown' }}
                                                </p>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="viewCurrentMedia()">
                                                        <i class="bi bi-eye me-1"></i>Lihat
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success btn-sm"
                                                        onclick="downloadCurrentFile()">
                                                        <i class="bi bi-download me-1"></i>Download
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Replace File (Optional) -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-arrow-repeat me-1"></i>Ganti File
                                <span class="text-muted">(Opsional)</span>
                            </label>
                            <input type="file" class="form-control" name="file" id="newFileInput"
                                accept="image/*,video/*" onchange="handleNewFile(this)">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Kosongkan jika tidak ingin mengganti file. Format: JPG, PNG, GIF, MP4, MOV, AVI (Maks. 20MB)
                            </div>

                            <!-- New File Preview -->
                            <div class="new-file-preview mt-3" id="newFilePreview" style="display: none;">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning bg-opacity-10">
                                        <h6 class="mb-0 text-warning">
                                            <i class="bi bi-arrow-repeat me-1"></i>File Pengganti
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="preview-thumbnail me-3" id="newPreviewThumbnail">
                                                <!-- New file preview -->
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" id="newFileName"></h6>
                                                <p class="text-muted mb-0 small" id="newFileInfo"></p>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="clearNewFile()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ekstrakurikuler Selection -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-collection me-1"></i>Ekstrakurikuler
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="ekstrakurikuler_id" required>
                                <option value="">Pilih Ekstrakurikuler</option>
                                @foreach ($ekstrakurikulers as $ekskul)
                                    <option value="{{ $ekskul->id }}"
                                        {{ old('ekstrakurikuler_id', $galeri->ekstrakurikuler_id) == $ekskul->id ? 'selected' : '' }}>
                                        {{ $ekskul->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ekstrakurikuler_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-type me-1"></i>Judul
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="judul"
                                placeholder="Masukkan judul untuk file ini..." value="{{ old('judul', $galeri->judul) }}"
                                required>
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-card-text me-1"></i>Deskripsi
                                <span class="text-muted">(Opsional)</span>
                            </label>
                            <textarea class="form-control" name="deskripsi" rows="3"
                                placeholder="Tambahkan deskripsi detail tentang foto/video ini...">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('pembina.galeri.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- File Info & Actions -->
        <div class="col-xl-4">
            <!-- File Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle text-info me-2"></i>Informasi File
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nama File:</strong><br>
                        <span class="text-muted small">{{ basename($galeri->path_file) }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Tipe:</strong><br>
                        <span class="badge bg-{{ $galeri->tipe === 'video' ? 'warning' : 'success' }}">
                            {{ ucfirst($galeri->tipe) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Ekstrakurikuler:</strong><br>
                        <span class="badge bg-primary">{{ $galeri->ekstrakurikuler->nama }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Diupload:</strong><br>
                        <span class="text-muted">{{ $galeri->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Terakhir diubah:</strong><br>
                        <span class="text-muted">{{ $galeri->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="mb-0">
                        <strong>Diupload oleh:</strong><br>
                        <span class="text-muted">{{ $galeri->uploader->name ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>

            <!-- Edit Tips -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips Edit
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small text-muted mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            <strong>Judul:</strong> Gunakan nama yang jelas dan deskriptif
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            <strong>Deskripsi:</strong> Tambahkan konteks waktu dan kegiatan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            <strong>File Baru:</strong> Hanya upload jika ingin mengganti
                        </li>
                        <li class="mb-0">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            <strong>Kategori:</strong> Pastikan ekstrakurikuler sesuai
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Viewer Modal -->
    <div class="modal fade" id="mediaViewerModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="mediaTitle">{{ $galeri->judul }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <div id="mediaContainer">
                        @if ($galeri->tipe === 'gambar')
                            <img src="{{ Storage::url($galeri->path_file) }}" class="img-fluid"
                                style="max-height: 70vh; width: auto;" alt="{{ $galeri->judul }}">
                        @else
                            <video controls class="w-100" style="max-height: 70vh;">
                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                Browser Anda tidak mendukung video.
                            </video>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light" onclick="downloadCurrentFile()">
                        <i class="bi bi-download me-1"></i>Download
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .current-thumbnail {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .current-thumbnail:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .preview-thumbnail {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .preview-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-thumbnail::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            pointer-events: none;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function handleNewFile(input) {
            if (input.files && input.files.length > 0) {
                const file = input.files[0];

                // Validate file
                if (!validateFile(file)) {
                    input.value = '';
                    return;
                }

                showNewFilePreview(file);
            } else {
                document.getElementById('newFilePreview').style.display = 'none';
            }
        }

        function validateFile(file) {
            const maxSize = 20 * 1024 * 1024; // 20MB
            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar! Maksimal 20MB.');
                return false;
            }

            const allowedTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
                'video/mp4', 'video/mov', 'video/avi', 'video/quicktime'
            ];

            if (!allowedTypes.includes(file.type.toLowerCase())) {
                alert('Tipe file tidak didukung!\n\nFormat yang diizinkan:\n• Foto: JPG, PNG, GIF\n• Video: MP4, MOV, AVI');
                return false;
            }

            return true;
        }

        function showNewFilePreview(file) {
            const preview = document.getElementById('newFilePreview');
            const thumbnail = document.getElementById('newPreviewThumbnail');
            const fileName = document.getElementById('newFileName');
            const fileInfo = document.getElementById('newFileInfo');

            fileName.textContent = file.name;
            fileInfo.textContent = `${formatFileSize(file.size)} • ${file.type.split('/')[1].toUpperCase()}`;

            thumbnail.innerHTML = '';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                thumbnail.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                thumbnail.innerHTML = '<i class="bi bi-play-circle-fill text-warning" style="font-size: 2rem;"></i>';
            }

            preview.style.display = 'block';
        }

        function clearNewFile() {
            document.getElementById('newFileInput').value = '';
            document.getElementById('newFilePreview').style.display = 'none';
        }

        function viewCurrentMedia() {
            const modal = new bootstrap.Modal(document.getElementById('mediaViewerModal'));
            modal.show();
        }

        function downloadCurrentFile() {
            const link = document.createElement('a');
            link.href = '{{ Storage::url($galeri->path_file) }}';
            link.download = '{{ $galeri->judul }}';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function deleteFile() {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?\n\nFile yang dihapus tidak dapat dikembalikan!')) {
                // Create form for delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('pembina.galeri.destroy', $galeri) }}';

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Add method override
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form submit handler
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editForm');
            form.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
            });
        });
    </script>
@endpush
