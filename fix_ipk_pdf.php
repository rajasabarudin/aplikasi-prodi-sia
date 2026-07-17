<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');
$old = <<<'EOD'
            case "C2_P3_Ipk":
                $title = "Evaluasi Rata-rata IPK Mahasiswa (Evaluasi C.2)";
                $data = \App\Models\IpkMahasiswa::all();
                break;
EOD;
$new = <<<'EOD'
            case "C2_P3_Ipk":
                $title = "Evaluasi Rata-rata IPK Mahasiswa per Tahun Akademik (Evaluasi C.2)";
                $data = \Illuminate\Support\Facades\DB::table('ipk_mahasiswa')
                    ->join('ts', 'ts.id', '=', 'ipk_mahasiswa.ts_id')
                    ->select(
                        'ts.tahun_sekarang as Tahun_Akademik',
                        \Illuminate\Support\Facades\DB::raw('COUNT(ipk_mahasiswa.nim) as Jumlah_Mahasiswa'),
                        \Illuminate\Support\Facades\DB::raw('ROUND(AVG(ipk_mahasiswa.ipk), 2) as Rata_Rata_IPK')
                    )
                    ->groupBy('ts.tahun_sekarang')
                    ->orderBy('ts.tahun_sekarang', 'desc')
                    ->get();
                break;
EOD;

$content = str_replace($old, $new, $content);
file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Replaced C2_P3_Ipk case block.";
