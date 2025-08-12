@extends('layouts.app')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')
@section('page-description', 'Berikut adalah daftar pengumuman yang pernah Anda buat.')

@section('page-actions')
    <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-light">
        <i class="bi bi-plus-lg me-1"></i>Buat Pengumuman Baru
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daftar Riwayat</h5>
        </div>
        <div class="card-body">
            @forelse ($pengumuman as $item)
                <div class="list-group-item d-flex justify-content-between align-items-center mb-3 border p-3 rounded">
                    {{-- Bagian Kiri: Info Pengumuman --}}
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">
                            @if ($item->is_penting)
                                <i class="bi bi-pin-angle-fill text-danger me-1" title="Penting"></i>
                            @endif
                            {{ $item->judul }}
                        </div>
                        <p class="mb-1 text-muted">{{ Str::limit($item->konten, 100) }}</p>
                        <small class="text-muted">
                            <span class="badge bg-info text-dark me-2">{{ $item->ekstrakurikuler->nama }}</span>
                            Diterbitkan: {{ $item->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    {{-- Bagian Kanan: Tombol Aksi --}}
                    <div class="ms-3 d-flex gap-2">
                        {{-- Tombol Lihat Detail --}}
                        <a href="{{ route('pembina.pengumuman.show', $item) }}" class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="tooltip" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- TOMBOL HAPUS (BARU) --}}
                        <form action="{{ route('pembina.pengumuman.destroy', $item) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip"
                                title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Belum ada riwayat pengumuman.</p>
                    <a href="{{ route('pembina.pengumuman.create') }}" class="btn btn-primary mt-2">Buat Pengumuman Pertama
                        Anda</a>
                </div>
            @endforelse

            {{-- Link Paginasi --}}
            <div class="mt-4">
                {{ $pengumuman->links() }}
            </div>
        </div>
    </div>
@endsection
