@extends('layouts.app')

@section('title', 'Edit Data Kerjasama')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Edit Data Kerjasama</h1>
                <p class="text-muted mb-0">Perbarui informasi data kerjasama.</p>
            </div>
            <a href="{{ route('kerjasama.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Kerjasama</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kerjasama.update', $kerjasama->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Tahun MoU -->
                        <div class="col-md-4 mb-3">
                            <label for="tahun_mou" class="form-label fw-semibold">Tahun Mulai <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_mou" id="tahun_mou" class="form-control @error('tahun_mou') is-invalid @enderror" value="{{ old('tahun_mou', $kerjasama->tahun_mou) }}" required min="1900" max="2100">
                            @error('tahun_mou')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tahun Berakhir -->
                        <div class="col-md-4 mb-3">
                            <label for="tahun_berakhir" class="form-label fw-semibold">Tahun Berakhir</label>
                            <input type="number" name="tahun_berakhir" id="tahun_berakhir" class="form-control @error('tahun_berakhir') is-invalid @enderror" value="{{ old('tahun_berakhir', $kerjasama->tahun_berakhir) }}" min="1900" max="2100" placeholder="Kosongkan jika tentatif">
                            @error('tahun_berakhir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Mitra -->
                        <div class="col-md-4 mb-3">
                            <label for="nama_mitra" class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                            <input type="text" name="nama_mitra" id="nama_mitra" class="form-control @error('nama_mitra') is-invalid @enderror" value="{{ old('nama_mitra', $kerjasama->nama_mitra) }}" required placeholder="Nama Instansi">
                            @error('nama_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor MoU UBSI -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_mou_ubsi" class="form-label fw-semibold">Nomor MoU UBSI <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_mou_ubsi" id="nomor_mou_ubsi" class="form-control @error('nomor_mou_ubsi') is-invalid @enderror" value="{{ old('nomor_mou_ubsi', $kerjasama->nomor_mou_ubsi) }}" required placeholder="Masukkan nomor MoU dari UBSI">
                            @error('nomor_mou_ubsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor MoU Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_mou_mitra" class="form-label fw-semibold">Nomor MoU Mitra</label>
                            <input type="text" name="nomor_mou_mitra" id="nomor_mou_mitra" class="form-control @error('nomor_mou_mitra') is-invalid @enderror" value="{{ old('nomor_mou_mitra', $kerjasama->nomor_mou_mitra) }}" placeholder="Masukkan nomor MoU dari pihak Mitra">
                            @error('nomor_mou_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ketua / Yang Mewakili -->
                        <div class="col-md-6 mb-3">
                            <label for="ketua_mewakili" class="form-label fw-semibold">Ketua / Yang Mewakili <span class="text-danger">*</span></label>
                            <input type="text" name="ketua_mewakili" id="ketua_mewakili" class="form-control @error('ketua_mewakili') is-invalid @enderror" value="{{ old('ketua_mewakili', $kerjasama->ketua_mewakili) }}" required placeholder="Nama penanggung jawab / perwakilan">
                            @error('ketua_mewakili')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No WA Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="no_wa_mitra" class="form-label fw-semibold">No. WhatsApp Mitra</label>
                            <input type="text" name="no_wa_mitra" id="no_wa_mitra" class="form-control @error('no_wa_mitra') is-invalid @enderror" value="{{ old('no_wa_mitra', $kerjasama->no_wa_mitra) }}" placeholder="Contoh: 081234567890">
                            <div class="form-text text-muted">Gunakan format angka saja (diawali 0 atau 62).</div>
                            @error('no_wa_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File MoU -->
                        <div class="col-md-6 mb-3">
                            <label for="file_mou" class="form-label fw-semibold">Dokumen MoU</label>
                            @if ($kerjasama->file_mou)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $kerjasama->file_mou) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-check me-1"></i> Lihat Dokumen Saat Ini
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="file_mou" id="file_mou" class="form-control @error('file_mou') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah dokumen. Format: PDF, Word, Gambar, ZIP.</div>
                            @error('file_mou')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('kerjasama.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
