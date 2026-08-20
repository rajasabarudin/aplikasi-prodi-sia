<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%Inggris%')->first();
if ($mk) {
    echo "MK: " . $mk->nama_matakuliah . "\n";
    $rps = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
    if ($rps) {
        $pertemuans = \App\Models\RpsPertemuan::where('rps_id', $rps->id)->get();
        foreach($pertemuans as $p) {
            echo "Mg " . $p->minggu_ke . " | Sub: " . substr($p->sub_cpmk, 0, 30) . "\n";
            echo "Bahan: " . $p->bahan_kajian . "\n------------------------\n";
        }
    } else {
        echo "RPS not found\n";
    }
} else {
    echo "MK not found\n";
}
