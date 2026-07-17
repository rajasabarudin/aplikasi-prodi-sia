<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\HibahPenelitian;
use App\Models\Hki;
use App\Models\RekognisiDosen;
use App\Models\TS;

return new class extends Migration
{
    public function up()
    {
        // 1. Sync Existing Hibah
        $hibahs = HibahPenelitian::all();
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

            $jenisHibah = $hibah->jenis_hibah;
            $level = 'lokal';
            if ($jenisHibah === 'eksternal') {
                $level = 'nasional';
                $skema = strtolower($hibah->skema_hibah);
                $pemberi = strtolower($hibah->pemberi_hibah);
                if (str_contains($skema, 'internasional') || str_contains($pemberi, 'internasional') || str_contains($skema, 'international') || str_contains($pemberi, 'international')) {
                    $level = 'internasional';
                }
            }

            $namaRekognisi = "Hibah Penelitian: {$hibah->judul} ({$hibah->skema_hibah})";
            if (strlen($namaRekognisi) > 200) {
                $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
            }

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

                $exists = RekognisiDosen::where('kode_dosen', $kode)
                    ->where('hibah_penelitian_id', $hibah->id)
                    ->exists();

                if (!$exists) {
                    RekognisiDosen::create([
                        'kode_dosen' => $kode,
                        'nama_dosen' => $nama,
                        'nama_rekognisi' => $namaRekognisi,
                        'tahun' => $tahun,
                        'ts_id' => $hibah->ts_id,
                        'level' => $level,
                        'link_dokumen' => $linkDokumen,
                        'is_keanggotaan' => false,
                        'hibah_penelitian_id' => $hibah->id,
                    ]);
                }
            }
        }

        // 2. Sync Existing HKI
        $hkis = Hki::all();
        foreach ($hkis as $hki) {
            $kodeDosens = array_filter(array_map('trim', explode(',', $hki->kode_dosen)));
            $namaDosens = array_filter(array_map('trim', explode(',', $hki->nama_dosen)));

            $tahun = date('Y', strtotime($hki->tgl_permohonan));
            $ts = TS::where('tahun_sekarang', 'like', "%{$tahun}%")->first();
            if (!$ts) {
                $ts = TS::orderBy('tahun_sekarang', 'desc')->first();
            }
            $tsId = $ts ? $ts->id : null;

            if (!$tsId) {
                continue;
            }

            $level = 'nasional';
            $jenis = strtolower($hki->jenis_ciptaan);
            $judulCiptaan = strtolower($hki->judul_ciptaan);
            if (str_contains($jenis, 'internasional') || str_contains($judulCiptaan, 'internasional') || str_contains($jenis, 'international') || str_contains($judulCiptaan, 'international')) {
                $level = 'internasional';
            }

            $namaRekognisi = "HKI ({$hki->jenis_ciptaan}): {$hki->judul_ciptaan}";
            if (strlen($namaRekognisi) > 200) {
                $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
            }

            $linkDokumen = $hki->link_dokumen;

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
                    ->where('hki_id', $hki->id)
                    ->exists();

                if (!$exists) {
                    RekognisiDosen::create([
                        'kode_dosen' => $kode,
                        'nama_dosen' => $nama,
                        'nama_rekognisi' => $namaRekognisi,
                        'tahun' => $tahun,
                        'ts_id' => $tsId,
                        'level' => $level,
                        'link_dokumen' => $linkDokumen,
                        'is_keanggotaan' => false,
                        'hki_id' => $hki->id,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        RekognisiDosen::whereNotNull('hibah_penelitian_id')->delete();
        RekognisiDosen::whereNotNull('hki_id')->delete();
    }
};
