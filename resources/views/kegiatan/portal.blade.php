@extends('layouts.public')
@section('title', 'Portal Kegiatan & Event Mahasiswa')
@section('content')
    <style>
        body {
            background: radial-gradient(circle at 10% 20%, rgba(240, 244, 255, 1) 0%, rgba(249, 245, 255, 1) 90.1%) !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            border-radius: 24px;
            padding: 50px 40px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(99, 102, 241, 0.25);
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
        }
        .kegiatan-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .kegiatan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.12) !important;
            border-color: rgba(99, 102, 241, 0.25) !important;
        }
        .badge-status {
            font-size: 10px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .btn-portal {
            border-radius: 12px !important;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
    </style>
    <!-- Content -->
    <div class="container my-5">
        <div class="hero-section text-center text-md-start">
            <div class="row align-items-center g-4">
                <div class="col-md-8 position-relative z-index-10">
                    <span class="badge bg-primary mb-3 px-3 py-2" style="font-size: 11px; border-radius: 8px; font-weight: 700; letter-spacing: 0.5px;">E-PORTAL KEGIATAN & CERTIFICATE</span>
                    <h1 class="fw-extrabold text-white mb-3" style="font-size: 2.5rem; letter-spacing: -1px;">Portal Pendaftaran Kegiatan</h1>
                    <p class="text-white-50 lead mb-0" style="font-size: 1.1rem; font-weight: 400;">Daftarkan diri Anda secara mandiri pada kegiatan program studi, unggah bukti pembayaran, lakukan absensi mandiri, dan dapatkan e-sertifikat langsung.</p>
                </div>
                <div class="col-md-4 text-center d-none d-md-block position-relative z-index-10">
                    <i class="bi bi-calendar-event text-white-50" style="font-size: 6.5rem; filter: drop-shadow(0 10px 20px rgba(99,102,241,0.3));"></i>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="close" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="close" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse ($kegiatans as $k)
                @php
                    $today = \Carbon\Carbon::now()->startOfDay();
                    $buka = $k->tgl_pendaftaran_buka ? \Carbon\Carbon::parse($k->tgl_pendaftaran_buka)->startOfDay() : null;
                    $tutup = $k->tgl_pendaftaran_tutup ? \Carbon\Carbon::parse($k->tgl_pendaftaran_tutup)->endOfDay() : null;
                    
                    if ($buka && $today->lt($buka)) {
                        $statusPendaftaran = 'belum_buka';
                    } elseif ($tutup && $today->gt($tutup)) {
                        $statusPendaftaran = 'tutup';
                    } else {
                        $statusPendaftaran = 'buka';
                    }
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="kegiatan-card h-100">
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        @if ($statusPendaftaran === 'belum_buka')
                                            <span class="badge bg-warning text-dark badge-status"><i class="bi bi-clock me-1"></i>Belum Buka</span>
                                        @elseif ($statusPendaftaran === 'tutup')
                                            <span class="badge bg-danger badge-status"><i class="bi bi-x-circle me-1"></i>Tutup</span>
                                        @else
                                            <span class="badge bg-success badge-status"><i class="bi bi-check-circle me-1"></i>Buka</span>
                                        @endif

                                        @if($loop->first)
                                            <span class="badge bg-primary text-white badge-status ms-1" style="background: linear-gradient(45deg, #4f46e5, #ec4899) !important; border: none; box-shadow: 0 4px 10px rgba(236,72,153,0.3);"><i class="bi bi-stars me-1 text-warning"></i>Terbaru</span>
                                        @endif
                                    </div>

                                    <!-- Price Badge -->
                                    @if($k->jenis_kegiatan === 'berbayar')
                                        <span class="badge bg-danger badge-status"><i class="bi bi-cash-coin me-1"></i>Rp {{ number_format($k->harga, 0, ',', '.') }}</span>
                                    @else
                                        <span class="badge bg-success badge-status"><i class="bi bi-gift-fill me-1"></i>Gratis</span>
                                    @endif
                                </div>
                                <h4 class="fw-bold text-dark mb-3">{{ $k->nama_kegiatan }}</h4>
                                
                                <div class="mb-2 text-muted small">
                                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                                    Pelaksanaan: {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="mb-2 text-muted small">
                                    <i class="bi bi-calendar-range me-2 text-info"></i>
                                    Pendaftaran: {{ $k->tgl_pendaftaran_buka ? \Carbon\Carbon::parse($k->tgl_pendaftaran_buka)->translatedFormat('d M') : '-' }} s/d {{ $k->tgl_pendaftaran_tutup ? \Carbon\Carbon::parse($k->tgl_pendaftaran_tutup)->translatedFormat('d M Y') : '-' }}
                                </div>
                                <div class="mb-2 text-muted small">
                                    <i class="bi bi-geo-alt me-2 text-danger"></i>
                                    {{ $k->tempat }}
                                </div>
                                @if($k->narasumber)
                                    <div class="mb-2 text-muted small">
                                        <i class="bi bi-person-workspace me-2 text-warning"></i>
                                        Narasumber: {{ $k->narasumber }}
                                    </div>
                                @endif
                                @if($k->deskripsi)
                                    <p class="text-muted small mt-3 bg-light p-2.5 rounded">{{ Str::limit($k->deskripsi, 120) }}</p>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                @if ($statusPendaftaran === 'belum_buka')
                                    <button class="btn btn-warning w-100 disabled py-2.5 text-dark fw-bold mb-2" style="border-radius: 10px;">
                                        <i class="bi bi-clock-fill me-1"></i> Pendaftaran Belum Buka
                                    </button>
                                @elseif ($statusPendaftaran === 'tutup')
                                    <button class="btn btn-secondary w-100 disabled py-2.5 mb-2" style="border-radius: 10px;">
                                        <i class="bi bi-lock-fill me-1"></i> Pendaftaran Ditutup
                                    </button>
                                @else
                                    <button class="btn btn-primary w-100 py-2.5 mb-2" style="border-radius: 10px;" 
                                            onclick="openRegisterModal('{{ $k->id }}', '{{ $k->nama_kegiatan }}', '{{ $k->jenis_kegiatan }}', '{{ number_format($k->harga, 0, ',', '.') }}', '{{ addslashes($k->rekening_info) }}')">
                                        <i class="bi bi-person-plus-fill me-1"></i> Daftar Mandiri
                                    </button>
                                @endif

                                <!-- View participants check button -->
                                <button class="btn btn-outline-primary w-100 py-2 mb-2" style="border-radius: 10px;" 
                                        onclick="openPesertaListModal('{{ $k->id }}', '{{ $k->nama_kegiatan }}', '{{ $k->jenis_kegiatan }}', '{{ addslashes($k->rekening_info) }}', {{ $k->pesertas->toJson() }})">
                                    <i class="bi bi-people-fill me-1"></i> Cek Peserta / Cetak Kartu / Bayar
                                </button>

                                @if((Str::contains(strtolower($k->tempat), 'online') || Str::contains(strtolower($k->tempat), 'zoom') || Str::contains(strtolower($k->tempat), 'meet')) && ($k->pin_masuk || $k->pin_pulang))
                                    <!-- Presensi Mandiri Online Button -->
                                    <button class="btn btn-outline-success w-100 py-2" style="border-radius: 10px;" 
                                            onclick="openPresensiOnlineModal('{{ $k->id }}', '{{ $k->nama_kegiatan }}')">
                                        <i class="bi bi-laptop me-1"></i> Presensi Mandiri (Online)
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="card p-5 max-width-500 mx-auto text-muted">
                        <i class="bi bi-calendar-x fs-1 mb-2 text-muted-50"></i>
                        <p class="mb-0 fw-semibold">Belum ada data kegiatan prodi yang terdaftar.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                <form id="regForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-dark text-white py-3">
                        <h5 class="modal-title fw-bold" id="registerModalLabel"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Pendaftaran Peserta</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h6 class="fw-semibold text-primary mb-3" id="modalKegiatanNama">Nama Kegiatan</h6>
                        
                        <!-- Identifier (NIM/NIP) -->
                        <div class="mb-3">
                            <label for="identifier" class="form-label fw-semibold small">NIM / NIP / NIK <span class="text-danger">*</span></label>
                            <input type="text" name="identifier" id="identifier" class="form-control py-2" required placeholder="Masukkan NIM / NIP / Nomor Identitas">
                            <div id="identitas-feedback" class="form-text mt-1 text-success fw-bold" style="font-size: 11px; display: none;"></div>
                        </div>
                        
                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control py-2" required placeholder="Masukkan Nama Lengkap Anda">
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-semibold small">Kategori Peserta <span class="text-danger">*</span></label>
                            <select name="kategori" id="kategori" class="form-select py-2" required>
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Tendik">Tenaga Kependidikan</option>
                                <option value="Umum">Umum</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Foto Peserta -->
                        <div class="mb-3">
                            <label for="foto" class="form-label fw-semibold small text-primary">Unggah Foto Profil <span class="text-danger">*</span></label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required>
                            <div class="form-text text-muted" style="font-size: 10px;">Foto ini akan ditampilkan di kartu peserta Anda. Format: JPG, PNG. Maks: 2MB.</div>
                        </div>

                        <!-- Paid Event Sections -->
                        <div id="portalPaymentSection" style="display: none;">
                            <hr class="my-3">
                            <div class="alert alert-info border-0 py-2.5 small mb-3 text-dark" style="background-color: #eff6ff; border-radius: 10px;">
                                <div class="fw-bold text-primary mb-1"><i class="bi bi-wallet2 me-1"></i>Metode Pembayaran:</div>
                                <span id="portalTicketPrice" class="badge bg-danger mb-2 fs-7">Rp 0</span>
                                <div id="portalBankInstructions" class="bg-white p-2.5 rounded border small text-dark mt-1" style="white-space: pre-line;"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="bukti_pembayaran" class="form-label fw-semibold small text-danger">Unggah Bukti Transfer / Bayar <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*">
                                <div class="form-text text-muted" style="font-size: 10px;">Format: JPG, JPEG, PNG. Maks: 2MB.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-3 bg-light border-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Daftar Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Peserta List Modal -->
    <div class="modal fade" id="pesertaListModal" tabindex="-1" aria-labelledby="pesertaListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold" id="pesertaListModalLabel"><i class="bi bi-people-fill me-2 text-primary"></i>Status Pendaftaran & Kartu Kegiatan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="fw-bold text-primary mb-3" id="pesertaListKegiatanNama">Nama Kegiatan</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle small mb-0 bg-white">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">No</th>
                                    <th>Foto</th>
                                    <th>Nama Peserta</th>
                                    <th>NIM/NIP</th>
                                    <th>Kategori</th>
                                    <th>Status Bayar</th>
                                    <th class="text-center" style="width: 25%;">Aksi / Kartu</th>
                                </tr>
                            </thead>
                            <tbody id="pesertaListTableBody">
                                <!-- Dynamic -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Bukti Modal (Dual Layered) -->
    <div class="modal fade" id="uploadBuktiModal" tabindex="-1" aria-labelledby="uploadBuktiModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.3);">
                <form id="uploadBuktiForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white py-3">
                        <h5 class="modal-title fw-bold" id="uploadBuktiModalLabel"><i class="bi bi-wallet2 me-2"></i>Kirim Bukti Pembayaran</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 text-dark small mb-3" style="background-color: #eff6ff; border-radius: 10px;">
                            <div class="fw-bold text-primary mb-1"><i class="bi bi-info-circle-fill me-1"></i>Petunjuk Pembayaran:</div>
                            <div id="uploadBuktiInstructions" style="white-space: pre-line; font-size: 11px;"></div>
                        </div>

                        <div class="mb-3">
                            <label for="bukti_pembayaran_upload" class="form-label fw-semibold small">Pilih File Foto Bukti Transfer <span class="text-danger">*</span></label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran_upload" class="form-control" accept="image/*" required>
                            <div class="form-text text-muted" style="font-size: 10px;">Format: JPG, JPEG, PNG. Maks: 2MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer p-3 bg-light border-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Kirim Bukti</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload Foto Modal (Dual Layered) -->
    <div class="modal fade" id="uploadFotoModal" tabindex="-1" aria-labelledby="uploadFotoModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.3);">
                <form id="uploadFotoForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white py-3">
                        <h5 class="modal-title fw-bold" id="uploadFotoModalLabel"><i class="bi bi-camera me-2"></i>Unggah / Ganti Foto Profil</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="foto_upload_field" class="form-label fw-semibold small">Pilih File Foto Profil <span class="text-danger">*</span></label>
                            <input type="file" name="foto" id="foto_upload_field" class="form-control" accept="image/*" required>
                            <div class="form-text text-muted" style="font-size: 10px;">Format: JPG, JPEG, PNG. Maks: 2MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer p-3 bg-light border-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;">Simpan Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Presensi Online Modal -->
    <div class="modal fade" id="presensiOnlineModal" tabindex="-1" aria-labelledby="presensiOnlineModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.3);">
                <form id="presensiOnlineForm" action="" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white py-3">
                        <h5 class="modal-title fw-bold" id="presensiOnlineModalLabel"><i class="bi bi-laptop me-2"></i>Presensi Mandiri (Zoom/Online)</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <h6 class="fw-bold text-success mb-3" id="presensiOnlineKegiatanNama">Nama Kegiatan</h6>

                        <!-- NIM / NIP / Ticket Token -->
                        <div class="mb-3">
                            <label for="presensi_identifier" class="form-label fw-semibold small">NIM / NIP / Token Tiket <span class="text-danger">*</span></label>
                            <input type="text" name="identifier" id="presensi_identifier" class="form-control" required placeholder="Contoh: 12210001 / token tiket">
                        </div>

                        <!-- PIN Verification Code -->
                        <div class="mb-3">
                            <label for="presensi_pin" class="form-label fw-semibold small text-success">Masukkan PIN Presensi (dari Zoom) <span class="text-danger">*</span></label>
                            <input type="text" name="pin" id="presensi_pin" class="form-control fw-bold text-center" style="font-size: 1.2rem; letter-spacing: 2px;" required placeholder="Masukkan PIN dari zoom">
                            <div class="form-text text-muted" style="font-size: 10px;">PIN presensi masuk/pulang dibagikan oleh pemandu acara selama sesi online berlangsung.</div>
                        </div>
                    </div>
                    <div class="modal-footer p-3 bg-light border-0 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-success" style="border-radius: 10px;">Kirim Presensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openRegisterModal(kegiatanId, kegiatanNama, jenisKegiatan, harga, rekeningInfo) {
            document.getElementById('modalKegiatanNama').innerText = kegiatanNama;
            
            // Set dynamic action URL
            const actionUrl = "{{ url('/kegiatan-portal') }}/" + kegiatanId + "/daftar";
            document.getElementById('regForm').action = actionUrl;

            const paymentSection = document.getElementById('portalPaymentSection');
            const fileInput = document.getElementById('bukti_pembayaran');

            if (jenisKegiatan === 'berbayar') {
                paymentSection.style.display = 'block';
                document.getElementById('portalTicketPrice').innerText = 'Biaya: Rp ' + harga;
                document.getElementById('portalBankInstructions').innerText = rekeningInfo;
                fileInput.required = true;
            } else {
                paymentSection.style.display = 'none';
                fileInput.required = false;
                fileInput.value = '';
            }
            
            // Show modal
            const myModal = new bootstrap.Modal(document.getElementById('registerModal'));
            myModal.show();
        }

        function openPesertaListModal(kegiatanId, kegiatanNama, jenisKegiatan, rekeningInfo, pesertas) {
            document.getElementById('pesertaListKegiatanNama').innerText = kegiatanNama;
            const tbody = document.getElementById('pesertaListTableBody');
            tbody.innerHTML = '';

            if (pesertas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">Belum ada peserta terdaftar.</td></tr>';
            } else {
                pesertas.forEach((p, idx) => {
                    let statusBadge = '';
                    let actionBtn = '';
                    let fotoHtml = '';

                    // Foto rendering
                    if (p.foto) {
                        const imgUrl = "{{ asset('storage') }}/" + p.foto;
                        fotoHtml = `
                            <div class="d-flex align-items-center">
                                <img src="${imgUrl}" style="width: 32px; height: 42px; object-fit: cover; border-radius: 4px;" class="border shadow-sm me-1">
                                <button class="btn btn-xs btn-link p-0 text-decoration-none" style="font-size: 10px;" onclick="openUploadFotoModal('${p.id}')">Ganti</button>
                            </div>
                        `;
                    } else {
                        fotoHtml = `<button class="btn btn-xs btn-outline-danger py-0.5 px-1.5" style="font-size: 10px; border-radius: 4px;" onclick="openUploadFotoModal('${p.id}')"><i class="bi bi-camera me-1"></i>Upload</button>`;
                    }

                    if (jenisKegiatan === 'berbayar') {
                        if (p.status_pembayaran === 'belum_bayar') {
                            statusBadge = '<span class="badge bg-danger">Belum Bayar</span>';
                            actionBtn = `<button class="btn btn-xs btn-primary px-2.5 py-1 text-white me-1 mb-1" style="font-size: 11px; border-radius: 6px;" onclick="openUploadBuktiModal('${p.id}', '${rekeningInfo}')"><i class="bi bi-wallet2 me-1"></i>Bayar Now</button>`;
                        } else if (p.status_pembayaran === 'menunggu_verifikasi') {
                            statusBadge = '<span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i>Verifikasi Admin</span>';
                            actionBtn = `<span class="text-muted small">Menunggu Verifikasi</span>`;
                        } else if (p.status_pembayaran === 'lunas') {
                            statusBadge = '<span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>Lunas</span>';
                            const cardUrl = "{{ url('/kegiatan-portal/kartu') }}/" + p.id;
                            actionBtn = `<a href="${cardUrl}" target="_blank" class="btn btn-xs btn-outline-success px-2.5 py-1 me-1 mb-1" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-printer-fill me-1"></i>Cetak Kartu</a>`;
                            
                            if (p.status_kehadiran === 'hadir_lengkap') {
                                const certUrl = "{{ url('/kegiatan-portal') }}/" + kegiatanId + "/peserta/" + p.id + "/sertifikat";
                                actionBtn += `<a href="${certUrl}" target="_blank" class="btn btn-xs btn-success px-2.5 py-1" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-award-fill me-1"></i>Sertifikat</a>`;
                            }
                        }
                    } else {
                        statusBadge = '<span class="badge bg-success">Gratis</span>';
                        const cardUrl = "{{ url('/kegiatan-portal/kartu') }}/" + p.id;
                        actionBtn = `<a href="${cardUrl}" target="_blank" class="btn btn-xs btn-outline-success px-2.5 py-1 me-1 mb-1" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-printer-fill me-1"></i>Cetak Kartu</a>`;
                        
                        if (p.status_kehadiran === 'hadir_lengkap') {
                            const certUrl = "{{ url('/kegiatan-portal') }}/" + kegiatanId + "/peserta/" + p.id + "/sertifikat";
                            actionBtn += `<a href="${certUrl}" target="_blank" class="btn btn-xs btn-success px-2.5 py-1" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-award-fill me-1"></i>Sertifikat</a>`;
                        }
                    }

                    const row = `
                        <tr>
                            <td class="text-center fw-bold text-muted">${idx + 1}</td>
                            <td>${fotoHtml}</td>
                            <td class="fw-bold">${p.nama}</td>
                            <td>${p.identifier}</td>
                            <td>${p.kategori}</td>
                            <td>${statusBadge}</td>
                            <td class="text-center">${actionBtn}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            }

            const myModal = new bootstrap.Modal(document.getElementById('pesertaListModal'));
            myModal.show();
        }

        function openUploadBuktiModal(pesertaId, rekeningInfo) {
            // Close Peserta list modal first
            const listModalEl = document.getElementById('pesertaListModal');
            const listModal = bootstrap.Modal.getInstance(listModalEl);
            if (listModal) {
                listModal.hide();
            }

            // Set Form action URL
            const actionUrl = "{{ url('/kegiatan-portal/peserta') }}/" + pesertaId + "/upload-bukti";
            document.getElementById('uploadBuktiForm').action = actionUrl;
            document.getElementById('uploadBuktiInstructions').innerText = rekeningInfo;

            // Show Upload modal
            const uploadModal = new bootstrap.Modal(document.getElementById('uploadBuktiModal'));
            uploadModal.show();
        }

        function openUploadFotoModal(pesertaId) {
            // Close Peserta list modal first
            const listModalEl = document.getElementById('pesertaListModal');
            const listModal = bootstrap.Modal.getInstance(listModalEl);
            if (listModal) {
                listModal.hide();
            }

            // Set Form action URL
            const actionUrl = "{{ url('/kegiatan-portal/peserta') }}/" + pesertaId + "/upload-foto";
            document.getElementById('uploadFotoForm').action = actionUrl;

            // Show Upload modal
            const uploadModal = new bootstrap.Modal(document.getElementById('uploadFotoModal'));
            uploadModal.show();
        }

        function openPresensiOnlineModal(kegiatanId, kegiatanNama) {
            document.getElementById('presensiOnlineKegiatanNama').innerText = kegiatanNama;
            
            // Set Form action URL
            const actionUrl = "{{ url('/kegiatan-portal') }}/" + kegiatanId + "/presensi-mandiri";
            document.getElementById('presensiOnlineForm').action = actionUrl;

            // Clear inputs
            document.getElementById('presensi_identifier').value = '';
            document.getElementById('presensi_pin').value = '';

            // Show Presensi modal
            const presensiModal = new bootstrap.Modal(document.getElementById('presensiOnlineModal'));
            presensiModal.show();
        }

        // Auto Detect Identitas (Vanilla JS)
        let identitasTimeout;
        const identifierInput = document.getElementById('identifier');
        const namaInput = document.getElementById('nama');
        const kategoriSelect = document.getElementById('kategori');
        const feedbackDiv = document.getElementById('identitas-feedback');

        if (identifierInput) {
            identifierInput.addEventListener('input', function() {
                clearTimeout(identitasTimeout);
                let val = this.value.trim();
                
                if (val.length < 3) {
                    feedbackDiv.style.display = 'none';
                    namaInput.readOnly = false;
                    namaInput.classList.remove('bg-light');
                    return;
                }
                
                identitasTimeout = setTimeout(function() {
                    fetch("{{ route('portal.kegiatan.cek-identitas') }}?identifier=" + encodeURIComponent(val))
                        .then(response => response.json())
                        .then(res => {
                            if (res.success) {
                                namaInput.value = res.nama;
                                namaInput.readOnly = true;
                                namaInput.classList.add('bg-light');
                                kategoriSelect.value = res.kategori;
                                
                                feedbackDiv.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Terdeteksi sebagai ' + res.kategori + ' (Otomatis)';
                                feedbackDiv.style.display = 'block';
                                feedbackDiv.classList.remove('text-danger');
                                feedbackDiv.classList.add('text-success');
                            } else {
                                namaInput.readOnly = false;
                                namaInput.classList.remove('bg-light');
                                
                                feedbackDiv.innerHTML = 'Data tidak ditemukan di Master (Isi manual)';
                                feedbackDiv.style.display = 'block';
                                feedbackDiv.classList.remove('text-success');
                                feedbackDiv.classList.add('text-danger');
                            }
                        })
                        .catch(err => console.error(err));
                }, 600); // 600ms debounce
            });
        }
    </script>
@endsection
