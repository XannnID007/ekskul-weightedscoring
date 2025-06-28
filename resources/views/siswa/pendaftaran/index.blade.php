@extends('layouts.app')

@section('title', 'Status Pendaftaran')
@section('page-title', 'Status Pendaftaran')
@section('page-description', 'Pantau status pendaftaran ekstrakurikuler Anda')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            @if (auth()->user()->pendaftarans->count() > 0)
                @foreach (auth()->user()->pendaftarans as $pendaftaran)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    @if ($pendaftaran->ekstrakurikuler->gambar)
                                        <img src="{{ Storage::url($pendaftaran->ekstrakurikuler->gambar) }}"
                                            alt="{{ $pendaftaran->ekstrakurikuler->nama }}" class="rounded-3" width="100"
                                            height="100" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary rounded-3 d-inline-flex align-items-center justify-content-center"
                                            style="width: 100px; height: 100px;">
                                            <i class="bi bi-collection text-white fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-2">{{ $pendaftaran->ekstrakurikuler->nama }}</h5>
                                    <p class="text-muted mb-2">
                                        {{ Str::limit($pendaftaran->ekstrakurikuler->deskripsi, 100) }}</p>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Tanggal Daftar</small>
                                            <strong>{{ $pendaftaran->created_at->format('d M Y') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Pembina</small>
                                            <strong>{{ $pendaftaran->ekstrakurikuler->pembina->name }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        @if ($pendaftaran->status == 'pending')
                                            <span class="badge bg-warning fs-6 px-3 py-2">
                                                <i class="bi bi-clock me-1"></i>Menunggu Persetujuan
                                            </span>
                                        @elseif($pendaftaran->status == 'disetujui')
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Diterima
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6 px-3 py-2">
                                                <i class="bi bi-x-circle me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    @if ($pendaftaran->status == 'pending')
                                        <button class="btn btn-outline-danger btn-sm"
                                            onclick="cancelPendaftaran({{ $pendaftaran->id }})">
                                            <i class="bi bi-x-lg me-1"></i>Batalkan
                                        </button>
                                    @elseif($pendaftaran->status == 'disetujui')
                                        <a href="{{ route('siswa.jadwal') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-calendar3 me-1"></i>Lihat Jadwal
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Detail Information -->
                            @if ($pendaftaran->status != 'pending')
                                <hr>
                                <div class="row">
                                    @if ($pendaftaran->status == 'disetujui')
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Disetujui pada</small>
                                            <strong>{{ $pendaftaran->disetujui_pada?->format('d M Y H:i') }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Disetujui oleh</small>
                                            <strong>{{ $pendaftaran->penyetuju?->name }}</strong>
                                        </div>
                                    @else
                                        <div class="col-12">
                                            <small class="text-muted d-block">Alasan Penolakan</small>
                                            <div class="alert alert-danger mt-2 mb-0">
                                                {{ $pendaftaran->alasan_penolakan }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Expand Details -->
                            <div class="mt-3">
                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#detail-{{ $pendaftaran->id }}">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail Pendaftaran
                                </button>
                            </div>

                            <!-- Collapsible Detail -->
                            <div class="collapse mt-3" id="detail-{{ $pendaftaran->id }}">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">MOTIVASI</label>
                                                <p class="mb-0">{{ $pendaftaran->motivasi }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">HARAPAN</label>
                                                <p class="mb-0">{{ $pendaftaran->harapan }}</p>
                                            </div>
                                            @if ($pendaftaran->pengalaman)
                                                <div class="col-md-6">
                                                    <label class="form-label small text-muted">PENGALAMAN</label>
                                                    <p class="mb-0">{{ $pendaftaran->pengalaman }}</p>
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">TINGKAT KOMITMEN</label>
                                                <span
                                                    class="badge bg-{{ $pendaftaran->tingkat_komitmen == 'tinggi' ? 'success' : ($pendaftaran->tingkat_komitmen == 'sedang' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($pendaftaran->tingkat_komitmen) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No Registration -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 mb-2">Belum Ada Pendaftaran</h4>
                        <p class="text-muted mb-4">
                            Anda belum mendaftar ekstrakurikuler apapun. Temukan ekstrakurikuler yang sesuai dengan minat
                            Anda.
                        </p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('siswa.rekomendasi') }}" class="btn btn-primary">
                                <i class="bi bi-stars me-1"></i>Lihat Rekomendasi
                            </a>
                            <a href="{{ route('siswa.ekstrakurikuler.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-collection me-1"></i>Jelajahi Semua
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function cancelPendaftaran(id) {
            Swal.fire({
                title: 'Batalkan Pendaftaran?',
                text: 'Anda dapat mendaftar lagi nanti jika berubah pikiran.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form to cancel
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/siswa/pendaftaran/${id}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = $('meta[name="csrf-token"]').attr('content');

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
