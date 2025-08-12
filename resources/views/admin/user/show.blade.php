@extends('layouts.app')

@section('title', 'Detail ' . $user->name)
@section('page-title', 'Detail ' . ucfirst($user->role))
@section('page-description', 'Informasi lengkap pengguna')
@push('styles')
    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 600;
            border: 4px solid var(--bs-white);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-list-item {
            display: flex;
            align-items: start;
            padding: 1rem 0;
            border-bottom: 1px solid var(--bs-gray-200);
        }

        .info-list-item:last-child {
            border-bottom: none;
        }

        .info-list-item .icon-wrapper {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background-color: var(--bs-gray-100);
            color: var(--bs-gray-600);
            font-size: 1.25rem;
        }
    </style>
@endpush

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.user.index', ['role' => $user->role]) }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-body text-center p-4">
                    <div class="profile-avatar mb-3">
                        <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <span
                        class="badge bg-{{ $user->is_active ? 'success-subtle text-success-emphasis' : 'secondary-subtle text-secondary-emphasis' }} rounded-pill px-3 py-2">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>

                    <hr>

                    @if ($user->role == 'siswa' && $user->nilai_rata_rata)
                        <div class="d-flex justify-content-around text-center mb-3">
                            <div>
                                <strong class="d-block fs-4 text-primary">{{ $user->nilai_rata_rata }}</strong>
                                <small class="text-muted">Nilai Rata-rata</small>
                            </div>
                            <div>
                                <strong class="d-block fs-4 text-primary">{{ $user->age ?? '-' }}</strong>
                                <small class="text-muted">Usia</small>
                            </div>
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit Profil
                        </a>
                        @if ($user->id !== auth()->id())
                            <button class="btn btn-outline-danger"
                                onclick="confirmDelete('{{ route('admin.user.destroy', $user) }}')">
                                <i class="bi bi-trash me-1"></i>Hapus Akun
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BAGIAN STATISTIK SISWA YANG HILANG --}}
            @if ($user->role == 'siswa')
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Statistik Siswa</h5>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $pendaftaran = $user->pendaftarans()->first();
                            $rekomendasi = $user->rekomendasis()->count();
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">Status Ekstrakurikuler</div>
                            @if ($pendaftaran)
                                <span
                                    class="badge bg-{{ $pendaftaran->status == 'disetujui' ? 'success' : ($pendaftaran->status == 'pending' ? 'warning' : 'danger') }} rounded-pill">
                                    {{ ucfirst($pendaftaran->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill">Belum
                                    Daftar</span>
                            @endif
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">Rekomendasi</div>
                            <span class="badge bg-info-subtle text-info-emphasis rounded-pill">{{ $rekomendasi }}
                                tersedia</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Pengguna</h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-list-item">
                        <div class="icon-wrapper"><i class="bi bi-envelope"></i></div>
                        <div>
                            <small class="text-muted">EMAIL</small><br>
                            <strong>{{ $user->email }}</strong>
                        </div>
                    </div>
                    <div class="info-list-item">
                        <div class="icon-wrapper"><i class="bi bi-telephone"></i></div>
                        <div>
                            <small class="text-muted">TELEPON</small><br>
                            <strong>{{ $user->telepon ?? '-' }}</strong>
                        </div>
                    </div>
                    <div class="info-list-item">
                        <div class="icon-wrapper"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <small class="text-muted">ROLE</small><br>
                            <strong>{{ ucfirst($user->role) }}</strong>
                        </div>
                    </div>

                    @if ($user->role == 'siswa')
                        <div class="info-list-item">
                            <div class="icon-wrapper"><i class="bi bi-person-vcard"></i></div>
                            <div>
                                <small class="text-muted">NIS</small><br>
                                <strong>{{ $user->nis ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="info-list-item">
                            <div class="icon-wrapper"><i class="bi bi-gender-ambiguous"></i></div>
                            <div>
                                <small class="text-muted">JENIS KELAMIN</small><br>
                                <strong>{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>
                            </div>
                        </div>
                        <div class="info-list-item">
                            <div class="icon-wrapper"><i class="bi bi-calendar-event"></i></div>
                            <div>
                                <small class="text-muted">TANGGAL LAHIR</small><br>
                                <strong>{{ $user->tanggal_lahir ? $user->tanggal_lahir->format('d M Y') : '-' }} <span
                                        class="text-muted">({{ $user->age ?? '-' }} thn)</span></strong>
                            </div>
                        </div>
                        <div class="info-list-item">
                            <div class="icon-wrapper"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <small class="text-muted">ALAMAT</small><br>
                                <strong>{{ $user->alamat ?? '-' }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($user->role == 'siswa' && $user->pendaftarans->count() > 0)
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-collection me-2 text-primary"></i>Aktivitas Ekstrakurikuler</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach ($user->pendaftarans as $pendaftaran)
                            <div class="info-list-item">
                                <div class="icon-wrapper"><i class="bi bi-check2-circle"></i></div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <small class="text-muted">MENDAFTAR DI</small><br>
                                            <strong>{{ $pendaftaran->ekstrakurikuler->nama }}</strong>
                                        </div>
                                        <span
                                            class="badge bg-{{ $pendaftaran->status == 'disetujui' ? 'success-subtle text-success-emphasis' : ($pendaftaran->status == 'pending' ? 'warning-subtle text-warning-emphasis' : 'danger-subtle text-danger-emphasis') }} rounded-pill">
                                            {{ ucfirst($pendaftaran->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- BAGIAN MINAT & PRESTASI YANG HILANG --}}
            @if ($user->role == 'siswa')
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-star me-2 text-primary"></i>Minat & Prestasi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-list-item">
                            <div class="icon-wrapper"><i class="bi bi-puzzle"></i></div>
                            <div>
                                <small class="text-muted">MINAT</small><br>
                                <div>
                                    @if ($user->minat_array && count($user->minat_array) > 0)
                                        @foreach ($user->minat_array as $minat)
                                            <span
                                                class="badge bg-secondary-subtle text-secondary-emphasis me-1 mb-1">{{ ucfirst($minat) }}</span>
                                        @endforeach
                                    @else
                                        <strong class="text-muted">Belum mengisi minat</strong>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="info-list-item">
                            <div class="icon-wrapper"><i class="bi bi-trophy"></i></div>
                            <div>
                                <small class="text-muted">PRESTASI</small><br>
                                <strong>{{ $user->prestasi ?: 'Belum ada prestasi yang dicatat' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Aktivitas Ekstrakurikuler --}}
            @if ($user->role == 'siswa' && $user->pendaftarans->count() > 0)
                <div class="card">
                    {{-- ... Bagian aktivitas ekstrakurikuler (tetap sama) ... --}}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Hapus {{ ucfirst($user->role) }}?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

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
