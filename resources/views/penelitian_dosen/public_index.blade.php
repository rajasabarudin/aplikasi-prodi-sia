@extends('layouts.public')

@section('title', 'Portal Penelitian Dosen')

@section('content')
<div class="hero mb-5" style="padding: 60px 0 40px;" data-aos="fade-down">
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-semibold">
                    <i class="bi bi-journal-text me-1"></i> Tridharma Perguruan Tinggi
                </span>
                <h1 class="mb-3">Portal Pendataan <span>Penelitian Dosen</span></h1>
                <p class="mb-0">Silakan masukkan data publikasi/penelitian Anda di bawah ini. Cukup gunakan Kode Dosen Anda untuk mengisi data.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5 pb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-plus-circle me-2 text-primary"></i>Input Data Baru</h5>
                    <form action="{{ route('portal.penelitian.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                            <select name="ts_id" class="form-select" required>
                                <option value="">-- Pilih TS --</option>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }} ({{ $ts->semester }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Dosen <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="kode_dosen[]" id="public-kode-dosen" required placeholder="Cth: DSN01">
                                <button class="btn btn-outline-primary" type="button" id="public-cek-dosen">Cek</button>
                            </div>
                            <small id="public-feedback-dosen" class="text-danger d-none mt-1">Kode Dosen tidak ditemukan.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Dosen</label>
                            <input type="text" class="form-control bg-light" name="nama_dosen[]" id="public-nama-dosen" readonly placeholder="Otomatis terisi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul / Nama Jurnal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_jurnal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Penelitian <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_penelitian" required>
                                <option value="">-- Pilih --</option>
                                <option value="Sesuai Bidang Ilmu">Sesuai Bidang Ilmu</option>
                                <option value="Tidak Sesuai Bidang Ilmu">Tidak Sesuai Bidang Ilmu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Jurnal <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_jurnal" required>
                                <option value="">-- Pilih --</option>
                                <option value="Jurnal Nasional">Jurnal Nasional</option>
                                <option value="Jurnal Nasional Terakreditasi (SINTA)">Jurnal Nasional Terakreditasi (SINTA)</option>
                                <option value="Jurnal Internasional">Jurnal Internasional</option>
                                <option value="Jurnal Internasional Bereputasi (Scopus/WoS)">Jurnal Internasional Bereputasi (Scopus/WoS)</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Link Jurnal/Publikasi (Opsional)</label>
                            <input type="url" class="form-control" name="link_jurnal" placeholder="https://...">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold" id="public-submit-btn" disabled>
                                <i class="bi bi-send me-1"></i> Kirim Data
                            </button>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Data yang dikirim tidak dapat diedit/dihapus secara publik. Hubungi Kaprodi untuk perubahan.</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="col-lg-8" data-aos="fade-left" data-aos-delay="200">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-table me-2 text-primary"></i>Daftar Penelitian Dosen</h5>
                        <form action="{{ route('portal.penelitian') }}" method="GET" class="d-flex gap-2" style="max-width: 300px;">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Cari..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Kode & Nama Dosen</th>
                                    <th>Penelitian / Jurnal</th>
                                    <th>Tahun Akademik</th>
                                    <th class="text-center">Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penelitian as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $penelitian->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->nama_dosen }}</div>
                                        <div class="small text-muted">{{ $item->kode_dosen }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($item->nama_jurnal, 50) }}</div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 mt-1" style="font-size: 0.7rem;">{{ $item->jenis_jurnal }}</span>
                                    </td>
                                    <td>{{ $item->ts->tahun_sekarang ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($item->link_jurnal)
                                            <a href="{{ $item->link_jurnal }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data penelitian yang disubmit.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $penelitian->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('public-cek-dosen').addEventListener('click', function() {
        let kode = document.getElementById('public-kode-dosen').value;
        let btn = this;
        let btnSubmit = document.getElementById('public-submit-btn');
        let feedback = document.getElementById('public-feedback-dosen');
        
        if (!kode) {
            alert('Masukkan Kode Dosen terlebih dahulu!');
            return;
        }

        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        btn.disabled = true;

        fetch(`{{ url('portal-penelitian/get-dosen') }}/${kode}`)
            .then(response => {
                if (!response.ok) throw new Error('Not found');
                return response.json();
            })
            .then(data => {
                document.getElementById('public-nama-dosen').value = data.nama_dosen;
                feedback.classList.add('d-none');
                btnSubmit.disabled = false;
                btn.innerHTML = 'Cek';
                btn.disabled = false;
                btn.classList.replace('btn-outline-primary', 'btn-success');
                btn.innerHTML = '<i class="bi bi-check-lg"></i>';
                setTimeout(() => {
                    btn.classList.replace('btn-success', 'btn-outline-primary');
                    btn.innerHTML = 'Cek';
                }, 2000);
            })
            .catch(error => {
                document.getElementById('public-nama-dosen').value = '';
                feedback.classList.remove('d-none');
                btnSubmit.disabled = true;
                btn.innerHTML = 'Cek';
                btn.disabled = false;
            });
    });

    document.getElementById('public-kode-dosen').addEventListener('input', function() {
        document.getElementById('public-submit-btn').disabled = true;
        document.getElementById('public-nama-dosen').value = '';
        document.getElementById('public-feedback-dosen').classList.add('d-none');
    });
</script>
@endpush
@endsection
