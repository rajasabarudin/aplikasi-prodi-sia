@extends('layouts.public')

@section('title', 'Portal Rekognisi & Keanggotaan Dosen')

@section('content')
<div class="hero mb-5" style="padding: 60px 0 40px;" data-aos="fade-down">
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill mb-3 fw-semibold">
                    <i class="bi bi-award-fill me-1"></i> Rekognisi & Keanggotaan Profesi
                </span>
                <h1 class="mb-3">Portal Pendataan <span>Rekognisi Dosen</span></h1>
                <p class="mb-0">Silakan masukkan data Rekognisi, Pengakuan, atau Keanggotaan Organisasi Profesi Anda di bawah ini untuk diperbarui pada Profil Dosen.</p>
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
        <div class="col-lg-5" data-aos="fade-right" data-aos-delay="100">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-lg-5">
                    <h5 class="fw-bold mb-4"><i class="bi bi-plus-circle me-2 text-warning"></i>Input Data Rekognisi</h5>
                    <form action="{{ route('portal.rekognisi.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                            <select name="ts_id" class="form-select bg-light" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }} ({{ $ts->semester }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dosen Section -->
                        <div class="mb-4 pb-3 border-bottom">
                            <label class="form-label fw-bold text-secondary mb-3"><i class="bi bi-person-fill me-1"></i> Data Dosen <span class="text-danger">*</span></label>
                            <div class="p-3 bg-light rounded-4 mb-3 dosen-row border">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-kode-dosen" name="kode_dosen" required placeholder="Kode Dosen (Cth: DSN01)">
                                            <button class="btn btn-warning text-white btn-cek-dosen" type="button">Cek</button>
                                        </div>
                                        <small class="feedback-dosen text-danger d-none mt-1">Kode Dosen tidak ditemukan.</small>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <input type="text" class="form-control input-nama-dosen border-0 bg-white" name="nama_dosen" readonly required placeholder="Nama Dosen (Otomatis)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Rekognisi / Keanggotaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="nama_rekognisi" rows="2" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tahun Kegiatan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="tahun" required placeholder="Cth: {{ date('Y') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tingkat / Level <span class="text-danger">*</span></label>
                                <select class="form-select" name="level" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="lokal">Lokal / Wilayah</option>
                                    <option value="nasional">Nasional</option>
                                    <option value="internasional">Internasional</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Tridharma</label>
                            <select class="form-select" name="kategori_tridharma">
                                <option value="">-- Tidak Dikategorikan --</option>
                                <option value="pendidikan">Pendidikan / Pengajaran</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian_masyarakat">Pengabdian Kepada Masyarakat</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Link Dokumen Bukti</label>
                            <input type="url" class="form-control" name="link_dokumen" placeholder="https://...">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning text-white rounded-pill py-3 fw-bold fs-5" id="public-submit-btn" disabled>
                                <i class="bi bi-send-fill me-2"></i> Kirim Data Rekognisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-table me-2 text-warning"></i>Daftar Rekognisi</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Dosen</th>
                                    <th>Rekognisi & Tingkat</th>
                                    <th class="text-center">Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekognisi as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $rekognisi->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->nama_dosen }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark" style="line-height: 1.3;">{{ Str::limit($item->nama_rekognisi, 60) }}</div>
                                        <div class="mt-2">
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 text-capitalize" style="font-size: 0.7rem;">{{ $item->level }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->tahun ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data Rekognisi yang disubmit.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $rekognisi->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkValid() {
        let inputNama = document.querySelector('.input-nama-dosen');
        document.getElementById('public-submit-btn').disabled = (inputNama.value.trim() === '');
    }

    let btnCek = document.querySelector('.btn-cek-dosen');
    let inputKode = document.querySelector('.input-kode-dosen');
    let inputNama = document.querySelector('.input-nama-dosen');
    let feedback = document.querySelector('.feedback-dosen');

    btnCek.addEventListener('click', function() {
        let kode = inputKode.value.trim();
        if (!kode) {
            alert('Masukkan Kode Dosen terlebih dahulu!');
            return;
        }

        btnCek.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btnCek.disabled = true;

        fetch(`{{ url('portal-rekognisi/get-dosen') }}/${kode}`)
            .then(response => {
                if (!response.ok) throw new Error('Not found');
                return response.json();
            })
            .then(data => {
                inputNama.value = data.nama_dosen;
                feedback.classList.add('d-none');
                btnCek.classList.replace('btn-warning', 'btn-success');
                btnCek.innerHTML = '<i class="bi bi-check-lg"></i>';
                setTimeout(() => {
                    btnCek.classList.replace('btn-success', 'btn-warning');
                    btnCek.innerHTML = 'Cek';
                    btnCek.disabled = false;
                }, 2000);
                checkValid();
            })
            .catch(error => {
                inputNama.value = '';
                feedback.classList.remove('d-none');
                btnCek.classList.replace('btn-warning', 'btn-danger');
                btnCek.innerHTML = '<i class="bi bi-x-lg"></i>';
                setTimeout(() => {
                    btnCek.classList.replace('btn-danger', 'btn-warning');
                    btnCek.innerHTML = 'Cek';
                    btnCek.disabled = false;
                }, 2000);
                checkValid();
            });
    });
</script>
@endpush
@endsection
