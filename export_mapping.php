<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mks = \App\Models\Matakuliah::all(['kode_matakuliah', 'nama_matakuliah'])->toArray();
$penelitians = \App\Models\PenelitianDosen::all(['id', 'judul_penelitian'])->toArray();

// Check if PKMDosen has tema_pkm or judul_kegiatan
$pkm_columns = \Illuminate\Support\Facades\Schema::getColumnListing('pkm_dosens');
$pkm_select = ['id'];
if (in_array('tema_pkm', $pkm_columns)) $pkm_select[] = 'tema_pkm';
if (in_array('judul_kegiatan', $pkm_columns)) $pkm_select[] = 'judul_kegiatan';
if (in_array('judul_pkm', $pkm_columns)) $pkm_select[] = 'judul_pkm';

$pkms = \App\Models\PKMDosen::all($pkm_select)->toArray();

file_put_contents('data_mapping.json', json_encode([
    'mks' => $mks,
    'penelitians' => $penelitians,
    'pkms' => $pkms
], JSON_PRETTY_PRINT));
echo "Exported.";
