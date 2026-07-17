<?php
$index = file_get_contents('resources/views/obe/index.blade.php');

$badge_p2 = '
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-heart-pulse me-1"></i>{{ $pkmCount ?? 0 }} PkM Dosen</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C4\', \'ppepp\' => \'P2\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-people me-1"></i>{{ $praktisiCount ?? 0 }} Praktisi Mengajar</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C4\', \'ppepp\' => \'P2_Praktisi\']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
</div>
';

$badge_p5 = '
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPkmCount ?? 0 }} Integrasi ke RPS</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C4\', \'ppepp\' => \'Integrasi\']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">PDF</a>
</div>
';

// Replace in C4
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P2"', '<td>' . $badge_p2 . '@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P2"', $index);
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P5"', '<td>' . $badge_p5 . '@include("obe.partials.ppepp_docs", ["kCode" => "C4", "pCode" => "P5"', $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Injected intelligent assessor data into C4.";
