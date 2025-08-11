@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')
@section('page-description', 'Ubah data pada form di bawah ini untuk memperbarui pengumuman.')

@section('page-actions')
    <a href="{{ route('pembina.pengumuman.show', $pengumuman) }}" class="btn btn-outline-light">
        <i class="bi bi-x-lg me-1"></i>Batal
    </a>
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Form Edit Pengumuman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembina.pengumuman.update', $pengumuman) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Penting untuk proses update --}}

                        {{-- Field Ekstrakurikuler --}}
                        <div class="mb-3">
                            <label for="ekstrakurikuler_id" class="form-label">Untuk Ekstrakurikuler</label>
                            <select class="form-select @error('ekstrakurikuler_id') is-invalid @enderror"
                                id="ekstrakurikuler_id" name="ekstrakurikuler_id" required>
                                @foreach ($ekstrakurikulerPilihan as $ekskul)
                                    <option value="{{ $ekskul->id }}"
                                        {{ old('ekstrakurikuler_id', $pengumuman->ekstrakurikuler_id) == $ekskul->id ? 'selected' : '' }}>
                                        {{ $ekskul->nama }}
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
                                name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Template tidak disertakan karena ini halaman edit, namun script tetap ada jika diperlukan --}}

                        {{-- Field Isi Pengumuman --}}
                        <div class="mb-3">
                            <label for="konten" class="form-label">Isi Pengumuman</label>
                            <textarea class="form-control @error('konten') is-invalid @enderror" id="konten" name="konten" rows="8"
                                required>{{ old('konten', $pengumuman->konten) }}</textarea>
                            @error('konten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Checkbox Penting --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_penting" name="is_penting"
                                value="1" {{ old('is_penting', $pengumuman->is_penting) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_penting">Tandai sebagai pengumuman penting</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
