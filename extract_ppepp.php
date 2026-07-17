<?php
$backup = file_get_contents('resources/views/obe/index_backup.blade.php');
$parts = explode('<!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->', $backup);
$ppepp_section = $parts[1];

$tbody = explode('<tbody>', $ppepp_section)[1];
$tbody = explode('</tbody>', $tbody)[0];

file_put_contents('ppepp_tbody_backup.html', $tbody);
echo "Extracted.";
