<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hibah = \App\Models\HibahPenelitian::all(['judul_penelitian'])->toArray();
echo "Hibah: " . json_encode($hibah) . "\n";
