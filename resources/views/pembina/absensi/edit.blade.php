@extends('layouts.app')

@section('title', 'Edit Absensi')
@section('page-title', 'Edit Absensi')
@section('page-description', 'Edit kehadiran siswa untuk tanggal tertentu')

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('pembina.absensi.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('pembina.absensi.history') }}" class="btn btn-outline-light">
            <i class="bi bi-clock-history me-1"></i>Riwayat
        </a>
    </div>
@endsection

@section('content')
    <!-- Date Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle p-3 me-3">
                            <i class="bi bi-calendar-event text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Edit Absensi Tanggal {{ request('date', now()->format('d M Y')) }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Anda dapat mengubah status kehadiran siswa yang sudah tersimpan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="input-group">
                        <input type="date" class="form-control" id="editDate"
                            value="{{ request('date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                        <button class="btn btn-primary" onclick="changeDate()">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Siswa</h6>
                            <h2 class="mb-0" id="totalSiswa">0</h2>
                            <small class="opacity-75">Yang terdaftar</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Hadir</h6>
                            <h2 class="mb-0" id="totalHadir">0</h2>
                            <small class="opacity-75">Siswa hadir</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Izin/Terlambat</h6>
                            <h2 class="mb-0" id="totalIzin">0</h2>
                            <small class="opacity-75">Siswa berhalangan</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Alpa</h6>
                            <h2 class="mb-0" id="totalAlpa">0</h2>
                            <small class="opacity-75">Tanpa keterangan</small>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <select class="form-select" id="filterEkskul" style="max-width: 200px;">
                            <option value="">Semua Ekstrakurikuler</option>
                            @foreach (auth()->user()->ekstrakurikulerSebagaiPembina as $ekskul)
                                <option value="{{ $ekskul->id }}">{{ $ekskul->nama }}</option>
                            @endforeach
                        </select>
                        <select class="form-select" id="filterStatus" style="max-width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-success btn-sm" onclick="bulkEdit('hadir')">
                            <i class="bi bi-check-all me-1"></i>Tandai Terpilih Hadir
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="bulkEdit('alpa')">
                            <i class="bi bi-x-all me-1"></i>Tandai Terpilih Alpa
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="saveAllChanges()">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Kehadiran
                </h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">
                        Pilih Semua
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="absensiContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-3">Memuat data absensi...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kehadiran Individual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editAbsensiId">

                    <div class="mb-3">
                        <label class="form-label">Siswa</label>
                        <input type="text" class="form-control" id="editSiswaName" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="editTanggal" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kehadiran</label>
                        <select class="form-select" id="editStatus" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="editCatatan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Waktu Perubahan</label>
                        <input type="datetime-local" class="form-control" id="editWaktu"
                            value="{{ now()->format('Y-m-d\TH:i') }}">
                        <div class="form-text">Waktu saat perubahan dilakukan</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveIndividualEdit()">
                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Riwayat Perubahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="historyContent">
                    <!-- History content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let absensiData = [];
        let selectedItems = [];
        let currentDate = '{{ request('date', now()->format('Y-m-d')) }}';

        $(document).ready(function() {
            loadAbsensiData();

            // Filter handlers
            $('#filterEkskul, #filterStatus').change(function() {
                filterData();
            });

            // Select all handler
            $('#selectAll').change(function() {
                $('.absensi-checkbox').prop('checked', $(this).prop('checked'));
                updateSelectedItems();
            });
        });

        function loadAbsensiData() {
            // Mock data - replace with actual AJAX call
            setTimeout(() => {
                absensiData = [{
                        id: 1,
                        siswa_nama: 'Ahmad Fauzi',
                        siswa_nis: '2024001',
                        ekstrakurikuler: 'Futsal',
                        ekstrakurikuler_id: 1,
                        status: 'hadir',
                        catatan: '',
                        updated_at: '2024-12-15 14:30:00',
                        updated_by: 'Budi Santoso'
                    },
                    {
                        id: 2,
                        siswa_nama: 'Siti Aisyah',
                        siswa_nis: '2024002',
                        ekstrakurikuler: 'Futsal',
                        ekstrakurikuler_id: 1,
                        status: 'izin',
                        catatan: 'Sakit demam',
                        updated_at: '2024-12-15 14:31:00',
                        updated_by: 'Budi Santoso'
                    },
                    {
                        id: 3,
                        siswa_nama: 'Budi Santoso',
                        siswa_nis: '2024003',
                        ekstrakurikuler: 'Basket',
                        ekstrakurikuler_id: 2,
                        status: 'terlambat',
                        catatan: 'Terlambat 15 menit',
                        updated_at: '2024-12-15 14:32:00',
                        updated_by: 'Budi Santoso'
                    },
                    {
                        id: 4,
                        siswa_nama: 'Dewi Sartika',
                        siswa_nis: '2024004',
                        ekstrakurikuler: 'Basket',
                        ekstrakurikuler_id: 2,
                        status: 'alpa',
                        catatan: '',
                        updated_at: '2024-12-15 14:33:00',
                        updated_by: 'Budi Santoso'
                    }
                ];

                renderAbsensiTable();
                updateStatistics();
            }, 1000);
        }

        function renderAbsensiTable() {
            if (absensiData.length === 0) {
                $('#absensiContainer').html(`
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted mt-3">Tidak ada data absensi untuk tanggal ini</p>
                        <p class="text-muted mb-4">Silakan buat absensi baru atau pilih tanggal lain</p>
                        <a href="{{ route('pembina.absensi.create') }}?date=${currentDate}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Buat Absensi Baru
                        </a>
                    </div>
                `);
                return;
            }

            let tableHtml = `
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" class="form-check-input" id="tableSelectAll">
                                </th>
                                <th width="5%">No</th>
                                <th width="25%">Siswa</th>
                                <th width="15%">Ekstrakurikuler</th>
                                <th width="15%">Status</th>
                                <th width="20%">Catatan</th>
                                <th width="10%">Terakhir Edit</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            absensiData.forEach((absensi, index) => {
                const statusColors = {
                    'hadir': 'success',
                    'izin': 'warning',
                    'terlambat': 'info',
                    'alpa': 'danger'
                };

                const statusIcons = {
                    'hadir': 'bi-check-circle',
                    'izin': 'bi-info-circle',
                    'terlambat': 'bi-clock',
                    'alpa': 'bi-x-circle'
                };

                const color = statusColors[absensi.status] || 'secondary';
                const icon = statusIcons[absensi.status] || 'bi-question-circle';

                tableHtml += `
                    <tr data-absensi-id="${absensi.id}">
                        <td>
                            <input type="checkbox" class="form-check-input absensi-checkbox" 
                                   value="${absensi.id}" onchange="updateSelectedItems()">
                        </td>
                        <td>${index + 1}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                <div>
                                    <strong>${absensi.siswa_nama}</strong><br>
                                    <small class="text-muted">${absensi.siswa_nis || 'NIS belum diisi'}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">${absensi.ekstrakurikuler}</span>
                        </td>
                        <td>
                            <span class="badge bg-${color}">
                                <i class="bi ${icon} me-1"></i>${absensi.status.charAt(0).toUpperCase() + absensi.status.slice(1)}
                            </span>
                        </td>
                        <td>
                            ${absensi.catatan ? `
                                    <span class="text-muted">${absensi.catatan.length > 30 ? absensi.catatan.substring(0, 30) + '...' : absensi.catatan}</span>
                                    ${absensi.catatan.length > 30 ? `
                                    <button class="btn btn-link btn-sm p-0 ms-1" onclick="showFullNote('${absensi.catatan.replace(/'/g, "\\'")}')">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                ` : ''}
                                ` : '<span class="text-muted">-</span>'}
                        </td>
                        <td>
                            <small class="text-muted">
                                ${new Date(absensi.updated_at).toLocaleString('id-ID')}<br>
                                <span class="badge bg-light text-dark">${absensi.updated_by}</span>
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="editAbsensi(${absensi.id})" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm" 
                                        onclick="showHistory(${absensi.id})" title="Riwayat">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tableHtml += '</tbody></table></div>';
            $('#absensiContainer').html(tableHtml);

            // Sync table select all with main select all
            $('#tableSelectAll').change(function() {
                $('.absensi-checkbox').prop('checked', $(this).prop('checked'));
                $('#selectAll').prop('checked', $(this).prop('checked'));
                updateSelectedItems();
            });
        }

        function updateStatistics() {
            const stats = {
                total: absensiData.length,
                hadir: absensiData.filter(a => a.status === 'hadir').length,
                izin: absensiData.filter(a => a.status === 'izin' || a.status === 'terlambat').length,
                alpa: absensiData.filter(a => a.status === 'alpa').length
            };

            $('#totalSiswa').text(stats.total);
            $('#totalHadir').text(stats.hadir);
            $('#totalIzin').text(stats.izin);
            $('#totalAlpa').text(stats.alpa);
        }

        function filterData() {
            const ekskulFilter = $('#filterEkskul').val();
            const statusFilter = $('#filterStatus').val();

            $('.table tbody tr').each(function() {
                const row = $(this);
                const absensiId = row.data('absensi-id');
                const absensi = absensiData.find(a => a.id == absensiId);

                let show = true;

                if (ekskulFilter && absensi.ekstrakurikuler_id != ekskulFilter) {
                    show = false;
                }

                if (statusFilter && absensi.status !== statusFilter) {
                    show = false;
                }

                if (show) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        function updateSelectedItems() {
            selectedItems = $('.absensi-checkbox:checked').map(function() {
                return parseInt($(this).val());
            }).get();

            // Update select all checkbox
            const totalVisible = $('.absensi-checkbox:visible').length;
            const totalChecked = $('.absensi-checkbox:checked').length;

            $('#selectAll, #tableSelectAll').prop('checked', totalVisible > 0 && totalChecked === totalVisible);
        }

        function editAbsensi(id) {
            const absensi = absensiData.find(a => a.id === id);
            if (!absensi) return;

            $('#editAbsensiId').val(absensi.id);
            $('#editSiswaName').val(absensi.siswa_nama);
            $('#editTanggal').val(currentDate);
            $('#editStatus').val(absensi.status);
            $('#editCatatan').val(absensi.catatan);

            $('#editModal').modal('show');
        }

        function saveIndividualEdit() {
            const id = $('#editAbsensiId').val();
            const status = $('#editStatus').val();
            const catatan = $('#editCatatan').val();

            if (!status) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Status Belum Dipilih',
                    text: 'Pilih status kehadiran terlebih dahulu'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate save (replace with actual AJAX)
            setTimeout(() => {
                // Update local data
                const absensi = absensiData.find(a => a.id == id);
                if (absensi) {
                    absensi.status = status;
                    absensi.catatan = catatan;
                    absensi.updated_at = new Date().toISOString();
                }

                $('#editModal').modal('hide');
                renderAbsensiTable();
                updateStatistics();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data kehadiran berhasil diperbarui!',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 1500);
        }

        function bulkEdit(newStatus) {
            if (selectedItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Item Terpilih',
                    text: 'Pilih siswa yang ingin diubah statusnya'
                });
                return;
            }

            Swal.fire({
                title: `Ubah Status ke ${newStatus.toUpperCase()}?`,
                text: `${selectedItems.length} siswa akan diubah statusnya`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Update selected items
                    selectedItems.forEach(id => {
                        const absensi = absensiData.find(a => a.id === id);
                        if (absensi) {
                            absensi.status = newStatus;
                            absensi.updated_at = new Date().toISOString();
                        }
                    });

                    renderAbsensiTable();
                    updateStatistics();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `${selectedItems.length} data berhasil diubah!`,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    selectedItems = [];
                    $('.absensi-checkbox').prop('checked', false);
                    $('#selectAll, #tableSelectAll').prop('checked', false);
                }
            });
        }

        function saveAllChanges() {
            Swal.fire({
                title: 'Simpan Semua Perubahan?',
                text: 'Semua perubahan akan disimpan secara permanen',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        html: 'Menyimpan semua perubahan data absensi',
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
                            text: 'Semua perubahan berhasil disimpan!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, 2000);
                }
            });
        }

        function changeDate() {
            const newDate = $('#editDate').val();
            if (newDate) {
                window.location.href = `{{ route('pembina.absensi.edit', '') }}/${newDate}`;
            }
        }

        function showFullNote(note) {
            Swal.fire({
                title: 'Catatan Lengkap',
                text: note,
                icon: 'info',
                confirmButtonText: 'Tutup'
            });
        }

        function showHistory(id) {
            const absensi = absensiData.find(a => a.id === id);
            if (!absensi) return;

            // Mock history data
            const historyData = [{
                    action: 'Dibuat',
                    old_status: null,
                    new_status: 'hadir',
                    timestamp: '2024-12-15 14:30:00',
                    user: 'Budi Santoso',
                    note: 'Input awal absensi'
                },
                {
                    action: 'Diubah',
                    old_status: 'hadir',
                    new_status: absensi.status,
                    timestamp: absensi.updated_at,
                    user: absensi.updated_by,
                    note: 'Perubahan status'
                }
            ];

            let historyHtml = '<div class="timeline">';

            historyData.forEach((history, index) => {
                const isFirst = index === 0;
                const color = isFirst ? 'primary' : 'warning';

                historyHtml += `
                    <div class="timeline-item">
                        <div class="timeline-marker bg-${color}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">${history.action}</h6>
                            ${history.old_status ? `
                                    <p class="mb-1">
                                        <span class="badge bg-secondary">${history.old_status}</span>
                                        <i class="bi bi-arrow-right mx-2"></i>
                                        <span class="badge bg-primary">${history.new_status}</span>
                                    </p>
                                ` : `
                                    <p class="mb-1">Status: <span class="badge bg-primary">${history.new_status}</span></p>
                                `}
                            <small class="text-muted">
                                ${new Date(history.timestamp).toLocaleString('id-ID')} oleh ${history.user}
                            </small>
                            ${history.note ? `<p class="mt-2 mb-0 small text-muted">${history.note}</p>` : ''}
                        </div>
                    </div>
                `;
            });

            historyHtml += '</div>';

            $('#historyContent').html(historyHtml);
            $('#historyModal').modal('show');
        }

        // Auto-refresh every 5 minutes
        setInterval(function() {
            if (!document.hidden) {
                loadAbsensiData();
            }
        }, 300000);
    </script>
@endpush

@push('styles')
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .avatar-sm {
            width: 35px;
            height: 35px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-content {
            padding-left: 20px;
        }

        .table tbody tr:hover {
            background-color: rgba(32, 178, 170, 0.1);
        }

        .btn-group .btn {
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
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
