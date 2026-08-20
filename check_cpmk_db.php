<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cpmks = \App\Models\Cpmk::all()->toArray();
echo "Total CPMK in DB: " . count($cpmks) . "\n";
if(count($cpmks) > 0) {
    echo "Sample CPMK: \n";
    print_r($cpmks[0]);
}

$cpmk_mk = \Illuminate\Support\Facades\DB::table('cpmk_matakuliah')->get();
echo "\nTotal CPMK-MK in pivot: " . count($cpmk_mk) . "\n";
if(count($cpmk_mk) > 0) {
    echo "Sample Pivot: \n";
    print_r((array)$cpmk_mk[0]);
}

$mk_cpmk = \App\Models\Matakuliah::where('kode_matakuliah', '0617')->with('cpmks')->first();
if ($mk_cpmk) {
    echo "\nCPMKs for MK 0617 via relation: \n";
    print_r($mk_cpmk->cpmks->toArray());
}
