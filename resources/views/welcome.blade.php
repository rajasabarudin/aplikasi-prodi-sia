@extends('layouts.public')
@section('title', 'Beranda')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 position-relative z-1 text-center text-lg-start" data-aos="fade-right">
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-2 mb-3">
                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill fw-bold shadow-sm fs-6">Sistem Informasi Akademik</span>
                        @if($profilProdi && $profilProdi->akreditasi)
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold shadow-sm fs-6"><i class="bi bi-award-fill me-1"></i>Akreditasi: {{ $profilProdi->akreditasi }}</span>
                        @endif
                        @if($profilProdi && $profilProdi->lama_masa_studi)
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm fs-6"><i class="bi bi-clock-history me-1"></i>Masa Studi: {{ $profilProdi->lama_masa_studi }}</span>
                        @endif
                    </div>
                    
                    @if($profilProdi && $profilProdi->visi_keilmuan)
                        <div class="mb-4 p-3 rounded-4" style="background: rgba(30, 58, 138, 0.05); border-left: 4px solid var(--primary);">
                            <span class="fw-bold d-block mb-1" style="color: var(--primary); font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;"><i class="bi bi-bullseye me-1"></i> Visi Keilmuan</span>
                            <p class="mb-0 text-dark" style="font-size: 0.95rem; line-height: 1.6; font-style: italic;">"{{ $profilProdi->visi_keilmuan }}"</p>
                        </div>
                    @endif

                    <h1 class="mb-3">Mewujudkan Lulusan <span>Unggul & Berkualitas</span></h1>
                    <p class="pe-lg-5 mb-4">Sistem Manajemen Data Program Studi yang transparan dan terintegrasi. Platform pusat untuk mengelola dan memantau seluruh profil akademik, inovasi, serta rekam jejak pencapaian Dosen dan Mahasiswa secara real-time.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('portal.kegiatan') }}" class="btn btn-login btn-lg px-4 py-3" style="border-radius: 16px;">
                            Lihat Kegiatan Terbaru
                        </a>
                        <a href="{{ route('profil-prodi.public') }}" class="btn btn-light btn-lg px-4 py-3 fw-bold" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.1); color: var(--dark);">
                            Jelajahi Profil
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center position-relative z-1" data-aos="fade-left" data-aos-delay="200">
                    <img src="{{ asset('img/kampus_ubsi.jpg') }}" alt="Kampus UBSI Pontianak" style="width: 100%; max-width: 500px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 8px solid rgba(255,255,255,0.8); object-fit: cover;">
                </div>
            </div>
        </div>
    </section>



    <!-- Stats Section -->
    <section id="statistik" class="py-5" style="margin-top: 0px; position: relative; z-index: 10;">
        <div class="container">
            <div class="row row-cols-2 row-cols-md-5 g-4 justify-content-center">
                <div class="col" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-card glass-card">
                        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--secondary);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-value">{{ $jumlahMahasiswa }}</div>
                        <div class="stat-label">Mahasiswa Aktif</div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-card glass-card">
                        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div class="stat-value">{{ $jumlahDosen }}</div>
                        <div class="stat-label">Dosen Ahli</div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-card glass-card">
                        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <div class="stat-value">{{ count($prestasiList) + count($prestasiDosenList) }}</div>
                        <div class="stat-label">Penghargaan</div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="400">
                    <div class="stat-card glass-card">
                        <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="stat-value">{{ $totalPks }}</div>
                        <div class="stat-label">Kerja Sama (PKS)</div>
                    </div>
                </div>
                <div class="col" data-aos="zoom-in" data-aos-delay="500">
                    <div class="stat-card glass-card">
                        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <div class="stat-value">{{ $totalIa }}</div>
                        <div class="stat-label">Implementasi (IA)</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Directory Dosen dipindahkan ke bawah -->

    <!-- Statistik & Tren Pencapaian Grafik -->
    <section class="py-5 mb-5 glass-card rounded-5 shadow-sm mx-lg-4 p-lg-5" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-10">
                    <h2 class="section-title">Statistik & Tren Pencapaian</h2>
                    <p class="section-subtitle mb-0">Pemantauan visual terhadap rekam jejak prestasi, kualitas akademik, kompetensi, serta tren jaringan kerja sama Program Studi secara komprehensif.</p>
                </div>
            </div>

            <!-- Baris 1: Prestasi & Rekognisi -->
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Grafik Prestasi Mahasiswa</h5>
                        <div id="prestasiChart"></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Rekognisi Dosen</h5>
                        @if(empty($rekognisiPerTSLabeled))
                            <div class="text-center text-muted py-5">Belum ada data rekognisi dosen.</div>
                        @else
                            <div id="rekognisiChart" class="d-flex justify-content-center"></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Baris 2: Sertifikasi, Pendidikan Dosen, Kerjasama -->
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-patch-check-fill text-success me-2"></i>Sertifikasi Kompetensi</h5>
                        <div id="serkomChart"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-mortarboard-fill text-danger me-2"></i>Kualifikasi Pendidikan</h5>
                        <div id="pendidikanChart" class="d-flex justify-content-center"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-globe-americas text-info me-2"></i>Tren Realisasi Kerjasama</h5>
                        <div id="kerjasamaChart"></div>
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

    <!-- Alumni Inspiratif & Tracer Study -->
    <section class="py-5" style="background: linear-gradient(135deg, rgba(30, 58, 138, 0.05), rgba(79, 70, 229, 0.05));">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm"><i class="bi bi-star-fill me-1"></i> Kisah Sukses Lulusan</span>
                <h2 class="section-title">Alumni Inspiratif & Tracer Study</h2>
                <p class="section-subtitle">Jejak langkah membanggakan dari para alumni yang telah sukses berkarier di berbagai bidang. Mari lengkapi data Tracer Study Anda untuk kemajuan program studi.</p>
                <div class="mt-4">
                    <a href="{{ route('portal.alumni') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-pencil-square me-2"></i> Isi Data Tracer Study Anda
                    </a>
                </div>
            </div>
            
            @if(isset($alumniInspiratif) && $alumniInspiratif->count() > 0)
            <div class="row g-4 justify-content-center mt-4">
                @foreach($alumniInspiratif as $alumni)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card h-100 border-0 rounded-4 shadow-sm" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); transition: transform 0.3s ease;">
                        <div class="card-body p-4 text-center d-flex flex-column">
                            <div class="mb-4 position-relative mx-auto" style="width: 120px; height: 120px;">
                                @if($alumni->foto)
                                    <img src="{{ asset('storage/' . $alumni->foto) }}" alt="{{ $alumni->nama }}" class="rounded-4 shadow w-100 h-100" style="border: 4px solid white; object-fit: cover;">
                                @else
                                    <div class="rounded-4 shadow w-100 h-100 d-flex align-items-center justify-content-center" style="border: 4px solid white; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: white; font-size: 2.5rem; font-weight: 800;">
                                        @php
                                            $words = explode(' ', $alumni->nama);
                                            $initials = '';
                                            foreach ($words as $w) {
                                                if (preg_match('/^[A-Za-z]/', $w)) $initials .= strtoupper($w[0]);
                                                if (strlen($initials) >= 2) break;
                                            }
                                            if(empty($initials)) $initials = 'AL';
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                @endif
                                @if($alumni->instagram_url)
                                    <a href="{{ $alumni->instagram_url }}" target="_blank" class="position-absolute bottom-0 end-0 text-white rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); width: 32px; height: 32px; text-decoration: none;">
                                        <i class="bi bi-instagram" style="font-size: 14px;"></i>
                                    </a>
                                @endif
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $alumni->nama }}</h5>
                            <div class="text-muted small mb-1">Lulusan {{ $alumni->tahun_lulus }}</div>
                            
                            @if($alumni->tracerStudy)
                                <div class="fw-bold text-primary small mb-3">
                                    {{ $alumni->tracerStudy->jabatan ?: $alumni->tracerStudy->status_kerja }}
                                </div>
                                <div class="badge bg-light text-dark border mb-3 px-3 py-2 rounded-pill mx-auto text-wrap" style="line-height: 1.5; font-weight: 500;">
                                    <i class="bi bi-building me-1 text-secondary"></i> {{ $alumni->tracerStudy->nama_perusahaan ?: 'Perusahaan/Instansi' }}
                                </div>
                            @endif
                            
                            <p class="mt-auto mb-0 fst-italic text-secondary" style="font-size: 0.95rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;" title="{{ $alumni->testimoni }}">
                                "{{ $alumni->testimoni }}"
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- Galeri Inovasi & HKI Kolaborasi -->
    <section class="py-5" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(52, 211, 153, 0.05));">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold mb-3 border border-success border-opacity-25">
                    <i class="bi bi-lightbulb-fill me-1"></i> Inovasi & Kekayaan Intelektual
                </span>
                <h2 class="section-title">HKI Kolaborasi (Dosen & Mahasiswa)</h2>
                <p class="section-subtitle">Daftar inovasi, karya cipta, dan hak kekayaan intelektual hasil kolaborasi hebat antara Dosen dan Mahasiswa.</p>
            </div>
            
            @if(isset($hkiKolaborasiList) && $hkiKolaborasiList->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($hkiKolaborasiList as $hki)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
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
                                <div class="text-muted small mb-1">Kolaborator:</div>
                                <div class="fw-bold text-primary mb-1"><i class="bi bi-person-video3 me-1"></i> {{ $hki->nama_dosen }}</div>
                                <div class="fw-bold text-success"><i class="bi bi-person-fill me-1"></i> {{ $hki->mahasiswa ? $hki->mahasiswa->nama : 'Mahasiswa' }}</div>
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
            
            <div class="text-center mt-5">
                <a href="{{ route('direktori-hki') }}" class="btn btn-success btn-lg rounded-pill px-4 py-2 shadow-sm fw-bold">
                    <i class="bi bi-grid-fill me-2"></i> Lihat Semua Direktori HKI
                </a>
            </div>
            @else
                <div class="text-center text-muted p-4 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-inbox fs-2 text-secondary mb-2 d-block"></i>
                    Belum ada data HKI Kolaborasi yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    <!-- Highlight Mitra Kerja Sama -->
    <section class="py-5 bg-light rounded-5 shadow-sm mx-lg-4 p-lg-5 mb-5 border">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold mb-3 border border-danger border-opacity-25">Kolaborasi & Sinergi</span>
                <h2 class="section-title">Jaringan Mitra (MoU)</h2>
                <p class="section-subtitle">Program Studi menjalin kerjasama strategis dengan berbagai instansi untuk pengembangan pendidikan, penelitian, dan pengabdian.</p>
            </div>
            
            @if($mitraList->isEmpty())
                <div class="text-center text-muted">Belum ada data mitra kerja sama yang dipublikasikan.</div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
                    @foreach($mitraList as $mitra)
                    <div class="col">
                        <div class="card h-100 text-center" style="transition: all 0.4s ease; border-radius: 20px; background: #ffffff; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 5px 20px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 20px rgba(0,0,0,0.02)';">
                            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(16, 185, 129, 0.1)); color: var(--primary);">
                                    <i class="bi bi-buildings fs-3"></i>
                                </div>
                                <h5 class="fw-bolder mb-3 w-100" style="color: var(--dark); font-size: 1.1rem; line-height: 1.3;">{{ $mitra->nama_mitra }}</h5>
                                <div class="d-flex flex-wrap gap-2 justify-content-center mt-auto">
                                    @if($mitra->tahun_mou)
                                    <span class="badge rounded-pill" style="background: rgba(79, 70, 229, 0.08); color: var(--primary); font-weight: 600;">Mulai: {{ $mitra->tahun_mou }}</span>
                                    @endif
                                    @if($mitra->tahun_berakhir)
                                    <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.08); color: var(--secondary); font-weight: 600;">Akhir: {{ $mitra->tahun_berakhir }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data Prestasi
        var prestasiLabels = {!! json_encode($prestasiMhsTsLabels) !!};
        var prestasiData = {!! json_encode($prestasiMhsTsData) !!};
        
        var prestasiSeries = [];
        for (var field in prestasiData) {
            prestasiSeries.push({
                name: field,
                data: prestasiData[field]
            });
        }

        var prestasiOptions = {
            series: prestasiSeries,
            chart: { type: 'line', height: 350, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
            stroke: { curve: 'smooth', width: 3 },
            markers: { size: 5, hover: { size: 7 } },
            xaxis: { categories: prestasiLabels },
            colors: ['#4f46e5', '#10b981', '#f59e0b', '#ec4899'],
            dataLabels: { enabled: false },
            legend: { position: 'top', markers: { radius: 12 } }
        };
        new ApexCharts(document.querySelector("#prestasiChart"), prestasiOptions).render();

        // Data Rekognisi
        @if(!empty($rekognisiPerTSLabeled))
            var rekognisiLabels = {!! json_encode(array_keys($rekognisiPerTSLabeled)) !!};
            var rekognisiSeries = {!! json_encode(array_values($rekognisiPerTSLabeled)) !!};

            var rekognisiOptions = {
                series: rekognisiSeries,
                chart: { type: 'donut', height: 350, fontFamily: 'Outfit, sans-serif' },
                labels: rekognisiLabels,
                colors: ['#8b5cf6', '#f59e0b', '#ec4899', '#10b981', '#4f46e5'],
                dataLabels: { enabled: true, dropShadow: { enabled: false } },
                legend: { position: 'bottom', markers: { radius: 12 } },
                plotOptions: { pie: { donut: { size: '65%' } } },
                stroke: { width: 0 }
            };
            new ApexCharts(document.querySelector("#rekognisiChart"), rekognisiOptions).render();
        @endif

        // Data Sertifikasi (Bar Horizontal)
        var serkomLabels = {!! json_encode($serkomChartLabels) !!};
        var serkomData = {!! json_encode($serkomChartData) !!};
        if(serkomLabels.length > 0) {
            var serkomOptions = {
                series: [{ name: 'Sertifikasi', data: serkomData }],
                chart: { type: 'bar', height: 350, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
                plotOptions: { bar: { horizontal: true, borderRadius: 4, distributed: true } },
                xaxis: { categories: serkomLabels },
                colors: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4'],
                dataLabels: { enabled: true, style: { colors: ['#fff'] } },
                legend: { show: false }
            };
            new ApexCharts(document.querySelector("#serkomChart"), serkomOptions).render();
        }

        // Data Pendidikan Dosen (Donut)
        var pendidikanLabels = {!! json_encode(array_keys($pendidikanData->toArray())) !!};
        var pendidikanData = {!! json_encode(array_values($pendidikanData->toArray())) !!};
        if(pendidikanLabels.length > 0) {
            var pendidikanOptions = {
                series: pendidikanData,
                chart: { type: 'pie', height: 350, fontFamily: 'Outfit, sans-serif' },
                labels: pendidikanLabels,
                colors: ['#3b82f6', '#f59e0b', '#ef4444', '#10b981'],
                dataLabels: { enabled: true, dropShadow: { enabled: false } },
                legend: { position: 'bottom', markers: { radius: 12 } },
                stroke: { width: 0 }
            };
            new ApexCharts(document.querySelector("#pendidikanChart"), pendidikanOptions).render();
        }

        // Data Kerjasama (Area/Line)
        var pksLabels = {!! json_encode($pksChartLabels) !!};
        var pksData = {!! json_encode($pksChartData) !!};
        var iaData = {!! json_encode($iaChartData) !!};
        if(pksLabels.length > 0) {
            var kerjasamaOptions = {
                series: [
                    { name: 'Perjanjian Kerja Sama (PKS)', data: pksData },
                    { name: 'Implementasi (IA)', data: iaData }
                ],
                chart: { type: 'area', height: 350, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
                stroke: { curve: 'smooth', width: 2 },
                markers: { size: 5, hover: { size: 7 } },
                xaxis: { categories: pksLabels },
                colors: ['#4f46e5', '#ec4899'],
                dataLabels: { enabled: false },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
                legend: { position: 'top', markers: { radius: 12 } }
            };
            new ApexCharts(document.querySelector("#kerjasamaChart"), kerjasamaOptions).render();
        }
    });
</script>
@endpush
