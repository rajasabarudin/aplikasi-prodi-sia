<?php
$index = file_get_contents('resources/views/obe/index.blade.php');

$badge_p2 = '
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-person-video3 me-1"></i>Live: {{ $jumlahDosen ?? 0 }} Dosen</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P2_Dosen\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
@php
    $totalDanaKeuangan = class_exists(\'\App\Models\KeuanganProdi\') ? \App\Models\KeuanganProdi::sum(\'dana_pendidikan\') + \App\Models\KeuanganProdi::sum(\'dana_penelitian\') + \App\Models\KeuanganProdi::sum(\'dana_pkm\') + \App\Models\KeuanganProdi::sum(\'dana_investasi\') : 0;
    $totalDanaFormat = $totalDanaKeuangan >= 1000000000 ? \'Rp \' . number_format($totalDanaKeuangan/1000000000, 1) . \'M\' : ($totalDanaKeuangan >= 1000000 ? \'Rp \' . number_format($totalDanaKeuangan/1000000, 0) . \' Jt\' : \'Rp 0\');
@endphp
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-wallet2 me-1"></i>Dana: {{ $totalDanaFormat }}</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P2_Dana\']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-link-45deg me-1"></i>{{ isset($kerjasamaCount) ? $kerjasamaCount : 0 }} MoU & MoA</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P2_Kerjasama\']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
';

$badge_p5 = '
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-shield-check me-1"></i>{{ isset($sertifikasiDosenCount) ? $sertifikasiDosenCount : 0 }} Sertifikasi Dosen</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P5_Sertifikasi\']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-award me-1"></i>{{ isset($prestasiDosenCount) ? $prestasiDosenCount : 0 }} Prestasi Dosen</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P5_Prestasi\']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-hand-thumbs-up me-1"></i>{{ isset($rekognisiDosenCount) ? $rekognisiDosenCount : 0 }} Rekognisi Dosen</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P5_Rekognisi\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
';

// Replace in C5
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P2"', '<td>' . $badge_p2 . '@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P2"', $index);
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P5"', '<td>' . $badge_p5 . '@include("obe.partials.ppepp_docs", ["kCode" => "C5", "pCode" => "P5"', $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Injected intelligent assessor data into C5.";
