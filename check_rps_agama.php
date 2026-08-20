<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rpsList = \App\Models\Rps::with('matakuliah')->get();
foreach($rpsList as $r) {
    if (strpos($r->matakuliah->nama_matakuliah, 'Agama') !== false) {
        echo "FOUND: " . $r->matakuliah->nama_matakuliah . "\n";
        echo "ID: " . $r->id . "\n";
    }
}
