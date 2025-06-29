@extends('layouts.app')

@section('title', 'Input Absensi Manual')
@section('page-title', 'Input Absensi Manual')
@section('page-description', 'Input kehadiran siswa secara manual untuk tanggal tertentu')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.absensi.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button class="btn btn-light" onclick="importFromExcel()">
            <i class="bi bi-file-excel me-1"></i>Import Excel
        </button>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Quick Setup Card -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-gear text-primary me-2"></i>Pengaturan Absensi
                    </h5>
                </div>
                <div class="card-body">
                    <form id="setupForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Absensi</label>
                                <input type="date" class="form-control" id="tanggalAbsensi"
                                    value="{{ request('date', now()->format('Y-m-d')) }}"
                                    max="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ekstrakurikuler</label>
                                <select class="form-select" id="ekstrakurikulerSelect">
                                    <option value="">Semua Ekstrakurikuler</option>
                                    @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                        <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status Default</label>
                                <select class="form-select" id="defaultStatus">
                                    <option value="">Pilih Status</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="terlambat">Terlambat</option>
                                    <option value="alpa">Alpa</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="loadSiswa()">
                                    <i class="bi bi-search me-1"></i>Muat Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Absensi Form -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check text-primary me-2"></i>Form Absensi Manual
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success btn-sm" onclick="setAllStatus('hadir')"
                                id="setAllHadirBtn">
                                <i class="bi bi-check-all me-1"></i>Semua Hadir
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="setAllStatus('izin')"
                                id="setAllIzinBtn">
                                <i class="bi bi-info-circle me-1"></i>Semua Izin
                            </button>
                            <button class="btn btn-success" onclick="saveAllAbsensi()" id="saveAllBtn" disabled>
                                <i class="bi bi-save me-1"></i>Simpan Semua
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="siswaContainer">
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3">Pilih tanggal dan ekstrakurikuler untuk memuat data siswa</p>
                            <p class="text-muted mb-0">Data siswa akan ditampilkan setelah Anda mengklik "Muat Data"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Import Excel Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Absensi dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">File Excel</label>
                        <input type="file" class="form-control" name="excel_file" accept=".xlsx,.xls" required>
                        <div class="form-text">
                            Format: Kolom A=Nama Siswa, Kolom B=Status, Kolom C=Catatan
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ now()->format('Y-m-d') }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ekstrakurikuler</label>
                        <select class="form-select" name="ekstrakurikuler_id" required>
                            <option value="">Pilih Ekstrakurikuler</option>
                            @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Format File Excel:</h6>
                    <ul class="mb-0 small">
                        <li>Kolom A: Nama Siswa</li>
                        <li>Kolom B: Status (hadir/izin/terlambat/alpa)</li>
                        <li>Kolom C: Catatan (opsional)</li>
                        <li>Mulai dari baris ke-2 (baris 1 untuk header)</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" class="btn btn-outline-primary" onclick="downloadTemplate()">
                    <i class="bi bi-download me-1"></i>Download Template
                </a>
                <button type="button" class="btn btn-primary" onclick="submitImport()">
                    <i class="bi bi-upload me-1"></i>Import
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let siswaData = [];
        let currentDate = '';
        let currentEkskul = '';

        $(document).ready(function() {
            // Auto load if date is provided
            const urlDate = new URLSearchParams(window.location.search).get('date');
            if (urlDate) {
                $('#tanggalAbsensi').val(urlDate);
                loadSiswa();
            }
        });

        function loadSiswa() {
            const tanggal = $('#tanggalAbsensi').val();
            const ekskulId = $('#ekstrakurikulerSelect').val();

            if (!tanggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal Belum Dipilih',
                    text: 'Silakan pilih tanggal absensi terlebih dahulu'
                });
                return;
            }

            currentDate = tanggal;
            currentEkskul = ekskulId;

            // Show loading
            $('#siswaContainer').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-3">Memuat data siswa...</p>
                </div>
            `);

            // Simulate loading (replace with actual AJAX call)
            setTimeout(() => {
                loadSiswaData(tanggal, ekskulId);
            }, 1000);
        }

        function loadSiswaData(tanggal, ekskulId) {
            // Mock data - replace with actual AJAX call
            siswaData = [{
                    id: 1,
                    nama: 'Ahmad Fauzi',
                    nis: '2024001',
                    ekstrakurikuler: 'Futsal',
                    ekstrakurikuler_id: 1,
                    pendaftaran_id: 1,
                    existing_status: null
                },
                {
                    id: 2,
                    nama: 'Siti Aisyah',
                    nis: '2024002',
                    ekstrakurikuler: 'Futsal',
                    ekstrakurikuler_id: 1,
                    pendaftaran_id: 2,
                    existing_status: 'hadir'
                },
                {
                    id: 3,
                    nama: 'Budi Santoso',
                    nis: '2024003',
                    ekstrakurikuler: 'Basket',
                    ekstrakurikuler_id: 2,
                    pendaftaran_id: 3,
                    existing_status: null
                }
            ];

            // Filter by ekstrakurikuler if selected
            if (ekskulId) {
                siswaData = siswaData.filter(s => s.ekstrakurikuler_id == ekskulId);
            }

            renderSiswaTable();
        }

        function renderSiswaTable() {
            if (siswaData.length === 0) {
                $('#siswaContainer').html(`
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">Tidak ada siswa ditemukan</p>
                        <p class="text-muted mb-0">Pastikan siswa sudah terdaftar dan disetujui di ekstrakurikuler</p>
                    </div>
                `);
                $('#saveAllBtn').prop('disabled', true);
                return;
            }

            let tableHtml = `
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Siswa</th>
                                <th width="20%">Ekstrakurikuler</th>
                                <th width="20%">Status Kehadiran</th>
                                <th width="20%">Catatan</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            siswaData.forEach((siswa, index) => {
                const statusColors = {
                    'hadir': 'success',
                    'izin': 'warning',
                    'terlambat': 'info',
                    'alpa': 'danger'
                };

                tableHtml += `
                    <tr data-siswa-id="${siswa.id}">
                        <td>${index + 1}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                <div>
                                    <strong>${siswa.nama}</strong><br>
                                    <small class="text-muted">${siswa.nis || 'NIS belum diisi'}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">${siswa.ekstrakurikuler}</span>
                        </td>
                        <td>
                            <select class="form-select form-select-sm status-select" 
                                    data-pendaftaran-id="${siswa.pendaftaran_id}"
                                    onchange="updateStatus(this)">
                                <option value="">- Pilih Status -</option>
                                <option value="hadir" ${siswa.existing_status === 'hadir' ? 'selected' : ''}>Hadir</option>
                                <option value="izin" ${siswa.existing_status === 'izin' ? 'selected' : ''}>Izin</option>
                                <option value="terlambat" ${siswa.existing_status === 'terlambat' ? 'selected' : ''}>Terlambat</option>
                                <option value="alpa" ${siswa.existing_status === 'alpa' ? 'selected' : ''}>Alpa</option>
                            </select>
                            ${siswa.existing_status ? `
                                    <small class="text-success d-block mt-1">
                                        <i class="bi bi-check-circle me-1"></i>Sudah ada data
                                    </small>
                                ` : ''}
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm catatan-input" 
                                   placeholder="Catatan (opsional)"
                                   data-pendaftaran-id="${siswa.pendaftaran_id}">
                        </td>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" 
                                    onclick="saveIndividual(${siswa.pendaftaran_id})">
                                <i class="bi bi-save"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            tableHtml += '</tbody></table></div>';

            $('#siswaContainer').html(tableHtml);
            $('#saveAllBtn').prop('disabled', false);
        }

        function updateStatus(selectElement) {
            const status = $(selectElement).val();
            const row = $(selectElement).closest('tr');

            // Visual feedback
            if (status) {
                row.addClass('table-success');
                setTimeout(() => row.removeClass('table-success'), 1000);
            }
        }

        function setAllStatus(status) {
            $('.status-select').val(status);
            $('.status-select').each(function() {
                updateStatus(this);
            });

            Swal.fire({
                icon: 'success',
                title: 'Status Diubah',
                text: `Semua siswa ditandai sebagai ${status}`,
                timer: 1500,
                showConfirmButton: false
            });
        }

        function saveIndividual(pendaftaranId) {
            const row = $(`tr[data-siswa-id]`).find(`[data-pendaftaran-id="${pendaftaranId}"]`).closest('tr');
            const status = row.find('.status-select').val();
            const catatan = row.find('.catatan-input').val();

            if (!status) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Status Belum Dipilih',
                    text: 'Pilih status kehadiran terlebih dahulu'
                });
                return;
            }

            const btn = row.find('.btn-outline-primary');
            const originalHtml = btn.html();
            btn.html('<i class="bi bi-hourglass-split"></i>').prop('disabled', true);

            // Simulate save (replace with actual AJAX)
            setTimeout(() => {
                btn.html('<i class="bi bi-check text-success"></i>');
                setTimeout(() => {
                    btn.html(originalHtml).prop('disabled', false);
                }, 2000);

                showSuccess('Absensi individual berhasil disimpan!');
            }, 1000);
        }

        function saveAllAbsensi() {
            const absensiData = [];

            $('.status-select').each(function() {
                const status = $(this).val();
                const pendaftaranId = $(this).data('pendaftaran-id');
                const catatan = $(`.catatan-input[data-pendaftaran-id="${pendaftaranId}"]`).val();

                if (status) {
                    absensiData.push({
                        pendaftaran_id: pendaftaranId,
                        status: status,
                        catatan: catatan
                    });
                }
            });

            if (absensiData.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data',
                    text: 'Pilih status kehadiran untuk minimal satu siswa'
                });
                return;
            }

            Swal.fire({
                title: 'Simpan Absensi?',
                text: `Menyimpan ${absensiData.length} data absensi untuk tanggal ${currentDate}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        html: `Menyimpan ${absensiData.length} data absensi`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Simulate save (replace with actual AJAX)
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `${absensiData.length} data absensi berhasil disimpan!`,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to main absensi page
                            window.location.href = '{{ route('pembina.absensi.index') }}';
                        });
                    }, 2000);
                }
            });
        }

        function importFromExcel() {
            $('#importModal').modal('show');
        }

        function downloadTemplate() {
            // Create CSV template
            const csvContent = "data:text/csv;charset=utf-8," +
                "Nama Siswa,Status,Catatan\n" +
                "Ahmad Fauzi,hadir,\n" +
                "Siti Aisyah,izin,Sakit\n" +
                "Budi Santoso,terlambat,Terlambat 15 menit\n";

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "template_absensi.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function submitImport() {
            const form = document.getElementById('importForm');
            const formData = new FormData(form);

            if (!formData.get('excel_file')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'File Belum Dipilih',
                    text: 'Pilih file Excel terlebih dahulu'
                });
                return;
            }

            Swal.fire({
                title: 'Mengimport...',
                html: 'Sedang memproses file Excel',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate import (replace with actual AJAX)
            setTimeout(() => {
                $('#importModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Import Berhasil!',
                    text: 'Data absensi berhasil diimport dari Excel',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reload siswa data
                loadSiswa();
            }, 3000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + S to save all
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                saveAllAbsensi();
            }

            // Ctrl + A to set all present
            if (e.ctrlKey && e.key === 'a') {
                e.preventDefault();
                setAllStatus('hadir');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
        }

        .status-select:focus,
        .catatan-input:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
        }

        .table tbody tr:hover {
            background-color: rgba(32, 178, 170, 0.1);
        }

        .table-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }

        .btn:disabled {
            opacity: 0.7;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .avatar-sm {
                width: 30px;
                height: 30px;
            }
        }
    </style>
@endpush
