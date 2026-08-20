<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$list = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%**%')->pluck('nama_matakuliah')->toArray();
print_r($list);
