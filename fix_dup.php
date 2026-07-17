<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');

$parts = explode('public function pdfRecap', $content);

// $parts[0] is everything before the first pdfRecap
$top = $parts[0];

// There should be two pdfRecaps if it was duplicated.
// The very last pdfRecap is in $parts[2] (if there are two), or we can just find the end of the second one.
// Let's just look at the last part, which contains the second pdfRecap and whatever comes after it.
// Wait, the duplicate is `}public function pdfRecap`. So it split exactly there!
// $parts[0] ends with `    `
// $parts[1] is `($kriteria, $ppepp)\n    {\n        $title = "Rekapitulasi Data Akreditasi";...` up to `    }`
// $parts[2] is `($kriteria, $ppepp)\n    {\n        $title = "Rekapitulasi Data Akreditasi";...` up to EOF

// If there are 3 parts, we know it's duplicated.
if (count($parts) >= 3) {
    // We want to keep $parts[0] (before any pdfRecap).
    // And we want to keep whatever is after the LAST pdfRecap's closing brace.
    // Let's find the closing brace of $parts[2].
    // We can find `return $pdf->stream("Rekap_Data_" . $kriteria . "_" . $ppepp . ".pdf");`
    // and then the next `}`
    
    $last_part = $parts[count($parts) - 1];
    $end_of_func = strpos($last_part, 'return $pdf->stream');
    $end_of_func = strpos($last_part, '}', $end_of_func) + 1;
    
    $bottom = substr($last_part, $end_of_func);
    
    // Now we reconstruct the clean function
    $new_func = '
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
    
    $final = rtrim($top, "}\n\r\t ") . "\n" . $new_func . "\n" . ltrim($bottom, "\n\r\t ");
    
    // Check if `class ObePortalController` still ends with `}`
    // It should, if $bottom didn't eat it. If $bottom is empty, we must add `}`
    if (trim($bottom) == '') {
        $final .= "\n}\n";
    }
    
    file_put_contents('app/Http/Controllers/ObePortalController.php', $final);
    echo "Successfully deduplicated and fixed.";
} else {
    echo "No duplication found or error parsing.";
}
