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

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-file-earmark-plus me-1"></i>Pilih File
                                <span class="text-danger">*</span>
                            </label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" class="form-control d-none" name="file" id="fileInput"
                                    accept="image/*,video/*" required>
                                <div class="upload-content text-center p-4">
                                    <i class="bi bi-cloud-upload text-primary" style="font-size: 3rem;"></i>
                                    <h6 class="mt-3 mb-2">Drag & Drop file atau klik untuk browse</h6>
                                    <p class="text-muted mb-2">Maksimal 20MB per file</p>
                                    <p class="text-muted small">
                                        <strong>Foto:</strong> JPG, PNG, GIF<br>
                                        <strong>Video:</strong> MP4, MOV, AVI
                                    </p>
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="$('#fileInput').click()">
                                        <i class="bi bi-folder2-open me-1"></i>Browse File
                                    </button>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div class="upload-preview mt-3 d-none" id="filePreview">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="preview-container me-3" id="previewContainer">
                                                <!-- Preview will be loaded here -->
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" id="fileName"></h6>
                                                <p class="text-muted mb-1 small" id="fileInfo"></p>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" id="uploadProgress" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="removeFile()">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            console.log('Upload script loaded');

            // Test if elements exist
            if ($('#fileInput').length === 0) {
                console.error('fileInput element not found!');
            }
            if ($('#uploadArea').length === 0) {
                console.error('uploadArea element not found!');
            }

            // File input change handler
            $('#fileInput').change(function(e) {
                console.log('File input changed:', this.files);
                if (this.files && this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            // Drag and drop handlers
            $('#uploadArea').on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Drag over');
                $(this).addClass('drag-over');
            });

            $('#uploadArea').on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Drag leave');
                $(this).removeClass('drag-over');
            });

            $('#uploadArea').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('File dropped');
                $(this).removeClass('drag-over');

                const files = e.originalEvent.dataTransfer.files;
                console.log('Dropped files:', files);

                if (files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            // Click to browse - gunakan event delegation yang lebih spesifik
            $(document).on('click', '#uploadArea', function(e) {
                e.preventDefault();
                console.log('Upload area clicked');
                $('#fileInput').trigger('click');
            });

            // Alternative click handler untuk button browse
            $(document).on('click', '[onclick*="fileInput"]', function(e) {
                e.preventDefault();
                console.log('Browse button clicked');
                $('#fileInput').trigger('click');
            });

            // Form submit handler
            $('#uploadForm').submit(function(e) {
                e.preventDefault();
                console.log('Form submitted');

                const fileInput = $('#fileInput')[0];
                if (!fileInput.files || fileInput.files.length === 0) {
                    showError('Pilih file terlebih dahulu!');
                    return false;
                }

                const formData = new FormData(this);
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();

                // Disable submit button
                submitBtn.prop('disabled', true).html(
                    '<i class="bi bi-spinner-border spinner-border-sm me-1"></i>Uploading...');

                // Show progress
                $('#uploadProgress').css('width', '0%');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                const percentComplete = (evt.loaded / evt.total) * 100;
                                $('#uploadProgress').css('width', percentComplete +
                                '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        console.log('Upload success:', response);
                        showSuccess('File berhasil diupload!');
                        setTimeout(() => {
                            window.location.href = $('#uploadForm').data('redirect') ||
                                "{{ route('pembina.galeri.index') }}";
                        }, 1500);
                    },
                    error: function(xhr) {
                        console.error('Upload error:', xhr);
                        let errorMessage = 'Terjadi kesalahan saat mengupload file.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                '\n');
                        }
                        showError(errorMessage);

                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });

        function handleFileSelect(file) {
            console.log('Handling file:', file);

            if (!file) {
                console.error('No file provided');
                return;
            }

            // Validate file size (20MB)
            const maxSize = 20 * 1024 * 1024; // 20MB in bytes
            if (file.size > maxSize) {
                console.error('File too large:', file.size, 'bytes');
                showError('Ukuran file terlalu besar! Maksimal 20MB.');
                return;
            }

            // Validate file type
            const allowedTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
                'video/mp4', 'video/mov', 'video/avi', 'video/quicktime'
            ];

            console.log('File type:', file.type);

            if (!allowedTypes.includes(file.type.toLowerCase())) {
                console.error('Invalid file type:', file.type);
                showError('Tipe file tidak didukung! Hanya mendukung JPG, PNG, GIF, MP4, MOV, AVI.');
                return;
            }

            // Update file input - Method yang lebih kompatibel
            try {
                const fileInput = document.getElementById('fileInput');
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                console.log('File added to input:', fileInput.files);
            } catch (error) {
                console.error('Error setting file input:', error);
                // Fallback: trigger change event manually
                $('#fileInput').trigger('change');
            }

            // Show preview
            showFilePreview(file);
        }

        function showFilePreview(file) {
            console.log('Showing preview for:', file.name);

            const filePreview = $('#filePreview');
            const previewContainer = $('#previewContainer');
            const fileName = $('#fileName');
            const fileInfo = $('#fileInfo');

            // Update file info
            fileName.text(file.name);
            fileInfo.text(`${formatFileSize(file.size)} • ${file.type.split('/')[1].toUpperCase()}`);

            // Create preview
            previewContainer.empty();

            if (file.type.startsWith('image/')) {
                console.log('Creating image preview');
                const img = $('<img>').addClass('img-thumbnail').css({
                    'width': '60px',
                    'height': '60px',
                    'object-fit': 'cover',
                    'border-radius': '8px'
                });

                const reader = new FileReader();
                reader.onload = function(e) {
                    img.attr('src', e.target.result);
                    console.log('Image preview loaded');
                };
                reader.onerror = function(e) {
                    console.error('Error reading file:', e);
                };
                reader.readAsDataURL(file);

                previewContainer.append(img);
            } else if (file.type.startsWith('video/')) {
                console.log('Creating video preview');
                const videoIcon = $('<div>').addClass(
                    'd-flex align-items-center justify-content-center bg-warning text-white rounded').css({
                    'width': '60px',
                    'height': '60px'
                }).html('<i class="bi bi-play-circle-fill" style="font-size: 2rem;"></i>');

                previewContainer.append(videoIcon);
            }

            // Hide upload area, show preview
            $('#uploadArea').hide();
            filePreview.removeClass('d-none').show();

            console.log('Preview shown');
        }

        function removeFile() {
            console.log('Removing file');

            // Clear file input
            $('#fileInput').val('');

            // Hide preview, show upload area
            $('#filePreview').addClass('d-none');
            $('#uploadArea').show();

            // Reset progress
            $('#uploadProgress').css('width', '0%');

            console.log('File removed');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function previewFile() {
            console.log('Preview file clicked');

            const fileInput = $('#fileInput')[0];
            if (!fileInput.files || fileInput.files.length === 0) {
                showError('Pilih file terlebih dahulu!');
                return;
            }

            const file = fileInput.files[0];
            console.log('Previewing file:', file);

            if (file.type.startsWith('image/')) {
                // Preview image in modal
                const reader = new FileReader();
                reader.onload = function(e) {
                    Swal.fire({
                        title: 'Preview Gambar',
                        html: `<img src="${e.target.result}" class="img-fluid" style="max-height: 400px; border-radius: 8px;">`,
                        width: 'auto',
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                // Preview video in modal
                const videoUrl = URL.createObjectURL(file);
                Swal.fire({
                    title: 'Preview Video',
                    html: `<video controls style="max-width: 100%; max-height: 400px; border-radius: 8px;">
                     <source src="${videoUrl}" type="${file.type}">
                     Browser Anda tidak mendukung tag video.
                   </video>`,
                    width: 'auto',
                    showCloseButton: true,
                    showConfirmButton: false,
                    didClose: () => {
                        URL.revokeObjectURL(videoUrl);
                    }
                });
            }
        }

        // Enhanced error handling functions
        function showSuccess(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert('Berhasil: ' + message);
            }
        }

        function showError(message) {
            console.error('Error:', message);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: message
                });
            } else {
                alert('Error: ' + message);
            }
        }

        // Add enhanced styles
        const enhancedStyles = `
    .upload-area {
        border: 2px dashed #6c757d;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        min-height: 200px;
    }
    
    .upload-area:hover,
    .upload-area.drag-over {
        border-color: var(--bs-primary, #20b2aa);
        background-color: rgba(32, 178, 170, 0.1);
        transform: translateY(-2px);
    }
    
    .upload-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        text-align: center;
    }
    
    .upload-preview .card {
        border-left: 4px solid var(--bs-primary, #20b2aa);
    }
    
    .preview-container img {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .progress {
        background-color: rgba(255,255,255,0.1);
    }
    
    .progress-bar {
        background: linear-gradient(90deg, var(--bs-primary, #20b2aa), var(--bs-primary-dark, #17a2b8));
        transition: width 0.3s ease;
    }
    
    /* Loading animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
        animation: spin 0.75s linear infinite;
    }
`;

        // Inject styles
        if (!document.getElementById('upload-styles')) {
            const styleSheet = document.createElement('style');
            styleSheet.id = 'upload-styles';
            styleSheet.textContent = enhancedStyles;
            document.head.appendChild(styleSheet);
        }

        // Test function to check if everything is working
        function testUpload() {
            console.log('=== UPLOAD DEBUG INFO ===');
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            console.log('SweetAlert loaded:', typeof Swal !== 'undefined');
            console.log('File input exists:', $('#fileInput').length > 0);
            console.log('Upload area exists:', $('#uploadArea').length > 0);
            console.log('Upload form exists:', $('#uploadForm').length > 0);
            console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
            console.log('========================');
        }

        // Run test on page load
        $(document).ready(function() {
            setTimeout(testUpload, 1000);
        });
    </script>
@endpush
