@extends('layouts.app')

@section('title', 'Jelajahi Ekstrakurikuler')
@section('page-title', 'Jelajahi Ekstrakurikuler')
@section('page-description', 'Temukan ekstrakurikuler yang sesuai dengan minat dan bakatmu')

@section('content')
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('siswa.ekstrakurikuler.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Ekstrakurikuler</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="Nama atau deskripsi...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori_options as $key => $label)
                                <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="tersedia" name="tersedia" value="1"
                                {{ request('tersedia') ? 'checked' : '' }}>
                            <label class="form-check-label" for="tersedia">
                                Hanya yang tersedia
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i>Filter
                            </button>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-secondary btn-sm">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Info -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h6 class="mb-1">{{ $ekstrakurikulers->total() }} Ekstrakurikuler Ditemukan</h6>
            @if (request()->anyFilled(['search', 'kategori', 'tersedia']))
                <small class="text-muted">
                    Filter aktif:
                    @if (request('search'))
                        <span class="badge bg-primary me-1">Pencarian: "{{ request('search') }}"</span>
                    @endif
                    @if (request('kategori'))
                        <span class="badge bg-info me-1">Kategori: {{ $kategori_options[request('kategori')] }}</span>
                    @endif
                    @if (request('tersedia'))
                        <span class="badge bg-success me-1">Tersedia</span>
                    @endif
                </small>
            @endif
        </div>
        <div>
            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-outline-warning">
                <i class="bi bi-stars me-1"></i>Lihat Rekomendasi
            </a>
        </div>
    </div>

    @if ($ekstrakurikulers->count() > 0)
        <!-- Ekstrakurikuler Grid -->
        <div class="row g-4">
            @foreach ($ekstrakurikulers as $ekstrakurikuler)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 ekstrakurikuler-card">
                        <!-- Image -->
                        <div class="position-relative">
                            @if ($ekstrakurikuler->gambar)
                                <img src="{{ Storage::url($ekstrakurikuler->gambar) }}" class="card-img-top"
                                    alt="{{ $ekstrakurikuler->nama }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-primary text-white"
                                    style="height: 200px;">
                                    <i class="bi bi-collection" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                @if ($ekstrakurikuler->masihBisaDaftar())
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Penuh</span>
                                @endif
                            </div>

                            <!-- Popularity Badge -->
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-dark">{{ $ekstrakurikuler->total_pendaftar }} pendaftar</span>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Title -->
                            <h5 class="card-title">{{ $ekstrakurikuler->nama }}</h5>

                            <!-- Categories -->
                            <div class="mb-2">
                                @if ($ekstrakurikuler->kategori && is_array($ekstrakurikuler->kategori))
                                    @foreach ($ekstrakurikuler->kategori as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @elseif($ekstrakurikuler->kategori && is_string($ekstrakurikuler->kategori))
                                    @php
                                        $kategoriArray = json_decode($ekstrakurikuler->kategori, true);
                                        if (!$kategoriArray) {
                                            $kategoriArray = [$ekstrakurikuler->kategori];
                                        }
                                    @endphp
                                    @foreach ($kategoriArray as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($ekstrakurikuler->deskripsi, 100) }}
                            </p>

                            <!-- Info Grid -->
                            <div class="row g-2 mb-3 small">
                                <div class="col-6">
                                    <strong>Pembina:</strong><br>
                                    <span class="text-muted">{{ $ekstrakurikuler->pembina->name }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Jadwal:</strong><br>
                                    <span class="text-muted">{{ $ekstrakurikuler->jadwal_string }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Kapasitas:</strong><br>
                                    <span
                                        class="text-muted">{{ $ekstrakurikuler->peserta_saat_ini }}/{{ $ekstrakurikuler->kapasitas_maksimal }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Min. Nilai:</strong><br>
                                    <span class="text-muted">{{ $ekstrakurikuler->nilai_minimal }}</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Kapasitas</span>
                                    <span>{{ round(($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100) }}%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $ekstrakurikuler->masihBisaDaftar() ? 'bg-success' : 'bg-danger' }}"
                                        style="width: {{ ($ekstrakurikuler->peserta_saat_ini / $ekstrakurikuler->kapasitas_maksimal) * 100 }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('siswa.ekstrakurikuler.show', $ekstrakurikuler) }}"
                                    class="btn btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                </a>

                                <!-- Ganti bagian tombol daftar dari yang lama -->
                                @if (auth()->user()->sudahTerdaftarEkstrakurikuler())
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-info-circle me-1"></i>Sudah Terdaftar Lain
                                    </button>
                                @elseif(!$ekstrakurikuler->masihBisaDaftar())
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle me-1"></i>Kuota Penuh
                                    </button>
                                @elseif(auth()->user()->nilai_rata_rata && auth()->user()->nilai_rata_rata < $ekstrakurikuler->nilai_minimal)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-exclamation-circle me-1"></i>Nilai Tidak Memenuhi
                                    </button>
                                @else
                                    <!-- TOMBOL BARU UNTUK MODAL -->
                                    <button class="btn btn-primary"
                                        onclick="openRegistrationModal({{ $ekstrakurikuler->id }})"
                                        data-ekskul-id="{{ $ekstrakurikuler->id }}"
                                        data-ekskul-name="{{ $ekstrakurikuler->nama }}"
                                        data-ekskul-pembina="{{ $ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}"
                                        data-ekskul-jadwal="{{ $ekstrakurikuler->jadwal_string }}"
                                        data-ekskul-kapasitas="{{ $ekstrakurikuler->kapasitas_maksimal }}"
                                        data-ekskul-peserta="{{ $ekstrakurikuler->peserta_saat_ini }}"
                                        data-ekskul-nilai-minimal="{{ $ekstrakurikuler->nilai_minimal }}">
                                        <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $ekstrakurikulers->withQueryString()->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
            <h4 class="mt-3 mb-2">Tidak Ada Ekstrakurikuler Ditemukan</h4>
            <p class="text-muted mb-4">
                @if (request()->anyFilled(['search', 'kategori', 'tersedia']))
                    Coba ubah kriteria pencarian atau filter Anda.
                @else
                    Belum ada ekstrakurikuler yang tersedia saat ini.
                @endif
            </p>
            @if (request()->anyFilled(['search', 'kategori', 'tersedia']))
                <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                </a>
            @endif
        </div>
    @endif

    <!-- Modal Pendaftaran Ekstrakurikuler -->
    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="registrationModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Pendaftaran Ekstrakurikuler
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Info Ekstrakurikuler -->
                    <div class="ekstrakurikuler-info mb-4" id="ekstrakurikulerInfo">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="text-primary mb-2" id="ekskul-name">Nama Ekstrakurikuler</h6>
                                <div class="row g-3 small">
                                    <div class="col-sm-6">
                                        <strong>Pembina:</strong><br>
                                        <span class="text-muted" id="ekskul-pembina">-</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Jadwal:</strong><br>
                                        <span class="text-muted" id="ekskul-jadwal">-</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Nilai Minimal:</strong><br>
                                        <span class="text-muted" id="ekskul-nilai-minimal">-</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Kapasitas:</strong><br>
                                        <span class="text-muted" id="ekskul-kapasitas">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Ketersediaan</small>
                                    <div class="progress mb-1" style="height: 8px;">
                                        <div class="progress-bar bg-success" id="kapasitas-progress"></div>
                                    </div>
                                    <small class="text-success" id="kapasitas-text">-</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pendaftaran -->
                    <form id="registrationForm" novalidate>
                        @csrf
                        <input type="hidden" id="ekstrakurikuler_id" name="ekstrakurikuler_id">

                        <!-- Motivasi -->
                        <div class="mb-3">
                            <label for="motivasi" class="form-label">Motivasi Bergabung *</label>
                            <textarea class="form-control" id="motivasi" name="motivasi" rows="4"
                                placeholder="Ceritakan mengapa Anda ingin bergabung dengan ekstrakurikuler ini..." required minlength="50"></textarea>
                            <div class="d-flex justify-content-between">
                                <div class="form-text">Minimal 50 karakter</div>
                                <div class="char-counter" id="motivasi-counter">0/50</div>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Pengalaman -->
                        <div class="mb-3">
                            <label for="pengalaman" class="form-label">Pengalaman Terkait</label>
                            <textarea class="form-control" id="pengalaman" name="pengalaman" rows="3"
                                placeholder="Pengalaman atau pengetahuan yang Anda miliki terkait ekstrakurikuler ini (opsional)"></textarea>
                            <div class="form-text">Opsional - dapat dikosongkan jika tidak ada</div>
                        </div>

                        <!-- Harapan -->
                        <div class="mb-3">
                            <label for="harapan" class="form-label">Harapan dan Tujuan *</label>
                            <textarea class="form-control" id="harapan" name="harapan" rows="3"
                                placeholder="Apa yang Anda harapkan dari ekstrakurikuler ini?" required minlength="20"></textarea>
                            <div class="d-flex justify-content-between">
                                <div class="form-text">Minimal 20 karakter</div>
                                <div class="char-counter" id="harapan-counter">0/20</div>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Tingkat Komitmen -->
                        <div class="mb-3">
                            <label for="tingkat_komitmen" class="form-label">Tingkat Komitmen *</label>
                            <select class="form-select" id="tingkat_komitmen" name="tingkat_komitmen" required>
                                <option value="">Pilih tingkat komitmen</option>
                                <option value="tinggi">Tinggi - Akan mengikuti semua kegiatan</option>
                                <option value="sedang">Sedang - Akan mengikuti sebagian besar kegiatan</option>
                                <option value="rendah">Rendah - Mungkin tidak selalu bisa mengikuti</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Konfirmasi -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="konfirmasi" name="konfirmasi"
                                    required>
                                <label class="form-check-label" for="konfirmasi">
                                    Saya menyetujui untuk mengikuti semua peraturan dan komitmen ekstrakurikuler ini *
                                </label>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="submitRegistration">
                        <i class="bi bi-send me-1"></i>Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto submit form on filter change
        document.getElementById('kategori').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('tersedia').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Card hover animations
        document.querySelectorAll('.ekstrakurikuler-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        function setupCharCounter(textareaId, minChars) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(textareaId + '-counter');

            if (!textarea || !counter) return;

            function updateCounter() {
                const length = textarea.value.length;
                counter.textContent = `${length}/${minChars}`;

                if (length < minChars) {
                    counter.className = 'char-counter warning';
                } else {
                    counter.className = 'char-counter success';
                }
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }

        // Open registration modal
        function openRegistrationModal(ekstrakurikulerId) {
            const button = document.querySelector(`[data-ekskul-id="${ekstrakurikulerId}"]`);

            if (!button) {
                console.error('Button not found for ekstrakurikuler:', ekstrakurikulerId);
                return;
            }

            const data = {
                id: button.getAttribute('data-ekskul-id'),
                name: button.getAttribute('data-ekskul-name'),
                pembina: button.getAttribute('data-ekskul-pembina'),
                jadwal: button.getAttribute('data-ekskul-jadwal'),
                kapasitas: button.getAttribute('data-ekskul-kapasitas'),
                peserta: button.getAttribute('data-ekskul-peserta'),
                nilaiMinimal: button.getAttribute('data-ekskul-nilai-minimal')
            };

            // Update modal content
            document.getElementById('ekskul-name').textContent = data.name;
            document.getElementById('ekskul-pembina').textContent = data.pembina;
            document.getElementById('ekskul-jadwal').textContent = data.jadwal;
            document.getElementById('ekskul-nilai-minimal').textContent = data.nilaiMinimal;
            document.getElementById('ekskul-kapasitas').textContent = `${data.peserta}/${data.kapasitas} peserta`;
            document.getElementById('ekstrakurikuler_id').value = data.id;

            // Update progress bar
            const percentage = (parseInt(data.peserta) / parseInt(data.kapasitas)) * 100;
            document.getElementById('kapasitas-progress').style.width = percentage + '%';
            document.getElementById('kapasitas-text').textContent = `${data.peserta}/${data.kapasitas} peserta`;

            // Reset form
            document.getElementById('registrationForm').reset();
            document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                el.classList.remove('is-valid', 'is-invalid');
            });

            // Setup character counters
            setupCharCounter('motivasi', 50);
            setupCharCounter('harapan', 20);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
            modal.show();
        }

        // Make function globally available
        window.openRegistrationModal = openRegistrationModal;

        // Form validation
        function validateForm() {
            const motivasi = document.getElementById('motivasi');
            const harapan = document.getElementById('harapan');
            const tingkatKomitmen = document.getElementById('tingkat_komitmen');
            const konfirmasi = document.getElementById('konfirmasi');

            let isValid = true;

            // Validate motivasi
            if (motivasi && motivasi.value.length < 50) {
                motivasi.classList.add('is-invalid');
                const feedback = motivasi.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = 'Motivasi harus minimal 50 karakter';
                isValid = false;
            } else if (motivasi) {
                motivasi.classList.remove('is-invalid');
                motivasi.classList.add('is-valid');
            }

            // Validate harapan
            if (harapan && harapan.value.length < 20) {
                harapan.classList.add('is-invalid');
                const feedback = harapan.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = 'Harapan harus minimal 20 karakter';
                isValid = false;
            } else if (harapan) {
                harapan.classList.remove('is-invalid');
                harapan.classList.add('is-valid');
            }

            // Validate tingkat komitmen
            if (tingkatKomitmen && tingkatKomitmen.value === '') {
                tingkatKomitmen.classList.add('is-invalid');
                const feedback = tingkatKomitmen.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = 'Pilih tingkat komitmen';
                isValid = false;
            } else if (tingkatKomitmen) {
                tingkatKomitmen.classList.remove('is-invalid');
                tingkatKomitmen.classList.add('is-valid');
            }

            // Validate konfirmasi
            if (konfirmasi && !konfirmasi.checked) {
                konfirmasi.classList.add('is-invalid');
                const feedback = konfirmasi.parentElement.querySelector('.invalid-feedback');
                if (feedback) feedback.textContent = 'Anda harus menyetujui peraturan';
                isValid = false;
            } else if (konfirmasi) {
                konfirmasi.classList.remove('is-invalid');
                konfirmasi.classList.add('is-valid');
            }

            return isValid;
        }

        // Document ready
        document.addEventListener('DOMContentLoaded', function() {
            // Submit registration
            const submitBtn = document.getElementById('submitRegistration');

            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    if (!validateForm()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Tidak Valid',
                            text: 'Mohon lengkapi semua field yang diperlukan dengan benar.'
                        });
                        return;
                    }

                    const originalText = this.innerHTML;
                    const formData = new FormData(document.getElementById('registrationForm'));
                    const ekstrakurikulerId = formData.get('ekstrakurikuler_id');

                    // Validasi ID
                    if (!ekstrakurikulerId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'ID Ekstrakurikuler tidak ditemukan.'
                        });
                        return;
                    }

                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Mengirim...';

                    // Submit via AJAX dengan URL yang benar
                    const submitUrl = `{{ url('/siswa/ekstrakurikuler') }}/${ekstrakurikulerId}/daftar`;

                    fetch(submitUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pendaftaran Berhasil!',
                                    text: 'Pendaftaran Anda telah dikirim dan menunggu persetujuan pembina.',
                                    confirmButtonColor: '#20b2aa'
                                }).then(() => {
                                    // Close modal
                                    const modalInstance = bootstrap.Modal.getInstance(document
                                        .getElementById('registrationModal'));
                                    if (modalInstance) modalInstance.hide();

                                    // Redirect to pendaftaran page
                                    window.location.href = '{{ route('siswa.pendaftaran') }}';
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Pendaftaran Gagal',
                                text: error.message ||
                                    'Terjadi kesalahan saat mengirim pendaftaran.'
                            });
                        })
                        .finally(() => {
                            // Reset button
                            this.disabled = false;
                            this.innerHTML = originalText;
                        });
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .ekstrakurikuler-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ekstrakurikuler-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            transition: all 0.3s ease;
        }

        .ekstrakurikuler-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .badge {
            font-size: 0.75em;
        }

        .progress {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .modal-content {
            background: linear-gradient(135deg, var(--bs-gray-800) 0%, #212529 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            border-bottom: none;
        }

        .ekstrakurikuler-info {
            background: rgba(32, 178, 170, 0.1);
            border: 1px solid rgba(32, 178, 170, 0.3);
            border-radius: 12px;
            padding: 1rem;
        }

        .char-counter {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .char-counter.warning {
            color: #ffc107;
        }

        .char-counter.success {
            color: #20c997;
        }

        .modal-lg {
            max-width: 800px;
        }

        /* Custom scrollbar untuk modal */
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--bs-primary);
            border-radius: 3px;
        }

        #registrationModal .modal-content {
            background: var(--bs-white);
            color: var(--bs-gray-800);
            border: 1px solid var(--bs-gray-200);
            border-radius: 1rem;
        }

        #registrationModal .modal-header {
            background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
            color: white;
            border-bottom: none;
        }

        #registrationModal .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        #registrationModal .ekstrakurikuler-info {
            background: var(--bs-primary-bg-subtle);
            border: 1px solid var(--bs-primary-border-subtle);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        #registrationModal .form-label,
        #registrationModal .form-check-label {
            color: var(--bs-gray-700);
        }

        #registrationModal .form-text {
            color: var(--bs-gray-500);
        }

        #registrationModal .char-counter {
            font-size: 0.75rem;
            color: var(--bs-gray-500);
        }

        #registrationModal .char-counter.warning {
            color: var(--bs-warning);
        }

        #registrationModal .char-counter.success {
            color: var(--bs-success);
        }
    </style>
@endpush
