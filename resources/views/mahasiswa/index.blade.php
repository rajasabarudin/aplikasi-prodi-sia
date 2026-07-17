@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<style>
    /* Styling khusus cetak */
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
        }
        body {
            font-size: 11px;
            color: #000;
            background: #fff;
        }
        .page-gradient-bg {
            background: none !important;
            padding: 0 !important;
            border: none !important;
        }
        .glass-panel {
            background: none !important;
            box-shadow: none !important;
            border: none !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 10px;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 4px 5px !important;
            vertical-align: middle !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .table th {
            background-color: #212529 !important;
            color: #fff !important;
            text-align: center !important;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9 !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef !important;
        }
        .col-lg-6, .col-lg-9, .col-md-8, .col-md-12 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Data Mahasiswa Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="row">
    <!-- Kolom Kiri 1: Statistik Dasar & Akademik (d-print-none) -->
    <div class="col-lg-3 col-md-6 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Mahasiswa</h5>
            </div>
            <div class="card-body">
                <!-- Total Mahasiswa -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Mahasiswa</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalMahasiswa }}</h2>
                </div>

                <!-- Berdasarkan Semester -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Semester</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Semester 2</span>
                            <span class="badge bg-primary rounded-pill">{{ $semesterCounts[2] }} Mahasiswa</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Semester 4</span>
                            <span class="badge bg-success rounded-pill">{{ $semesterCounts[4] }} Mahasiswa</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Semester 6</span>
                            <span class="badge bg-warning text-dark rounded-pill" style="background-color: #ffc107 !important;">{{ $semesterCounts[6] }} Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Berdasarkan Kelas -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-houses-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Kelas</span>
                    </div>
                    <div class="ps-1 custom-scroll" style="max-height: 250px; overflow-y: auto;">
                        @forelse ($kelasStats as $stat)
                            <div class="d-flex justify-content-between text-muted small py-2 border-bottom border-light">
                                <span class="text-dark fw-semibold">{{ $stat->kelas }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $stat->count }} Mahasiswa</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Grafik Pai (Pie Chart) -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Visualisasi Kelas</span>
                    </div>
                    <div style="position: relative; height: 200px; width: 100%;">
                        <canvas id="kelasPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kolaborasi Penelitian -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Kolaborasi Penelitian</h5>
            </div>
            <div class="card-body">
                <div class="custom-scroll" style="max-height: 250px; overflow-y: auto;">
                    @forelse ($collabMhs as $m)
                        <div class="py-2 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $m['nama'] }}</span>
                                <span class="badge bg-success rounded-pill" style="font-size: 10px; padding: 4px 8px;">{{ $m['count'] }} Penelitian</span>
                            </div>
                            <small class="text-muted d-block">NIM: {{ $m['nim'] }}</small>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Belum ada mahasiswa berkolaborasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Card Kolaborasi Hibah -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-cash-coin me-2"></i>Kolaborasi Hibah</h5>
            </div>
            <div class="card-body">
                <div class="custom-scroll" style="max-height: 250px; overflow-y: auto;">
                    @forelse ($collabHibah as $m)
                        <div class="py-2 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $m['nama'] }}</span>
                                <span class="badge bg-info text-dark rounded-pill" style="font-size: 10px; padding: 4px 8px; background-color: #0dcaf0 !important;">{{ $m['total'] }} Hibah</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small text-muted">
                                <span>NIM: {{ $m['nim'] }}</span>
                                <span class="text-success fw-bold" style="font-size: 11px;">Rp {{ number_format($m['total_dana'], 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small text-muted mt-1">
                                <small>Rincian:</small>
                                <span>
                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 9px; padding: 2px 5px;">Int: {{ $m['internal'] }}</span>
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 9px; padding: 2px 5px;">Eks: {{ $m['eksternal'] }}</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Belum ada mahasiswa berkolaborasi hibah.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div> <!-- /Kolom Kiri 1 -->

    <!-- Kolom Kiri 2: Kolaborasi HKI, Prestasi & Organisasi (d-print-none) -->
    <div class="col-lg-3 col-md-6 d-print-none mb-4">

        <!-- Card Kolaborasi PKM -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-people-fill me-2 text-success"></i>Kolaborasi PKM</h5>
            </div>
            <div class="card-body">
                <div class="custom-scroll" style="max-height: 250px; overflow-y: auto;">
                    @forelse ($collabPkm as $m)
                        <div class="py-2 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $m['nama'] }}</span>
                                <span class="badge bg-success rounded-pill" style="font-size: 10px; padding: 4px 8px;">{{ $m['count'] }} PKM</span>
                            </div>
                            <small class="text-muted d-block">NIM: {{ $m['nim'] }}</small>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Belum ada mahasiswa berkolaborasi PKM.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Card Kolaborasi HKI -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-award-fill me-2 text-warning"></i>Kolaborasi HKI</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Mhs Pemilik HKI:</span>
                    <button class="btn btn-sm btn-outline-success fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #a7f3d0; color: #065f46; background-color: #ecfdf5;">
                        {{ $mhsPunyaHkiCount }} Mhs
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Dosen Terlibat:</span>
                    <button class="btn btn-sm btn-outline-info fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #9eeaf9; color: #087990; background-color: #e0f7fa;">
                        {{ $dosenTerkaitHkiCount }} Dosen
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted small">Total Karya HKI:</span>
                    <button class="btn btn-sm btn-outline-primary fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #bfdbfe; color: #1e40af; background-color: #eff6ff;">
                        {{ $totalHkiCount }} Karya
                    </button>
                </div>

                <!-- Nav Tabs untuk beralih antara Mahasiswa & Dosen -->
                <ul class="nav nav-pills nav-fill mb-3" id="hkiTab" role="tablist" style="background-color: #f8f9fa; padding: 4px; border-radius: 8px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-1 fw-bold" id="hki-mhs-tab" data-bs-toggle="tab" data-bs-target="#hki-mhs" type="button" role="tab" aria-controls="hki-mhs" aria-selected="true" style="font-size: 0.8rem; border-radius: 6px; padding: 4px 8px;">Mahasiswa</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 fw-bold" id="hki-dosen-tab" data-bs-toggle="tab" data-bs-target="#hki-dosen" type="button" role="tab" aria-controls="hki-dosen" aria-selected="false" style="font-size: 0.8rem; border-radius: 6px; padding: 4px 8px;">Dosen</button>
                    </li>
                </ul>

                <div class="tab-content" id="hkiTabContent">
                    <!-- Tab Mahasiswa -->
                    <div class="tab-pane fade show active" id="hki-mhs" role="tabpanel" aria-labelledby="hki-mhs-tab">
                        <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                            @forelse ($collabHki as $m)
                                <div class="py-2 border-bottom border-light">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        @if($m['id'])
                                            <a href="{{ route('mahasiswa.show', $m['id']) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 0.9rem;">{{ $m['nama'] }}</a>
                                        @else
                                            <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $m['nama'] }}</span>
                                        @endif
                                        <button class="btn btn-xs btn-warning fw-bold px-2 py-0" style="font-size: 10px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2;">
                                            {{ $m['count'] }} HKI
                                        </button>
                                    </div>
                                    <small class="text-muted d-block">NIM: {{ $m['nim'] }}</small>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada mahasiswa memiliki HKI.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab Dosen -->
                    <div class="tab-pane fade" id="hki-dosen" role="tabpanel" aria-labelledby="hki-dosen-tab">
                        <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                            @forelse ($collabDosenHki as $d)
                                <div class="py-2 border-bottom border-light">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        @if($d['id'])
                                            <a href="{{ route('dosen.show', $d['id']) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 0.9rem;">{{ $d['nama'] }}</a>
                                        @else
                                            <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $d['nama'] }}</span>
                                        @endif
                                        <button class="btn btn-xs btn-info text-dark fw-bold px-2 py-0" style="font-size: 10px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2; background-color: #0dcaf0 !important; border-color: #0dcaf0 !important;">
                                            {{ $d['count'] }} HKI
                                        </button>
                                    </div>
                                    <small class="text-muted d-block">Kode: {{ $d['kode'] }}</small>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada dosen terlibat HKI.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Statistik Prestasi Mahasiswa -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-trophy-fill me-2 text-warning"></i>Statistik Prestasi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Mahasiswa Berprestasi:</span>
                        <span class="fw-bold text-dark">{{ $mhsBerprestasiCount ?? 0 }} Mhs</span>
                    </div>
                    <div class="progress" style="height: 6px;" title="{{ $persenMhsPrestasi ?? 0 }}% dari total mahasiswa memiliki prestasi">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $persenMhsPrestasi ?? 0 }}%" aria-valuenow="{{ $persenMhsPrestasi ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">Persentase:</small>
                        <small class="fw-semibold text-warning">{{ $persenMhsPrestasi ?? 0 }}%</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted small">Total Prestasi:</span>
                    <button class="btn btn-sm btn-outline-warning fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #fde68a; color: #b45309; background-color: #fef3c7;">
                        {{ $totalPrestasiCount }} Prestasi
                    </button>
                </div>

                <!-- Nav Tabs untuk beralih antara TA, Bidang & Hasil -->
                <ul class="nav nav-pills nav-fill mb-3" id="prestasiTab" role="tablist" style="background-color: #f8f9fa; padding: 4px; border-radius: 8px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-1 fw-bold" id="prestasi-ts-tab" data-bs-toggle="tab" data-bs-target="#prestasi-ts" type="button" role="tab" aria-controls="prestasi-ts" aria-selected="true" style="font-size: 0.75rem; border-radius: 6px; padding: 4px 2px;">Berdasarkan TA</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 fw-bold" id="prestasi-bidang-tab" data-bs-toggle="tab" data-bs-target="#prestasi-bidang" type="button" role="tab" aria-controls="prestasi-bidang" aria-selected="false" style="font-size: 0.75rem; border-radius: 6px; padding: 4px 2px;">Berdasarkan Bidang</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 fw-bold" id="prestasi-hasil-tab" data-bs-toggle="tab" data-bs-target="#prestasi-hasil" type="button" role="tab" aria-controls="prestasi-hasil" aria-selected="false" style="font-size: 0.75rem; border-radius: 6px; padding: 4px 2px;">Berdasarkan Hasil</button>
                    </li>
                </ul>

                <div class="tab-content" id="prestasiTabContent">
                    <!-- Tab TA -->
                    <div class="tab-pane fade show active" id="prestasi-ts" role="tabpanel" aria-labelledby="prestasi-ts-tab">
                        <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                            @forelse ($prestasiByTs as $tsName => $total)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                    <span class="text-dark fw-semibold" style="font-size: 0.9rem;">TA: {{ $tsName }}</span>
                                    <span class="badge bg-primary rounded-pill fw-bold">{{ $total }} Prestasi</span>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada data prestasi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab Bidang -->
                    <div class="tab-pane fade" id="prestasi-bidang" role="tabpanel" aria-labelledby="prestasi-bidang-tab">
                        <div class="custom-scroll" style="max-height: 240px; overflow-y: auto;">
                            @php
                                $groupedPrestasi = $prestasiList->groupBy('bidang_prestasi');
                            @endphp
                            @forelse ($groupedPrestasi as $bidang => $items)
                                <div class="mb-3">
                                    <div class="bg-light px-2 py-1 rounded fw-bold text-dark mb-1 small d-flex justify-content-between align-items-center" style="font-size: 0.8rem;">
                                        <span><i class="bi bi-tag-fill text-primary me-1"></i>{{ $bidang }}</span>
                                        <span class="badge bg-secondary text-white">{{ $items->count() }}</span>
                                    </div>
                                    <div class="ps-1">
                                        @foreach ($items as $p)
                                            <div class="py-1.5 border-bottom border-light" style="font-size: 0.85rem;">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    @if($p->mahasiswa)
                                                        <a href="{{ route('mahasiswa.show', $p->mahasiswa->id) }}" class="text-dark fw-bold text-decoration-none d-block">{{ $p->mahasiswa->nama }}</a>
                                                    @else
                                                        <span class="text-dark fw-bold d-block">Mhs (NIM: {{ $p->nim }})</span>
                                                    @endif
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-1 py-0" style="font-size: 9px; line-height: 1;">
                                                        {{ $p->prestasi_diraih }}
                                                    </span>
                                                </div>
                                                <small class="text-muted d-block" style="font-size: 0.75rem;" title="{{ $p->nama_prestasi }}">{{ \Str::limit($p->nama_prestasi, 35) }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada data prestasi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab Hasil -->
                    <div class="tab-pane fade" id="prestasi-hasil" role="tabpanel" aria-labelledby="prestasi-hasil-tab">
                        <div class="custom-scroll" style="max-height: 220px; overflow-y: auto;">
                            @foreach ($prestasiByHasil as $hasil => $total)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                    <span class="text-dark fw-semibold" style="font-size: 0.9rem;">{{ $hasil }}</span>
                                    <span class="badge bg-success rounded-pill fw-bold">{{ $total }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Statistik Organisasi Mahasiswa -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-diagram-3-fill me-2 text-info"></i>Statistik Organisasi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Mahasiswa Aktif Organisasi:</span>
                        <span class="fw-bold text-dark">{{ $mhsIkutOrganisasiCount }} Mhs</span>
                    </div>
                    <div class="progress" style="height: 6px;" title="{{ $persenMhsOrganisasi }}% dari total mahasiswa mengikuti organisasi">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persenMhsOrganisasi }}%" aria-valuenow="{{ $persenMhsOrganisasi }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">Persentase:</small>
                        <small class="fw-semibold text-info">{{ $persenMhsOrganisasi }}%</small>
                    </div>
                </div>

                <!-- Nav Tabs untuk beralih antara TA & Anggota -->
                <ul class="nav nav-pills nav-fill mb-3" id="organisasiTab" role="tablist" style="background-color: #f8f9fa; padding: 4px; border-radius: 8px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-1 fw-bold" id="org-ts-tab" data-bs-toggle="tab" data-bs-target="#org-ts" type="button" role="tab" aria-controls="org-ts" aria-selected="true" style="font-size: 0.8rem; border-radius: 6px; padding: 4px 8px;">Berdasarkan TA</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 fw-bold" id="org-mhs-tab" data-bs-toggle="tab" data-bs-target="#org-mhs" type="button" role="tab" aria-controls="org-mhs" aria-selected="false" style="font-size: 0.8rem; border-radius: 6px; padding: 4px 8px;">Daftar Anggota</button>
                    </li>
                </ul>

                <div class="tab-content" id="organisasiTabContent">
                    <!-- Tab TA -->
                    <div class="tab-pane fade show active" id="org-ts" role="tabpanel" aria-labelledby="org-ts-tab">
                        <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                            @forelse ($organisasiByTs as $tsName => $total)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                    <span class="text-dark fw-semibold" style="font-size: 0.9rem;">TA: {{ $tsName }}</span>
                                    <span class="badge bg-info text-dark rounded-pill fw-bold" style="background-color: #0dcaf0 !important; border-color: #0dcaf0 !important;">{{ $total }} Mhs</span>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada data organisasi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab Anggota -->
                    <div class="tab-pane fade" id="org-mhs" role="tabpanel" aria-labelledby="org-mhs-tab">
                        <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                            @forelse ($organisasiMhsList as $o)
                                <div class="py-2 border-bottom border-light">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        @if($o['mhs_id'])
                                            <a href="{{ route('mahasiswa.show', $o['mhs_id']) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 0.9rem;">{{ $o['nama'] }}</a>
                                        @else
                                            <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $o['nama'] }}</span>
                                        @endif
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-1.5 py-0.5" style="font-size: 10px; border-radius: 4px;">
                                            {{ $o['jabatan'] }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.8rem;">{{ $o['nama_organisasi'] }}</small>
                                </div>
                            @empty
                                <div class="text-center py-3 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada mahasiswa aktif organisasi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data & Pencarian -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 fw-bold text-dark">Data Mahasiswa</h1>
                    <span class="badge bg-success-subtle text-success border border-success-subtle ms-2" style="font-size: 0.8rem; border-radius: 6px;" title="Baris berwarna hijau menandakan mahasiswa memiliki kolaborasi Penelitian, Hibah, atau HKI bersama dosen">
                        <i class="bi bg-circle-fill me-1"></i> Kolaborasi Dosen
                    </span>
                </div>
                <p class="text-muted mb-0">Total terfilter: <strong>{{ $mahasiswas instanceof \Illuminate\Pagination\LengthAwarePaginator ? $mahasiswas->total() : $mahasiswas->count() }}</strong> mahasiswa</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger d-print-none">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success d-print-none">{{ session('success') }}</div>
        @endif

        <!-- Panel Pencarian dan Tombol Aksi (Digabung) -->
        <div class="card mb-3 d-print-none border-0 shadow-sm">
            <div class="card-body py-2">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari NIM, nama, kelas..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="per_page" class="form-select" onchange="this.form.submit()" title="Tampilkan baris">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                            <option value="1000" {{ request('per_page') == 1000 ? 'selected' : '' }}>1000 Baris</option>
                        </select>
                    </div>
                    <div class="col-md-7 text-md-end">
                        <button type="submit" class="btn btn-primary me-1"><i class="bi bi-search"></i> Cari</button>
                        <a href="{{ route('mahasiswa.index', array_merge(request()->query(), ['print' => 'all'])) }}" target="_blank" class="btn btn-success me-1"><i class="bi bi-printer"></i> Cetak</a>
                        <button type="button" class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-upload"></i> Import Excel
                        </button>
                        <button type="button" class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#tambahMahasiswaModal">
                            <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
                        </button>
                        @if (request('search') || request('per_page'))
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 8%;">No</th>
                        <th style="width: 25%;">NIM</th>
                        <th style="width: 40%;">Nama</th>
                        <th style="width: 15%;">Kelas</th>
                        <th class="text-center d-print-none" style="width: 12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mahasiswa)
                        <tr @if(in_array($mahasiswa->nim, $collaboratedNims)) style="background-color: #e6f4ea !important;" title="Mahasiswa memiliki kolaborasi dengan dosen (Penelitian/Hibah/HKI)" @endif>
                            <td class="text-center fw-bold text-muted">{{ $mahasiswas instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration : $loop->iteration }}</td>
                            <td class="fw-bold">{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->nama }}</td>
                            <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ $mahasiswa->kelas }}</span></td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data mahasiswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mahasiswas instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center d-print-none mt-4">
                {{ $mahasiswas->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-earmark-excel-fill me-2 text-success"></i>Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">Pilih File Excel</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small>
                            <strong>Format file:</strong> Kolom A = NIM, Kolom B = Nama, Kolom C = Kelas (nama_kelas).
                            Baris pertama adalah header (akan dilewati).
                            <br><a href="{{ route('mahasiswa.template') }}" class="alert-link">Download template Excel</a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Mahasiswa -->
<div class="modal fade" id="tambahMahasiswaModal" tabindex="-1" aria-labelledby="tambahMahasiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mahasiswa.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahMahasiswaModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Data Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_nim" class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="modal_nim" class="form-control" required placeholder="Masukkan NIM...">
                    </div>
                    <div class="mb-3">
                        <label for="modal_nama" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="modal_nama" class="form-control" required placeholder="Masukkan Nama Lengkap...">
                    </div>
                    <div class="mb-3">
                        <label for="modal_kelas" class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                        <select name="kelas" id="modal_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (request('print') == 'all')
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        });
    </script>
@endif

@if ($errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('tambahMahasiswaModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('kelasPieChart').getContext('2d');
        
        var labels = {!! json_encode($kelasStats->pluck('kelas')) !!};
        var data = {!! json_encode($kelasStats->pluck('count')) !!};
        
        // Warna palette premium modern
        var colors = [
            '#6366f1', '#10b981', '#f59e0b', '#3b82f6', 
            '#ec4899', '#8b5cf6', '#06b6d4', '#f97316'
        ];
        
        var backgroundColors = [];
        for (var i = 0; i < labels.length; i++) {
            backgroundColors.push(colors[i % colors.length]);
        }

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: 'rgba(255, 255, 255, 0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                return ' ' + label + ': ' + value + ' Mahasiswa';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
