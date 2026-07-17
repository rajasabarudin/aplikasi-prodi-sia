@extends('layouts.public')
@section('title', 'Beranda')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 position-relative z-1 text-center text-lg-start">
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-4 border border-primary border-opacity-25">Sistem Informasi Akademik</span>
                        @if($profilProdi && $profilProdi->akreditasi)
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold mb-4 border border-success border-opacity-25 ms-0 ms-md-2 mt-2 mt-md-0"><i class="bi bi-award-fill me-1"></i>Akreditasi: {{ $profilProdi->akreditasi }}</span>
                        @endif
                        @if($profilProdi && $profilProdi->lama_masa_studi)
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold mb-4 border border-info border-opacity-25 ms-0 ms-md-2 mt-2 mt-md-0"><i class="bi bi-clock-history me-1"></i>Masa Studi: {{ $profilProdi->lama_masa_studi }}</span>
                        @endif
                    </div>
                    <h1>Mewujudkan Lulusan <span>Unggul & Berkualitas</span></h1>
                    <p class="pe-lg-5">Portal resmi program studi yang transparan dan terintegrasi. Pantau pencapaian, inovasi, dan profil akademik secara real-time.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('portal.kegiatan') }}" class="btn btn-login btn-lg px-4 py-3" style="border-radius: 16px;">
                            Lihat Kegiatan Terbaru
                        </a>
                        <a href="#statistik" class="btn btn-light btn-lg px-4 py-3 fw-bold" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.1); color: var(--dark);">
                            Jelajahi Profil
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center position-relative z-1">
                    <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Student" style="width: 100%; max-width: 450px;">
                </div>
            </div>
        </div>
    </section>



    <!-- Stats Section -->
    <section id="statistik" class="py-5" style="margin-top: 0px; position: relative; z-index: 10;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--secondary);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-value">{{ $jumlahMahasiswa }}</div>
                        <div class="stat-label">Mahasiswa Aktif</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="stat-value">{{ $jumlahDosen }}</div>
                        <div class="stat-label">Dosen Ahli</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <div class="stat-value">{{ count($prestasiList) + count($prestasiDosenList) }}</div>
                        <div class="stat-label">Penghargaan</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                            <i class="bi bi-building-check"></i>
                        </div>
                        <div class="stat-value">{{ $totalPks + $totalIa }}</div>
                        <div class="stat-label">Mitra Kerjasama</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Directory Dosen -->
    <section class="py-5 overflow-hidden">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Tenaga Pengajar Profesional</h2>
                <p class="section-subtitle">Didukung oleh dosen-dosen berdedikasi dengan kualifikasi terbaik.</p>
            </div>
            
            @if($dosenList->isEmpty())
                <div class="text-center text-muted p-5">Belum ada data dosen.</div>
            @else
                <div class="marquee">
                    <div class="marquee-content">
                        {{-- Duplicated for infinite effect --}}
                        @for($i=0; $i<2; $i++)
                            @foreach($dosenList as $d)
                            <div class="marquee-item">
                                <div class="dosen-card">
                                    @if ($d->foto)
                                        <img src="{{ asset('storage/' . $d->foto) }}" class="dosen-img" alt="{{ $d->nama_dosen }}">
                                    @else
                                        <div class="dosen-img-placeholder">
                                            @php
                                                $words = explode(' ', $d->nama_dosen);
                                                $initials = '';
                                                foreach ($words as $w) {
                                                    if (preg_match('/^[A-Za-z]/', $w)) $initials .= strtoupper($w[0]);
                                                    if (strlen($initials) >= 2) break;
                                                }
                                                if(empty($initials)) $initials = 'DS';
                                            @endphp
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <h4 class="dosen-name text-truncate" title="{{ $d->nama_dosen }}">{{ $d->nama_dosen }}</h4>
                                    <div class="dosen-role mt-2">{{ $d->pendidikan ?: 'Pengajar Utama' }}</div>
                                </div>
                            </div>
                            @endforeach
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Highlight Prestasi -->
    <section class="py-5 mb-5 bg-white rounded-5 shadow-sm mx-lg-4 p-lg-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-8">
                    <h2 class="section-title">Pusat Keunggulan & Prestasi</h2>
                    <p class="section-subtitle mb-0">Deretan pencapaian membanggakan dari mahasiswa dan dosen kami.</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Prestasi Mahasiswa (Limit to 3) -->
                @forelse($prestasiList->take(3) as $p)
                <div class="col-md-4">
                    <div class="prestasi-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-award-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $p->mahasiswa->nama ?? 'NIM: '.$p->nim }}</h6>
                                <small class="text-muted">Mahasiswa</small>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $p->nama_prestasi }}</h5>
                        <div class="mt-auto">
                            <span class="prestasi-badge">{{ $p->prestasi_diraih }}</span>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-secondary border">{{ $p->level_prestasi }}</span>
                                <span class="text-muted small fw-bold">{{ $p->tahun }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">Belum ada data prestasi mahasiswa yang dipublikasikan.</div>
                @endforelse

                <!-- Prestasi Dosen (Limit to 3) -->
                @forelse($prestasiDosenList->take(3) as $p)
                <div class="col-md-4">
                    <div class="prestasi-card">
                        <div class="d-flex align-items-center mb-3">
                            @if($p->dosen && $p->dosen->foto)
                                <img src="{{ asset('storage/' . $p->dosen->foto) }}" class="rounded-circle me-3 border" style="width: 48px; height: 48px; object-fit:cover;">
                            @else
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-badge-fill fs-4"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $p->nama_dosen }}</h6>
                                <small class="text-muted">Dosen</small>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $p->nama_prestasi }}</h5>
                        <div class="mt-auto">
                            <span class="prestasi-badge" style="background: rgba(16, 185, 129, 0.1); color: var(--secondary);">{{ $p->prestasi_diraih }}</span>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-light text-secondary border">{{ $p->level_prestasi }}</span>
                                <span class="text-muted small fw-bold">{{ $p->tahun }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Hidden if empty -->
                @endforelse
            </div>
        </div>
    </section>

    <!-- Highlight Mitra Kerja Sama -->
    <section class="py-5 bg-light rounded-5 shadow-sm mx-lg-4 p-lg-5 mb-5 border">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold mb-3 border border-danger border-opacity-25">Kolaborasi & Sinergi</span>
                <h2 class="section-title">Mitra Kerja Sama (PKS & IA)</h2>
                <p class="section-subtitle">Program Studi menjalin kolaborasi strategis dengan berbagai instansi untuk pengembangan pendidikan, penelitian, dan pengabdian.</p>
            </div>
            
            @if($mitraList->isEmpty())
                <div class="text-center text-muted">Belum ada data mitra kerja sama yang dipublikasikan.</div>
            @else
                <div class="marquee">
                    <div class="marquee-content" style="animation-direction: reverse;">
                        {{-- Duplicated for infinite effect --}}
                        @for($i=0; $i<2; $i++)
                            @foreach($mitraList as $mitra)
                            <div class="marquee-item">
                                <div class="card border-0 shadow-sm rounded-4 h-100 text-center" style="transition: transform 0.3s ease; width: 280px; margin: 0 10px;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-buildings-fill fs-3"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2 text-truncate w-100" title="{{ $mitra->nama_mitra }}">{{ $mitra->nama_mitra }}</h5>
                                        @if($mitra->level_pks)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary mb-2">{{ $mitra->level_pks }}</span>
                                        @endif
                                        @if($mitra->kategori)
                                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.75rem;">{{ $mitra->kategori }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
