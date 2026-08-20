<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dosens = \App\Models\Dosen::all(['nama_dosen'])->toArray();
$penelitians = \App\Models\PenelitianDosen::all(['nama_dosen', 'judul_penelitian'])->toArray();
$pkms = \App\Models\PKMDosen::all(['nama_dosen', 'tema_pkm'])->toArray();

echo json_encode([
    'dosens' => $dosens,
    'penelitians' => $penelitians,
    'pkms' => $pkms
], JSON_PRETTY_PRINT);
