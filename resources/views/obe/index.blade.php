@extends('layouts.app')

@section('title', 'OBE Accreditation Portal')

@section('content')
<style>
    .obe-tab {
        border-radius: 8px 8px 0 0 !important;
        font-weight: 600;
        color: #475569 !important;
        border: none !important;
        padding: 12px 20px !important;
        background: #f1f5f9;
        margin-right: 4px;
        transition: all 0.3s ease;
    }
    .obe-tab:hover {
        background: #e2e8f0;
    }
    .obe-tab.active {
        background: #3b82f6 !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 10px rgba(59, 130, 246, 0.2) !important;
    }
    .nav-tabs {
        border-bottom: 3px solid #3b82f6;
    }
    .bg-gradient-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important; }
    .bg-gradient-green { background: linear-gradient(135deg, #10b981, #059669) !important; }
    .bg-gradient-orange { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
</style>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-shield-check text-primary me-2"></i>Portal Evaluasi Diri (LED) & LKPS</h3>
            <p class="text-muted mb-0">Instrumen Akreditasi LAM INFOKOM (Standar 6 Kriteria Baru)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('obe.cetak') }}" target="_blank" class="btn btn-dark shadow-sm rounded-3">
                <i class="bi bi-printer text-warning me-2"></i>Cetak Laporan
            </a>
            <button type="button" class="btn btn-success shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalImportBaak">
                <i class="bi bi-file-earmark-excel me-2"></i>Upload File BAAK
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div style="overflow-x: auto; white-space: nowrap;">
            <ul class="nav nav-tabs border-0 flex-nowrap mb-4" id="obeTab" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab active" id="tab-a-btn" data-bs-toggle="tab" data-bs-target="#tab-a" type="button" role="tab">A. Kondisi Eksternal</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-b-btn" data-bs-toggle="tab" data-bs-target="#tab-b" type="button" role="tab">B. Profil UPPS</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c1-btn" data-bs-toggle="tab" data-bs-target="#tab-c1" type="button" role="tab">C.1 Budaya Mutu</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c2-btn" data-bs-toggle="tab" data-bs-target="#tab-c2" type="button" role="tab">C.2 Rel. Pendidikan</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c3-btn" data-bs-toggle="tab" data-bs-target="#tab-c3" type="button" role="tab">C.3 Penelitian</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c4-btn" data-bs-toggle="tab" data-bs-target="#tab-c4" type="button" role="tab">C.4 PkM</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c5-btn" data-bs-toggle="tab" data-bs-target="#tab-c5" type="button" role="tab">C.5 Akuntabilitas</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c6-btn" data-bs-toggle="tab" data-bs-target="#tab-c6" type="button" role="tab">C.6 Diferensiasi</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-d-btn" data-bs-toggle="tab" data-bs-target="#tab-d" type="button" role="tab">D. Suplemen</button></li>
            </ul>
        </div>

        <div class="tab-content" id="obeTabContent">
            
            <div class="tab-pane fade show active" id="tab-a" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-globe me-2 text-warning"></i>Kriteria A: Kondisi Eksternal</h5></div>
                    <div class="card-body">
                        @include("obe.partials.ppepp_docs", ["kCode" => "A", "pCode" => "-", "kName" => "Kriteria A: Kondisi Eksternal", "pName" => "Dokumen Pendukung"])
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-b" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-warning"></i>Kriteria B: Profil UPPS</h5></div>
                    <div class="card-body">
                        @include("obe.partials.ppepp_docs", ["kCode" => "B", "pCode" => "-", "kName" => "Kriteria B: Profil UPPS", "pName" => "Dokumen Pendukung"])
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="tab-c1" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - C.1 Budaya Mutu</h6>
                        <div>
                            <a href="#" class="btn btn-sm btn-primary me-2"><i class="bi bi-pencil-square me-1"></i>Input Perbaikan Mutu (CQI)</a>
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">P (Penetapan)</th>
                                        <th style="width: 20%;">P (Pelaksanaan)</th>
                                        <th style="width: 20%;">E (Evaluasi)</th>
                                        <th style="width: 20%;">P (Pengendalian)</th>
                                        <th style="width: 20%;">P (Peningkatan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P1", "kName" => "C.1 Budaya Mutu", "pName" => "Penetapan"])</td>

                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P2", "kName" => "C.1 Budaya Mutu", "pName" => "Pelaksanaan"])</td>
                                        <td>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-chat-square-heart me-1"></i>Survei Kepuasan Stakeholder</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'Survei', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-ui-checks me-1"></i>Evaluasi Pemahaman Visi Misi</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C1', 'ppepp' => 'P3_SurveiVisi']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P3", "kName" => "C.1 Budaya Mutu", "pName" => "Evaluasi"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P4", "kName" => "C.1 Budaya Mutu", "pName" => "Pengendalian"])</td>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-arrow-repeat me-1"></i>Live: {{ isset($cqiLogs) ? $cqiLogs->count() : 0 }} CQI Logs</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C1', 'ppepp' => 'P5']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P5", "kName" => "C.1 Budaya Mutu", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-c2" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - C.2 Relevansi Pendidikan</h6>
                        <div>
                            
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">P (Penetapan)</th>
                                        <th style="width: 20%;">P (Pelaksanaan)</th>
                                        <th style="width: 20%;">E (Evaluasi)</th>
                                        <th style="width: 20%;">P (Pengendalian)</th>
                                        <th style="width: 20%;">P (Peningkatan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-graph-up-arrow me-1"></i>Tren PMB: {{ $pmbCount ?? 0 }} Thn</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P1_PMB']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-file-pdf me-1"></i>Buku Kurikulum</span>
    <a href="/buku kurikulum.pdf" target="_blank" class="badge bg-success text-decoration-none">Buka</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P1", "kName" => "C.2 Relevansi Pendidikan", "pName" => "Penetapan"])</td>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-people-fill me-1"></i>Live: {{ $jumlahMahasiswa ?? 0 }} Mhs</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P2_Mhs']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-file-earmark-check me-1"></i>Live: {{ $totalRps ?? 0 }} RPS</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P2_Rps']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-link-45deg me-1"></i>Implementation Arrangement (IA)</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P2_IA']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
@foreach($prestasiMahasiswas->groupBy('bidang_prestasi') as $bidang => $prestasis)
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-trophy-fill me-1"></i>Prestasi {{ $bidang }}: {{ count($prestasis) }}</span>
    <a href="{{ route('prestasi-mahasiswa.index') }}" target="_blank" class="badge bg-success text-decoration-none">Lihat</a>
</div>
@endforeach
@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P2", "kName" => "C.2 Relevansi Pendidikan", "pName" => "Pelaksanaan"])</td>
                                        <td>
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-graph-up me-1"></i>Rerata IPK: {{ $avgIpk ?? 0 }}</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P3_Ipk']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-dark border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-table me-1"></i>Matriks Kohort</span>
    <div>
        <a href="{{ route('kohort.index') }}" class="badge bg-dark text-decoration-none me-1">Lihat</a>
        <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P3_Kohort']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
    </div>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P3", "kName" => "C.2 Relevansi Pendidikan", "pName" => "Evaluasi"])</td>
                                        <td>
<div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-briefcase me-1"></i>Tracer Study Alumni</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P4_Tracer']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P4", "kName" => "C.2 Relevansi Pendidikan", "pName" => "Pengendalian"])</td>
                                        <td>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-journal-code me-1"></i>{{ $capstoneCount ?? 0 }} Capstone/TA</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P5_Capstone']) }}" target="_blank" class="badge bg-info text-dark text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P5", "kName" => "C.2 Relevansi Pendidikan", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-c3" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - C.3 Relevansi Penelitian</h6>
                        <div>
                            
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">P (Penetapan)</th>
                                        <th style="width: 20%;">P (Pelaksanaan)</th>
                                        <th style="width: 20%;">E (Evaluasi)</th>
                                        <th style="width: 20%;">P (Pengendalian)</th>
                                        <th style="width: 20%;">P (Peningkatan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C3", "pCode" => "P1", "kName" => "C.3 Relevansi Penelitian", "pName" => "Penetapan"])</td>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-search me-1"></i>{{ $penelitianCount ?? 0 }} Penelitian Dosen</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-cash-coin me-1"></i>{{ $hibahCount ?? 0 }} Hibah/Dana Riset</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P2_Hibah']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-person-workspace me-1"></i>{{ $kegiatanDosenCount ?? 0 }} Kegiatan Dosen</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P2_KegiatanDosen']) }}" target="_blank" class="badge bg-info text-dark text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C3", "pCode" => "P2", "kName" => "C.3 Relevansi Penelitian", "pName" => "Pelaksanaan"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C3", "pCode" => "P3", "kName" => "C.3 Relevansi Penelitian", "pName" => "Evaluasi"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C3", "pCode" => "P4", "kName" => "C.3 Relevansi Penelitian", "pName" => "Pengendalian"])</td>
                                        <td>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPenelitianCount ?? 0 }} Integrasi ke RPS</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'Integrasi']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C3", "pCode" => "P5", "kName" => "C.3 Relevansi Penelitian", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-c4" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - C.4 Relevansi PkM</h6>
                        <div>
                            
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">P (Penetapan)</th>
                                        <th style="width: 20%;">P (Pelaksanaan)</th>
                                        <th style="width: 20%;">E (Evaluasi)</th>
                                        <th style="width: 20%;">P (Pengendalian)</th>
                                        <th style="width: 20%;">P (Peningkatan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P1", "kName" => "C.4 Relevansi PkM", "pName" => "Penetapan"])</td>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-heart-pulse me-1"></i>{{ $pkmCount ?? 0 }} PkM Dosen</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-people me-1"></i>{{ $praktisiCount ?? 0 }} Praktisi Mengajar</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P2_Praktisi']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P2", "kName" => "C.4 Relevansi PkM", "pName" => "Pelaksanaan"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P3", "kName" => "C.4 Relevansi PkM", "pName" => "Evaluasi"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P4", "kName" => "C.4 Relevansi PkM", "pName" => "Pengendalian"])</td>
                                        <td>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPkmCount ?? 0 }} Integrasi ke RPS</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'Integrasi']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P5", "kName" => "C.4 Relevansi PkM", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-c5" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - C.5 Akuntabilitas</h6>
                        <div>
                            
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">P (Penetapan)</th>
                                        <th style="width: 20%;">P (Pelaksanaan)</th>
                                        <th style="width: 20%;">E (Evaluasi)</th>
                                        <th style="width: 20%;">P (Pengendalian)</th>
                                        <th style="width: 20%;">P (Peningkatan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P1", "kName" => "C.5 Akuntabilitas", "pName" => "Penetapan"])</td>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-person-video3 me-1"></i>{{ $jumlahDosen ?? 0 }} Dosen Tetap</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2_Dosen']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-person-workspace me-1"></i>{{ $jumlahTendik ?? 0 }} Tenaga Kependidikan</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2_Tendik']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-calendar2-check me-1"></i>{{ isset($pesertaDosenCount) ? $pesertaDosenCount : 0 }} Keikutsertaan Kegiatan (Dosen)</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2_KegDosen']) }}" target="_blank" class="badge bg-info text-dark text-decoration-none">PDF</a>
