@extends('layouts.app')

@section('title', 'Detail Mahasiswa & HKI')

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Custom style for student tabs */
    #mahasiswaTabs .nav-link.active {
        background: linear-gradient(135deg, #0f172a, #1e293b) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
    }
    #mahasiswaTabs .nav-link {
        color: #475569;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    #mahasiswaTabs .nav-link:hover:not(.active) {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 fw-bold text-dark">Detail Mahasiswa & HKI</h1>
        <p class="text-muted mb-0">Kelola data pribadi dan Hak Kekayaan Intelektual (HKI) mahasiswa</p>
    </div>
    <div>
        <a href="{{ route('mahasiswa.cetak-profil', $mahasiswa) }}" target="_blank" class="btn btn-info me-1 text-white">
            <i class="bi bi-printer-fill me-1"></i>Cetak Profil
        </a>
        <a href="{{ route('mahasiswa.card', $mahasiswa) }}" class="btn btn-primary me-1">
            <i class="bi bi-credit-card-2-front me-1"></i>Cetak Kartu
        </a>
        <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning me-1">
            <i class="bi bi-pencil-square me-1"></i>Edit Mahasiswa
        </a>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger shadow-sm border-0 border-start border-danger border-4 mb-4">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success shadow-sm border-0 border-start border-success border-4 mb-4">
        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
    </div>
@endif

