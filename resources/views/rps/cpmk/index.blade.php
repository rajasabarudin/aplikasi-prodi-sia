@extends('layouts.app')
@section('title', 'Data CPMK')
@section('content')
<div class="row">
    <div class="col-lg-3 mb-4 d-print-none">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background:#000!important">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik CPMK</h6>
            </div>
            <div class="card-body">
                <div class="text-center py-3 mb-3 rounded" style="background:linear-gradient(135deg,#d97706,#b45309)">
                    <span class="text-white-50 small d-block">Total CPMK</span>
                    <h2 class="fw-bold text-white mb-0">{{ $totalCpmk }}</h2>
                </div>
                <div class="mb-3">
                    <div class="fw-semibold text-dark mb-2 small"><i class="bi bi-diagram-3 me-1 text-warning"></i>Distribusi CPL</div>
                    @foreach($distribusiCpl as $distCpl)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <small class="text-dark fw-semibold">{{ $distCpl->kode_cpl }}</small>
                        <span class="badge bg-warning text-dark">{{ $distCpl->cpmks_count }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="alert alert-warning border-0 small mb-0" style="background:#fffbeb;color:#92400e">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    CPMK adalah Capaian Pembelajaran Matakuliah — turunan dari CPL yang dibebankan ke tiap mata kuliah.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data CPMK</h1>
                <p class="text-muted mb-0">Capaian Pembelajaran Matakuliah</p>
            </div>
            <button class="btn btn-warning d-print-none" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1"></i>Tambah CPMK
            </button>
        </div>

        @if(session('success'))<div class="alert alert-success d-print-none">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger d-print-none"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

        <div class="card shadow-sm border-0 mb-3 d-print-none">
            <div class="card-body py-2">
                <form action="{{ route('rps-cpmk.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-2">
                        <select name="limit" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('limit') == '10' ? 'selected' : '' }}>10 Baris</option>
                            <option value="50" {{ request('limit') == '50' ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('limit') == '100' ? 'selected' : '' }}>100 Baris</option>
                            <option value="all" {{ request('limit') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>
                    </div>
                    <div class="col-md-6 ms-auto">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari Kode atau Deskripsi CPMK..." value="{{ request('search') }}">
                            <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i> Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width:5%">No</th>
                            <th style="width:15%">Kode CPMK</th>
                            <th style="width:15%">CPL</th>
                            <th style="width:20%">Matakuliah</th>
                            <th>Deskripsi CPMK</th>
                            <th class="text-center d-print-none" style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cpmks as $c)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ ($cpmks->currentPage() - 1) * $cpmks->perPage() + $loop->iteration }}</td>
                            <td><span class="badge bg-warning text-dark fs-6 px-2">{{ $c->kode_cpmk }}</span></td>
                            <td><span class="badge bg-danger">{{ $c->cpl?->kode_cpl ?? '-' }}</span></td>
                            <td>
                                @if($c->matakuliahs->count() > 0)
                                <div style="display: grid; grid-template-rows: repeat(5, auto); grid-auto-flow: column; gap: 0.5rem; justify-content: start; overflow-x: auto; padding-bottom: 5px;">
                                    @foreach($c->matakuliahs as $mk)
                                    <div class="border rounded px-2 py-1 bg-light" style="min-width: 180px;">
                                        <span class="fw-semibold text-dark small d-block text-truncate" style="max-width: 170px;" title="{{ $mk->nama_matakuliah }}">{{ $mk->nama_matakuliah }}</span>
                                        <small class="text-muted">{{ $mk->kode_matakuliah }}</small>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="small">{{ $c->deskripsi_cpmk }}</td>
                            <td class="text-center d-print-none">
                                <div class="btn-group">
                                    <a href="{{ route('rps-cpmk.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('rps-cpmk.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus CPMK {{ $c->kode_cpmk }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data CPMK.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cpmks instanceof \Illuminate\Pagination\LengthAwarePaginator && $cpmks->hasPages())
            <div class="card-footer bg-white pt-3 pb-0 d-print-none">
                {{ $cpmks->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('rps-cpmk.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Tambah CPMK</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode CPMK <span class="text-danger">*</span></label>
                            <input type="text" name="kode_cpmk" class="form-control" placeholder="Contoh: CPMK.04.1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">CPL yang Dibebankan <span class="text-danger">*</span></label>
                            <select name="cpl_id" class="form-select" required>
                                <option value="">-- Pilih CPL --</option>
                                @foreach($cpls as $cpl)
                                <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Matakuliah (Bisa lebih dari 1) <span class="text-danger">*</span></label>
                            <select name="kode_matakuliah[]" class="form-select select2-multiple" multiple required>
                                @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->kode_matakuliah }}">{{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi CPMK <span class="text-danger">*</span></label>
                            <textarea name="deskripsi_cpmk" class="form-control" rows="4" placeholder="Tuliskan deskripsi capaian pembelajaran matakuliah..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>document.addEventListener('DOMContentLoaded',()=>new bootstrap.Modal(document.getElementById('modalTambah')).show())</script>
@endpush
@endif

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modalTambah'),
        placeholder: '-- Pilih Matakuliah --',
        width: '100%'
    });
});
</script>
@endpush

@endsection
