@extends('layouts.app')

@section('title', 'Buat Pengumuman Baru')
@section('page-title', 'Buat Pengumuman Baru')
@section('page-description', 'Isi form di bawah ini untuk membuat pengumuman baru.')

@section('page-actions')
    <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-outline-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat
    </a>
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Pengumuman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembina.pengumuman.store') }}" method="POST">
                        @csrf
                        {{-- Field Ekstrakurikuler --}}
                        <div class="mb-3">
                            <label for="ekstrakurikuler_id" class="form-label">Untuk Ekstrakurikuler</label>
                            <select class="form-select @error('ekstrakurikuler_id') is-invalid @enderror"
                                id="ekstrakurikuler_id" name="ekstrakurikuler_id" required>
                                <option value="" disabled selected>-- Pilih Ekstrakurikuler --</option>
                                @foreach ($ekstrakurikulerPilihan as $ekskul)
                                    <option value="{{ $ekskul->id }}"
                                        {{ old('ekstrakurikuler_id') == $ekskul->id ? 'selected' : '' }}>{{ $ekskul->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ekstrakurikuler_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Field Judul --}}
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Pengumuman</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul"
                                name="judul" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dropdown Template --}}
                        <div class="mb-3">
                            <label for="template-pengumuman" class="form-label">Gunakan Template</label>
                            <select class="form-select" id="template-pengumuman">
                                <option value="">-- Pilih Template --</option>
                                <option
                                    data-template="Assalamualaikum Wr. Wb.&#10;&#10;Diberitahukan kepada seluruh anggota ekstrakurikuler [NAMA EKSKUL], akan diadakan latihan rutin pada:&#10;&#10;Hari/Tanggal : &#10;Waktu : &#10;Tempat : &#10;Agenda : &#10;&#10;Diharapkan kehadirannya tepat waktu. Terima kasih.&#10;Wassalamualaikum Wr. Wb.">
                                    Jadwal Latihan Rutin
                                </option>
                                <option
                                    data-template="PENGUMUMAN PEMBATALAN&#10;&#10;Dengan hormat,&#10;Dengan ini kami sampaikan bahwa kegiatan latihan ekstrakurikuler [NAMA EKSKUL] yang seharusnya dilaksanakan pada:&#10;&#10;Hari/Tanggal : &#10;Waktu : &#10;&#10;DIBATALKAN dikarenakan [ALASAN PEMBATALAN].&#10;&#10;Jadwal latihan pengganti akan diinformasikan lebih lanjut. Atas perhatiannya, kami ucapkan terima kasih.">
                                    Pembatalan Latihan
                                </option>
                                <option
                                    data-template="INFORMASI LOMBA&#10;&#10;Kepada seluruh anggota ekstrakurikuler [NAMA EKSKUL] yang terpilih,&#10;&#10;Diharapkan untuk mempersiapkan diri untuk mengikuti kompetisi/lomba:&#10;&#10;Nama Lomba : &#10;Tanggal Pelaksanaan : &#10;Tempat : &#10;&#10;Detail teknis dan persiapan akan dibahas pada pertemuan selanjutnya. Tetap semangat!">
                                    Informasi Lomba/Kompetisi
                                </option>
                            </select>
                        </div>

                        {{-- Field Isi Pengumuman --}}
                        <div class="mb-3">
                            <label for="konten" class="form-label">Isi Pengumuman</label>
                            <textarea class="form-control @error('konten') is-invalid @enderror" id="konten" name="konten" rows="8"
                                required>{{ old('konten') }}</textarea>
                            @error('konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Checkbox Penting --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_penting" name="is_penting"
                                value="1" {{ old('is_penting') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_penting">Tandai sebagai pengumuman penting</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-2"></i>
                                Terbitkan Pengumuman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const templateSelect = document.getElementById('template-pengumuman');
            const isiTextarea = document.getElementById('konten');

            templateSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const templateText = selectedOption.getAttribute('data-template');
                if (templateText) {
                    isiTextarea.value = templateText;
                }
            });
        });
    </script>
@endpush
