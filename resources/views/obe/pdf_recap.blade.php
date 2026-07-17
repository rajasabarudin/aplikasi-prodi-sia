<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; padding: 20px; background-color: #fff; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.85rem; }
        table.data-table th, table.data-table td { border: 1px solid #333; padding: 8px 10px; text-align: left; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; text-transform: capitalize; text-align: center; }
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
        @media print { .no-print { display: none !important; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="no-print-btn">Cetak / Simpan Sebagai PDF</button>
    </div>

    <div class="header-wrap">
        <div class="header-logo">
            <img src="{{ asset('img/logo_ubsi.png') }}" alt="Logo UBSI">
        </div>
        <div class="header-text">
            <h1>Universitas Bina Sarana Informatika</h1>
            <h1>Kampus Kota Pontianak</h1>
            <h2>Program Studi Sistem Informasi Akuntansi (D3)</h2>
            <p>Laporan Evaluasi Diri (LED) &amp; Penjaminan Mutu | Standar Akreditasi LAM INFOKOM</p>
        </div>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%; font-weight: bold;">Kriteria:</td>
                <td style="width: 35%;">Kriteria {{ $kriteria }}</td>
                <td style="width: 15%; font-weight: bold;">Tanggal Cetak:</td>
                <td style="width: 35%;">{{ date('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Topik Laporan:</td>
                <td colspan="3" style="font-weight: bold; color: #111;">{{ $title }}</td>
            </tr>
        </table>
    </div>

    @if(isset($statistics) && is_array($statistics))
        <div style="margin-top: 15px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; margin-bottom: 8px; font-size: 1rem; color: #333; text-transform: uppercase;">Statistik Ringkas</h4>
            <table class="data-table" style="margin-top: 0; width: 50%;">
                <tbody>
                    @foreach($statistics as $label => $value)
                        <tr>
                            <td style="font-weight: bold; width: 70%; background-color: #fafafa;">{{ $label }}</td>
                            <td style="width: 30%; text-align: center;">{{ $value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(empty($data) || (is_iterable($data) && count($data) == 0))
        <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f9f9f9; border: 1px dashed #ccc;">
            Belum ada data untuk laporan ini.
        </div>
    @elseif($kriteria === 'C2' && $ppepp === 'P3_Ipk')
        @php
            $grouped = collect($data)->groupBy('Tahun_Akademik');
        @endphp
        
        @foreach($grouped as $ta => $mhsList)
            @php
                $avg = $mhsList->avg('IPK');
            @endphp
            <div style="margin-top: 25px; margin-bottom: 5px; border-bottom: 2px solid #333; padding-bottom: 5px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 1.1rem;">Tahun Akademik: {{ $ta }}</h3>
                <h3 style="margin: 0; font-size: 1.1rem; color: #4f46e5;">Rata-rata IPK: {{ number_format($avg, 2) }}</h3>
            </div>
            
            <table class="data-table" style="margin-top: 0;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">NIM</th>
                        <th style="width: 50%;">Nama Lengkap</th>
                        <th style="width: 20%;">IPK</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mhsList as $index => $m)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ is_object($m) ? $m->NIM : $m['NIM'] }}</td>
                            <td>{{ is_object($m) ? $m->Nama_Lengkap : $m['Nama_Lengkap'] }}</td>
                            <td class="text-center">{{ number_format(is_object($m) ? $m->IPK : $m['IPK'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
        @php
            $rows = is_array($data) ? $data : (method_exists($data, 'toArray') ? $data->toArray() : (array)$data);
            if(isset($rows['data'])) $rows = $rows['data']; // In case of pagination
            
            // Exclude common internal columns
            $exclude = ['id', 'created_at', 'updated_at', 'deleted_at', 'ts_id'];
            
            // Get headers from first row
            $first = (array) reset($rows);
            // Sometimes it has relations, let's just pick scalar values
            $headers = [];
            foreach($first as $key => $val) {
                if(!in_array($key, $exclude) && is_scalar($val)) {
                    $headers[] = $key;
                }
            }
            
            // If data is heavily nested like Kohort, we fallback to basic view or JSON string
            if(empty($headers) && !empty($first)) {
                $headers = array_keys($first);
            }
        @endphp

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    @foreach($headers as $h)
                        <th>{{ str_replace('_', ' ', $h) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $index => $row)
                    @php $row = (array) $row; @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        @foreach($headers as $h)
                            <td>
                                @php 
                                    $val = $row[$h] ?? '-';
                                    if(is_array($val) || is_object($val)) {
                                        echo "-";
                                    } else {
                                        // Format currency if key contains biaya/dana
                                        if (str_contains(strtolower($h), 'biaya') || str_contains(strtolower($h), 'dana')) {
                                            echo 'Rp ' . number_format((float)$val, 0, ',', '.');
                                        } else {
                                            echo $val;
                                        }
                                    }
                                @endphp
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>