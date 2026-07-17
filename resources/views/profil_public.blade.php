@extends('layouts.public')
@section('title', 'Profil Program Studi')
@section('content')

    <!-- Profil Prodi Section -->
    @if($profilProdi && ($profilProdi->deskripsi_profil || $profilProdi->visi_keilmuan || $profilProdi->nama_kaprodi))
    <section class="py-5" style="background-color: #fff; position: relative; overflow: hidden; min-height: 80vh; display: flex; align-items: center;">
        <!-- Background decoration -->
        <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: rgba(79, 70, 229, 0.03); border-radius: 50%; z-index: 0;"></div>
        <div style="position: absolute; bottom: -100px; right: -100px; width: 400px; height: 400px; background: rgba(16, 185, 129, 0.03); border-radius: 50%; z-index: 0;"></div>
        
        <div class="container position-relative z-1">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-3 border border-primary border-opacity-25">Mengenal Lebih Dekat</span>
                <h2 class="section-title">Profil Program Studi</h2>
            </div>
            <div class="row align-items-stretch g-5">
                <div class="col-lg-7 d-flex flex-column justify-content-center">
                    @if($profilProdi->akreditasi || $profilProdi->lama_masa_studi)
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            @if($profilProdi->akreditasi)
                            <div class="bg-white border border-light shadow-sm rounded-4 px-4 py-3 d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-award-fill fs-5"></i>
                                </div>
                                <div>
                                    <div class="text-muted small fw-bold text-uppercase">Akreditasi</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $profilProdi->akreditasi }}</div>
                                </div>
                            </div>
                            @endif

                            @if($profilProdi->lama_masa_studi)
                            <div class="bg-white border border-light shadow-sm rounded-4 px-4 py-3 d-flex align-items-center gap-3">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                                <div>
                                    <div class="text-muted small fw-bold text-uppercase">Lama Masa Studi</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $profilProdi->lama_masa_studi }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($profilProdi->deskripsi_profil)
                        <div class="mb-4 bg-white p-4 rounded-4 shadow-sm border border-light position-relative" style="transition: all 0.3s ease;">
                            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--primary); border-top-left-radius: 1rem; border-bottom-left-radius: 1rem;"></div>
                            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-info-circle-fill text-primary me-2"></i>Tentang Prodi</h5>
                            <p class="text-muted mb-0 lh-lg" style="text-align: justify;">{{ $profilProdi->deskripsi_profil }}</p>
                        </div>
                    @endif

                    @if($profilProdi->visi_keilmuan)
                        <div class="mb-4 bg-white p-4 rounded-4 shadow-sm border border-light position-relative" style="transition: all 0.3s ease;">
                            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--secondary); border-top-left-radius: 1rem; border-bottom-left-radius: 1rem;"></div>
                            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-eye-fill text-secondary me-2"></i>Visi Keilmuan</h5>
                            <p class="text-muted mb-0 lh-lg" style="text-align: justify;">{{ $profilProdi->visi_keilmuan }}</p>
                        </div>
                    @endif
                </div>

                <div class="col-lg-5">
                    <div class="d-flex flex-column h-100 gap-4">
                        @if($profilProdi->nama_kaprodi)
                            <div class="card border-0 shadow-lg rounded-5 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #f3f4f6);">
                                <div class="card-body p-5 text-center d-flex flex-column justify-content-center align-items-center relative">
                                <div class="bg-primary bg-opacity-10 w-100 h-50 position-absolute top-0 start-0" style="z-index: 0;"></div>
                                @if($profilProdi->foto_kaprodi)
                                    <div class="position-relative z-1 mb-4">
                                        <img src="{{ asset('storage/' . $profilProdi->foto_kaprodi) }}" alt="{{ $profilProdi->nama_kaprodi }}" class="rounded-4 shadow-sm border border-white" style="width: 100%; max-width: 260px; height: 320px; object-fit: cover; border-width: 6px !important;">
                                    </div>
                                @else
                                    <div class="position-relative z-1 mb-4 bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center mx-auto border border-white shadow-sm" style="width: 220px; height: 280px; border-width: 6px !important;">
                                        <i class="bi bi-person-fill" style="font-size: 6rem;"></i>
                                    </div>
                                @endif
                                <div class="position-relative z-1 bg-white px-4 py-3 rounded-4 shadow-sm w-100" style="margin-top: -20px;">
                                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.3rem;">{{ $profilProdi->nama_kaprodi }}</h4>
                                    <p class="text-primary fw-bold mb-0 small text-uppercase tracking-wider">Ketua Program Studi</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($profilProdi->informasi_staf)
                        <div class="bg-white p-4 rounded-4 shadow-sm border border-light position-relative mt-4" style="transition: all 0.3s ease;">
                            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #f59e0b; border-top-left-radius: 1rem; border-bottom-left-radius: 1rem;"></div>
                            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-people-fill me-2" style="color: #f59e0b;"></i>Informasi Staf</h5>
                            <p class="text-muted mb-0 lh-lg">{!! nl2br(e($profilProdi->informasi_staf)) !!}</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="py-5" style="background-color: #fff; min-height: 80vh; display: flex; align-items: center;">
        <div class="container text-center text-muted">
            Belum ada data profil program studi.
        </div>
    </section>
    @endif

@endsection
