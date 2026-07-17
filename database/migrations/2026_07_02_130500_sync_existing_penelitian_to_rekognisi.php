<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PenelitianDosen;
use App\Models\RekognisiDosen;
use App\Models\TS;

return new class extends Migration
{
    public function up()
    {
        $qualifyingTypes = [
            'Jurnal Nasional Terakreditasi (SINTA)' => 'nasional',
            'Jurnal Internasional' => 'internasional',
            'Jurnal Internasional Bereputasi (Scopus/WoS)' => 'internasional',
        ];

        $penelitians = PenelitianDosen::all();

        foreach ($penelitians as $penelitian) {
            $jenisJurnal = $penelitian->jenis_jurnal;

            if (array_key_exists($jenisJurnal, $qualifyingTypes)) {
                $level = $qualifyingTypes[$jenisJurnal];

                // Split kode_dosen and nama_dosen
                $kodeDosens = array_filter(array_map('trim', explode(',', $penelitian->kode_dosen)));
                $namaDosens = array_filter(array_map('trim', explode(',', $penelitian->nama_dosen)));

                $ts = TS::find($penelitian->ts_id);
                $tahun = date('Y');
                if ($ts) {
                    if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                        $tahun = $matches[0];
                    } else {
                        $tahun = substr($ts->tahun_sekarang, 0, 10);
                    }
                }

                $namaJurnal = $penelitian->nama_jurnal;
                $namaRekognisi = "Publikasi Jurnal: {$namaJurnal} ({$jenisJurnal})";
                if (strlen($namaRekognisi) > 200) {
                    $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
                }

                $linkDokumen = $penelitian->berkas_paper ?: $penelitian->link_jurnal;

                foreach ($kodeDosens as $index => $kode) {
                    if (empty($kode)) continue;
                    
                    // Match corresponding name by index
                    // Reconstruct index matching safely
                    $nama = '';
                    if (isset($namaDosens[$index])) {
                        $nama = $namaDosens[$index];
                    } else {
                        // fallback to finding the Dosen name from dosens table
                        $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                        $nama = $dosen ? $dosen->nama_dosen : '';
                    }

                    // Prevent duplicate if already exists
                    $exists = RekognisiDosen::where('kode_dosen', $kode)
                        ->where('penelitian_dosen_id', $penelitian->id)
                        ->exists();

                    if (!$exists) {
                        RekognisiDosen::create([
                            'kode_dosen' => $kode,
                            'nama_dosen' => $nama,
                            'nama_rekognisi' => $namaRekognisi,
                            'tahun' => $tahun,
                            'ts_id' => $penelitian->ts_id,
                            'level' => $level,
                            'link_dokumen' => $linkDokumen,
                            'is_keanggotaan' => false,
                            'penelitian_dosen_id' => $penelitian->id,
                        ]);
                    }
                }
            }
        }
    }

    public function down()
    {
        RekognisiDosen::whereNotNull('penelitian_dosen_id')->delete();
    }
};
