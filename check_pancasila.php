<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%Pancasila%')->first();
$rps = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
if ($rps) {
    $pertemuans = \App\Models\RpsPertemuan::where('rps_id', $rps->id)->get();
    foreach($pertemuans as $p) {
        echo $p->minggu_ke . ' | ' . substr($p->bahan_kajian, 0, 50) . "\n";
    }
}
