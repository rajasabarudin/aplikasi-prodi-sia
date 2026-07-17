<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');
$old = <<<'EOD'
            case "C2_P3_Ipk":
                $title = "Evaluasi Indeks Prestasi Kumulatif (IPK) Mahasiswa (Evaluasi C.2)";
                $data = \Illuminate\Support\Facades\DB::table('ipk_mahasiswa')
                    ->join('ts', 'ts.id', '=', 'ipk_mahasiswa.ts_id')
                    ->select(
                        'ts.tahun_sekarang as Tahun_Akademik',
                        'ipk_mahasiswa.nim as NIM',
                        'ipk_mahasiswa.nama as Nama_Lengkap',
                        'ipk_mahasiswa.ipk as IPK'
                    )
                    ->orderBy('ts.tahun_sekarang', 'desc')
                    ->orderBy('ipk_mahasiswa.ipk', 'desc')
                    ->get();
                break;
EOD;
$new = <<<'EOD'
            case "C2_P3_Ipk":
                $title = "Evaluasi Indeks Prestasi Kumulatif (IPK) Mahasiswa (Evaluasi C.2)";
                $data = \Illuminate\Support\Facades\DB::table('ipk_mahasiswa')
                    ->join('ts', 'ts.id', '=', 'ipk_mahasiswa.ts_id')
                    ->select(
                        'ts.tahun_sekarang as Tahun_Akademik',
                        'ipk_mahasiswa.nim as NIM',
                        'ipk_mahasiswa.nama as Nama_Lengkap',
                        'ipk_mahasiswa.ipk as IPK'
                    )
                    ->orderByRaw('SUBSTR(ts.tahun_sekarang, -9) DESC')
                    ->orderBy('ts.tahun_sekarang', 'ASC')
                    ->orderBy('ipk_mahasiswa.ipk', 'desc')
                    ->get();
                break;
EOD;

$content = str_replace($old, $new, $content);
file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Replaced IPK order by logic.";
