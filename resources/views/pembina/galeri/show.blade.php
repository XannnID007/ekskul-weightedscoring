@extends('layouts.app')

@section('title', 'Detail Galeri')
@section('page-title', 'Detail Dokumentasi')
@section('page-description', 'Lihat detail foto atau video kegiatan ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.galeri.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('pembina.galeri.edit', $galeri) }}" class="btn btn-outline-light">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <button type="button" class="btn btn-light" onclick="downloadFile()">
            <i class="bi bi-download me-1"></i>Download
        </button>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-share me-1"></i>Bagikan
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="copyLink()">
                        <i class="bi bi-link-45deg me-2"></i>Salin Link
                    </a></li>
                <li><a class="dropdown-item" href="#" onclick="shareWhatsApp()">
                        <i class="bi bi-whatsapp me-2"></i>WhatsApp
                    </a></li>
                <li><a class="dropdown-item" href="#" onclick="shareEmail()">
                        <i class="bi bi-envelope me-2"></i>Email
                    </a></li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Media Display -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $galeri->judul }}</h5>
                        <div class="d-flex align-items-center gap-3 text-muted">
                            <span>
                                <i class="bi bi-collection me-1"></i>{{ $galeri->ekstrakurikuler->nama }}
                            </span>
                            <span>
                                <i class="bi bi-clock me-1"></i>{{ $galeri->created_at->format('d M Y H:i') }}
                            </span>
                            <span>
                                <i class="bi bi-person me-1"></i>{{ $galeri->uploader->name ?? 'System' }}
                            </span>
                        </div>
                    </div>
                    <span class="badge bg-{{ $galeri->tipe === 'video' ? 'warning' : 'success' }} fs-6">
                        <i class="bi bi-{{ $galeri->tipe === 'video' ? 'play-circle' : 'image' }} me-1"></i>
                        {{ ucfirst($galeri->tipe) }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="media-container position-relative">
                        @if ($galeri->tipe === 'gambar')
                            <img src="{{ Storage::url($galeri->path_file) }}" alt="{{ $galeri->judul }}" class="w-100"
                                style="max-height: 600px; object-fit: contain; background: #000;" id="mainImage">

                            <!-- Image Controls Overlay -->
                            <div class="image-controls position-absolute top-0 end-0 m-3">
                                <div class="btn-group-vertical">
                                    <button class="btn btn-dark btn-sm" onclick="toggleFullscreen()" title="Fullscreen">
                                        <i class="bi bi-arrows-fullscreen"></i>
                                    </button>
                                    <button class="btn btn-dark btn-sm" onclick="zoomIn()" title="Zoom In">
                                        <i class="bi bi-zoom-in"></i>
                                    </button>
                                    <button class="btn btn-dark btn-sm" onclick="zoomOut()" title="Zoom Out">
                                        <i class="bi bi-zoom-out"></i>
                                    </button>
                                    <button class="btn btn-dark btn-sm" onclick="resetZoom()" title="Reset">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </div>
                            </div>
                        @else
                            <video controls class="w-100" style="max-height: 600px; background: #000;" id="mainVideo">
                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/quicktime">
                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/avi">
                                Browser Anda tidak mendukung tag video.
                            </video>

                            <!-- Video Controls Overlay -->
                            <div class="video-info position-absolute bottom-0 start-0 m-3 text-white">
                                <div class="bg-dark bg-opacity-75 rounded p-2">
                                    <small id="videoInfo">
                                        <i class="bi bi-info-circle me-1"></i>Loading video info...
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($galeri->deskripsi)
                    <div class="card-footer">
                        <div class="mb-2">
                            <h6 class="text-primary mb-2">
                                <i class="bi bi-card-text me-1"></i>Deskripsi
                            </h6>
                            <p class="mb-0 text-justify">{{ $galeri->deskripsi }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- File Information -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informasi File
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="text-muted small">Nama File</label>
                            <p class="mb-0 fw-bold">{{ $galeri->judul }}</p>
                        </div>

                        <div class="col-6">
                            <label class="text-muted small">Tipe</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $galeri->tipe === 'video' ? 'warning' : 'success' }}">
                                    {{ ucfirst($galeri->tipe) }}
                                </span>
                            </p>
                        </div>

                        <div class="col-6">
                            <label class="text-muted small">Format</label>
                            <p class="mb-0" id="fileFormat">-</p>
                        </div>

                        <div class="col-6">
                            <label class="text-muted small">Ukuran</label>
                            <p class="mb-0" id="fileSize">-</p>
                        </div>

                        <div class="col-6">
                            <label class="text-muted small">Resolusi</label>
                            <p class="mb-0" id="fileResolution">-</p>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Diupload</label>
                            <p class="mb-0">{{ $galeri->created_at->format('d M Y H:i') }}</p>
                            <small class="text-muted">{{ $galeri->created_at->diffForHumans() }}</small>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Ekstrakurikuler</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $galeri->ekstrakurikuler->nama }}</span>
                            </p>
                        </div>

                        <div class="col-12">
                            <label class="text-muted small">Diupload oleh</label>
                            <p class="mb-0">
                                <i class="bi bi-person-circle me-1"></i>{{ $galeri->uploader->name ?? 'System' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>Statistik
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h5 class="text-primary mb-1" id="viewCount">-</h5>
                                <small class="text-muted">Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h5 class="text-success mb-1" id="downloadCount">-</h5>
                                <small class="text-muted">Downloads</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Fullscreen Modal -->
<div class="modal fade" id="fullscreenModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">{{ $galeri->judul }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center p-0">
                <div id="fullscreenContainer" class="w-100 h-100 d-flex align-items-center justify-content-center">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer border-0">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-light" onclick="downloadFile()">
                        <i class="bi bi-download me-1"></i>Download
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentZoom = 1;
        const maxZoom = 3;
        const minZoom = 0.5;
        const zoomStep = 0.2;

        $(document).ready(function() {
            // Load file information
            loadFileInfo();

            // Setup video info if video
            @if ($galeri->tipe === 'video')
                setupVideoInfo();
            @endif

            // Setup keyboard shortcuts
            setupKeyboardShorts();

            // Track view
            trackView();
        });

        function loadFileInfo() {
            const fileUrl = '{{ Storage::url($galeri->path_file) }}';
            const fileName = '{{ basename($galeri->path_file) }}';

            // Get file extension
            const extension = fileName.split('.').pop().toUpperCase();
            $('#fileFormat').text(extension);

            // Load file size and resolution
            @if ($galeri->tipe === 'gambar')
                const img = new Image();
                img.onload = function() {
                    $('#fileResolution').text(`${this.naturalWidth} x ${this.naturalHeight}px`);
                };
                img.src = fileUrl;

                // Get file size via fetch (approximate)
                fetch(fileUrl, {
                        method: 'HEAD'
                    })
                    .then(response => {
                        const contentLength = response.headers.get('Content-Length');
                        if (contentLength) {
                            const size = formatFileSize(parseInt(contentLength));
                            $('#fileSize').text(size);
                        }
                    })
                    .catch(() => {
                        $('#fileSize').text('Tidak diketahui');
                    });
            @else
                // For video
                const video = document.getElementById('mainVideo');
                if (video) {
                    video.addEventListener('loadedmetadata', function() {
                        $('#fileResolution').text(`${this.videoWidth} x ${this.videoHeight}px`);
                    });
                }
            @endif
        }

        function setupVideoInfo() {
            const video = document.getElementById('mainVideo');
            if (video) {
                video.addEventListener('loadedmetadata', function() {
                    const duration = formatDuration(this.duration);
                    const info = `Durasi: ${duration} | ${this.videoWidth}x${this.videoHeight}px`;
                    $('#videoInfo').html('<i class="bi bi-info-circle me-1"></i>' + info);
                });
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function formatDuration(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        // Image zoom functions
        function zoomIn() {
            if (currentZoom < maxZoom) {
                currentZoom += zoomStep;
                applyZoom();
            }
        }

        function zoomOut() {
            if (currentZoom > minZoom) {
                currentZoom -= zoomStep;
                applyZoom();
            }
        }

        function resetZoom() {
            currentZoom = 1;
            applyZoom();
        }

        function applyZoom() {
            const img = document.getElementById('mainImage');
            if (img) {
                img.style.transform = `scale(${currentZoom})`;
                img.style.transition = 'transform 0.3s ease';
            }
        }

        // Fullscreen functionality
        function toggleFullscreen() {
            const modal = $('#fullscreenModal');
            const container = $('#fullscreenContainer');

            @if ($galeri->tipe === 'gambar')
                container.html(`
                <img src="{{ Storage::url($galeri->path_file) }}" 
                     alt="{{ $galeri->judul }}" 
                     class="img-fluid"
                     style="max-width: 100%; max-height: 100vh; object-fit: contain;">
            `);
            @else
                container.html(`
                <video controls class="w-100 h-100" style="object-fit: contain;">
                    <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                    Browser Anda tidak mendukung video.
                </video>
            `);
            @endif

            modal.modal('show');
        }

        // Download function
        function downloadFile() {
            const link = document.createElement('a');
            link.href = '{{ Storage::url($galeri->path_file) }}';
            link.download = '{{ $galeri->judul }}';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Track download
            trackDownload();
        }

        // Share functions
        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                showSuccess('Link berhasil disalin!');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showSuccess('Link berhasil disalin!');
            });
        }

        function shareWhatsApp() {
            const text = encodeURIComponent(`Lihat dokumentasi kegiatan: {{ $galeri->judul }} - ${window.location.href}`);
            window.open(`https://wa.me/?text=${text}`, '_blank');
        }

        function shareEmail() {
            const subject = encodeURIComponent(`Dokumentasi: {{ $galeri->judul }}`);
            const body = encodeURIComponent(
                `Silakan lihat dokumentasi kegiatan ekstrakurikuler: {{ $galeri->judul }}\n\nLink: ${window.location.href}`
            );
            window.open(`mailto:?subject=${subject}&body=${body}`);
        }

        // Delete function
        function deleteGaleri() {
            Swal.fire({
                title: 'Hapus File?',
                html: `
                <div class="text-start">
                    <p>Anda akan menghapus file:</p>
                    <div class="alert alert-warning">
                        <strong>{{ $galeri->judul }}</strong><br>
                        <small>{{ $galeri->ekstrakurikuler->nama }}</small>
                    </div>
                    <p class="text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        File yang dihapus tidak dapat dikembalikan!
                    </p>
                </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Sedang menghapus file dari galeri',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit deletion
                    fetch('{{ route('pembina.galeri.destroy', $galeri) }}', {
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
                                    window.location.href = '{{ route('pembina.galeri.index') }}';
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

        // Quick actions
        function setAsProfilePicture() {
            @if ($galeri->tipe === 'gambar')
                Swal.fire({
                    title: 'Set sebagai Foto Profil?',
                    text: 'Gambar ini akan dijadikan foto profil ekstrakurikuler.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Set!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showSuccess('Fitur ini akan segera tersedia');
                    }
                });
            @else
                showError('Hanya gambar yang dapat dijadikan foto profil');
            @endif
        }

        function addToSlideshow() {
            showSuccess('Fitur slideshow akan segera tersedia');
        }

        function shareToSocial() {
            showSuccess('Fitur share ke media sosial akan segera tersedia');
        }

        // Tracking functions
        function trackView() {
            // Implementation for tracking views
            console.log('Tracking view for file: {{ $galeri->id }}');
            $('#viewCount').text(Math.floor(Math.random() * 100) + 1); // Mock data
        }

        function trackDownload() {
            // Implementation for tracking downloads
            console.log('Tracking download for file: {{ $galeri->id }}');
            const current = parseInt($('#downloadCount').text()) || 0;
            $('#downloadCount').text(current + 1);
        }

        // Keyboard shortcuts
        function setupKeyboardShorts() {
            document.addEventListener('keydown', function(e) {
                // F for fullscreen
                if (e.key === 'f' || e.key === 'F') {
                    e.preventDefault();
                    toggleFullscreen();
                }

                // D for download
                if (e.key === 'd' || e.key === 'D') {
                    e.preventDefault();
                    downloadFile();
                }

                // + for zoom in (image only)
                @if ($galeri->tipe === 'gambar')
                    if (e.key === '+' || e.key === '=') {
                        e.preventDefault();
                        zoomIn();
                    }

                    // - for zoom out (image only)
                    if (e.key === '-' || e.key === '_') {
                        e.preventDefault();
                        zoomOut();
                    }

                    // 0 for reset zoom (image only)
                    if (e.key === '0') {
                        e.preventDefault();
                        resetZoom();
                    }
                @endif

                // Escape to close fullscreen
                if (e.key === 'Escape') {
                    $('#fullscreenModal').modal('hide');
                }
            });
        }

        // Utility functions
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
    </script>
@endpush

@push('styles')
    <style>
        .media-container {
            background: #000;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
        }

        .image-controls {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .media-container:hover .image-controls {
            opacity: 1;
        }

        .image-controls .btn {
            margin-bottom: 2px;
            border-radius: 4px;
            backdrop-filter: blur(10px);
        }

        .video-info {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .media-container:hover .video-info {
            opacity: 1;
        }

        #mainImage {
            cursor: zoom-in;
            transition: transform 0.3s ease;
            transform-origin: center center;
        }

        #mainImage.zoomed {
            cursor: zoom-out;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .badge-sm {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }

        .text-justify {
            text-align: justify;
        }

        /* Fullscreen modal styling */
        .modal-fullscreen .modal-content {
            background: #000;
            border: none;
        }

        .modal-fullscreen .modal-body {
            padding: 0;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-xl-4 {
                margin-top: 2rem;
            }

            .image-controls {
                opacity: 1;
                /* Always show on mobile */
            }

            .video-info {
                opacity: 1;
                /* Always show on mobile */
            }

            .btn-group-vertical .btn {
                margin-bottom: 4px;
            }

            .media-container {
                max-height: 50vh;
                overflow: hidden;
            }

            #mainImage,
            #mainVideo {
                max-height: 50vh;
                width: 100%;
                object-fit: contain;
            }
        }

        /* Loading animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Zoom indicator */
        .zoom-indicator {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            transition: opacity 0.3s ease;
        }

        /* Animation classes */
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

        .pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Hover effects for related files */
        .card-body .card {
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .card-body .card:hover {
            border-color: var(--bs-primary);
            transform: translateY(-2px);
        }

        /* Button enhancements */
        .btn {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-group-vertical .btn:hover {
            transform: scale(1.1);
        }

        /* Badge improvements */
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Enhanced modal */
        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Progress indicator for video loading */
        .video-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2rem;
        }

        /* Context menu prevention */
        .media-container img,
        .media-container video {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
        }

        /* Print styles */
        @media print {

            .card-header,
            .card-footer,
            .btn,
            .image-controls,
            .video-info {
                display: none !important;
            }

            .media-container {
                background: white !important;
            }

            #mainImage {
                max-width: 100% !important;
                max-height: none !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .bg-light {
                background-color: #2d3748 !important;
            }

            .text-muted {
                color: #a0aec0 !important;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .card {
                border: 2px solid;
            }

            .btn-outline-primary {
                border-width: 2px;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {

            .card,
            .btn,
            #mainImage,
            .image-controls,
            .video-info {
                transition: none !important;
            }

            .fade-in,
            .pulse {
                animation: none !important;
            }
        }
    </style>
@endpush
