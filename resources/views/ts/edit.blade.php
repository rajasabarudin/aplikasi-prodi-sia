@extends('layouts.app')

@section('title', 'Edit TA')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Edit TA (Tahun Akademik)</h1>
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

<form action="{{ route('ts.update', $ts) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="tahun_sekarang" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
        <input type="text" name="tahun_sekarang" id="tahun_sekarang" class="form-control" value="{{ old('tahun_sekarang', $ts->tahun_sekarang) }}" placeholder="Contoh: 2025/2026" required>
    </div>
    <div class="mb-3">
        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
        <select name="semester" id="semester" class="form-select" required>
            <option value="">-- Pilih Semester --</option>
            <option value="Gasal" {{ old('semester', $ts->semester) == 'Gasal' ? 'selected' : '' }}>Gasal</option>
            <option value="Genap" {{ old('semester', $ts->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="label_ts" class="form-label">Label TS (Opsional)</label>
        <input type="text" name="label_ts" id="label_ts" class="form-control" value="{{ old('label_ts', $ts->label_ts) }}" placeholder="Contoh: TS, TS-1, TS-2">
        <small class="text-muted">Isi jika Tahun Akademik ini ingin dijadikan referensi TS tertentu.</small>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
