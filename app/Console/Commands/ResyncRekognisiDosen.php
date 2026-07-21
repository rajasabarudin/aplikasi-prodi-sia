<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PenelitianDosen;
use App\Models\HibahPenelitian;
use App\Models\Hki;
use App\Models\PrestasiDosen;
use App\Models\RekognisiDosen;
use App\Models\Ts;

class ResyncRekognisiDosen extends Command
{
    protected $signature = 'rekognisi:resync';
    protected $description = 'Menghapus dan menarik ulang data Rekognisi dari Penelitian, Hibah, HKI, dan Prestasi.';

    public function handle()
    {
        $this->info('Menghapus data rekognisi yang ter-generate otomatis...');
        RekognisiDosen::whereNotNull('penelitian_dosen_id')
            ->orWhereNotNull('hibah_penelitian_id')
            ->orWhereNotNull('hki_id')
            ->orWhereNotNull('prestasi_dosen_id')
            ->delete();

        $this->info('Data lama berhasil dihapus.');

        $this->syncPenelitian();
        $this->syncHibahAndHki();
        $this->syncPrestasi();

        $this->info('Semua data berhasil ditarik ulang (resync) ke tabel Rekognisi Dosen!');
        return Command::SUCCESS;
    }

    private function syncPenelitian()
    {
        $this->info('Sinkronisasi Penelitian...');
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
                $kodeDosens = array_filter(array_map('trim', explode(',', $penelitian->kode_dosen)));
                $namaDosens = array_filter(array_map('trim', explode(',', $penelitian->nama_dosen)));
                
                $ts = Ts::find($penelitian->ts_id);
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
                    
                    $nama = $namaDosens[$index] ?? '';
                    if (empty($nama)) {
                        $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                        $nama = $dosen ? $dosen->nama_dosen : '';
                    }

                    RekognisiDosen::firstOrCreate([
                        'kode_dosen' => $kode,
                        'penelitian_dosen_id' => $penelitian->id,
                    ], [
                        'nama_dosen' => $nama,
                        'nama_rekognisi' => $namaRekognisi,
                        'tahun' => $tahun,
                        'ts_id' => $penelitian->ts_id,
                        'level' => $level,
                        'link_dokumen' => $linkDokumen,
                        'is_keanggotaan' => false,
                    ]);
                }
            }
        }
    }

    private function syncHibahAndHki()
    {
        $this->info('Sinkronisasi Hibah & HKI...');
        // Hibah
        $hibahs = HibahPenelitian::all();
        foreach ($hibahs as $hibah) {
            $kodeDosens = array_filter(array_map('trim', explode(',', $hibah->kode_dosen)));
            $namaDosens = array_filter(array_map('trim', explode(',', $hibah->nama_dosen)));
            $ts = Ts::find($hibah->ts_id);
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
                $nama = $namaDosens[$index] ?? '';
                if (empty($nama)) {
                    $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                    $nama = $dosen ? $dosen->nama_dosen : '';
                }
                RekognisiDosen::firstOrCreate([
                    'kode_dosen' => $kode,
                    'hibah_penelitian_id' => $hibah->id,
                ], [
                    'nama_dosen' => $nama,
                    'nama_rekognisi' => $namaRekognisi,
                    'tahun' => $tahun,
                    'ts_id' => $hibah->ts_id,
                    'level' => $level,
                    'link_dokumen' => $linkDokumen,
                    'is_keanggotaan' => false,
                ]);
            }
        }

        // HKI
        $hkis = Hki::all();
        foreach ($hkis as $hki) {
            $kodeDosens = array_filter(array_map('trim', explode(',', $hki->kode_dosen)));
            $namaDosens = array_filter(array_map('trim', explode(',', $hki->nama_dosen)));
            $tahun = date('Y', strtotime($hki->tgl_permohonan));
            $ts = Ts::where('tahun_sekarang', 'like', "%{$tahun}%")->first() ?: Ts::orderBy('tahun_sekarang', 'desc')->first();
            $tsId = $ts ? $ts->id : null;
            if (!$tsId) continue;

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
                $nama = $namaDosens[$index] ?? '';
                if (empty($nama)) {
                    $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                    $nama = $dosen ? $dosen->nama_dosen : '';
                }
                RekognisiDosen::firstOrCreate([
                    'kode_dosen' => $kode,
                    'hki_id' => $hki->id,
                ], [
                    'nama_dosen' => $nama,
                    'nama_rekognisi' => $namaRekognisi,
                    'tahun' => $tahun,
                    'ts_id' => $tsId,
                    'level' => $level,
                    'link_dokumen' => $linkDokumen,
                    'is_keanggotaan' => false,
                ]);
            }
        }
    }

    private function syncPrestasi()
    {
        $this->info('Sinkronisasi Prestasi...');
        $prestasis = PrestasiDosen::all();
        foreach ($prestasis as $prestasi) {
            $kodeDosens = array_filter(array_map('trim', explode(',', $prestasi->kode_dosen)));
            $namaDosens = array_filter(array_map('trim', explode(',', $prestasi->nama_dosen)));
            if (empty($kodeDosens)) continue;

            $ts = Ts::find($prestasi->ts_id);
            $tahun = $prestasi->tahun;
            if (empty($tahun) && $ts) {
                if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                    $tahun = $matches[0];
                } else {
                    $tahun = substr($ts->tahun_sekarang, 0, 10);
                }
            }
            if (empty($tahun)) $tahun = date('Y');

            $diraih = $prestasi->prestasi_diraih;
            $namaRekognisi = "Prestasi: {$prestasi->nama_prestasi}" . ($diraih ? " ({$diraih})" : "");
            if (strlen($namaRekognisi) > 200) {
                $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
            }
            $level = $prestasi->level_prestasi ?: 'lokal';
            $linkDokumen = $prestasi->link_dokumen;

            foreach ($kodeDosens as $index => $kode) {
                if (empty($kode)) continue;
                $nama = $namaDosens[$index] ?? '';
                if (empty($nama)) {
                    $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                    $nama = $dosen ? $dosen->nama_dosen : '';
                }
                RekognisiDosen::firstOrCreate([
                    'kode_dosen' => $kode,
                    'prestasi_dosen_id' => $prestasi->id,
                ], [
                    'nama_dosen' => $nama,
                    'nama_rekognisi' => $namaRekognisi,
                    'tahun' => $tahun,
                    'ts_id' => $prestasi->ts_id,
                    'level' => $level,
                    'link_dokumen' => $linkDokumen,
                    'is_keanggotaan' => false,
                ]);
            }
        }
    }
}
