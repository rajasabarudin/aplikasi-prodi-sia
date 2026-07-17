<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = \Illuminate\Support\Facades\DB::table('rps_penelitian')
    ->join('rps', 'rps.id', '=', 'rps_penelitian.rps_id')
    ->join('matakuliahs', 'matakuliahs.kode_matakuliah', '=', 'rps.kode_matakuliah')
    ->join('penelitian_dosens', 'penelitian_dosens.id', '=', 'rps_penelitian.penelitian_dosen_id')
    ->select('matakuliahs.kode_matakuliah as Kode_MK', 'matakuliahs.nama_matakuliah as Nama_Mata_Kuliah', 'penelitian_dosens.nama_jurnal as Judul_Penelitian', 'rps_penelitian.bentuk_integrasi as Bentuk_Integrasi')
    ->get();
print_r($data->toArray());
