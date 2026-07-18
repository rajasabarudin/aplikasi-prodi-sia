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
                <div class="col-lg-5 d-none d-lg-block text-center position-relative z-1" data-aos="fade-left" data-aos-delay="200">
                    <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Student" style="width: 100%; max-width: 450px;">
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

    <!-- Highlight Prestasi & Rekognisi Grafik -->
    <section class="py-5 mb-5 glass-card rounded-5 shadow-sm mx-lg-4 p-lg-5" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-8">
                    <h2 class="section-title">Statistik & Tren Pencapaian</h2>
                    <p class="section-subtitle mb-0">Pemantauan visual terhadap rekam jejak prestasi mahasiswa dan tingkat rekognisi dosen dari tahun ke tahun.</p>
                </div>
            </div>

            <div class="row g-4">
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
        </div>
    <!-- Kualitas Akademik & Kompetensi -->
    <section class="py-5 mb-5 glass-card rounded-5 shadow-sm mx-lg-4 p-lg-5" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-8">
                    <h2 class="section-title">Kualitas Akademik & Kompetensi</h2>
                    <p class="section-subtitle mb-0">Visualisasi profil kualifikasi dosen dan sebaran sertifikasi kompetensi yang diakui industri untuk mahasiswa kami.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-patch-check-fill text-success me-2"></i>Sertifikasi Kompetensi Mahasiswa</h5>
                        <div id="serkomChart"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-mortarboard-fill text-danger me-2"></i>Kualifikasi Pendidikan Dosen</h5>
                        <div id="pendidikanChart" class="d-flex justify-content-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlight Mitra Kerja Sama -->
    <section class="py-5 bg-light rounded-5 shadow-sm mx-lg-4 p-lg-5 mb-5 border">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold mb-3 border border-danger border-opacity-25">Kolaborasi & Sinergi</span>
                <h2 class="section-title">Mitra Kerja Sama (MoU, PKS, IA)</h2>
                <p class="section-subtitle">Program Studi menjalin kerjasama strategis dengan berbagai instansi untuk pengembangan pendidikan, penelitian, dan pengabdian.</p>
            </div>
            
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4" style="background: rgba(255,255,255,0.9);">
                        <h5 class="fw-bold mb-4 text-dark text-center"><i class="bi bi-globe-americas text-primary me-2"></i>Tren Realisasi Kerjasama (PKS & IA)</h5>
                        <div id="kerjasamaChart"></div>
                    </div>
                </div>
            </div>

            @if($mitraList->isEmpty())
                <div class="text-center text-muted">Belum ada data mitra kerja sama yang dipublikasikan.</div>
            @else
                <div class="marquee">
                    <div class="marquee-content" style="animation-direction: reverse;">
                        {{-- Duplicated for infinite effect --}}
                        @for($i=0; $i<2; $i++)
                            @foreach($mitraList as $mitra)
                            <div class="marquee-item" style="width: auto;">
                                <div class="card h-100 text-center" style="transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); width: 380px; margin: 0 10px; border-radius: 20px; background: #ffffff; border: 1px solid rgba(0,0,0,0.03); box-shadow: 0 10px 30px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.02)';">
                                    <div class="card-body p-5 d-flex flex-column align-items-center justify-content-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px; background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(16, 185, 129, 0.1)); color: var(--primary);">
                                            <i class="bi bi-buildings fs-2"></i>
                                        </div>
                                        <h5 class="fw-bolder mb-3 w-100" style="color: var(--dark); letter-spacing: -0.5px; white-space: normal; line-height: 1.4;">{{ $mitra->nama_mitra }}</h5>
                                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                                            @if($mitra->tahun_mou)
                                            <span class="badge rounded-pill" style="background: rgba(79, 70, 229, 0.08); color: var(--primary); font-weight: 600; padding: 6px 12px; font-size: 0.75rem;">Mulai: {{ $mitra->tahun_mou }}</span>
                                            @endif
                                            @if($mitra->tahun_berakhir)
                                            <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.08); color: var(--secondary); font-weight: 600; padding: 6px 12px; font-size: 0.75rem;">Berakhir: {{ $mitra->tahun_berakhir }}</span>
                                            @endif
                                        </div>
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
