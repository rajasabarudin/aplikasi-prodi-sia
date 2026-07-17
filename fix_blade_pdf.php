<?php
$content = <<<'EOD'
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

    @if(empty($data) || (is_iterable($data) && count($data) == 0))
        <div style="margin-top: 30px; text-align: center; padding: 20px; background: #f9f9f9; border: 1px dashed #ccc;">
            Belum ada data untuk laporan ini.
        </div>
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
EOD;

file_put_contents('resources/views/obe/pdf_recap.blade.php', $content);
echo "pdf_recap.blade.php replaced with dynamic table generator.";
