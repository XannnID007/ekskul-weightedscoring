@extends('layouts.app')

@section('title', 'Tambah Ekstrakurikuler')
@section('page-title', 'Tambah Ekstrakurikuler')
@section('page-description', 'Buat ekstrakurikuler baru untuk siswa')

@section('page-actions')
    <a href="{{ route('admin.ekstrakurikuler.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Form Tambah Ekstrakurikuler
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ekstrakurikuler.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Informasi Dasar -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Ekstrakurikuler *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pembina_id" class="form-label">Pembina *</label>
                                <select class="form-select @error('pembina_id') is-invalid @enderror" id="pembina_id"
                                    name="pembina_id" required>
                                    <option value="">Pilih Pembina</option>
                                    @foreach ($pembinas as $pembina)
                                        <option value="{{ $pembina->id }}"
                                            {{ old('pembina_id') == $pembina->id ? 'selected' : '' }}>
                                            {{ $pembina->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pembina_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Deskripsi *</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                    required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gambar" class="form-label">Gambar Ekstrakurikuler</label>
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                    id="gambar" name="gambar" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, maksimal 2MB</div>
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kapasitas & Persyaratan -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-people me-2"></i>Kapasitas & Persyaratan
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="kapasitas_maksimal" class="form-label">Kapasitas Maksimal *</label>
                                <input type="number" class="form-control @error('kapasitas_maksimal') is-invalid @enderror"
                                    id="kapasitas_maksimal" name="kapasitas_maksimal"
                                    value="{{ old('kapasitas_maksimal') }}" min="1" required>
                                @error('kapasitas_maksimal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nilai_minimal" class="form-label">Nilai Minimal *</label>
                                <input type="number" class="form-control @error('nilai_minimal') is-invalid @enderror"
                                    id="nilai_minimal" name="nilai_minimal" value="{{ old('nilai_minimal', 0) }}"
                                    min="0" max="100" step="0.1" required>
                                <div class="form-text">Nilai minimal untuk bisa mendaftar</div>
                                @error('nilai_minimal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jadwal -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-calendar me-2"></i>Jadwal Kegiatan
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="hari" class="form-label">Hari *</label>
                                <select class="form-select @error('hari') is-invalid @enderror" id="hari"
                                    name="hari" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="senin" {{ old('hari') == 'senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="selasa" {{ old('hari') == 'selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="rabu" {{ old('hari') == 'rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="kamis" {{ old('hari') == 'kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="jumat" {{ old('hari') == 'jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="sabtu" {{ old('hari') == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="minggu" {{ old('hari') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="waktu" class="form-label">Waktu *</label>
                                <input type="text" class="form-control @error('waktu') is-invalid @enderror"
                                    id="waktu" name="waktu" value="{{ old('waktu') }}"
                                    placeholder="Contoh: 15:30 - 17:00" required>
                                @error('waktu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kategori -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-tags me-2"></i>Kategori Ekstrakurikuler *
                        </h6>

                        <div class="mb-4">
                            <div class="form-text mb-3">Pilih minimal 1 kategori yang sesuai</div>
                            <div class="row g-2">
                                @foreach ($kategori_options as $key => $label)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="kategori_{{ $key }}" name="kategori[]"
                                                value="{{ $key }}"
                                                {{ in_array($key, old('kategori', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="kategori_{{ $key }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('kategori')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.ekstrakurikuler.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan Ekstrakurikuler
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
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const checkboxes = document.querySelectorAll('input[name="kategori[]"]:checked');
            if (checkboxes.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Kategori Belum Dipilih',
                    text: 'Pilih minimal satu kategori untuk ekstrakurikuler ini.'
                });
                return false;
            }
        });

        // Preview image
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview if doesn't exist
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image-preview';
                        preview.className = 'mt-2';
                        e.target.parentNode.appendChild(preview);
                    }
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
