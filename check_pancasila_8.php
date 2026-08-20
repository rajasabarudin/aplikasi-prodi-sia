<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%Pancasila%')->first();
$rps = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
$p = \App\Models\RpsPertemuan::where('rps_id', $rps->id)->where('minggu_ke', 8)->first();
if($p) {
    print_r($p->toArray());
}

$p16 = \App\Models\RpsPertemuan::where('rps_id', $rps->id)->where('minggu_ke', 16)->first();
if($p16) {
    print_r($p16->toArray());
}
