@extends('layouts.app')

@section('title', 'Detail Pengumuman')
@section('page-title', 'Detail Pengumuman')
@section('page-description', $pengumuman->judul)

@section('page-actions')
    <a href="{{ route('pembina.pengumuman.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header {{ $pengumuman->is_penting ? 'bg-warning text-dark' : '' }}">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-{{ $pengumuman->is_penting ? 'dark' : 'primary' }} rounded-circle p-2">
                                <i
                                    class="bi bi-{{ $pengumuman->is_penting ? 'exclamation-triangle' : 'megaphone' }} text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $pengumuman->judul }}</h5>
                            <div
                                class="d-flex align-items-center gap-3 text-muted {{ $pengumuman->is_penting ? 'text-dark' : '' }}">
                                <small><i class="bi bi-person me-1"></i>{{ $pengumuman->pembuat->name ?? 'Admin' }}</small>
                                <small><i
                                        class="bi bi-clock me-1"></i>{{ $pengumuman->created_at->diffForHumans() }}</small>
                                <small><i
                                        class="bi bi-calendar me-1"></i>{{ $pengumuman->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        @if ($pengumuman->is_penting)
                            <div>
                                <span class="badge bg-dark"><i class="bi bi-star-fill me-1"></i>Penting</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="pengumuman-content py-3">
                        {!! nl2br(e($pengumuman->konten)) !!}
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-end align-items-center">
                        <a href="{{ route('pembina.pengumuman.edit', $pengumuman) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit Pengumuman
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-collection me-2"></i>Untuk Ekstrakurikuler</h6>
                </div>
                <div class="card-body">
                    <h6 class="mb-1">{{ $pengumuman->ekstrakurikuler->nama }}</h6>
                    <small class="text-muted">Pembina: {{ $pengumuman->ekstrakurikuler->pembina->name ?? 'N/A' }}</small>
                </div>
            </div>

            @if ($pengumumanLainnya->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-megaphone me-2"></i>Pengumuman Lainnya</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($pengumumanLainnya as $item)
                            <div class="d-flex align-items-start {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('pembina.pengumuman.show', $item) }}"
                                            class="text-decoration-none">
                                            {{ Str::limit($item->judul, 40) }}
                                        </a>
                                        @if ($item->is_penting)
                                            <i class="bi bi-pin-angle-fill text-warning ms-1"></i>
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
