<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ts = \Illuminate\Support\Facades\DB::table('ts')
    ->orderByRaw('SUBSTR(tahun_sekarang, -9) DESC')
    ->orderBy('tahun_sekarang', 'ASC')
    ->get();
foreach ($ts as $t) {
    echo $t->id . ' - ' . $t->tahun_sekarang . "\n";
}
