<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; padding: 20px; background-color: #fff; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.85rem; }
        table.data-table th, table.data-table td { border: 1px solid #333; padding: 8px 10px; text-align: left; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-center { text-align: center !important; }
        .no-print-btn { background-color: #4f46e5; color: white; border: none; padding: 10px 20px; font-size: 1rem; font-weight: bold; border-radius: 5px; cursor: pointer; margin-bottom: 20px; display: inline-flex; }
        .header-wrap { display: flex; align-items: center; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 20px; gap: 16px; }
        .header-logo { flex: 0 0 80px; text-align: center; }
        .header-logo img { width: 75px; height: auto; }
        .header-text { flex: 1; text-align: center; }
        .header-text h1 { margin: 2px 0; font-size: 1.15rem; font-weight: bold; text-transform: uppercase; }
        .header-text h2 { margin: 3px 0; font-size: 1.15rem; text-transform: uppercase; }
        .header-text p { margin: 3px 0; font-size: 0.82rem; color: #555; }
        .meta-info table { width: 100%; border: none; font-size: 0.9rem; }
        .meta-info td { padding: 4px 0; border: none; }
        .badge-hadir { color: green; font-weight: bold; }
        .badge-absen { color: red; font-weight: bold; }
        .badge-warning { color: orange; font-weight: bold; }
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="no-print-btn">Cetak / Simpan Sebagai PDF</button>
    </div>

    <div class="header-wrap">
        <div class="header-logo">
            <!-- Ensure you have an image at public/img/logo_ubsi.png or handle dynamically -->
            <img src="{{ asset('img/logo_ubsi.png') }}" alt="Logo">
        </div>
        <div class="header-text">
            <h1>Universitas Bina Sarana Informatika</h1>
            <h1>Kampus Kota Pontianak</h1>
            <h2>Program Studi Sistem Informasi Akuntansi (D3)</h2>
            <p>Laporan Kehadiran Peserta Kegiatan Akademik & Non Akademik</p>
        </div>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%; font-weight: bold;">Nama Kegiatan:</td>
                <td style="width: 35%;">{{ $kegiatan->nama_kegiatan }}</td>
                <td style="width: 15%; font-weight: bold;">Tanggal Cetak:</td>
                <td style="width: 35%;">{{ date('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tanggal Pelaksanaan:</td>
                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d-m-Y') }}</td>
                <td style="font-weight: bold;">Total Pendaftar:</td>
                <td>{{ $kegiatan->pesertas->count() }} Orang</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tempat:</td>
                <td colspan="3">{{ $kegiatan->tempat }}</td>
            </tr>
        </table>
    </div>

    @if($kegiatan->pesertas->count() === 0)
        <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f9f9f9; border: 1px dashed #ccc;">
            Belum ada pendaftar / peserta untuk kegiatan ini.
        </div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Identitas (NIM/NIP)</th>
                    <th style="width: 35%;">Nama Lengkap</th>
                    <th style="width: 20%;">Kategori</th>
                    <th style="width: 20%;">Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan->pesertas as $index => $peserta)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $peserta->identifier ?: '-' }}</td>
                        <td>{{ $peserta->nama }}</td>
                        <td class="text-center">{{ ucfirst($peserta->kategori) }}</td>
                        <td class="text-center">
                            @if($peserta->status_kehadiran == 'hadir_lengkap')
                                <span class="badge-hadir">Hadir</span>
                            @elseif($peserta->status_kehadiran == 'hadir_masuk')
                                <span class="badge-warning">Hanya Masuk</span>
                            @else
                                <span class="badge-absen">Tidak Hadir</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 50px; text-align: right; margin-right: 50px;">
            <p>Pontianak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 70px;">Ketua Program Studi</p>
            <p style="font-weight: bold;">
                @php 
                    $profil = \App\Models\ProfilProdi::first();
                    echo $profil && $profil->nama_kaprodi ? $profil->nama_kaprodi : 'Raja Sabaruddin, M.Kom';
                @endphp
            </p>
        </div>
    @endif
</body>
</html>
