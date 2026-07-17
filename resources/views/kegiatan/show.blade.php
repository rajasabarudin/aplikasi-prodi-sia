@extends('layouts.app')

@section('title', 'Detail Kegiatan: ' . $kegiatan->nama_kegiatan)

@section('content')
@php
    $pendingPayments = $pesertas->where('status_pembayaran', 'menunggu_verifikasi');
@endphp

@if($kegiatan->jenis_kegiatan === 'berbayar' && $pendingPayments->count() > 0)
    <!-- Pulsing Admin Alert for self-registered participants awaiting payment verification -->
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex justify-content-between align-items-center p-3" style="border-radius: 12px; border-left: 5px solid #d97706 !important;">
        <div>
            <i class="bi bi-bell-fill me-2 text-warning fs-5"></i>
            Ada <strong>{{ $pendingPayments->count() }} pendaftar baru</strong> yang mengirimkan bukti transfer dan sedang menunggu verifikasi pembayaran.
        </div>
        <a href="#tabel-peserta" class="btn btn-sm btn-warning fw-bold text-dark"><i class="bi bi-arrow-down-short me-1"></i>Verifikasi Sekarang</a>
    </div>
@endif

<div class="row">
    <!-- Panel Detail Kegiatan -->
    <div class="col-lg-4 col-md-5 mb-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-info-circle-fill me-2 text-info"></i>Detail Kegiatan</h5>
                <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#editKegiatanModal">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
            <div class="card-body">
                <h4 class="fw-bold text-primary mb-3">{{ $kegiatan->nama_kegiatan }}</h4>

                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Tanggal Pelaksanaan</span>
                    <strong class="text-dark"><i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('l, d F Y') }}</strong>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Periode Pendaftaran</span>
                    <strong class="text-dark small"><i class="bi bi-calendar3 me-1 text-primary"></i>{{ $kegiatan->tgl_pendaftaran_buka ? \Carbon\Carbon::parse($kegiatan->tgl_pendaftaran_buka)->translatedFormat('d M Y') : '-' }} s/d {{ $kegiatan->tgl_pendaftaran_tutup ? \Carbon\Carbon::parse($kegiatan->tgl_pendaftaran_tutup)->translatedFormat('d M Y') : '-' }}</strong>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Tempat</span>
                    <strong class="text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>{{ $kegiatan->tempat }}</strong>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Bidang Tridharma</span>
                    <strong class="text-dark"><i class="bi bi-bookmark-star-fill text-warning me-2"></i>{{ $kegiatan->bidang_kegiatan ?? 'Belum Diatur' }}</strong>
                </div>

                <!-- Sifat / Biaya Kegiatan -->
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Sifat Kegiatan</span>
                    @if($kegiatan->jenis_kegiatan === 'berbayar')
                        <span class="badge bg-danger p-2 fs-6"><i class="bi bi-cash-coin me-1"></i>Berbayar (Rp {{ number_format($kegiatan->harga, 0, ',', '.') }})</span>
                    @else
                        <span class="badge bg-success p-2 fs-6"><i class="bi bi-gift-fill me-1"></i>Gratis / Free</span>
                    @endif
                </div>

                <!-- Online Presence PIN info -->
                @if($kegiatan->pin_masuk || $kegiatan->pin_pulang)
                <div class="mb-3 p-2.5 rounded bg-light border border-info">
                    <span class="text-info small fw-bold d-block mb-1.5"><i class="bi bi-laptop-fill me-1"></i>PIN Presensi Mandiri:</span>
                    @if($kegiatan->pin_masuk)
                        <div class="d-flex justify-content-between align-items-center mb-1 bg-white px-2 py-1 rounded border small">
                            <span class="text-muted">Masuk:</span>
                            <span class="badge bg-primary fs-7 fw-bold">{{ $kegiatan->pin_masuk }}</span>
                        </div>
                    @endif
                    @if($kegiatan->pin_pulang)
                        <div class="d-flex justify-content-between align-items-center bg-white px-2 py-1 rounded border small">
                            <span class="text-muted">Pulang:</span>
                            <span class="badge bg-success fs-7 fw-bold">{{ $kegiatan->pin_pulang }}</span>
                        </div>
                    @endif
                </div>
                @endif
                <!-- Online Event Self-Presence Control -->
                @if(\Str::contains(strtolower($kegiatan->tempat), 'online') || \Str::contains(strtolower($kegiatan->tempat), 'zoom') || \Str::contains(strtolower($kegiatan->tempat), 'meet'))
                <div class="mb-3 p-3 rounded bg-light border {{ $kegiatan->presensi_online_aktif ? 'border-success' : 'border-warning' }}">
                    <span class="small fw-bold d-block mb-2 text-dark"><i class="bi bi-laptop-fill me-1 text-primary"></i>Link Presensi Online (1x Absen):</span>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">Akses Presensi:</span>
                        @if($kegiatan->presensi_online_aktif)
                            <span class="badge bg-success"><i class="bi bi-unlock-fill me-1"></i>Dibuka</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i>Ditutup</span>
                        @endif
                    </div>

                    <!-- Toggle Button Form -->
                    <form action="{{ route('kegiatan.toggle-presensi-online', $kegiatan) }}" method="POST" class="mb-2">
                        @csrf
                        @if($kegiatan->presensi_online_aktif)
                            <button type="submit" class="btn btn-sm btn-danger w-100"><i class="bi bi-lock-fill me-1"></i>Tutup Presensi</button>
                        @else
                            <button type="submit" class="btn btn-sm btn-success w-100"><i class="bi bi-unlock-fill me-1"></i>Buka Presensi</button>
                        @endif
                    </form>

                    <!-- Link share box -->
                    <div>
                        <label class="form-label small text-muted mb-1" style="font-size: 11px;">Bagikan ke Chat Zoom:</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="presensiLinkUrl" class="form-control" readonly value="{{ route('portal.kegiatan.absensi-mandiri-page', $kegiatan) }}" style="font-size: 11px;">
                            <button class="btn btn-primary" type="button" onclick="copyPresensiLink()"><i class="bi bi-clipboard"></i></button>
                        </div>
                    </div>
                </div>
                @endif
                @if($kegiatan->jenis_kegiatan === 'berbayar' && $kegiatan->rekening_info)
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Metode Pembayaran</span>
                    <div class="bg-light p-2.5 rounded small text-dark" style="white-space: pre-line;">{{ $kegiatan->rekening_info }}</div>
                </div>
                @endif

                @if($kegiatan->narasumber)
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Narasumber</span>
                    <strong class="text-dark"><i class="bi bi-person-workspace me-2 text-warning"></i>{{ $kegiatan->narasumber }}</strong>
                </div>
                @endif

                @if($kegiatan->deskripsi)
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Deskripsi</span>
                    <p class="text-dark bg-light p-2.5 rounded small mb-0">{{ $kegiatan->deskripsi }}</p>
                </div>
                @endif

                <hr class="my-4">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-patch-check text-warning me-2"></i>Penandatangan Sertifikat</h6>
                <div class="ps-1">
                    <span class="text-muted small d-block mb-1">Nama</span>
                    <strong class="text-dark d-block">{{ $kegiatan->tanda_tangan_nama ?? '-' }}</strong>
                    <span class="text-muted small d-block mt-2 mb-1">Jabatan</span>
                    <strong class="text-dark d-block">{{ $kegiatan->tanda_tangan_jabatan ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <!-- Panel Link Presensi Quick-Access -->
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body text-center py-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Presensi Barcode/QR</h5>
                <p class="text-muted small">Buka halaman scanner presensi menggunakan kamera perangkat atau input token barcode peserta.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('kegiatan.presensi', $kegiatan) }}" target="_blank" class="btn btn-primary btn-lg">
                        <i class="bi bi-camera-fill me-2"></i>Buka Layar Presensi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Tabel Peserta -->
    <div class="col-lg-8 col-md-7" id="tabel-peserta">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Daftar Peserta Kegiatan</h3>
                <p class="text-muted mb-0">Total: <strong>{{ $pesertas->count() }}</strong> orang terdaftar.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('kegiatan.cetak-presensi', $kegiatan) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer-fill me-1"></i>Cetak Presensi
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPesertaModal">
                    <i class="bi bi-person-plus-fill me-1"></i>Tambah Peserta
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-3" style="border-radius: 10px;">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-3" style="border-radius: 10px;">{{ session('error') }}</div>
        @endif

        <div class="table-responsive shadow-sm rounded bg-white">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th>Nama Peserta</th>
                        <th>NIM/NIP</th>
                        <th>Kategori</th>
                        <th>Barcode Token</th>
                        <th>Kehadiran</th>
                        @if($kegiatan->jenis_kegiatan === 'berbayar')
                            <th>Pembayaran</th>
                        @endif
                        <th class="text-center">Sertifikat</th>
                        <th class="text-center" style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesertas as $p)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-2 rounded-circle border overflow-hidden" style="width: 38px; height: 38px; background-color: #f1f5f9; flex-shrink: 0;">
                                        @if($p->foto)
                                            <img src="{{ asset('storage/' . $p->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                <i class="bi bi-person-fill small"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $p->nama }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-muted small">{{ $p->identifier }}</span></td>
                            <td><span class="badge bg-secondary">{{ $p->kategori }}</span></td>
                            <td>
                                <div class="d-flex align-items-center justify-content-between bg-light p-1 px-2 rounded border border-light">
                                    <code class="fw-bold text-indigo small" id="token_{{ $p->id }}">{{ $p->barcode_token }}</code>
                                    <button class="btn btn-link btn-xs p-0 text-decoration-none text-muted" onclick="showQrModal('{{ $p->nama }}', '{{ $p->barcode_token }}')" title="Tampilkan QR Code">
                                        <i class="bi bi-qr-code fs-6 text-primary"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                @if($p->status_kehadiran === 'absen')
                                    <span class="badge bg-danger">Belum Hadir</span>
                                @elseif($p->status_kehadiran === 'hadir_masuk')
                                    <span class="badge bg-warning text-dark">Hadir (Masuk)</span>
                                    <small class="text-muted d-block" style="font-size: 10px;">{{ $p->jam_masuk ? $p->jam_masuk->format('H:i') : '' }}</small>
                                @elseif($p->status_kehadiran === 'hadir_lengkap')
                                    <span class="badge bg-success">Hadir Lengkap</span>
                                    <small class="text-muted d-block" style="font-size: 10px;">M: {{ $p->jam_masuk ? $p->jam_masuk->format('H:i') : '-' }} | P: {{ $p->jam_pulang ? $p->jam_pulang->format('H:i') : '-' }}</small>
                                @endif
                            </td>
                            @if($kegiatan->jenis_kegiatan === 'berbayar')
                                <td>
                                    @if($p->status_pembayaran === 'belum_bayar')
                                        <span class="badge bg-danger d-block mb-1">Belum Bayar</span>
                                        <button class="btn btn-xs btn-outline-primary py-0.5 px-1.5 w-100" style="font-size: 10px;" onclick="openPaymentModal('{{ $p->id }}', '{{ $p->nama }}', 'belum_bayar', '')">Ubah</button>
                                    @elseif($p->status_pembayaran === 'menunggu_verifikasi')
                                        <button class="btn btn-xs btn-warning text-dark fw-bold w-100 py-1" style="font-size: 11px;" onclick="openPaymentModal('{{ $p->id }}', '{{ $p->nama }}', 'menunggu_verifikasi', '{{ asset('storage/' . $p->bukti_pembayaran) }}')">
                                            <i class="bi bi-shield-exclamation me-1"></i>Perlu Verifikasi
                                        </button>
                                    @elseif($p->status_pembayaran === 'lunas')
                                        <span class="badge bg-success d-block mb-1"><i class="bi bi-patch-check-fill me-1"></i>Lunas</span>
                                        <button class="btn btn-xs btn-outline-secondary py-0.5 px-1.5 w-100" style="font-size: 10px;" onclick="openPaymentModal('{{ $p->id }}', '{{ $p->nama }}', 'lunas', '{{ $p->bukti_pembayaran ? asset('storage/' . $p->bukti_pembayaran) : '' }}')">Detail</button>
                                    @endif
                                </td>
                            @endif
                            <td class="text-center">
                                @if ($p->status_kehadiran === 'hadir_lengkap' && ($kegiatan->jenis_kegiatan !== 'berbayar' || $p->status_pembayaran === 'lunas'))
                                    <a href="{{ route('kegiatan.sertifikat.cetak', [$kegiatan, $p]) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Cetak Sertifikat">
                                        <i class="bi bi-printer-fill me-1"></i>Cetak
                                    </a>
                                @else
                                    <span class="text-muted small" style="font-size: 10px;">
                                        <i class="bi bi-lock-fill text-muted-50"></i> Terkunci
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                @if($p->status_kehadiran !== 'hadir_lengkap')
                                    <form action="{{ route('kegiatan.peserta.hadir', [$kegiatan, $p]) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai peserta {{ $p->nama }} sebagai hadir lengkap secara manual?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Tandai Hadir Manual"><i class="bi bi-check2-square"></i></button>
                                    </form>
                                @endif
                                <form action="{{ route('kegiatan.peserta.destroy', [$kegiatan, $p]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus peserta {{ $p->nama }} dari kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $kegiatan->jenis_kegiatan === 'berbayar' ? 9 : 8 }}" class="text-center text-muted py-4">Belum ada peserta yang didaftarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title" id="qrModalLabel">Barcode/QR Code Peserta</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <h6 class="fw-bold text-dark mb-3" id="qrModalNama">Nama Peserta</h6>
                <div class="d-flex justify-content-center p-3 bg-white border rounded shadow-sm mb-3">
                    <img id="qrImage" src="" alt="QR Code" class="img-fluid" style="width: 180px; height: 180px;">
                </div>
                <code class="fw-bold text-indigo" id="qrModalToken">TOKEN</code>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Peserta -->
