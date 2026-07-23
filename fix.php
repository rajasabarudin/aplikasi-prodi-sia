<?php
$file = 'resources/views/dosen/cetak_profil.blade.php';
$content = file_get_contents($file);
$content = str_replace('tahun_akademik', 'tahun_sekarang', $content);
file_put_contents($file, $content);
echo "Replaced all tahun_akademik to tahun_sekarang\n";
