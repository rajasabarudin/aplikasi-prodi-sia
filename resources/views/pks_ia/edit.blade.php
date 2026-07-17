@extends('layouts.app')

@section('title', 'Edit Data PKS & IA')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Edit Data PKS & IA</h1>
                <p class="text-muted mb-0">Perbarui informasi data PKS & IA.</p>
            </div>
            <a href="{{ route('pks-ia.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Formulir Edit PKS & IA</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('pks-ia.update', $pksIa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nama Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_mitra" class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                            <select name="nama_mitra" id="nama_mitra" class="form-select @error('nama_mitra') is-invalid @enderror" required>
                                <option value="" disabled>Pilih Mitra dari Data Kerjasama</option>
                                @foreach ($kerjasamaList as $k)
                                    <option value="{{ $k->nama_mitra }}" {{ old('nama_mitra', $pksIa->nama_mitra) == $k->nama_mitra ? 'selected' : '' }}>{{ $k->nama_mitra }}</option>
                                @endforeach
                            </select>
                            @error('nama_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal PKS -->
                        <div class="col-md-6 mb-3">
                            <label for="tgl_pks" class="form-label fw-semibold">Tanggal PKS <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pks" id="tgl_pks" class="form-control @error('tgl_pks') is-invalid @enderror" value="{{ old('tgl_pks', $pksIa->tgl_pks->format('Y-m-d')) }}" required>
                            @error('tgl_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor PKS UBSI -->
                        <div class="col-md-6 mb-3">
                            <label for="no_pks_ubsi" class="form-label fw-semibold">Nomor PKS UBSI <span class="text-danger">*</span></label>
                            <input type="text" name="no_pks_ubsi" id="no_pks_ubsi" class="form-control @error('no_pks_ubsi') is-invalid @enderror" value="{{ old('no_pks_ubsi', $pksIa->no_pks_ubsi) }}" required placeholder="Nomor PKS dari pihak UBSI">
                            @error('no_pks_ubsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor PKS Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="no_pks_mitra" class="form-label fw-semibold">Nomor PKS Mitra</label>
                            <input type="text" name="no_pks_mitra" id="no_pks_mitra" class="form-control @error('no_pks_mitra') is-invalid @enderror" value="{{ old('no_pks_mitra', $pksIa->no_pks_mitra) }}" placeholder="Nomor PKS dari pihak Mitra">
                            @error('no_pks_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tema PKS -->
                        <div class="col-md-12 mb-3">
                            <label for="tema_pks" class="form-label fw-semibold">Tema PKS <span class="text-danger">*</span></label>
                            <input type="text" name="tema_pks" id="tema_pks" class="form-control @error('tema_pks') is-invalid @enderror" value="{{ old('tema_pks', $pksIa->tema_pks) }}" required placeholder="Tema atau ruang lingkup kerjasama perjanjian">
                            @error('tema_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Divider & IA Section -->
                        <div class="col-12 mt-2 mb-2">
                            <hr>
                            <h6 class="fw-bold text-primary"><i class="bi bi-file-earmark-check-fill me-1"></i>Detail IA (Implementation Agreement) <span class="text-muted font-monospace" style="font-size: 0.75rem;">(Opsional)</span></h6>
                        </div>

                        <!-- Tanggal IA -->
                        <div class="col-md-4 mb-3">
                            <label for="tgl_ia" class="form-label fw-semibold">Tanggal IA</label>
                            <input type="date" name="tgl_ia" id="tgl_ia" class="form-control @error('tgl_ia') is-invalid @enderror" value="{{ old('tgl_ia', $pksIa->tgl_ia ? $pksIa->tgl_ia->format('Y-m-d') : '') }}">
                            @error('tgl_ia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor IA UBSI -->
                        <div class="col-md-4 mb-3">
                            <label for="no_ia_ubsi" class="form-label fw-semibold">Nomor IA UBSI</label>
                            <input type="text" name="no_ia_ubsi" id="no_ia_ubsi" class="form-control @error('no_ia_ubsi') is-invalid @enderror" value="{{ old('no_ia_ubsi', $pksIa->no_ia_ubsi) }}" placeholder="Nomor IA dari UBSI">
                            @error('no_ia_ubsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor IA Mitra -->
                        <div class="col-md-4 mb-3">
                            <label for="no_ia_mitra" class="form-label fw-semibold">Nomor IA Mitra</label>
                            <input type="text" name="no_ia_mitra" id="no_ia_mitra" class="form-control @error('no_ia_mitra') is-invalid @enderror" value="{{ old('no_ia_mitra', $pksIa->no_ia_mitra) }}" placeholder="Nomor IA dari Mitra">
                            @error('no_ia_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Judul/Tema Kegiatan IA -->
                        <div class="col-md-12 mb-3">
                            <label for="judul_ia" class="form-label fw-semibold">Judul Kegiatan IA</label>
                            <input type="text" name="judul_ia" id="judul_ia" class="form-control @error('judul_ia') is-invalid @enderror" value="{{ old('judul_ia', $pksIa->judul_ia) }}" placeholder="Judul spesifik kegiatan implementasi">
                            @error('judul_ia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="" disabled>Pilih Kategori</option>
                                <option value="Pendidikan" {{ old('kategori', $pksIa->kategori) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="PKM" {{ old('kategori', $pksIa->kategori) == 'PKM' ? 'selected' : '' }}>PKM</option>
                                <option value="Penelitian" {{ old('kategori', $pksIa->kategori) == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                            </select>
                            @error('kategori')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Level PKS -->
                        <div class="col-md-6 mb-3">
                            <label for="level_pks" class="form-label fw-semibold">Level PKS <span class="text-danger">*</span></label>
                            <select name="level_pks" id="level_pks" class="form-select @error('level_pks') is-invalid @enderror" required>
                                <option value="" disabled>Pilih Level</option>
                                <option value="Lokal/Wilayah" {{ old('level_pks', $pksIa->level_pks) == 'Lokal/Wilayah' ? 'selected' : '' }}>Lokal/Wilayah</option>
                                <option value="Nasional" {{ old('level_pks', $pksIa->level_pks) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ old('level_pks', $pksIa->level_pks) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                            @error('level_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File PKS -->
                        <div class="col-md-4 mb-3">
                            <label for="file_pks" class="form-label fw-semibold">Berkas PKS</label>
                            @if ($pksIa->file_pks)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $pksIa->file_pks) }}" target="_blank" class="btn btn-sm btn-outline-success w-100 text-truncate">
                                        <i class="bi bi-file-earmark-check me-1"></i> Lihat Berkas PKS
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="file_pks" id="file_pks" class="form-control @error('file_pks') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah.</div>
                            @error('file_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File IA -->
                        <div class="col-md-4 mb-3">
                            <label for="file_ia" class="form-label fw-semibold">Berkas IA</label>
                            @if ($pksIa->file_ia)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $pksIa->file_ia) }}" target="_blank" class="btn btn-sm btn-outline-info w-100 text-truncate text-dark">
                                        <i class="bi bi-file-earmark-check me-1"></i> Lihat Berkas IA
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="file_ia" id="file_ia" class="form-control @error('file_ia') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah.</div>
                            @error('file_ia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Tambahan -->
                        <div class="col-md-4 mb-3">
                            <label for="file_tambahan" class="form-label fw-semibold">Berkas Tambahan</label>
                            @if ($pksIa->file_tambahan)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $pksIa->file_tambahan) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 text-truncate">
                                        <i class="bi bi-file-earmark-check me-1"></i> Lihat Berkas Tambahan
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="file_tambahan" id="file_tambahan" class="form-control @error('file_tambahan') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah.</div>
                            @error('file_tambahan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('pks-ia.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