<div class="modal fade" id="tambahPesertaModal" tabindex="-1" aria-labelledby="tambahPesertaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kegiatan.peserta.store', $kegiatan) }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahPesertaModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Tambah Peserta Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="identifier" class="form-label fw-semibold">NIM / NIP / NIK <span class="text-danger">*</span></label>
                        <input type="text" name="identifier" id="identifier" class="form-control" required placeholder="Ketik NIM/NIP lalu tunggu sejenak...">
                        <div id="identifierHelper" class="form-text text-info d-none">Sedang mencari data...</div>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required placeholder="Contoh: Rian Hidayat">
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-semibold">Kategori Peserta <span class="text-danger">*</span></label>
                        <select name="kategori" id="kategori" class="form-select" required>
                            <option value="Mahasiswa">Mahasiswa</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Tendik">Tenaga Kependidikan</option>
                            <option value="Umum">Umum</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Daftarkan Peserta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paymentVerifForm" action="" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="paymentModalLabel"><i class="bi bi-shield-check me-2"></i>Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold mb-3" id="paymentPesertaNama">Nama Peserta</h6>

                    <!-- Uploaded Receipt View -->
                    <div class="mb-4 text-center d-none" id="buktiBayarWrapper">
                        <div class="info-label text-muted text-start mb-2 small fw-semibold">Bukti Pembayaran / Transfer:</div>
                        <a href="" id="buktiLink" target="_blank" title="Klik untuk perbesar">
                            <img src="" id="buktiImage" class="img-fluid rounded border shadow-sm" style="max-height: 250px; object-fit: contain;">
                        </a>
                        <p class="text-muted small mt-1"><i class="bi bi-zoom-in"></i> Klik gambar untuk perbesar bukti transfer.</p>
                    </div>

                    <div class="mb-3">
                        <label for="status_pembayaran" class="form-label fw-semibold">Ubah Status Pembayaran <span class="text-danger">*</span></label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-select" required>
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi (Pending)</option>
                            <option value="lunas">Lunas (Lunas & Buka Sertifikat)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kegiatan -->
