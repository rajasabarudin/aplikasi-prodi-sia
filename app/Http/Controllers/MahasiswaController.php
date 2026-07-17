<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        // Hitung total keseluruhan
        $totalMahasiswa = Mahasiswa::count();

        // Hitung statistik per kelas
        $kelasStats = Mahasiswa::groupBy('kelas')
            ->selectRaw('kelas, count(*) as count')
            ->orderBy('count', 'desc')
            ->get();

        // Hitung jumlah mahasiswa berdasarkan tingkat semester (2, 4, 6)
        $semesterCounts = [
            2 => 0,
            4 => 0,
            6 => 0
        ];

        $allMahasiswa = Mahasiswa::all();
        foreach ($allMahasiswa as $m) {
            $kelasName = $m->kelas;
            $parts = explode('.', $kelasName);
            if (count($parts) >= 2) {
                $semChar = substr($parts[1], 0, 1);
                if (in_array($semChar, ['2', '4', '6'])) {
                    $semesterCounts[intval($semChar)]++;
                }
            } else {
                if (str_contains($kelasName, '2')) {
                    $semesterCounts[2]++;
                } elseif (str_contains($kelasName, '4')) {
                    $semesterCounts[4]++;
                } elseif (str_contains($kelasName, '6')) {
                    $semesterCounts[6]++;
                }
            }
        }

        // Ambil data kolaborasi penelitian mahasiswa bersama dosen
        $penelitians = \App\Models\PenelitianDosen::whereNotNull('nim_mhs')
            ->where('nim_mhs', '!=', '')
            ->get();

        $collabMhs = [];
        foreach ($penelitians as $p) {
            $nims = array_map('trim', explode(',', $p->nim_mhs));
            $namas = array_map('trim', explode(',', $p->nama_mahasiswa));
            foreach ($nims as $idx => $nim) {
                if (empty($nim)) continue;
                $nama = $namas[$idx] ?? '';
                
                if (!isset($collabMhs[$nim])) {
                    $collabMhs[$nim] = [
                        'nim' => $nim,
                        'nama' => $nama,
                        'count' => 0,
                    ];
                }
                $collabMhs[$nim]['count']++;
            }
        }
        
        // Urutkan berdasarkan kolaborasi terbanyak
        uasort($collabMhs, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // Ambil data kolaborasi hibah penelitian mahasiswa bersama dosen
        $hibahs = \App\Models\HibahPenelitian::whereNotNull('nim_mhs')
            ->where('nim_mhs', '!=', '')
            ->get();

        $collabHibah = [];
        foreach ($hibahs as $h) {
            $nims = array_map('trim', explode(',', $h->nim_mhs));
            $namas = array_map('trim', explode(',', $h->nama_mahasiswa));
            $jenis = strtolower(trim($h->jenis_hibah));
            $biaya = floatval($h->biaya);
            
            foreach ($nims as $idx => $nim) {
                if (empty($nim)) continue;
                $nama = $namas[$idx] ?? '';
                
                if (!isset($collabHibah[$nim])) {
                    $collabHibah[$nim] = [
                        'nim' => $nim,
                        'nama' => $nama,
                        'total' => 0,
                        'internal' => 0,
                        'eksternal' => 0,
                        'total_dana' => 0,
                    ];
                }
                $collabHibah[$nim]['total']++;
                $collabHibah[$nim]['total_dana'] += $biaya;
                if ($jenis === 'internal') {
                    $collabHibah[$nim]['internal']++;
                } else {
                    $collabHibah[$nim]['eksternal']++;
                }
            }
        }
        
        // Urutkan berdasarkan kolaborasi hibah terbanyak
        uasort($collabHibah, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Ambil data kolaborasi PKM mahasiswa bersama dosen
        $pkms = \App\Models\PKMDosen::whereNotNull('nim_mhs')
            ->where('nim_mhs', '!=', '')
            ->get();

        $collabPkm = [];
        foreach ($pkms as $p) {
            $nims = array_map('trim', explode(',', $p->nim_mhs));
            $namas = array_map('trim', explode(',', $p->nama_mahasiswa));
            foreach ($nims as $idx => $nim) {
                if (empty($nim)) continue;
                $nama = $namas[$idx] ?? '';

                if (!isset($collabPkm[$nim])) {
                    $collabPkm[$nim] = [
                        'nim' => $nim,
                        'nama' => $nama,
                        'count' => 0,
                    ];
                }
                $collabPkm[$nim]['count']++;
            }
        }

        uasort($collabPkm, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        // Ambil data HKI mahasiswa yang berkolaborasi dengan dosen (memiliki kode dosen)
        $hkis = \App\Models\Hki::with('mahasiswa')
            ->whereNotNull('kode_dosen')
            ->where('kode_dosen', '!=', '')
            ->get();
        $totalHkiCount = $hkis->count();
        
        $collabHki = [];
        $collabDosenHki = [];
        $lecturerHkiSeen = [];
        foreach ($hkis as $h) {
            $nim = $h->nim;
            if (!empty($nim)) {
                $mhsId = $h->mahasiswa ? $h->mahasiswa->id : null;
                $mhsNama = $h->mahasiswa ? $h->mahasiswa->nama : 'Mahasiswa (NIM: ' . $nim . ')';
                
                if (!isset($collabHki[$nim])) {
                    $collabHki[$nim] = [
                        'id' => $mhsId,
                        'nim' => $nim,
                        'nama' => $mhsNama,
                        'count' => 0,
                    ];
                }
                $collabHki[$nim]['count']++;
            }

            // Hitung dosen terlibat HKI bersama mahasiswa
            $kodes = array_map('trim', explode(',', $h->kode_dosen));
            $namas = array_map('trim', explode(',', $h->nama_dosen));
            $noPermohonan = trim($h->no_permohonan);
            
            foreach ($kodes as $idx => $kode) {
                if (empty($kode)) continue;
                $nama = $namas[$idx] ?? $kode;
                
                // Jangan hitung nomor permohonan yang sama lebih dari sekali untuk dosen yang sama
                $seenKey = $kode . '_' . $noPermohonan;
                if (in_array($seenKey, $lecturerHkiSeen)) {
                    continue;
                }
                $lecturerHkiSeen[] = $seenKey;
                
                $dosenModel = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                $dosenId = $dosenModel ? $dosenModel->id : null;
                
                if (!isset($collabDosenHki[$kode])) {
                    $collabDosenHki[$kode] = [
                        'id' => $dosenId,
                        'kode' => $kode,
                        'nama' => $nama,
                        'count' => 0,
                    ];
                }
                $collabDosenHki[$kode]['count']++;
            }
        }

        // Urutkan berdasarkan HKI terbanyak
        uasort($collabHki, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        uasort($collabDosenHki, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $mhsPunyaHkiCount = count($collabHki);
        $dosenTerkaitHkiCount = count($collabDosenHki);

        // Kumpulkan NIM mahasiswa yang memiliki kolaborasi (Penelitian, Hibah, PKM, atau HKI) bersama dosen
        $collaboratedNims = array_unique(array_merge(
            array_keys($collabMhs),
            array_keys($collabHibah),
            array_keys($collabPkm),
            array_keys($collabHki)
        ));

        // Hitung statistik prestasi mahasiswa berdasarkan TS dan prestasi yang diraih
        $prestasiByTs = \App\Models\PrestasiMahasiswa::join('ts', 'prestasi_mahasiswas.ts_id', '=', 'ts.id')
            ->select('ts.tahun_sekarang as ts_name')
            ->selectRaw('count(*) as total')
            ->groupBy('ts.tahun_sekarang', 'prestasi_mahasiswas.ts_id')
            ->orderBy('prestasi_mahasiswas.ts_id', 'desc')
            ->pluck('total', 'ts_name')
            ->toArray();

        $prestasiByHasilRaw = \App\Models\PrestasiMahasiswa::select('prestasi_diraih')
            ->selectRaw('count(*) as total')
            ->groupBy('prestasi_diraih')
            ->pluck('total', 'prestasi_diraih')
            ->toArray();

        $allHasilOptions = ['Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1', 'Harapan 2', 'Partisipan'];
        $prestasiByHasil = [];
        foreach ($allHasilOptions as $option) {
            $prestasiByHasil[$option] = $prestasiByHasilRaw[$option] ?? 0;
        }

        $prestasiByBidangRaw = \App\Models\PrestasiMahasiswa::select('bidang_prestasi')
            ->selectRaw('count(*) as total')
            ->groupBy('bidang_prestasi')
            ->pluck('total', 'bidang_prestasi')
            ->toArray();

        $allBidangOptions = ['Akademik', 'Non Akademik', 'Akademik Non Lomba', 'Partisipan'];
        $prestasiByBidang = [];
        foreach ($allBidangOptions as $option) {
            $prestasiByBidang[$option] = $prestasiByBidangRaw[$option] ?? 0;
        }

        $totalPrestasiCount = \App\Models\PrestasiMahasiswa::count();

        // Hitung statistik prestasi mahasiswa
        $mhsBerprestasiCount = \App\Models\PrestasiMahasiswa::distinct('nim')->count('nim');
        $persenMhsPrestasi = $totalMahasiswa > 0 ? round(($mhsBerprestasiCount / $totalMahasiswa) * 100, 2) : 0;

        // Hitung statistik organisasi mahasiswa
        $mhsIkutOrganisasiCount = \App\Models\OrganisasiMahasiswa::distinct('nim')->count('nim');
        $persenMhsOrganisasi = $totalMahasiswa > 0 ? round(($mhsIkutOrganisasiCount / $totalMahasiswa) * 100, 2) : 0;

        $organisasiByTs = \App\Models\OrganisasiMahasiswa::join('ts', 'organisasi_mahasiswas.ts_id', '=', 'ts.id')
            ->select('ts.tahun_sekarang as ts_name')
            ->selectRaw('count(distinct organisasi_mahasiswas.nim) as total')
            ->groupBy('ts.tahun_sekarang', 'organisasi_mahasiswas.ts_id')
            ->orderBy('organisasi_mahasiswas.ts_id', 'desc')
            ->pluck('total', 'ts_name')
            ->toArray();

        $organisasiMhsList = \App\Models\OrganisasiMahasiswa::join('mahasiswas', 'organisasi_mahasiswas.nim', '=', 'mahasiswas.nim')
            ->select('mahasiswas.id as mhs_id', 'mahasiswas.nama', 'mahasiswas.nim', 'organisasi_mahasiswas.nama_organisasi', 'organisasi_mahasiswas.jabatan')
            ->orderBy('organisasi_mahasiswas.created_at', 'desc')
            ->get()
            ->toArray();

        $prestasiList = \App\Models\PrestasiMahasiswa::with('mahasiswa')->orderBy('created_at', 'desc')->get();

        $perPage = in_array($request->get('per_page'), [10, 20, 50, 100, 1000]) ? intval($request->get('per_page')) : 20;

        if ($request->get('print') === 'all') {
            $mahasiswas = $query->get();
        } else {
            $mahasiswas = $query->paginate($perPage)->withQueryString();
        }
        $kelasList = Kelas::all();
        
        return view('mahasiswa.index', compact(
            'mahasiswas', 'kelasList', 'totalMahasiswa', 'kelasStats', 'collabMhs', 'collabHibah',
            'collabPkm',
            'semesterCounts', 'totalHkiCount', 'collabHki', 'mhsPunyaHkiCount', 'collaboratedNims', 
            'collabDosenHki', 'dosenTerkaitHkiCount', 'prestasiByTs', 'prestasiByHasil', 'prestasiByBidang', 
            'totalPrestasiCount', 'mhsIkutOrganisasiCount', 'persenMhsOrganisasi', 'organisasiByTs', 'organisasiMhsList',
            'mhsBerprestasiCount', 'persenMhsPrestasi', 'prestasiList'
        ));
    }

    public function create()
    {
        $kelasList = Kelas::all();
        return view('mahasiswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama' => 'required',
            'kelas' => 'required',
        ]);

        Mahasiswa::create($request->all());

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['hki', 'prestasi.ts', 'organisasi.ts']);
        $dosenList = Dosen::orderBy('nama_dosen', 'asc')->get();
        $tsList = \App\Models\Ts::orderBy('tahun_sekarang', 'desc')->get();
        $matakuliahList = \App\Models\Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        $classmates = $mahasiswa->kelas ? Mahasiswa::where('kelas', $mahasiswa->kelas)->where('nim', '!=', $mahasiswa->nim)->orderBy('nama', 'asc')->get() : collect();
        
        $tugasList = \App\Models\TugasMahasiswa::with('matakuliah')
            ->where('nim', $mahasiswa->nim)
            ->orWhere('anggota_kelompok', 'like', "%{$mahasiswa->nim}%")
            ->get();

        $capstoneList = \App\Models\CapstoneMahasiswa::with('matakuliah')
            ->where('nim', $mahasiswa->nim)
            ->orWhere('anggota_kelompok', 'like', "%{$mahasiswa->nim}%")
            ->get();

        $sertifikasiList = \App\Models\SertifikasiMahasiswa::where('nim', $mahasiswa->nim)->get();
        $ipkList = \App\Models\IpkMahasiswa::with('ts')
            ->where('nim', $mahasiswa->nim)
            ->orderBy('id', 'desc')
            ->get();

        return view('mahasiswa.show', compact('mahasiswa', 'dosenList', 'tsList', 'matakuliahList', 'classmates', 'tugasList', 'capstoneList', 'sertifikasiList', 'ipkList'));
    }

    public function cetakProfil(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['hki', 'prestasi.ts', 'organisasi.ts']);
        
        $tugasList = \App\Models\TugasMahasiswa::with('matakuliah')
            ->where('nim', $mahasiswa->nim)
            ->orWhere('anggota_kelompok', 'like', "%{$mahasiswa->nim}%")
            ->get();

        $capstoneList = \App\Models\CapstoneMahasiswa::with('matakuliah')
            ->where('nim', $mahasiswa->nim)
            ->orWhere('anggota_kelompok', 'like', "%{$mahasiswa->nim}%")
            ->get();

        $sertifikasiList = \App\Models\SertifikasiMahasiswa::where('nim', $mahasiswa->nim)->get();
        
        $ipkList = \App\Models\IpkMahasiswa::with('ts')
            ->where('nim', $mahasiswa->nim)
            ->orderBy('id', 'desc')
            ->get();
            
        // Kegiatan yang diikuti
        $kegiatans = \App\Models\PesertaKegiatan::with('kegiatan')
            ->where('identifier', $mahasiswa->nim)
            ->orderBy('created_at', 'desc')
            ->get();

        $title = "Profil & Portofolio Mahasiswa - " . $mahasiswa->nama;

        return view('mahasiswa.cetak_profil', compact('mahasiswa', 'tugasList', 'capstoneList', 'sertifikasiList', 'ipkList', 'kegiatans', 'title'));
    }

    public function card(Mahasiswa $mahasiswa)
    {
        $qrUrl = route('mahasiswa.show', $mahasiswa->id);
        return view('mahasiswa.card', compact('mahasiswa', 'qrUrl'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $kelasList = Kelas::all();
        return view('mahasiswa.edit', compact('mahasiswa', 'kelasList'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required',
            'kelas' => 'required',
        ]);

        $mahasiswa->update($request->all());

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $inserted = 0;
        $skipped = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $nim = trim($row[0] ?? '');
            $nama = trim($row[1] ?? '');
            $kelas = trim($row[2] ?? '');

            if (empty($nim) || empty($nama) || empty($kelas)) {
                $skipped++;
                continue;
            }

            if (Mahasiswa::where('nim', $nim)->exists()) {
                $skipped++;
                continue;
            }

            Mahasiswa::create([
                'nim' => $nim,
                'nama' => $nama,
                'kelas' => $kelas,
            ]);

            $inserted++;
        }

        return redirect()->route('mahasiswa.index')
            ->with('success', "Import selesai. $inserted data berhasil ditambahkan, $skipped dilewati.");
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Kelas');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);

        $sheet->setCellValue('A3', 'Contoh:');
        $sheet->setCellValue('A4', '1234567890');
        $sheet->setCellValue('B4', 'Nama Mahasiswa');
        $sheet->setCellValue('C4', 'TI-1A');
        $sheet->getStyle('A3:C3')->getFont()->setItalic(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-mahasiswa.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