</div>
<div class="alert alert-dark border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-calendar2-check me-1"></i>{{ isset($pesertaTendikCount) ? $pesertaTendikCount : 0 }} Keikutsertaan Kegiatan (Tendik)</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2_KegTendik']) }}" target="_blank" class="badge bg-dark text-white text-decoration-none">PDF</a>
</div>
@php
    $totalDanaKeuangan = class_exists('\App\Models\KeuanganProdi') ? \App\Models\KeuanganProdi::sum('dana_pendidikan') + \App\Models\KeuanganProdi::sum('dana_penelitian') + \App\Models\KeuanganProdi::sum('dana_pkm') + \App\Models\KeuanganProdi::sum('dana_investasi') : 0;
    $totalHibah = \Illuminate\Support\Facades\DB::table('hibah_penelitians')->sum('biaya') + \Illuminate\Support\Facades\DB::table('penelitian_dosens')->sum('biaya') + \Illuminate\Support\Facades\DB::table('pkm_dosens')->sum('biaya');
    $grandTotalDana = $totalDanaKeuangan + $totalHibah;
    $totalDanaFormat = $grandTotalDana >= 1000000000 ? 'Rp ' . number_format($grandTotalDana/1000000000, 1) . 'M' : ($grandTotalDana >= 1000000 ? 'Rp ' . number_format($grandTotalDana/1000000, 0) . ' Jt' : 'Rp 0');
