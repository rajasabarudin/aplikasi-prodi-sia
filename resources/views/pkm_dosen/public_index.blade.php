@extends('layouts.public')

@section('title', 'Portal Pengabdian Masyarakat (PkM) Dosen')

@section('content')
<div class="hero mb-5" style="padding: 60px 0 40px;" data-aos="fade-down">
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill mb-3 fw-semibold">
                    <i class="bi bi-people-fill me-1"></i> Tridharma Perguruan Tinggi
                </span>
                <h1 class="mb-3">Portal Pendataan <span>Pengabdian Masyarakat (PkM)</span></h1>
                <p class="mb-0">Silakan masukkan data Pengabdian kepada Masyarakat Anda di bawah ini. Jika ada kolaborasi, Anda dapat menambahkan beberapa Dosen dan Mahasiswa sekaligus.</p>
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
                    <h5 class="fw-bold mb-4"><i class="bi bi-plus-circle me-2 text-secondary"></i>Input Data PkM</h5>
                    <form action="{{ route('portal.pkm.store') }}" method="POST">
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold text-secondary mb-0"><i class="bi bi-person-fill me-1"></i> Dosen Pelaksana <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="btn-add-dosen"><i class="bi bi-plus"></i> Tambah</button>
                            </div>
                            <div id="dosen-rows-container">
                                <div class="p-3 bg-light rounded-4 mb-3 dosen-row border">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-kode-dosen" name="kode_dosen[]" required placeholder="Kode Dosen (Cth: DSN01)">
                                                <button class="btn btn-secondary text-white btn-cek-dosen" type="button">Cek</button>
                                            </div>
                                            <small class="feedback-dosen text-danger d-none mt-1">Kode Dosen tidak ditemukan.</small>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <input type="text" class="form-control input-nama-dosen border-0 bg-white" name="nama_dosen[]" readonly required placeholder="Nama Dosen (Otomatis)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mahasiswa Section -->
                        <div class="mb-4 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold text-success mb-0"><i class="bi bi-mortarboard-fill me-1"></i> Mahasiswa (Opsional)</label>
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill" id="btn-add-mahasiswa"><i class="bi bi-plus"></i> Tambah</button>
                            </div>
                            <div id="mahasiswa-rows-container">
                                <div class="p-3 bg-light rounded-4 mb-3 mahasiswa-row border">
                                    <div class="row g-2">
                                        <div class="col-10">
                                            <input type="text" class="form-control input-nim-mhs" name="nim_mhs[]" placeholder="NIM Mahasiswa">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-outline-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i></button>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <input type="text" class="form-control input-nama-mahasiswa border-0 bg-white" name="nama_mahasiswa[]" readonly placeholder="Nama Mahasiswa (Otomatis)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tema PkM <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="tema_pkm" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mitra <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="mitra" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis PkM <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_pkm" required>
                                <option value="">-- Pilih --</option>
                                <option value="Mitra Non Produktif">Mitra Non Produktif</option>
                                <option value="Mitra Produktif">Mitra Produktif</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sumber IPTEK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="sumber_iptek" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Link Dokumen (Opsional)</label>
                            <input type="url" class="form-control" name="link_dokumen" placeholder="https://...">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Link Publikasi (Opsional)</label>
                            <input type="url" class="form-control" name="link_publikasi" placeholder="https://...">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-secondary text-white rounded-pill py-3 fw-bold fs-5" id="public-submit-btn" disabled>
                                <i class="bi bi-send-fill me-2"></i> Kirim Data PkM
                            </button>
                        </div>
                        <div class="mt-4 text-center">
                            <div class="alert alert-warning py-2 mb-0" style="font-size: 0.85rem;">
                                <i class="bi bi-info-circle-fill me-1"></i> Data yang dikirim tidak dapat diedit/dihapus secara publik. Hubungi Kaprodi untuk perubahan.
                            </div>
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
                        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-table me-2 text-secondary"></i>Daftar PkM</h4>
                        <form action="{{ route('portal.pkm') }}" method="GET" class="d-flex gap-2" style="max-width: 300px;">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0" name="search" placeholder="Cari dosen, tema..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-secondary text-white"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Pelaksana (Dosen)</th>
                                    <th>Tema PkM & Mitra</th>
                                    <th class="text-center">Tahun Akademik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pkm as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $pkm->firstItem() + $index }}</td>
                                    <td>
                                        @php
                                            $namas = explode(', ', $item->nama_dosen);
                                        @endphp
                                        @foreach($namas as $nama)
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">- {{ $nama }}</div>
                                        @endforeach
                                        @if($item->nama_mahasiswa)
                                            <div class="small text-success mt-1"><i class="bi bi-mortarboard-fill me-1"></i> + Mhs Terlibat</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark" style="line-height: 1.3;">{{ Str::limit($item->tema_pkm, 60) }}</div>
                                        <div class="small text-muted mt-1"><i class="bi bi-building me-1"></i>{{ $item->mitra }}</div>
                                        <div class="mt-2">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2" style="font-size: 0.7rem;">{{ $item->jenis_pkm }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->ts->tahun_sekarang ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data PkM yang disubmit.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $pkm->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkAllDosenValid() {
        let allValid = true;
        let inputs = document.querySelectorAll('.input-nama-dosen');
        inputs.forEach(function(input) {
            if(input.value.trim() === '') {
                allValid = false;
            }
        });
        document.getElementById('public-submit-btn').disabled = !allValid;
    }

    function attachDosenEvent(row) {
        let btnCek = row.querySelector('.btn-cek-dosen');
        let inputKode = row.querySelector('.input-kode-dosen');
        let inputNama = row.querySelector('.input-nama-dosen');
        let feedback = row.querySelector('.feedback-dosen');

        btnCek.addEventListener('click', function() {
            let kode = inputKode.value.trim();
            if (!kode) {
                alert('Masukkan Kode Dosen terlebih dahulu!');
                return;
            }

            btnCek.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btnCek.disabled = true;

            fetch(`{{ url('portal-pkm/get-dosen') }}/${kode}`)
                .then(response => {
                    if (!response.ok) throw new Error('Not found');
                    return response.json();
                })
                .then(data => {
                    inputNama.value = data.nama_dosen;
                    feedback.classList.add('d-none');
                    btnCek.classList.replace('btn-secondary', 'btn-success');
                    btnCek.innerHTML = '<i class="bi bi-check-lg"></i>';
                    setTimeout(() => {
                        btnCek.classList.replace('btn-success', 'btn-secondary');
                        btnCek.innerHTML = 'Cek';
                        btnCek.disabled = false;
                    }, 1500);
                    checkAllDosenValid();
                })
                .catch(error => {
                    inputNama.value = '';
                    feedback.classList.remove('d-none');
                    btnCek.innerHTML = 'Cek';
                    btnCek.disabled = false;
                    checkAllDosenValid();
                });
        });

        inputKode.addEventListener('input', function() {
            inputNama.value = '';
            feedback.classList.add('d-none');
            checkAllDosenValid();
        });

        let btnRemove = row.querySelector('.btn-remove-dosen');
        if(btnRemove) {
            btnRemove.addEventListener('click', function() {
                row.remove();
                checkAllDosenValid();
            });
        }
    }

    function attachMahasiswaEvent(row) {
        let inputNim = row.querySelector('.input-nim-mhs');
        let inputNama = row.querySelector('.input-nama-mahasiswa');
        
        inputNim.addEventListener('input', function() {
            let nim = this.value.trim();
            if (nim.length >= 3) {
                // Gunakan route dari beasiswa atau penelitian karena fungsinya sama, mengambil getMahasiswa
                fetch(`{{ url('portal-beasiswa/get-mahasiswa') }}/${nim}`)
                    .then(res => {
                        if(!res.ok) throw new Error('Not found');
                        return res.json();
                    })
                    .then(data => {
                        inputNama.value = data.nama;
                    })
                    .catch(() => {
                        inputNama.value = 'Tidak ditemukan (Otomatis menyesuaikan jika nim benar)';
                    });
            } else {
                inputNama.value = '';
            }
        });

        let btnRemove = row.querySelector('.btn-remove-mahasiswa');
        if(btnRemove) {
            btnRemove.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Initialize first rows
    document.querySelectorAll('.dosen-row').forEach(attachDosenEvent);
    document.querySelectorAll('.mahasiswa-row').forEach(attachMahasiswaEvent);

    // Add Dosen
    document.getElementById('btn-add-dosen').addEventListener('click', function() {
        let container = document.getElementById('dosen-rows-container');
        let newRow = document.createElement('div');
        newRow.className = 'p-3 bg-light rounded-4 mb-3 dosen-row border';
        newRow.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-10">
                    <div class="input-group">
                        <input type="text" class="form-control input-kode-dosen" name="kode_dosen[]" required placeholder="Kode Dosen">
                        <button class="btn btn-secondary text-white btn-cek-dosen" type="button">Cek</button>
                    </div>
                    <small class="feedback-dosen text-danger d-none mt-1">Kode Dosen tidak ditemukan.</small>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger w-100 btn-remove-dosen"><i class="bi bi-trash"></i></button>
                </div>
                <div class="col-12 mt-2">
                    <input type="text" class="form-control input-nama-dosen border-0 bg-white" name="nama_dosen[]" readonly required placeholder="Nama Dosen (Otomatis)">
                </div>
            </div>
        `;
        container.appendChild(newRow);
        attachDosenEvent(newRow);
        checkAllDosenValid();
    });

    // Add Mahasiswa
    document.getElementById('btn-add-mahasiswa').addEventListener('click', function() {
        let container = document.getElementById('mahasiswa-rows-container');
        let newRow = document.createElement('div');
        newRow.className = 'p-3 bg-light rounded-4 mb-3 mahasiswa-row border';
        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-10">
                    <input type="text" class="form-control input-nim-mhs" name="nim_mhs[]" placeholder="NIM Mahasiswa">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-outline-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i></button>
                </div>
                <div class="col-12 mt-2">
                    <input type="text" class="form-control input-nama-mahasiswa border-0 bg-white" name="nama_mahasiswa[]" readonly placeholder="Nama Mahasiswa (Otomatis)">
                </div>
            </div>
        `;
        container.appendChild(newRow);
        attachMahasiswaEvent(newRow);
    });
</script>
@endpush
@endsection
