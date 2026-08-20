<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\Rps::all() as $r) {
    echo $r->kode_matakuliah . ' - ' . ($r->matakuliah ? $r->matakuliah->nama_matakuliah : 'NULL') . "\n";
}
