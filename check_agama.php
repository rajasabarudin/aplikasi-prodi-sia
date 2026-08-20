<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%Agama%')->first();
if ($mk) {
    print_r($mk->toArray());
    
    $rps = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
    if ($rps) {
        echo "\nFound RPS for this MK:\n";
        print_r($rps->toArray());
    } else {
        echo "\nNo RPS found for this MK.\n";
    }
} else {
    echo "MK not found.";
}
