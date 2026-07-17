@extends('layouts.app')
@section('title', 'Data CPL')
@section('content')
<div class="row">
    <div class="col-lg-3 mb-4 d-print-none">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background:#000!important">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik CPL</h6>
            </div>
            <div class="card-body">
                <div class="text-center py-3 mb-3 rounded" style="background:linear-gradient(135deg,#dc2626,#b91c1c)">
                    <span class="text-white-50 small d-block">Total CPL</span>
                    <h2 class="fw-bold text-white mb-0">{{ $cpls->count() }}</h2>
                </div>
                <div class="alert alert-danger border-0 small mb-0" style="background:#fef2f2;color:#991b1b">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    CPL adalah Capaian Pembelajaran Lulusan yang menjadi dasar penyusunan CPMK setiap matakuliah.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data CPL</h1>
                <p class="text-muted mb-0">Capaian Pembelajaran Lulusan Program Studi</p>
            </div>
            <button class="btn btn-danger d-print-none" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1"></i>Tambah CPL
            </button>
        </div>

        @if(session('success'))<div class="alert alert-success d-print-none">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger d-print-none"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width:5%">No</th>
                            <th style="width:15%">Kode CPL</th>
                            <th>Deskripsi CPL</th>
                            <th style="width:10%">CPMK</th>
                            <th class="text-center d-print-none" style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cpls as $c)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td><span class="badge bg-danger fs-6 px-3">{{ $c->kode_cpl }}</span></td>
                            <td>{{ $c->deskripsi_cpl }}</td>
                            <td class="text-center"><span class="badge bg-secondary">{{ $c->cpmks->count() }}</span></td>
                            <td class="text-center d-print-none">
                                <div class="btn-group">
                                    <a href="{{ route('rps-cpl.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('rps-cpl.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus CPL {{ $c->kode_cpl }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data CPL.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('rps-cpl.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Tambah CPL</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode CPL <span class="text-danger">*</span></label>
                        <input type="text" name="kode_cpl" class="form-control" placeholder="Contoh: CPL01, CPL04" required>
                        <div class="form-text">Format: CPL01, CPL02, dst.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi CPL <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_cpl" class="form-control" rows="4" placeholder="Tuliskan deskripsi capaian pembelajaran lulusan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan</button>
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
@endsection
