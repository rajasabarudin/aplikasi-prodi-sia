@extends('layouts.app')

@section('title', 'Edit Kegiatan Tendik')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Edit Kegiatan Tendik</h4>
        <a href="{{ request('redirect_to', route('kegiatan-tendik.index')) }}" class="btn btn-secondary">Kembali</a>
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



    <form action="{{ route('kegiatan-tendik.update', $KegiatanTendik->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if(request('redirect_to'))
            <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nip_nik" class="form-label">Kode Tendik <span class="text-danger">*</span></label>
                <select name="nip_nik" id="nip_nik" class="form-select" data-route="{{ route('kegiatan-tendik.get-tendik', '') }}/" required>
                    <option value="">-- Pilih Kode Tendik --</option>
                    @foreach ($Tendiks as $Tendik)
                        <option value="{{ $Tendik->nip_nik }}" {{ old('nip_nik', $KegiatanTendik->nip_nik) == $Tendik->nip_nik ? 'selected' : '' }}>
                            {{ $Tendik->nip_nik }} - {{ $Tendik->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama_tendik" class="form-label">Nama Tendik <span class="text-danger">*</span></label>
                <input type="text" name="nama_tendik" id="nama_tendik" class="form-control" value="{{ old('nama_tendik', $KegiatanTendik->nama_lengkap) }}" readonly required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nama_kegiatan" class="form-label">Nama kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $KegiatanTendik->nama_kegiatan) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="text" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', $KegiatanTendik->tahun) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="ts_id" class="form-label">TA <span class="text-danger">*</span></label>
                <select name="ts_id" id="ts_id" class="form-select" required>
                    <option value="">-- Pilih TA --</option>
                    @foreach ($tsList as $ts)
                        <option value="{{ $ts->id }}" {{ old('ts_id', $KegiatanTendik->ts_id) == $ts->id ? 'selected' : '' }}>
                            {{ $ts->tahun_sekarang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="penyelenggara" class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" value="{{ old('penyelenggara', $KegiatanTendik->penyelenggara) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="jenis" class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                <select name="jenis" id="jenis" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Internal" {{ old('jenis', $KegiatanTendik->jenis) == 'Internal' ? 'selected' : '' }}>Internal</option>
                    <option value="Eksternal" {{ old('jenis', $KegiatanTendik->jenis) == 'Eksternal' ? 'selected' : '' }}>Eksternal</option>
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label for="link_dokumen" class="form-label">Link Dokumen Bukti</label>
                <input type="url" name="link_dokumen" id="link_dokumen" class="form-control"
                       value="{{ old('link_dokumen', $KegiatanTendik->link_dokumen) }}"
                       placeholder="https://example.com/sertifikat-kegiatan">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

            <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
            <input type="text" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', $KegiatanTendik->tahun) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="ts_id" class="form-label">TA <span class="text-danger">*</span></label>
            <select name="ts_id" id="ts_id" class="form-select" required>
                <option value="">-- Pilih TA --</option>
                @foreach ($tsList as $ts)
                    <option value="{{ $ts->id }}" {{ old('ts_id', $KegiatanTendik->ts_id) == $ts->id ? 'selected' : '' }}>
                        {{ $ts->tahun_sekarang }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="penyelenggara" class="form-label">Penyelenggara <span class="text-danger">*</span></label>
            <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" value="{{ old('penyelenggara', $KegiatanTendik->penyelenggara) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="jenis" class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Internal" {{ old('jenis', $KegiatanTendik->jenis) == 'Internal' ? 'selected' : '' }}>Internal (Kegiatan Prodi)</option>
                <option value="Eksternal" {{ old('jenis', $KegiatanTendik->jenis) == 'Eksternal' ? 'selected' : '' }}>Eksternal (Luar Prodi)</option>
            </select>
        </div>
        <div class="col-md-12 mb-3" id="wrapper_kegiatan_internal" style="{{ old('jenis', $KegiatanTendik->jenis) == 'Internal' ? 'display: block;' : 'display: none;' }}">
            <label for="kegiatan_prodi_id" class="form-label">Pilih Kegiatan Internal <span class="text-danger">*</span></label>
            <select name="kegiatan_prodi_id" id="kegiatan_prodi_id" class="form-select">
                <option value="">-- Pilih Kegiatan --</option>
                @foreach($kegiatanSistem as $k)
                    <option value="{{ $k->id }}" {{ old('kegiatan_prodi_id', $KegiatanTendik->kegiatan_prodi_id) == $k->id ? 'selected' : '' }} data-nama="{{ $k->nama_kegiatan }}" data-tahun="{{ $k->tanggal ? date('Y', strtotime($k->tanggal)) : '' }}">
                        {{ $k->nama_kegiatan }} ({{ $k->tanggal ? date('Y', strtotime($k->tanggal)) : 'N/A' }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Sertifikat otomatis diambil jika Tendik terdaftar sebagai peserta kegiatan sistem ini.</small>
        </div>
        <div class="col-md-12 mb-3" id="wrapper_link_dokumen" style="{{ old('jenis', $KegiatanTendik->jenis) == 'Internal' ? 'display: none;' : 'display: block;' }}">
            <label for="link_dokumen" class="form-label">Link Dokumen Bukti (Sertifikat)</label>
            <input type="url" name="link_dokumen" id="link_dokumen" class="form-control"
                   value="{{ old('link_dokumen', $KegiatanTendik->link_dokumen) }}"
                   placeholder="https://example.com/sertifikat-kegiatan">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@push('scripts')
<script>
    document.getElementById('nip_nik').addEventListener('change', function() {
        var kode = this.value;
        if (kode) {
            var route = document.getElementById('nip_nik').getAttribute('data-route');
            fetch(route + kode)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    document.getElementById('nama_tendik').value = data.nama_tendik;
                })
                .catch(function() {
                    document.getElementById('nama_tendik').value = '';
                });
        } else {
            document.getElementById('nama_tendik').value = '';
        }
    });

    // Toggle logic for Jenis Kegiatan
    const jenisSelect = document.getElementById('jenis');
    const wrapInternal = document.getElementById('wrapper_kegiatan_internal');
    const wrapLink = document.getElementById('wrapper_link_dokumen');
    const inputKegiatanInternal = document.getElementById('kegiatan_prodi_id');
    const inputNamaKegiatan = document.getElementById('nama_kegiatan');
    const inputTahun = document.getElementById('tahun');
    const inputPenyelenggara = document.getElementById('penyelenggara');

    jenisSelect.addEventListener('change', function() {
        if (this.value === 'Internal') {
            wrapInternal.style.display = 'block';
            inputKegiatanInternal.required = true;
            wrapLink.style.display = 'none';
        } else {
            wrapInternal.style.display = 'none';
            inputKegiatanInternal.required = false;
            wrapLink.style.display = 'block';
        }
    });

    // Auto-fill when selecting internal kegiatan
    inputKegiatanInternal.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.value) {
            inputNamaKegiatan.value = selected.getAttribute('data-nama');
            inputTahun.value = selected.getAttribute('data-tahun');
            inputPenyelenggara.value = 'Program Studi';
        }
    });
</script>
@endpush
@endsection
