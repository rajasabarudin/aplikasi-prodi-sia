@extends('layouts.app')

@section('title', 'Edit Data IPK Mahasiswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-0 fw-bold text-dark">Edit Data IPK Mahasiswa</h1>
                <p class="text-muted mb-0">Ubah rincian data IPK mahasiswa di bawah ini</p>
            </div>
            <div>
                <a href="{{ route('ipk.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 border-start border-danger border-4 mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Form Edit IPK</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ipk.update', $ipk->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Hubungkan dengan Data Mahasiswa (Opsional untuk Auto-fill) -->
                    <div class="mb-3">
                        <label for="mhs_select" class="form-label fw-semibold">Pilih Mahasiswa Terdaftar <span class="text-muted small">(Opsional untuk Auto-fill)</span></label>
                        <select id="mhs_select" class="form-select">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($mahasiswaList as $m)
                                <option value="{{ $m->id }}" data-nim="{{ $m->nim }}" data-nama="{{ $m->nama }}" {{ $ipk->nim == $m->nim ? 'selected' : '' }}>
                                    {{ $m->nim }} - {{ $m->nama }} (Kelas: {{ $m->kelas }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="text-muted">

                    <!-- NIM -->
                    <div class="mb-3">
                        <label for="nim" class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="nim" class="form-control" required value="{{ old('nim', $ipk->nim) }}" placeholder="Masukkan NIM...">
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required value="{{ old('nama', $ipk->nama) }}" placeholder="Masukkan Nama...">
                    </div>

                    <!-- IPK -->
                    <div class="mb-3">
                        <label for="ipk" class="form-label fw-semibold">IPK <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.00" max="4.00" name="ipk" id="ipk" class="form-control" required value="{{ old('ipk', $ipk->ipk) }}" placeholder="Contoh: 3.85">
                        <div class="form-text">Nilai IPK berkisar antara 0.00 hingga 4.00</div>
                    </div>

                    <!-- TA -->
                    <div class="mb-3">
                        <label for="ts_id" class="form-label fw-semibold">TA (Tahun Akademik) <span class="text-danger">*</span></label>
                        <select name="ts_id" id="ts_id" class="form-select" required>
                            <option value="">-- Pilih TA --</option>
                            @foreach ($tsList as $ts)
                                <option value="{{ $ts->id }}" {{ old('ts_id', $ipk->ts_id) == $ts->id ? 'selected' : '' }}>{{ $ts->tahun_sekarang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var mhsSelect = document.getElementById('mhs_select');
    if (mhsSelect) {
        mhsSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value !== "") {
                document.getElementById('nim').value = selectedOption.getAttribute('data-nim');
                document.getElementById('nama').value = selectedOption.getAttribute('data-nama');
            }
        });
    }
});
</script>
@endsection
