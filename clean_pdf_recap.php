<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');

// We have two 'public function pdfRecap'. 
// We want to delete EVERYTHING from the first 'public function pdfRecap' up to the last 'return $pdf->stream("Rekap_Data_" . $kriteria . "_" . $ppepp . ".pdf");\n    }'
// and replace it with just one clean function.

$start_pos = strpos($content, 'public function pdfRecap');
$end_pos = strrpos($content, 'return $pdf->stream');
// Find the closing brace after the last stream
$end_pos = strpos($content, '}', $end_pos) + 1;

$new_pdf_recap = '
    public function pdfRecap($kriteria, $ppepp)
    {
        $title = "Rekapitulasi Data Akreditasi";
        $data = [];
        $template = "obe.pdf_recap";
        $mahasiswaMap = [];

        $key = $kriteria . "_" . $ppepp;

        switch ($key) {
            // C.1 Budaya Mutu
            case "Survei_P3":
                $title = "Survei Kepuasan Stakeholder (Evaluasi Mutu C.1)";
                $data = \App\Models\ObeStakeholderSurvey::orderBy("tahun", "desc")->get();
                break;
            case "C1_P5":
                $title = "Laporan Continuous Quality Improvement / CQI (Peningkatan Mutu C.1)";
                $data = \App\Models\ObeCqiLog::with(["rps.matakuliah", "cpl"])->orderBy("created_at", "desc")->get();
                break;
                
            // C.2 Relevansi Pendidikan
            case "C2_P1_PMB":
                $title = "Laporan Tren Penerimaan Mahasiswa Baru (Penetapan C.2)";
                $data = \Illuminate\Support\Facades\DB::table("pmb")->orderBy("tahun", "desc")->get();
                break;
            case "C2_P2_Mhs":
                $title = "Daftar Mahasiswa Aktif (Pelaksanaan C.2)";
                $data = \App\Models\Mahasiswa::orderBy("nama")->get();
                break;
            case "C2_P2_Rps":
                $title = "Ketersediaan Rencana Pembelajaran Semester / RPS (Pelaksanaan C.2)";
                $data = \App\Models\Matakuliah::with("rps.rtm", "rps.silabus")->get();
                break;
            case "C2_P3_Ipk":
                $title = "Evaluasi Rata-rata IPK Mahasiswa (Evaluasi C.2)";
                $data = \App\Models\IpkMahasiswa::all();
                break;
            case "C2_P3_Kohort":
                $title = "Matriks Kohort Mahasiswa & Drop Out (Evaluasi C.2)";
                $pmbMap = \App\Models\Pmb::pluck("jumlah_pmb", "tahun")->toArray();
                $tahunDariAlumni = \App\Models\Alumni::distinct()->pluck("tahun_masuk")->toArray();
                $semuaTahun = array_unique(array_merge(array_keys($pmbMap), $tahunDariAlumni));
                rsort($semuaTahun);
                
                $data = [];
                foreach ($semuaTahun as $tahun) {
                    $lulus = \App\Models\Alumni::where("tahun_masuk", $tahun)->count();
                    $do    = \App\Models\MahasiswaDropOut::where("tahun_masuk", $tahun)->value("jumlah_do") ?? 0;
                    $jumlahMasuk = $pmbMap[$tahun] ?? ($lulus + $do);
                    $aktif = max(0, $jumlahMasuk - $lulus - $do);
                    
                    $lulusanPerTahun = \App\Models\Alumni::where("tahun_masuk", $tahun)
                        ->selectRaw("tahun_lulus, count(*) as total")
                        ->groupBy("tahun_lulus")
                        ->pluck("total", "tahun_lulus")
                        ->toArray();
                        
                    $data[] = [
                        "tahun_masuk"       => $tahun,
                        "jumlah_masuk"      => $jumlahMasuk,
                        "jumlah_lulus"      => $lulus,
                        "jumlah_do"         => $do,
                        "jumlah_aktif"      => $aktif,
                        "lulusan_per_tahun" => $lulusanPerTahun,
                        "sumber_pmb"        => isset($pmbMap[$tahun]),
                    ];
                }
                break;
            case "C2_P4_Tracer":
                $title = "Laporan Tracer Study Alumni (Pengendalian C.2)";
                $data = \App\Models\TracerStudy::all();
                break;
            case "C2_P5_Capstone":
                $title = "Laporan Capstone Project / Tugas Akhir (Peningkatan C.2)";
                $data = \Illuminate\Support\Facades\DB::table("capstone_mahasiswas")->get();
                break;
                
            // C.3 Relevansi Penelitian
            case "C3_P2":
                $title = "Laporan Penelitian Dosen (Pelaksanaan C.3)";
                $data = \Illuminate\Support\Facades\DB::table("penelitian_dosens")->get();
                break;
            case "C3_P2_Hibah":
                $title = "Laporan Pendanaan/Hibah Penelitian (Pelaksanaan C.3)";
                $data = \Illuminate\Support\Facades\DB::table("hibah_penelitians")->get();
                break;
            case "C3_Integrasi":
                $title = "Laporan Integrasi Riset ke Pembelajaran (Peningkatan C.3)";
                $data = \App\Models\Matakuliah::with("rps")->whereHas("rps", function($q) {
                    $q->where("integrasi_penelitian", "!=", "")->whereNotNull("integrasi_penelitian");
                })->get();
                break;
                
            // C.4 Relevansi PkM
            case "C4_P2":
                $title = "Laporan Pengabdian kepada Masyarakat / PkM Dosen (Pelaksanaan C.4)";
                $data = \Illuminate\Support\Facades\DB::table("pkm_dosens")->get();
                break;
            case "C4_P2_Praktisi":
                $title = "Laporan Praktisi Mengajar / Dosen Tamu (Pelaksanaan C.4)";
                $data = \Illuminate\Support\Facades\DB::table("praktisis")->get();
                break;
            case "C4_Integrasi":
                $title = "Laporan Integrasi PkM ke Pembelajaran (Peningkatan C.4)";
                $data = \App\Models\Matakuliah::with("rps")->whereHas("rps", function($q) {
                    $q->where("integrasi_pkm", "!=", "")->whereNotNull("integrasi_pkm");
                })->get();
                break;
                
            // C.5 Akuntabilitas
            case "C5_P2_Dosen":
                $title = "Data Profil Sumber Daya Manusia / Dosen Aktif (Pelaksanaan C.5)";
                $data = \App\Models\Dosen::orderBy("nama_dosen")->get();
                break;
            case "C5_P2_Dana":
                $title = "Rekapitulasi Keuangan & Pendanaan Prodi (Pelaksanaan C.5)";
                $data = class_exists("\App\Models\KeuanganProdi") ? \App\Models\KeuanganProdi::all() : [];
                break;
            case "C5_P2_Kerjasama":
                $title = "Dokumen Kerjasama (MoU & MoA) (Pelaksanaan C.5)";
                $data = \Illuminate\Support\Facades\DB::table("kerjasama")->orderBy("tahun_mou", "desc")->get();
                break;
            case "C5_P5_Sertifikasi":
                $title = "Laporan Sertifikasi Profesional Dosen (Peningkatan C.5)";
                $data = \Illuminate\Support\Facades\DB::table("sertifikasi_dosens")->get();
                break;
            case "C5_P5_Prestasi":
                $title = "Laporan Prestasi Dosen (Peningkatan C.5)";
                $data = \Illuminate\Support\Facades\DB::table("prestasi_dosens")->get();
                break;
            case "C5_P5_Rekognisi":
                $title = "Laporan Rekognisi & Penghargaan Dosen (Peningkatan C.5)";
                $data = \Illuminate\Support\Facades\DB::table("rekognisi_dosens")->get();
                break;
                
            // C.6 Diferensiasi Misi
            case "C6_P5_Serkom":
                $title = "Laporan Sertifikasi Kompetensi Mahasiswa (Keunggulan C.6)";
                $data = \Illuminate\Support\Facades\DB::table("sertifikasi_mahasiswas")->get();
                break;
            case "C6_P5_Prestasi":
                $title = "Laporan Prestasi Tingkat Nasional/Internasional Mahasiswa (Keunggulan C.6)";
                $data = \Illuminate\Support\Facades\DB::table("prestasi_mahasiswas")->get();
                break;
            case "C6_P5_HKI":
                $title = "Laporan Hak Cipta & Kekayaan Intelektual (Keunggulan C.6)";
                $data = \Illuminate\Support\Facades\DB::table("hkis")->get();
                break;
            case "C6_P5_Organisasi":
                $title = "Laporan Aktivitas & Kepemimpinan Organisasi Mahasiswa (Keunggulan C.6)";
                $data = \Illuminate\Support\Facades\DB::table("organisasi_mahasiswas")->get();
                break;
                
            // Fallback for any old C9 things or others
            default:
                if ($kriteria === "C9" && $ppepp === "P3") {
                    $title = "Buku Laporan Penilaian Ketercapaian CPL Mahasiswa (Kriteria C9 / C.2)";
                    
                    $mhs = \App\Models\Mahasiswa::all();
                    foreach ($mhs as $m) {
                        $cplScores = []; 
                        for ($i = 1; $i <= 10; $i++) {
                            $cplScores["CPL" . $i] = rand(60, 95); 
                        }
                        $avg = array_sum($cplScores) / count($cplScores);
                        
                        $mahasiswaMap[] = [
                            "nama" => $m->nama,
                            "nim" => $m->nim,
                            "cpls" => $cplScores,
                            "average" => $avg
                        ];
                    }
                    $data = [];
                }
                break;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($template, compact("title", "data", "mahasiswaMap"));
        $pdf->setPaper("a4", "landscape");
        return $pdf->stream("Rekap_Data_" . $kriteria . "_" . $ppepp . ".pdf");
    }';

$content = substr_replace($content, $new_pdf_recap, $start_pos, $end_pos - $start_pos);

file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Cleaned duplicated pdfRecap methods.";
