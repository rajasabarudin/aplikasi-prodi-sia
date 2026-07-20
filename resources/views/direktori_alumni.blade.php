@extends('layouts.public')

@section('title', 'Direktori Alumni')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-3 border border-primary border-opacity-25"><i class="bi bi-people-fill me-1"></i> Jaringan Profesional</span>
            <h1 class="fw-bold mb-3">Direktori Alumni</h1>
            <p class="text-muted lead fs-6">Jelajahi jaringan lulusan yang tersebar di berbagai instansi dan perusahaan. Temukan koneksi profesional Anda di sini.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <form action="{{ route('direktori-alumni') }}" method="GET" class="d-flex p-2 bg-white rounded-pill shadow-sm border">
                <input type="text" name="search" class="form-control border-0 bg-transparent ms-3" placeholder="Cari nama, tahun, atau tempat kerja..." value="{{ request('search') }}" style="box-shadow: none;">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Cari</button>
            </form>
            @if(request('search'))
                <div class="mt-2 text-end text-muted small">
                    Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('direktori-alumni') }}" class="text-decoration-none ms-2 text-danger"><i class="bi bi-x-circle"></i> Reset</a>
                </div>
            @endif
        </div>
    </div>

    @if($alumniList->isEmpty())
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3"><i class="bi bi-search"></i></div>
            <h4 class="text-muted">Tidak ada data alumni ditemukan.</h4>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-5">
            @foreach($alumniList as $alumni)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                    <div class="card h-100 border-0 shadow-sm rounded-4" style="transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="mb-3 position-relative mx-auto" style="width: 80px; height: 80px;">
                                @if($alumni->foto && \Storage::disk('public')->exists($alumni->foto))
                                    <img src="{{ asset('storage/' . $alumni->foto) }}" alt="{{ $alumni->nama }}" class="rounded-4 shadow-sm w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="rounded-4 bg-light d-flex align-items-center justify-content-center shadow-sm w-100 h-100 text-primary fw-bold fs-3">
                                        {{ strtoupper(substr($alumni->nama, 0, 1)) }}
                                    </div>
                                @endif
                                
                                @if($alumni->instagram_url)
                                    <a href="{{ $alumni->instagram_url }}" target="_blank" class="position-absolute bottom-0 end-0 text-white rounded-circle d-flex align-items-center justify-content-center border border-white" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); width: 24px; height: 24px; text-decoration: none;">
                                        <i class="bi bi-instagram" style="font-size: 10px;"></i>
                                    </a>
                                @endif
                            </div>
                            
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $alumni->nama }}">{{ $alumni->nama }}</h6>
                            <div class="text-muted small mb-2"><i class="bi bi-mortarboard-fill me-1"></i>Lulusan {{ $alumni->tahun_lulus }}</div>
                            
                            <div class="mt-auto pt-3 border-top">
                                @if($alumni->tracerStudy)
                                    <div class="text-primary small fw-semibold text-truncate" title="{{ $alumni->tracerStudy->jabatan ?: $alumni->tracerStudy->status_kerja }}">
                                        {{ $alumni->tracerStudy->jabatan ?: $alumni->tracerStudy->status_kerja }}
                                    </div>
                                    <div class="text-secondary small text-truncate" title="{{ $alumni->tracerStudy->nama_perusahaan ?: 'Perusahaan/Instansi' }}">
                                        di {{ $alumni->tracerStudy->nama_perusahaan ?: 'Perusahaan/Instansi' }}
                                    </div>
                                @else
                                    <div class="text-muted small fst-italic">Data karir belum tersedia</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $alumniList->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
