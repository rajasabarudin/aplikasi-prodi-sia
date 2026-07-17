@extends('layouts.app')

@section('title', 'Tambah PKM Dosen')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Tambah PKM Dosen</h1>
        <a href="{{ route('pkm-dosen.index') }}" class="btn btn-secondary">Kembali</a>
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

    <form action="{{ route('pkm-dosen.store') }}" method="POST">
        @csrf
        <div class="card p-4 mb-4">
            <h5 class="card-title mb-3 text-primary"><i class="bi bi-people-fill"></i> Anggota Dosen Pelaksana</h5>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0 fw-bold">Dosen <span class="text-danger">*</span></label>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-dosen"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
                </div>
                <div id="dosen-rows-container">
                    <div class="row g-2 mb-2 dosen-row">
                        <div class="col-md-5">
                            <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('pkm-dosen.get-dosen', '') }}/" required>
                                <option value="">-- Pilih Kode Dosen --</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->kode_dosen }}">{{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" readonly required placeholder="Nama Dosen">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <h5 class="card-title mb-3 text-primary"><i class="bi bi-journal-bookmark-fill"></i> Detail PKM</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tema_pkm" class="form-label">Tema PKM <span class="text-danger">*</span></label>
                    <input type="text" name="tema_pkm" id="tema_pkm" class="form-control" value="{{ old('tema_pkm') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mitra" class="form-label">Mitra <span class="text-danger">*</span></label>
                    <input type="text" name="mitra" id="mitra" class="form-control" value="{{ old('mitra') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jenis_pkm" class="form-label">Jenis PKM <span class="text-danger">*</span></label>
                    <select name="jenis_pkm" id="jenis_pkm" class="form-select" required>
                        <option value="">-- Pilih Jenis PKM --</option>
                        <option value="Mitra Non Produktif" {{ old('jenis_pkm') == 'Mitra Non Produktif' ? 'selected' : '' }}>Mitra Non Produktif</option>
                        <option value="Mitra Produktif" {{ old('jenis_pkm') == 'Mitra Produktif' ? 'selected' : '' }}>Mitra Produktif</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sumber_iptek" class="form-label">Sumber IPTEK <span class="text-danger">*</span></label>
                    <input type="text" name="sumber_iptek" id="sumber_iptek" class="form-control" value="{{ old('sumber_iptek') }}" required>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <h5 class="card-title mb-3 text-primary"><i class="bi bi-people-fill"></i> Mahasiswa (Opsional)</h5>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0 fw-bold">Anggota Mahasiswa</label>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-add-mahasiswa"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>
                </div>
                <div id="mahasiswa-rows-container">
                    <div class="row g-2 mb-2 mahasiswa-row">
                        <div class="col-md-5">
                            <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('pkm-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-4">
            <h5 class="card-title mb-3 text-primary"><i class="bi bi-calendar"></i> TA & Dokumen</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ts_id" class="form-label">TA <span class="text-danger">*</span></label>
                    <select name="ts_id" id="ts_id" class="form-select" required>
                        <option value="">-- Pilih TA --</option>
                        @foreach ($tsList as $ts)
                            <option value="{{ $ts->id }}" {{ old('ts_id') == $ts->id ? 'selected' : '' }}>{{ $ts->tahun_sekarang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="link_dokumen" class="form-label">Link Dokumen</label>
                    <input type="url" name="link_dokumen" id="link_dokumen" class="form-control" value="{{ old('link_dokumen') }}" placeholder="https://example.com/dokumen">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="link_publikasi" class="form-label">Link Publikasi</label>
                    <input type="url" name="link_publikasi" id="link_publikasi" class="form-control" value="{{ old('link_publikasi') }}" placeholder="https://example.com/publikasi">
                </div>
            </div>
        </div>

        <div class="mb-4 text-end">
            <button type="submit" class="btn btn-primary btn-lg px-5"><i class="bi bi-save"></i> Simpan Data</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function attachDosenEvents(row) {
        var select = row.querySelector('.select-kode-dosen');
        var input = row.querySelector('.input-nama-dosen');
        select.addEventListener('change', function() {
            var kode = this.value;
            if (kode) {
                fetch(this.getAttribute('data-route') + kode)
                    .then(function(res) { return res.json(); })
                    .then(function(data) { input.value = data.nama_dosen; })
                    .catch(function() { input.value = ''; });
            } else {
                input.value = '';
            }
        });
        var btn = row.querySelector('.btn-remove-dosen');
        if (btn) btn.addEventListener('click', function() { row.remove(); });
    }

    function attachMahasiswaEvents(row) {
        var nimInput = row.querySelector('.input-nim-mhs');
        var namaInput = row.querySelector('.input-nama-mahasiswa');
        nimInput.addEventListener('input', function() {
            var nim = this.value.trim();
            if (nim.length >= 3) {
                fetch(this.getAttribute('data-route') + nim)
                    .then(function(res) { return res.json(); })
                    .then(function(data) { namaInput.value = data ? data.nama : ''; })
                    .catch(function() { namaInput.value = ''; });
            } else {
                namaInput.value = '';
            }
        });
        var btn = row.querySelector('.btn-remove-mahasiswa');
        if (btn) btn.addEventListener('click', function() { row.remove(); });
    }

    document.querySelectorAll('.dosen-row').forEach(attachDosenEvents);
    document.querySelectorAll('.mahasiswa-row').forEach(attachMahasiswaEvents);

    document.getElementById('btn-add-dosen').addEventListener('click', function() {
        var c = document.getElementById('dosen-rows-container');
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 dosen-row';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('pkm-dosen.get-dosen', '') }}/" required>
                    <option value="">-- Pilih Kode Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->kode_dosen }}">{{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" readonly required placeholder="Nama Dosen">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-dosen"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        `;
        c.appendChild(row);
        attachDosenEvents(row);
    });

    document.getElementById('btn-add-mahasiswa').addEventListener('click', function() {
        var c = document.getElementById('mahasiswa-rows-container');
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 mahasiswa-row';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('pkm-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        `;
        c.appendChild(row);
        attachMahasiswaEvents(row);
    });
</script>
@endpush
@endsection
