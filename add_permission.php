<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\HakAkses::firstOrCreate([
    'level' => 'jendral',
    'menu' => 'penghargaan-universitas'
]);
echo 'Permission added to DB.';
