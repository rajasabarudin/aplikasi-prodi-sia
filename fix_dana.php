<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');

$old = <<<'EOD'
            case "C5_P2_Dana":
                $title = "Rekapitulasi Keuangan & Pendanaan Prodi (Pelaksanaan C.5)";
                $data = class_exists("\App\Models\KeuanganProdi") ? \App\Models\KeuanganProdi::all() : [];
                break;
EOD;

$new = <<<'EOD'
            case "C5_P2_Dana":
                $title = "Rekapitulasi Keuangan & Pendanaan Prodi (Termasuk Hibah) (Pelaksanaan C.5)";
                
                // Ambil tahun akademik unik dari ts
                $tsList = \Illuminate\Support\Facades\DB::table('ts')
                    ->orderByRaw('SUBSTR(tahun_sekarang, -9) DESC')
                    ->orderBy('tahun_sekarang', 'ASC')
                    ->get();
                
                $data = [];
                foreach ($tsList as $ts) {
                    $tahun = $ts->tahun_sekarang;
                    
                    // Dana Internal dari keuangan_prodis
                    $keuangan = \Illuminate\Support\Facades\DB::table('keuangan_prodis')->where('tahun_akademik', $tahun)->first();
                    $danaPend = $keuangan ? $keuangan->dana_pendidikan : 0;
                    $danaPenelitianInt = $keuangan ? $keuangan->dana_penelitian : 0;
                    $danaPkmInt = $keuangan ? $keuangan->dana_pkm : 0;
                    $danaInv = $keuangan ? $keuangan->dana_investasi : 0;
                    
                    // Dana Eksternal / Hibah berdasarkan ts_id
                    $danaHibah = \Illuminate\Support\Facades\DB::table('hibah_penelitians')->where('ts_id', $ts->id)->sum('biaya');
                    $danaPenelitianDosen = \Illuminate\Support\Facades\DB::table('penelitian_dosens')->where('ts_id', $ts->id)->sum('biaya');
                    $danaPkmDosen = \Illuminate\Support\Facades\DB::table('pkm_dosens')->where('ts_id', $ts->id)->sum('biaya');
                    
                    $totalPenelitian = $danaPenelitianInt + $danaHibah + $danaPenelitianDosen;
                    $totalPkm = $danaPkmInt + $danaPkmDosen;
                    $grandTotal = $danaPend + $totalPenelitian + $totalPkm + $danaInv;
                    
                    if ($grandTotal > 0 || $keuangan) {
                        $data[] = [
                            "Tahun_Akademik" => $tahun,
                            "Dana_Pendidikan" => "Rp " . number_format($danaPend, 0, ',', '.'),
                            "Dana_Penelitian_dan_Hibah" => "Rp " . number_format($totalPenelitian, 0, ',', '.'),
                            "Dana_Pengabdian_Masyarakat" => "Rp " . number_format($totalPkm, 0, ',', '.'),
                            "Dana_Investasi" => "Rp " . number_format($danaInv, 0, ',', '.'),
                            "Total_Dana" => "Rp " . number_format($grandTotal, 0, ',', '.')
                        ];
                    }
                }
                
                // Fallback jika kosong
                if (empty($data)) {
                    $data[] = [
                        "Tahun_Akademik" => "-",
                        "Dana_Pendidikan" => "Rp 0",
                        "Dana_Penelitian_dan_Hibah" => "Rp 0",
                        "Dana_Pengabdian_Masyarakat" => "Rp 0",
                        "Dana_Investasi" => "Rp 0",
                        "Total_Dana" => "Rp 0"
                    ];
                }
                break;
EOD;

$content = str_replace($old, $new, $content);
file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Replaced C5_P2_Dana logic.\n";
