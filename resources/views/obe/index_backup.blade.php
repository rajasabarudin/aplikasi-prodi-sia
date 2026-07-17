@extends('layouts.app')

@section('title', 'OBE Accreditation Portal')

@section('content')
<style>
    .bg-gradient-blue {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    }
    .bg-gradient-green {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    .bg-gradient-orange {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    }
    .obe-tab {
        border-radius: 12px 12px 0 0 !important;
        font-weight: 600;
        color: #475569 !important;
        border: none !important;
        padding: 12px 20px !important;
    }
    .obe-tab.active {
        background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
        color: #fff !important;
        box-shadow: 0 -4px 15px rgba(79, 70, 229, 0.15) !important;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
    }
    .badge-cqi {
        border-radius: 30px;
        padding: 6px 12px;
        font-size: 0.75rem;
    }
</style>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-shield-check text-primary me-2"></i>OBE Accreditation & Portofolio Portal</h3>
            <p class="text-muted mb-0">Halaman pemetaan portofolio program studi berbasis Outcome-Based Education (OBE) untuk Akreditasi Unggul.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('obe.transkrip') }}" class="btn btn-outline-primary btn-lg shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-file-earmark-person me-2"></i>Transkrip CPL
            </a>
            <a href="{{ route('obe.cetak') }}" target="_blank" class="btn btn-dark btn-lg shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-printer me-2 text-warning"></i>Cetak Laporan
            </a>
            <button type="button" class="btn btn-success btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportBaak" style="border-radius: 10px;">
                <i class="bi bi-file-earmark-excel me-2"></i>Upload File BAAK
            </button>
            <a href="{{ route('obe.input-score') }}" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-plus-circle me-2"></i>Input Nilai CPMK
            </a>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success shadow-sm border-0 border-start border-success border-4 mb-4">
        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
    </div>
@endif

<!-- NAV TABS -->
<div class="card shadow-sm border-0 mb-4 bg-transparent" style="overflow-x: auto; white-space: nowrap;">
    <ul class="nav nav-tabs border-0 flex-nowrap" id="obeTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-a-btn" data-bs-toggle="tab" data-bs-target="#tab-a" type="button" role="tab"><i class="bi bi-globe me-2"></i>A. Kondisi Eksternal</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-b-btn" data-bs-toggle="tab" data-bs-target="#tab-b" type="button" role="tab"><i class="bi bi-building me-2"></i>B. Profil UPPS</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab active me-2" id="tab-c1-btn" data-bs-toggle="tab" data-bs-target="#tab-c1" type="button" role="tab"><i class="bi bi-shield-check me-2"></i>C.1 Budaya Mutu</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-c2-btn" data-bs-toggle="tab" data-bs-target="#tab-c2" type="button" role="tab"><i class="bi bi-book-half me-2"></i>C.2 Relevansi Pendidikan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-c3-btn" data-bs-toggle="tab" data-bs-target="#tab-c3" type="button" role="tab"><i class="bi bi-journal-text me-2"></i>C.3 Relevansi Penelitian</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-c4-btn" data-bs-toggle="tab" data-bs-target="#tab-c4" type="button" role="tab"><i class="bi bi-people me-2"></i>C.4 Relevansi PkM</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-c5-btn" data-bs-toggle="tab" data-bs-target="#tab-c5" type="button" role="tab"><i class="bi bi-wallet2 me-2"></i>C.5 Akuntabilitas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab me-2" id="tab-c6-btn" data-bs-toggle="tab" data-bs-target="#tab-c6" type="button" role="tab"><i class="bi bi-stars me-2"></i>C.6 Diferensiasi Misi</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link obe-tab" id="tab-d-btn" data-bs-toggle="tab" data-bs-target="#tab-d" type="button" role="tab"><i class="bi bi-plus-square me-2"></i>D. Suplemen</button>
        </li>
    </ul>
</div>

