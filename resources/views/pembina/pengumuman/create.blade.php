@extends('layouts.app')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman Baru')
@section('page-description', 'Buat pengumuman untuk siswa ekstrakurikuler')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button type="button" class="btn btn-light" onclick="previewPengumuman()">
            <i class="bi bi-eye me-1"></i>Preview
        </button>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <!-- Form Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-megaphone text-primary me-2"></i>Form Pengumuman
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembina.pengumuman.store') }}" method="POST" id="pengumumanForm">
                        @csrf

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
                                        {{ old('ekstrakurikuler_id') == $ekskul->id ? 'selected' : '' }}>
                                        {{ $ekskul->nama }}
                                        <small>({{ $ekskul->siswaDisetujui->count() }} siswa)</small>
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Pengumuman akan dikirim ke semua siswa yang terdaftar di ekstrakurikuler ini
                            </div>
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
                                placeholder="Masukkan judul pengumuman yang menarik..." value="{{ old('judul') }}" required
                                maxlength="255">
                            <div class="form-text d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-lightbulb me-1"></i>
                                    Buatlah judul yang jelas dan menarik perhatian
                                </span>
                                <span class="character-count">0/255</span>
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
                            <textarea class="form-control" name="konten" rows="8"
                                placeholder="Tuliskan isi pengumuman dengan detail yang jelas..." required>{{ old('konten') }}</textarea>
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
                                            {{ old('is_penting') ? 'checked' : '' }}>
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

                        <!-- Quick Templates -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-layout-text-window me-1"></i>Template Cepat
                                <small class="text-muted">(Opsional)</small>
                            </label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-primary w-100"
                                        onclick="useTemplate('latihan')">
                                        <i class="bi bi-calendar-event me-1"></i>Jadwal Latihan
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-success w-100"
                                        onclick="useTemplate('event')">
                                        <i class="bi bi-star me-1"></i>Event Khusus
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-outline-warning w-100"
                                        onclick="useTemplate('perubahan')">
                                        <i class="bi bi-exclamation-circle me-1"></i>Perubahan Jadwal
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="button" class="btn btn-outline-primary" onclick="saveDraft()">
                                <i class="bi bi-bookmark me-1"></i>Simpan Draft
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-send me-1"></i>Publikasikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tips & Guidelines -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips Pengumuman Efektif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-success">
                            <i class="bi bi-check-circle me-1"></i>Judul yang Baik
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Singkat dan jelas (maksimal 50 karakter)</li>
                            <li>• Menggunakan kata kunci penting</li>
                            <li>• Hindari singkatan yang ambigu</li>
                            <li>• Sertakan tanggal jika perlu</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-info">
                            <i class="bi bi-chat-text me-1"></i>Isi yang Efektif
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li>• Gunakan bahasa yang mudah dipahami</li>
                            <li>• Sertakan informasi 5W+1H jika perlu</li>
                            <li>• Berikan instruksi yang jelas</li>
                            <li>• Tambahkan kontak untuk pertanyaan</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong><br>
                        <small>
                            Pastikan informasi yang disampaikan akurat dan sudah terkoordinasi
                            dengan pihak sekolah terkait.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history text-secondary me-2"></i>Pengumuman Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $recentAnnouncements = auth()
                            ->user()
                            ->ekstrakurikulerSebagaiPembina()
                            ->with([
                                'pengumumans' => function ($query) {
                                    $query->latest()->limit(3);
                                },
                            ])
                            ->get()
                            ->pluck('pengumumans')
                            ->flatten();
                    @endphp

                    @if ($recentAnnouncements->count() > 0)
                        @foreach ($recentAnnouncements as $recent)
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2">
                                    @if ($recent->is_penting)
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                    @else
                                        <i class="bi bi-megaphone text-primary"></i>
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
                        <p class="text-muted small mb-0">Belum ada pengumuman terbaru</p>
                    @endif
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
                            <i class="bi bi-clock me-1"></i>Baru saja
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <i class="bi bi-send me-1"></i>Publikasikan
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
                    '<i class="bi bi-hourglass-split me-1"></i>Mempublikasikan...');
            });
        });

        // Template functions
        function useTemplate(type) {
            const templates = {
                latihan: {
                    judul: 'Jadwal Latihan Rutin - [Tanggal]',
                    konten: `Assalamualaikum Wr. Wb.

Kepada seluruh anggota ekstrakurikuler,

Dengan ini kami informasikan jadwal latihan rutin sebagai berikut:

📅 Hari/Tanggal: [Isi hari dan tanggal]
🕐 Waktu: [Isi waktu]
📍 Tempat: [Isi lokasi]

Hal yang perlu dipersiapkan:
- [Item 1]
- [Item 2]
- [Item 3]

Mohon kehadiran seluruh anggota tepat waktu.

Terima kasih.
Wassalamualaikum Wr. Wb.`
                },
                event: {
                    judul: 'Event Khusus - [Nama Event]',
                    konten: `Assalamualaikum Wr. Wb.

Kepada seluruh anggota ekstrakurikuler,

Kami dengan bangga mengumumkan akan diadakannya:

🎉 Event: [Nama Event]
📅 Tanggal: [Tanggal Event]
🕐 Waktu: [Waktu Event]
📍 Lokasi: [Lokasi Event]

Deskripsi Event:
[Jelaskan detail event]

Pendaftaran:
- Deadline: [Tanggal deadline]
- Cara daftar: [Cara pendaftaran]
- Kontak: [Kontak person]

Jangan lewatkan kesempatan emas ini!

Wassalamualaikum Wr. Wb.`
                },
                perubahan: {
                    judul: 'PENTING: Perubahan Jadwal',
                    konten: `Assalamualaikum Wr. Wb.

Kepada seluruh anggota ekstrakurikuler,

⚠️ PERHATIAN: Ada perubahan jadwal kegiatan

Jadwal Lama:
- Hari: [Hari lama]
- Waktu: [Waktu lama]
- Tempat: [Tempat lama]

Jadwal Baru:
- Hari: [Hari baru]
- Waktu: [Waktu baru]
- Tempat: [Tempat baru]

Alasan perubahan:
[Jelaskan alasan perubahan]

Berlaku mulai: [Tanggal berlaku]

Mohon maaf atas ketidaknyamanan ini dan terima kasih atas pengertiannya.

Wassalamualaikum Wr. Wb.`
                }
            };

            if (templates[type]) {
                $('input[name="judul"]').val(templates[type].judul);
                $('textarea[name="konten"]').val(templates[type].konten);

                if (type === 'perubahan') {
                    $('#is_penting').prop('checked', true);
                }

                // Update character counter
                $('input[name="judul"]').trigger('input');

                Swal.fire({
                    icon: 'success',
                    title: 'Template Diterapkan',
                    text: 'Silakan edit sesuai kebutuhan Anda',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

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

            fetch('{{ route('pembina.pengumuman.store') }}', {
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
                            text: 'Draft pengumuman berhasil disimpan',
                            timer: 2000,
                            showConfirmButton: false
                        });
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

        // Auto save functionality
        let autoSaveTimer;
        $('input[name="judul"], textarea[name="konten"]').on('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // Auto save implementation
                console.log('Auto saving...');
            }, 30000); // Auto save every 30 seconds
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + Enter to submit
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                $('#pengumumanForm').submit();
            }

            // Ctrl + P for preview
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                previewPengumuman();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .character-count {
            font-size: 0.8rem;
            transition: color 0.3s ease;
        }

        .template-btn {
            transition: all 0.3s ease;
        }

        .template-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        /* Preview modal styling */
        .modal-lg {
            max-width: 700px;
        }

        .preview-card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .col-xl-4 {
                margin-top: 2rem;
            }

            .btn-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-group .btn {
                border-radius: 0.375rem !important;
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
