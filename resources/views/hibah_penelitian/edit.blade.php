@extends('layouts.app')

@section('title', 'Edit Hibah Penelitian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Edit Hibah Penelitian</h1>
    <a href="{{ route('hibah-penelitian.index') }}" class="btn btn-secondary">Kembali</a>
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

@php
    $existingKodes = explode(', ', $hibahPenelitian->kode_dosen);
    $existingNamas = explode(', ', $hibahPenelitian->nama_dosen);
    $existingNims = $hibahPenelitian->nim_mhs ? explode(', ', $hibahPenelitian->nim_mhs) : [];
    $existingMhsNamas = $hibahPenelitian->nama_mahasiswa ? explode(', ', $hibahPenelitian->nama_mahasiswa) : [];
@endphp

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('hibah-penelitian.update', $hibahPenelitian) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Baris 1: Jenis, TA, Pemberi -->
                <div class="col-md-4 mb-3">
                    <label for="jenis_hibah" class="form-label">Jenis Hibah <span class="text-danger">*</span></label>
                    <select name="jenis_hibah" id="jenis_hibah" class="form-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="internal" {{ old('jenis_hibah', $hibahPenelitian->jenis_hibah) == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="eksternal" {{ old('jenis_hibah', $hibahPenelitian->jenis_hibah) == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="ts_id" class="form-label">TA <span class="text-danger">*</span></label>
                    <select name="ts_id" id="ts_id" class="form-select" required>
                        <option value="">-- Pilih TA --</option>
                        @foreach ($tsList as $ts)
                            <option value="{{ $ts->id }}" {{ old('ts_id', $hibahPenelitian->ts_id) == $ts->id ? 'selected' : '' }}>{{ $ts->tahun_sekarang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="pemberi_hibah" class="form-label">Pemberi Hibah <span class="text-danger">*</span></label>
                    <input type="text" name="pemberi_hibah" id="pemberi_hibah" class="form-control" value="{{ old('pemberi_hibah', $hibahPenelitian->pemberi_hibah) }}" required>
                </div>

                <!-- Section: Dosen (Multiple) -->
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0 fw-bold">Dosen Pengaju / Anggota <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-dosen"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
                    </div>
                    <div id="dosen-rows-container">
                        @foreach ($existingKodes as $index => $kode)
                            <div class="row g-2 mb-2 dosen-row">
                                <div class="col-md-5">
                                    <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('hibah-penelitian.get-dosen', '') }}/" required>
                                        <option value="">-- Pilih Kode Dosen --</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->kode_dosen }}" {{ $dosen->kode_dosen == $kode ? 'selected' : '' }}>
                                                {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" value="{{ $existingNamas[$index] ?? '' }}" readonly required placeholder="Nama Dosen">
                                </div>
                                <div class="col-md-2">
                                    @if ($index > 0)
                                        <button type="button" class="btn btn-danger w-100 btn-remove-dosen"><i class="bi bi-trash"></i> Hapus</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section: Mahasiswa (Multiple) -->
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0 fw-bold">Mahasiswa Terlibat (Opsional)</label>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-add-mahasiswa"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>
                    </div>
                    <div id="mahasiswa-rows-container">
                        @forelse ($existingNims as $index => $nim)
                            <div class="row g-2 mb-2 mahasiswa-row">
                                <div class="col-md-5">
                                    <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" value="{{ $nim }}" data-route="{{ route('hibah-penelitian.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" value="{{ $existingMhsNamas[$index] ?? '' }}" readonly placeholder="Nama Mahasiswa">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </div>
                        @empty
                            <div class="row g-2 mb-2 mahasiswa-row">
                                <div class="col-md-5">
                                    <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('hibah-penelitian.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Baris 4: Skema, Tema, Topik -->
                <div class="col-md-4 mb-3">
                    <label for="skema_hibah" class="form-label">Skema Hibah <span class="text-danger">*</span></label>
                    <input type="text" name="skema_hibah" id="skema_hibah" class="form-control" value="{{ old('skema_hibah', $hibahPenelitian->skema_hibah) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tema_hibah" class="form-label">Tema Hibah <span class="text-danger">*</span></label>
                    <input type="text" name="tema_hibah" id="tema_hibah" class="form-control" value="{{ old('tema_hibah', $hibahPenelitian->tema_hibah) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="topik_hibah" class="form-label">Topik Hibah <span class="text-danger">*</span></label>
                    <input type="text" name="topik_hibah" id="topik_hibah" class="form-control" value="{{ old('topik_hibah', $hibahPenelitian->topik_hibah) }}" required>
                </div>

                <!-- Baris 5: Judul & Biaya -->
                <div class="col-md-8 mb-3">
                    <label for="judul" class="form-label">Judul Penelitian <span class="text-danger">*</span></label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $hibahPenelitian->judul) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="biaya" class="form-label">Biaya (Rupiah) <span class="text-danger">*</span></label>
                    <input type="number" name="biaya" id="biaya" class="form-control" value="{{ old('biaya', $hibahPenelitian->biaya) }}" required min="0">
                </div>

                <!-- Baris 6: Dokumen Pendukung -->
                <div class="col-md-4 mb-3">
                    <label for="link_proposal" class="form-label">Link Proposal</label>
                    <input type="url" name="link_proposal" id="link_proposal" class="form-control" value="{{ old('link_proposal', $hibahPenelitian->link_proposal) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="link_st" class="form-label">Link ST (Surat Tugas)</label>
                    <input type="url" name="link_st" id="link_st" class="form-control" value="{{ old('link_st', $hibahPenelitian->link_st) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="link_spk" class="form-label">Link SPK (Perjanjian)</label>
                    <input type="url" name="link_spk" id="link_spk" class="form-control" value="{{ old('link_spk', $hibahPenelitian->link_spk) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="link_luaran" class="form-label">Link Luaran</label>
                    <input type="url" name="link_luaran" id="link_luaran" class="form-control" value="{{ old('link_luaran', $hibahPenelitian->link_luaran) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="link_laporan" class="form-label">Link Laporan</label>
                    <input type="url" name="link_laporan" id="link_laporan" class="form-control" value="{{ old('link_laporan', $hibahPenelitian->link_laporan) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="link_persentasi" class="form-label">Link press Release</label>
                    <input type="url" name="link_persentasi" id="link_persentasi" class="form-control" value="{{ old('link_persentasi', $hibahPenelitian->link_persentasi) }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Fungsi untuk memasang event listener autocomplete Dosen pada baris tertentu
    function attachDosenEvents(row) {
        var select = row.querySelector('.select-kode-dosen');
        var input = row.querySelector('.input-nama-dosen');
        
        select.addEventListener('change', function() {
            var kode = this.value;
            if (kode) {
                var route = this.getAttribute('data-route');
                fetch(route + kode)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        input.value = data.nama_dosen;
                    })
                    .catch(function() {
                        input.value = '';
                    });
            } else {
                input.value = '';
            }
        });

        var removeBtn = row.querySelector('.btn-remove-dosen');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Fungsi untuk memasang event listener autocomplete Mahasiswa pada baris tertentu
    function attachMahasiswaEvents(row) {
        var nimInput = row.querySelector('.input-nim-mhs');
        var namaInput = row.querySelector('.input-nama-mahasiswa');
        
        nimInput.addEventListener('input', function() {
            var nim = this.value.trim();
            if (nim.length >= 3) {
                var route = this.getAttribute('data-route');
                fetch(route + nim)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if(data) {
                            namaInput.value = data.nama;
                        } else {
                            namaInput.value = '';
                        }
                    })
                    .catch(function() {
                        namaInput.value = '';
                    });
            } else {
                namaInput.value = '';
            }
        });

        var removeBtn = row.querySelector('.btn-remove-mahasiswa');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Daftarkan event pada baris yang sudah ada
    document.querySelectorAll('.dosen-row').forEach(function(row) {
        attachDosenEvents(row);
    });
    document.querySelectorAll('.mahasiswa-row').forEach(function(row) {
        attachMahasiswaEvents(row);
    });

    // Aksi Tambah Baris Dosen
    document.getElementById('btn-add-dosen').addEventListener('click', function() {
        var container = document.getElementById('dosen-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 dosen-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('hibah-penelitian.get-dosen', '') }}/" required>
                    <option value="">-- Pilih Kode Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->kode_dosen }}">
                            {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                        </option>
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
        container.appendChild(newRow);
        attachDosenEvents(newRow);
    });

    // Aksi Tambah Baris Mahasiswa
    document.getElementById('btn-add-mahasiswa').addEventListener('click', function() {
        var container = document.getElementById('mahasiswa-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 mahasiswa-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('hibah-penelitian.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        `;
        container.appendChild(newRow);
        attachMahasiswaEvents(newRow);
    });
</script>
@endpush
@endsection
