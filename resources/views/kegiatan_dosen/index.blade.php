@extends('layouts.app')

@section('title', 'Data Kegiatan Dosen')

@section('content')
<style>
    .page-gradient-bg {
        background: radial-gradient(circle at 10% 20%, rgba(255, 245, 235, 0.8) 0%, rgba(255, 237, 213, 0.5) 90.1%);
        padding: 24px;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px 0 rgba(251, 146, 60, 0.06) !important;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .glass-panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px 0 rgba(251, 146, 60, 0.1) !important;
    }

    .gradient-header {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        color: #fff !important;
        border: none !important;
    }

    .stat-card-total {
        background: linear-gradient(135deg, #f97316, #ea580c) !important;
        color: #fff !important;
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 8px 24px rgba(249, 115, 22, 0.3) !important;
        position: relative;
        overflow: hidden;
    }

    .stat-card-total::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -20%;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50%;
    }

    .glass-table {
        background: rgba(255, 255, 255, 0.4) !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 14px !important;
        overflow: hidden !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    .glass-table thead tr th {
        background: rgba(15, 23, 42, 0.9) !important;
        color: #fff !important;
        border: none !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.78rem;
        letter-spacing: 0.5px;
        padding: 13px 10px !important;
    }

    .glass-table tbody tr {
        transition: all 0.2s ease;
    }

    .glass-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.75) !important;
    }

    .glass-table tbody tr td {
        border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.7) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 10px !important;
        transition: all 0.3s ease;
    }

    .glass-input:focus {
        background: #fff !important;
        border-color: #f97316 !important;
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.15) !important;
    }

    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.03); border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.25); }

    .level-badge-internasional { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }
    .level-badge-nasional      { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }
    .level-badge-lokal         { background: linear-gradient(135deg, #94a3b8, #64748b); color: #fff; }

    @media print {
        @page { size: landscape; margin: 10mm; }
        body { font-size: 11px; color: #000; background: #fff; }
        .page-gradient-bg { background: none !important; padding: 0 !important; border: none !important; }
        .glass-panel { background: none !important; box-shadow: none !important; border: none !important; }
        .col-lg-9, .col-md-8 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Kegiatan Dosen Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="page-gradient-bg">
    <div class="row">
        {{-- KIRI: Panel Statistik --}}
        <div class="col-lg-3 col-md-4 d-print-none mb-4">
            <div class="card glass-panel border-0 mb-3">
                <div class="card-header gradient-header py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik kegiatan</h5>
                </div>
                <div class="card-body">

                    {{-- Total --}}
                    <div class="mb-4 text-center py-3 stat-card-total">
                        <span class="text-white-50 small d-block mb-1"><i class="bi bi-trophy-fill me-1"></i>Total kegiatan</span>
                        <h2 class="fw-bold mb-0 text-white">{{ $totalkegiatan }}</h2>
                    </div>

                    {{-- Berdasarkan Jenis --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                <i class="bi bi-tag-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan Jenis</span>
                        </div>
                        <div class="ps-1">
                            @foreach ($jenisCounts as $jenis => $count)
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                    <span class="small text-dark">{{ $jenis }}</span>
                                    <span class="badge rounded-pill {{ $jenis == 'Internal' ? 'bg-primary' : 'bg-success' }}">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Berdasarkan TS (Label TS) --}}
                    @if(!empty($labelTsCounts))
                    <div class="mb-4 border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="bi bi-tags-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan TS</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 140px; overflow-y: auto;">
                            @foreach ($labelTsCounts as $labelTs => $count)
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                    <span class="small text-dark">{{ $labelTs }}</span>
                                    <span class="badge rounded-pill" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color:#fff;">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Berdasarkan TA --}}
                    @if(!empty($tsCounts))
                    <div class="mb-4 border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info text-dark rounded p-1 px-2 me-2">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan TA</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 140px; overflow-y: auto;">
                            @foreach ($tsCounts as $taName => $count)
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                    <span class="small text-dark">{{ $taName }}</span>
                                    <span class="badge bg-info text-dark rounded-pill">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Berdasarkan Dosen --}}
                    @if(!empty($dosenCounts))
                    <div class="mb-2 border-top pt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success text-white rounded p-1 px-2 me-2">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan Dosen</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 180px; overflow-y: auto;">
                            @foreach ($dosenCounts as $nama => $count)
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                    <span class="small text-dark text-truncate" style="max-width: 150px;" title="{{ $nama }}">{{ $nama }}</span>
                                    <span class="badge bg-success rounded-pill">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- KANAN: Tabel Data --}}
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <div>
                    <h1 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">Data Kegiatan Dosen</h1>
                    <p class="text-muted mb-0">Total: <strong>{{ $totalkegiatan }}</strong> kegiatan terdaftar</p>
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

            {{-- Filter & Search --}}
            <div class="card glass-panel mb-3 d-print-none border-0">
                <div class="card-body py-2">
                    <form method="GET" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control glass-input"
                                placeholder="Cari dosen, kegiatan, penyelenggara, level..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="per_page" class="form-select glass-input" onchange="this.form.submit()" title="Tampilkan baris">
                                <option value="10"  {{ request('per_page', 10) == 10  ? 'selected' : '' }}>10 Baris</option>
                                <option value="25"  {{ request('per_page') == 25  ? 'selected' : '' }}>25 Baris</option>
                                <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50 Baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                            </select>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <button type="submit" class="btn btn-primary px-3 me-1" style="border-radius:10px;">
                                <i class="bi bi-search"></i> Cari
                            </button>
                            <a href="{{ route('kegiatan-dosen.index', array_merge(request()->query(), ['print' => 'all'])) }}"
                               target="_blank" class="btn btn-success px-3 me-1" style="border-radius:10px;">
                                <i class="bi bi-printer"></i> Cetak
                            </a>
                            <button type="button" class="btn btn-warning px-3 me-1" data-bs-toggle="modal" data-bs-target="#tambahkegiatanModal" style="border-radius:10px;">
                                <i class="bi bi-plus-circle"></i> Tambah
                            </button>
                            @if(request('search') || request('per_page'))
                                <a href="{{ route('kegiatan-dosen.index') }}" class="btn btn-secondary px-3" style="border-radius:10px;">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="table-responsive glass-panel border-0">
                <table class="table glass-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:4%;">No</th>
                            <th style="width:16%;">Dosen</th>
                            <th style="width:20%;">Nama Kegiatan</th>
                            <th style="width:10%;">Tahun</th>
                            <th style="width:10%;">TA</th>
                            <th style="width:20%;">Penyelenggara</th>
                            <th style="width:10%;">Jenis</th>
                            <th style="width:5%;">Bukti</th>
                            <th class="text-center d-print-none" style="width:5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatans as $item)
                            <tr>
                                <td class="text-center fw-bold text-muted">
                                    {{ ($kegiatans->currentPage() - 1) * $kegiatans->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <strong class="text-dark d-block" style="font-size:0.9rem;">{{ $item->nama_dosen }}</strong>
                                    <span class="badge bg-dark-subtle text-dark small" style="font-size:10px;">{{ $item->kode_dosen }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $item->nama_kegiatan }}</span>
                                </td>
                                <td class="fw-semibold text-dark">{{ $item->tahun }}</td>
                                <td class="text-muted">{{ $item->ts->tahun_sekarang ?? '-' }}</td>
                                <td class="small text-dark">{{ $item->penyelenggara }}</td>
                                <td>
                                    <span class="badge {{ $item->jenis == 'Internal' ? 'bg-primary' : 'bg-success' }} rounded-pill" style="font-size:11px;">
                                        {{ $item->jenis }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->jenis == 'Internal')
                                        @php
                                            $dosenModel = $item->dosen;
                                            $identifiers = $dosenModel ? array_filter([$dosenModel->kode_dosen, $dosenModel->nip, $dosenModel->nidn, $dosenModel->nuptk]) : [$item->kode_dosen];
                                            $kegiatanId = $item->kegiatan_prodi_id;
                                            if (!$kegiatanId) {
                                                $kegSistem = \App\Models\Kegiatan::where('nama_kegiatan', $item->nama_kegiatan)->first();
                                                if ($kegSistem) {
                                                    $kegiatanId = $kegSistem->id;
                                                    // Optional: update the DB so we don't query next time
                                                    $item->update(['kegiatan_prodi_id' => $kegiatanId]);
                                                }
                                            }
                                            $peserta = null;
                                            if ($kegiatanId) {
                                                $peserta = \App\Models\PesertaKegiatan::where('kegiatan_id', $kegiatanId)
                                                    ->whereIn('identifier', $identifiers)
                                                    ->first();
                                            }
                                        @endphp
                                        @if($peserta && $kegiatanId)
                                            <a href="{{ route('kegiatan.sertifikat.cetak', ['kegiatan' => $kegiatanId, 'peserta' => $peserta->id]) }}" target="_blank"
                                               class="badge bg-primary-subtle text-primary text-decoration-none">
                                                <i class="bi bi-award"></i> Sertifikat
                                            </a>
                                        @else
                                            @if($item->link_dokumen)
                                                <a href="{{ $item->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none">
                                                    <i class="bi bi-link-45deg"></i> Link Manual
                                                </a>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning"><i class="bi bi-exclamation-triangle"></i> Belum Terdaftar</span>
                                            @endif
                                        @endif
                                    @elseif($item->link_dokumen)
                                        <a href="{{ $item->link_dokumen }}" target="_blank"
                                           class="badge bg-info-subtle text-info text-decoration-none">
                                            <i class="bi bi-link-45deg"></i> Link
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center d-print-none">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('kegiatan-dosen.show', $item) }}"
                                           class="btn btn-sm btn-info text-white" style="border-radius:6px 0 0 6px;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('kegiatan-dosen.edit', $item) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kegiatan-dosen.destroy', $item) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius:0 6px 6px 0;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada data Kegiatan Dosen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($kegiatans->hasPages())
                <div class="d-flex justify-content-center d-print-none mt-4">
                    {{ $kegiatans->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="tambahkegiatanModal" tabindex="-1" aria-labelledby="tambahkegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-panel border-0">
            <form action="{{ route('kegiatan-dosen.store') }}" method="POST">
                @csrf
                <div class="modal-header gradient-header">
                    <h5 class="modal-title fw-bold" id="tambahkegiatanModalLabel">
                        <i class="bi bi-plus-circle-fill me-2"></i>Tambah Kegiatan Dosen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_kode_dosen" class="form-label fw-semibold">Kode Dosen <span class="text-danger">*</span></label>
                            <select name="kode_dosen" id="modal_kode_dosen" class="form-select glass-input"
                                    data-route="{{ route('kegiatan-dosen.get-dosen', '') }}/" required>
                                <option value="">-- Pilih Kode Dosen --</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->kode_dosen }}">
                                        {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nama_dosen" class="form-label fw-semibold">Nama Dosen <span class="text-danger">*</span></label>
                            <input type="text" name="nama_dosen" id="modal_nama_dosen" class="form-control glass-input" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nama_kegiatan" class="form-label fw-semibold">Nama kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" id="modal_nama_kegiatan" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="modal_tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="modal_tahun" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="modal_ts_id" class="form-label fw-semibold">TA <span class="text-danger">*</span></label>
                            <select name="ts_id" id="modal_ts_id" class="form-select glass-input" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_penyelenggara" class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                            <input type="text" name="penyelenggara" id="modal_penyelenggara" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_jenis" class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
                            <select name="jenis" id="modal_jenis" class="form-select glass-input" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Internal">Internal (Kegiatan Prodi)</option>
                                <option value="Eksternal">Eksternal (Luar Prodi)</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3" id="wrapper_kegiatan_internal" style="display: none;">
                            <label for="modal_kegiatan_prodi_id" class="form-label fw-semibold">Pilih Kegiatan Internal <span class="text-danger">*</span></label>
                            <select name="kegiatan_prodi_id" id="modal_kegiatan_prodi_id" class="form-select glass-input">
                                <option value="">-- Pilih Kegiatan --</option>
                                @foreach($kegiatanSistem as $k)
                                    <option value="{{ $k->id }}" data-nama="{{ $k->nama_kegiatan }}" data-tahun="{{ $k->tanggal ? date('Y', strtotime($k->tanggal)) : '' }}">
                                        {{ $k->nama_kegiatan }} ({{ $k->tanggal ? date('Y', strtotime($k->tanggal)) : 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Sertifikat akan otomatis diambil dari sistem jika dosen ini terdaftar sebagai peserta.</small>
                        </div>
                        <div class="col-md-12 mb-3" id="wrapper_link_dokumen">
                            <label for="modal_link_dokumen" class="form-label fw-semibold">Link Dokumen Bukti (Sertifikat)</label>
                            <input type="url" name="link_dokumen" id="modal_link_dokumen"
                                   class="form-control glass-input" placeholder="https://example.com/sertifikat-kegiatan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"
                            style="border-radius:10px; background: linear-gradient(135deg, #f97316, #ea580c); border:none;">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('tambahkegiatanModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Autocomplete Kode Dosen
    document.getElementById('modal_kode_dosen').addEventListener('change', function () {
        var kode = this.value;
        if (kode) {
            var route = this.getAttribute('data-route');
            fetch(route + kode)
                .then(res => res.json())
                .then(data => { document.getElementById('modal_nama_dosen').value = data.nama_dosen; })
                .catch(() => { document.getElementById('modal_nama_dosen').value = ''; });
        } else {
            document.getElementById('modal_nama_dosen').value = '';
        }
    // Toggle logic for Jenis Kegiatan
    const jenisSelect = document.getElementById('modal_jenis');
    const wrapInternal = document.getElementById('wrapper_kegiatan_internal');
    const wrapLink = document.getElementById('wrapper_link_dokumen');
    const inputKegiatanInternal = document.getElementById('modal_kegiatan_prodi_id');
    const inputNamaKegiatan = document.getElementById('modal_nama_kegiatan');
    const inputTahun = document.getElementById('modal_tahun');
    const inputPenyelenggara = document.getElementById('modal_penyelenggara');

    jenisSelect.addEventListener('change', function() {
        if (this.value === 'Internal') {
            wrapInternal.style.display = 'block';
            inputKegiatanInternal.required = true;
            wrapLink.style.display = 'none';
        } else {
            wrapInternal.style.display = 'none';
            inputKegiatanInternal.required = false;
            wrapLink.style.display = 'block';
        }
    });

    // Auto-fill when selecting internal kegiatan
    inputKegiatanInternal.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.value) {
            inputNamaKegiatan.value = selected.getAttribute('data-nama');
            inputTahun.value = selected.getAttribute('data-tahun');
            inputPenyelenggara.value = 'Program Studi';
        }
    });
});
</script>
@endpush
@endsection
