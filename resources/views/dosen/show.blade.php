@extends('layouts.app')

@section('title', 'Detail Dosen')

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Custom style for lecturer tabs */
    #dosenTab .nav-link.active {
        background: linear-gradient(135deg, #0f172a, #1e293b) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
    }
    #dosenTab .nav-link {
        color: #475569;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    #dosenTab .nav-link:hover:not(.active) {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="d-print-none">Detail Dosen</h1>
    <div class="d-print-none">
        <a href="{{ route('dosen.cetak-profil', $dosen) }}" target="_blank" class="btn btn-info text-white me-1"><i class="bi bi-printer-fill"></i> Cetak Profil</a>
        <a href="{{ route('dosen.card', $dosen) }}" class="btn btn-primary me-1"><i class="bi bi-credit-card-2-front"></i> Cetak Kartu</a>
        <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-warning me-1">Edit</a>
        <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Dosen DTPR Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

@if (session('success'))
    <div class="alert alert-success d-print-none shadow-sm border-0 border-start border-success border-4 mb-3">
        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
    </div>
@endif

<!-- Profile Row -->
<div class="row g-3 mb-4">
    <!-- Foto -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary bg-gradient text-white text-center py-2">
                <i class="bi bi-person-circle fs-5 d-block"></i>
                <small class="fw-semibold">Foto Profil</small>
            </div>
            <div class="card-body p-3 text-center">
                @if ($dosen->foto)
                    <img src="{{ asset('storage/' . $dosen->foto) }}" class="img-fluid rounded-circle mb-2 shadow-sm border" style="max-height: 120px; object-fit: cover; width: 120px; height: 120px;" alt="{{ $dosen->nama_dosen }}">
                    <div class="d-flex gap-1 w-100 mt-2 d-print-none">
                        <button type="button" class="btn btn-sm btn-outline-warning w-100" style="border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#updatePhotoModal" title="Edit Foto">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <form action="{{ route('dosen.update-photo', $dosen) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('Yakin ingin menghapus foto profil ini?')">
                            @csrf
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="border-radius: 6px;" title="Hapus Foto">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-light text-secondary rounded-circle mb-2 d-flex align-items-center justify-content-center border mx-auto" style="height: 110px; width: 110px;">
                        <i class="bi bi-person-fill" style="font-size: 3.5rem; opacity: 0.25;"></i>
                    </div>
                    <div class="w-100 d-print-none">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100" style="border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                            <i class="bi bi-camera-fill me-1"></i> Upload
                        </button>
                    </div>
                @endif
                <h6 class="fw-bold text-dark mb-0 mt-2">{{ $dosen->nama_dosen }}</h6>
                <span class="badge bg-secondary">Kode: {{ $dosen->kode_dosen }}</span>
            </div>
        </div>
    </div>

    <!-- Statistik -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success bg-gradient text-white text-center py-2">
                <i class="bi bi-bar-chart-line fs-5 d-block"></i>
                <small class="fw-semibold">Statistik</small>
            </div>
            <div class="card-body p-2 d-flex flex-column justify-content-center h-100">
                <div class="row g-1 text-center">
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #f0f7ff;">
                            <div class="fw-bold text-primary fs-5 lh-1">{{ $statCounts['hki'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">HKI</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #f0faff;">
                            <div class="fw-bold text-info fs-5 lh-1">{{ $statCounts['sertifikasi'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">Sertifikasi</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #fffdf0;">
                            <div class="fw-bold text-warning fs-5 lh-1">{{ $statCounts['kegiatan'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">Kegiatan</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #fff0f0;">
                            <div class="fw-bold text-danger fs-5 lh-1">{{ $statCounts['prestasi'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">Prestasi</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #f0fff4;">
                            <div class="fw-bold text-success fs-5 lh-1">{{ $statCounts['penelitian'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">Penelitian</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #f8f9fa;">
                            <div class="fw-bold text-secondary fs-5 lh-1">{{ $statCounts['pkm'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">PKM</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #f3f0ff;">
                            <div class="fw-bold text-primary fs-5 lh-1">{{ $statCounts['hibah'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">Hibah</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="py-2 px-1 rounded-3" style="background: #f0f5ff;">
                            <div class="fw-bold text-info fs-5 lh-1">{{ $statCounts['rekognisi'] }}</div>
                            <small class="text-muted" style="font-size: 10px;">Rekognisi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Info -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100 border-info border-top border-3">
            <div class="card-header bg-info bg-gradient text-white py-2">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-vcard me-1"></i> Informasi Detail Dosen</h6>
            </div>
            <div class="card-body py-2">
                <div class="row g-1">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Kode Dosen</small>
                            <span class="fw-semibold">{{ $dosen->kode_dosen }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light align-items-center">
                            <small class="text-muted" style="flex-shrink: 0;">Nama Dosen</small>
                            <span class="fw-bold text-end text-wrap ms-2" style="max-width: 70%; word-break: break-word; font-size: 0.85rem;">{{ $dosen->nama_dosen }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light align-items-center">
                            <small class="text-muted" style="flex-shrink: 0;">Homebase</small>
                            <span class="text-end text-wrap ms-2" style="max-width: 70%; word-break: break-word; font-size: 0.85rem;">{{ $dosen->homebase_dosen }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">NIDN</small>
                            <span>{{ $dosen->nidn }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">NUPTK</small>
                            <span>{{ $dosen->nuptk }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">NIP</small>
                            <span>{{ $dosen->nip }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Pendidikan</small>
                            <span>{{ $dosen->pendidikan }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <small class="text-muted">Gelar</small>
                            <span>{{ $dosen->gelar }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">JFA</small>
                            <span>{{ $dosen->jfa }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Kepangkatan</small>
                            <span>{{ $dosen->kepangkatan }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Serdos</small>
                            <span>{{ $dosen->keterangan_serdos }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Jenjang</small>
                            <span>{{ $dosen->jenjang }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Sisfo</small>
                            <span>{{ $dosen->kondisi_sisfo }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">PDDikti</small>
                            <span>{{ $dosen->kondisi_pddikti }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-light">
                            <small class="text-muted">Dibuat</small>
                            <span class="small">{{ $dosen->created_at->format('d-m-Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <small class="text-muted">Diperbarui</small>
                            <span class="small">{{ $dosen->updated_at->format('d-m-Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nav Tabs -->
<ul class="nav nav-pills mb-3 bg-white p-2 rounded shadow-sm gap-2 d-flex flex-wrap border d-print-none" id="dosenTab" role="tablist" style="border-radius: 12px !important;">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold px-3 py-2" id="tab-hki-tab" data-bs-toggle="pill" data-bs-target="#tab-hki" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-award-fill me-1 text-warning"></i> HKI
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-sertifikasi-tab" data-bs-toggle="pill" data-bs-target="#tab-sertifikasi" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-patch-check-fill me-1 text-success"></i> Sertifikasi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-kegiatan-tab" data-bs-toggle="pill" data-bs-target="#tab-kegiatan" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-calendar-event-fill me-1 text-info"></i> Kegiatan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-prestasi-tab" data-bs-toggle="pill" data-bs-target="#tab-prestasi" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-trophy-fill me-1 text-warning"></i> Prestasi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-rekognisi-tab" data-bs-toggle="pill" data-bs-target="#tab-rekognisi" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-person-check-fill me-1 text-info"></i> Rekognisi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-penelitian-tab" data-bs-toggle="pill" data-bs-target="#tab-penelitian" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-journal-text me-1 text-success"></i> Penelitian
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-pkm-tab" data-bs-toggle="pill" data-bs-target="#tab-pkm" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-journal-bookmark-fill me-1 text-secondary"></i> PKM
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-hibah-tab" data-bs-toggle="pill" data-bs-target="#tab-hibah" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-cash-coin me-1 text-primary"></i> Hibah
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-3 py-2" id="tab-organisasi-tab" data-bs-toggle="pill" data-bs-target="#tab-organisasi" type="button" role="tab" style="border-radius: 8px;">
            <i class="bi bi-diagram-3-fill me-1 text-primary"></i> Organisasi
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="dosenTabContent">

    <!-- HKI -->
    <div class="tab-pane fade show active animate-fade-in" id="tab-hki" role="tabpanel">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Karya HKI Bersama Mahasiswa</h6>
                <button type="button" class="btn btn-sm btn-primary d-print-none" data-bs-toggle="modal" data-bs-target="#tambahHkiModal" onclick="setHkiType('kolaborasi')">
                    <i class="bi bi-plus-circle me-1"></i> Tambah HKI Kolaborasi
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 25%;">NIM & Nama Mahasiswa</th>
                                <th style="width: 25%;">No & Tgl Permohonan</th>
                                <th style="width: 15%;">Jenis Ciptaan</th>
                                <th style="width: 20%;">Judul Ciptaan</th>
                                <th class="text-center d-print-none" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hkiBersamaMhs as $hki)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($hki->mahasiswa)
                                            <a href="{{ route('mahasiswa.show', $hki->mahasiswa->id) }}" class="fw-bold text-dark text-decoration-none">{{ $hki->mahasiswa->nama }}</a>
                                            <div class="text-muted small">NIM: {{ $hki->nim }}</div>
                                        @else
                                            <span class="text-dark fw-bold">Mahasiswa Terhapus</span>
                                        @endif
                                    </td>
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
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editHkiModal"
                                                data-id="{{ $hki->id }}"
                                                data-nim="{{ $hki->nim }}"
                                                data-no_permohonan="{{ $hki->no_permohonan }}"
                                                data-tgl_permohonan="{{ $hki->tgl_permohonan }}"
                                                data-jenis_ciptaan="{{ $hki->jenis_ciptaan }}"
                                                data-judul_ciptaan="{{ $hki->judul_ciptaan }}"
                                                data-kode_dosen="{{ $hki->kode_dosen }}"
                                                data-nama_dosen="{{ $hki->nama_dosen }}"
                                                data-link_dokumen="{{ $hki->link_dokumen }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('hki.destroy', $hki->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data HKI ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-award display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada karya HKI bersama mahasiswa untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-fill-check me-2 text-info"></i>Karya HKI Mandiri Dosen (Tanpa Mahasiswa)</h6>
                <button type="button" class="btn btn-sm btn-info text-dark d-print-none" data-bs-toggle="modal" data-bs-target="#tambahHkiModal" onclick="setHkiType('mandiri')">
                    <i class="bi bi-plus-circle me-1"></i> Tambah HKI Mandiri
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
                                <th style="width: 40%;">Judul Ciptaan</th>
                                <th class="text-center d-print-none" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hkiMandiri as $hki)
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
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editHkiModal"
                                                data-id="{{ $hki->id }}"
                                                data-nim=""
                                                data-no_permohonan="{{ $hki->no_permohonan }}"
                                                data-tgl_permohonan="{{ $hki->tgl_permohonan }}"
                                                data-jenis_ciptaan="{{ $hki->jenis_ciptaan }}"
                                                data-judul_ciptaan="{{ $hki->judul_ciptaan }}"
                                                data-kode_dosen="{{ $hki->kode_dosen }}"
                                                data-nama_dosen="{{ $hki->nama_dosen }}"
                                                data-link_dokumen="{{ $hki->link_dokumen }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('hki.destroy', $hki->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data HKI ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-award display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada karya HKI mandiri untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sertifikasi -->
    <div class="tab-pane fade animate-fade-in" id="tab-sertifikasi" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-patch-check-fill me-2"></i>Sertifikasi Kompetensi / Profesi Dosen</h6>
                <button type="button" class="btn btn-sm btn-primary d-print-none" data-bs-toggle="modal" data-bs-target="#tambahSertifikasiModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Sertifikasi
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 35%;">Nama Sertifikasi</th>
                                <th style="width: 30%;">Penerbit / Penyelenggara</th>
                                <th style="width: 20%;">Link Dokumen</th>
                                <th class="text-center d-print-none" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dosen->sertifikasi as $sert)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $sert->nama_sertifikasi }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $sert->penerbit }}</span>
                                    </td>
                                    <td>
                                        @if($sert->link_dokumen)
                                            <a href="{{ $sert->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-2 px-3" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-2 px-3" style="border-radius: 6px; font-weight: 600;">
                                                <i class="bi bi-x-circle me-1"></i> Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editSertifikasiModal"
                                                data-id="{{ $sert->id }}"
                                                data-kode_dosen="{{ $sert->kode_dosen }}"
                                                data-nama_dosen="{{ $sert->nama_dosen }}"
                                                data-nama_sertifikasi="{{ $sert->nama_sertifikasi }}"
                                                data-penerbit="{{ $sert->penerbit }}"
                                                data-link_dokumen="{{ $sert->link_dokumen }}"
                                                title="Edit Sertifikasi">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('sertifikasi-dosen.destroy', $sert->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data sertifikasi {{ $sert->nama_sertifikasi }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Sertifikasi"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-award-fill display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada data sertifikasi kompetensi/profesi untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kegiatan -->
    <div class="tab-pane fade animate-fade-in" id="tab-kegiatan" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-event-fill me-2"></i>Kegiatan Seminar / Workshop / Pelatihan Dosen</h6>
                <button type="button" class="btn btn-sm btn-primary d-print-none" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Kegiatan
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama Kegiatan</th>
                                <th style="width: 10%;" class="text-center">Tahun</th>
                                <th style="width: 10%;" class="text-center">TA</th>
                                <th style="width: 20%;">Penyelenggara</th>
                                <th style="width: 10%;" class="text-center">Jenis</th>
                                <th style="width: 12%;">Link Dokumen</th>
                                <th class="text-center d-print-none" style="width: 8%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dosen->kegiatan as $keg)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $keg->nama_kegiatan }}</span>
                                    </td>
                                    <td class="text-center">{{ $keg->tahun }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white">{{ $keg->ts->tahun_sekarang ?? '-' }}</span>
                                    </td>
                                    <td>{{ $keg->penyelenggara }}</td>
                                    <td class="text-center">
                                        @if($keg->jenis === 'Internal')
                                            <span class="badge bg-primary px-2 py-1" style="border-radius: 4px;">Internal</span>
                                        @else
                                            <span class="badge bg-info text-dark px-2 py-1" style="border-radius: 4px;">Eksternal</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($keg->link_dokumen)
                                            <a href="{{ $keg->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-2 px-3" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-2 px-3" style="border-radius: 6px; font-weight: 600;">
                                                <i class="bi bi-x-circle me-1"></i> Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editKegiatanModal"
                                                data-id="{{ $keg->id }}"
                                                data-kode_dosen="{{ $keg->kode_dosen }}"
                                                data-nama_dosen="{{ $keg->nama_dosen }}"
                                                data-nama_kegiatan="{{ $keg->nama_kegiatan }}"
                                                data-tahun="{{ $keg->tahun }}"
                                                data-ts_id="{{ $keg->ts_id }}"
                                                data-penyelenggara="{{ $keg->penyelenggara }}"
                                                data-jenis="{{ $keg->jenis }}"
                                                data-link_dokumen="{{ $keg->link_dokumen }}"
                                                title="Edit Kegiatan">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('kegiatan-dosen.destroy', $keg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan {{ $keg->nama_kegiatan }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Kegiatan"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada data seminar/workshop/pelatihan untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Prestasi -->
    <div class="tab-pane fade animate-fade-in" id="tab-prestasi" role="tabpanel">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-trophy-fill me-2"></i>Prestasi Dosen</h6>
                <button type="button" class="btn btn-sm btn-primary d-print-none" data-bs-toggle="modal" data-bs-target="#tambahPrestasiModal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Prestasi
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 25%;">Tema Kegiatan / Nama Prestasi</th>
                                <th style="width: 20%;">Penyelenggara</th>
                                <th style="width: 8%;" class="text-center">Tahun</th>
                                <th style="width: 8%;" class="text-center">TA</th>
                                <th style="width: 12%;" class="text-center">Prestasi yang Diraih</th>
                                <th style="width: 10%;" class="text-center">Tingkat</th>
                                <th style="width: 12%;">Link Dokumen</th>
                                <th class="text-center d-print-none" style="width: 8%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dosen->prestasi as $pres)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $pres->nama_prestasi }}</span>
                                    </td>
                                    <td>{{ $pres->penyelenggara }}</td>
                                    <td class="text-center">{{ $pres->tahun }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white">{{ $pres->ts->tahun_sekarang ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success py-1.5 px-2.5" style="border-radius: 4px; font-weight: 600;">
                                            {{ $pres->prestasi_diraih ?? 'Partisipan' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($pres->level_prestasi === 'internasional')
                                            <span class="badge bg-danger px-2.5 py-1.5" style="border-radius: 4px; font-size: 0.75rem; text-transform: capitalize;">Internasional</span>
                                        @elseif($pres->level_prestasi === 'nasional')
                                            <span class="badge bg-warning text-dark px-2.5 py-1.5" style="border-radius: 4px; font-size: 0.75rem; text-transform: capitalize;">Nasional</span>
                                        @else
                                            <span class="badge bg-primary px-2.5 py-1.5" style="border-radius: 4px; font-size: 0.75rem; text-transform: capitalize;">Lokal</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pres->link_dokumen)
                                            <a href="{{ $pres->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-2 px-3" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-2 px-3" style="border-radius: 6px; font-weight: 600;">
                                                <i class="bi bi-x-circle me-1"></i> Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPrestasiModal"
                                                data-id="{{ $pres->id }}"
                                                data-kode_dosen="{{ $pres->kode_dosen }}"
                                                data-nama_dosen="{{ $pres->nama_dosen }}"
                                                data-nama_prestasi="{{ $pres->nama_prestasi }}"
                                                data-tahun="{{ $pres->tahun }}"
                                                data-ts_id="{{ $pres->ts_id }}"
                                                data-penyelenggara="{{ $pres->penyelenggara }}"
                                                data-level_prestasi="{{ $pres->level_prestasi }}"
                                                data-prestasi_diraih="{{ $pres->prestasi_diraih }}"
                                                data-link_dokumen="{{ $pres->link_dokumen }}"
                                                title="Edit Prestasi">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('prestasi-dosen.destroy', $pres->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus prestasi {{ $pres->nama_prestasi }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="redirect_to" value="{{ route('dosen.show', $dosen->id) }}">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Prestasi"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-trophy display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada data prestasi untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Organisasi -->
    <div class="tab-pane fade animate-fade-in" id="tab-organisasi" role="tabpanel">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-success bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Keanggotaan Organisasi / Asosiasi Dosen</h6>
                @if(Auth::check() && (Auth::user()->level === 'king' || Auth::user()->level === 'jendral' || strtolower(Auth::user()->username) === strtolower($dosen->kode_dosen)))
                    <button type="button" class="btn btn-sm btn-light text-success fw-bold d-print-none" data-bs-toggle="modal" data-bs-target="#kelolaKeanggotaanModal">
                        <i class="bi bi-pencil-square me-1"></i> Kelola Keanggotaan
                    </button>
                @endif
            </div>
            <div class="card-body p-4">

                @php
                    $memberships = $dosen->rekognisi->where('is_keanggotaan', true);
                @endphp

                @if($memberships->isEmpty())
                    <div class="text-center py-5 text-muted bg-light rounded border border-dashed">
                        <i class="bi bi-people display-4 d-block mb-2 text-secondary"></i>
                        <p class="mb-0">Belum ada keanggotaan organisasi yang dipilih.</p>
                        @if(Auth::check() && (Auth::user()->level === 'king' || Auth::user()->level === 'jendral' || strtolower(Auth::user()->username) === strtolower($dosen->kode_dosen)))
                            <button type="button" class="btn btn-sm btn-outline-success mt-3" data-bs-toggle="modal" data-bs-target="#kelolaKeanggotaanModal">
                                <i class="bi bi-plus-circle me-1"></i> Pilih Keanggotaan dari Rekognisi
                            </button>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 8%;">No</th>
                                    <th style="width: 45%;">Nama Organisasi / Keanggotaan (Diambil dari Rekognisi)</th>
                                    <th class="text-center" style="width: 15%;">Tahun</th>
                                    <th class="text-center" style="width: 15%;">Level</th>
                                    <th class="text-center" style="width: 17%;">Bukti Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($memberships as $item)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->nama_rekognisi }}</div>
                                            <small class="text-muted"><i class="bi bi-person me-1"></i>{{ $dosen->nama_dosen }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary text-white">{{ $item->tahun }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $item->level == 'internasional' ? 'primary' : ($item->level == 'nasional' ? 'success' : 'secondary') }}">
                                                {{ ucfirst($item->level) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($item->link_dokumen)
                                                <a href="{{ $item->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                    <i class="bi bi-link-45deg"></i> Lihat SK
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rekognisi Dosen -->
    <div class="tab-pane fade animate-fade-in" id="tab-rekognisi" role="tabpanel">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-check-fill me-2"></i>Rekognisi Dosen</h6>
                <a href="{{ route('rekognisi-dosen.index') }}" class="btn btn-sm btn-primary d-print-none">
                    <i class="bi bi-plus-circle me-1"></i> Kelola Rekognisi
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 40%;">Nama Rekognisi / Keterangan</th>
                                <th class="text-center" style="width: 10%;">Tahun</th>
                                <th class="text-center" style="width: 10%;">TA</th>
                                <th class="text-center" style="width: 15%;">Tingkat</th>
                                <th style="width: 20%;">Bukti Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekognisiList as $rek)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $rek->nama_rekognisi }}</div>
                                        @if($rek->penelitian_dosen_id)
                                            <span class="badge bg-primary-subtle text-primary" style="font-size: 10px;">Penelitian</span>
                                        @elseif($rek->hibah_penelitian_id)
                                            <span class="badge bg-warning-subtle text-warning text-dark" style="font-size: 10px;">Hibah</span>
                                        @elseif($rek->hki_id)
                                            <span class="badge bg-success-subtle text-success" style="font-size: 10px;">HKI</span>
                                        @elseif($rek->prestasi_dosen_id)
                                            <span class="badge bg-danger-subtle text-danger" style="font-size: 10px;">Prestasi</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $rek->tahun }}</td>
                                    <td class="text-center">{{ $rek->ts->tahun_sekarang ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $rek->level == 'internasional' ? 'primary' : ($rek->level == 'nasional' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($rek->level) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($rek->link_dokumen)
                                            <a href="{{ $rek->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Belum ada data rekognisi untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Penelitian Dosen -->
    <div class="tab-pane fade animate-fade-in" id="tab-penelitian" role="tabpanel">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i>Penelitian Dosen</h6>
                <a href="{{ route('penelitian-dosen.index') }}" class="btn btn-sm btn-primary d-print-none">
                    <i class="bi bi-plus-circle me-1"></i> Kelola Penelitian
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama Jurnal & Jenis</th>
                                <th style="width: 35%;">Judul Penelitian / Artikel</th>
                                <th class="text-center" style="width: 10%;">Tahun Akademik</th>
                                <th style="width: 25%;">Dokumen / Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penelitianList as $pen)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $pen->nama_jurnal }}</div>
                                        <div class="text-muted small">Jenis: <span class="badge bg-secondary">{{ $pen->jenis_jurnal }}</span></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $pen->jenis_penelitian }}</div>
                                        <div class="text-muted small">{{ $pen->nama_dosen }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white">{{ $pen->ts->tahun_sekarang ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($pen->link_jurnal)
                                            <a href="{{ $pen->link_jurnal }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none me-1 mb-1">
                                                <i class="bi bi-link-45deg"></i> Jurnal
                                            </a>
                                        @endif
                                        @if($pen->berkas_paper)
                                            <a href="{{ $pen->berkas_paper }}" target="_blank" class="badge bg-success-subtle text-success text-decoration-none me-1 mb-1">
                                                <i class="bi bi-file-earmark-pdf"></i> Paper
                                            </a>
                                        @endif
                                        @if($pen->berkas_sertifikat)
                                            <a href="{{ $pen->berkas_sertifikat }}" target="_blank" class="badge bg-warning-subtle text-warning text-dark text-decoration-none mb-1">
                                                <i class="bi bi-award"></i> Sertifikat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data penelitian untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- PKM Dosen -->
    <div class="tab-pane fade animate-fade-in" id="tab-pkm" role="tabpanel">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-journal-bookmark-fill me-2"></i>PKM Dosen (Pengabdian Kepada Masyarakat)</h6>
                <a href="{{ route('pkm-dosen.index') }}" class="btn btn-sm btn-primary d-print-none">
                    <i class="bi bi-plus-circle me-1"></i> Kelola PKM
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 30%;">Tema / Skema</th>
                                <th style="width: 35%;">Judul Kegiatan</th>
                                <th class="text-center" style="width: 10%;">Tahun Akademik</th>
                                <th style="width: 20%;">Bukti Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pkmList as $pkm)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $pkm->tema_pkm }}</div>
                                        <div class="text-muted small">Skema: {{ $pkm->skema_pkm }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $pkm->judul }}</div>
                                        <div class="text-muted small">{{ $pkm->nama_dosen }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary text-white">{{ $pkm->ts->tahun_sekarang ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $pkmLink = $pkm->link_st ?: ($pkm->link_proposal ?: ($pkm->link_laporan ?: $pkm->link_luaran));
                                        @endphp
                                        @if($pkmLink)
                                            <a href="{{ $pkmLink }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data PKM untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hibah Dosen -->
    <div class="tab-pane fade animate-fade-in" id="tab-hibah" role="tabpanel">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-primary bg-gradient text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cash-coin me-2"></i>Hibah Dosen</h6>
                <a href="{{ route('hibah-penelitian.index') }}" class="btn btn-sm btn-primary d-print-none">
                    <i class="bi bi-plus-circle me-1"></i> Kelola Hibah
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th class="text-center" style="width: 10%;">Jenis</th>
                                <th style="width: 20%;">Skema & Pemberi</th>
                                <th style="width: 35%;">Judul Penelitian</th>
                                <th class="text-end" style="width: 15%;">Biaya</th>
                                <th style="width: 15%;">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hibahList as $hib)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $hib->jenis_hibah == 'internal' ? 'primary' : 'secondary' }}">
                                            {{ ucfirst($hib->jenis_hibah) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $hib->skema_hibah }}</div>
                                        <div class="text-muted small">Pemberi: {{ $hib->pemberi_hibah }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $hib->judul }}</div>
                                        <div class="text-muted small">TA: {{ $hib->ts->tahun_sekarang ?? '-' }}</div>
                                    </td>
                                    <td class="text-end text-success fw-bold">
                                        Rp {{ number_format($hib->biaya, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $hibLink = $hib->link_st ?: ($hib->link_spk ?: ($hib->link_laporan ?: $hib->link_proposal));
                                        @endphp
                                        @if($hibLink)
                                            <a href="{{ $hibLink }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Dokumen
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Belum ada data Hibah Penelitian untuk dosen ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kelola Keanggotaan -->
    @if(Auth::check() && (Auth::user()->level === 'king' || Auth::user()->level === 'jendral' || strtolower(Auth::user()->username) === strtolower($dosen->kode_dosen)))
    <div class="modal fade" id="kelolaKeanggotaanModal" tabindex="-1" aria-labelledby="kelolaKeanggotaanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('dosen.update-keanggotaan', $dosen) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="kelolaKeanggotaanModalLabel"><i class="bi bi-people-fill me-2"></i>Kelola Keanggotaan Organisasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Pilih data rekognisi yang termasuk dalam <strong>Keanggotaan Organisasi / Asosiasi Profesi</strong> dari dosen yang bersangkutan:</p>
                        
                        @if($dosen->rekognisi->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-exclamation-triangle display-6 d-block mb-2 text-warning"></i>
                                Belum ada data rekognisi yang diinputkan untuk dosen ini.
                                <br>
                                <a href="{{ route('rekognisi-dosen.index') }}" class="btn btn-sm btn-primary mt-2">Input Rekognisi Terlebih Dahulu</a>
                            </div>
                        @else
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th class="text-center" style="width: 10%;">Pilih</th>
                                            <th>Nama Rekognisi / Keterangan</th>
                                            <th class="text-center" style="width: 15%;">Tahun</th>
                                            <th class="text-center" style="width: 20%;">Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dosen->rekognisi as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" name="rekognisi_ids[]" value="{{ $item->id }}" id="chk-rekognisi-{{ $item->id }}" class="form-check-input" style="width: 1.25rem; height: 1.25rem;" {{ $item->is_keanggotaan ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <label for="chk-rekognisi-{{ $item->id }}" class="form-check-label d-block fw-semibold text-dark" style="cursor: pointer;">
                                                        {{ $item->nama_rekognisi }}
                                                    </label>
                                                    @if($item->link_dokumen)
                                                        <a href="{{ $item->link_dokumen }}" target="_blank" class="small text-decoration-none"><i class="bi bi-file-earmark-pdf small"></i> Lihat Bukti</a>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->tahun }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $item->level == 'internasional' ? 'primary' : ($item->level == 'nasional' ? 'success' : 'secondary') }}">
                                                        {{ ucfirst($item->level) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        @if(!$dosen->rekognisi->isEmpty())
                            <button type="submit" class="btn btn-success">Simpan Keanggotaan</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Tambah HKI -->
<div class="modal fade" id="tambahHkiModal" tabindex="-1" aria-labelledby="tambahHkiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hki.store') }}" method="POST">
                @csrf
                <!-- Dosen Info (Otomatis) -->
                <input type="hidden" name="kode_dosen" value="{{ $dosen->kode_dosen }}">
                <input type="hidden" name="nama_dosen" value="{{ $dosen->nama_dosen }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahHkiModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Data HKI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6" id="add_nim_container">
                            <label for="add_nim" class="form-label fw-semibold">Mahasiswa Kolaborator <span class="text-danger">*</span></label>
                            <select name="nim" id="add_nim" class="form-select">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach ($mahasiswaList as $m)
                                    <option value="{{ $m->nim }}">{{ $m->nim }} - {{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_no_permohonan" class="form-label fw-semibold">Nomor Permohonan <span class="text-danger">*</span></label>
                            <input type="text" name="no_permohonan" id="add_no_permohonan" class="form-control" required placeholder="Contoh: EC0020239999">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_tgl_permohonan" class="form-label fw-semibold">Tanggal Permohonan <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_permohonan" id="add_tgl_permohonan" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="add_judul_ciptaan" class="form-label fw-semibold">Judul Ciptaan <span class="text-danger">*</span></label>
                            <textarea name="judul_ciptaan" id="add_judul_ciptaan" rows="2" class="form-control" required placeholder="Masukkan judul ciptaan..."></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_link_dokumen" class="form-label fw-semibold">Link Dokumen <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="add_link_dokumen" class="form-control" placeholder="https://example.com/dokumen">
                        </div>
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
                <!-- Dosen Info (Otomatis) -->
                <input type="hidden" name="kode_dosen" id="edit_kode_dosen" value="{{ $dosen->kode_dosen }}">
                <input type="hidden" name="nama_dosen" id="edit_nama_dosen" value="{{ $dosen->nama_dosen }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editHkiModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data HKI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6" id="edit_nim_container">
                            <label for="edit_nim" class="form-label fw-semibold">Mahasiswa Kolaborator <span class="text-danger">*</span></label>
                            <select name="nim" id="edit_nim" class="form-select">
                                <option value="">-- Tanpa Mahasiswa (Mandiri) --</option>
                                @foreach ($mahasiswaList as $m)
                                    <option value="{{ $m->nim }}">{{ $m->nim }} - {{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_no_permohonan" class="form-label fw-semibold">Nomor Permohonan <span class="text-danger">*</span></label>
                            <input type="text" name="no_permohonan" id="edit_no_permohonan" class="form-control" required placeholder="Contoh: EC0020239999">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_tgl_permohonan" class="form-label fw-semibold">Tanggal Permohonan <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_permohonan" id="edit_tgl_permohonan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="edit_judul_ciptaan" class="form-label fw-semibold">Judul Ciptaan <span class="text-danger">*</span></label>
                            <textarea name="judul_ciptaan" id="edit_judul_ciptaan" rows="2" class="form-control" required placeholder="Masukkan judul ciptaan..."></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_link_dokumen" class="form-label fw-semibold">Link Dokumen <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="edit_link_dokumen" class="form-control" placeholder="https://example.com/dokumen">
                        </div>
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

<!-- Modal Upload/Edit Foto -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1" aria-labelledby="updatePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dosen.update-photo', $dosen) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="action" value="upload">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="updatePhotoModalLabel"><i class="bi bi-camera-fill me-2"></i>{{ $dosen->foto ? 'Edit' : 'Upload' }} Foto Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="foto" class="form-label fw-semibold">Pilih File Gambar <span class="text-danger">*</span></label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required onchange="previewImage(this)">
                        <div class="form-text text-muted">Format gambar: jpeg, png, jpg, gif (Maks. 2MB).</div>
                    </div>
                    <!-- Preview Box -->
                    <div class="text-center d-none mb-2" id="preview_box">
                        <span class="text-muted small d-block mb-2">Pratinjau Gambar:</span>
                        <img id="img_preview" class="img-thumbnail" style="max-height: 150px; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Tambah Sertifikasi -->
<div class="modal fade" id="tambahSertifikasiModal" tabindex="-1" aria-labelledby="tambahSertifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sertifikasi-dosen.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kode_dosen" value="{{ $dosen->kode_dosen }}">
                <input type="hidden" name="nama_dosen" value="{{ $dosen->nama_dosen }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahSertifikasiModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Sertifikasi Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Dosen</label>
                            <input type="text" class="form-control" value="{{ $dosen->kode_dosen }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" class="form-control" value="{{ $dosen->nama_dosen }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_nama_sertifikasi" class="form-label fw-semibold">Nama Sertifikasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sertifikasi" id="add_nama_sertifikasi" class="form-control" required placeholder="Contoh: Pendidik Profesional, CCNA, BNSP Programmer">
                        </div>
                        <div class="col-md-6">
                            <label for="add_penerbit" class="form-label fw-semibold">Penerbit / Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penerbit" id="add_penerbit" class="form-control" required placeholder="Contoh: Kemendikbudristek, Cisco, BNSP">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_sert_link_dokumen" class="form-label fw-semibold">Link Dokumen Sertifikat <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="add_sert_link_dokumen" class="form-control" placeholder="https://example.com/sertifikat-saya">
                        </div>
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

<!-- Modal Edit Sertifikasi -->
<div class="modal fade" id="editSertifikasiModal" tabindex="-1" aria-labelledby="editSertifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="kode_dosen" id="edit_sert_kode_dosen">
                <input type="hidden" name="nama_dosen" id="edit_sert_nama_dosen">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editSertifikasiModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Sertifikasi Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Dosen</label>
                            <input type="text" id="edit_sert_kode_dosen_display" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" id="edit_sert_nama_dosen_display" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_nama_sertifikasi" class="form-label fw-semibold">Nama Sertifikasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_sertifikasi" id="edit_nama_sertifikasi" class="form-control" required placeholder="Masukkan nama sertifikasi...">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_penerbit" class="form-label fw-semibold">Penerbit / Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penerbit" id="edit_penerbit" class="form-control" required placeholder="Masukkan penerbit...">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_sert_link_dokumen" class="form-label fw-semibold">Link Dokumen Sertifikat <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="edit_sert_link_dokumen" class="form-control">
                        </div>
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
<!-- Modal Tambah Kegiatan -->
<div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kegiatan-dosen.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kode_dosen" value="{{ $dosen->kode_dosen }}">
                <input type="hidden" name="nama_dosen" value="{{ $dosen->nama_dosen }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahKegiatanModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Kegiatan Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Dosen</label>
                            <input type="text" class="form-control" value="{{ $dosen->kode_dosen }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" class="form-control" value="{{ $dosen->nama_dosen }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" id="add_nama_kegiatan" class="form-control" required placeholder="Contoh: Seminar AI, Workshop PHP, Pelatihan ISO">
                        </div>
                        <div class="col-md-6">
                            <label for="add_keg_tahun" class="form-label fw-semibold">Tahun Kegiatan <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" id="add_keg_tahun" class="form-control" required min="2000" max="2100" placeholder="Contoh: 2026">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_keg_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="add_keg_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_keg_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penyelenggara" id="add_keg_penyelenggara" class="form-control" required placeholder="Contoh: LLDIKTI, Universitas Indonesia, Google">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_keg_jenis" class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select name="jenis" id="add_keg_jenis" class="form-select" required>
                                <option value="Internal">Internal</option>
                                <option value="Eksternal">Eksternal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_keg_link_dokumen" class="form-label fw-semibold">Link Sertifikat / Dokumen <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="add_keg_link_dokumen" class="form-control" placeholder="https://example.com/sertifikat-workshop">
                        </div>
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

<!-- Modal Edit Kegiatan -->
<div class="modal fade" id="editKegiatanModal" tabindex="-1" aria-labelledby="editKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="kode_dosen" id="edit_keg_kode_dosen">
                <input type="hidden" name="nama_dosen" id="edit_keg_nama_dosen">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editKegiatanModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kegiatan Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Dosen</label>
                            <input type="text" id="edit_keg_kode_dosen_display" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" id="edit_keg_nama_dosen_display" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" id="edit_nama_kegiatan" class="form-control" required placeholder="Masukkan nama kegiatan...">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_keg_tahun" class="form-label fw-semibold">Tahun Kegiatan <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" id="edit_keg_tahun" class="form-control" required min="2000" max="2100">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_keg_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="edit_keg_ts_id" class="form-select" required>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_keg_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penyelenggara" id="edit_keg_penyelenggara" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_keg_jenis" class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select name="jenis" id="edit_keg_jenis" class="form-select" required>
                                <option value="Internal">Internal</option>
                                <option value="Eksternal">Eksternal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_keg_link_dokumen" class="form-label fw-semibold">Link Sertifikat / Dokumen <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="edit_keg_link_dokumen" class="form-control">
                        </div>
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
            <form action="{{ route('prestasi-dosen.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kode_dosen" value="{{ $dosen->kode_dosen }}">
                <input type="hidden" name="nama_dosen" value="{{ $dosen->nama_dosen }}">
                <input type="hidden" name="redirect_to" value="{{ route('dosen.show', $dosen->id) }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahPrestasiModalLabel"><i class="bi bi-trophy-fill me-2 text-primary"></i>Tambah Prestasi Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Dosen</label>
                            <input type="text" class="form-control" value="{{ $dosen->kode_dosen }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" class="form-control" value="{{ $dosen->nama_dosen }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_pres_nama" class="form-label fw-semibold">Tema Kegiatan / Nama Prestasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_prestasi" id="add_pres_nama" class="form-control" required placeholder="Contoh: Lomba Inovasi Media Pembelajaran">
                        </div>
                        <div class="col-md-6">
                            <label for="add_pres_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penyelenggara" id="add_pres_penyelenggara" class="form-control" required placeholder="Contoh: Kemendikbudristek, LLDIKTI">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_pres_tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" id="add_pres_tahun" class="form-control" required min="2000" max="2100" placeholder="Contoh: 2026">
                        </div>
                        <div class="col-md-6">
                            <label for="add_pres_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="add_pres_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_pres_diraih" class="form-label fw-semibold">Prestasi yang Diraih <span class="text-danger">*</span></label>
                            <select name="prestasi_diraih" id="add_pres_diraih" class="form-select" required>
                                <option value="Juara 1">Juara 1</option>
                                <option value="Juara 2">Juara 2</option>
                                <option value="Juara 3">Juara 3</option>
                                <option value="harapan I">Harapan I</option>
                                <option value="harapan II">Harapan II</option>
                                <option value="Finalis">Finalis</option>
                                <option value="Hibah Eksternal">Hibah Eksternal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_pres_level" class="form-label fw-semibold">Tingkat Prestasi <span class="text-danger">*</span></label>
                            <select name="level_prestasi" id="add_pres_level" class="form-select" required>
                                <option value="lokal">Lokal</option>
                                <option value="nasional">Nasional</option>
                                <option value="internasional">Internasional</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_pres_link_dokumen" class="form-label fw-semibold">Link Dokumen Bukti <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="add_pres_link_dokumen" class="form-control" placeholder="https://example.com/sertifikat-prestasi">
                        </div>
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
                <input type="hidden" name="kode_dosen" id="edit_pres_kode_dosen">
                <input type="hidden" name="nama_dosen" id="edit_pres_nama_dosen">
                <input type="hidden" name="redirect_to" value="{{ route('dosen.show', $dosen->id) }}">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editPrestasiModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Prestasi Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Dosen</label>
                            <input type="text" id="edit_pres_kode_dosen_display" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" id="edit_pres_nama_dosen_display" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_nama_prestasi" class="form-label fw-semibold">Tema Kegiatan / Nama Prestasi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_prestasi" id="edit_nama_prestasi" class="form-control" required placeholder="Masukkan nama prestasi...">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pres_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penyelenggara" id="edit_pres_penyelenggara" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_pres_tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" id="edit_pres_tahun" class="form-control" required min="2000" max="2100">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pres_ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                            <select name="ts_id" id="edit_pres_ts_id" class="form-select" required>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_prestasi_diraih" class="form-label fw-semibold">Prestasi yang Diraih <span class="text-danger">*</span></label>
                            <select name="prestasi_diraih" id="edit_prestasi_diraih" class="form-select" required>
                                <option value="Juara 1">Juara 1</option>
                                <option value="Juara 2">Juara 2</option>
                                <option value="Juara 3">Juara 3</option>
                                <option value="harapan I">Harapan I</option>
                                <option value="harapan II">Harapan II</option>
                                <option value="Finalis">Finalis</option>
                                <option value="Hibah Eksternal">Hibah Eksternal</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_pres_level" class="form-label fw-semibold">Tingkat Prestasi <span class="text-danger">*</span></label>
                            <select name="level_prestasi" id="edit_pres_level" class="form-select" required>
                                <option value="lokal">Lokal</option>
                                <option value="nasional">Nasional</option>
                                <option value="internasional">Internasional</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_pres_link_dokumen" class="form-label fw-semibold">Link Dokumen Bukti <span class="text-muted small">(Opsional)</span></label>
                            <input type="url" name="link_dokumen" id="edit_pres_link_dokumen" class="form-control">
                        </div>
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
@push('scripts')
<script>
    function previewImage(input) {
        var previewBox = document.getElementById('preview_box');
        var imgPreview = document.getElementById('img_preview');
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                previewBox.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            previewBox.classList.add('d-none');
        }
    }

    // HKI Type Handler for Add Modal
    function setHkiType(type) {
        var container = document.getElementById('add_nim_container');
        var nimSelect = document.getElementById('add_nim');
        if (type === 'mandiri') {
            container.style.display = 'none';
            nimSelect.value = '';
            nimSelect.required = false;
        } else {
            container.style.display = 'block';
            nimSelect.required = true;
        }
    }

    // Edit Modal Population for HKI
    var editHkiModal = document.getElementById('editHkiModal');
    if (editHkiModal) {
        editHkiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var nim = button.getAttribute('data-nim');
            var noPermohonan = button.getAttribute('data-no_permohonan');
            var tglPermohonan = button.getAttribute('data-tgl_permohonan');
            var jenisCiptaan = button.getAttribute('data-jenis_ciptaan');
            var judulCiptaan = button.getAttribute('data-judul_ciptaan');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('hki.store') }}";
            action = action.replace('/hki', '/hki/' + id);
            
            var form = editHkiModal.querySelector('form');
            form.setAttribute('action', action);
            
            var editNimContainer = document.getElementById('edit_nim_container');
            var editNimSelect = document.getElementById('edit_nim');
            
            if (!nim) {
                editNimContainer.style.display = 'none';
                editNimSelect.value = '';
                editNimSelect.required = false;
            } else {
                editNimContainer.style.display = 'block';
                editNimSelect.value = nim;
                editNimSelect.required = true;
            }
            
            document.getElementById('edit_no_permohonan').value = noPermohonan || '';
            document.getElementById('edit_tgl_permohonan').value = tglPermohonan || '';
            document.getElementById('edit_jenis_ciptaan').value = jenisCiptaan || '';
            document.getElementById('edit_judul_ciptaan').value = judulCiptaan || '';
            document.getElementById('edit_link_dokumen').value = linkDokumen || '';
        });
    }

    // Edit Sertifikasi Modal Population
    var editSertifikasiModal = document.getElementById('editSertifikasiModal');
    if (editSertifikasiModal) {
        editSertifikasiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var kodeDosen = button.getAttribute('data-kode_dosen');
            var namaDosen = button.getAttribute('data-nama_dosen');
            var namaSertifikasi = button.getAttribute('data-nama_sertifikasi');
            var penerbit = button.getAttribute('data-penerbit');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('sertifikasi-dosen.index') }}/" + id;
            
            var form = editSertifikasiModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_sert_kode_dosen').value = kodeDosen || '';
            document.getElementById('edit_sert_kode_dosen_display').value = kodeDosen || '';
            document.getElementById('edit_sert_nama_dosen').value = namaDosen || '';
            document.getElementById('edit_sert_nama_dosen_display').value = namaDosen || '';
            document.getElementById('edit_nama_sertifikasi').value = namaSertifikasi || '';
            document.getElementById('edit_penerbit').value = penerbit || '';
            document.getElementById('edit_sert_link_dokumen').value = linkDokumen || '';
        });
    }

    // Edit Kegiatan Modal Population
    var editKegiatanModal = document.getElementById('editKegiatanModal');
    if (editKegiatanModal) {
        editKegiatanModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var kodeDosen = button.getAttribute('data-kode_dosen');
            var namaDosen = button.getAttribute('data-nama_dosen');
            var namaKegiatan = button.getAttribute('data-nama_kegiatan');
            var tahun = button.getAttribute('data-tahun');
            var tsId = button.getAttribute('data-ts_id');
            var penyelenggara = button.getAttribute('data-penyelenggara');
            var jenis = button.getAttribute('data-jenis');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('kegiatan-dosen.index') }}/" + id;
            
            var form = editKegiatanModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_keg_kode_dosen').value = kodeDosen || '';
            document.getElementById('edit_keg_kode_dosen_display').value = kodeDosen || '';
            document.getElementById('edit_keg_nama_dosen').value = namaDosen || '';
            document.getElementById('edit_keg_nama_dosen_display').value = namaDosen || '';
            document.getElementById('edit_nama_kegiatan').value = namaKegiatan || '';
            document.getElementById('edit_keg_tahun').value = tahun || '';
            document.getElementById('edit_keg_ts_id').value = tsId || '';
            document.getElementById('edit_keg_penyelenggara').value = penyelenggara || '';
            document.getElementById('edit_keg_jenis').value = jenis || '';
            document.getElementById('edit_keg_link_dokumen').value = linkDokumen || '';
        });
    }

    // Edit Prestasi Modal Population
    var editPrestasiModal = document.getElementById('editPrestasiModal');
    if (editPrestasiModal) {
        editPrestasiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            var id = button.getAttribute('data-id');
            var kodeDosen = button.getAttribute('data-kode_dosen');
            var namaDosen = button.getAttribute('data-nama_dosen');
            var namaPrestasi = button.getAttribute('data-nama_prestasi');
            var tahun = button.getAttribute('data-tahun');
            var tsId = button.getAttribute('data-ts_id');
            var penyelenggara = button.getAttribute('data-penyelenggara');
            var levelPrestasi = button.getAttribute('data-level_prestasi');
            var prestasiDiraih = button.getAttribute('data-prestasi_diraih');
            var linkDokumen = button.getAttribute('data-link_dokumen');
            
            var action = "{{ route('prestasi-dosen.index') }}/" + id;
            
            var form = editPrestasiModal.querySelector('form');
            form.setAttribute('action', action);
            
            document.getElementById('edit_pres_kode_dosen').value = kodeDosen || '';
            document.getElementById('edit_pres_kode_dosen_display').value = kodeDosen || '';
            document.getElementById('edit_pres_nama_dosen').value = namaDosen || '';
            document.getElementById('edit_pres_nama_dosen_display').value = namaDosen || '';
            document.getElementById('edit_nama_prestasi').value = namaPrestasi || '';
            document.getElementById('edit_pres_tahun').value = tahun || '';
            document.getElementById('edit_pres_ts_id').value = tsId || '';
            document.getElementById('edit_pres_penyelenggara').value = penyelenggara || '';
            document.getElementById('edit_pres_level').value = levelPrestasi || '';
            document.getElementById('edit_prestasi_diraih').value = prestasiDiraih || '';
            document.getElementById('edit_pres_link_dokumen').value = linkDokumen || '';
        });
    }
</script>
@endpush
@endsection
