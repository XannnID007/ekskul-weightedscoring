@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')
@section('page-description', 'Perbarui pengumuman untuk siswa ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('pembina.pengumuman.show', $pengumuman) }}" class="btn btn-outline-light">
            <i class="bi bi-eye me-1"></i>Lihat
        </a>
        <button type="button" class="btn btn-light" onclick="previewPengumuman()">
            <i class="bi bi-eye me-1"></i>Preview
        </button>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @if ($pengumuman->is_penting)
                                <div class="bg-warning rounded-circle p-2">
                                    <i class="bi bi-exclamation-triangle text-white"></i>
                                </div>
                            @else
                                <div class="bg-primary rounded-circle p-2">
                                    <i class="bi bi-megaphone text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $pengumuman->judul }}</h6>
                            <p class="text-muted mb-0">
                                <i class="bi bi-collection me-1"></i>{{ $pengumuman->ekstrakurikuler->nama }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-clock me-1"></i>Dibuat {{ $pengumuman->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div>
                            @if ($pengumuman->is_penting)
                                <span class="badge bg-warning">Penting</span>
                            @else
                                <span class="badge bg-primary">Biasa</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square text-primary me-2"></i>Edit Pengumuman
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembina.pengumuman.update', $pengumuman) }}" method="POST" id="pengumumanForm">
                        @csrf
                        @method('PUT')

                        <!-- Ekstrakurikuler Selection -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-collection me-1"></i>Ekstrakurikuler
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="ekstrakurikuler_id" required>
                                <option value="">Pilih Ekstrakurikuler</option>
                                @foreach ($ekstrakurikulers as $ekskul)
                                    <option value="{{ $ekskul->id }}"
                                        {{ old('ekstrakurikuler_id', $pengumuman->ekstrakurikuler_id) == $ekskul->id ? 'selected' : '' }}>
                                        {{ $ekskul->nama }}
                                        <small>({{ $ekskul->siswaDisetujui->count() }} siswa)</small>
                                    </option>
                                @endforeach
                            </select>
                            @error('ekstrakurikuler_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Judul -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-type me-1"></i>Judul Pengumuman
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="judul"
                                placeholder="Masukkan judul pengumuman yang menarik..."
                                value="{{ old('judul', $pengumuman->judul) }}" required maxlength="255">
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-lightbulb me-1"></i>
                                    Buatlah judul yang jelas dan menarik perhatian
                                </span>
                                <span class="character-count">{{ strlen($pengumuman->judul) }}/255</span>
                            </div>
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Konten -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-card-text me-1"></i>Isi Pengumuman
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" name="konten" rows="10"
                                placeholder="Tuliskan isi pengumuman dengan detail yang jelas..." required>{{ old('konten', $pengumuman->konten) }}</textarea>
                            <div class="form-text">
                                <i class="bi bi-markdown me-1"></i>
                                Anda dapat menggunakan format markdown untuk styling text
                            </div>
                            @error('konten')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prioritas -->
                        <div class="mb-4">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_penting" id="is_penting"
                                            {{ old('is_penting', $pengumuman->is_penting) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_penting">
                                            <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                                            Tandai sebagai Pengumuman Penting
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Pengumuman penting akan ditampilkan dengan highlight khusus dan mendapat prioritas
                                        notifikasi
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-between">
                            <div>
                                <button type="button" class="btn btn-outline-danger" onclick="deletePengumuman()">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-1"></i>Batal
                                </a>
                                <button type="button" class="btn btn-outline-primary" onclick="saveDraft()">
                                    <i class="bi bi-bookmark me-1"></i>Simpan Draft
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-save me-1"></i>Perbarui
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- History & Info -->
        <div class="col-xl-4">
            <!-- Riwayat Edit -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history text-secondary me-2"></i>Riwayat Pengumuman
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pengumuman Dibuat</h6>
                                <p class="text-muted mb-0">{{ $pengumuman->created_at->format('d M Y H:i') }}</p>
                                <small class="text-muted">Oleh: {{ $pengumuman->pembuat->name }}</small>
                            </div>
                        </div>

                        @if ($pengumuman->updated_at > $pengumuman->created_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Terakhir Diperbarui</h6>
                                    <p class="text-muted mb-0">{{ $pengumuman->updated_at->format('d M Y H:i') }}</p>
                                    <small class="text-muted">{{ $pengumuman->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endif
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
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary mb-1">{{ $pengumuman->ekstrakurikuler->siswaDisetujui->count() }}
                                </h4>
                                <small class="text-muted">Total Penerima</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success mb-1">{{ $pengumuman->created_at->diffInDays() }}</h4>
                                <small class="text-muted">Hari Lalu</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Judul:</span>
                            <span>{{ strlen($pengumuman->judul) }} karakter</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Konten:</span>
                            <span>{{ str_word_count($pengumuman->konten) }} kata</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $pengumuman->is_penting ? 'warning' : 'primary' }}">
                                {{ $pengumuman->is_penting ? 'Penting' : 'Biasa' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
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
                            Pastikan informasi yang diperbarui sudah akurat
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            Gunakan bahasa yang mudah dipahami siswa
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-1"></i>
                            Sertakan tanggal efektif jika ada perubahan
                        </li>
                        <li>
                            <i class="bi bi-check-circle text-success me-1"></i>
                            Tambahkan kontak untuk pertanyaan lebih lanjut
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0" id="previewJudul"></h6>
                            <small class="text-muted" id="previewEkskul"></small>
                        </div>
                        <div id="previewBadge"></div>
                    </div>
                    <div class="card-body">
                        <div id="previewKonten"></div>
                        <hr>
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>{{ auth()->user()->name }}
                            <span class="mx-2">•</span>
                            <i class="bi bi-clock me-1"></i>Diperbarui
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <i class="bi bi-save me-1"></i>Perbarui
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Character counter for title
            $('input[name="judul"]').on('input', function() {
                const length = $(this).val().length;
                $('.character-count').text(`${length}/255`);

                if (length > 200) {
                    $('.character-count').addClass('text-warning');
                } else if (length > 240) {
                    $('.character-count').addClass('text-danger');
                } else {
                    $('.character-count').removeClass('text-warning text-danger');
                }
            });

            // Form validation
            $('#pengumumanForm').on('submit', function(e) {
                const judul = $('input[name="judul"]').val().trim();
                const konten = $('textarea[name="konten"]').val().trim();

                if (judul.length < 10) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Judul Terlalu Pendek',
                        text: 'Judul pengumuman minimal 10 karakter'
                    });
                    return;
                }

                if (konten.length < 20) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Konten Terlalu Pendek',
                        text: 'Isi pengumuman minimal 20 karakter'
                    });
                    return;
                }

                // Show loading
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="bi bi-hourglass-split me-1"></i>Memperbarui...');
            });

            // Auto-save notification
            let hasUnsavedChanges = false;
            $('input, textarea, select').on('change', function() {
                hasUnsavedChanges = true;
            });

            // Warning before leaving page with unsaved changes
            window.addEventListener('beforeunload', function(e) {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // Remove warning after successful submit
            $('#pengumumanForm').on('submit', function() {
                hasUnsavedChanges = false;
            });
        });

        function previewPengumuman() {
            const ekstrakurikulerId = $('select[name="ekstrakurikuler_id"]').val();
            const judul = $('input[name="judul"]').val().trim();
            const konten = $('textarea[name="konten"]').val().trim();
            const isPenting = $('#is_penting').is(':checked');

            if (!ekstrakurikulerId || !judul || !konten) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Mohon lengkapi ekstrakurikuler, judul, dan isi pengumuman terlebih dahulu'
                });
                return;
            }

            // Get ekstrakurikuler name
            const ekskulName = $('select[name="ekstrakurikuler_id"] option:selected').text();

            // Update preview content
            $('#previewJudul').text(judul);
            $('#previewEkskul').text(ekskulName);
            $('#previewKonten').html(konten.replace(/\n/g, '<br>'));

            if (isPenting) {
                $('#previewBadge').html(
                    '<span class="badge bg-warning"><i class="bi bi-exclamation-triangle me-1"></i>Penting</span>');
            } else {
                $('#previewBadge').html(
                    '<span class="badge bg-primary"><i class="bi bi-megaphone me-1"></i>Pengumuman</span>');
            }

            $('#previewModal').modal('show');
        }

        function submitForm() {
            $('#previewModal').modal('hide');
            $('#pengumumanForm').submit();
        }

        function saveDraft() {
            const formData = new FormData(document.getElementById('pengumumanForm'));
            formData.append('is_draft', '1');

            Swal.fire({
                title: 'Menyimpan Draft...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('pembina.pengumuman.update', $pengumuman) }}', {
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
                            title: 'Draft Tersimpan',
                            text: 'Perubahan berhasil disimpan sebagai draft',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        hasUnsavedChanges = false;
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: error.message || 'Terjadi kesalahan saat menyimpan draft'
                    });
                });
        }

        function deletePengumuman() {
            Swal.fire({
                title: 'Hapus Pengumuman?',
                html: `
                    <div class="text-start">
                        <p>Anda akan menghapus pengumuman:</p>
                        <div class="alert alert-warning">
                            <strong>{{ $pengumuman->judul }}</strong><br>
                            <small>{{ $pengumuman->ekstrakurikuler->nama }}</small>
                        </div>
                        <p class="text-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Tindakan ini tidak dapat dibatalkan!
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
                        html: 'Sedang menghapus pengumuman',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit deletion
                    fetch('{{ route('pembina.pengumuman.destroy', $pengumuman) }}', {
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
                                    text: 'Pengumuman berhasil dihapus!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    hasUnsavedChanges = false;
                                    window.location.href = '{{ route('pembina.pengumuman.index') }}';
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan saat menghapus pengumuman'
                            });
                        });
                }
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + S to save
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                $('#pengumumanForm').submit();
            }

            // Ctrl + P for preview
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                previewPengumuman();
            }

            // Ctrl + D for draft
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                saveDraft();
            }
        });

        // Word count for content
        $('textarea[name="konten"]').on('input', function() {
            const wordCount = $(this).val().trim().split(/\s+/).length;
            if (!$(this).siblings('.word-count').length) {
                $(this).after(`<div class="word-count text-muted small mt-1">${wordCount} kata</div>`);
            } else {
                $(this).siblings('.word-count').text(`${wordCount} kata`);
            }
        });

        // Trigger initial word count
        $('textarea[name="konten"]').trigger('input');
    </script>
@endpush

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-content {
            padding-left: 20px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .character-count {
            font-size: 0.8rem;
            transition: color 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .alert {
            border-radius: 8px;
        }

        /* Custom scrollbar for textarea */
        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: #f8f9fa;
        }

        textarea::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 4px;
        }

        /* Modal styling */
        .modal-lg {
            max-width: 700px;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .col-xl-4 {
                margin-top: 2rem;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.5rem !important;
            }

            .timeline {
                padding-left: 20px;
            }

            .timeline::before {
                left: 10px;
            }

            .timeline-marker {
                left: -17px;
            }
        }

        /* Animation for form validation */
        .is-invalid {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Loading states */
        .btn:disabled {
            opacity: 0.7;
        }

        /* Word count styling */
        .word-count {
            font-size: 0.8rem;
            text-align: right;
        }

        /* Unsaved changes indicator */
        .has-unsaved-changes::after {
            content: ' *';
            color: #dc3545;
        }

        /* Success feedback */
        .success-feedback {
            animation: successPulse 0.6s ease-in-out;
        }

        @keyframes successPulse {
            0% {
                background-color: transparent;
            }

            50% {
                background-color: rgba(40, 167, 69, 0.1);
            }

            100% {
                background-color: transparent;
            }
        }

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
