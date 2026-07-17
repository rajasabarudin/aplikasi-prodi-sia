@extends('layouts.app')

@section('title', 'Tambah Penelitian Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Tambah Penelitian Dosen</h1>
    <a href="{{ route('penelitian-dosen.index') }}" class="btn btn-secondary">Kembali</a>
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

<form action="{{ route('penelitian-dosen.store') }}" method="POST">
    @csrf
    <div class="card p-4 mb-4">
        <h5 class="card-title mb-3 text-primary"><i class="bi bi-person-fill"></i> Informasi TA</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="ts_id" class="form-label">Pilihan TA <span class="text-danger">*</span></label>
                <select name="ts_id" id="ts_id" class="form-select" required>
                    <option value="">-- Pilih TA --</option>
                    @foreach ($tsList as $ts)
                        <option value="{{ $ts->id }}" {{ old('ts_id') == $ts->id ? 'selected' : '' }}>
                            {{ $ts->tahun_sekarang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="card-title mb-3 text-primary"><i class="bi bi-people-fill"></i> Anggota Peneliti</h5>
        
        <!-- Section: Dosen (Multiple) -->
        <div class="mb-4 pb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold">Dosen Pelaksana <span class="text-danger">*</span></label>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-dosen"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
            </div>
            <div id="dosen-rows-container">
                <div class="row g-2 mb-2 dosen-row">
                    <div class="col-md-5">
                        <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('penelitian-dosen.get-dosen', '') }}/" required>
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
                        <!-- Baris pertama tidak bisa dihapus -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Mahasiswa (Multiple) -->
        <div class="mb-4 pb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold">Anggota Mahasiswa (Opsional)</label>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-add-mahasiswa"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>
            </div>
            <div id="mahasiswa-rows-container">
                <div class="row g-2 mb-2 mahasiswa-row">
                    <div class="col-md-5">
                        <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('penelitian-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Mitra (Multiple) -->
        <div class="mb-0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold">Anggota Mitra (Opsional)</label>
                <button type="button" class="btn btn-sm btn-outline-dark" id="btn-add-mitra"><i class="bi bi-plus-circle"></i> Tambah Mitra</button>
            </div>
            <div id="mitra-rows-container">
                <div class="row g-2 mb-2 mitra-row">
                    <div class="col-md-10">
                        <input type="text" name="anggota_mitra[]" class="form-control" placeholder="Masukkan Nama Mitra...">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 btn-remove-mitra"><i class="bi bi-trash"></i> Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="card-title mb-3 text-primary"><i class="bi bi-journal-bookmark-fill"></i> Detail Jurnal & Penelitian</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="jenis_jurnal" class="form-label">Jenis Jurnal <span class="text-danger">*</span></label>
                <select name="jenis_jurnal" id="jenis_jurnal" class="form-select" required>
                    <option value="">-- Pilih Jenis Jurnal --</option>
                    <option value="Jurnal Nasional">Jurnal Nasional</option>
                    <option value="Jurnal Nasional Terakreditasi (SINTA)">Jurnal Nasional Terakreditasi (SINTA)</option>
                    <option value="Jurnal Internasional">Jurnal Internasional</option>
                    <option value="Jurnal Internasional Bereputasi (Scopus/WoS)">Jurnal Internasional Bereputasi (Scopus/WoS)</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="jenis_penelitian" class="form-label">Jenis Penelitian <span class="text-danger">*</span></label>
                <input type="text" name="jenis_penelitian" id="jenis_penelitian" class="form-control" placeholder="Contoh: Mandiri, Kerja Sama, Institusi" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama_jurnal" class="form-label">Nama Jurnal <span class="text-danger">*</span></label>
                <input type="text" name="nama_jurnal" id="nama_jurnal" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="link_jurnal" class="form-label">Link Jurnal</label>
                <input type="url" name="link_jurnal" id="link_jurnal" class="form-control" placeholder="https://example.com/jurnal">
            </div>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <h5 class="card-title mb-3 text-primary"><i class="bi bi-link-45deg"></i> Tautan / Link Berkas Pendukung</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="berkas_sertifikat" class="form-label">Link Sertifikat</label>
                <input type="url" name="berkas_sertifikat" id="berkas_sertifikat" class="form-control" placeholder="https://example.com/sertifikat">
            </div>
            <div class="col-md-4 mb-3">
                <label for="berkas_paper" class="form-label">Link Paper</label>
                <input type="url" name="berkas_paper" id="berkas_paper" class="form-control" placeholder="https://example.com/paper">
            </div>
            <div class="col-md-4 mb-3">
                <label for="proposal" class="form-label">Link Proposal</label>
                <input type="url" name="proposal" id="proposal" class="form-control" placeholder="https://example.com/proposal">
            </div>
            <div class="col-md-6 mb-3">
                <label for="laporan" class="form-label">Link Laporan</label>
                <input type="url" name="laporan" id="laporan" class="form-control" placeholder="https://example.com/laporan">
            </div>
            <div class="col-md-6 mb-3">
                <label for="lainnya" class="form-label">Link Lainnya</label>
                <input type="url" name="lainnya" id="lainnya" class="form-control" placeholder="https://example.com/lainnya">
            </div>
        </div>
    </div>

    <div class="mb-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg px-5"><i class="bi bi-save"></i> Simpan Data</button>
    </div>
</form>

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

    // Fungsi pasang event Hapus Mitra
    function attachMitraEvents(row) {
        var removeBtn = row.querySelector('.btn-remove-mitra');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Daftarkan event pada baris awal
    document.querySelectorAll('.dosen-row').forEach(function(row) {
        attachDosenEvents(row);
    });
    document.querySelectorAll('.mahasiswa-row').forEach(function(row) {
        attachMahasiswaEvents(row);
    });
    document.querySelectorAll('.mitra-row').forEach(function(row) {
        attachMitraEvents(row);
    });

    // Aksi Tambah Baris Dosen
    document.getElementById('btn-add-dosen').addEventListener('click', function() {
        var container = document.getElementById('dosen-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 dosen-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('penelitian-dosen.get-dosen', '') }}/" required>
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
                <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('penelitian-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
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

    // Aksi Tambah Baris Mitra
    document.getElementById('btn-add-mitra').addEventListener('click', function() {
        var container = document.getElementById('mitra-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 mitra-row';
        newRow.innerHTML = `
            <div class="col-md-10">
                <input type="text" name="anggota_mitra[]" class="form-control" placeholder="Masukkan Nama Mitra...">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mitra"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        `;
        container.appendChild(newRow);
        attachMitraEvents(newRow);
    });
</script>
@endpush
@endsection
