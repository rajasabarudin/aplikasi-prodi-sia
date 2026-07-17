@extends('layouts.app')

@section('title', 'Tambah TA')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Tambah TA (Tahun Akademik)</h1>
    <a href="{{ route('ts.index') }}" class="btn btn-secondary">Kembali</a>
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

<form action="{{ route('ts.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="tahun_sekarang" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
        <input type="text" name="tahun_sekarang" id="tahun_sekarang" class="form-control" value="{{ old('tahun_sekarang') }}" placeholder="Contoh: 2025/2026" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
