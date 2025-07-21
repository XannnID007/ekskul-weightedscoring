@extends('layouts.app')

@section('title', 'Upload File Galeri')
@section('page-title', 'Upload File Galeri')
@section('page-description', 'Tambahkan foto atau video kegiatan ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.galeri.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button type="button" class="btn btn-light" onclick="previewFile()">
            <i class="bi bi-eye me-1"></i>Preview
        </button>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Upload Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-cloud-upload text-primary me-2"></i>Form Upload File
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembina.galeri.store') }}" method="POST" enctype="multipart/form-data"
                        id="uploadForm">
                        @csrf

                        <!-- File Upload - Simplified Version -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-file-earmark-plus me-1"></i>Pilih File
                                <span class="text-danger">*</span>
                            </label>

                            <!-- Simple File Input (Always Visible) -->
                            <input type="file" class="form-control mb-3" name="file" id="fileInput"
                                accept="image/*,video/*" required onchange="handleFileChange(this)">

                            <div class="form-text mb-3">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Format yang didukung:</strong><br>
                                • <strong>Foto:</strong> JPG, PNG, GIF (Maks. 20MB)<br>
                                • <strong>Video:</strong> MP4, MOV, AVI (Maks. 20MB)
                            </div>

                            <!-- Drag & Drop Area (Optional) -->
                            <div class="upload-drop-zone p-4 text-center" id="dropZone" style="display: none;">
                                <div class="border border-2 border-dashed rounded p-4">
                                    <i class="bi bi-cloud-upload text-primary" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 mb-2">Atau drag & drop file di sini</h6>
                                    <p class="text-muted mb-0">Maksimal 20MB per file</p>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div class="file-preview mt-3" id="filePreview" style="display: none;">
                                <div class="card border-primary">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="preview-thumbnail me-3" id="previewThumbnail">
                                                <!-- Preview will be loaded here -->
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" id="previewFileName">No file selected</h6>
                                                <p class="text-muted mb-1 small" id="previewFileInfo">-</p>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" id="uploadProgress"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="clearFile()">
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
                                        {{ old('ekstrakurikuler_id') == $ekskul->id ? 'selected' : '' }}>
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
                                placeholder="Masukkan judul untuk file ini..." value="{{ old('judul') }}" required>
                            <div class="form-text">
                                <i class="bi bi-lightbulb me-1"></i>
                                Contoh: "Latihan Rutin Minggu Ke-3", "Pertandingan Futsal Antar Kelas"
                            </div>
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
                                placeholder="Tambahkan deskripsi detail tentang foto/video ini...">{{ old('deskripsi') }}</textarea>
                            <div class="form-text">
                                Jelaskan konteks, waktu, atau hal menarik dari dokumentasi ini
                            </div>
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
                                <i class="bi bi-cloud-upload me-1"></i>Upload File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upload Tips -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips Upload
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-success">
                            <i class="bi bi-check-circle me-1"></i>Foto yang Baik
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Resolusi minimal 1024x768</li>
                            <li>• Pencahayaan yang cukup</li>
                            <li>• Tidak blur atau shake</li>
                            <li>• Menampilkan aktivitas dengan jelas</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-info">
                            <i class="bi bi-play-circle me-1"></i>Video yang Baik
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Durasi 30 detik - 5 menit</li>
                            <li>• Kualitas HD (720p atau lebih)</li>
                            <li>• Audio yang jernih</li>
                            <li>• Fokus pada kegiatan utama</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="bi bi-tag me-1"></i>Judul yang Baik
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Jelas dan deskriptif</li>
                            <li>• Mencantumkan tanggal/event</li>
                            <li>• Maksimal 50 karakter</li>
                            <li>• Mudah dipahami siswa</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong><br>
                        <small>
                            Pastikan file yang diupload tidak melanggar privasi siswa dan
                            sudah mendapat izin dari semua pihak yang terlibat.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Recent Uploads -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history text-secondary me-2"></i>Upload Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentUploads = auth()
                            ->user()
                            ->ekstrakurikulerSebagaiPembina()
                            ->with([
                                'galeris' => function ($query) {
                                    $query->latest()->limit(3);
                                },
                            ])
                            ->get()
                            ->pluck('galeris')
                            ->flatten();
                    @endphp

                    @if ($recentUploads->count() > 0)
                        @foreach ($recentUploads as $recent)
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">
                                    @if ($recent->tipe === 'gambar')
                                        <i class="bi bi-image text-success"></i>
                                    @else
                                        <i class="bi bi-play-circle text-warning"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small">{{ Str::limit($recent->judul, 25) }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $recent->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small mb-0">Belum ada upload terbaru</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .upload-drop-zone {
            transition: all 0.3s ease;
        }

        .upload-drop-zone.drag-over {
            background-color: rgba(32, 178, 170, 0.1);
            border-color: var(--bs-primary) !important;
        }

        .file-preview .card {
            border-left: 4px solid var(--bs-primary);
        }

        .preview-thumbnail {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            overflow: hidden;
        }

        .preview-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .progress {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Simple and reliable file handling
        function handleFileChange(input) {
            console.log('File input changed:', input.files);

            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                console.log('Selected file:', file);

                // Validate file
                if (!validateFile(file)) {
                    input.value = ''; // Clear invalid file
                    return;
                }

                // Show file preview
                showFilePreview(file);

                // Show drag drop area for future uploads
                document.getElementById('dropZone').style.display = 'block';
            } else {
                // Hide preview if no file
                document.getElementById('filePreview').style.display = 'none';
            }
        }

        function validateFile(file) {
            // Size validation (20MB)
            const maxSize = 20 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('Ukuran file terlalu besar! Maksimal 20MB.');
                return false;
            }

            // Type validation
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

        function showFilePreview(file) {
            const preview = document.getElementById('filePreview');
            const thumbnail = document.getElementById('previewThumbnail');
            const fileName = document.getElementById('previewFileName');
            const fileInfo = document.getElementById('previewFileInfo');

            // Update file info
            fileName.textContent = file.name;
            fileInfo.textContent = `${formatFileSize(file.size)} • ${file.type.split('/')[1].toUpperCase()}`;

            // Create thumbnail
            thumbnail.innerHTML = '';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.alt = 'Preview';

                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                thumbnail.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                thumbnail.innerHTML = '<i class="bi bi-play-circle-fill text-warning" style="font-size: 2rem;"></i>';
                thumbnail.style.backgroundColor = '#f8f9fa';
            }

            // Show preview
            preview.style.display = 'block';
        }

        function clearFile() {
            const fileInput = document.getElementById('fileInput');
            const preview = document.getElementById('filePreview');
            const progress = document.getElementById('uploadProgress');

            // Clear file input
            fileInput.value = '';

            // Hide preview
            preview.style.display = 'none';

            // Reset progress
            progress.style.width = '0%';

            console.log('File cleared');
        }

        function previewFile() {
            const fileInput = document.getElementById('fileInput');

            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Pilih file terlebih dahulu!');
                return;
            }

            const file = fileInput.files[0];

            if (file.type.startsWith('image/')) {
                // Preview image in new window
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newWindow = window.open('', '_blank');
                    newWindow.document.write(`
                <html>
                    <head><title>Preview: ${file.name}</title></head>
                    <body style="margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; background:#000;">
                        <img src="${e.target.result}" style="max-width:100%; max-height:100%; object-fit:contain;">
                    </body>
                </html>
            `);
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                // Preview video in new window
                const videoUrl = URL.createObjectURL(file);
                const newWindow = window.open('', '_blank');
                newWindow.document.write(`
            <html>
                <head><title>Preview: ${file.name}</title></head>
                <body style="margin:0; display:flex; justify-content:center; align-items:center; min-height:100vh; background:#000;">
                    <video controls style="max-width:100%; max-height:100%;">
                        <source src="${videoUrl}" type="${file.type}">
                    </video>
                </body>
            </html>
        `);
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop functionality (optional enhancement)
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');

            if (dropZone && fileInput) {
                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('drag-over');
                });

                dropZone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('drag-over');
                });

                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('drag-over');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        // Create a new FileList and assign to input
                        const dt = new DataTransfer();
                        dt.items.add(files[0]);
                        fileInput.files = dt.files;

                        // Trigger change event
                        handleFileChange(fileInput);
                    }
                });
            }

            // Form submit handler
            const form = document.getElementById('uploadForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const fileInput = document.getElementById('fileInput');

                    if (!fileInput.files || fileInput.files.length === 0) {
                        e.preventDefault();
                        alert('Pilih file terlebih dahulu!');
                        return false;
                    }

                    // Show loading state
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Mengupload...';

                    // Show progress (fake progress for user feedback)
                    const progressBar = document.getElementById('uploadProgress');
                    let progress = 0;
                    const interval = setInterval(function() {
                        progress += Math.random() * 15;
                        if (progress > 90) progress = 90;
                        progressBar.style.width = progress + '%';
                    }, 200);

                    // Clear interval after 10 seconds (fallback)
                    setTimeout(() => clearInterval(interval), 10000);
                });
            }
        });

        // Global error handler
        window.addEventListener('error', function(e) {
            console.error('JavaScript Error:', e.error);
        });

        console.log('Upload script loaded successfully');
    </script>
@endpush
