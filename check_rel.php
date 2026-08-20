<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rps1 = \App\Models\Rps::where('kode_matakuliah', '0617')->first();
$rps2 = \App\Models\Rps::where('kode_matakuliah', '617')->first();

echo "RPS 0617 ID: " . ($rps1 ? $rps1->id : 'none') . "\n";
echo "RPS 617 ID: " . ($rps2 ? $rps2->id : 'none') . "\n";

if ($rps1) echo "MK 0617 nama: " . $rps1->matakuliah->nama_matakuliah . "\n";
if ($rps2) echo "MK 617 nama: " . $rps2->matakuliah->nama_matakuliah . "\n";
