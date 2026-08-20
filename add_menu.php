<?php
$file = 'app/Http/Controllers/HakAksesController.php';
$content = file_get_contents($file);
$insert = "'penghargaan-universitas' => 'Penghargaan Universitas',\n        'profil-prodi'";
$content = str_replace("'profil-prodi'", $insert, $content);
file_put_contents($file, $content);
