@extends('layouts.app')

@block('title', 'Edit Rencana Tugas Mahasiswa (RTM)')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4 fw-bold text-dark">Edit Rencana Tugas Mahasiswa (RTM)</h3>
    <ol class="breadcrumb mb-4 small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('penyusunan-rtm.index') }}">Penyusunan RTM</a></li>
        <li class="breadcrumb-item active">Edit RTM</li>
    </ol>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark py-3">
            <h6 class="m-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Form Edit RTM - {{ $rtm->rps?->matakuliah?->nama_matakuliah }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('penyusunan-rtm.update', $rtm->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="fw-bold text-primary mb-3">Informasi Umum Dokumen</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode Matakuliah</label>
                        <input type="text" class="form-control bg-light" value="{{ $rtm->rps?->kode_matakuliah }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nomor Dokumen RTM</label>
                        <input type="text" name="nomor_dokumen" class="form-control" value="{{ $rtm->nomor_dokumen }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Dosen Pengampu</label>
                        <input type="text" name="dosen_pengampu" class="form-control" value="{{ $rtm->dosen_pengampu }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Semester</label>
                        <input type="number" name="semester" class="form-control" value="{{ $rtm->semester }}">
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold text-primary mb-3">Rincian Tugas Mahasiswa</h5>
                
                @foreach($rtm->tugas as $tIndex => $t)
                <div class="card border border-primary-subtle shadow-none mb-4">
                    <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary"><i class="bi bi-file-earmark-check me-2"></i>Tugas ke-{{ $t->tugas_ke }} (Minggu ke-{{ $t->minggu_ke }})</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Bentuk Tugas</label>
                                <input type="text" name="tugas[{{ $t->id }}][bentuk_tugas]" class="form-control form-control-sm" value="{{ $t->bentuk_tugas }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Judul Tugas</label>
                                <input type="text" name="tugas[{{ $t->id }}][judul_tugas]" class="form-control form-control-sm" value="{{ $t->judul_tugas }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Sub CPMK</label>
                                <textarea name="tugas[{{ $t->id }}][sub_cpmk]" class="form-control form-control-sm" rows="2">{{ $t->sub_cpmk }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Obyek Garapan</label>
                                <textarea name="tugas[{{ $t->id }}][obyek_garapan]" class="form-control form-control-sm" rows="3">{{ $t->obyek_garapan }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Metode Pengerjaan Tugas</label>
                                <textarea name="tugas[{{ $t->id }}][metode_pengerjaan]" class="form-control form-control-sm" rows="3">{{ $t->metode_pengerjaan }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Bentuk dan Format Luaran</label>
                                <textarea name="tugas[{{ $t->id }}][bentuk_format_luaran]" class="form-control form-control-sm" rows="3">{{ $t->bentuk_format_luaran }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Waktu Pengerjaan</label>
                                <input type="text" name="tugas[{{ $t->id }}][waktu_pengerjaan]" class="form-control form-control-sm" value="{{ $t->waktu_pengerjaan }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Waktu Pengumpulan</label>
                                <input type="text" name="tugas[{{ $t->id }}][waktu_pengumpulan]" class="form-control form-control-sm" value="{{ $t->waktu_pengumpulan }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Lain-Lain</label>
                                <textarea name="tugas[{{ $t->id }}][lain_lain]" class="form-control form-control-sm" rows="2">{{ $t->lain_lain }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Daftar Rujukan</label>
                                <textarea name="tugas[{{ $t->id }}][daftar_rujukan]" class="form-control form-control-sm" rows="2">{{ $t->daftar_rujukan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('penyusunan-rtm.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning fw-bold"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
