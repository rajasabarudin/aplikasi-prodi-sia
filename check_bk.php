<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bk = \App\Models\BahanKajian::whereIn('kode_matakuliah', ['0617', '617'])->get()->toArray();
$ref = \App\Models\Referensi::whereIn('kode_matakuliah', ['0617', '617'])->get()->toArray();

echo "Bahan Kajian:\n";
echo json_encode($bk, JSON_PRETTY_PRINT) . "\n";
echo "Referensi:\n";
echo json_encode($ref, JSON_PRETTY_PRINT) . "\n";
