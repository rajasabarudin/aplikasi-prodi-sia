<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\HibahPenelitian;
use App\Models\PrestasiDosen;
use App\Models\RekognisiDosen;
use App\Models\TS;

return new class extends Migration
{
    public function up()
    {
        $hibahs = HibahPenelitian::where('jenis_hibah', 'eksternal')->get();

        foreach ($hibahs as $hibah) {
            $kodeDosens = array_filter(array_map('trim', explode(',', $hibah->kode_dosen)));
            $namaDosens = array_filter(array_map('trim', explode(',', $hibah->nama_dosen)));

            $ts = TS::find($hibah->ts_id);
            $tahun = date('Y');
            if ($ts) {
                if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                    $tahun = $matches[0];
                } else {
                    $tahun = substr($ts->tahun_sekarang, 0, 10);
                }
            }

            $level = 'nasional';
            $skema = strtolower($hibah->skema_hibah);
            $pemberi = strtolower($hibah->pemberi_hibah);
            if (str_contains($skema, 'internasional') || str_contains($pemberi, 'internasional') || str_contains($skema, 'international') || str_contains($pemberi, 'international')) {
                $level = 'internasional';
            }

            $judul = $hibah->judul;
            $skemaHibah = $hibah->skema_hibah;
            $namaPrestasi = "Hibah Penelitian Eksternal: {$judul} ({$skemaHibah})";
            if (strlen($namaPrestasi) > 200) {
                $namaPrestasi = substr($namaPrestasi, 0, 197) . '...';
            }

            $penyelenggara = $hibah->pemberi_hibah ?: 'Pemberi Hibah';
            $linkDokumen = $hibah->link_st ?: ($hibah->link_spk ?: ($hibah->link_laporan ?: $hibah->link_proposal));

            foreach ($kodeDosens as $index => $kode) {
                if (empty($kode)) continue;
                
                $nama = '';
                if (isset($namaDosens[$index])) {
                    $nama = $namaDosens[$index];
                } else {
                    $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                    $nama = $dosen ? $dosen->nama_dosen : '';
                }

                // Check if PrestasiDosen already exists for this lecturer and this hibah
                $prestasi = PrestasiDosen::where('kode_dosen', $kode)
                    ->where('hibah_penelitian_id', $hibah->id)
                    ->first();

                if (!$prestasi) {
                    $prestasi = PrestasiDosen::create([
                        'kode_dosen' => $kode,
                        'nama_dosen' => $nama,
                        'nama_prestasi' => $namaPrestasi,
                        'tahun' => $tahun,
                        'ts_id' => $hibah->ts_id,
                        'penyelenggara' => $penyelenggara,
                        'level_prestasi' => $level,
                        'prestasi_diraih' => 'Hibah Eksternal',
                        'link_dokumen' => $linkDokumen,
                        'hibah_penelitian_id' => $hibah->id,
                    ]);
                }

                // Check if RekognisiDosen already exists for this lecturer and this PrestasiDosen
                $exists = RekognisiDosen::where('kode_dosen', $kode)
                    ->where('prestasi_dosen_id', $prestasi->id)
                    ->exists();

                if (!$exists) {
                    RekognisiDosen::create([
                        'kode_dosen' => $kode,
                        'nama_dosen' => $nama,
                        'nama_rekognisi' => $namaPrestasi,
                        'tahun' => $tahun,
                        'ts_id' => $hibah->ts_id,
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
        // Deleting PrestasiDosen will cascade delete RekognisiDosen records
        PrestasiDosen::whereNotNull('hibah_penelitian_id')->delete();
    }
};
