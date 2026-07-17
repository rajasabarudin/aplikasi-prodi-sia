@extends('layouts.public')
@section('title', $berita->judul)

@section('content')
    <div class="container py-5" style="min-height: 80vh;">
        <div class="mb-4">
            <a href="{{ route('berita.public') }}" class="text-decoration-none text-muted fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Berita
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    @if($berita->foto)
                        <img src="{{ asset('storage/' . $berita->foto) }}" class="card-img-top" alt="{{ $berita->judul }}" style="max-height: 500px; object-fit: cover;">
                    @endif
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            @if(\Carbon\Carbon::parse($berita->tanggal)->greaterThanOrEqualTo(\Carbon\Carbon::today()->subDays(3)))
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="bi bi-stars me-2"></i>Terbaru</span>
                            @endif
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                        <h1 class="fw-bold mb-4" style="color: #0f172a; line-height: 1.4;">{{ $berita->judul }}</h1>
                        <hr class="mb-5 text-muted">
                        <div class="article-content" style="font-size: 1.1rem; line-height: 1.8; color: #334155;">
                            {!! $berita->isi !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
