<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pesertas = App\Models\PesertaKegiatan::with('kegiatan')->where('kategori', 'Dosen')->where('status_kehadiran', 'hadir_lengkap')->get();
$ts = App\Models\Ts::where('label_ts', 'TS')->first() ?? App\Models\Ts::first();
$tsId = $ts ? $ts->id : 1;
foreach ($pesertas as $peserta) {
    if ($peserta->kegiatan) {
        $dosen = App\Models\Dosen::where('nidn', $peserta->identifier)->orWhere('nip', $peserta->identifier)->orWhere('kode_dosen', $peserta->identifier)->first();
        if ($dosen) {
            App\Models\KegiatanDosen::updateOrCreate(
                [
                    'kode_dosen' => $dosen->kode_dosen,
                    'kegiatan_prodi_id' => $peserta->kegiatan_id,
                ],
                [
                    'nama_dosen' => $peserta->nama,
                    'nama_kegiatan' => $peserta->kegiatan->nama_kegiatan,
                    'tahun' => $peserta->kegiatan->tanggal ? date('Y', strtotime($peserta->kegiatan->tanggal)) : date('Y'),
                    'ts_id' => $tsId,
                    'penyelenggara' => 'Internal Prodi SIA',
                    'jenis' => 'Internal',
                ]
            );
        }
    }
}
echo "Synced: " . $pesertas->count();
