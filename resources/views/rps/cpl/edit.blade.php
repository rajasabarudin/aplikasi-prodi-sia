@extends('layouts.app')
@section('title', 'Edit CPL')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data CPL</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rps-cpl.update', $cpl) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode CPL <span class="text-danger">*</span></label>
                        <input type="text" name="kode_cpl" class="form-control @error('kode_cpl') is-invalid @enderror" value="{{ old('kode_cpl', $cpl->kode_cpl) }}" required>
                        @error('kode_cpl')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi CPL <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_cpl" class="form-control @error('deskripsi_cpl') is-invalid @enderror" rows="5" required>{{ old('deskripsi_cpl', $cpl->deskripsi_cpl) }}</textarea>
                        @error('deskripsi_cpl')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('rps-cpl.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