@endphp
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-wallet2 me-1"></i>Dana: {{ $totalDanaFormat }}</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2_Dana']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-link-45deg me-1"></i>{{ isset($kerjasamaCount) ? $kerjasamaCount : 0 }} MoU & MoA</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2_Kerjasama']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P2", "kName" => "C.5 Akuntabilitas", "pName" => "Pelaksanaan"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P3", "kName" => "C.5 Akuntabilitas", "pName" => "Evaluasi"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P4", "kName" => "C.5 Akuntabilitas", "pName" => "Pengendalian"])</td>
                                        <td>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-shield-check me-1"></i>{{ isset($sertifikasiDosenCount) ? $sertifikasiDosenCount : 0 }} Sertifikasi Dosen</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P5_Sertifikasi']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-award me-1"></i>{{ isset($prestasiDosenCount) ? $prestasiDosenCount : 0 }} Prestasi Dosen</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P5_Prestasi']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-hand-thumbs-up me-1"></i>{{ isset($rekognisiDosenCount) ? $rekognisiDosenCount : 0 }} Rekognisi Dosen</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P5_Rekognisi']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P5", "kName" => "C.5 Akuntabilitas", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-c6" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - C.6 Diferensiasi Misi</h6>
                        <div>
                            
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">P (Penetapan)</th>
                                        <th style="width: 20%;">P (Pelaksanaan)</th>
                                        <th style="width: 20%;">E (Evaluasi)</th>
                                        <th style="width: 20%;">P (Pengendalian)</th>
                                        <th style="width: 20%;">P (Peningkatan)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-bookmark-star me-1"></i>Penetapan CPL Prodi</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P1_CPL']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P1", "kName" => "C.6 Diferensiasi Misi", "pName" => "Penetapan"])</td>
                                        <td>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-journal-code me-1"></i>{{ $mkUnggulanCount ?? 0 }} MK Inti / Unggulan</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P2_MK_Unggulan']) }}" target="_blank" class="badge bg-info text-dark text-decoration-none">PDF</a>
