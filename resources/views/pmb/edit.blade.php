@extends('layouts.app')

@section('title', 'Edit Data PMB')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Edit Data PMB</h1>
                <p class="text-muted mb-0">Perbarui informasi jumlah penerimaan mahasiswa baru.</p>
            </div>
            <a href="{{ route('pmb.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Formulir Edit PMB</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('pmb.update', $pmb->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Tahun -->
                    <div class="mb-3">
                        <label for="tahun" class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar text-secondary"></i></span>
                            <input type="number" name="tahun" id="tahun" class="form-control border-start-0 @error('tahun') is-invalid @enderror" value="{{ old('tahun', $pmb->tahun) }}" required min="1900" max="2100" placeholder="Contoh: 2024">
                        </div>
                        @error('tahun')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jumlah PMB -->
                    <div class="mb-4">
                        <label for="jumlah_pmb" class="form-label fw-semibold">Jumlah PMB <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-people text-secondary"></i></span>
                            <input type="number" name="jumlah_pmb" id="jumlah_pmb" class="form-control border-start-0 @error('jumlah_pmb') is-invalid @enderror" value="{{ old('jumlah_pmb', $pmb->jumlah_pmb) }}" required min="0" placeholder="Masukkan jumlah mahasiswa">
                        </div>
                        @error('jumlah_pmb')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pmb.index') }}" class="btn btn-light border">Batal</a>
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
