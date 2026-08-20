<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pen_cols = Illuminate\Support\Facades\Schema::getColumnListing('penelitian_dosens');
$pkm_cols = Illuminate\Support\Facades\Schema::getColumnListing('pkm_dosens');

echo "Penelitian: " . json_encode($pen_cols) . "\n";
echo "PKM: " . json_encode($pkm_cols) . "\n";
