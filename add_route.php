<?php
$file = 'routes/web.php';
$content = file_get_contents($file);
$insert = "Route::resource('penghargaan-universitas', \App\Http\Controllers\PenghargaanUniversitasController::class);\n    Route::resource('berita'";
$content = str_replace("Route::resource('berita'", $insert, $content);
file_put_contents($file, $content);
