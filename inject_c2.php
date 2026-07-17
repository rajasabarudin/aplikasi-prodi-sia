<?php
$index = file_get_contents('resources/views/obe/index.blade.php');

$badge_p1 = '
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-graph-up-arrow me-1"></i>Tren PMB: {{ $pmbCount ?? 0 }} Thn</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P1_PMB\']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-file-pdf me-1"></i>Buku Kurikulum</span>
    <a href="/buku kurikulum.pdf" target="_blank" class="badge bg-success text-decoration-none">Buka</a>
</div>
';

$badge_p2 = '
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-people-fill me-1"></i>Live: {{ $jumlahMahasiswa ?? 0 }} Mhs</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P2_Mhs\']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-file-earmark-check me-1"></i>Live: {{ $totalRps ?? 0 }} RPS</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P2_Rps\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
';

$badge_p3 = '
<div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-graph-up me-1"></i>Rerata IPK: {{ $avgIpk ?? 0 }}</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P3_Ipk\']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
<div class="alert alert-dark border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-table me-1"></i>Matriks Kohort</span>
    <div>
        <a href="{{ route(\'kohort.index\') }}" class="badge bg-dark text-decoration-none me-1">Lihat</a>
        <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P3_Kohort\']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
    </div>
</div>
';

$badge_p4 = '
<div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-briefcase me-1"></i>Tracer Study Alumni</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P4_Tracer\']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
</div>
';

$badge_p5 = '
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
    <span><i class="bi bi-journal-code me-1"></i>{{ $capstoneCount ?? 0 }} Capstone/TA</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P5_Capstone\']) }}" target="_blank" class="badge bg-info text-dark text-decoration-none">PDF</a>
</div>
';

// Replace in C2
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P1"', '<td>' . $badge_p1 . '@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P1"', $index);
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P2"', '<td>' . $badge_p2 . '@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P2"', $index);
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P3"', '<td>' . $badge_p3 . '@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P3"', $index);
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P4"', '<td>' . $badge_p4 . '@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P4"', $index);
$index = str_replace('<td>@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P5"', '<td>' . $badge_p5 . '@include("obe.partials.ppepp_docs", ["kCode" => "C2", "pCode" => "P5"', $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Injected intelligent assessor data into C2.";
