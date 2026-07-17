<?php
$index = file_get_contents('resources/views/obe/index.blade.php');

$badge_e = '
<div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-chat-square-heart me-1"></i>{{ $surveiKepuasanCount ?? 0 }} Survei Kepuasan</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'Survei\', \'ppepp\' => \'P3\']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
</div>
';

$badge_p = '
<div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span><i class="bi bi-arrow-repeat me-1"></i>Live: {{ isset($cqiLogs) ? $cqiLogs->count() : 0 }} CQI Logs</span>
    <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C1\', \'ppepp\' => \'P5\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
</div>
';

// C1 is the first table. The order is: P1, P2, P3, P4, P5
// We need to inject $badge_e into P3 (Evaluasi) and $badge_p into P5 (Peningkatan)
// The HTML structure is:
// <td>@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P3"...
$search_p3 = '<td>@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P3"';
$replace_p3 = '<td>' . $badge_e . '@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P3"';
$index = str_replace($search_p3, $replace_p3, $index);

$search_p5 = '<td>@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P5"';
$replace_p5 = '<td>' . $badge_p . '@include("obe.partials.ppepp_docs", ["kCode" => "C1", "pCode" => "P5"';
$index = str_replace($search_p5, $replace_p5, $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Injected intelligent assessor data into C1.";
