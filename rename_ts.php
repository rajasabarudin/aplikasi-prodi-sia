<?php
$file = 'resources/views/dosen/cetak_profil.blade.php';
$content = file_get_contents($file);
$content = str_replace('Tahun (TS)', 'Tahun Akademik (TA)', $content);
file_put_contents($file, $content);
echo "Replaced all Tahun (TS) to Tahun Akademik (TA)\n";
