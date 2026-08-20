<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pertemuans = \App\Models\RpsPertemuan::all();

foreach($pertemuans as $p) {
    $fields = ['sub_cpmk', 'bahan_kajian', 'metode_pembelajaran', 'waktu_pembelajaran', 'pengalaman_belajar', 'kriteria_penilaian', 'indikator_penilaian'];
    
    foreach($fields as $f) {
        if (!empty($p->$f)) {
            $fixed = preg_replace('/\r\n|\r|\n/', "\n", $p->$f);
            // Replace newline not followed by number/dot/dash with space
            $fixed = preg_replace('/\n(?!\s*(?:[0-9]+\.|-))/', ' ', $fixed);
            $p->$f = $fixed;
        }
    }
    $p->save();
}

echo "All RPS fixed!";
