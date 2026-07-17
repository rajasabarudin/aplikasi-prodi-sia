<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ObeStakeholderSurvey;
use App\Models\ObeNilaiCpmk;
use App\Models\Rps;
use App\Models\Mahasiswa;
use App\Models\Cpmk;
use App\Models\Kelas;
use App\Models\ObeCqiLog;

class ObeDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Stakeholder Surveys
        ObeStakeholderSurvey::truncate();
        
        $aspects = [
            'Etika & Moral',
            'Keahlian Bidang Ilmu (Kompetensi Utama)',
            'Kemampuan Bahasa Asing',
            'Penggunaan Teknologi Informasi',
            'Kemampuan Komunikasi',
            'Kerjasama Tim (Teamwork)',
            'Pengembangan Diri & Kemandirian'
        ];

        foreach ([2024, 2025] as $year) {
            foreach ($aspects as $aspect) {
                // Generate random percentages that sum to 100
                $sb = rand(40, 60);
                $b = rand(30, 45);
                $c = 100 - ($sb + $b) - 2;
                $k = 2;

                ObeStakeholderSurvey::create([
                    'tahun' => $year,
                    'aspek_penilaian' => $aspect,
                    'nilai_sangat_baik' => $sb,
                    'nilai_baik' => $b,
                    'nilai_cukup' => $c,
                    'nilai_kurang' => $k,
                    'responden_count' => rand(25, 40)
                ]);
            }
        }

        // 2. Seed Nilai CPMK for all students across active RPS
        ObeNilaiCpmk::truncate();
        
        $rpsList = Rps::with('matakuliah')->get();
        $mahasiswas = Mahasiswa::all();
        
        foreach ($rpsList as $rps) {
            $cpmks = Cpmk::where('kode_matakuliah', $rps->kode_matakuliah)->get();

            if ($cpmks->isEmpty()) {
                // fallback to general CPMK query
                $cpmks = Cpmk::take(3)->get();
            }

            foreach ($mahasiswas as $mhs) {
                // Assign student to their actual kelas_id
                $kelasId = $mhs->kelas_id ?: Kelas::first()->id;
                
                foreach ($cpmks as $cpmk) {
                    // Generate scores (some high, some average, some failing)
                    $nilai = rand(60, 100);
                    // 5% chance of failing score to show CQI trigger
                    if (rand(1, 20) === 1) {
                        $nilai = rand(40, 59);
                    }

                    ObeNilaiCpmk::create([
                        'rps_id' => $rps->id,
                        'kelas_id' => $kelasId,
                        'mahasiswa_id' => $mhs->id,
                        'cpmk_id' => $cpmk->id,
                        'nilai' => $nilai
                    ]);
                }
            }
        }

        // 3. Seed CQI Logs
        ObeCqiLog::truncate();
        
        foreach ($rpsList as $rps) {
            ObeCqiLog::create([
                'rps_id' => $rps->id,
                'semester' => '2025/2026 Ganjil',
                'cpl_id' => 1,
                'analisis_masalah' => 'Beberapa mahasiswa mengalami kesulitan dalam memahami praktikum terintegrasi karena kurangnya waktu latihan mandiri di lab.',
                'rencana_perbaikan' => 'Menyediakan modul video tutorial tambahan di e-learning dan memperpanjang jam buka lab komputer untuk latihan mandiri.',
                'status' => 'implemented'
            ]);
        }
    }
}
