@extends('layouts.app')

@section('title', 'Edit Data Praktisi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Praktisi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('praktisi.update', $praktisi) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Praktisi -->
                    <div class="mb-3">
                        <label for="nama_praktisi" class="form-label fw-semibold">Nama Praktisi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_praktisi" id="nama_praktisi" class="form-control @error('nama_praktisi') is-invalid @enderror" value="{{ old('nama_praktisi', $praktisi->nama_praktisi) }}" required>
                        @error('nama_praktisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div class="mb-3">
                        <label for="pendidikan_terakhir" class="form-label fw-semibold">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" value="{{ old('pendidikan_terakhir', $praktisi->pendidikan_terakhir) }}" placeholder="Contoh: S2 Teknik Informatika">
                        @error('pendidikan_terakhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- TA (Tahun Akademik) -->
                    <div class="mb-3">
                        <label for="ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                        <select name="ts_id" id="ts_id" class="form-select @error('ts_id') is-invalid @enderror" required>
                            <option value="">-- Pilih TA --</option>
                            @foreach($tsList as $ts)
                                <option value="{{ $ts->id }}" {{ old('ts_id', $praktisi->ts_id) == $ts->id ? 'selected' : '' }}>
                                    {{ $ts->tahun_sekarang }}
                                </option>
                            @endforeach
                        </select>
                        @error('ts_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Matakuliah (Multiple Checkbox) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Matakuliah yang Diampu <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-white @error('kode_matakuliah') is-invalid @enderror" style="max-height: 200px; overflow-y: auto;">
                            @php
                                $selectedMKs = old('kode_matakuliah', $praktisi->kode_matakuliah ?? []);
                            @endphp
                            @forelse($matakuliahList as $mk)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="kode_matakuliah[]" value="{{ $mk->kode_matakuliah }}" id="mk_{{ $mk->kode_matakuliah }}"
                                        {{ in_array($mk->kode_matakuliah, $selectedMKs) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark small" for="mk_{{ $mk->kode_matakuliah }}">
                                        <strong>{{ $mk->kode_matakuliah }}</strong> - {{ $mk->nama_matakuliah }}
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada data matakuliah.</span>
                            @endforelse
                        </div>
                        @error('kode_matakuliah')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Dapat memilih lebih dari 1 matakuliah.</div>
                    </div>

                    <!-- Kelas (Multiple Checkbox) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas Mengajar <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-white @error('kelas') is-invalid @enderror" style="max-height: 150px; overflow-y: auto;">
                            @php
                                $selectedKelas = old('kelas', $praktisi->kelas ?? []);
                            @endphp
                            @forelse($kelasList as $kls)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="kelas[]" value="{{ $kls->nama_kelas }}" id="kelas_{{ $kls->nama_kelas }}"
                                        {{ in_array($kls->nama_kelas, $selectedKelas) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark small" for="kelas_{{ $kls->nama_kelas }}">
                                        {{ $kls->nama_kelas }}
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada data kelas.</span>
                            @endforelse
                        </div>
                        @error('kelas')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Dapat memilih lebih dari 1 kelas.</div>
                    </div>

                    <!-- Link Ijazah -->
                    <div class="mb-3">
                        <label for="link_ijazah" class="form-label fw-semibold">Link Ijazah <span class="text-muted">(Optional)</span></label>
                        <input type="url" name="link_ijazah" id="link_ijazah" class="form-control @error('link_ijazah') is-invalid @enderror" value="{{ old('link_ijazah', $praktisi->link_ijazah) }}" placeholder="https://example.com/link-ijazah">
                        @error('link_ijazah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Link Sertifikasi -->
                    <div class="mb-3">
                        <label for="link_sertifikasi" class="form-label fw-semibold">Link Sertifikasi Kompetensi <span class="text-muted">(Optional)</span></label>
                        <input type="url" name="link_sertifikasi" id="link_sertifikasi" class="form-control @error('link_sertifikasi') is-invalid @enderror" value="{{ old('link_sertifikasi', $praktisi->link_sertifikasi) }}" placeholder="https://example.com/link-sertifikasi">
                        @error('link_sertifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Link Dokumen Lainnya -->
                    <div class="mb-3">
                        <label for="link_dokumen" class="form-label fw-semibold">Link Dokumen Pendukung Lainnya <span class="text-muted">(Optional)</span></label>
                        <input type="url" name="link_dokumen" id="link_dokumen" class="form-control @error('link_dokumen') is-invalid @enderror" value="{{ old('link_dokumen', $praktisi->link_dokumen) }}" placeholder="https://example.com/link-dokumen-lain">
                        @error('link_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('praktisi.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