</div>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-person-check me-1"></i>{{ $tracerStudyCount ?? 0 }} Tracer Study</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P2_Tracer']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-journal-bookmark me-1"></i>{{ $publikasiNasionalCount ?? 0 }} Publikasi Nasional</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P2_PubNasional']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-globe me-1"></i>{{ $publikasiInternasionalCount ?? 0 }} Pub. Internasional</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P2_PubInternasional']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-people me-1"></i>{{ $pkmCount ?? 0 }} Pengabdian Masy.</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P2_PkmDosen']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P2", "kName" => "C.6 Diferensiasi Misi", "pName" => "Pelaksanaan"])</td>
                                        <td>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-graph-up me-1"></i>Ketercapaian CPL / CPMK Mhs</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P3_Nilai']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P3", "kName" => "C.6 Diferensiasi Misi", "pName" => "Evaluasi"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P4", "kName" => "C.6 Diferensiasi Misi", "pName" => "Pengendalian"])</td>
                                        <td>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-award me-1"></i>Live: {{ $serkomCount ?? 0 }} Sertifikasi Mhs</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P5_Serkom']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-trophy me-1"></i>Live: {{ $prestasiCount ?? 0 }} Prestasi Mhs</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P5_Prestasi']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-lightbulb me-1"></i>Live: {{ $hkiCount ?? 0 }} Hak Cipta/HKI</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P5_HKI']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-journal-check me-1"></i>{{ $organisasiCount ?? 0 }} Org. Mahasiswa</span>
    <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P5_Organisasi']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P5", "kName" => "C.6 Diferensiasi Misi", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<div class="tab-pane fade" id="tab-d" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-plus-square me-2 text-warning"></i>Kriteria D: Suplemen</h5></div>
                    <div class="card-body">
                        @include("obe.partials.ppepp_docs", ["kCode" => "D", "pCode" => "-", "kName" => "Kriteria D: Suplemen", "pName" => "Dokumen Pendukung"])
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div>

<!-- MODALS -->

<!-- Modal Upload File BAAK -->

