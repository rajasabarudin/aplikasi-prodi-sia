@extends('layouts.app')
@section('title', 'Referensi Matakuliah')
@section('content')
<div class="row">
    <div class="col-lg-3 mb-4 d-print-none">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background:#000!important">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik</h6>
            </div>
            <div class="card-body">
                <div class="text-center py-3 mb-3 rounded" style="background:linear-gradient(135deg,#059669,#047857)">
                    <span class="text-white-50 small d-block">Total Referensi</span>
                    <h2 class="fw-bold text-white mb-0">{{ $referensis->count() }}</h2>
                </div>
                <div class="row g-2 mb-3 text-center">
                    <div class="col-6">
                        <div class="bg-white border rounded p-2 shadow-sm">
                            <small class="text-muted d-block">Utama</small>
                            <h5 class="fw-bold text-success mb-0">{{ $referensis->where('jenis','utama')->count() }}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white border rounded p-2 shadow-sm">
                            <small class="text-muted d-block">Pendukung</small>
                            <h5 class="fw-bold text-secondary mb-0">{{ $referensis->where('jenis','pendukung')->count() }}</h5>
                        </div>
                    </div>
                </div>
                <div class="alert alert-success border-0 small mb-0" style="background:#f0fdf4;color:#14532d">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Referensi terdiri dari pustaka utama (buku teks wajib) dan pendukung (jurnal, artikel) per matakuliah.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Referensi Matakuliah</h1>
                <p class="text-muted mb-0">Daftar pustaka utama & pendukung per matakuliah</p>
            </div>
            <button class="btn btn-success d-print-none" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1"></i>Tambah Referensi
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
                            <th style="width:15%">Matakuliah</th>
                            <th style="width:10%">Jenis</th>
                            <th>Penulis / Tahun / Judul</th>
                            <th style="width:15%">Penerbit</th>
                            <th class="text-center d-print-none" style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referensis as $r)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold text-dark small">{{ $r->matakuliah?->nama_matakuliah ?? $r->kode_matakuliah }}</span>
                                <small class="text-muted d-block">{{ $r->kode_matakuliah }}</small>
                            </td>
                            <td>
                                @if($r->jenis === 'utama')
                                    <span class="badge bg-success">Utama</span>
                                @else
                                    <span class="badge bg-secondary">Pendukung</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-dark small">{{ $r->penulis }} ({{ $r->tahun }})</span>
                                <div class="fst-italic text-muted small">{{ $r->judul }}</div>
                                @if($r->url)
                                <a href="{{ $r->url }}" target="_blank" class="small text-primary"><i class="bi bi-link-45deg me-1"></i>Lihat URL</a>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $r->penerbit ?? '-' }}{{ $r->kota ? ', '.$r->kota : '' }}</td>
                            <td class="text-center d-print-none">
                                <div class="btn-group">
                                    <a href="{{ route('rps-referensi.edit', $r) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('rps-referensi.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus referensi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data referensi.</td></tr>
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
            <form action="{{ route('rps-referensi.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Referensi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Matakuliah <span class="text-danger">*</span></label>
                            <select name="kode_matakuliah" class="form-select" required>
                                <option value="">-- Pilih Matakuliah --</option>
                                @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->kode_matakuliah }}">{{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select" required>
                                <option value="utama">📗 Utama</option>
                                <option value="pendukung">📄 Pendukung</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">Penulis <span class="text-danger">*</span></label>
                            <input type="text" name="penulis" class="form-control" placeholder="Contoh: Hidayat, Miwan K, dkk." required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control" placeholder="2024" min="1900" max="2099" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" placeholder="Judul buku/jurnal/artikel" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penerbit <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="penerbit" class="form-control" placeholder="Contoh: Graha Ilmu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kota <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="kota" class="form-control" placeholder="Contoh: Yogyakarta">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">URL <span class="text-muted">(Opsional)</span></label>
                            <input type="url" name="url" class="form-control" placeholder="https://doi.org/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
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
