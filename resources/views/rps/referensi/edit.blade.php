@extends('layouts.app')
@section('title', 'Edit Referensi')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Referensi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rps-referensi.update', $referensi) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Matakuliah <span class="text-danger">*</span></label>
                            <select name="kode_matakuliah" class="form-select @error('kode_matakuliah') is-invalid @enderror" required>
                                <option value="">-- Pilih Matakuliah --</option>
                                @foreach($matakuliahList as $mk)
                                <option value="{{ $mk->kode_matakuliah }}" {{ old('kode_matakuliah', $referensi->kode_matakuliah) == $mk->kode_matakuliah ? 'selected' : '' }}>{{ $mk->nama_matakuliah }} ({{ $mk->kode_matakuliah }})</option>
                                @endforeach
                            </select>
                            @error('kode_matakuliah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                            <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                <option value="utama" {{ old('jenis', $referensi->jenis) == 'utama' ? 'selected' : '' }}>📗 Utama</option>
                                <option value="pendukung" {{ old('jenis', $referensi->jenis) == 'pendukung' ? 'selected' : '' }}>📄 Pendukung</option>
                            </select>
                            @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-semibold">Penulis <span class="text-danger">*</span></label>
                            <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis', $referensi->penulis) }}" required>
                            @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun', $referensi->tahun) }}" min="1900" max="2099" required>
                            @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $referensi->judul) }}" required>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="{{ old('penerbit', $referensi->penerbit) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kota</label>
                            <input type="text" name="kota" class="form-control" value="{{ old('kota', $referensi->kota) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">URL</label>
                            <input type="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $referensi->url) }}">
                            @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('rps-referensi.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
