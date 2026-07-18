<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Ts;
use App\Models\PKMDosen;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PKMDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = PKMDosen::with('ts')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('tema_pkm', 'like', "%{$search}%")
                  ->orWhere('mitra', 'like', "%{$search}%")
                  ->orWhere('nim_mhs', 'like', "%{$search}%")
                  ->orWhere('nama_mahasiswa', 'like', "%{$search}%");
            });
        }

        $perPage = in_array($request->get('per_page'), [10, 50, 100, 200]) ? intval($request->get('per_page')) : 10;

        if ($request->get('print') === 'all') {
            $pkm = $query->get();
        } else {
            $pkm = $query->paginate($perPage)->withQueryString();
        }

        $totalPkm = PKMDosen::count();
        $pkmDenganMhs = PKMDosen::whereNotNull('nim_mhs')->count();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();

        // Statistik
        $jenisPkmCounts = [
            'Mitra Non Produktif' => PKMDosen::where('jenis_pkm', 'Mitra Non Produktif')->count(),
            'Mitra Produktif' => PKMDosen::where('jenis_pkm', 'Mitra Produktif')->count(),
        ];

        $tsPkmCounts = Ts::orderBy('tahun_sekarang')
            ->get()
            ->mapWithKeys(function ($ts) {
                $count = \App\Models\PKMDosen::where('ts_id', $ts->id)->count();
                $name = $ts->tahun_sekarang . ' - ' . $ts->semester;
                return [$name => $count];
            })
            ->toArray();
            
        $labelTsPkmCounts = \App\Models\PKMDosen::with('ts')->get()->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map->count()
            ->sortDesc()
            ->toArray();

        // Kolaborasi stats
        $allDosens = PKMDosen::pluck('kode_dosen');
        $allMhs = PKMDosen::whereNotNull('nim_mhs')->pluck('nim_mhs');
        $totalDosenTerlibat = $allDosens->flatMap(function ($item) {
            return array_map('trim', explode(',', $item));
        })->unique()->filter()->count();
        $totalMhsTerlibat = $allMhs->flatMap(function ($item) {
            return array_map('trim', explode(',', $item));
        })->unique()->filter()->count();
        $totalPkmDosenSaja = $totalPkm - $pkmDenganMhs;

        return view('pkm_dosen.index', compact(
            'pkm', 'totalPkm', 'pkmDenganMhs', 'tsList', 'dosens', 'mahasiswas',
            'jenisPkmCounts', 'tsPkmCounts', 'labelTsPkmCounts', 'perPage', 'request',
            'totalDosenTerlibat', 'totalMhsTerlibat', 'totalPkmDosenSaja'
        ));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();
        return view('pkm_dosen.create', compact('dosens', 'tsList', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string|max:20',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string|max:100',
            'tema_pkm' => 'required|string|max:200',
            'mitra' => 'required|string|max:200',
            'jenis_pkm' => 'required|in:Mitra Non Produktif,Mitra Produktif',
            'sumber_iptek' => 'required|string|max:100',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string|max:20',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string|max:100',
            'ts_id' => 'required|exists:ts,id',
            'link_dokumen' => 'nullable|url|max:255',
            'link_publikasi' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));

        $nimArray = array_filter($request->input('nim_mhs', []));
        $mhsNamaArray = array_filter($request->input('nama_mahasiswa', []));
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $mhsNamaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        PKMDosen::create($data);

        return redirect()->route('pkm-dosen.index')
            ->with('success', 'Data PKM Dosen berhasil ditambahkan.');
    }

    public function edit(PKMDosen $pkmDosen)
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();
        return view('pkm_dosen.edit', compact('pkmDosen', 'dosens', 'tsList', 'mahasiswas'));
    }

    public function update(Request $request, PKMDosen $pkmDosen)
    {
        $request->validate([
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string|max:20',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string|max:100',
            'tema_pkm' => 'required|string|max:200',
            'mitra' => 'required|string|max:200',
            'jenis_pkm' => 'required|in:Mitra Non Produktif,Mitra Produktif',
            'sumber_iptek' => 'required|string|max:100',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string|max:20',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string|max:100',
            'ts_id' => 'required|exists:ts,id',
            'link_dokumen' => 'nullable|url|max:255',
            'link_publikasi' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));

        $nimArray = array_filter($request->input('nim_mhs', []));
        $mhsNamaArray = array_filter($request->input('nama_mahasiswa', []));
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $mhsNamaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        $pkmDosen->update($data);

        return redirect()->route('pkm-dosen.index')
            ->with('success', 'Data PKM Dosen berhasil diperbarui.');
    }

    public function destroy(PKMDosen $pkmDosen)
    {
        $pkmDosen->delete();
        return redirect()->route('pkm-dosen.index')
            ->with('success', 'Data PKM Dosen berhasil dihapus.');
    }

    public function getDosen($kode)
    {
        $dosen = Dosen::where('kode_dosen', $kode)->first();
        if ($dosen) {
            return response()->json(['nama_dosen' => $dosen->nama_dosen]);
        }
        return response()->json(null, 404);
    }

    public function getMahasiswa($nim)
    {
        $mhs = Mahasiswa::where('nim', $nim)->first();
        if ($mhs) {
            return response()->json(['nama' => $mhs->nama]);
        }
        return response()->json(null, 404);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes' => 'File harus berupa xlsx, xls, atau csv.',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $dosens = Dosen::all();
            $mahasiswas = Mahasiswa::all();
            $tsList = Ts::all();

            $inserted = 0;
            $skipped = 0;

            foreach ($rows as $index => $row) {
                if ($index === 0) continue;

                $kodeDosen = trim($row[0] ?? '');
                $namaDosen = trim($row[1] ?? '');
                $temaPkm = trim($row[2] ?? '');
                $mitra = trim($row[3] ?? '');
                $jenisPkm = trim($row[4] ?? '');
                $sumberIptek = trim($row[5] ?? '');
                $nimMhs = trim($row[6] ?? '');
                $namaMhs = trim($row[7] ?? '');
                $tsTahun = trim($row[8] ?? '');
                $linkDokumen = trim($row[9] ?? '');
                $linkPublikasi = trim($row[10] ?? '');

                if (empty($kodeDosen) || empty($temaPkm) || empty($mitra) || empty($jenisPkm) || empty($tsTahun)) {
                    $skipped++;
                    continue;
                }

                // Validasi jenis PKM
                if (!in_array($jenisPkm, ['Mitra Non Produktif', 'Mitra Produktif'])) {
                    $skipped++;
                    continue;
                }

                // Cari dosen berdasarkan kode
                $dosen = null;
                foreach ($dosens as $d) {
                    if (strtolower(trim($d->kode_dosen)) === strtolower($kodeDosen)) {
                        $dosen = $d;
                        break;
                    }
                }

                if (!$dosen) {
                    // Coba cari berdasarkan NIP
                    foreach ($dosens as $d) {
                        if ($d->nip && trim($d->nip) === $kodeDosen) {
                            $dosen = $d;
                            break;
                        }
                    }
                }

                if (!$dosen) {
                    $skipped++;
                    continue;
                }

                // Cari TS
                $ts = null;
                foreach ($tsList as $t) {
                    if (trim((string)$t->tahun_sekarang) === $tsTahun) {
                        $ts = $t;
                        break;
                    }
                }

                if (!$ts) {
                    $skipped++;
                    continue;
                }

                // Validasi mahasiswa jika NIM diisi
                $resolvedNim = null;
                $resolvedNamaMhs = null;
                if (!empty($nimMhs)) {
                    foreach ($mahasiswas as $m) {
                        if (trim($m->nim) === $nimMhs) {
                            $resolvedNim = $m->nim;
                            $resolvedNamaMhs = $namaMhs ?: $m->nama;
                            break;
                        }
                    }
                    if (!$resolvedNim) {
                        $skipped++;
                        continue;
                    }
                }

                $namaDosenFinal = $namaDosen ?: $dosen->nama_dosen;

                PKMDosen::create([
                    'kode_dosen' => $dosen->kode_dosen,
                    'nama_dosen' => $namaDosenFinal,
                    'tema_pkm' => $temaPkm,
                    'mitra' => $mitra,
                    'jenis_pkm' => $jenisPkm,
                    'sumber_iptek' => $sumberIptek ?: '',
                    'nim_mhs' => $resolvedNim,
                    'nama_mahasiswa' => $resolvedNamaMhs,
                    'ts_id' => $ts->id,
                    'link_dokumen' => $linkDokumen ?: '',
                    'link_publikasi' => $linkPublikasi ?: '',
                ]);

                $inserted++;
            }

            return redirect()->route('pkm-dosen.index')
                ->with('success', "Import selesai. $inserted data berhasil ditambahkan, $skipped dilewati.");
        } catch (\Exception $e) {
            return redirect()->route('pkm-dosen.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['Kode Dosen', 'Nama Dosen', 'Tema PKM', 'Mitra', 'Jenis PKM', 'Sumber IPTEK', 'NIM Mhs', 'Nama Mhs', 'TA', 'Link Dokumen', 'Link Publikasi'];
        $col = 'A';
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(35);
        $sheet->getColumnDimension('K')->setWidth(35);

        $sheet->setCellValue('A3', 'Contoh:');
        $sheet->setCellValue('A4', 'AEE');
        $sheet->setCellValue('B4', 'Ade Hendini');
        $sheet->setCellValue('C4', 'Pelatihan Digital Marketing');
        $sheet->setCellValue('D4', 'Kelompok PKK RW.02');
        $sheet->setCellValue('E4', 'Mitra Non Produktif');
        $sheet->setCellValue('F4', 'Teknologi Tepat Guna');
        $sheet->setCellValue('G4', '64240626');
        $sheet->setCellValue('H4', 'Khoirunnisa');
        $sheet->setCellValue('I4', '2026');
        $sheet->setCellValue('J4', 'https://example.com/dokumen');
        $sheet->setCellValue('K4', 'https://example.com/publikasi');

        $sheet->getStyle('A3:K3')->getFont()->setItalic(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-pkm-dosen.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function publicIndex(Request $request)
    {
        $query = PKMDosen::with('ts')->latest();
        $pkm = $query->paginate(10);
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        return view('pkm_dosen.public_index', compact('pkm', 'tsList'));
    }

    public function publicStore(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string|exists:dosens,kode_dosen',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string|max:100',
            'tema_pkm' => 'required|string|max:200',
            'mitra' => 'required|string|max:200',
            'jenis_pkm' => 'required|in:Mitra Non Produktif,Mitra Produktif',
            'sumber_iptek' => 'required|string|max:100',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string|max:20',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string|max:100',
            'ts_id' => 'required|exists:ts,id',
            'link_dokumen' => 'nullable|url|max:255',
            'link_publikasi' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));

        $nimArray = array_filter($request->input('nim_mhs', []));
        $mhsNamaArray = array_filter($request->input('nama_mahasiswa', []));
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $mhsNamaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        PKMDosen::create($data);

        return redirect()->route('portal.pkm')->with('success', 'Data PKM berhasil dikirim. Hubungi Kaprodi jika terdapat kesalahan input.');
    }
}
