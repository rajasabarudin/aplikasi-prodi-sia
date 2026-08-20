<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$totalPenelitian = \App\Models\Matakuliah::whereHas('rps.penelitians')->count();
$totalPkm = \App\Models\Matakuliah::whereHas('rps.pkms')->count();

echo "Penelitian: $totalPenelitian, PkM: $totalPkm\n";
