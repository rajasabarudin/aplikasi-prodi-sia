<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kegiatans = \App\Models\Kegiatan::all();
foreach ($kegiatans as $kegiatan) {
    $pesertas = \App\Models\PesertaKegiatan::where('kegiatan_id', $kegiatan->id)
        ->where('status_kehadiran', 'hadir_lengkap')
        ->where('kategori', 'Tenaga Kependidikan')
        ->get();
        
    foreach ($pesertas as $peserta) {
        $ts = \App\Models\Ts::where('label_ts', 'TS')->first() ?? \App\Models\Ts::first();
        $tsId = $ts ? $ts->id : 1;
        
        $tendik = \App\Models\Tendik::where('nip_nik', $peserta->identifier)->first();
        if ($tendik) {
            \App\Models\KegiatanTendik::updateOrCreate(
                [
                    'nip_nik' => $tendik->nip_nik,
                    'kegiatan_prodi_id' => $kegiatan->id,
                ],
                [
                    'nama_tendik' => $peserta->nama,
                    'nama_kegiatan' => $kegiatan->nama_kegiatan,
                    'tahun' => $kegiatan->tanggal ? date('Y', strtotime($kegiatan->tanggal)) : date('Y'),
                    'ts_id' => $tsId,
                    'penyelenggara' => 'Internal Prodi SIA',
                    'jenis' => 'Internal',
                ]
            );
        }
    }
}
echo 'Synced';
