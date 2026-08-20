<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk1 = \App\Models\Matakuliah::where('kode_matakuliah', '0617')->first();
$mk2 = \App\Models\Matakuliah::where('kode_matakuliah', '617')->first();

echo "ID 26 Button data:\n";
echo "data-kode: " . $mk1->kode_matakuliah . "\n";
echo "data-nama: " . $mk1->nama_matakuliah . "\n";
