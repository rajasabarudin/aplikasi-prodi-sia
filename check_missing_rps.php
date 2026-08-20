<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$matakuliahs = \App\Models\Matakuliah::all();
$rpsKodes = \App\Models\Rps::pluck('kode_matakuliah')->toArray();

foreach($matakuliahs as $mk) {
    if(!in_array($mk->kode_matakuliah, $rpsKodes)) {
        echo "Missing RPS for: " . $mk->kode_matakuliah . " | " . $mk->nama_matakuliah . "\n";
    }
}
