@extends('layouts.app')

@section('title', 'Data Kerjasama')

@section('content')
<!-- Custom Premium Stats Styles -->
<style>
    .stats-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 16px !important;
        overflow: hidden;
        position: relative;
    }
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.08), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    .stats-card:hover::before {
        transform: translateX(100%);
    }
    .stats-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.15) !important;
    }
    .icon-box {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stats-card:hover .icon-box {
        background: rgba(255, 255, 255, 0.28);
        transform: rotate(8deg) scale(1.08);
        border-color: rgba(255, 255, 255, 0.4);
    }
</style>

<div class="row">
    <!-- Left Column: Statistik Kerjasama (Dosen Style) -->
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Kerjasama</h5>
            </div>
            <div class="card-body">
                <!-- Total Kerjasama -->
                <div class="mb-4 text-center py-3 rounded text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
                    <span class="text-white-50 small d-block mb-1">Total Kerjasama</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalKerjasama }}</h2>
                </div>

                <!-- Card Kerjasama Baru -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background-color: #10b981 !important;">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Kerjasama Baru</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                            <span class="text-dark">Tahun Ini ({{ date('Y') }})</span>
                            <span class="badge bg-success rounded-pill">{{ $kerjasamaTahunIni }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Jumlah PKS per Mitra -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2" style="background-color: #2563eb !important;">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">PKS per Mitra</span>
                    </div>
                    <div class="ps-1" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($pksPerMitra as $mitra => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-dark text-truncate" style="max-width: 160px;" title="{{ $mitra }}">{{ $mitra }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }} PKS</span>
                            </div>
                        @empty
                            <span class="text-muted small">Belum ada data PKS</span>
                        @endforelse
                    </div>
                </div>

                <!-- Card Masa Berlaku MoU -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Masa Berlaku MoU</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                            <span class="text-dark">Masih Aktif</span>
                            <span class="badge bg-success rounded-pill">{{ $mouAktif }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                            <span class="text-dark">Akan Berakhir ({{ date('Y') }})</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $mouAkanBerakhir }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                            <span class="text-dark">Telah Berakhir</span>
                            <span class="badge bg-danger rounded-pill">{{ $mouTelahBerakhir }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card MoU Per Tahun -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2" style="background-color: #ffc107 !important;">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">MoU Per Tahun</span>
                    </div>
                    <div class="ps-1" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($mouPerTahun as $tahun => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-dark">{{ $tahun }}</span>
                                <span class="badge bg-warning text-dark rounded-pill" style="background-color: #ffc107 !important;">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Table Card -->
    <div class="col-lg-9 col-md-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 fw-bold text-dark">Data Kerjasama</h1>
                        <p class="text-muted mb-0">Total: <strong>{{ $totalKerjasama }}</strong> kerjasama tercatat</p>
                    </div>
                    <div class="d-print-none">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKerjasamaModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Kerjasama
                        </button>
                    </div>
                </div>

                <!-- Search & Filter Form -->
                <form method="GET" action="{{ route('kerjasama.index') }}" class="row g-2 align-items-center mb-4 d-print-none">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari berdasarkan nama mitra, nomor MoU, ketua, atau tahun..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-list-ol"></i></span>
                            <select name="limit" class="form-select border-start-0" onchange="this.form.submit()">
                                <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10 Data</option>
                                <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20 Data</option>
                                <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50 Data</option>
                                <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100 Data</option>
                                <option value="500" {{ $limit == 500 ? 'selected' : '' }}>500 Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex gap-1 justify-content-end">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                        @if (request('search') || $limit != 10)
                            <a href="{{ route('kerjasama.index') }}" class="btn btn-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                        @endif
                    </div>
                </form>

                @if ($errors->any())
                    <div class="alert alert-danger d-print-none shadow-sm border-0 border-start border-danger border-4 mb-4">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success d-print-none shadow-sm border-0 border-start border-success border-4 mb-4">
                        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th class="text-center" style="width: 10%;">Tahun</th>
                                <th>Nama Mitra</th>
                                <th>Nomor MoU UBSI</th>
                                <th>Nomor MoU Mitra</th>
                                <th>Ketua / Mewakili</th>
                                <th class="text-center" style="width: 15%;">No. WA</th>
                                <th class="text-center d-print-none" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kerjasamaList as $k)
                                @php
                                    $rowBg = '';
                                    $rowTitle = '';
                                    if ($k->tahun_berakhir) {
                                        if ($k->tahun_berakhir < date('Y')) {
                                            $rowBg = 'background-color: #fee2e2 !important;'; // Soft red
                                            $rowTitle = 'Kerjasama telah berakhir';
                                        } elseif ($k->tahun_berakhir == date('Y')) {
                                            $rowBg = 'background-color: #fef9c3 !important;'; // Soft yellow
                                            $rowTitle = 'Kerjasama akan berakhir tahun ini';
                                        }
                                    }
                                @endphp
                                <tr @if($rowBg) style="{{ $rowBg }}" title="{{ $rowTitle }}" @endif>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-center fw-bold text-dark" style="font-size: 0.85rem;">
                                        {{ $k->tahun_mou }}@if($k->tahun_berakhir) - {{ $k->tahun_berakhir }}@endif
                                    </td>
                                    <td class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        <i class="bi bi-building me-1 text-secondary"></i>{{ $k->nama_mitra }}
                                    </td>
                                    <td style="font-size: 0.85rem;"><code class="text-dark">{{ $k->nomor_mou_ubsi }}</code></td>
                                    <td style="font-size: 0.85rem;">
                                        @if ($k->nomor_mou_mitra)
                                            <code class="text-secondary">{{ $k->nomor_mou_mitra }}</code>
                                        @else
                                            <span class="text-muted small italic">-</span>
                                        @endif
                                    </td>
                                    <td style="font-size: 0.85rem;">{{ $k->ketua_mewakili }}</td>
                                    <td class="text-center">
                                        @if ($k->no_wa_mitra)
                                            @php
                                                $cleanPhone = preg_replace('/[^0-9]/', '', $k->no_wa_mitra);
                                                if (strpos($cleanPhone, '0') === 0) {
                                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-0.5" style="font-size: 0.8rem;" title="Hubungi via WhatsApp">
                                                <i class="bi bi-whatsapp me-0.5"></i> Hubungi
                                            </a>
                                        @else
                                            <span class="text-muted small italic">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            @if ($k->file_mou)
                                                <a href="{{ asset('storage/' . $k->file_mou) }}" target="_blank" class="btn btn-sm btn-success" title="Unduh MoU"><i class="bi bi-file-earmark-arrow-down text-white"></i></a>
                                            @endif
                                            <a href="{{ route('kerjasama.show', $k->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="bi bi-eye text-white"></i></a>
                                            <a href="{{ route('kerjasama.edit', $k->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('kerjasama.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data kerjasama dengan {{ $k->nama_mitra }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-briefcase display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada data kerjasama.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3 d-print-none">
                    <div class="text-muted small">
                        Menampilkan {{ $kerjasamaList->firstItem() ?? 0 }} sampai {{ $kerjasamaList->lastItem() ?? 0 }} dari {{ $kerjasamaList->total() }} data
                    </div>
                    <div class="pagination-container">
                        {{ $kerjasamaList->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Kerjasama -->
<div class="modal fade" id="tambahKerjasamaModal" tabindex="-1" aria-labelledby="tambahKerjasamaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kerjasama.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahKerjasamaModalLabel"><i class="bi bi-briefcase-fill me-2"></i>Tambah Data Kerjasama Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Tahun MoU -->
                        <div class="col-md-4 mb-3">
                            <label for="tahun_mou" class="form-label fw-semibold">Tahun Mulai <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_mou" id="tahun_mou" class="form-control @error('tahun_mou') is-invalid @enderror" value="{{ old('tahun_mou', date('Y')) }}" required min="1900" max="2100">
                            @error('tahun_mou')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tahun Berakhir -->
                        <div class="col-md-4 mb-3">
                            <label for="tahun_berakhir" class="form-label fw-semibold">Tahun Berakhir</label>
                            <input type="number" name="tahun_berakhir" id="tahun_berakhir" class="form-control @error('tahun_berakhir') is-invalid @enderror" value="{{ old('tahun_berakhir') }}" min="1900" max="2100" placeholder="Kosongkan jika tentatif">
                            @error('tahun_berakhir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Mitra -->
                        <div class="col-md-4 mb-3">
                            <label for="nama_mitra" class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                            <input type="text" name="nama_mitra" id="nama_mitra" class="form-control @error('nama_mitra') is-invalid @enderror" value="{{ old('nama_mitra') }}" required placeholder="Nama Instansi">
                            @error('nama_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <!-- Nomor MoU UBSI -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_mou_ubsi" class="form-label fw-semibold">Nomor MoU UBSI <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_mou_ubsi" id="nomor_mou_ubsi" class="form-control @error('nomor_mou_ubsi') is-invalid @enderror" value="{{ old('nomor_mou_ubsi') }}" required placeholder="Masukkan nomor MoU dari UBSI">
                            @error('nomor_mou_ubsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor MoU Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_mou_mitra" class="form-label fw-semibold">Nomor MoU Mitra</label>
                            <input type="text" name="nomor_mou_mitra" id="nomor_mou_mitra" class="form-control @error('nomor_mou_mitra') is-invalid @enderror" value="{{ old('nomor_mou_mitra') }}" placeholder="Masukkan nomor MoU dari pihak Mitra">
                            @error('nomor_mou_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ketua / Yang Mewakili -->
                        <div class="col-md-6 mb-3">
                            <label for="ketua_mewakili" class="form-label fw-semibold">Ketua / Yang Mewakili <span class="text-danger">*</span></label>
                            <input type="text" name="ketua_mewakili" id="ketua_mewakili" class="form-control @error('ketua_mewakili') is-invalid @enderror" value="{{ old('ketua_mewakili') }}" required placeholder="Nama penanggung jawab / perwakilan">
                            @error('ketua_mewakili')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No WA Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="no_wa_mitra" class="form-label fw-semibold">No. WhatsApp Mitra</label>
                            <input type="text" name="no_wa_mitra" id="no_wa_mitra" class="form-control @error('no_wa_mitra') is-invalid @enderror" value="{{ old('no_wa_mitra') }}" placeholder="Contoh: 081234567890">
                            <div class="form-text text-muted">Gunakan format angka saja (diawali 0 atau 62).</div>
                            @error('no_wa_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File MoU -->
                        <div class="col-md-6 mb-3">
                            <label for="file_mou" class="form-label fw-semibold">Dokumen MoU</label>
                            <input type="file" name="file_mou" id="file_mou" class="form-control @error('file_mou') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Format: PDF, Word, Gambar, ZIP (Maks. 5MB).</div>
                            @error('file_mou')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('tambahKerjasamaModal'));
            myModal.show();
        @endif
    });
</script>
@endpush
@endsection