<div class="row">
    <!-- Kiri: Data Diri Mahasiswa -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-fill me-2 text-info"></i>Profil Mahasiswa</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <tr>
                        <th class="ps-3 py-3 text-muted" style="width: 35%;">NIM</th>
                        <td class="pe-3 py-3 fw-bold text-dark">{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3 py-3 text-muted">Nama</th>
                        <td class="pe-3 py-3 fw-semibold text-dark">{{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3 py-3 text-muted">Kelas</th>
                        <td class="pe-3 py-3">
                            <span class="badge bg-primary px-3 py-1.5" style="border-radius: 6px; font-size: 0.8rem;">{{ $mahasiswa->kelas }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3 py-3 text-muted">Terdaftar</th>
                        <td class="pe-3 py-3 text-muted small">{{ $mahasiswa->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="ps-3 py-3 text-muted">Pembaruan</th>
                        <td class="pe-3 py-3 text-muted small">{{ $mahasiswa->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Statistik Mahasiswa Card -->
        <div class="card shadow-sm border-0 mt-4 d-print-none">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2 text-warning"></i>Statistik Mahasiswa</h5>
            </div>
            <div class="card-body bg-light">
                <!-- IPK Terbaru (Highlight Card) -->
                @php
                    $latestIpk = $ipkList->first();
                @endphp
                <div class="text-center py-3 bg-white rounded border border-light mb-4 shadow-sm">
                    <span class="text-muted small d-block mb-1">IPK Terakhir</span>
                    <h2 class="fw-bold text-primary mb-0">
                        {{ $latestIpk ? number_format($latestIpk->ipk, 2) : '-' }}
                    </h2>
                    @if($latestIpk && $latestIpk->ts)
                        <small class="text-muted">TA: {{ $latestIpk->ts->tahun_sekarang }}</small>
                    @endif
                </div>

                <!-- Detail List Statistik -->
                <div class="d-flex flex-column gap-2">
                    <!-- HKI -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-warning text-dark rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-award-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Total HKI</span>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $mahasiswa->hki->count() }}
                        </span>
                    </div>

                    <!-- Prestasi -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-danger text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-trophy-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Total Prestasi</span>
                        </div>
                        <span class="badge bg-danger rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $mahasiswa->prestasi->count() }}
                        </span>
                    </div>

                    <!-- Sertifikasi -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-success text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-patch-check-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Sertifikasi</span>
                        </div>
                        <span class="badge bg-success rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $sertifikasiList->count() }}
                        </span>
                    </div>

                    <!-- Organisasi -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-primary text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-diagram-3-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Organisasi</span>
                        </div>
                        <span class="badge bg-primary rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $mahasiswa->organisasi->count() }}
                        </span>
                    </div>

                    <!-- Tugas -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-secondary text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-journal-check"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Tugas Mandiri/Kelompok</span>
                        </div>
                        <span class="badge bg-secondary rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $tugasList->count() }}
                        </span>
                    </div>

                    <!-- Capstone -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-dark text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-cpu-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Proyek Capstone</span>
                        </div>
                        <span class="badge bg-dark rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $capstoneList->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Daftar HKI Mahasiswa -->
    <div class="col-lg-8 mb-4">
        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-3 bg-white p-2 rounded shadow-sm gap-2 d-flex flex-wrap border d-print-none" id="mahasiswaTabs" role="tablist" style="border-radius: 12px !important;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold px-3 py-2" id="hki-tab" data-bs-toggle="tab" data-bs-target="#hki" type="button" role="tab" aria-controls="hki" aria-selected="true" style="border-radius: 8px;">
                    <i class="bi bi-award-fill me-1 text-warning"></i> HKI
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2" id="prestasi-tab" data-bs-toggle="tab" data-bs-target="#prestasi" type="button" role="tab" aria-controls="prestasi" aria-selected="false" style="border-radius: 8px;">
                    <i class="bi bi-trophy-fill me-1 text-warning"></i> Prestasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2" id="sertifikasi-tab" data-bs-toggle="tab" data-bs-target="#sertifikasi" type="button" role="tab" aria-controls="sertifikasi" aria-selected="false" style="border-radius: 8px;">
                    <i class="bi bi-patch-check-fill me-1 text-success"></i> Sertifikasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2" id="ipk-tab" data-bs-toggle="tab" data-bs-target="#ipk" type="button" role="tab" aria-controls="ipk" aria-selected="false" style="border-radius: 8px;">
                    <i class="bi bi-graph-up-arrow me-1 text-info"></i> IPK
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2" id="organisasi-tab" data-bs-toggle="tab" data-bs-target="#organisasi" type="button" role="tab" aria-controls="organisasi" aria-selected="false" style="border-radius: 8px;">
                    <i class="bi bi-diagram-3-fill me-1 text-primary"></i> Organisasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas" type="button" role="tab" aria-controls="tugas" aria-selected="false" style="border-radius: 8px;">
                    <i class="bi bi-journal-check me-1 text-success"></i> Tugas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-3 py-2" id="capstone-tab" data-bs-toggle="tab" data-bs-target="#capstone" type="button" role="tab" aria-controls="capstone" aria-selected="false" style="border-radius: 8px;">
                    <i class="bi bi-cpu-fill me-1 text-danger"></i> Capstone
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="mahasiswaTabContent">
            <!-- Tab HKI -->
            <div class="tab-pane fade show active animate-fade-in" id="hki" role="tabpanel" aria-labelledby="hki-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-award-fill me-2 text-warning"></i>Data Hak Kekayaan Intelektual (HKI)</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHkiModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah HKI
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th style="width: 25%;">No & Tgl Permohonan</th>
                                        <th style="width: 20%;">Jenis Ciptaan</th>
                                        <th style="width: 30%;">Judul Ciptaan</th>
                                        <th style="width: 20%;">Dosen Pengusul</th>
                                        <th class="text-center" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mahasiswa->hki as $hki)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $hki->no_permohonan }}</div>
                                                <div class="text-muted small"><i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($hki->tgl_permohonan)->format('d M Y') }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary px-2.5 py-1.5" style="border-radius: 6px; font-size: 0.75rem;">{{ $hki->jenis_ciptaan }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $hki->judul_ciptaan }}</div>
                                                @if($hki->link_dokumen)
                                                    <a href="{{ $hki->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none mt-1" style="border-radius: 6px;">
                                                        <i class="bi bi-link-45deg"></i> Lihat Dokumen
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $hki->nama_dosen }}</div>
                                                <div class="text-muted small">Kode: {{ $hki->kode_dosen }}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editHkiModal"
                                                        data-id="{{ $hki->id }}"
                                                        data-no_permohonan="{{ $hki->no_permohonan }}"
                                                        data-tgl_permohonan="{{ $hki->tgl_permohonan }}"
                                                        data-jenis_ciptaan="{{ $hki->jenis_ciptaan }}"
                                                        data-judul_ciptaan="{{ $hki->judul_ciptaan }}"
                                                        data-kode_dosen="{{ $hki->kode_dosen }}"
                                                        data-nama_dosen="{{ $hki->nama_dosen }}"
                                                        data-link_dokumen="{{ $hki->link_dokumen }}"
                                                        title="Edit HKI">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('hki.destroy', $hki->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data HKI {{ $hki->judul_ciptaan }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus HKI"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-award display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data HKI untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Prestasi -->
            <div class="tab-pane fade animate-fade-in" id="prestasi" role="tabpanel" aria-labelledby="prestasi-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-trophy-fill me-2 text-warning"></i>Data Prestasi Mahasiswa</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPrestasiModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Prestasi
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th style="width: 25%;">Nama Prestasi</th>
                                        <th style="width: 15%;">Tahun & TA</th>
                                        <th style="width: 25%;">Penyelenggara</th>
                                        <th style="width: 20%;">Tingkat</th>
                                        <th class="text-center" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mahasiswa->prestasi as $prestasi)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $prestasi->nama_prestasi }}</div>
                                                <div class="text-muted small">
                                                    Bidang: <span class="fw-semibold text-secondary me-2">{{ $prestasi->bidang_prestasi }}</span>
                                                    Hasil: <span class="fw-semibold text-success">{{ $prestasi->prestasi_diraih }}</span>
                                                </div>
                                                @if($prestasi->link_dokumen)
                                                    <a href="{{ $prestasi->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none mt-1" style="border-radius: 6px;">
                                                        <i class="bi bi-link-45deg"></i> Lihat Bukti
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $prestasi->tahun }}</div>
                                                <div class="text-muted small">TA: {{ $prestasi->ts ? $prestasi->ts->tahun_sekarang : 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <div class="text-dark fw-semibold">{{ $prestasi->penyelenggara }}</div>
                                            </td>
                                            <td>
                                                @php
                                                    $badgeColor = 'bg-secondary';
                                                    if($prestasi->level_prestasi == 'Internasional') $badgeColor = 'bg-danger';
                                                    elseif($prestasi->level_prestasi == 'Nasional') $badgeColor = 'bg-warning text-dark';
                                                    elseif($prestasi->level_prestasi == 'Wilayah') $badgeColor = 'bg-info text-dark';
                                                    elseif($prestasi->level_prestasi == 'Lokal') $badgeColor = 'bg-success';
                                                @endphp
                                                <span class="badge {{ $badgeColor }} px-2.5 py-1.5" style="border-radius: 6px; font-size: 0.75rem;">{{ $prestasi->level_prestasi }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editPrestasiModal"
                                                        data-id="{{ $prestasi->id }}"
                                                        data-nama_prestasi="{{ $prestasi->nama_prestasi }}"
                                                        data-tahun="{{ $prestasi->tahun }}"
                                                        data-ts_id="{{ $prestasi->ts_id }}"
                                                        data-penyelenggara="{{ $prestasi->penyelenggara }}"
                                                        data-bidang_prestasi="{{ $prestasi->bidang_prestasi }}"
                                                        data-prestasi_diraih="{{ $prestasi->prestasi_diraih }}"
                                                        data-level_prestasi="{{ $prestasi->level_prestasi }}"
                                                        data-link_dokumen="{{ $prestasi->link_dokumen }}"
                                                        title="Edit Prestasi">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('prestasi-mahasiswa.destroy', $prestasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus prestasi {{ $prestasi->nama_prestasi }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Prestasi"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-trophy display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data prestasi untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Sertifikasi -->
            <div class="tab-pane fade animate-fade-in" id="sertifikasi" role="tabpanel" aria-labelledby="sertifikasi-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-patch-check-fill me-2 text-success"></i>Sertifikasi Kompetensi</h5>
                        <a href="{{ route('sertifikasi-mahasiswa.index') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Kelola Sertifikasi
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th style="width: 65%;">Skema Sertifikasi Kompetensi</th>
                                        <th style="width: 30%;">Bukti Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sertifikasiList as $serkom)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $serkom->skema_serkom }}</div>
                                                <div class="text-muted small">NIM: {{ $serkom->nim }} - {{ $serkom->nama_mhs }}</div>
                                            </td>
                                            <td>
                                                @if($serkom->link_dokumen)
                                                    <a href="{{ $serkom->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                        <i class="bi bi-link-45deg"></i> Lihat Sertifikat
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="bi bi-patch-check display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data sertifikasi kompetensi untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab IPK -->
            <div class="tab-pane fade animate-fade-in" id="ipk" role="tabpanel" aria-labelledby="ipk-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Indeks Prestasi Kumulatif (IPK)</h5>
                        <a href="{{ route('ipk.index') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Kelola IPK
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 10%;">No</th>
                                        <th style="width: 45%;">Tahun Akademik (TA)</th>
                                        <th class="text-end" style="width: 45%;">IPK Mahasiswa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ipkList as $ipk)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $ipk->ts ? $ipk->ts->tahun_sekarang : 'N/A' }}</div>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                {{ number_format($ipk->ipk, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="bi bi-graph-up display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data IPK untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Organisasi -->
            <div class="tab-pane fade animate-fade-in" id="organisasi" role="tabpanel" aria-labelledby="organisasi-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-diagram-3-fill me-2 text-warning"></i>Data Organisasi Mahasiswa</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahOrganisasiModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Organisasi
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th style="width: 30%;">Nama Organisasi</th>
                                        <th style="width: 20%;">Jabatan</th>
                                        <th style="width: 15%;">Periode</th>
                                        <th style="width: 20%;">Tahun Akademik (TA)</th>
                                        <th class="text-center" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mahasiswa->organisasi as $org)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $org->nama_organisasi }}</div>
                                                @if($org->link_sk)
                                                    <a href="{{ $org->link_sk }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none mt-1" style="border-radius: 6px;">
                                                        <i class="bi bi-file-earmark-pdf"></i> Lihat SK
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1.5" style="border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
                                                    {{ $org->jabatan }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $org->periode }}</div>
                                            </td>
                                            <td>
                                                <div class="text-dark">{{ $org->ts ? $org->ts->tahun_sekarang : 'N/A' }}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editOrganisasiModal"
                                                        data-id="{{ $org->id }}"
                                                        data-nama_organisasi="{{ $org->nama_organisasi }}"
                                                        data-jabatan="{{ $org->jabatan }}"
                                                        data-periode="{{ $org->periode }}"
                                                        data-ts_id="{{ $org->ts_id }}"
                                                        data-link_sk="{{ $org->link_sk }}"
                                                        title="Edit Organisasi">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('organisasi-mahasiswa.destroy', $org->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data organisasi {{ $org->nama_organisasi }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Organisasi"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-diagram-3 display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data organisasi untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Tugas -->
            <div class="tab-pane fade animate-fade-in" id="tugas" role="tabpanel" aria-labelledby="tugas-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-journal-check me-2 text-warning"></i>Data Tugas Mahasiswa</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTugasModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Tugas
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th style="width: 25%;">NIM</th>
                                        <th style="width: 25%;">Nama Mahasiswa</th>
                                        <th style="width: 25%;">Nama Matakuliah</th>
                                        <th style="width: 15%;">Link Dokumen</th>
                                        <th class="text-center" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @forelse ($tugasList as $tugas)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div>{{ $tugas->nim }}</div>
                                                @if($tugas->nim !== $mahasiswa->nim)
                                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Ketua: {{ $tugas->mahasiswa->nama ?? $tugas->nama_mahasiswa }}</span>
                                                @else
                                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Ketua Kelompok</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $tugas->nama_mahasiswa }}</div>
                                                @if($tugas->anggota_kelompok)
                                                    @php
                                                        $memberNims = array_map('trim', explode(',', $tugas->anggota_kelompok));
                                                        $memberNames = \App\Models\Mahasiswa::whereIn('nim', $memberNims)->pluck('nama')->toArray();
                                                    @endphp
                                                    @if(!empty($memberNames))
                                                        <div class="small text-muted mt-1" style="font-size: 11px;">
                                                            <strong>Anggota:</strong> {{ implode(', ', $memberNames) }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-secondary">{{ $tugas->matakuliah->nama_matakuliah ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($tugas->link_dokumen)
                                                    <a href="{{ $tugas->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-2 px-3" style="border-radius: 6px;">
                                                        <i class="bi bi-link-45deg"></i> Lihat Bukti
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger py-2 px-3" style="border-radius: 6px; font-weight: 600;">
                                                        <i class="bi bi-x-circle me-1"></i> Belum Ada
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($tugas->nim === $mahasiswa->nim)
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editTugasModal"
                                                        data-id="{{ $tugas->id }}"
                                                        data-nim="{{ $tugas->nim }}"
                                                        data-nama_mahasiswa="{{ $tugas->nama_mahasiswa }}"
                                                        data-matakuliah_id="{{ $tugas->matakuliah_id }}"
                                                        data-link_dokumen="{{ $tugas->link_dokumen }}"
                                                        data-anggota_kelompok="{{ $tugas->anggota_kelompok }}"
                                                        title="Edit Tugas">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('tugas-mahasiswa.destroy', $tugas->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data tugas {{ $tugas->matakuliah->nama_matakuliah ?? '' }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Tugas"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                                @else
                                                    <span class="text-muted small" style="font-size: 11px;">Hanya ketua yang dapat mengedit</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-journal-x display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data tugas untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Capstone -->
            <div class="tab-pane fade animate-fade-in" id="capstone" role="tabpanel" aria-labelledby="capstone-tab">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-cpu me-2 text-warning"></i>Proyek Capstone Mahasiswa</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahCapstoneModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Projek Capstone
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">No</th>
                                        <th style="width: 25%;">NIM</th>
                                        <th style="width: 25%;">Nama Mahasiswa</th>
                                        <th style="width: 25%;">Judul Capstone</th>
                                        <th style="width: 15%;">Link Dokumen</th>
                                        <th class="text-center" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @forelse ($capstoneList as $cap)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div>{{ $cap->nim }}</div>
                                                @if($cap->nim !== $mahasiswa->nim)
                                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">Ketua: {{ $cap->mahasiswa->nama ?? $cap->nama_mahasiswa }}</span>
                                                @else
                                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Ketua Kelompok</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $cap->nama_mahasiswa }}</div>
                                                @if($cap->anggota_kelompok)
                                                    @php
                                                        $memberNims = array_map('trim', explode(',', $cap->anggota_kelompok));
                                                        $memberNames = \App\Models\Mahasiswa::whereIn('nim', $memberNims)->pluck('nama')->toArray();
                                                    @endphp
                                                    @if(!empty($memberNames))
                                                        <div class="small text-muted mt-1" style="font-size: 11px;">
                                                            <strong>Anggota:</strong> {{ implode(', ', $memberNames) }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-secondary d-block mb-1">{{ $cap->judul_capstone }}</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($cap->matakuliah as $mk)
                                                        <span class="badge bg-secondary text-white small" style="font-size: 0.7rem; border-radius: 4px;">{{ $mk->nama_matakuliah }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                @if($cap->link_dokumen)
                                                    <a href="{{ $cap->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-2 px-3" style="border-radius: 6px;">
                                                        <i class="bi bi-link-45deg"></i> Lihat Bukti
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger py-2 px-3" style="border-radius: 6px; font-weight: 600;">
                                                        <i class="bi bi-x-circle me-1"></i> Belum Ada
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($cap->nim === $mahasiswa->nim)
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editCapstoneModal"
                                                        data-id="{{ $cap->id }}"
                                                        data-nim="{{ $cap->nim }}"
                                                        data-nama_mahasiswa="{{ $cap->nama_mahasiswa }}"
                                                        data-judul_capstone="{{ $cap->judul_capstone }}"
                                                        data-link_dokumen="{{ $cap->link_dokumen }}"
                                                        data-matakuliah_ids="{{ json_encode($cap->matakuliah->pluck('id')) }}"
                                                        data-anggota_kelompok="{{ $cap->anggota_kelompok }}"
                                                        title="Edit Capstone">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('capstone-mahasiswa.destroy', $cap->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus proyek capstone {{ $cap->judul_capstone }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Capstone"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                                @else
                                                    <span class="text-muted small" style="font-size: 11px;">Hanya ketua yang dapat mengedit</span>
                                                @endif
                                            </td>
                                        </tr>
                                     @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-cpu-fill display-6 d-block mb-2 text-secondary"></i>
                                                Belum ada data proyek capstone untuk mahasiswa ini.
                                            </td>
                                        </tr>
                                     @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah HKI -->
<div class="modal fade" id="tambahHkiModal" tabindex="-1" aria-labelledby="tambahHkiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hki.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahHkiModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Data HKI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- No Permohonan -->
                    <div class="mb-3">
                        <label for="add_no_permohonan" class="form-label fw-semibold">Nomor Permohonan <span class="text-danger">*</span></label>
                        <input type="text" name="no_permohonan" id="add_no_permohonan" class="form-control" required placeholder="Contoh: EC0020239999">
                    </div>

                    <!-- Tgl Permohonan -->
                    <div class="mb-3">
                        <label for="add_tgl_permohonan" class="form-label fw-semibold">Tanggal Permohonan <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_permohonan" id="add_tgl_permohonan" class="form-control" required>
                    </div>

                    <!-- Jenis Ciptaan -->
                    <div class="mb-3">
                        <label for="add_jenis_ciptaan" class="form-label fw-semibold">Jenis Ciptaan <span class="text-danger">*</span></label>
                        <select name="jenis_ciptaan" id="add_jenis_ciptaan" class="form-select" required>
                            <option value="">-- Pilih Jenis Ciptaan --</option>
                            <option value="Program Komputer">Program Komputer</option>
                            <option value="Buku">Buku</option>
                            <option value="Karya Tulis">Karya Tulis</option>
                            <option value="Jurnal">Jurnal</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Judul Ciptaan -->
                    <div class="mb-3">
                        <label for="add_judul_ciptaan" class="form-label fw-semibold">Judul Ciptaan <span class="text-danger">*</span></label>
                        <textarea name="judul_ciptaan" id="add_judul_ciptaan" rows="2" class="form-control" required placeholder="Masukkan judul ciptaan..."></textarea>
                    </div>

                    <hr class="text-muted">

                    <!-- Pilih Dosen (Auto-fill) -->
                    <div class="mb-3">
                        <label for="add_dosen_select" class="form-label fw-semibold">Pilih Dosen Pengusul <span class="text-muted small">(Auto-fill)</span></label>
                        <select id="add_dosen_select" class="form-select">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosenList as $d)
                                <option value="{{ $d->id }}" data-kode="{{ $d->kode_dosen }}" data-nama="{{ $d->nama_dosen }}">
                                    {{ $d->kode_dosen }} - {{ $d->nama_dosen }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                     <!-- Kode Dosen -->
                     <div class="mb-3">
                         <label for="add_kode_dosen" class="form-label fw-semibold">Kode Dosen <span class="text-muted small">(Gunakan koma jika > 1 dosen)</span></label>
                         <input type="text" name="kode_dosen" id="add_kode_dosen" class="form-control" placeholder="Contoh: D01, D02">
                     </div>

                     <!-- Nama Dosen -->
                     <div class="mb-3">
                         <label for="add_nama_dosen" class="form-label fw-semibold">Nama Dosen <span class="text-muted small">(Gunakan koma jika > 1 dosen)</span></label>
                         <input type="text" name="nama_dosen" id="add_nama_dosen" class="form-control" placeholder="Contoh: Dr. Andi, Dr. Budi">
                     </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="add_link_dokumen" class="form-label fw-semibold">Link Dokumen <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="add_link_dokumen" class="form-control" placeholder="https://example.com/dokumen">
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

<!-- Modal Edit HKI -->
<div class="modal fade" id="editHkiModal" tabindex="-1" aria-labelledby="editHkiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editHkiModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data HKI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- No Permohonan -->
                    <div class="mb-3">
                        <label for="edit_no_permohonan" class="form-label fw-semibold">Nomor Permohonan <span class="text-danger">*</span></label>
                        <input type="text" name="no_permohonan" id="edit_no_permohonan" class="form-control" required placeholder="Contoh: EC0020239999">
                    </div>

                    <!-- Tgl Permohonan -->
                    <div class="mb-3">
                        <label for="edit_tgl_permohonan" class="form-label fw-semibold">Tanggal Permohonan <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_permohonan" id="edit_tgl_permohonan" class="form-control" required>
                    </div>

                    <!-- Jenis Ciptaan -->
                    <div class="mb-3">
                        <label for="edit_jenis_ciptaan" class="form-label fw-semibold">Jenis Ciptaan <span class="text-danger">*</span></label>
                        <select name="jenis_ciptaan" id="edit_jenis_ciptaan" class="form-select" required>
                            <option value="">-- Pilih Jenis Ciptaan --</option>
                            <option value="Program Komputer">Program Komputer</option>
                            <option value="Buku">Buku</option>
                            <option value="Karya Tulis">Karya Tulis</option>
                            <option value="Jurnal">Jurnal</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Judul Ciptaan -->
                    <div class="mb-3">
                        <label for="edit_judul_ciptaan" class="form-label fw-semibold">Judul Ciptaan <span class="text-danger">*</span></label>
                        <textarea name="judul_ciptaan" id="edit_judul_ciptaan" rows="2" class="form-control" required placeholder="Masukkan judul ciptaan..."></textarea>
                    </div>

                    <hr class="text-muted">

                    <!-- Pilih Dosen (Auto-fill) -->
                    <div class="mb-3">
                        <label for="edit_dosen_select" class="form-label fw-semibold">Pilih Dosen Pengusul <span class="text-muted small">(Auto-fill)</span></label>
                        <select id="edit_dosen_select" class="form-select">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosenList as $d)
                                <option value="{{ $d->id }}" data-kode="{{ $d->kode_dosen }}" data-nama="{{ $d->nama_dosen }}">
                                    {{ $d->kode_dosen }} - {{ $d->nama_dosen }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                     <!-- Kode Dosen -->
                     <div class="mb-3">
                         <label for="edit_kode_dosen" class="form-label fw-semibold">Kode Dosen <span class="text-muted small">(Gunakan koma jika > 1 dosen)</span></label>
                         <input type="text" name="kode_dosen" id="edit_kode_dosen" class="form-control" placeholder="Contoh: D01, D02">
                     </div>

                     <!-- Nama Dosen -->
                     <div class="mb-3">
                         <label for="edit_nama_dosen" class="form-label fw-semibold">Nama Dosen <span class="text-muted small">(Gunakan koma jika > 1 dosen)</span></label>
                         <input type="text" name="nama_dosen" id="edit_nama_dosen" class="form-control" placeholder="Contoh: Dr. Andi, Dr. Budi">
                     </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="edit_link_dokumen" class="form-label fw-semibold">Link Dokumen <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="edit_link_dokumen" class="form-control" placeholder="https://example.com/dokumen">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Prestasi -->
<div class="modal fade" id="tambahPrestasiModal" tabindex="-1" aria-labelledby="tambahPrestasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('prestasi-mahasiswa.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahPrestasiModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Prestasi Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Nama Prestasi -->
                    <div class="mb-3">
                        <label for="add_nama_prestasi" class="form-label fw-semibold">Nama Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_prestasi" id="add_nama_prestasi" class="form-control" required placeholder="Contoh: Juara 1 Lomba Web Design Nasional">
                    </div>

                    <!-- Penyelenggara -->
                    <div class="mb-3">
                        <label for="add_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" id="add_penyelenggara" class="form-control" required placeholder="Contoh: Universitas Indonesia">
                    </div>

                    <div class="row">
                        <!-- Tahun -->
                        <div class="col-md-6 mb-3">
                            <label for="add_prestasi_tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="add_prestasi_tahun" class="form-control" required placeholder="Contoh: 2024">
                        </div>
                        
                        <!-- TA (Tahun Akademik) -->
                        <div class="col-md-6 mb-3">
                            <label for="add_prestasi_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="add_prestasi_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Bidang Prestasi -->
                    <div class="mb-3">
                        <label for="add_bidang_prestasi" class="form-label fw-semibold">Bidang Prestasi <span class="text-danger">*</span></label>
                        <select name="bidang_prestasi" id="add_bidang_prestasi" class="form-select" required>
                            <option value="">-- Pilih Bidang --</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Non Akademik">Non Akademik</option>
                            <option value="Akademik Non Lomba">Akademik Non Lomba</option>
                            <option value="Partisipan">Partisipan</option>
                        </select>
                    </div>

                    <!-- Hasil / Prestasi yang Diraih -->
                    <div class="mb-3">
                        <label for="add_prestasi_diraih" class="form-label fw-semibold">Prestasi yang Diraih <span class="text-danger">*</span></label>
                        <select name="prestasi_diraih" id="add_prestasi_diraih" class="form-select" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Juara 1">Juara 1</option>
                            <option value="Juara 2">Juara 2</option>
                            <option value="Juara 3">Juara 3</option>
                            <option value="Harapan 1">Harapan 1</option>
                            <option value="Harapan 2">Harapan 2</option>
                            <option value="Partisipan">Partisipan</option>
                        </select>
                    </div>

                    <!-- Tingkat -->
                    <div class="mb-3">
                        <label for="add_level_prestasi" class="form-label fw-semibold">Tingkat Prestasi <span class="text-danger">*</span></label>
                        <select name="level_prestasi" id="add_level_prestasi" class="form-select" required>
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="Lokal">Lokal</option>
                            <option value="Wilayah">Wilayah</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>

                    <!-- Link Dokumen / Sertifikat -->
                    <div class="mb-3">
                        <label for="add_prestasi_link_dokumen" class="form-label fw-semibold">Link Sertifikat / Bukti <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="add_prestasi_link_dokumen" class="form-control" placeholder="https://example.com/sertifikat">
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

<!-- Modal Edit Prestasi -->
<div class="modal fade" id="editPrestasiModal" tabindex="-1" aria-labelledby="editPrestasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editPrestasiModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Prestasi Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Nama Prestasi -->
                    <div class="mb-3">
                        <label for="edit_nama_prestasi" class="form-label fw-semibold">Nama Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_prestasi" id="edit_nama_prestasi" class="form-control" required>
                    </div>

                    <!-- Penyelenggara -->
                    <div class="mb-3">
                        <label for="edit_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" id="edit_penyelenggara" class="form-control" required>
                    </div>

                    <div class="row">
                        <!-- Tahun -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_prestasi_tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="edit_prestasi_tahun" class="form-control" required>
                        </div>
                        
                        <!-- TA (Tahun Akademik) -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_prestasi_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="edit_prestasi_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Bidang Prestasi -->
                    <div class="mb-3">
                        <label for="edit_bidang_prestasi" class="form-label fw-semibold">Bidang Prestasi <span class="text-danger">*</span></label>
                        <select name="bidang_prestasi" id="edit_bidang_prestasi" class="form-select" required>
                            <option value="">-- Pilih Bidang --</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Non Akademik">Non Akademik</option>
                            <option value="Akademik Non Lomba">Akademik Non Lomba</option>
                            <option value="Partisipan">Partisipan</option>
                        </select>
                    </div>

                    <!-- Hasil / Prestasi yang Diraih -->
                    <div class="mb-3">
                        <label for="edit_prestasi_diraih" class="form-label fw-semibold">Prestasi yang Diraih <span class="text-danger">*</span></label>
                        <select name="prestasi_diraih" id="edit_prestasi_diraih" class="form-select" required>
                            <option value="">-- Pilih Hasil --</option>
                            <option value="Juara 1">Juara 1</option>
                            <option value="Juara 2">Juara 2</option>
                            <option value="Juara 3">Juara 3</option>
                            <option value="Harapan 1">Harapan 1</option>
                            <option value="Harapan 2">Harapan 2</option>
                            <option value="Partisipan">Partisipan</option>
                        </select>
                    </div>

                    <!-- Tingkat -->
                    <div class="mb-3">
                        <label for="edit_level_prestasi" class="form-label fw-semibold">Tingkat Prestasi <span class="text-danger">*</span></label>
                        <select name="level_prestasi" id="edit_level_prestasi" class="form-select" required>
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="Lokal">Lokal</option>
                            <option value="Wilayah">Wilayah</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                    </div>

                    <!-- Link Dokumen / Sertifikat -->
                    <div class="mb-3">
                        <label for="edit_prestasi_link_dokumen" class="form-label fw-semibold">Link Sertifikat / Bukti <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="edit_prestasi_link_dokumen" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Organisasi -->
<div class="modal fade" id="tambahOrganisasiModal" tabindex="-1" aria-labelledby="tambahOrganisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('organisasi-mahasiswa.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahOrganisasiModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Organisasi Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Nama Organisasi -->
                    <div class="mb-3">
                        <label for="add_nama_organisasi" class="form-label fw-semibold">Nama Organisasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_organisasi" id="add_nama_organisasi" class="form-control" required placeholder="Contoh: Himpunan Mahasiswa Informatika">
                    </div>

                    <!-- Jabatan -->
                    <div class="mb-3">
                        <label for="add_jabatan" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="add_jabatan" class="form-control" required placeholder="Contoh: Ketua, Sekretaris, Anggota">
                    </div>

                    <div class="row">
                        <!-- Periode -->
                        <div class="col-md-6 mb-3">
                            <label for="add_periode" class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                            <input type="text" name="periode" id="add_periode" class="form-control" required placeholder="Contoh: 2023-2024">
                        </div>
                        
                        <!-- TA (Tahun Akademik) -->
                        <div class="col-md-6 mb-3">
                            <label for="add_organisasi_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="add_organisasi_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Link SK -->
                    <div class="mb-3">
                        <label for="add_link_sk" class="form-label fw-semibold">Link SK Kepengurusan / Bukti <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_sk" id="add_link_sk" class="form-control" placeholder="https://example.com/sk-organisasi">
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

<!-- Modal Edit Organisasi -->
<div class="modal fade" id="editOrganisasiModal" tabindex="-1" aria-labelledby="editOrganisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editOrganisasiModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Organisasi Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- Nama Organisasi -->
                    <div class="mb-3">
                        <label for="edit_nama_organisasi" class="form-label fw-semibold">Nama Organisasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_organisasi" id="edit_nama_organisasi" class="form-control" required>
                    </div>

                    <!-- Jabatan -->
                    <div class="mb-3">
                        <label for="edit_jabatan" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" id="edit_jabatan" class="form-control" required>
                    </div>

                    <div class="row">
                        <!-- Periode -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_periode" class="form-label fw-semibold">Periode <span class="text-danger">*</span></label>
                            <input type="text" name="periode" id="edit_periode" class="form-control" required>
                        </div>
                        
                        <!-- TA (Tahun Akademik) -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_organisasi_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="edit_organisasi_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Link SK -->
                    <div class="mb-3">
                        <label for="edit_link_sk" class="form-label fw-semibold">Link SK Kepengurusan / Bukti <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_sk" id="edit_link_sk" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Tugas -->
<div class="modal fade" id="tambahTugasModal" tabindex="-1" aria-labelledby="tambahTugasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tugas-mahasiswa.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                <input type="hidden" name="nama_mahasiswa" value="{{ $mahasiswa->nama }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahTugasModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Tugas Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- NIM (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM</label>
                        <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
                    </div>

                    <!-- Nama Mahasiswa (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" readonly>
                    </div>

                    <!-- Nama Matakuliah -->
                    <div class="mb-3">
                        <label for="add_tugas_matakuliah_id" class="form-label fw-semibold">Nama Matakuliah <span class="text-danger">*</span></label>
                        <select name="matakuliah_id" id="add_tugas_matakuliah_id" class="form-select" required>
                            <option value="">-- Pilih Matakuliah --</option>
                            @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->id }}">[{{ $mk->kode_matakuliah }}] {{ $mk->nama_matakuliah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="add_tugas_link_dokumen" class="form-label fw-semibold">Link Dokumen Tugas <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="add_tugas_link_dokumen" class="form-control" placeholder="https://example.com/tugas-saya">
                    </div>

                    <!-- Anggota Kelompok (Pilih Teman Sekelas) -->
                    @if($classmates->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota Kelompok <span class="text-muted small">(Pilih Teman Sekelas, Opsional)</span></label>
                        <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto; background: #f8f9fa;">
                            @foreach($classmates as $cm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anggota_nims[]" value="{{ $cm->nim }}" id="add_anggota_{{ $cm->nim }}">
                                    <label class="form-check-label text-dark" for="add_anggota_{{ $cm->nim }}" style="cursor: pointer;">
                                        {{ $cm->nim }} - {{ $cm->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Tugas -->
<div class="modal fade" id="editTugasModal" tabindex="-1" aria-labelledby="editTugasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="nim" id="edit_tugas_nim">
                <input type="hidden" name="nama_mahasiswa" id="edit_tugas_nama_mahasiswa">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editTugasModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Tugas Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- NIM (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM</label>
                        <input type="text" id="edit_tugas_nim_display" class="form-control" readonly>
                    </div>

                    <!-- Nama Mahasiswa (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" id="edit_tugas_nama_mahasiswa_display" class="form-control" readonly>
                    </div>

                    <!-- Nama Matakuliah -->
                    <div class="mb-3">
                        <label for="edit_tugas_matakuliah_id" class="form-label fw-semibold">Nama Matakuliah <span class="text-danger">*</span></label>
                        <select name="matakuliah_id" id="edit_tugas_matakuliah_id" class="form-select" required>
                            <option value="">-- Pilih Matakuliah --</option>
                            @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->id }}">[{{ $mk->kode_matakuliah }}] {{ $mk->nama_matakuliah }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="edit_tugas_link_dokumen" class="form-label fw-semibold">Link Dokumen Tugas <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="edit_tugas_link_dokumen" class="form-control">
                    </div>

                    <!-- Anggota Kelompok (Pilih Teman Sekelas) -->
                    @if($classmates->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota Kelompok <span class="text-muted small">(Pilih Teman Sekelas, Opsional)</span></label>
                        <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto; background: #f8f9fa;">
                            @foreach($classmates as $cm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anggota_nims[]" value="{{ $cm->nim }}" id="edit_anggota_{{ $cm->nim }}">
                                    <label class="form-check-label text-dark" for="edit_anggota_{{ $cm->nim }}" style="cursor: pointer;">
                                        {{ $cm->nim }} - {{ $cm->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Capstone -->
<div class="modal fade" id="tambahCapstoneModal" tabindex="-1" aria-labelledby="tambahCapstoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('capstone-mahasiswa.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nim" value="{{ $mahasiswa->nim }}">
                <input type="hidden" name="nama_mahasiswa" value="{{ $mahasiswa->nama }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahCapstoneModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Proyek Capstone</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- NIM (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM</label>
                        <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
                    </div>

                    <!-- Nama Mahasiswa (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" readonly>
                    </div>

                    <!-- Judul Capstone -->
                    <div class="mb-3">
                        <label for="add_judul_capstone" class="form-label fw-semibold">Judul Projek Capstone <span class="text-danger">*</span></label>
                        <textarea name="judul_capstone" id="add_judul_capstone" rows="3" class="form-control" required placeholder="Masukkan judul proyek capstone..."></textarea>
                    </div>

                    <!-- Matakuliah (Multiple Checkboxes) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Matakuliah Terkait <span class="text-danger">*</span> <span class="text-muted small">(Bisa pilih lebih dari 1)</span></label>
                        <div class="border rounded p-3 bg-light" style="max-height: 180px; overflow-y: auto;">
                            @forelse($matakuliahList as $mk)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="matakuliah_ids[]" value="{{ $mk->id }}" id="add_mk_{{ $mk->id }}">
                                    <label class="form-check-label text-dark" for="add_mk_{{ $mk->id }}" style="font-size: 0.9rem;">
                                        <strong>[{{ $mk->kode_matakuliah }}]</strong> {{ $mk->nama_matakuliah }} ({{ $mk->sks }} SKS)
                                    </label>
                                </div>
                            @empty
                                <div class="text-muted small py-2"><i class="bi bi-info-circle me-1"></i>Belum ada data matakuliah.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="add_capstone_link_dokumen" class="form-label fw-semibold">Link Dokumen / Repository <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="add_capstone_link_dokumen" class="form-control" placeholder="https://github.com/username/capstone-project">
                    </div>

                    <!-- Anggota Kelompok (Pilih Teman Sekelas) -->
                    @if($classmates->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota Kelompok <span class="text-muted small">(Pilih Teman Sekelas, Opsional)</span></label>
                        <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto; background: #f8f9fa;">
                            @foreach($classmates as $cm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anggota_nims[]" value="{{ $cm->nim }}" id="add_capstone_anggota_{{ $cm->nim }}">
                                    <label class="form-check-label text-dark" for="add_capstone_anggota_{{ $cm->nim }}" style="cursor: pointer;">
                                        {{ $cm->nim }} - {{ $cm->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Capstone -->
<div class="modal fade" id="editCapstoneModal" tabindex="-1" aria-labelledby="editCapstoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="nim" id="edit_capstone_nim">
                <input type="hidden" name="nama_mahasiswa" id="edit_capstone_nama_mahasiswa">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editCapstoneModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Proyek Capstone</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <!-- NIM (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM</label>
                        <input type="text" id="edit_capstone_nim_display" class="form-control" readonly>
                    </div>

                    <!-- Nama Mahasiswa (Readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" id="edit_capstone_nama_mahasiswa_display" class="form-control" readonly>
                    </div>

                    <!-- Judul Capstone -->
                    <div class="mb-3">
                        <label for="edit_judul_capstone" class="form-label fw-semibold">Judul Projek Capstone <span class="text-danger">*</span></label>
                        <textarea name="judul_capstone" id="edit_judul_capstone" rows="3" class="form-control" required placeholder="Masukkan judul proyek capstone..."></textarea>
                    </div>

                    <!-- Matakuliah (Multiple Checkboxes) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Matakuliah Terkait <span class="text-danger">*</span> <span class="text-muted small">(Bisa pilih lebih dari 1)</span></label>
                        <div class="border rounded p-3 bg-light" style="max-height: 180px; overflow-y: auto;">
                            @forelse($matakuliahList as $mk)
                                <div class="form-check mb-2">
                                    <input class="form-check-input edit-mk-checkbox" type="checkbox" name="matakuliah_ids[]" value="{{ $mk->id }}" id="edit_mk_{{ $mk->id }}">
                                    <label class="form-check-label text-dark" for="edit_mk_{{ $mk->id }}" style="font-size: 0.9rem;">
                                        <strong>[{{ $mk->kode_matakuliah }}]</strong> {{ $mk->nama_matakuliah }} ({{ $mk->sks }} SKS)
                                    </label>
                                </div>
                            @empty
                                <div class="text-muted small py-2"><i class="bi bi-info-circle me-1"></i>Belum ada data matakuliah.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="edit_capstone_link_dokumen" class="form-label fw-semibold">Link Dokumen / Repository <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_dokumen" id="edit_capstone_link_dokumen" class="form-control">
                    </div>

                    <!-- Anggota Kelompok (Pilih Teman Sekelas) -->
                    @if($classmates->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota Kelompok <span class="text-muted small">(Pilih Teman Sekelas, Opsional)</span></label>
                        <div class="border rounded p-2" style="max-height: 150px; overflow-y: auto; background: #f8f9fa;">
                            @foreach($classmates as $cm)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="anggota_nims[]" value="{{ $cm->nim }}" id="edit_capstone_anggota_{{ $cm->nim }}">
                                    <label class="form-check-label text-dark" for="edit_capstone_anggota_{{ $cm->nim }}" style="cursor: pointer;">
                                        {{ $cm->nim }} - {{ $cm->nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Modal Auto-fill (Supports multiple lecturers by appending)
    var addDosenSelect = document.getElementById('add_dosen_select');
    if (addDosenSelect) {
        addDosenSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value !== "") {
                var kodeInput = document.getElementById('add_kode_dosen');
                var namaInput = document.getElementById('add_nama_dosen');
                
                var newKode = selectedOption.getAttribute('data-kode');
                var newNama = selectedOption.getAttribute('data-nama');
                
                if (kodeInput.value.trim() === "") {
                    kodeInput.value = newKode;
                    namaInput.value = newNama;
                } else {
                    var existingKodes = kodeInput.value.split(',').map(s => s.trim());
                    if (!existingKodes.includes(newKode)) {
                        kodeInput.value = kodeInput.value.trim() + ", " + newKode;
                        namaInput.value = namaInput.value.trim() + ", " + newNama;
                    }
                }
            }
        });
    }

    // Edit Modal Auto-fill (Supports multiple lecturers by appending)
    var editDosenSelect = document.getElementById('edit_dosen_select');
    if (editDosenSelect) {
        editDosenSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value !== "") {
                var kodeInput = document.getElementById('edit_kode_dosen');
                var namaInput = document.getElementById('edit_nama_dosen');
                
                var newKode = selectedOption.getAttribute('data-kode');
                var newNama = selectedOption.getAttribute('data-nama');
                
                if (kodeInput.value.trim() === "") {
                    kodeInput.value = newKode;
                    namaInput.value = newNama;
                } else {
                    var existingKodes = kodeInput.value.split(',').map(s => s.trim());
                    if (!existingKodes.includes(newKode)) {
                        kodeInput.value = kodeInput.value.trim() + ", " + newKode;
                        namaInput.value = namaInput.value.trim() + ", " + newNama;
                    }
                }
            }
        });
    }

    // Edit Modal Population
    var editHkiModal = document.getElementById('editHkiModal');
    if (editHkiModal) {
        editHkiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var noPermohonan = button.getAttribute('data-no_permohonan');
            var tglPermohonan = button.getAttribute('data-tgl_permohonan');
            var jenisCiptaan = button.getAttribute('data-jenis_ciptaan');
            var judulCiptaan = button.getAttribute('data-judul_ciptaan');
            var kodeDosen = button.getAttribute('data-kode_dosen');
            var namaDosen = button.getAttribute('data-nama_dosen');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('hki.store') }}";
            // Replace the last part of route to create the update URL
            action = action.replace('/hki', '/hki/' + id);
            
            var form = editHkiModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_no_permohonan').value = noPermohonan || '';
            document.getElementById('edit_tgl_permohonan').value = tglPermohonan || '';
            document.getElementById('edit_jenis_ciptaan').value = jenisCiptaan || '';
            document.getElementById('edit_judul_ciptaan').value = judulCiptaan || '';
            document.getElementById('edit_kode_dosen').value = kodeDosen || '';
            document.getElementById('edit_nama_dosen').value = namaDosen || '';
            document.getElementById('edit_link_dokumen').value = linkDokumen || '';
            
            // Reset Dosen select dropdown
            document.getElementById('edit_dosen_select').value = '';
        });
    }

    // Edit Prestasi Modal Population
    var editPrestasiModal = document.getElementById('editPrestasiModal');
    if (editPrestasiModal) {
        editPrestasiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var namaPrestasi = button.getAttribute('data-nama_prestasi');
            var tahun = button.getAttribute('data-tahun');
            var tsId = button.getAttribute('data-ts_id');
            var penyelenggara = button.getAttribute('data-penyelenggara');
            var bidangPrestasi = button.getAttribute('data-bidang_prestasi');
            var prestasiDiraih = button.getAttribute('data-prestasi_diraih');
            var levelPrestasi = button.getAttribute('data-level_prestasi');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('prestasi-mahasiswa.store') }}";
            action = action.replace('prestasi-mahasiswa', 'prestasi-mahasiswa/' + id);
            
            var form = editPrestasiModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_nama_prestasi').value = namaPrestasi || '';
            document.getElementById('edit_prestasi_tahun').value = tahun || '';
            document.getElementById('edit_prestasi_ts_id').value = tsId || '';
            document.getElementById('edit_penyelenggara').value = penyelenggara || '';
            document.getElementById('edit_bidang_prestasi').value = bidangPrestasi || '';
            document.getElementById('edit_prestasi_diraih').value = prestasiDiraih || '';
            document.getElementById('edit_level_prestasi').value = levelPrestasi || '';
            document.getElementById('edit_prestasi_link_dokumen').value = linkDokumen || '';
        });
    }

    // Edit Organisasi Modal Population
    var editOrganisasiModal = document.getElementById('editOrganisasiModal');
    if (editOrganisasiModal) {
        editOrganisasiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var namaOrganisasi = button.getAttribute('data-nama_organisasi');
            var jabatan = button.getAttribute('data-jabatan');
            var periode = button.getAttribute('data-periode');
            var tsId = button.getAttribute('data-ts_id');
            var linkSk = button.getAttribute('data-link_sk');
            
            var action = "{{ route('organisasi-mahasiswa.store') }}";
            action = action.replace('organisasi-mahasiswa', 'organisasi-mahasiswa/' + id);
            
            var form = editOrganisasiModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_nama_organisasi').value = namaOrganisasi || '';
            document.getElementById('edit_jabatan').value = jabatan || '';
            document.getElementById('edit_periode').value = periode || '';
            document.getElementById('edit_organisasi_ts_id').value = tsId || '';
            document.getElementById('edit_link_sk').value = linkSk || '';
        });
    }

    // Edit Tugas Modal Population
    var editTugasModal = document.getElementById('editTugasModal');
    if (editTugasModal) {
        editTugasModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var nim = button.getAttribute('data-nim');
            var namaMahasiswa = button.getAttribute('data-nama_mahasiswa');
            var matakuliahId = button.getAttribute('data-matakuliah_id');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            var anggotaKelompok = button.getAttribute('data-anggota_kelompok') || '';
            
            var action = "{{ route('tugas-mahasiswa.store') }}";
            action = action.replace('tugas-mahasiswa', 'tugas-mahasiswa/' + id);
            
            var form = editTugasModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_tugas_nim').value = nim || '';
            document.getElementById('edit_tugas_nim_display').value = nim || '';
            document.getElementById('edit_tugas_nama_mahasiswa').value = namaMahasiswa || '';
            document.getElementById('edit_tugas_nama_mahasiswa_display').value = namaMahasiswa || '';
            document.getElementById('edit_tugas_matakuliah_id').value = matakuliahId || '';
            document.getElementById('edit_tugas_link_dokumen').value = linkDokumen || '';

            // Reset all checkboxes first
            var checkboxes = editTugasModal.querySelectorAll('input[name="anggota_nims[]"]');
            checkboxes.forEach(function(cb) {
                cb.checked = false;
            });

            // Check members
            if (anggotaKelompok) {
                var members = anggotaKelompok.split(',');
                members.forEach(function(memberNim) {
                    var cb = document.getElementById('edit_anggota_' + memberNim.trim());
                    if (cb) {
                        cb.checked = true;
                    }
                });
            }
        });
    }

    // Edit Capstone Modal Population
    var editCapstoneModal = document.getElementById('editCapstoneModal');
    if (editCapstoneModal) {
        editCapstoneModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var nim = button.getAttribute('data-nim');
            var namaMahasiswa = button.getAttribute('data-nama_mahasiswa');
            var judulCapstone = button.getAttribute('data-judul_capstone');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('capstone-mahasiswa.store') }}";
            action = action.replace('capstone-mahasiswa', 'capstone-mahasiswa/' + id);
            
            var form = editCapstoneModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_capstone_nim').value = nim || '';
            document.getElementById('edit_capstone_nim_display').value = nim || '';
            document.getElementById('edit_capstone_nama_mahasiswa').value = namaMahasiswa || '';
            document.getElementById('edit_capstone_nama_mahasiswa_display').value = namaMahasiswa || '';
            document.getElementById('edit_judul_capstone').value = judulCapstone || '';
            document.getElementById('edit_capstone_link_dokumen').value = linkDokumen || '';

            // Reset all checkboxes first
            editCapstoneModal.querySelectorAll('.edit-mk-checkbox').forEach(cb => cb.checked = false);

            var anggotaKelompok = button.getAttribute('data-anggota_kelompok') || '';

            // Reset classmate checkboxes
            var classmatesCheckboxes = editCapstoneModal.querySelectorAll('input[name="anggota_nims[]"]');
            classmatesCheckboxes.forEach(function(cb) {
                cb.checked = false;
            });

            // Populate classmates checkboxes
            if (anggotaKelompok) {
                var members = anggotaKelompok.split(',');
                members.forEach(function(memberNim) {
                    var cb = document.getElementById('edit_capstone_anggota_' + memberNim.trim());
                    if (cb) {
                        cb.checked = true;
                    }
                });
            }

            // Populate checkboxes based on data-matakuliah_ids
            var mkIdsAttr = button.getAttribute('data-matakuliah_ids');
            if (mkIdsAttr) {
                var mkIds = JSON.parse(mkIdsAttr);
                mkIds.forEach(id => {
                    var checkbox = document.getElementById('edit_mk_' + id);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        });
    }
});
</script>
@endsection
