<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$matakuliahs = \App\Models\Matakuliah::whereIn('kode_matakuliah', ['0617', '617'])->get();
foreach($matakuliahs as $mk) {
    echo "ID: {$mk->id} | KODE: {$mk->kode_matakuliah} | NAMA: {$mk->nama_matakuliah}\n";
    echo "<button data-bs-target='#editMatakuliahModal' data-id='{$mk->id}' data-kode_matakuliah='{$mk->kode_matakuliah}' data-nama_matakuliah='{$mk->nama_matakuliah}'></button>\n";
}
