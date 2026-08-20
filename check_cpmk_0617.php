<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk_id = '0617';
$cpmk_ids = \Illuminate\Support\Facades\DB::table('cpmk_matakuliah')->where('kode_matakuliah', $mk_id)->pluck('cpmk_id');
$cpmks = \App\Models\Cpmk::whereIn('id', $cpmk_ids)->get();

echo "CPMK for 0617 from pivot:\n";
print_r($cpmks->toArray());

$cpmks_direct = \App\Models\Cpmk::where('kode_matakuliah', $mk_id)->get();
echo "\nCPMK for 0617 direct column:\n";
print_r($cpmks_direct->toArray());
