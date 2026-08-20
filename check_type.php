<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$type1 = \Illuminate\Support\Facades\Schema::getColumnType('matakuliahs', 'kode_matakuliah');
$type2 = \Illuminate\Support\Facades\Schema::getColumnType('rps', 'kode_matakuliah');

echo "matakuliahs.kode_matakuliah: $type1\n";
echo "rps.kode_matakuliah: $type2\n";
