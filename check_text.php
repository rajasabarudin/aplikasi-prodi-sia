<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%Pancasila%')->first();
$rps = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
$p = \App\Models\RpsPertemuan::where('rps_id', $rps->id)->where('minggu_ke', 1)->first();
echo "== TEXT START ==\n";
echo $p->bahan_kajian;
echo "\n== TEXT END ==\n";
