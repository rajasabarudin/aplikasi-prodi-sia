<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mk = \App\Models\Matakuliah::where('nama_matakuliah', 'like', '%Pancasila%')->first();
$rps = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
$pertemuans = \App\Models\RpsPertemuan::where('rps_id', $rps->id)->get();

foreach($pertemuans as $p) {
    // We fix sub_cpmk, bahan_kajian, metode_pembelajaran, dll
    $fields = ['sub_cpmk', 'bahan_kajian', 'metode_pembelajaran', 'waktu_pembelajaran', 'pengalaman_belajar', 'kriteria_penilaian', 'indikator_penilaian'];
    
    foreach($fields as $f) {
        if (!empty($p->$f)) {
            // Replace newline that is NOT followed by a number and a dot, with a space
            $fixed = preg_replace('/\r\n|\r|\n/', "\n", $p->$f); // Normalize newlines
            // Add a lookahead: if the next line does NOT start with a number, a dot, or a dash
            $fixed = preg_replace('/\n(?!\s*(?:[0-9]+\.|-))/', ' ', $fixed);
            $p->$f = $fixed;
        }
    }
    $p->save();
}

echo "Database fixed!";
