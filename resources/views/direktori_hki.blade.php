@extends('layouts.public')

@section('title', 'Direktori HKI & Inovasi')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold mb-3 border border-success border-opacity-25"><i class="bi bi-lightbulb-fill me-1"></i> Kekayaan Intelektual</span>
            <h1 class="fw-bold mb-3">Direktori HKI</h1>
            <p class="text-muted lead fs-6">Eksplorasi seluruh karya cipta, inovasi, dan hak kekayaan intelektual (HKI) hasil kolaborasi luar biasa antara mahasiswa dan dosen program studi kami.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="d-flex justify-content-lg-end mb-3">
                <button type="button" class="btn btn-warning rounded-pill fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalTambahHki">
                    <i class="bi bi-plus-circle me-1"></i> Laporkan HKI Baru
                </button>
            </div>
            <form action="{{ route('direktori-hki') }}" method="GET" class="d-flex p-2 bg-white rounded-pill shadow-sm border">
                <input type="text" name="search" class="form-control border-0 bg-transparent ms-3" placeholder="Cari judul, jenis HKI, nama..." value="{{ request('search') }}" style="box-shadow: none;">
                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Cari</button>
            </form>
            @if(request('search'))
                <div class="mt-2 text-end text-muted small">
                    Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                    <a href="{{ route('direktori-hki') }}" class="text-decoration-none ms-2 text-danger"><i class="bi bi-x-circle"></i> Reset</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistik HKI Section -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm p-4" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(52, 211, 153, 0.1)); border-left: 5px solid var(--bs-success) !important;">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center border-end mb-3 mb-md-0">
                        <div class="display-4 fw-bold text-success mb-2">{{ $statTotalHki }}</div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">Total HKI Terdaftar</div>
                    </div>
                    <div class="col-md-9">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Sebaran Jenis HKI</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse($statHkiPerJenis as $jenis => $jumlah)
                                        <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center" style="font-size: 0.9rem;">
                                            <span class="fw-semibold me-2 text-dark">{{ $jenis }}</span>
                                            <span class="badge bg-primary rounded-pill">{{ $jumlah }}</span>
                                        </div>
                                    @empty
                                        <div class="text-muted small">Belum ada data jenis HKI</div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-5">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-bar-chart-fill text-warning me-2"></i>Tren 5 Tahun Terakhir</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse($statHkiPerTahun as $tahun => $jumlah)
                                        <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center" style="font-size: 0.9rem;">
                                            <span class="fw-semibold me-2 text-dark">{{ $tahun }}</span>
                                            <span class="badge bg-warning text-dark rounded-pill">{{ $jumlah }}</span>
                                        </div>
                                    @empty
                                        <div class="text-muted small">Belum ada data tren tahun</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($hkiList->isEmpty())
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-3"><i class="bi bi-search"></i></div>
            <h4 class="text-muted">Tidak ada data HKI yang ditemukan.</h4>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            @foreach($hkiList as $hki)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                    <div class="card h-100 border-0 rounded-4 shadow-sm" style="transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.05)';">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-award-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="badge bg-success mb-1">{{ $hki->jenis_ciptaan }}</div>
                                    <div class="text-muted small"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($hki->tgl_permohonan)->format('d M Y') }}</div>
                                </div>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem; line-height: 1.4;">{{ $hki->judul_ciptaan }}</h5>
                            
                            <div class="mt-3 mb-3 p-3 bg-light rounded-3 border">
                                <div class="text-muted small mb-1">Kolaborator/Pencipta:</div>
                                @if($hki->kode_dosen)
                                    <div class="fw-bold text-primary mb-1"><i class="bi bi-person-video3 me-1"></i> {{ $hki->nama_dosen }}</div>
                                @endif
                                @if($hki->nim)
                                    <div class="fw-bold text-success"><i class="bi bi-person-fill me-1"></i> {{ $hki->mahasiswa ? $hki->mahasiswa->nama : 'Mahasiswa' }}</div>
                                @endif
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <div class="small fw-bold text-secondary">
                                    <i class="bi bi-upc-scan me-1"></i> {{ $hki->no_permohonan }}
                                </div>
                                @if($hki->link_dokumen)
                                    <a href="{{ $hki->link_dokumen }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $hkiList->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Modal Tambah HKI -->
