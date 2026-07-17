@extends('layouts.app')
@section('title', 'Profil Prodi')
@section('content')
@if (session('success'))
    <div class="alert alert-success shadow-sm border-0 border-start border-success border-4 mb-4">
        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
    </div>
@endif
<div class="card shadow-sm border-0">
    @if ($errors->any())
        <div class="alert alert-danger m-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card-header bg-dark text-white fw-bold d-flex align-items-center">
        <i class="bi bi-building me-2"></i> Kelola Profil Prodi
    </div>
    <div class="card-body">
        <form action="{{ route('profil-prodi.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Ketua Program Studi</label>
                        <input type="text" name="nama_kaprodi" class="form-control" value="{{ old('nama_kaprodi', $profil->nama_kaprodi) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Kaprodi <small class="text-muted fw-normal">(Format: jpg, png | Max: 2MB)</small></label>
                        <input type="file" name="foto_kaprodi" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-center justify-content-center">
                    @if($profil->foto_kaprodi)
                        <div class="text-center">
                            <p class="mb-2 text-muted small">Foto Saat Ini</p>
                            <img src="{{ asset('storage/' . $profil->foto_kaprodi) }}" style="width: 150px; height: 150px; object-fit: cover;" class="rounded-circle img-thumbnail shadow-sm">
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Akreditasi Prodi</label>
                        <input type="text" name="akreditasi" class="form-control" placeholder="Misal: B atau Baik Sekali" value="{{ old('akreditasi', $profil->akreditasi) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lama Masa Studi</label>
                        <input type="text" name="lama_masa_studi" class="form-control" placeholder="Misal: 4 Tahun (8 Semester)" value="{{ old('lama_masa_studi', $profil->lama_masa_studi) }}">
                    </div>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi / Tentang Prodi</label>
                <textarea name="deskripsi_profil" class="form-control" rows="5" placeholder="Masukkan sejarah singkat atau deskripsi umum mengenai prodi">{{ old('deskripsi_profil', $profil->deskripsi_profil) }}</textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Visi Keilmuan</label>
                <textarea name="visi_keilmuan" class="form-control" rows="4">{{ old('visi_keilmuan', $profil->visi_keilmuan) }}</textarea>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Informasi Tambahan Staf / Struktur</label>
                <textarea name="informasi_staf" class="form-control" rows="4" placeholder="Misal: Sekretaris Prodi: Budi, Staf Akademik: Ani">{{ old('informasi_staf', $profil->informasi_staf) }}</textarea>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
