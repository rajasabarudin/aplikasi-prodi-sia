<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PrestasiDosen;
use App\Models\RekognisiDosen;
use App\Models\TS;

return new class extends Migration
{
    public function up()
    {
        $prestasis = PrestasiDosen::all();

        foreach ($prestasis as $prestasi) {
            $kodeDosenStr = $prestasi->kode_dosen;
            $namaDosenStr = $prestasi->nama_dosen;

            $kodeDosens = array_filter(array_map('trim', explode(',', $kodeDosenStr)));
            $namaDosens = array_filter(array_map('trim', explode(',', $namaDosenStr)));

            if (empty($kodeDosens)) {
                continue;
            }

            $ts = TS::find($prestasi->ts_id);
            $tahun = $prestasi->tahun;
            if (empty($tahun) && $ts) {
                if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                    $tahun = $matches[0];
                } else {
                    $tahun = substr($ts->tahun_sekarang, 0, 10);
                }
            }
            if (empty($tahun)) {
                $tahun = date('Y');
            }

            $diraih = $prestasi->prestasi_diraih;
            $namaRekognisi = "Prestasi: {$prestasi->nama_prestasi}" . ($diraih ? " ({$diraih})" : "");
            if (strlen($namaRekognisi) > 200) {
                $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
            }

            $level = $prestasi->level_prestasi ?: 'lokal';
            $linkDokumen = $prestasi->link_dokumen;

            foreach ($kodeDosens as $index => $kode) {
                if (empty($kode)) continue;
                
                $nama = '';
                if (isset($namaDosens[$index])) {
                    $nama = $namaDosens[$index];
                } else {
                    $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                    $nama = $dosen ? $dosen->nama_dosen : '';
                }

                $exists = RekognisiDosen::where('kode_dosen', $kode)
                    ->where('prestasi_dosen_id', $prestasi->id)
                    ->exists();

                if (!$exists) {
                    RekognisiDosen::create([
                        'kode_dosen' => $kode,
                        'nama_dosen' => $nama,
                        'nama_rekognisi' => $namaRekognisi,
                        'tahun' => $tahun,
                        'ts_id' => $prestasi->ts_id,
                        'level' => $level,
                        'link_dokumen' => $linkDokumen,
                        'is_keanggotaan' => false,
                        'prestasi_dosen_id' => $prestasi->id,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        RekognisiDosen::whereNotNull('prestasi_dosen_id')->delete();
    }
};