<!-- TAB CONTENTS -->
<div class="tab-content" id="obeTabContent">
    
    <!-- KRITERIA A: CPL -->
    <div class="tab-pane fade" id="tab-c2" role="tabpanel"><h4 class="mb-4 fw-bold">C.2 Relevansi Pendidikan (Capaian Pembelajaran)</h4>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <form action="{{ route('obe.index') }}" method="GET" class="d-flex align-items-center">
                    <label class="me-2 fw-bold text-secondary text-nowrap">Filter Tahun Ajaran:</label>
                    <select name="tahun_ajaran" class="form-select border-primary" onchange="this.form.submit()">
                        <option value="">Semua TA (Akumulasi)</option>
                        @foreach($availableTas as $ta)
                            <option value="{{ $ta }}" {{ $selectedTa == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Grafik Ketercapaian CPL</h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2">Threshold: 70%</span>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <canvas id="chartCplAttainment" height="260"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-light py-3 border-bottom">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-list-stars text-primary me-2"></i>Daftar Capaian Pembelajaran Lulusan (CPL)</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 15%;">Kode CPL</th>
                                        <th style="width: 55%;">Deskripsi Kompetensi</th>
                                        <th class="text-center" style="width: 15%;">Rerata Nilai</th>
                                        <th class="text-center" style="width: 15%;">Ketercapaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cpls as $index => $cpl)
                                    <tr>
                                        <td class="text-center fw-bold text-primary">{{ $cpl->kode_cpl }}</td>
                                        <td style="text-align: justify;">{{ $cpl->deskripsi_cpl }}</td>
                                        <td class="text-center fw-bold">{{ $cplAverages[$index] }}</td>
                                        <td class="text-center">
                                            @if($cplTargetMetPerc[$index] >= 75)
                                                <span class="badge bg-success" style="border-radius: 30px;">{{ $cplTargetMetPerc[$index] }}%</span>
                                            @elseif($cplTargetMetPerc[$index] >= 60)
                                                <span class="badge bg-warning text-dark" style="border-radius: 30px;">{{ $cplTargetMetPerc[$index] }}%</span>
                                            @else
                                                <span class="badge bg-danger" style="border-radius: 30px;">{{ $cplTargetMetPerc[$index] }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KRITERIA B: KURIKULUM -->
    <h4 class="mt-5 mb-4 fw-bold border-top pt-4">Struktur Kurikulum & Matakuliah</h4>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-4 d-flex align-items-center">
                        <i class="bi bi-journal-text fs-1 me-3"></i>
                        <div>
                            <h6 class="mb-1 text-white-50 fw-semibold">Jumlah Mata Kuliah</h6>
                            <span class="fs-2 fw-bold text-white">{{ $totalMk }} MK</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-4 d-flex align-items-center">
                        <i class="bi bi-clock-history fs-1 me-3"></i>
                        <div>
                            <h6 class="mb-1 text-white-50 fw-semibold">Total SKS Kurikulum</h6>
                            <span class="fs-2 fw-bold text-white">{{ $totalSks }} SKS</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-dark text-white">
                    <div class="card-body py-4 d-flex align-items-center">
                        <i class="bi bi-patch-check-fill fs-1 me-3 text-warning"></i>
                        <div>
                            <h6 class="mb-1 text-white-50 fw-semibold">Persentase RPS Selesai</h6>
                            <span class="fs-2 fw-bold text-white">{{ $rpsCompleteness }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-table text-primary me-2"></i>Struktur Kurikulum & Kelengkapan Dokumen Pembelajaran</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 15%;">Kode MK</th>
                                <th style="width: 30%;">Nama Mata Kuliah</th>
                                <th class="text-center" style="width: 10%;">Semester</th>
                                <th class="text-center" style="width: 10%;">SKS (T/PA/PU)</th>
                                <th class="text-center" style="width: 15%;">Status RPS</th>
                                <th class="text-center" style="width: 15%;">Status RTM & Silabus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matakuliahList as $idx => $mk)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td><code>{{ $mk->kode_matakuliah }}</code></td>
                                <td class="fw-bold">{{ $mk->nama_matakuliah }}</td>
                                <td class="text-center">{{ $mk->semester }}</td>
                                <td class="text-center">{{ $mk->sks_t + $mk->sks_pa + $mk->sks_pu }} SKS</td>
                                <td class="text-center">
                                    @if($mk->rps)
                                        <a href="{{ route('penyusunan-rps.cetak', $mk->rps->id) }}" target="_blank" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none px-2 py-1">
                                            <i class="bi bi-file-earmark-check me-1"></i>Tersedia (Cetak)
                                        </a>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Belum Dibuat</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($mk->rps && $mk->rps->rtm)
                                        <a href="{{ route('penyusunan-rtm.cetak', $mk->rps->rtm->id) }}" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1 me-1">
                                            RTM
                                        </a>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 me-1">RTM (-)</span>
                                    @endif

                                    @if($mk->rps && $mk->rps->silabus)
                                        <a href="{{ route('penyusunan-silabus.cetak', $mk->rps->silabus->id) }}" target="_blank" class="badge bg-info-subtle text-info border border-info-subtle text-decoration-none px-2 py-1">
                                            Silabus
                                        </a>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">Silabus (-)</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- KRITERIA C: ASESMEN & CQI -->
    <div class="tab-pane fade show active" id="tab-c1" role="tabpanel"><h4 class="mb-4 fw-bold">Budaya Mutu (Asesmen & CQI)</h4>
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill" id="subObeTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-2 small fw-semibold" id="sub-c1-tab" data-bs-toggle="pill" data-bs-target="#sub-c1" type="button"><i class="bi bi-file-earmark-diff me-1"></i>C1: Penilaian CPMK</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-2 small fw-semibold" id="sub-c2-tab" data-bs-toggle="pill" data-bs-target="#sub-c2" type="button"><i class="bi bi-graph-up me-1"></i>C2: Penilaian CPL</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-2 small fw-semibold" id="sub-c3-tab" data-bs-toggle="pill" data-bs-target="#sub-c3" type="button"><i class="bi bi-arrow-repeat me-1"></i>C3: Siklus CQI</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-2 small fw-semibold" id="sub-c4-tab" data-bs-toggle="pill" data-bs-target="#sub-c4" type="button"><i class="bi bi-award me-1"></i>C4: Portofolio & Serkom</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-2 small fw-semibold" id="sub-c5-tab" data-bs-toggle="pill" data-bs-target="#sub-c5" type="button"><i class="bi bi-search me-1"></i>C5: Tracer Study</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-2 small fw-semibold" id="sub-c6-tab" data-bs-toggle="pill" data-bs-target="#sub-c6" type="button"><i class="bi bi-chat-heart me-1"></i>C6: Kepuasan Stakeholder</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="subObeTabContent">
            <!-- C1: Penilaian CPMK -->
            <div class="tab-pane fade show active" id="sub-c1" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-diff text-primary me-2"></i>Kriteria C1: Rerata Capaian Nilai CPMK (Direct Assessment)</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 15%;">Kode CPMK</th>
                                        <th style="width: 55%;">Deskripsi CPMK</th>
                                        <th class="text-center" style="width: 15%;">CPL Pendukung</th>
                                        <th class="text-center" style="width: 15%;">Rerata Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $cpmkList = \App\Models\Cpmk::with('cpl')->get();
                                    @endphp
                                    @foreach($cpmkList as $c)
                                    @php
                                        $avg = \App\Models\ObeNilaiCpmk::where('cpmk_id', $c->id)->avg('nilai') ?: 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark fw-bold">{{ $c->kode_cpmk }}</span></td>
                                        <td class="text-justify">{{ $c->deskripsi_cpmk }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $c->cpl?->kode_cpl ?? '-' }}</td>
                                        <td class="text-center fw-bold">{{ round($avg, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C2: Penilaian CPL -->
            <div class="tab-pane fade" id="sub-c2" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up text-primary me-2"></i>Kriteria C2: Penilaian Akumulatif Ketercapaian CPL Lulusan</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Capaian CPL dihitung secara otomatis dan akumulatif berdasarkan ketercapaian target kelulusan mahasiswa pada CPMK pendukung CPL tersebut.</p>
                        <table class="table table-bordered table-striped align-middle" style="font-size: 0.9rem;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 15%;">Kode CPL</th>
                                    <th>Deskripsi CPL</th>
                                    <th class="text-center" style="width: 20%;">Rerata Nilai Capaian</th>
                                    <th class="text-center" style="width: 20%;">Tingkat Kelulusan Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cpls as $index => $cpl)
                                <tr>
                                    <td class="text-center fw-bold text-primary">{{ $cpl->kode_cpl }}</td>
                                    <td>{{ $cpl->deskripsi_cpl }}</td>
                                    <td class="text-center fw-bold">{{ $cplAverages[$index] }}</td>
                                    <td class="text-center fw-bold">
                                        @if($cplTargetMetPerc[$index] >= 75)
                                            <span class="badge bg-success">{{ $cplTargetMetPerc[$index] }}% (Tercapai)</span>
                                        @else
                                            <span class="badge bg-danger">{{ $cplTargetMetPerc[$index] }}% (Belum Tercapai)</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- C3: Siklus CQI -->
            <div class="tab-pane fade" id="sub-c3" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-dark text-white py-3">
                                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-pencil-square me-2 text-warning"></i>Tambah Log Perbaikan Mutu (CQI)</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('obe.store-cqi') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Pilih Mata Kuliah / RPS <span class="text-danger">*</span></label>
                                        <select name="rps_id" class="form-select" required>
                                            <option value="">-- Pilih MK --</option>
                                            @foreach($matakuliahList as $mk)
                                                @if($mk->rps)
                                                <option value="{{ $mk->rps->id }}">{{ $mk->nama_matakuliah }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Semester/TA <span class="text-danger">*</span></label>
                                            <input type="text" name="semester" class="form-control" placeholder="Contoh: 2025/2026 Ganjil" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Fokus CPL</label>
                                            <select name="cpl_id" class="form-select">
                                                <option value="">-- Semua CPL --</option>
                                                @foreach($cpls as $cpl)
                                                <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Analisis Masalah Pembelajaran <span class="text-danger">*</span></label>
                                        <textarea name="analisis_masalah" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Rencana Tindak Lanjut Perbaikan <span class="text-danger">*</span></label>
                                        <textarea name="rencana_perbaikan" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status Tindakan <span class="text-danger">*</span></label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="cqi_draft" value="draft" checked>
                                            <label class="form-check-label" for="cqi_draft">Draft</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="cqi_impl" value="implemented">
                                            <label class="form-check-label" for="cqi_impl">Implemented</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">
                                        <i class="bi bi-save me-2"></i>Simpan Log CQI
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light py-3 border-bottom">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-journals text-primary me-2"></i>Riwayat Siklus CQI (Kriteria C3)</h6>
                            </div>
                            <div class="card-body p-0" style="max-height: 480px; overflow-y: auto;">
                                @forelse($cqiLogs as $log)
                                <div class="p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $log->rps?->matakuliah?->nama_matakuliah }}</h6>
                                        <span class="badge {{ $log->status === 'implemented' ? 'bg-success-subtle text-success border border-success' : 'bg-warning-subtle text-warning border border-warning' }} badge-cqi">
                                            {{ strtoupper($log->status) }}
                                        </span>
                                    </div>
                                    <div class="mb-2 small">
                                        <span class="text-muted"><i class="bi bi-calendar-event me-1"></i>Semester: {{ $log->semester }}</span>
                                    </div>
                                    <div class="bg-light p-2 rounded small border mb-2">
                                        <strong>Masalah:</strong> {{ $log->analisis_masalah }}
                                    </div>
                                    <div class="bg-primary-subtle p-2 rounded small border border-primary-subtle text-dark">
                                        <strong>Rencana Tindakan:</strong> {{ $log->rencana_perbaikan }}
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted py-5">
                                    Belum ada riwayat perbaikan mutu (CQI) yang dicatat.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C4: Portofolio & Serkom -->
            <div class="tab-pane fade" id="sub-c4" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-award text-primary me-2"></i>Kriteria C4: Evaluasi Portofolio Kelulusan Mahasiswa & Sertifikasi Kompetensi (Serkom)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border p-3 text-center h-100 shadow-none">
                                    <i class="bi bi-shield-check text-success fs-1 mb-2"></i>
                                    <h5 class="fw-bold text-dark">Total Sertifikasi Mahasiswa</h5>
                                    <span class="fs-1 fw-bold text-success">{{ $serkomCount }}</span>
                                    <p class="text-muted small mt-2">Sertifikasi profesi nasional/internasional yang diakui oleh BAN-PT (seperti Serkom BNSP, Microsoft, dll).</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border p-3 text-center h-100 shadow-none">
                                    <i class="bi bi-trophy text-warning fs-1 mb-2"></i>
                                    <h5 class="fw-bold text-dark">Total Prestasi Terdata</h5>
                                    <span class="fs-1 fw-bold text-warning">{{ $prestasiCount }}</span>
                                    <p class="text-muted small mt-2">Prestasi akademik dan non-akademik tingkat wilayah, nasional, hingga internasional.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C5: Tracer Study -->
            <div class="tab-pane fade" id="sub-c5" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-search text-primary me-2"></i>Kriteria C5: Tracer Study & Masa Tunggu Kerja Lulusan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 text-center">
                            <div class="col-md-4">
                                <div class="card border p-3 shadow-none">
                                    <i class="bi bi-clock-history text-primary fs-1 mb-2"></i>
                                    <h6>Rerata Masa Tunggu Kerja</h6>
                                    <span class="fs-2 fw-bold text-primary">3.2 Bulan</span>
                                    <small class="text-muted">Target Standar: &lt; 6 Bulan</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border p-3 shadow-none">
                                    <i class="bi bi-briefcase text-success fs-1 mb-2"></i>
                                    <h6>Kesesuaian Bidang Kerja</h6>
                                    <span class="fs-2 fw-bold text-success">84.5%</span>
                                    <small class="text-muted">Sesuai Kompetensi SIA</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border p-3 shadow-none">
                                    <i class="bi bi-cash-stack text-warning fs-1 mb-2"></i>
                                    <h6>Gaji Pertama Lulusan</h6>
                                    <span class="fs-2 fw-bold text-warning">1.4 x UMR</span>
                                    <small class="text-muted">Rata-rata Pendapatan Awal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C6: Kepuasan Stakeholder -->
            <div class="tab-pane fade" id="sub-c6" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-chat-heart me-2 text-warning"></i>Kriteria C6: Survei Kepuasan Stakeholder & Pengguna Lulusan</h6>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalSurvey">
                            <i class="bi bi-plus-circle me-1"></i>Input Data Survei
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($surveys as $tahun => $aspekList)
                            <div class="col-md-6 mb-3">
                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-calendar3 me-2"></i>Survei Tahun {{ $tahun }}</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle" style="font-size: 0.85rem;">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Aspek Penilaian CPL</th>
                                                <th class="text-center" style="width: 12%;">Sangat Baik (%)</th>
                                                <th class="text-center" style="width: 12%;">Baik (%)</th>
                                                <th class="text-center" style="width: 12%;">Cukup (%)</th>
                                                <th class="text-center" style="width: 12%;">Kurang (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($aspekList as $s)
                                            <tr>
                                                <td><strong>{{ $s->aspek_penilaian }}</strong></td>
                                                <td class="text-center text-success fw-bold">{{ $s->nilai_sangat_baik }}%</td>
                                                <td class="text-center text-primary">{{ $s->nilai_baik }}%</td>
                                                <td class="text-center text-warning">{{ $s->nilai_cukup }}%</td>
                                                <td class="text-center text-danger">{{ $s->nilai_kurang }}%</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-4">
                                Belum ada data survei pengguna lulusan.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KRITERIA D: MAHASISWA -->
    <h4 class="mt-5 mb-4 fw-bold border-top pt-4">Data Mahasiswa</h4>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-people-fill text-primary fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark mb-0">Total Mahasiswa</h5>
                    <span class="fs-2 fw-bold text-primary my-2">{{ $jumlahMahasiswa }}</span>
                    <small class="text-muted">Aktif Terdaftar</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-graph-up text-success fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark mb-0">Rerata IPK Prodi</h5>
                    <span class="fs-2 fw-bold text-success my-2">{{ $avgIpk }}</span>
                    <small class="text-muted">Skala 4.00</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-trophy-fill text-warning fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark mb-0">Prestasi Mahasiswa</h5>
                    <span class="fs-2 fw-bold text-warning my-2">{{ $prestasiCount }}</span>
                    <small class="text-muted">Akademik & Non-Akademik</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center p-4">
                    <i class="bi bi-file-earmark-lock-fill text-info fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark mb-0">Sertifikasi Serkom</h5>
                    <span class="fs-2 fw-bold text-info my-2">{{ $serkomCount }}</span>
                    <small class="text-muted">Keahlian Bersertifikat</small>
                </div>
            </div>
        </div>
    </div>

    <!-- KRITERIA E: DOSEN & SUMBER DAYA -->
    <div class="tab-pane fade" id="tab-c5" role="tabpanel"><h4 class="mb-4 fw-bold">C.5 Akuntabilitas (SDM Dosen)</h4>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-gradient-blue text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-video3 fs-1 me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-white">Dosen Pengampu</h5>
                            <span class="fs-3 fw-bold text-white">{{ $jumlahDosen }} Dosen</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-gradient-orange text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-search fs-1 me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-white">Penelitian & Publikasi</h5>
                            <span class="fs-3 fw-bold text-white">{{ $penelitianCount }} Paper</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-gradient-green text-white p-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-heart-pulse fs-1 me-3"></i>
                        <div>
                            <h5 class="fw-bold mb-0 text-white">Kegiatan PkM Dosen</h5>
                            <span class="fs-3 fw-bold text-white">{{ $pkmCount }} Pengabdian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 text-center p-4">
                    <i class="bi bi-building-check text-primary fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark">Jumlah Kerjasama & PKS</h5>
                    <span class="fs-1 fw-bold text-primary my-2">{{ $kerjasamaCount }} MoA/IA</span>
                    <p class="text-muted small px-3">Mendukung IKU 3 melalui kolaborasi penelitian bersama industri, pertukaran mahasiswa, dan pengabdian masyarakat.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 text-center p-4">
                    <i class="bi bi-person-workspace text-success fs-1 mb-2"></i>
                    <h5 class="fw-bold text-dark">Dosen Praktisi Industri</h5>
                    <span class="fs-1 fw-bold text-success my-2">{{ $praktisiCount }} Pengajar</span>
                    <p class="text-muted small px-3">Mendukung IKU 4 & 6. Mengintegrasikan tenaga ahli, profesional, dan direktur industri langsung ke ruang kelas pembelajaran.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->
    <h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks Pemantauan PPEPP & LED Program Studi</h5>
                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                    <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Matriks di bawah ini membantu Kaprodi memantau ketersediaan bukti fisik dokumen akreditasi 9 Kriteria BAN-PT/LAM-INFOKOM berdasarkan siklus <strong>PPEPP</strong> (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan). Data dihitung dan direkap secara otomatis dari sistem prodi.</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-start" style="font-size: 0.85rem; min-width: 1000px;">
                        <thead class="table-dark text-center">
                            <tr>
                                <th style="width: 15%;" rowspan="2">Kriteria Akreditasi</th>
                                <th colspan="5">Siklus Penjaminan Mutu Internal (PPEPP)</th>
                            </tr>
                            <tr>
                                <th style="width: 17%;">P (Penetapan)</th>
                                <th style="width: 17%;">P (Pelaksanaan)</th>
                                <th style="width: 17%;">E (Evaluasi)</th>
                                <th style="width: 17%;">P (Pengendalian)</th>
                                <th style="width: 17%;">P (Peningkatan)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kriteriaList = [
                                    'C1' => 'C1: Visi, Misi, Tujuan & Strategi',
                                    'C2' => 'C2: Tata Pamong & Kerjasama',
                                    'C3' => 'C3: Mahasiswa / Kriteria D',
                                    'C4' => 'C4: Sumber Daya Manusia (SDM) / Kriteria E',
                                    'C5' => 'C5: Keuangan & Sarpras',
                                    'C6' => 'C6: Pendidikan & Kurikulum / Kriteria A, B, C',
                                    'C7' => 'C7: Penelitian Dosen',
                                    'C8' => 'C8: Pengabdian Masyarakat (PkM)',
                                    'C9' => 'C9: Luaran & Capaian Tridharma / Kriteria A'
                                ];

                                $ppeppList = [
                                    'P1' => 'Penetapan',
                                    'P2' => 'Pelaksanaan',
                                    'P3' => 'Evaluasi',
                                    'P4' => 'Pengendalian',
                                    'P5' => 'Peningkatan'
                                ];
                            @endphp

                            @foreach($kriteriaList as $kCode => $kName)
                            <tr>
                                <td class="fw-bold bg-light">
                                    {{ $kName }}
                                </td>
                                
                                @foreach($ppeppList as $pCode => $pName)
                                <td>
                                    <!-- SYSTEM AUTO DATA / REKAP OTOMATIS -->
                                    @if($kCode === 'C1' && $pCode === 'P3')
                                        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-chat-square-heart me-1"></i>{{ $surveiKepuasanCount }} Survei Kepuasan</span>
                                            <div>
                                                <a href="{{ route('survei-kepuasan.index') }}" class="badge bg-info text-decoration-none me-1">Lihat</a>
                                                <a href="{{ route('obe.pdf-recap', ['kriteria' => 'Survei', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
                                            </div>
                                        </div>
                                    @elseif($kCode === 'C2' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-link-45deg me-1"></i>Live: {{ $kerjasamaCount }} MoU</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-file-earmark-text me-1"></i>{{ $pksCount }} PKS & IA</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C2', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C3' && $pCode === 'P1')
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-graph-up-arrow me-1"></i>Tren PMB: {{ $pmbCount }} Thn</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P1']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C3' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-people-fill me-1"></i>Live: {{ $jumlahMahasiswa }} Mhs</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-dark border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-table me-1"></i>Matriks Kohort</span>
                                            <div>
                                                <a href="{{ route('kohort.index') }}" class="badge bg-dark text-decoration-none me-1">Lihat</a>
                                                <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'Kohort']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
                                            </div>
                                        </div>
                                    @elseif($kCode === 'C3' && $pCode === 'P3')
                                        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
                                            <span><i class="bi bi-graph-up me-1"></i>Rerata IPK: {{ $avgIpk }}</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C3' && $pCode === 'P4')
                                        <div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-calendar-event me-1"></i>{{ $kegiatanProdiCount }} Kegiatan Prodi</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P4']) }}" target="_blank" class="badge bg-secondary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-people me-1"></i>{{ $pesertaMahasiswaCount }} Partisipasi Mhs</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P4_Peserta']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C3' && $pCode === 'P5')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-journal-check me-1"></i>{{ $organisasiCount }} Org. Mahasiswa</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C3', 'ppepp' => 'P5']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C4' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-person-video3 me-1"></i>Live: {{ $jumlahDosen }} Dosen</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-award me-1"></i>{{ $prestasiDosenCount }} Prestasi Dosen</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-hand-thumbs-up me-1"></i>{{ $rekognisiDosenCount }} Rekognisi</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-shield-check me-1"></i>{{ $sertifikasiDosenCount }} Sertifikat</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-secondary text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C4' && $pCode === 'P3')
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-mortarboard me-1"></i>{{ $kegiatanDosenCount }} Keg. SDM Dosen</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C4' && $pCode === 'P4')
                                        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-person-badge me-1"></i>{{ $pesertaDosenCount }} Partisipasi Dosen</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C4', 'ppepp' => 'P4_Peserta']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C5' && $pCode === 'P2')
                                        @php
                                            $totalDanaKeuangan = \App\Models\KeuanganProdi::sum('dana_pendidikan') + \App\Models\KeuanganProdi::sum('dana_penelitian') + \App\Models\KeuanganProdi::sum('dana_pkm') + \App\Models\KeuanganProdi::sum('dana_investasi');
                                            $totalDanaFormat = $totalDanaKeuangan >= 1000000000 ? 'Rp ' . number_format($totalDanaKeuangan/1000000000, 1) . 'M' : ($totalDanaKeuangan >= 1000000 ? 'Rp ' . number_format($totalDanaKeuangan/1000000, 0) . ' Jt' : 'Rp 0');
                                        @endphp
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px; font-size: 0.75rem;">
                                            <span><i class="bi bi-wallet2 me-1"></i>Dana: {{ $totalDanaFormat }}</span>
                                            <div>
                                                <a href="{{ route('keuangan-prodi.index') }}" class="badge bg-success text-decoration-none me-1">Lihat</a>
                                                <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C5', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
                                            </div>
                                        </div>
                                    @elseif($kCode === 'C6' && $pCode === 'P1')
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-file-pdf me-1"></i>Buku Kurikulum</span>
                                            <a href="/buku kurikulum.pdf" target="_blank" class="badge bg-success text-decoration-none">Buka</a>
                                        </div>
                                    @elseif($kCode === 'C6' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-file-earmark-check me-1"></i>Live: {{ $totalRps }} RPS</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C6' && $pCode === 'P3')
                                        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
                                            <span><i class="bi bi-graph-up me-1"></i>Evaluasi CPL</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C6' && $pCode === 'P5')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-arrow-repeat me-1"></i>Live: {{ $cqiLogs->count() }} Logs</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'P5']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C7' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-search me-1"></i>{{ $penelitianCount }} Penelitian</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C7', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
                                            <span><i class="bi bi-cash-coin me-1"></i>{{ $hibahCount }} Hibah Penelitian</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C7', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPenelitianCount }} Integrasi RPS</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'IntegrasiPenelitian']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C8' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-heart-pulse me-1"></i>{{ $pkmCount }} PkM Dosen</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C8', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-people me-1"></i>{{ $praktisiCount }} Praktisi/Dosen Tamu</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C8', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPkmCount }} Integrasi RPS</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C6', 'ppepp' => 'IntegrasiPkm']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C9' && $pCode === 'P2')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-award me-1"></i>Live: {{ $serkomCount }} Serkom</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C9', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
                                            <span><i class="bi bi-trophy me-1"></i>Live: {{ $prestasiCount }} Prestasi Mhs</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C9', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
                                        </div>
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-lightbulb me-1"></i>{{ $hkiCount }} HKI</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C9', 'ppepp' => 'P2']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C9' && $pCode === 'P3')
                                        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
                                            <span><i class="bi bi-journal-code me-1"></i>{{ $capstoneCount }} Capstone/TA</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C9', 'ppepp' => 'P3']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C9' && $pCode === 'P4')
                                        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
                                            <span><i class="bi bi-briefcase me-1"></i>Tracer Study Alumni</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C9', 'ppepp' => 'P4_Tracer']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">PDF</a>
                                        </div>
                                    @elseif($kCode === 'C9' && $pCode === 'P5')
                                        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                            <span><i class="bi bi-folder2-open me-1"></i>{{ $tugasMahasiswaCount }} Tugas Mhs</span>
                                            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'C9', 'ppepp' => 'P5']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
                                        </div>
                                    @endif

                                    <!-- UPLOADED PHYSICAL DOCUMENTS -->
                                    @php
                                        $cellKey = $kCode . '_' . $pCode;
                                        $docsInCell = $ppeppDocs->get($cellKey) ?? collect();
                                    @endphp

                                    @foreach($docsInCell as $d)
                                    <div class="bg-light border rounded p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
                                        <span class="text-truncate me-1 text-dark" style="max-width: 100px;" title="{{ $d->nama_dokumen }}">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i> {{ $d->nama_dokumen }}
                                        </span>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('obe.view-ppepp', $d->id) }}" target="_blank" class="btn btn-link p-0 text-info me-1" title="Lihat"><i class="bi bi-eye" style="font-size: 0.8rem;"></i></a>
                                            <a href="{{ route('obe.download-ppepp', $d->id) }}" class="btn btn-link p-0 text-success me-1" title="Download"><i class="bi bi-download" style="font-size: 0.8rem;"></i></a>
                                            <form action="{{ route('obe.delete-ppepp', $d->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 text-danger" onclick="return confirm('Hapus dokumen ini?')" title="Hapus"><i class="bi bi-trash" style="font-size: 0.8rem;"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach

                                    <!-- QUICK UPLOAD TRIGGER -->
                                    <button type="button" class="btn btn-xs btn-outline-secondary w-100 py-0 border-dashed" style="border-style: dashed; font-size: 0.75rem;" onclick="openQuickUpload('{{ $kCode }}', '{{ $pCode }}', '{{ $kName }}', '{{ $pName }}')">
                                        <i class="bi bi-plus"></i> Tambah Dokumen
                                    </button>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Input Survei Stakeholder -->
