@extends('layouts.app')
@section('title', 'Edit Bahan Kajian')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Bahan Kajian</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rps-bahan-kajian.update', $bahanKajian) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Matakuliah <span class="text-danger">*</span></label>
                            <select name="kode_matakuliah" class="form-select @error('kode_matakuliah') is-invalid @enderror" required>
                                <option value="">-- Pilih Matakuliah --</option>
                                @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->kode_matakuliah }}" {{ old('kode_matakuliah', $bahanKajian->kode_matakuliah) == $mk->kode_matakuliah ? 'selected' : '' }}>{{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }})</option>
                                @endforeach
                            </select>
                            @error('kode_matakuliah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Urut <span class="text-danger">*</span></label>
                            <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror" value="{{ old('urutan', $bahanKajian->urutan) }}" min="1" required>
                            @error('urutan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Topik <span class="text-danger">*</span></label>
                            <input type="text" name="topik" class="form-control @error('topik') is-invalid @enderror" value="{{ old('topik', $bahanKajian->topik) }}" required>
                            @error('topik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Sub Topik <span class="text-muted">(Opsional)</span></label>
                            <textarea name="sub_topik" class="form-control @error('sub_topik') is-invalid @enderror" rows="3">{{ old('sub_topik', $bahanKajian->sub_topik) }}</textarea>
                            @error('sub_topik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('rps-bahan-kajian.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
