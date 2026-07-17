<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $peserta->nama }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,500&display=swap');

        /* Screen Preview Styling */
        body {
            margin: 0;
            padding: 40px 10px;
            background-color: #0b0f19;
            color: #1e293b;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* Certificate Container (A4 Landscape aspect ratio) */
        .cert-page {
            background: #ffffff;
            box-sizing: border-box;
            position: relative;
            width: 100%;
            max-width: 1050px;
            aspect-ratio: 297 / 210;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.6);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
        }

        /* Asymmetric Layout - Left Sidebar (Deep Navy Blue to Gold) */
        .cert-sidebar {
            width: 30%;
            background: linear-gradient(135deg, #0a1931 0%, #15305b 50%, #c5a85c 100%);
            color: #ffffff;
            padding: 35px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            border-right: 4px solid #c5a85c;
            position: relative;
            overflow: hidden;
        }

        /* Specialized Pontianak & Accounting Graphics in Sidebar */
        .sidebar-graphic {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .cert-sidebar-logo {
            text-align: center;
            margin-top: 15px;
            z-index: 5;
        }

        .cert-logo {
            height: 105px; /* Even Larger UBSI Logo */
            width: auto;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.35));
        }

        /* Sidebar Signature Box */
        .cert-sidebar-sig {
            width: 100%;
            text-align: center;
            z-index: 5;
            margin-bottom: 5px;
        }

        .cert-sig-date {
            font-size: 11px;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .cert-sig-qr {
            margin-bottom: 8px;
        }

        .cert-sig-qr canvas {
            background: #ffffff;
            border: 2px solid #c5a85c;
            border-radius: 6px;
            padding: 4px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .cert-sig-name {
            font-size: 11.5px;
            font-weight: 800;
            color: #0b132b;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cert-sig-title {
            font-size: 9.5px;
            color: #64748b;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Asymmetric Layout - Right Main Area (Cream/White Gradient) */
        .cert-main {
            width: 70%;
            background: linear-gradient(135deg, #ffffff 0%, #fafaf9 60%, #f4f7f6 100%);
            padding: 35px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        /* Fine Decorative Border inside Main Content to reduce empty space feel */
        .cert-main::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 1px solid rgba(197, 168, 92, 0.2);
            pointer-events: none;
            z-index: 5;
            border-radius: 8px;
        }

        /* Abstract elegant mesh background */
        .cert-main-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(197, 168, 92, 0.08) 1px, transparent 0);
            background-size: 24px 24px;
            opacity: 0.6;
            pointer-events: none;
            z-index: 1;
        }

        /* Transparent Watermark UBSI Logo in center */
        .cert-watermark-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 340px;
            height: auto;
            opacity: 0.05; /* Subtly increased opacity for luxury seal */
            pointer-events: none;
            z-index: 1;
        }

        /* Institution Header */
        .cert-main-header {
            text-align: left;
            z-index: 10;
        }

        .cert-main-header h3 {
            font-size: 17px;
            font-weight: 800;
            color: #0b132b;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .cert-main-header h4 {
            font-size: 13px;
            font-weight: 700;
            color: #c5a85c;
            margin: 3px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cert-main-header h5 {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin: 2.5px 0 0 0;
            text-transform: uppercase;
        }

        .header-line {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, #c5a85c 0%, rgba(241, 245, 249, 0) 100%);
            margin-top: 10px;
        }

        /* Organizer Badge to reduce empty space and satisfy request */
        .organizer-badge {
            display: inline-flex;
            align-items: center;
            background-color: rgba(30, 58, 138, 0.06);
            border: 1px solid rgba(30, 58, 138, 0.2);
            padding: 6px 12px;
            border-radius: 6px;
            margin-top: 8px;
            font-size: 10px;
            font-weight: 700;
            color: #1e3a8a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Certificate Title */
        .cert-main-title {
            z-index: 10;
            margin-top: 5px;
        }

        .cert-main-title h1 {
            font-family: 'Playfair Display', serif;
            font-size: 40px; /* Slightly larger */
            font-weight: 700;
            color: #0b132b;
            margin: 0;
            letter-spacing: 1px;
            text-shadow: 1px 1px 0px rgba(255,255,255,0.8);
        }

        .cert-number {
            font-size: 10.5px;
            font-weight: 700;
            color: #64748b;
            margin: 5px 0 0 0;
            letter-spacing: 2px;
        }

        /* Recipient Section */
        .cert-main-recipient {
            z-index: 10;
            margin-top: 10px;
            border-left: 3px solid #c5a85c;
            padding-left: 15px;
        }

        .recipient-label {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #64748b;
            font-weight: 700;
            margin: 0;
        }

        .recipient-name {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
            color: #0b132b;
            margin: 6px 0;
            letter-spacing: 0.5px;
        }

        /* Description Body */
        .cert-main-desc {
            z-index: 10;
            font-size: 13.5px; /* Enlarged font size */
            line-height: 1.8; /* Increased line spacing */
            color: #475569;
            margin-top: 8px; /* Raised slightly */
            max-width: 80%; /* Limit width to prevent overlap with bottom-right photo */
            text-align: justify; /* Rata kiri dan kanan */
        }

        .cert-main-desc strong {
            color: #0b132b;
            font-weight: 700;
        }

        /* Print Override Styles */
        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 297mm;
                height: 210mm;
            }
            .cert-page {
                width: 297mm !important;
                height: 210mm !important;
                max-width: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                page-break-after: avoid;
            }
            .cert-sidebar {
                padding: 35px 20px !important;
            }
            .cert-main {
                padding: 35px 40px !important;
            }
            .no-print {
                display: none !important;
            }
        }

        /* Floating Print Button */
        .btn-print {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #1c2541 0%, #0b132b 100%);
            color: #ffffff;
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(27, 38, 59, 0.4);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(27, 38, 59, 0.5);
        }
    </style>
</head>
<body>

    <!-- Floating Print Button -->
    <button class="btn-print no-print" onclick="window.print()">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
        </svg>
        Cetak Sertifikat
    </button>

    <div class="cert-page">
        <!-- Asymmetric Left Sidebar -->
        <div class="cert-sidebar">
            <!-- Specialized Accounting & Indonesian Batik Kawung Graphics inside the Sidebar -->
            <svg class="sidebar-graphic" viewBox="0 0 100 200" preserveAspectRatio="none">
                <defs>
                    <!-- Batik Kawung Repeating Pattern -->
                    <pattern id="batikKawung" width="20" height="20" patternUnits="userSpaceOnUse">
                        <!-- Overlapping circles forming the classic Kawung leaf motif -->
                        <circle cx="10" cy="10" r="8.5" fill="none" stroke="rgba(197, 168, 92, 0.14)" stroke-width="0.45"></circle>
                        <circle cx="0" cy="0" r="8.5" fill="none" stroke="rgba(197, 168, 92, 0.14)" stroke-width="0.45"></circle>
                        <circle cx="20" cy="0" r="8.5" fill="none" stroke="rgba(197, 168, 92, 0.14)" stroke-width="0.45"></circle>
                        <circle cx="0" cy="20" r="8.5" fill="none" stroke="rgba(197, 168, 92, 0.14)" stroke-width="0.45"></circle>
                        <circle cx="20" cy="20" r="8.5" fill="none" stroke="rgba(197, 168, 92, 0.14)" stroke-width="0.45"></circle>
                        
                        <!-- Accent grid lines -->
                        <line x1="0" y1="10" x2="20" y2="10" stroke="rgba(255, 255, 255, 0.02)" stroke-width="0.4"></line>
                        <line x1="10" y1="0" x2="10" y2="20" stroke="rgba(255, 255, 255, 0.02)" stroke-width="0.4"></line>
                        
                        <!-- Central diamond core -->
                        <rect x="8.5" y="8.5" width="3" height="3" fill="none" stroke="rgba(197, 168, 92, 0.22)" stroke-width="0.45" transform="rotate(45 10 10)"></rect>
                        
                        <!-- Small internal dots -->
                        <circle cx="5" cy="5" r="0.8" fill="rgba(255, 255, 255, 0.04)"></circle>
                        <circle cx="15" cy="5" r="0.8" fill="rgba(255, 255, 255, 0.04)"></circle>
                        <circle cx="5" cy="15" r="0.8" fill="rgba(255, 255, 255, 0.04)"></circle>
                        <circle cx="15" cy="15" r="0.8" fill="rgba(255, 255, 255, 0.04)"></circle>
                    </pattern>
                </defs>
                
                <!-- Background filled with repeating Batik Kawung pattern -->
                <rect width="100" height="200" fill="url(#batikKawung)"></rect>

                <!-- ACCOUNTING / AKUNTANSI SYMBOLS (Balance Scale / Neraca) -->
                <!-- Central pillar -->
                <path d="M50,146 L50,165 M44,165 L56,165" stroke="rgba(255, 255, 255, 0.12)" stroke-width="0.5" fill="none"></path>
                <!-- Scale beam -->
                <path d="M40,149 L60,149" stroke="rgba(255, 255, 255, 0.16)" stroke-width="0.75" fill="none"></path>
                <!-- Left and Right hanging pans -->
                <path d="M40,149 L37,156 M40,149 L43,156 M37,156 L43,156" stroke="rgba(255, 255, 255, 0.1)" stroke-width="0.5" fill="none"></path>
                <path d="M60,149 L57,156 M60,149 L63,156 M57,156 L63,156" stroke="rgba(255, 255, 255, 0.1)" stroke-width="0.5" fill="none"></path>
            </svg>

            <!-- Top Area: UBSI Logo (Enlarged) -->
            <!-- Top Area: UBSI Logo (Enlarged) -->
            <div class="cert-sidebar-logo">
                <img class="cert-logo" src="{{ asset('img/logo_ubsi.png') }}" alt="Logo UBSI">
            </div>

            <!-- Bottom Area: Participant Photo (replacing signature) -->
            <div class="cert-sidebar-photo" style="margin-top: auto; margin-bottom: 20px; text-align: center; z-index: 5; width: 100%;">
                @if($peserta->foto)
                    <div class="cert-photo-frame" style="border: 2px solid #c5a85c; padding: 2px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-radius: 4px; display: inline-block;">
                        <img src="{{ asset('storage/' . $peserta->foto) }}" style="width: 80px; height: 110px; object-fit: cover; display: block;" alt="Foto Penerima">
                    </div>
                    <div style="margin-top: 10px;">
                        <span style="display: inline-block; background: linear-gradient(135deg, #c5a85c, #e5c06a); color: #0a1931; padding: 4px 16px; border-radius: 20px; font-size: 9.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 3px 8px rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2);">PESERTA</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Asymmetric Right Main Area -->
        <div class="cert-main">
            <!-- Grid Mesh Background -->
            <div class="cert-main-pattern"></div>

            <!-- Transparent Watermark UBSI Logo in center -->
            <img class="cert-watermark-logo" src="{{ asset('img/logo_ubsi.png') }}" alt="Watermark UBSI">

            <!-- Institution Header -->
            <div class="cert-main-header">
                <h3>UNIVERSITAS BINA SARANA INFORMATIKA</h3>
                <h4>FAKULTAS TEKNIK DAN INFORMATIKA</h4>
                <h5>Program Studi Sistem Informasi Akuntansi (D3) - Kampus Kota Pontianak</h5>
                <div class="header-line"></div>
            </div>

            <!-- Title -->
            <div class="cert-main-title">
                <h1>SERTIFIKAT</h1>
                <div class="cert-number">No: {{ str_pad($peserta->id, 4, '0', STR_PAD_LEFT) }}/UBSI.P1/P-SIA/{{ date('Y') }}</div>
            </div>

            <!-- Recipient Name -->
            <div class="cert-main-recipient" style="border-left: 3px solid #c5a85c; padding-left: 15px; margin-top: 15px;">
                <p class="recipient-label" style="margin-bottom: 5px;">Diberikan Kepada:</p>
                <h2 class="recipient-name" style="margin: 0; font-size: 38px; line-height: 1.2;">{{ $peserta->nama }}</h2>
            </div>

            <!-- Event Details Text (With Organizer Name included) -->
            <div class="cert-main-desc">
                Atas partisipasi dan kehadirannya sebagai <strong>PESERTA</strong> dalam kegiatan 
                <strong>"{{ $kegiatan->nama_kegiatan }}"</strong> yang diselenggarakan oleh 
                <strong>Program Studi Sistem Informasi Akuntansi (D3) Universitas Bina Sarana Informatika Kampus Kota Pontianak</strong> 
                di <strong>{{ $kegiatan->tempat }}</strong> pada tanggal <strong>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</strong>.
            </div>

            <!-- Signature Box flowing naturally under description text -->
            <div class="cert-main-sig" style="margin-top: 30px; margin-left: auto; text-align: center; display: flex; flex-direction: column; align-items: center; z-index: 10; width: 220px; padding-right: 15px;">
                <div class="cert-sig-date">
                    Pontianak, {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                </div>
                
                <!-- QR Code Signature -->
                <div class="cert-sig-qr" id="qrcode" style="display: flex; justify-content: center; background: #ffffff; padding: 4px; border: 2px solid #c5a85c; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: fit-content; margin: 0 auto 8px auto;">
                </div>

                <p class="cert-sig-name">{{ $kegiatan->tanda_tangan_nama ?? 'Ketua Program Studi' }}</p>
                <p class="cert-sig-title">{{ $kegiatan->tanda_tangan_jabatan ?? 'NIP/NIDN. -' }}</p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate Reliable QR Code for Verification
        window.addEventListener('DOMContentLoaded', () => {
            const verifyUrl = "{{ route('portal.kegiatan.sertifikat', ['kegiatan' => $kegiatan->id, 'peserta' => $peserta->id]) }}";
            new QRCode(document.getElementById("qrcode"), {
                text: verifyUrl,
                width: 85,
                height: 85,
                colorDark : "#0b132b",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
        });
    </script>
</body>
</html>
