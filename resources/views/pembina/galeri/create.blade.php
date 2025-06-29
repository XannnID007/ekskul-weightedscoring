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
                    // File input change handler
                    $('#fileInput').change(function() {
                        handleFileSelect(this.files[0]);
                    });

                    // Drag and drop handlers
                    $('#uploadArea').on('dragover', function(e) {
                        e.preventDefault();
                        $(this).addClass('drag-over');
                    });
