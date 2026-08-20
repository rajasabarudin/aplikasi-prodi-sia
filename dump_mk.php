<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mks = \App\Models\Matakuliah::all();
$output = [];
foreach($mks as $mk) {
    $output[] = $mk->kode_matakuliah . ' | ' . $mk->nama_matakuliah;
}
file_put_contents('db_mks.txt', implode("\n", $output));
echo "Done";
