<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tsList = \Illuminate\Support\Facades\DB::table('ts')
    ->orderByRaw('SUBSTR(tahun_sekarang, -9) DESC')
    ->orderBy('tahun_sekarang', 'ASC')
    ->get();

$data = [];
foreach ($tsList as $ts) {
    $tahun = $ts->tahun_sekarang;
    
    // Dana Internal dari keuangan_prodis
    $keuangan = \Illuminate\Support\Facades\DB::table('keuangan_prodis')->where('tahun_akademik', $tahun)->first();
    $danaPend = $keuangan ? $keuangan->dana_pendidikan : 0;
    $danaPenelitianInt = $keuangan ? $keuangan->dana_penelitian : 0;
    $danaPkmInt = $keuangan ? $keuangan->dana_pkm : 0;
    $danaInv = $keuangan ? $keuangan->dana_investasi : 0;
    
    // Dana Eksternal / Hibah berdasarkan ts_id
    $danaHibah = \Illuminate\Support\Facades\DB::table('hibah_penelitians')->where('ts_id', $ts->id)->sum('biaya');
    $danaPenelitianDosen = \Illuminate\Support\Facades\DB::table('penelitian_dosens')->where('ts_id', $ts->id)->sum('biaya');
    $danaPkmDosen = \Illuminate\Support\Facades\DB::table('pkm_dosens')->where('ts_id', $ts->id)->sum('biaya');
    
    $totalPenelitian = $danaPenelitianInt + $danaHibah + $danaPenelitianDosen;
    $totalPkm = $danaPkmInt + $danaPkmDosen;
    $grandTotal = $danaPend + $totalPenelitian + $totalPkm + $danaInv;
    
    if ($grandTotal > 0 || $keuangan) {
        $data[] = [
            "Tahun_Akademik" => $tahun,
            "Dana_Pendidikan" => "Rp " . number_format($danaPend, 0, ',', '.'),
            "Dana_Penelitian_dan_Hibah" => "Rp " . number_format($totalPenelitian, 0, ',', '.'),
            "Dana_Pengabdian_Masyarakat" => "Rp " . number_format($totalPkm, 0, ',', '.'),
            "Dana_Investasi" => "Rp " . number_format($danaInv, 0, ',', '.'),
            "Total_Dana" => "Rp " . number_format($grandTotal, 0, ',', '.')
        ];
    }
}
print_r($data);
