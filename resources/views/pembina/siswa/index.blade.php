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
                                    <table class="table table-hover datatable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>Email</th>
                                                <th>Telepon</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ekskul->siswaDisetujui as $siswa)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $siswa->name }}</td>
                                                    <td>{{ $siswa->nis ?: '-' }}</td>
                                                    <td>{{ $siswa->email }}</td>
                                                    <td>{{ $siswa->telepon ?: '-' }}</td>
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
