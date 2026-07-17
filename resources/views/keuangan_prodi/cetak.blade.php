<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; padding: 20px; background-color: #fff; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.85rem; }
        table.data-table th, table.data-table td { border: 1px solid #333; padding: 8px 10px; text-align: center; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .no-print-btn { background-color: #4f46e5; color: white; border: none; padding: 10px 20px; font-size: 1rem; font-weight: bold; border-radius: 5px; cursor: pointer; margin-bottom: 20px; display: inline-flex; }
        .header-wrap { display: flex; align-items: center; border-bottom: 3px double #333; padding-bottom: 10px; margin-bottom: 20px; gap: 16px; }
        .header-logo { flex: 0 0 80px; text-align: center; }
        .header-logo img { width: 75px; height: auto; }
        .header-text { flex: 1; text-align: center; }
        .header-text h1 { margin: 2px 0; font-size: 1.15rem; font-weight: bold; text-transform: uppercase; }
        .header-text h2 { margin: 3px 0; font-size: 1.15rem; text-transform: uppercase; }
        .header-text p { margin: 3px 0; font-size: 0.82rem; color: #555; }
        .meta-info table { width: 100%; border: none; font-size: 0.9rem; }
        .meta-info td { padding: 4px 0; border: none; text-align: left; }
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="no-print-btn">Cetak / Simpan Sebagai PDF</button>
    </div>

    <div class="header-wrap">
        <div class="header-logo">
            <img src="{{ asset('img/logo_ubsi.png') }}" alt="Logo">
        </div>
        <div class="header-text">
            <h1>Universitas Bina Sarana Informatika</h1>
            <h1>Kampus Kota Pontianak</h1>
            <h2>Program Studi Sistem Informasi Akuntansi (D3)</h2>
            <p>Laporan Penggunaan Dana Akademik & Tridharma (C5)</p>
        </div>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%; font-weight: bold;">Tanggal Cetak:</td>
                <td style="width: 85%;">{{ date('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Topik Laporan:</td>
                <td style="font-weight: bold; color: #111;">{{ $title }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" rowspan="2">No</th>
                <th style="width: 15%;" rowspan="2">Tahun (TS)</th>
                <th style="width: 10%;" rowspan="2">Jumlah Mahasiswa Aktif</th>
                <th colspan="3">Penggunaan Dana (Rp)</th>
                <th style="width: 15%;" rowspan="2">Rata-rata per Mahasiswa Aktif</th>
            </tr>
            <tr>
                <th style="width: 18%;">Operasional Pendidikan</th>
                <th style="width: 18%;">Penelitian & PKM</th>
                <th style="width: 18%;">Investasi (SDM, Sarpras)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totMhs = 0;
                $totOpr = 0;
                $totPenPkm = 0;
                $totInv = 0;
            @endphp
            @foreach($keuangans as $index => $row)
                @php
                    $danaPenPkm = $row->dana_penelitian + $row->dana_pkm;
                    $totalDana = $row->dana_pendidikan + $danaPenPkm + $row->dana_investasi;
                    $rataRata = $row->jumlah_mahasiswa_aktif > 0 ? $totalDana / $row->jumlah_mahasiswa_aktif : 0;
                    
                    $totMhs += $row->jumlah_mahasiswa_aktif;
                    $totOpr += $row->dana_pendidikan;
                    $totPenPkm += $danaPenPkm;
                    $totInv += $row->dana_investasi;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">{{ $row->tahun_akademik }}</td>
                    <td>{{ number_format($row->jumlah_mahasiswa_aktif) }}</td>
                    <td class="text-right">{{ number_format($row->dana_pendidikan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($danaPenPkm, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->dana_investasi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($rataRata, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @if($keuangans->count() === 0)
                <tr>
                    <td colspan="7">Belum ada data keuangan prodi.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="2" style="text-align: right; padding-right: 15px;">TOTAL KESELURUHAN</td>
                <td>{{ number_format($totMhs) }}</td>
                <td class="text-right">{{ number_format($totOpr, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totPenPkm, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totInv, 0, ',', '.') }}</td>
                <td class="text-right">
                    @php
                        $grandTotal = $totOpr + $totPenPkm + $totInv;
                        echo $totMhs > 0 ? number_format($grandTotal / $totMhs, 0, ',', '.') : '0';
                    @endphp
                </td>
            </tr>
        </tfoot>
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
</body>
</html>