<div class="modal fade" id="modalImportBaak" tabindex="-1" aria-labelledby="modalImportBaakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="modalImportBaakLabel"><i class="bi bi-file-earmark-excel me-2"></i>Upload File Nilai dari BAAK</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('obe.upload-baak') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small">Silakan unggah berkas excel pengukuran CPL dari BAAK. Sistem akan memetakan dan mengonversi nilai UTS, UAS, dan Tugas secara otomatis untuk seluruh mata kuliah dan mahasiswa di dalam file tersebut.</p>
                    
                    <div class="alert alert-info border-0 py-2 px-3 mb-3 small d-flex justify-content-between align-items-center" style="border-radius: 8px;">
                        <span>Format berkas harus sesuai template standar BAAK.</span>
                        <a href="{{ asset('Template_CPL_Baru.xlsx') }}" download class="btn btn-sm btn-success fw-bold text-decoration-none">
                            <i class="bi bi-download me-1"></i>Unduh Template Baru
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select name="tahun_ajaran" class="form-select" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach(\App\Models\Ts::orderBy('id', 'desc')->pluck('tahun_sekarang')->filter()->values() as $ta)
                                <option value="{{ $ta }}">{{ $ta }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Berkas Excel (.xlsx, .xls) <span class="text-danger">*</span></label>
                        <input type="file" name="baak_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4"><i class="bi bi-upload me-1"></i>Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Upload Dokumen PPEPP -->
<div class="modal fade" id="modalUploadPpepp" tabindex="-1" aria-labelledby="modalUploadPpeppLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalUploadPpeppLabel"><i class="bi bi-file-earmark-arrow-up text-warning me-2"></i>Unggah Dokumen Penjaminan Mutu (PPEPP)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('obe.upload-ppepp') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Kriteria Akreditasi <span class="text-danger">*</span></label>
                        <select name="kriteria" id="ppepp_select_kriteria" class="form-select" required>
                            <option value="A">Kriteria A: Kondisi Eksternal</option>
                            <option value="B">Kriteria B: Profil UPPS</option>
                            <option value="C1">C1: Budaya Mutu</option>
                            <option value="C2">C2: Relevansi Pendidikan</option>
                            <option value="C3">C3: Relevansi Penelitian</option>
                            <option value="C4">C4: Relevansi Pengabdian Kepada Masyarakat</option>
                            <option value="C5">C5: Akuntabilitas</option>
                            <option value="C6">C6: Diferensiasi Misi</option>
                            <option value="D">Kriteria D: Suplemen</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Tahap PPEPP <span class="text-danger">*</span></label>
                        <select name="ppepp" id="ppepp_select_stage" class="form-select" required>
                            <option value="-">- (Hanya Dokumen Utama)</option>
                            <option value="P1">Penetapan (Standar)</option>
                            <option value="P2">Pelaksanaan (Implementasi)</option>
                            <option value="P3">Evaluasi (Audit/Pengukuran)</option>
                            <option value="P4">Pengendalian (Tindak Lanjut)</option>
                            <option value="P5">Peningkatan (Peningkatan Mutu)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Dokumen Bukti Fisik <span class="text-danger">*</span></label>
                        <input type="text" name="nama_dokumen" id="ppepp_input_docname" class="form-control" placeholder="Contoh: SK Rektor Visi Misi 2025" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tautan Dokumen Bukti Fisik (G-Drive/Cloud) <span class="text-danger">*</span></label>
                        <input type="url" name="file_dokumen" class="form-control" placeholder="https://drive.google.com/..." required>
                        <small class="text-muted">Pastikan pengaturan berbagi tautan sudah di-set ke 'Bisa diakses publik'.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-link-45deg me-1"></i>Simpan Tautan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById('chartCplAttainment').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: {!! json_encode($cplLabels) !!},
            datasets: [
                {
                    label: 'Rerata Capaian Nilai CPL',
                    data: {!! json_encode($cplAverages) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                },
                {
                    label: 'Persentase Target Tercapai (%)',
                    data: {!! json_encode($cplTargetMetPerc) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.15)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                },
                {
                    label: 'Standar Kelulusan Target (75%)',
                    data: Array({!! count($cpls) !!}).fill(75),
                    backgroundColor: 'rgba(239, 68, 68, 0.02)',
                    borderColor: 'rgba(239, 68, 68, 0.8)',
                    borderWidth: 1.5,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { display: true },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});

function openQuickUpload(kriteriaCode, ppeppStage, kriteriaName, ppeppName) {
    document.getElementById('ppepp_select_kriteria').value = kriteriaCode;
    document.getElementById('ppepp_select_stage').value = ppeppStage;
    document.getElementById('ppepp_input_docname').value = 'SK/Dokumen Bukti ' + kriteriaCode + ' (' + ppeppStage + ')';
    
    var myModal = new bootstrap.Modal(document.getElementById('modalUploadPpepp'));
    myModal.show();
}
</script>
@endpush
@endsection
