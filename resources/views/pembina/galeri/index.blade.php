@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('page-description', 'Kelola galeri kegiatan ekstrakurikuler Anda.')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Galeri Kegiatan</h5>
                <a href="{{ route('pembina.galeri.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i>
                    Tambah
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Form untuk Filter dan Aksi --}}
            <form action="{{ route('pembina.galeri.index') }}" method="GET" id="filterForm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">

                    {{-- Opsi Pilih Semua & Hapus --}}
                    <div class="form-check mb-2 mb-md-0">
                        <input class="form-check-input" type="checkbox" id="pilihSemua">
                        <label class="form-check-label" for="pilihSemua">
                            Pilih Semua
                        </label>
                        <button type="button" class="btn btn-danger btn-sm ms-2" id="hapusTerpilihBtn"
                            style="display: none;">
                            <i class="bi bi-trash"></i> Hapus Terpilih
                        </button>
                    </div>

                    {{-- Filter Posisi/Urutan --}}
                    <div class="d-flex align-items-center">
                        <label for="sort" class="form-label me-2 mb-0">Urutkan:</label>
                        <select name="sort" id="sort" class="form-select"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="terbaru" {{ request('sort', 'terbaru') == 'terbaru' ? 'selected' : '' }}>Terbaru
                            </option>
                            <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                </div>
            </form>

            {{-- Kontainer Galeri --}}
            <div class="row">
                @forelse ($galeri as $item)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ Storage::url($item->media) }}" class="card-img-top" alt="{{ $item->keterangan }}"
                                style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ Str::limit($item->keterangan, 50) }}</h5>
                                <p class="card-text text-muted small">
                                    <span class="badge bg-primary me-1">{{ $item->ekstrakurikuler->nama }}</span>
                                    {{ $item->created_at->diffForHumans() }}
                                </p>

                                {{-- Spacer untuk mendorong tombol ke bawah --}}
                                <div class="mt-auto"></div>

                                {{-- Tombol Aksi yang Rapi --}}
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <input type="checkbox" class="form-check-input" name="selected_galeri[]"
                                        value="{{ $item->id }}">
                                    <div class="btn-group">
                                        <a href="{{ Storage::url($item->media) }}" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="tooltip" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('pembina.galeri.download', $item) }}"
                                            class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                            title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="{{ route('pembina.galeri.destroy', $item) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-images" style="font-size: 3rem;"></i>
                            <p class="mt-2">Anda belum mengunggah foto kegiatan apapun.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi --}}
            <div class="mt-4">
                {{ $galeri->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    {{-- Form untuk Hapus Massal --}}
    <form id="bulkDeleteForm" action="{{ route('pembina.galeri.bulkDestroy') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <div id="bulkDeleteIds"></div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            const pilihSemua = document.getElementById('pilihSemua');
            const checkboxes = document.querySelectorAll('input[name="selected_galeri[]"]');
            const hapusTerpilihBtn = document.getElementById('hapusTerpilihBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const bulkDeleteIds = document.getElementById('bulkDeleteIds');

            // Fungsi untuk menampilkan/menyembunyikan tombol hapus
            function toggleBulkDeleteButton() {
                const anyChecked = Array.from(checkboxes).some(c => c.checked);
                hapusTerpilihBtn.style.display = anyChecked ? 'inline-block' : 'none';
            }

            // Event listener untuk 'Pilih Semua'
            pilihSemua.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkDeleteButton();
            });

            // Event listener untuk setiap checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleBulkDeleteButton);
            });

            // Event listener untuk tombol 'Hapus Terpilih'
            hapusTerpilihBtn.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus semua foto yang dipilih?')) {
                    bulkDeleteIds.innerHTML = ''; // Kosongkan dulu
                    checkboxes.forEach(checkbox => {
                        if (checkbox.checked) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = checkbox.value;
                            bulkDeleteIds.appendChild(input);
                        }
                    });
                    bulkDeleteForm.submit();
                }
            });
        });
    </script>
@endpush