<div class="modal fade" id="modalSurvey" tabindex="-1" aria-labelledby="modalSurveyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalSurveyLabel"><i class="bi bi-chat-heart text-warning me-2"></i>Input Data Kepuasan Stakeholder</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('obe.store-survey') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tahun Survei <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Responden <span class="text-danger">*</span></label>
                            <input type="number" name="responden_count" class="form-control" placeholder="Contoh: 30" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Aspek Penilaian CPL <span class="text-danger">*</span></label>
                        <select name="aspek_penilaian" class="form-select" required>
                            <option value="">-- Pilih Aspek --</option>
                            <option value="Etika & Moral">Etika & Moral</option>
                            <option value="Keahlian Bidang Ilmu (Kompetensi Utama)">Keahlian Bidang Ilmu (Kompetensi Utama)</option>
                            <option value="Kemampuan Bahasa Asing">Kemampuan Bahasa Asing</option>
                            <option value="Penggunaan Teknologi Informasi">Penggunaan Teknologi Informasi</option>
                            <option value="Kemampuan Komunikasi">Kemampuan Komunikasi</option>
                            <option value="Kerjasama Tim (Teamwork)">Kerjasama Tim (Teamwork)</option>
                            <option value="Pengembangan Diri & Kemandirian">Pengembangan Diri & Kemandirian</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-success small">Sangat Baik (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_sangat_baik" class="form-control" placeholder="0-100" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-primary small">Baik (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_baik" class="form-control" placeholder="0-100" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-warning small">Cukup (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_cukup" class="form-control" placeholder="0-100" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-danger small">Kurang (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="nilai_kurang" class="form-control" placeholder="0-100" required>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Total akumulasi persentase harus mendekati 100%.</p>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>Simpan Survei</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Empty Tabs -->
    <div class="tab-pane fade" id="tab-a" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-globe me-2 text-warning"></i>Kriteria A: Kondisi Eksternal</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Lingkungan makro dan mikro yang memengaruhi keberadaan dan pengembangan program studi (Misal: aspek politik, ekonomi, kebijakan, sosial, budaya, dll).</p>
                <form action="{{ route('obe.save-narrative') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="A">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Kondisi Eksternal di sini...">{{ $narratives['A'] ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-b" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-warning"></i>Kriteria B: Profil UPPS & Program Studi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Sejarah, visi, misi, tujuan, sasaran, dan struktur organisasi Unit Pengelola Program Studi (UPPS) serta Program Studi.</p>
                <form action="{{ route('obe.save-narrative') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="B">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Profil UPPS di sini...">{{ $narratives['B'] ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-c3" role="tabpanel">
        <h4 class="fw-bold">C.3 Relevansi Penelitian</h4>
        <p class="text-muted">Data integrasi penelitian akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-c4" role="tabpanel">
        <h4 class="fw-bold">C.4 Relevansi PkM</h4>
        <p class="text-muted">Data integrasi pengabdian masyarakat akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-c6" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-stars me-2 text-warning"></i>Kriteria C.6: Diferensiasi Misi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Keunggulan spesifik dan kekhasan/diferensiasi misi program studi dibandingkan program studi sejenis lainnya (Visi Keilmuan).</p>
                <form action="{{ route('obe.save-narrative') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="C6">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Diferensiasi Misi di sini...">{{ $narratives['C6'] ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-d" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-square me-2 text-warning"></i>Kriteria D: Suplemen</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Informasi tambahan, lampiran penting, atau suplemen spesifik yang disyaratkan oleh instrumen akreditasi prodi.</p>
                <form action="{{ route('obe.save-narrative') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="D">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Suplemen di sini...">{{ $narratives['D'] ?? '' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
                            <option value="C1">C1: Budaya Mutu</option>
                            <option value="C2">C2: Relevansi Pendidikan</option>
                            <option value="C3">C3: Relevansi Penelitian</option>
                            <option value="C4">C4: Relevansi Pengabdian Kepada Masyarakat</option>
                            <option value="C5">C5: Akuntabilitas</option>
                            <option value="C6">C6: Diferensiasi Misi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Tahap PPEPP <span class="text-danger">*</span></label>
                        <select name="ppepp" id="ppepp_select_stage" class="form-select" required>
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
