@extends('layouts.public')
@section('title', 'Berita & Pengumuman')
@section('content')

    <!-- Berita & Pengumuman -->
    <section class="py-5" style="background-color: #f8fafc; position: relative; min-height: 80vh;">
        
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold mb-3 border border-info border-opacity-25">Kabar Terkini</span>
                    <h2 class="section-title mb-0">Berita & Pengumuman</h2>
                    <p class="text-muted mt-2 mb-0" style="font-size: 1.1rem;">Informasi dan event terbaru dari program studi.</p>
                </div>
            </div>
            
            @if(isset($beritaList) && count($beritaList) > 0)
            <div class="row g-4 mb-4">
                @foreach($beritaList as $berita)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 rounded-4 h-100 overflow-hidden bg-white" style="box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);" onmouseover="this.style.transform='translateY(-12px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.03)'">
                        <div class="position-relative overflow-hidden" style="height: 220px;">
                            @if($berita->foto)
                                <img src="{{ asset('storage/' . $berita->foto) }}" class="card-img-top w-100 h-100" alt="{{ $berita->judul }}" style="object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div class="bg-primary bg-opacity-10 w-100 h-100 d-flex align-items-center justify-content-center" style="transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="bi bi-newspaper text-primary" style="font-size: 5rem; opacity: 0.6;"></i>
                                </div>
                            @endif
                            @if(\Carbon\Carbon::parse($berita->tanggal)->greaterThanOrEqualTo(\Carbon\Carbon::today()->subDays(3)))
                            <div class="position-absolute top-0 start-0 m-3 z-1">
                                <span class="badge bg-danger text-white shadow-sm px-3 py-2 rounded-pill fw-bold" style="font-size: 0.8rem; background: linear-gradient(135deg, #ef4444, #dc2626);">
                                    <i class="bi bi-stars me-1"></i>Terbaru
                                </span>
                            </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-3 z-1">
                                <span class="badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                    <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column position-relative">
                            <h5 class="fw-bold mb-3 lh-base" style="font-size: 1.25rem;">
                                <a href="{{ route('berita.baca', $berita->slug) }}" class="text-dark text-decoration-none stretched-link" style="transition: color 0.2s ease;" onmouseover="this.classList.add('text-primary'); this.classList.remove('text-dark')" onmouseout="this.classList.remove('text-primary'); this.classList.add('text-dark')">
                                    {{ $berita->judul }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.7;">
                                {{ strip_tags($berita->isi) }}
                            </p>
                            <div class="mt-auto d-flex align-items-center text-primary fw-bold" style="font-size: 0.9rem;">
                                <span>Baca Selengkapnya</span>
                                <i class="bi bi-arrow-right ms-2 fs-5" style="transition: transform 0.3s ease;" id="arrow-{{$berita->id}}"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $beritaList->links() }}
            </div>
            
            @else
            <div class="text-center py-5 text-muted">
                Belum ada berita atau pengumuman.
            </div>
            @endif
        </div>
    </section>

@endsection
