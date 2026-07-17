<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Kegiatan - {{ $peserta->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #e0e7ff;
            --secondary: #10b981;
            --dark: #0f172a;
            --gray: #64748b;
            --bg-color: #f8fafc;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .ticket-card {
            width: 100%;
            max-width: 700px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .ticket-header {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            padding: 24px;
            color: #ffffff;
            position: relative;
        }

        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            width: 100%;
            height: 30px;
            background: radial-gradient(circle at 15px 15px, transparent 15px, #ffffff 16px) repeat-x;
            background-size: 30px 30px;
            background-position: -15px -15px;
            z-index: 10;
        }

        .ticket-body {
            padding: 30px 24px 24px;
            background: #ffffff;
            position: relative;
        }

        .info-label {
            font-size: 11px;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 16px;
        }

        .qr-wrapper {
            background: #ffffff;
            border-radius: 16px;
            padding: 12px;
            display: inline-block;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);
            border: 2px solid var(--primary-light);
        }

        .qr-wrapper img {
            width: 120px;
            height: 120px;
        }

        .ticket-footer {
            background: var(--bg-color);
            padding: 16px 24px;
            font-size: 12px;
            color: var(--gray);
            font-weight: 600;
            text-align: center;
            border-top: 2px dashed #e2e8f0;
        }

        .photo-box {
            width: 100px;
            height: 125px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
            border: 3px solid #ffffff;
            background: var(--bg-color);
            margin: 0 auto;
            overflow: hidden;
        }

        /* Print Override */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .ticket-card {
                box-shadow: none !important;
                max-width: 100% !important;
                width: 170mm !important;
                border: 2px solid #e2e8f0;
                border-radius: 0 !important;
            }
            .ticket-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="d-flex flex-column align-items-center gap-4">
        <!-- Success Alert (Hidden during print) -->
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm text-center mb-0 no-print" style="border-radius: 12px; max-width: 680px; width: 100%;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Ticket Card -->
        <div class="ticket-card">
            
            <div class="ticket-header">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Left Logo -->
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="height: 38px; width: auto; filter: brightness(0) invert(1);" alt="Logo UBSI">
                    
                    <!-- Center Title -->
                    <div class="text-center flex-grow-1 mx-3">
                        <h5 class="fw-bold mb-0" style="font-size: 14px; letter-spacing: 1px;">KARTU TANDA PESERTA</h5>
                        <p class="mb-0 text-white-50" style="font-size: 9.5px; font-weight: 600; text-transform: uppercase;">Portal Kegiatan UBSI Pontianak</p>
                    </div>
                    
                    <!-- Right Logo (Icon) -->
                    <i class="bi bi-ticket-detailed-fill" style="font-size: 28px; opacity: 0.8;"></i>
                </div>
            </div>

            <div class="ticket-body">
                <div class="row align-items-center">
                    <!-- Column 1: Event & Participant Details -->
                    <div class="col-md-5">
                        <div class="info-label">Nama Kegiatan</div>
                        <div class="info-value text-primary" style="font-size: 14px;">{{ $peserta->kegiatan->nama_kegiatan }}</div>

                        <div class="info-label">Waktu / Tanggal Kegiatan</div>
                        <div class="info-value small"><i class="bi bi-calendar3 text-primary me-1"></i>{{ \Carbon\Carbon::parse($peserta->kegiatan->tanggal)->translatedFormat('l, d F Y') }}</div>

                        <div class="info-label">Tempat / Lokasi Kegiatan</div>
                        <div class="info-value small"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $peserta->kegiatan->tempat }}</div>

                        <div class="info-label">Nama Peserta</div>
                        <div class="info-value">{{ $peserta->nama }}</div>

                        <div class="info-label">NIM / NIP / NIK</div>
                        <div class="info-value">{{ $peserta->identifier }}</div>

                        <div class="info-label">Kategori / Token</div>
                        <div class="info-value" style="margin-bottom: 0;">
                            <span class="badge bg-primary me-2">{{ $peserta->kategori }}</span>
                            <code class="text-primary small fw-bold" style="font-size: 11px;">{{ $peserta->barcode_token }}</code>
                        </div>
                    </div>

                    <!-- Column 2: Participant Photo -->
                    <div class="col-md-3 text-center mt-4 mt-md-0 position-relative">
                        <div class="d-none d-md-block" style="position: absolute; left: 0; top: 0; bottom: 0; width: 1px; background: #e2e8f0;"></div>
                        <div class="d-none d-md-block" style="position: absolute; right: 0; top: 0; bottom: 0; width: 1px; background: #e2e8f0;"></div>
                        <div class="info-label mb-2">Foto Profil</div>
                        <div class="photo-box">
                            @if($peserta->foto)
                                <img src="{{ asset('storage/' . $peserta->foto) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Peserta">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-secondary">
                                    <i class="bi bi-person-fill fs-1"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Column 3: QR Code / Access Status -->
                    <div class="col-md-4 text-center mt-4 mt-md-0">
                        @if($peserta->kegiatan->jenis_kegiatan === 'berbayar' && $peserta->status_pembayaran !== 'lunas')
                            <!-- Locked QR Code for Unpaid Ticket -->
                            <div class="p-3 bg-light border border-danger border-dashed rounded text-center d-flex flex-column align-items-center justify-content-center" style="border-radius: 14px; min-height: 145px; margin: 0 auto; max-width: 160px;">
                                <i class="bi bi-qr-code text-danger fs-2 mb-1" style="opacity: 0.5;"></i>
                                <span class="text-danger fw-bold small d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">QR TERKUNCI</span>
                                @if($peserta->status_pembayaran === 'belum_bayar')
                                    <small class="text-secondary d-block" style="font-size: 9px; line-height: 1.3;">Belum Bayar. Silakan selesaikan pembayaran.</small>
                                @else
                                    <small class="text-warning d-block" style="font-size: 9px; line-height: 1.3;">Menunggu verifikasi admin.</small>
                                @endif
                            </div>
                        @else
                            <!-- Active QR Code -->
                            <div class="qr-wrapper">
                                <!-- Dynamic QR Code -->
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=125x125&data={{ urlencode($peserta->barcode_token) }}" alt="QR Code Peserta">
                            </div>
                            <div class="text-muted fw-bold mt-2 no-print" style="font-size: 10px;">SCAN UNTUK PRESENSI</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ticket-footer">
                @if($peserta->kegiatan->jenis_kegiatan === 'berbayar' && $peserta->status_pembayaran !== 'lunas')
                    <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> Akses belum tersedia karena pembayaran belum diverifikasi.
                @else
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Tunjukkan kartu digital ini saat proses presensi kegiatan.
                @endif
            </div>
        </div>

        <!-- Action Buttons (Hidden during print) -->
        <div class="d-flex gap-3 no-print">
            <a href="{{ route('portal.kegiatan') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Portal
            </a>
            @if($peserta->kegiatan->jenis_kegiatan !== 'berbayar' || $peserta->status_pembayaran === 'lunas')
                <button onclick="window.print()" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" style="border-radius: 10px; background-color: #4f46e5; border: none;">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Kartu
                </button>
            @endif
        </div>
    </div>

</body>
</html>
