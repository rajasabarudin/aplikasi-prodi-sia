@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Tambah Dosen</h1>
    <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('dosen.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kode_dosen" class="form-label">Kode Dosen <span class="text-danger">*</span></label>
            <input type="text" name="kode_dosen" id="kode_dosen" class="form-control" value="{{ old('kode_dosen') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nama_dosen" class="form-label">Nama Dosen <span class="text-danger">*</span></label>
            <input type="text" name="nama_dosen" id="nama_dosen" class="form-control" value="{{ old('nama_dosen') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="homebase_dosen" class="form-label">Homebase Dosen</label>
            <input type="text" name="homebase_dosen" id="homebase_dosen" class="form-control" value="{{ old('homebase_dosen') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="nidn" class="form-label">NIDN</label>
            <input type="text" name="nidn" id="nidn" class="form-control" value="{{ old('nidn') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="nuptk" class="form-label">NUPTK</label>
            <input type="text" name="nuptk" id="nuptk" class="form-control" value="{{ old('nuptk') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="pendidikan" class="form-label">Pendidikan</label>
            <input type="text" name="pendidikan" id="pendidikan" class="form-control" value="{{ old('pendidikan') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="gelar" class="form-label">Gelar</label>
            <input type="text" name="gelar" id="gelar" class="form-control" value="{{ old('gelar') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="jfa" class="form-label">JFA</label>
            <input type="text" name="jfa" id="jfa" class="form-control" value="{{ old('jfa') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="kepangkatan" class="form-label">Kepangkatan</label>
            <input type="text" name="kepangkatan" id="kepangkatan" class="form-control" value="{{ old('kepangkatan') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="keterangan_serdos" class="form-label">Keterangan Serdos</label>
            <input type="text" name="keterangan_serdos" id="keterangan_serdos" class="form-control" value="{{ old('keterangan_serdos') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="jenjang" class="form-label">Jenjang</label>
            <input type="text" name="jenjang" id="jenjang" class="form-control" value="{{ old('jenjang') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="kondisi_sisfo" class="form-label">Kondisi Sisfo</label>
            <input type="text" name="kondisi_sisfo" id="kondisi_sisfo" class="form-control" value="{{ old('kondisi_sisfo') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="kondisi_pddikti" class="form-label">Kondisi PDDikti</label>
            <input type="text" name="kondisi_pddikti" id="kondisi_pddikti" class="form-control" value="{{ old('kondisi_pddikti') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="foto" class="form-label">Foto Profil Dosen</label>
            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
            <div class="form-text text-muted">Format gambar: jpeg, png, jpg, gif (Maks. 2MB).</div>
            @error('foto')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
