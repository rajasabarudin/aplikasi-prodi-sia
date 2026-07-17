@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Edit Mahasiswa</h1>
    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
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

<form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
        <input type="text" name="nim" id="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $mahasiswa->nama) }}" required>
    </div>
    <div class="mb-3">
        <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
        <select name="kelas" id="kelas" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelasList as $k)
                <option value="{{ $k->nama_kelas }}" {{ old('kelas', $mahasiswa->kelas) == $k->nama_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
