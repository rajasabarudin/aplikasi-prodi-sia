<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$rpss = \App\Models\Rps::whereIn('kode_matakuliah', ['0617', '617'])->get()->toArray();
echo json_encode($rpss, JSON_PRETTY_PRINT);
