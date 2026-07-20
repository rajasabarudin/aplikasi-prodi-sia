@extends('layouts.public')

@section('title', 'Portal Tracer Study Alumni')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold mb-3 border border-primary border-opacity-25">Portal Alumni</span>
                <h2 class="fw-bold mb-3">Kuesioner Tracer Study</h2>
                <p class="text-muted">Partisipasi Anda sangat berarti untuk evaluasi dan akreditasi Program Studi. Data Anda akan dijaga kerahasiaannya.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center" role="alert" data-aos="fade-in">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <h5 class="alert-heading fw-bold mb-1">Berhasil!</h5>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <h5 class="alert-heading fw-bold mb-0">Terdapat Kesalahan</h5>
                    </div>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow rounded-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('portal.alumni.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Cek Identitas -->
                        <div class="mb-4 pb-4 border-bottom">
                            <label class="form-label fw-bold text-primary mb-3"><i class="bi bi-search me-2"></i>Cek Data Alumni</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control form-control-lg" id="cek_nim" placeholder="Masukkan NIM Anda">
                                <button class="btn btn-primary px-4" type="button" id="btn-cek">Cek Data</button>
                            </div>
                            <div id="cek-result" class="small mt-2 d-none"></div>
                        </div>

                        <!-- Data Diri -->
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-person-vcard me-2 text-primary"></i>1. Identitas Alumni</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                <input type="text" name="nim" id="nim" class="form-control" required readonly placeholder="Isi NIM di atas">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control" required placeholder="Nama lengkap Anda">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tahun Masuk <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_masuk" id="tahun_masuk" class="form-control" min="2000" max="{{ date('Y') }}" required placeholder="Contoh: 2018">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tahun Lulus <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_lulus" id="tahun_lulus" class="form-control" min="2000" max="{{ date('Y')+1 }}" required placeholder="Contoh: 2022">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">IPK Akhir <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="ipk" id="ipk" class="form-control" min="0" max="4" required placeholder="Contoh: 3.75">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">No. WhatsApp</label>
                                <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="0812...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Email Aktif</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="email@gmail.com">
                            </div>
                        </div>

                        <!-- Data Tracer Study -->
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-briefcase me-2 text-success"></i>2. Kuesioner Tracer Study (Status Saat Ini)</h5>
                        <div class="row g-3 mb-5 bg-light p-3 rounded-4 border">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Status Pekerjaan Saat Ini <span class="text-danger">*</span></label>
                                <select name="status_kerja" id="status_kerja" class="form-select" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Bekerja">Bekerja (Pegawai/Karyawan)</option>
                                    <option value="Wirausaha">Wirausaha / Memiliki Usaha Sendiri</option>
                                    <option value="Melanjutkan Studi">Melanjutkan Studi (S2/S3)</option>
                                    <option value="Belum Bekerja">Belum Bekerja / Sedang Mencari Kerja</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Waktu Tunggu Mendapat Kerja (Bulan)</label>
                                <input type="number" name="waktu_tunggu" id="waktu_tunggu" class="form-control" min="0" placeholder="0 jika sebelum lulus">
                                <div class="form-text">Berapa bulan setelah lulus Anda mendapatkan pekerjaan pertama?</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kesesuaian Bidang Ilmu</label>
                                <select name="kesesuaian_bidang" id="kesesuaian_bidang" class="form-select">
                                    <option value="">-- Pilih Kesesuaian --</option>
                                    <option value="Sangat Sesuai">Sangat Sesuai</option>
                                    <option value="Sesuai">Sesuai</option>
                                    <option value="Kurang Sesuai">Kurang Sesuai</option>
                                    <option value="Tidak Sesuai">Tidak Sesuai</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Perusahaan / Tempat Usaha / Kampus Lanjut</label>
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" placeholder="PT... / Universitas...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tingkat Tempat Kerja</label>
                                <select name="tingkat_tempat_kerja" id="tingkat_tempat_kerja" class="form-select">
                                    <option value="">-- Pilih Tingkat --</option>
                                    <option value="Lokal">Lokal (Kabupaten/Provinsi)</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional / Multinasional</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Posisi / Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Contoh: Staff IT / Direktur">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Perkiraan Pendapatan Pertama (Rp)</label>
                                <input type="number" name="pendapatan_pertama" id="pendapatan_pertama" class="form-control" placeholder="Contoh: 5000000">
                                <div class="form-text">Data ini bersifat rahasia untuk IKU prodi.</div>
                            </div>
                        </div>

                        <!-- Data Publik / Kisah Sukses -->
                        <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-star me-2 text-warning"></i>3. Profil Publik & Kisah Sukses (Opsional)</h5>
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-4 rounded-4">
                            <i class="bi bi-info-circle-fill me-2"></i> Data di bawah ini bersifat opsional. Jika diisi, profil Anda berkesempatan untuk ditampilkan di <strong>Halaman Beranda</strong> sebagai Alumni Inspiratif untuk memotivasi mahasiswa aktif.
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Foto Profil Profesional (Max 2MB)</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL Instagram</label>
                                <input type="url" name="instagram_url" id="instagram_url" class="form-control" placeholder="https://instagram.com/nama">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Testimoni / Pesan Kesan</label>
                                <textarea name="testimoni" id="testimoni" class="form-control" rows="4" placeholder="Bagaimana kesan Anda berkuliah di Prodi ini? Berikan pesan motivasi untuk adik tingkat..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                <i class="bi bi-send-fill me-2"></i>Kirim Data Tracer Study
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('btn-cek').addEventListener('click', function() {
        const nim = document.getElementById('cek_nim').value;
        const resultDiv = document.getElementById('cek-result');
        const nimInput = document.getElementById('nim');
        
        if (!nim) {
            resultDiv.className = 'small mt-2 text-danger fw-bold';
            resultDiv.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Masukkan NIM terlebih dahulu.';
            return;
        }

        resultDiv.className = 'small mt-2 text-info fw-bold';
        resultDiv.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Sedang memeriksa data...';

        fetch(`/portal-alumni/get-alumni/${nim}`)
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    nimInput.value = data.nim;
                    document.getElementById('nama').value = data.nama || '';
                    document.getElementById('tahun_masuk').value = data.tahun_masuk || '';
                    document.getElementById('tahun_lulus').value = data.tahun_lulus || '';
                    document.getElementById('ipk').value = data.ipk || '';
                    document.getElementById('no_telepon').value = data.no_telepon || '';
                    document.getElementById('email').value = data.email || '';
                    
                    if (data.tracer_study) {
                        const ts = data.tracer_study;
                        document.getElementById('status_kerja').value = ts.status_kerja || '';
                        document.getElementById('waktu_tunggu').value = ts.waktu_tunggu !== null ? ts.waktu_tunggu : '';
                        document.getElementById('kesesuaian_bidang').value = ts.kesesuaian_bidang || '';
                        document.getElementById('nama_perusahaan').value = ts.nama_perusahaan || '';
                        document.getElementById('tingkat_tempat_kerja').value = ts.tingkat_tempat_kerja || '';
                        document.getElementById('jabatan').value = ts.jabatan || '';
                        document.getElementById('pendapatan_pertama').value = ts.pendapatan_pertama || '';
                    }
                    
                    document.getElementById('instagram_url').value = data.instagram_url || '';
                    document.getElementById('testimoni').value = data.testimoni || '';

                    resultDiv.className = 'small mt-2 text-success fw-bold';
                    resultDiv.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Data ditemukan! Silakan perbarui data Tracer Study Anda.';
                } else {
                    nimInput.value = nim;
                    // Reset other fields
                    document.getElementById('nama').value = '';
                    
                    resultDiv.className = 'small mt-2 text-primary fw-bold';
                    resultDiv.innerHTML = '<i class="bi bi-info-circle-fill me-1"></i> Anda belum terdaftar. Silakan lengkapi form di bawah sebagai data baru.';
                }
            })
            .catch(error => {
                console.error(error);
                resultDiv.className = 'small mt-2 text-danger fw-bold';
                resultDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan saat memeriksa data.';
            });
    });
</script>
@endpush
@endsection
