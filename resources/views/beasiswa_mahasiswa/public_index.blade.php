@extends('layouts.public')

@section('title', 'Portal Beasiswa Mahasiswa')

@section('content')
<div class="hero mb-5" style="padding: 60px 0 40px;" data-aos="fade-down">
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-semibold">
                    <i class="bi bi-wallet2 me-1"></i> Formulir Sementara
                </span>
                <h1 class="mb-3">Portal Pendataan <span>Beasiswa</span></h1>
                <p class="mb-0">Silakan masukkan data beasiswa yang telah Anda dapatkan di bawah ini. Pastikan NIM Anda sudah terdaftar.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Statistics Section -->
    <div class="row g-4 mb-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-semibold text-white-50">Total Penerima</h6>
                        <h3 class="mb-0 fw-bold">{{ $statTotal }} <span class="fs-6 fw-normal">Mahasiswa</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-building fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-semibold text-white-50">Beasiswa Internal</h6>
                        <h3 class="mb-0 fw-bold">{{ $statInternal }} <span class="fs-6 fw-normal">Penerima</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-info text-white">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-globe fs-3"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-semibold text-white-50">Beasiswa Eksternal</h6>
                        <h3 class="mb-0 fw-bold">{{ $statEksternal }} <span class="fs-6 fw-normal">Penerima</span></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
                    <form action="{{ route('portal.beasiswa.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">NIM Anda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="nim" id="public-nim" required placeholder="Masukkan NIM">
                                <button class="btn btn-outline-primary" type="button" id="public-cek-nim">Cek</button>
                            </div>
                            <small id="public-feedback" class="text-danger d-none mt-1">NIM tidak ditemukan dalam sistem kami.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Mahasiswa</label>
                            <input type="text" class="form-control bg-light" id="public-nama" readonly placeholder="Otomatis terisi setelah cek NIM">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Beasiswa <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_beasiswa" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="internal">Internal (Kampus)</option>
                                <option value="eksternal">Eksternal (Pemerintah/Swasta)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Beasiswa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kategori_beasiswa" required placeholder="Contoh: KIP-Kuliah, Beasiswa BCA, dll">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Link Dokumen Bukti (Opsional)</label>
                            <input type="url" class="form-control" name="link_dokumen" placeholder="https://drive.google.com/...">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold" id="public-submit-btn" disabled>
                                <i class="bi bi-send me-1"></i> Kirim Data
                            </button>
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
                        <h5 class="fw-bold mb-0"><i class="bi bi-table me-2 text-primary"></i>Daftar Beasiswa yang Masuk</h5>
                        <form action="{{ route('portal.beasiswa') }}" method="GET" class="d-flex gap-2" style="max-width: 300px;">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Cari NIM, Nama..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                            @if(request('search'))
                                <a href="{{ route('portal.beasiswa') }}" class="btn btn-outline-danger" title="Reset"><i class="bi bi-x-lg"></i></a>
                            @endif
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>NIM & Nama</th>
                                    <th>Beasiswa</th>
                                    <th class="text-center">Dokumen</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($beasiswas as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $beasiswas->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->mahasiswa->nama ?? 'Tidak Diketahui' }}</div>
                                        <div class="small text-muted">{{ $item->nim }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->kategori_beasiswa }}</div>
                                        @if($item->jenis_beasiswa == 'internal')
                                            <span class="badge bg-primary rounded-pill px-2" style="font-size: 0.7rem;">Internal</span>
                                        @else
                                            <span class="badge bg-success rounded-pill px-2" style="font-size: 0.7rem;">Eksternal</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->link_dokumen)
                                            <a href="{{ $item->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1.5 px-2.5" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-circle" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit Data">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('portal.beasiswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Anda ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Data">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-clipboard-data display-6 d-block mb-3 text-light"></i>
                                        Belum ada data beasiswa yang diinputkan hari ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $beasiswas->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Beasiswa (Outside Table to prevent focus lock) -->
@foreach($beasiswas as $item)
<div class="modal fade" id="editModal{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header bg-warning text-dark border-0" style="border-radius: 16px 16px 0 0;">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Beasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('portal.beasiswa.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM & Nama</label>
                        <input type="text" class="form-control bg-light" value="{{ $item->nim }} - {{ $item->mahasiswa->nama ?? '' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Beasiswa <span class="text-danger">*</span></label>
                        <select class="form-select" name="jenis_beasiswa" required>
                            <option value="internal" {{ $item->jenis_beasiswa == 'internal' ? 'selected' : '' }}>Internal (Kampus)</option>
                            <option value="eksternal" {{ $item->jenis_beasiswa == 'eksternal' ? 'selected' : '' }}>Eksternal (Pemerintah/Swasta)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Beasiswa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kategori_beasiswa" value="{{ $item->kategori_beasiswa }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Dokumen Bukti</label>
                        <input type="url" class="form-control" name="link_dokumen" value="{{ $item->link_dokumen }}">
                        <small class="text-muted">Masukkan link valid dari Google Drive dsb jika ada perubahan dokumen.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
<!-- End Modal Edit -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnCekNim = document.getElementById('public-cek-nim');
        const inputNim = document.getElementById('public-nim');
        const inputNama = document.getElementById('public-nama');
        const btnSubmit = document.getElementById('public-submit-btn');
        const feedback = document.getElementById('public-feedback');

        btnCekNim.addEventListener('click', function() {
            const nim = inputNim.value;
            if(!nim) return;
            
            btnCekNim.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            
            fetch(`/portal-beasiswa/get-mahasiswa/${nim}`)
                .then(res => res.json())
                .then(data => {
                    btnCekNim.innerHTML = 'Cek';
                    if(data.success) {
                        inputNama.value = data.nama;
                        feedback.classList.add('d-none');
                        btnSubmit.removeAttribute('disabled');
                        inputNim.classList.remove('is-invalid');
                        inputNim.classList.add('is-valid');
                    } else {
                        inputNama.value = '';
                        feedback.classList.remove('d-none');
                        btnSubmit.setAttribute('disabled', 'true');
                        inputNim.classList.add('is-invalid');
                        inputNim.classList.remove('is-valid');
                    }
                })
                .catch(err => {
                    btnCekNim.innerHTML = 'Cek';
                    console.error(err);
                });
        });
    });
</script>
@endsection