<div class="modal fade" id="modalTambahHki" tabindex="-1" aria-labelledby="modalTambahHkiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-success text-white rounded-top-4 border-0">
                <h5 class="modal-title fw-bold" id="modalTambahHkiLabel"><i class="bi bi-lightbulb-fill me-2"></i>Form Pelaporan HKI Mahasiswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('direktori-hki.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">NIM Mahasiswa <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                                <input type="text" class="form-control" name="nim" id="nim" required placeholder="Masukkan NIM Anda">
                                <button class="btn btn-outline-secondary" type="button" id="btnCekNim">Cek NIM</button>
                            </div>
                            <div id="namaMahasiswaResult" class="form-text text-success fw-bold mt-1" style="display: none;"></div>
                            <div id="namaMahasiswaError" class="form-text text-danger fw-bold mt-1" style="display: none;">NIM tidak ditemukan.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Judul Ciptaan / Inovasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul_ciptaan" required placeholder="Misal: Sistem Informasi Manajemen...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Ciptaan <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_ciptaan" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Hak Cipta">Hak Cipta (Copyright)</option>
                                <option value="Paten">Paten</option>
                                <option value="Paten Sederhana">Paten Sederhana</option>
                                <option value="Desain Industri">Desain Industri</option>
                                <option value="Merek">Merek</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nomor Permohonan / Pencatatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_permohonan" required placeholder="Contoh: EC002023...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Permohonan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_permohonan" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dosen Pembimbing / Kolaborator (Opsional)</label>
                            <select class="form-select" name="kode_dosen[]" multiple size="4">
                                @foreach($dosenList ?? [] as $dosen)
                                    <option value="{{ $dosen->kode_dosen }}">{{ $dosen->nama_dosen }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih jika HKI hasil kolaborasi (Tahan tombol <strong>Ctrl</strong> atau <strong>Cmd</strong> untuk memilih lebih dari satu dosen). Biarkan kosong jika tidak ada.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Link Dokumen Sertifikat (Opsional)</label>
                            <input type="url" class="form-control" name="link_dokumen" placeholder="Contoh: https://drive.google.com/...">
                            <div class="form-text">Pastikan link Google Drive atau penyimpanan lain bisa diakses publik (Anyone with the link).</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold"><i class="bi bi-save me-1"></i> Simpan Data HKI</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success') || $errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('modalTambahHki'));
            myModal.show();
        @endif

        const btnCekNim = document.getElementById('btnCekNim');
        const inputNim = document.getElementById('nim');
        const resultDiv = document.getElementById('namaMahasiswaResult');
        const errorDiv = document.getElementById('namaMahasiswaError');

        btnCekNim.addEventListener('click', function() {
            const nim = inputNim.value.trim();
            if (!nim) return;
            
            btnCekNim.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cek...';
            btnCekNim.disabled = true;
            resultDiv.style.display = 'none';
            errorDiv.style.display = 'none';

            fetch("{{ route('portal.kegiatan.cek-identitas') }}?identifier=" + nim)
                .then(response => response.json())
                .then(data => {
                    btnCekNim.innerHTML = 'Cek NIM';
                    btnCekNim.disabled = false;
                    
                    if (data.success && data.kategori === 'Mahasiswa') {
                        resultDiv.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Ditemukan: ' + data.nama;
                        resultDiv.style.display = 'block';
                    } else {
                        errorDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    btnCekNim.innerHTML = 'Cek NIM';
                    btnCekNim.disabled = false;
                    console.error('Error:', error);
                });
        });
    });
</script>
@endpush
