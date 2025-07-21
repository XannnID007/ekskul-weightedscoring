@extends('layouts.app')

@section('title', 'Rekomendasi Ekstrakurikuler')
@section('page-title', 'Rekomendasi Ekstrakurikuler')
@section('page-description', 'Temukan ekstrakurikuler yang paling cocok dengan minat dan bakatmu')

@section('page-actions')
    <button class="btn btn-outline-light" onclick="regenerateRekomendasi()">
        <i class="bi bi-arrow-clockwise me-1"></i>Perbarui Rekomendasi
    </button>
@endsection

@section('content')
    <!-- Profil Completion Alert -->
    @if ($profilCheck['persentase'] < 100)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle me-3 fs-4"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">Lengkapi Profil untuk Rekomendasi Terbaik</h6>
                    <p class="mb-2">Profil Anda {{ $profilCheck['persentase'] }}% lengkap. Lengkapi untuk mendapat
                        rekomendasi yang lebih akurat!</p>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: {{ $profilCheck['persentase'] }}%"></div>
                    </div>
                    <a href="{{ route('siswa.profil') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-person-gear me-1"></i>Lengkapi Profil
                    </a>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- How Algorithm Works -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-3">
                                <i class="bi bi-cpu me-2"></i>Bagaimana Sistem Rekomendasi Bekerja?
                            </h4>
                            <p class="mb-3 opacity-90">
                                Sistem kami menggunakan <strong>Weighted Scoring Algorithm</strong> yang menganalisis
                                berbagai faktor untuk memberikan rekomendasi terbaik:
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-heart-fill"></i>
                                        </div>
                                        <div>
                                            <strong>50% Minat</strong>
                                            <br><small class="opacity-75">Kecocokan dengan minat Anda</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-trophy-fill"></i>
                                        </div>
                                        <div>
                                            <strong>30% Akademik</strong>
                                            <br><small class="opacity-75">Nilai dan prestasi akademik</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                                            <i class="bi bi-clock-fill"></i>
                                        </div>
                                        <div>
                                            <strong>20% Jadwal</strong>
                                            <br><small class="opacity-75">Kesesuaian waktu kegiatan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-stars" style="font-size: 6rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($rekomendasis->count() > 0)
        <!-- Top 3 Recommendations -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="bi bi-trophy text-warning me-2"></i>Top 3 Rekomendasi Terbaik
                </h5>
            </div>
            @foreach ($rekomendasis->take(3) as $index => $rekomendasi)
                <div class="col-lg-4">
                    <div class="card h-100 border-0 position-relative"
                        style="background: linear-gradient(135deg, {{ $index == 0 ? '#ffd700, #ffed4a' : ($index == 1 ? '#c0c0c0, #e2e8f0' : '#cd7f32, #f6ad55') }} 0%, rgba(255,255,255,0.1) 100%);">
                        <!-- Ranking Badge -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-dark fs-6 px-3 py-2">
                                <i class="bi bi-{{ $index == 0 ? 'trophy' : ($index == 1 ? 'award' : 'medal') }} me-1"></i>
                                #{{ $index + 1 }}
                            </span>
                        </div>

                        <!-- Match Score -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                {{ number_format($rekomendasi->total_skor, 1) }}% Match
                            </span>
                        </div>

                        <div class="card-body p-4 text-center">
                            <!-- Ekstrakurikuler Image -->
                            <div class="mb-4 mt-4">
                                @if ($rekomendasi->ekstrakurikuler->gambar)
                                    <img src="{{ Storage::url($rekomendasi->ekstrakurikuler->gambar) }}"
                                        alt="{{ $rekomendasi->ekstrakurikuler->nama }}" class="rounded-3 shadow"
                                        width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center shadow"
                                        style="width: 120px; height: 120px;">
                                        <i class="bi bi-collection text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>

                            <h4 class="card-title text-dark mb-2">{{ $rekomendasi->ekstrakurikuler->nama }}</h4>

                            <!-- Categories -->
                            <div class="mb-3">
                                @if ($rekomendasi->ekstrakurikuler->kategori && is_array($rekomendasi->ekstrakurikuler->kategori))
                                    @foreach ($rekomendasi->ekstrakurikuler->kategori as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @elseif($rekomendasi->ekstrakurikuler->kategori && is_string($rekomendasi->ekstrakurikuler->kategori))
                                    @php
                                        $kategoriArray = json_decode($rekomendasi->ekstrakurikuler->kategori, true);
                                        if (!$kategoriArray) {
                                            $kategoriArray = [$rekomendasi->ekstrakurikuler->kategori];
                                        }
                                    @endphp
                                    @foreach ($kategoriArray as $kategori)
                                        <span class="badge bg-secondary me-1">{{ ucfirst($kategori) }}</span>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Score Breakdown -->
                            <div class="row g-2 mb-3 text-start">
                                <div class="col-4">
                                    <small class="text-muted d-block">Minat</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $rekomendasi->skor_minat }}%">
                                        </div>
                                    </div>
                                    <small class="fw-bold">{{ number_format($rekomendasi->skor_minat, 0) }}%</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Akademik</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-warning"
                                            style="width: {{ $rekomendasi->skor_akademik }}%"></div>
                                    </div>
                                    <small class="fw-bold">{{ number_format($rekomendasi->skor_akademik, 0) }}%</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Jadwal</small>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success"
                                            style="width: {{ $rekomendasi->skor_jadwal }}%"></div>
                                    </div>
                                    <small class="fw-bold">{{ number_format($rekomendasi->skor_jadwal, 0) }}%</small>
                                </div>
                            </div>

                            <!-- Reason -->
                            <p class="text-muted small mb-4">{{ $rekomendasi->alasan }}</p>

                            <!-- Actions -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}"
                                    class="btn btn-dark">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                </a>
                                @if ($rekomendasi->ekstrakurikuler->masihBisaDaftar())
                                    <button class="btn btn-primary"
                                        onclick="openRegistrationModal({{ $rekomendasi->ekstrakurikuler->id }})"
                                        data-ekskul-id="{{ $rekomendasi->ekstrakurikuler->id }}"
                                        data-ekskul-name="{{ $rekomendasi->ekstrakurikuler->nama }}"
                                        data-ekskul-pembina="{{ $rekomendasi->ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}"
                                        data-ekskul-jadwal="{{ $rekomendasi->ekstrakurikuler->jadwal_string }}"
                                        data-ekskul-kapasitas="{{ $rekomendasi->ekstrakurikuler->kapasitas_maksimal }}"
                                        data-ekskul-peserta="{{ $rekomendasi->ekstrakurikuler->peserta_saat_ini }}"
                                        data-ekskul-nilai-minimal="{{ $rekomendasi->ekstrakurikuler->nilai_minimal }}">
                                        <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                    </button>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="bi bi-x-circle me-1"></i>Kuota Penuh
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- All Recommendations -->
        @if ($rekomendasis->count() > 3)
            <div class="row g-4">
                <div class="col-12">
                    <h5 class="mb-3">
                        <i class="bi bi-list-stars text-primary me-2"></i>Semua Rekomendasi
                    </h5>
                </div>

                @foreach ($rekomendasis->skip(3) as $rekomendasi)
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center">
                                        @if ($rekomendasi->ekstrakurikuler->gambar)
                                            <img src="{{ Storage::url($rekomendasi->ekstrakurikuler->gambar) }}"
                                                alt="{{ $rekomendasi->ekstrakurikuler->nama }}" class="rounded-3"
                                                width="80" height="80" style="object-fit: cover;">
                                        @else
                                            <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center"
                                                style="width: 80px; height: 80px;">
                                                <i class="bi bi-collection text-white fs-3"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $rekomendasi->ekstrakurikuler->nama }}</h6>
                                            <span
                                                class="badge bg-primary">{{ number_format($rekomendasi->total_skor, 1) }}%</span>
                                        </div>

                                        <p class="text-muted small mb-2">{{ Str::limit($rekomendasi->alasan, 100) }}</p>

                                        <!-- Quick Info -->
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Pembina</small>
                                                <small
                                                    class="fw-medium">{{ $rekomendasi->ekstrakurikuler->pembina->name }}</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Jadwal</small>
                                                <small
                                                    class="fw-medium">{{ $rekomendasi->ekstrakurikuler->jadwal_string }}</small>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="{{ route('siswa.ekstrakurikuler.show', $rekomendasi->ekstrakurikuler) }}"
                                                class="btn btn-outline-primary btn-sm flex-grow-1">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                            @if ($rekomendasi->ekstrakurikuler->masihBisaDaftar())
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="openRegistrationModal({{ $rekomendasi->ekstrakurikuler->id }})"
                                                    data-ekskul-id="{{ $rekomendasi->ekstrakurikuler->id }}"
                                                    data-ekskul-name="{{ $rekomendasi->ekstrakurikuler->nama }}"
                                                    data-ekskul-pembina="{{ $rekomendasi->ekstrakurikuler->pembina->name ?? 'Belum ditentukan' }}"
                                                    data-ekskul-jadwal="{{ $rekomendasi->ekstrakurikuler->jadwal_string }}"
                                                    data-ekskul-kapasitas="{{ $rekomendasi->ekstrakurikuler->kapasitas_maksimal }}"
                                                    data-ekskul-peserta="{{ $rekomendasi->ekstrakurikuler->peserta_saat_ini }}"
                                                    data-ekskul-nilai-minimal="{{ $rekomendasi->ekstrakurikuler->nilai_minimal }}">
                                                    <i class="bi bi-person-plus me-1"></i>Daftar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <!-- No Recommendations -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 mb-2">Belum Ada Rekomendasi</h4>
                        <p class="text-muted mb-4">
                            Lengkapi profil Anda terlebih dahulu untuk mendapatkan rekomendasi ekstrakurikuler yang sesuai
                            dengan minat dan bakat Anda.
                        </p>
                        <a href="{{ route('siswa.profil') }}" class="btn btn-primary">
                            <i class="bi bi-person-gear me-1"></i>Lengkapi Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tips Section -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <h6 class="mb-3">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Tips untuk Mendapatkan Rekomendasi Terbaik
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-person-check text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Lengkapi Profil</h6>
                                    <small class="text-muted">Isi semua data profil termasuk minat, nilai, dan informasi
                                        pribadi lainnya.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="bi bi-arrow-clockwise text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Update Berkala</h6>
                                    <small class="text-muted">Perbarui rekomendasi secara berkala jika ada perubahan minat
                                        atau nilai.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="bi bi-eye text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Jelajahi Detail</h6>
                                    <small class="text-muted">Lihat detail setiap ekstrakurikuler untuk memahami kegiatan
                                        dan persyaratannya.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        function regenerateRekomendasi() {
            Swal.fire({
                title: 'Perbarui Rekomendasi?',
                text: 'Sistem akan menganalisis ulang profil Anda dan memberikan rekomendasi terbaru.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6f42c1',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Perbarui!',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('{{ route('siswa.rekomendasi.regenerate') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json',
                        },
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

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
        .progress {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .badge {
            font-size: 0.8em;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(111, 66, 193, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(111, 66, 193, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(111, 66, 193, 0);
            }
        }

        .btn-primary {
            animation: pulse 2s infinite;
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

        /* Form control focus state */
        .form-control:focus,
        .form-select:focus {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
            color: #fff;
        }
    </style>
@endpush
