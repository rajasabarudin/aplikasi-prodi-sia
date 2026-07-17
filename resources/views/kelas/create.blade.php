@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Tambah Kelas</h1>
    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
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

<form action="{{ route('kelas.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="nama_kelas" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
        <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" value="{{ old('nama_kelas') }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
