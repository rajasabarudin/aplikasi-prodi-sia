<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = \App\Models\Matakuliah::whereIn('kode_matakuliah', ['0617', '617'])->get();
foreach($items as $i) {
    echo "ID: {$i->id}, Kode: {$i->kode_matakuliah}, Nama: {$i->nama_matakuliah}\n";
}
