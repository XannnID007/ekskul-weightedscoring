@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa Ekstrakurikuler')
@section('page-description', 'Lihat daftar siswa yang terdaftar pada ekstrakurikuler Anda.')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Daftar Siswa Berdasarkan Ekstrakurikuler</div>
        </div>
        <div class="card-body">
            @if ($ekstrakurikulers->count() > 0)
                {{-- Navigasi Tabs --}}
                <ul class="nav nav-tabs" id="ekskulTab" role="tablist">
                    @foreach ($ekstrakurikulers as $ekskul)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $ekskul->id }}"
                                data-bs-toggle="tab" data-bs-target="#content-{{ $ekskul->id }}" type="button"
                                role="tab" aria-controls="content-{{ $ekskul->id }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $ekskul->nama }}
                                <span
                                    class="badge bg-primary rounded-pill ms-1">{{ $ekskul->siswaDisetujui->count() }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- Konten Tabs --}}
                <div class="tab-content pt-3" id="ekskulTabContent">
                    @foreach ($ekstrakurikulers as $ekskul)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $ekskul->id }}"
                            role="tabpanel" aria-labelledby="tab-{{ $ekskul->id }}">

                            @if ($ekskul->siswaDisetujui->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ekskul->siswaDisetujui as $siswa)
                                                <tr id="row-{{ $siswa->id }}-{{ $ekskul->id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $siswa->name }}</td>
                                                    <td>{{ $siswa->nis ?: '-' }}</td>
                                                    <td>{{ $siswa->email }}</td>
                                                    <td>{{ $siswa->telepon ?: '-' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="hapusSiswa({{ $siswa->id }}, {{ $ekskul->id }}, '{{ $siswa->name }}')">
                                                            <i class="bi bi-trash"></i> Keluarkan
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Belum ada siswa yang disetujui untuk ekstrakurikuler ini.</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-info-circle text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-3">Anda belum ditugaskan sebagai pembina ekstrakurikuler apapun.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function hapusSiswa(studentId, ekskulId, studentName) {
            Swal.fire({
                title: 'Keluarkan Siswa?',
                html: `
            <div class="text-start">
                <p>Anda akan mengeluarkan siswa:</p>
                <div class="alert alert-warning">
                    <strong>Nama:</strong> ${studentName}
                </div>
                <p class="text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Siswa akan dikeluarkan dari ekstrakurikuler dan statusnya berubah menjadi "ditolak".
                </p>
            </div>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-person-x me-1"></i>Ya, Keluarkan!',
                cancelButtonText: '<i class="bi bi-x-lg me-1"></i>Batal',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang mengeluarkan siswa dari ekstrakurikuler',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('/pembina/siswa/remove', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                student_id: studentId,
                                ekstrakurikuler_id: ekskulId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Siswa berhasil dikeluarkan dari ekstrakurikuler!',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Hapus baris dari tabel
                                    document.getElementById(`row-${studentId}-${ekskulId}`).remove();

                                    // Update badge count
                                    const badge = document.querySelector(`#tab-${ekskulId} .badge`);
                                    const currentCount = parseInt(badge.textContent);
                                    badge.textContent = currentCount - 1;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan saat mengeluarkan siswa'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat mengeluarkan siswa'
                            });
                        });
                }
            });
        }
    </script>
@endpush
