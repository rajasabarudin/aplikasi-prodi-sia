<?php
$index = file_get_contents('resources/views/obe/index.blade.php');

$badge_p5 = '
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-award me-1"></i>Live: {{ $serkomCount ?? 0 }} Sertifikasi Mhs</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C6\', \'ppepp\' => \'P5_Serkom\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-trophy me-1"></i>Live: {{ $prestasiCount ?? 0 }} Prestasi Mhs</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C6\', \'ppepp\' => \'P5_Prestasi\']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-lightbulb me-1"></i>Live: {{ $hkiCount ?? 0 }} Hak Cipta/HKI</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C6\', \'ppepp\' => \'P5_HKI\']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-journal-check me-1"></i>{{ $organisasiCount ?? 0 }} Org. Mahasiswa</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C6\', \'ppepp\' => \'P5_Organisasi\']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
</div>
';

// Replace in C6
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P5"', '<td>' . $badge_p5 . '@include("obe.partials.ppepp_docs", ["kCode" => "C6", "pCode" => "P5"', $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Injected intelligent assessor data into C6.";
