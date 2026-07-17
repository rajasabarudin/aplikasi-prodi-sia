@extends('layouts.app')

@section('title', 'Tambah Prestasi Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Tambah Prestasi Dosen</h1>
    <a href="{{ route('prestasi-dosen.index') }}" class="btn btn-secondary">Kembali</a>
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

<form action="{{ route('prestasi-dosen.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="kode_dosen" class="form-label">Kode Dosen <span class="text-danger">*</span></label>
            <select name="kode_dosen" id="kode_dosen" class="form-select" data-route="{{ route('prestasi-dosen.get-dosen', '') }}/" required>
                <option value="">-- Pilih Kode Dosen --</option>
                @foreach ($dosens as $dosen)
                    <option value="{{ $dosen->kode_dosen }}" {{ old('kode_dosen') == $dosen->kode_dosen ? 'selected' : '' }}>
                        {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nama_dosen" class="form-label">Nama Dosen <span class="text-danger">*</span></label>
            <input type="text" name="nama_dosen" id="nama_dosen" class="form-control" value="{{ old('nama_dosen') }}" readonly required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="nama_prestasi" class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
            <input type="text" name="nama_prestasi" id="nama_prestasi" class="form-control" value="{{ old('nama_prestasi') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
            <input type="text" name="tahun" id="tahun" class="form-control" value="{{ old('tahun') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="ts_id" class="form-label">TA <span class="text-danger">*</span></label>
            <select name="ts_id" id="ts_id" class="form-select" required>
                <option value="">-- Pilih TA --</option>
                @foreach ($tsList as $ts)
                    <option value="{{ $ts->id }}" {{ old('ts_id') == $ts->id ? 'selected' : '' }}>
                        {{ $ts->tahun_sekarang }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="penyelenggara" class="form-label">Penyelenggara <span class="text-danger">*</span></label>
            <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="level_prestasi" class="form-label">Level Prestasi <span class="text-danger">*</span></label>
            <select name="level_prestasi" id="level_prestasi" class="form-select" required>
                <option value="">-- Pilih Level --</option>
                <option value="lokal" {{ old('level_prestasi') == 'lokal' ? 'selected' : '' }}>Lokal</option>
                <option value="nasional" {{ old('level_prestasi') == 'nasional' ? 'selected' : '' }}>Nasional</option>
                <option value="internasional" {{ old('level_prestasi') == 'internasional' ? 'selected' : '' }}>Internasional</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="prestasi_diraih" class="form-label">Prestasi yang Diraih <span class="text-danger">*</span></label>
            <select name="prestasi_diraih" id="prestasi_diraih" class="form-select" required>
                <option value="">-- Pilih Prestasi --</option>
                <option value="Juara 1" {{ old('prestasi_diraih') == 'Juara 1' ? 'selected' : '' }}>Juara 1</option>
                <option value="Juara 2" {{ old('prestasi_diraih') == 'Juara 2' ? 'selected' : '' }}>Juara 2</option>
                <option value="Juara 3" {{ old('prestasi_diraih') == 'Juara 3' ? 'selected' : '' }}>Juara 3</option>
                <option value="harapan I" {{ old('prestasi_diraih') == 'harapan I' ? 'selected' : '' }}>Harapan I</option>
                <option value="harapan II" {{ old('prestasi_diraih') == 'harapan II' ? 'selected' : '' }}>Harapan II</option>
                <option value="Finalis" {{ old('prestasi_diraih') == 'Finalis' ? 'selected' : '' }}>Finalis</option>
                <option value="Hibah Eksternal" {{ old('prestasi_diraih') == 'Hibah Eksternal' ? 'selected' : '' }}>Hibah Eksternal</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="link_dokumen" class="form-label">Link Dokumen Bukti</label>
            <input type="url" name="link_dokumen" id="link_dokumen" class="form-control" value="{{ old('link_dokumen') }}" placeholder="https://example.com/sertifikat-prestasi">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>

@push('scripts')
<script>
    document.getElementById('kode_dosen').addEventListener('change', function() {
        var kode = this.value;
        if (kode) {
            var route = document.getElementById('kode_dosen').getAttribute('data-route');
            fetch(route + kode)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    document.getElementById('nama_dosen').value = data.nama_dosen;
                })
                .catch(function() {
                    document.getElementById('nama_dosen').value = '';
                });
        } else {
            document.getElementById('nama_dosen').value = '';
        }
    });
</script>
@endpush
@endsection
