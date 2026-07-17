<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');
$content = str_replace(
    '        $pdf = \PDF::loadView($template, compact("title", "data", "mahasiswaMap"));
        $pdf->setPaper("a4", "landscape");
        return $pdf->stream("Rekap_Data_" . $kriteria . "_" . $ppepp . ".pdf");',
    "        return view(\$template, compact('kriteria', 'ppepp', 'title', 'data', 'mahasiswaMap'));",
    $content
);
file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Restored return view().";