<div class="modal fade" id="editKegiatanModal" tabindex="-1" aria-labelledby="editKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kegiatan.update', $kegiatan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editKegiatanModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Detail Kegiatan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nama Kegiatan -->
                        <div class="col-md-12 mb-3">
                            <label for="edit_nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" id="edit_nama_kegiatan" class="form-control" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required>
                        </div>

                        <!-- Tanggal & Tempat -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_tanggal" class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control" value="{{ old('tanggal', $kegiatan->tanggal) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_tempat" class="form-label fw-semibold">Tempat Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="text" name="tempat" id="edit_tempat" class="form-control" value="{{ old('tempat', $kegiatan->tempat) }}" required>
                        </div>

                        <!-- Tanggal Pendaftaran Buka & Tutup -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_tgl_pendaftaran_buka" class="form-label fw-semibold">Tanggal Pendaftaran Buka <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pendaftaran_buka" id="edit_tgl_pendaftaran_buka" class="form-control" value="{{ old('tgl_pendaftaran_buka', $kegiatan->tgl_pendaftaran_buka) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_tgl_pendaftaran_tutup" class="form-label fw-semibold">Batas Tanggal Pendaftaran <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pendaftaran_tutup" id="edit_tgl_pendaftaran_tutup" class="form-control" value="{{ old('tgl_pendaftaran_tutup', $kegiatan->tgl_pendaftaran_tutup) }}" required>
                        </div>

                        <!-- Narasumber -->
                        <div class="col-md-12 mb-3">
                            <label for="edit_narasumber" class="form-label fw-semibold">Narasumber / Pembicara</label>
                            <input type="text" name="narasumber" id="edit_narasumber" class="form-control" value="{{ old('narasumber', $kegiatan->narasumber) }}">
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-md-12 mb-3">
                            <label for="edit_deskripsi" class="form-label fw-semibold">Deskripsi Kegiatan</label>
                            <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="2">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                        </div>

                        <!-- Edit Payment Options & Bidang Kegiatan -->
                        <div class="col-md-6 mb-3">
                            <label for="edit_bidang_kegiatan" class="form-label fw-semibold">Bidang Tridharma <span class="text-danger">*</span></label>
                            <select name="bidang_kegiatan" id="edit_bidang_kegiatan" class="form-select" required>
                                <option value="Pendidikan" {{ $kegiatan->bidang_kegiatan === 'Pendidikan' ? 'selected' : '' }}>Pendidikan / Akademik</option>
                                <option value="Penelitian" {{ $kegiatan->bidang_kegiatan === 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                                <option value="Pengabdian Masyarakat" {{ $kegiatan->bidang_kegiatan === 'Pengabdian Masyarakat' ? 'selected' : '' }}>Pengabdian Kepada Masyarakat</option>
                                <option value="Lainnya" {{ $kegiatan->bidang_kegiatan === 'Lainnya' ? 'selected' : '' }}>Lainnya / Umum</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_jenis_kegiatan" class="form-label fw-semibold">Sifat Kegiatan <span class="text-danger">*</span></label>
                            <select name="jenis_kegiatan" id="edit_jenis_kegiatan" class="form-select" required onchange="toggleEditPaymentInputs(this.value)">
                                <option value="gratis" {{ $kegiatan->jenis_kegiatan === 'gratis' ? 'selected' : '' }}>Gratis / Free</option>
                                <option value="berbayar" {{ $kegiatan->jenis_kegiatan === 'berbayar' ? 'selected' : '' }}>Berbayar</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3 edit-payment-field" style="display: {{ $kegiatan->jenis_kegiatan === 'berbayar' ? 'block' : 'none' }};">
                            <label for="edit_harga" class="form-label fw-semibold">Harga Tiket (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" id="edit_harga" class="form-control" value="{{ old('harga', $kegiatan->harga) }}" min="0" required>
                        </div>
                        <div class="col-md-12 mb-3 edit-payment-field" style="display: {{ $kegiatan->jenis_kegiatan === 'berbayar' ? 'block' : 'none' }};">
                            <label for="edit_rekening_info" class="form-label fw-semibold">Informasi Rekening / Metode Pembayaran <span class="text-danger">*</span></label>
                            <textarea name="rekening_info" id="edit_rekening_info" class="form-control" rows="2">{{ old('rekening_info', $kegiatan->rekening_info) }}</textarea>
                        </div>

                        <!-- Edit Online Presence PINs -->
                        <div class="col-md-12"><hr class="my-3"></div>
                        <h6 class="fw-bold mb-3 px-3"><i class="bi bi-laptop-fill text-primary me-2"></i>Presensi Mandiri Online (Khusus Zoom/Meet)</h6>
                        <div class="col-md-6 mb-3">
                            <label for="edit_pin_masuk" class="form-label fw-semibold">PIN Presensi Masuk <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="pin_masuk" id="edit_pin_masuk" class="form-control" value="{{ old('pin_masuk', $kegiatan->pin_masuk) }}" placeholder="Contoh: MASUK123">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_pin_pulang" class="form-label fw-semibold">PIN Presensi Pulang <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="pin_pulang" id="edit_pin_pulang" class="form-control" value="{{ old('pin_pulang', $kegiatan->pin_pulang) }}" placeholder="Contoh: PULANG456">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showQrModal(nama, token) {
        document.getElementById('qrModalNama').innerText = nama;
        document.getElementById('qrModalToken').innerText = token;
        var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(token);
        document.getElementById('qrImage').src = qrUrl;
        var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
        myModal.show();
    }

    function toggleEditPaymentInputs(val) {
        const els = document.querySelectorAll('.edit-payment-field');
        const priceInput = document.getElementById('edit_harga');
        const bankInput = document.getElementById('edit_rekening_info');
        if (val === 'berbayar') {
            els.forEach(el => el.style.display = 'block');
            priceInput.required = true;
            bankInput.required = true;
        } else {
            els.forEach(el => el.style.display = 'none');
            priceInput.required = false;
            bankInput.required = false;
        }
    }

    function openPaymentModal(pesertaId, nama, currentStatus, buktiUrl) {
        document.getElementById('paymentPesertaNama').innerText = nama;
        document.getElementById('status_pembayaran').value = currentStatus;
        
        // Setup Form Action Route
        const formAction = "{{ url('/admin/kegiatan') }}/{{ $kegiatan->id }}/peserta/" + pesertaId + "/verifikasi-pembayaran";
        document.getElementById('paymentVerifForm').action = formAction;

        // Toggle Receipt View
        const wrapper = document.getElementById('buktiBayarWrapper');
        const img = document.getElementById('buktiImage');
        const link = document.getElementById('buktiLink');

        if (buktiUrl && buktiUrl.trim() !== '') {
            wrapper.classList.remove('d-none');
            img.src = buktiUrl;
            link.href = buktiUrl;
        } else {
            wrapper.classList.add('d-none');
            img.src = '';
            link.href = '';
        }

        const payModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        payModal.show();
    }

    function copyPresensiLink() {
        const linkInput = document.getElementById('presensiLinkUrl');
        if (linkInput) {
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(linkInput.value);
            alert('Link presensi online berhasil disalin ke clipboard!');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const idInput = document.getElementById('identifier');
        const namaInput = document.getElementById('nama');
        const katInput = document.getElementById('kategori');
        const helper = document.getElementById('identifierHelper');
        let timeout = null;

        if(idInput) {
            idInput.addEventListener('input', function() {
                clearTimeout(timeout);
                const val = idInput.value.trim();
                
                if (val.length < 3) {
                    helper.classList.add('d-none');
                    return;
                }

                helper.textContent = 'Mencari data identitas...';
                helper.classList.remove('d-none');
                helper.classList.remove('text-success', 'text-danger');
                helper.classList.add('text-info');

                timeout = setTimeout(() => {
                    fetch(`{{ route('portal.kegiatan.cek-identitas') }}?identifier=${val}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            namaInput.value = data.nama;
                            katInput.value = data.kategori;
                            helper.textContent = `Ditemukan: ${data.kategori} terdaftar.`;
                            helper.classList.remove('text-info');
                            helper.classList.add('text-success');
                        } else {
                            helper.textContent = 'Data tidak ditemukan, silakan input manual.';
                            helper.classList.remove('text-info');
                            helper.classList.add('text-danger');
                        }
                    }).catch(err => {
                        helper.classList.add('d-none');
                    });
                }, 500);
            });
        }
    });
</script>
@endpush
