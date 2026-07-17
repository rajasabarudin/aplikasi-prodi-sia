<?php

namespace App\Http\Controllers;

use App\Models\IpkMahasiswa;
use App\Models\Ts;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IpkMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = IpkMahasiswa::with('ts');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if ($tsId = $request->get('ts_id')) {
            $query->where('ts_id', $tsId);
        }

        $perPage = in_array($request->get('per_page'), [10, 20, 50, 100]) ? intval($request->get('per_page')) : 20;

        $ipkList = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        
        $tsList = TS::all();
        $mahasiswaList = Mahasiswa::orderBy('nama', 'asc')->get();
        $latestTsRecords = TS::where('label_ts', 'TS')->get();
        if ($latestTsRecords->count() > 0) {
            $tsIds = $latestTsRecords->pluck('id')->toArray();
            $totalIpk = IpkMahasiswa::whereIn('ts_id', $tsIds)->count();
            $avgIpk = IpkMahasiswa::whereIn('ts_id', $tsIds)->avg('ipk') ?? 0;
            $dist = [
                'sangat_memuaskan' => IpkMahasiswa::whereIn('ts_id', $tsIds)->where('ipk', '>=', 3.50)->count(),
                'memuaskan' => IpkMahasiswa::whereIn('ts_id', $tsIds)->whereBetween('ipk', [3.00, 3.49])->count(),
                'cukup' => IpkMahasiswa::whereIn('ts_id', $tsIds)->where('ipk', '<', 3.00)->count(),
            ];
            $latestTsLabel = 'TS';
        } else {
            $totalIpk = 0;
            $avgIpk = 0;
            $dist = ['sangat_memuaskan' => 0, 'memuaskan' => 0, 'cukup' => 0];
            $latestTsLabel = '-';
        }
        $avgIpk = round($avgIpk, 2);

        // Calculate average GPA per TS for the chart
        $avgIpkPerTs = IpkMahasiswa::join('ts', 'ipk_mahasiswa.ts_id', '=', 'ts.id')
            ->selectRaw('ts.tahun_sekarang, AVG(ipk_mahasiswa.ipk) as average')
            ->groupBy('ts.tahun_sekarang', 'ipk_mahasiswa.ts_id')
            ->orderBy('ipk_mahasiswa.ts_id', 'desc')
            ->get();

        $chartLabels = $avgIpkPerTs->pluck('tahun_sekarang')->toArray();
        $chartData = $avgIpkPerTs->pluck('average')->map(function($val) {
            return round($val, 2);
        })->toArray();

        // Calculate average and distribution per TS for the sidebar list
        $tsStats = [];
        foreach ($tsList as $ts) {
            $records = IpkMahasiswa::where('ts_id', $ts->id)->get();
            $count = $records->count();
            $average = $count > 0 ? $records->avg('ipk') : 0;

            $tsStats[] = [
                'tahun_sekarang' => $ts->tahun_sekarang,
                'average' => $average,
                'cumlaude' => $records->where('ipk', '>=', 3.50)->count(),
                'memuaskan' => $records->where('ipk', '>=', 3.00)->where('ipk', '<', 3.50)->count(),
                'cukup' => $records->where('ipk', '<', 3.00)->count(),
                'lulus' => $records->where('ipk', '>=', 2.00)->count(),
                'tidak_lulus' => $records->where('ipk', '<', 2.00)->count(),
            ];
        }
        
        $labelTsStats = [];
        $recordsByLabel = IpkMahasiswa::with('ts')->get()->filter(function ($item) {
            return $item->ts && $item->ts->label_ts;
        })->groupBy(function ($item) {
            return $item->ts->label_ts;
        })->sortKeys();

        foreach ($recordsByLabel as $label => $records) {
            $count = $records->count();
            $average = $count > 0 ? $records->avg('ipk') : 0;
            $labelTsStats[] = [
                'label' => $label,
                'average' => $average,
                'cumlaude' => $records->where('ipk', '>=', 3.50)->count(),
                'memuaskan' => $records->where('ipk', '>=', 3.00)->where('ipk', '<', 3.50)->count(),
                'cukup' => $records->where('ipk', '<', 3.00)->count(),
                'lulus' => $records->where('ipk', '>=', 2.00)->count(),
                'tidak_lulus' => $records->where('ipk', '<', 2.00)->count(),
            ];
        }

        return view('ipk.index', compact('ipkList', 'tsList', 'mahasiswaList', 'totalIpk', 'avgIpk', 'dist', 'chartLabels', 'chartData', 'tsStats', 'labelTsStats', 'latestTsLabel'));
    }

    public function create()
    {
        $tsList = TS::all();
        $mahasiswaList = Mahasiswa::orderBy('nama', 'asc')->get();
        return view('ipk.create', compact('tsList', 'mahasiswaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'ipk' => 'required|numeric|between:0.00,4.00',
            'ts_id' => 'required|exists:ts,id',
        ]);

        IpkMahasiswa::create($request->all());

        return redirect()->route('ipk.index')->with('success', 'Data IPK Mahasiswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ipk = IpkMahasiswa::findOrFail($id);
        $tsList = TS::all();
        $mahasiswaList = Mahasiswa::orderBy('nama', 'asc')->get();
        return view('ipk.edit', compact('ipk', 'tsList', 'mahasiswaList'));
    }

    public function update(Request $request, $id)
    {
        $ipk = IpkMahasiswa::findOrFail($id);

        $request->validate([
            'nim' => 'required|string|max:20',
            'nama' => 'required|string|max:255',
            'ipk' => 'required|numeric|between:0.00,4.00',
            'ts_id' => 'required|exists:ts,id',
        ]);

        $ipk->update($request->all());

        return redirect()->route('ipk.index')->with('success', 'Data IPK Mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ipk = IpkMahasiswa::findOrFail($id);
        $ipk->delete();

        return redirect()->route('ipk.index')->with('success', 'Data IPK Mahasiswa berhasil dihapus.');
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
            if ($index === 0) continue; // Skip header

            $nim = trim($row[0] ?? '');
            $nama = trim($row[1] ?? '');
            $ipkVal = trim($row[2] ?? '');
            $tsVal = trim($row[3] ?? '');

            if (empty($nim) || empty($nama) || $ipkVal === '' || empty($tsVal)) {
                $skipped++;
                continue;
            }

            // Clean up IPK
            $ipkVal = floatval(str_replace(',', '.', $ipkVal));

            if ($ipkVal < 0 || $ipkVal > 4.00) {
                $skipped++;
                continue;
            }

            // Find or create TS
            $ts = TS::firstOrCreate(['tahun_sekarang' => $tsVal]);

            // Avoid duplicate NIM in the same TS
            $exists = IpkMahasiswa::where('nim', $nim)->where('ts_id', $ts->id)->exists();
            if ($exists) {
                IpkMahasiswa::where('nim', $nim)->where('ts_id', $ts->id)->update([
                    'nama' => $nama,
                    'ipk' => $ipkVal,
                ]);
                $inserted++;
                continue;
            }

            IpkMahasiswa::create([
                'nim' => $nim,
                'nama' => $nama,
                'ipk' => $ipkVal,
                'ts_id' => $ts->id,
            ]);

            $inserted++;
        }

        return redirect()->route('ipk.index')
            ->with('success', "Import selesai. $inserted data berhasil diproses (ditambahkan/diperbarui), $skipped dilewati.");
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'IPK');
        $sheet->setCellValue('D1', 'TA (Tahun Akademik)');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(20);

        $sheet->setCellValue('A3', 'Contoh:');
        $sheet->setCellValue('A4', '12210001');
        $sheet->setCellValue('B4', 'Rian Hidayat');
        $sheet->setCellValue('C4', '3.85');
        $sheet->setCellValue('D4', '2023/2024');

        $sheet->getStyle('A3:D3')->getFont()->setItalic(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-ipk.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
