@extends('layouts.public')

@section('title', 'Direktori HKI & Inovasi')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold mb-3 border border-success border-opacity-25"><i class="bi bi-lightbulb-fill me-1"></i> Kekayaan Intelektual</span>
            <h1 class="fw-bold mb-3">Direktori HKI</h1>
            <p class="text-muted lead fs-6">Eksplorasi seluruh karya cipta, inovasi, dan hak kekayaan intelektual (HKI) hasil kolaborasi luar biasa antara mahasiswa dan dosen program studi kami.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <form action="{{ route('direktori-hki') }}" method="GET" class="d-flex p-2 bg-white rounded-pill shadow-sm border">
                <input type="text" name="search" class="form-control border-0 bg-transparent ms-3" placeholder="Cari judul, jenis HKI, nama..." value="{{ request('search') }}" style="box-shadow: none;">
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Cari</button>
            </form>
            @if(request('search'))
                <div class="mt-2 text-end text-muted small">
                    Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('direktori-hki') }}" class="text-decoration-none ms-2 text-danger"><i class="bi bi-x-circle"></i> Reset</a>
                </div>
            @endif
        </div>
    </div>

    @if($hkiList->isEmpty())
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3"><i class="bi bi-search"></i></div>
            <h4 class="text-muted">Tidak ada data HKI yang ditemukan.</h4>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            @foreach($hkiList as $hki)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                    <div class="card h-100 border-0 rounded-4 shadow-sm" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.05)';">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-award-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="badge bg-success mb-1">{{ $hki->jenis_ciptaan }}</div>
                                    <div class="text-muted small"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($hki->tgl_permohonan)->format('d M Y') }}</div>
                                </div>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; line-height: 1.4;">{{ $hki->judul_ciptaan }}</h5>
                            
                            <div class="mt-3 mb-3 p-3 bg-light rounded-3 border">
                                <div class="text-muted small mb-1">Kolaborator/Pencipta:</div>
                                @if($hki->kode_dosen)
                                    <div class="fw-bold text-primary mb-1"><i class="bi bi-person-video3 me-1"></i> {{ $hki->nama_dosen }}</div>
                                @endif
                                @if($hki->nim)
                                    <div class="fw-bold text-success"><i class="bi bi-person-fill me-1"></i> {{ $hki->mahasiswa ? $hki->mahasiswa->nama : 'Mahasiswa' }}</div>
                                @endif
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <div class="small fw-bold text-secondary">
                                    <i class="bi bi-upc-scan me-1"></i> {{ $hki->no_permohonan }}
                                </div>
                                @if($hki->link_dokumen)
                                    <a href="{{ $hki->link_dokumen }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $hkiList->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
