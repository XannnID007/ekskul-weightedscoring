@extends('layouts.app')

@section('title', 'Detail Media: ' . $galeri->judul)
@section('page-title', 'Detail Media')
@section('page-description', 'Menampilkan detail dari galeri kegiatan ' . $galeri->ekstrakurikuler->nama)

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('siswa.galeri.index', $galeri->ekstrakurikuler_id) }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button type="button" class="btn btn-light" onclick="downloadFile()">
            <i class="bi bi-download me-1"></i>Download
        </button>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-share me-1"></i>Bagikan
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
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
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body p-lg-5">

                    {{-- Media Player (Gambar atau Video) --}}
                    <div class="mb-4 text-center bg-light rounded-3 p-3">
                        @if ($galeri->tipe == 'gambar')
                            <img src="{{ Storage::url($galeri->path_file) }}" alt="{{ $galeri->judul }}"
                                class="img-fluid rounded-3" style="max-height: 70vh;">
                        @else
                            <video controls class="w-100 rounded-3" style="max-height: 70vh;" autoplay muted>
                                <source src="{{ Storage::url($galeri->path_file) }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        @endif
                    </div>

                    {{-- Judul dan Informasi --}}
                    <div class="text-center">
                        <h2 class="mb-2">{{ $galeri->judul }}</h2>
                        <span
                            class="badge bg-{{ $galeri->tipe == 'gambar' ? 'info' : 'warning' }}-subtle text-{{ $galeri->tipe == 'gambar' ? 'info' : 'warning' }}-emphasis rounded-pill fs-6 px-3 py-2">
                            <i class="bi bi-{{ $galeri->tipe == 'gambar' ? 'image' : 'play-btn' }} me-2"></i>
                            {{ ucfirst($galeri->tipe) }}
                        </span>
                    </div>

                    <hr class="my-4">

                    {{-- Deskripsi --}}
                    <div>
                        <h5 class="mb-3"><i class="bi bi-card-text me-2"></i>Deskripsi</h5>
                        @if ($galeri->deskripsi)
                            <p class="text-muted" style="white-space: pre-wrap;">{{ $galeri->deskripsi }}</p>
                        @else
                            <p class="text-muted fst-italic">Tidak ada deskripsi untuk media ini.</p>
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- Detail Tambahan --}}
                    <div>
                        <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Detail Lainnya</h5>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong class="d-block">Ekstrakurikuler:</strong>
                                <span class="text-muted">{{ $galeri->ekstrakurikuler->nama }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong class="d-block">Diupload pada:</strong>
                                <span class="text-muted">{{ $galeri->created_at->format('d F Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fungsi untuk download file
        function downloadFile() {
            const link = document.createElement('a');
            link.href = '{{ Storage::url($galeri->path_file) }}';

            // Menambahkan ekstensi file pada nama file yang diunduh
            const fileName = '{{ $galeri->judul }}';
            const fileExtension = '{{ pathinfo($galeri->path_file, PATHINFO_EXTENSION) }}';
            link.download = `${fileName}.${fileExtension}`;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Fungsi untuk membagikan
        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(() => {
                // Menggunakan SweetAlert jika tersedia
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Link berhasil disalin ke clipboard.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    alert('Link berhasil disalin!');
                }
            });
        }

        function shareWhatsApp() {
            const text = encodeURIComponent(`Lihat dokumentasi kegiatan: {{ $galeri->judul }} - ${window.location.href}`);
            window.open(`https://wa.me/?text=${text}`, '_blank');
        }

        function shareEmail() {
            const subject = encodeURIComponent(`Dokumentasi Kegiatan: {{ $galeri->judul }}`);
            const body = encodeURIComponent(
                `Lihat dokumentasi kegiatan ekstrakurikuler berikut:\n\nJudul: {{ $galeri->judul }}\n\nLink: ${window.location.href}`
            );
            window.open(`mailto:?subject=${subject}&body=${body}`);
        }
    </script>
@endpush
