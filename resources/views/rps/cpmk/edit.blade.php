@extends('layouts.app')
@section('title', 'Edit CPMK')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data CPMK</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rps-cpmk.update', $cpmk) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode CPMK <span class="text-danger">*</span></label>
                            <input type="text" name="kode_cpmk" class="form-control @error('kode_cpmk') is-invalid @enderror" value="{{ old('kode_cpmk', $cpmk->kode_cpmk) }}" required>
                            @error('kode_cpmk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">CPL yang Dibebankan <span class="text-danger">*</span></label>
                            <select name="cpl_id" class="form-select @error('cpl_id') is-invalid @enderror" required>
                                <option value="">-- Pilih CPL --</option>
                                @foreach($cpls as $cpl)
                                <option value="{{ $cpl->id }}" {{ old('cpl_id', $cpmk->cpl_id) == $cpl->id ? 'selected' : '' }}>{{ $cpl->kode_cpl }}</option>
                                @endforeach
                            </select>
                            @error('cpl_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Matakuliah (Bisa lebih dari 1) <span class="text-danger">*</span></label>
                            @php
                                $selectedMk = old('kode_matakuliah', $cpmk->matakuliahs->pluck('kode_matakuliah')->toArray());
                            @endphp
                            <select name="kode_matakuliah[]" class="form-select select2-multiple @error('kode_matakuliah') is-invalid @enderror" multiple required>
                                @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->kode_matakuliah }}" {{ in_array($mk->kode_matakuliah, $selectedMk) ? 'selected' : '' }}>{{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }})</option>
                                @endforeach
                            </select>
                            @error('kode_matakuliah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi CPMK <span class="text-danger">*</span></label>
                            <textarea name="deskripsi_cpmk" class="form-control @error('deskripsi_cpmk') is-invalid @enderror" rows="5" required>{{ old('deskripsi_cpmk', $cpmk->deskripsi_cpmk) }}</textarea>
                            @error('deskripsi_cpmk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('rps-cpmk.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

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
        placeholder: '-- Pilih Matakuliah --',
        width: '100%'
    });
});
</script>
@endpush
