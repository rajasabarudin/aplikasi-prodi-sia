<?php

namespace App\Http\Controllers;

use App\Models\IpkLulusan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IpkLulusanController extends Controller
{
    public function index(Request $request)
    {
        $query = IpkLulusan::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('tahun_lulusan', 'like', "%{$search}%");
            });
        }

        $perPage = in_array($request->get('per_page'), [10, 20, 50, 100]) ? intval($request->get('per_page')) : 20;

        $ipkList = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        $tahunList = IpkLulusan::select('tahun_lulusan')->distinct()->orderBy('tahun_lulusan', 'desc')->pluck('tahun_lulusan');
        
        $totalIpk = IpkLulusan::count();
        $avgIpk = IpkLulusan::avg('ipk') ?? 0;
        
        $dist = [
            'sangat_memuaskan' => IpkLulusan::where('ipk', '>=', 3.50)->count(),
            'memuaskan' => IpkLulusan::whereBetween('ipk', [3.00, 3.49])->count(),
            'cukup' => IpkLulusan::where('ipk', '<', 3.00)->count(),
        ];
        
        $avgIpk = round($avgIpk, 2);

        $statsPerTahun = [];
        foreach ($tahunList as $tahun) {
            if (empty($tahun)) continue;
            $records = IpkLulusan::where('tahun_lulusan', $tahun)->get();
            $count = $records->count();
            $statsPerTahun[] = [
                'tahun' => $tahun,
                'average' => $count > 0 ? $records->avg('ipk') : 0,
                'cumlaude' => $records->where('ipk', '>=', 3.50)->count(),
                'memuaskan' => $records->where('ipk', '>=', 3.00)->where('ipk', '<', 3.50)->count(),
                'cukup' => $records->where('ipk', '<', 3.00)->count(),
            ];
        }

        // Chart data
        $chartLabels = array_reverse(array_filter($tahunList->toArray()));
        $chartData = [];
        foreach ($chartLabels as $label) {
            $val = IpkLulusan::where('tahun_lulusan', $label)->avg('ipk');
            $chartData[] = round($val ?? 0, 2);
        }

        return view('ipk_lulusan.index', compact('ipkList', 'tahunList', 'totalIpk', 'avgIpk', 'dist', 'statsPerTahun', 'chartLabels', 'chartData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:50',
            'nama_mahasiswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'tahun_lulusan' => 'required|string|max:10',
            'ipk' => 'required|numeric|between:0.00,4.00',
        ]);

        IpkLulusan::create($request->all());

        return redirect()->route('ipk_lulusan.index')->with('success', 'Data IPK Lulusan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $ipk = IpkLulusan::findOrFail($id);

        $request->validate([
            'nim' => 'required|string|max:50',
            'nama_mahasiswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'tahun_lulusan' => 'required|string|max:10',
            'ipk' => 'required|numeric|between:0.00,4.00',
        ]);

        $ipk->update($request->all());

        return redirect()->route('ipk_lulusan.index')->with('success', 'Data IPK Lulusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ipk = IpkLulusan::findOrFail($id);
        $ipk->delete();

        return redirect()->route('ipk_lulusan.index')->with('success', 'Data IPK Lulusan berhasil dihapus.');
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
            $kelas = trim($row[2] ?? '');
            $tahun = trim($row[3] ?? '');
            $ipkVal = trim($row[4] ?? '');

            if (empty($nim) || empty($nama) || empty($kelas) || empty($tahun) || $ipkVal === '') {
                $skipped++;
                continue;
            }

            // Clean up IPK
            $ipkVal = floatval(str_replace(',', '.', $ipkVal));

            if ($ipkVal < 0 || $ipkVal > 4.00) {
                $skipped++;
                continue;
            }

            $exists = IpkLulusan::where('nim', $nim)->first();
            if ($exists) {
                $exists->update([
                    'nama_mahasiswa' => $nama,
                    'kelas' => $kelas,
                    'tahun_lulusan' => $tahun,
                    'ipk' => $ipkVal,
                ]);
                $inserted++;
                continue;
            }

            IpkLulusan::create([
                'nim' => $nim,
                'nama_mahasiswa' => $nama,
                'kelas' => $kelas,
                'tahun_lulusan' => $tahun,
                'ipk' => $ipkVal,
            ]);

            $inserted++;
        }

        return redirect()->route('ipk_lulusan.index')
            ->with('success', "Import selesai. $inserted data berhasil diproses, $skipped dilewati.");
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama Mahasiswa');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Tahun Lulusan');
        $sheet->setCellValue('E1', 'IPK');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(10);

        $sheet->setCellValue('A3', 'Contoh:');
        $sheet->setCellValue('A4', '12210001');
        $sheet->setCellValue('B4', 'Budi Santoso');
        $sheet->setCellValue('C4', 'SI-4A');
        $sheet->setCellValue('D4', '2024');
        $sheet->setCellValue('E4', '3.85');

        $sheet->getStyle('A3:E3')->getFont()->setItalic(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-ipk-lulusan.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function export(Request $request)
    {
        $tahun = $request->get('tahun_lulusan');
        
        if ($tahun) {
            $data = IpkLulusan::where('tahun_lulusan', $tahun)->orderBy('nama_mahasiswa', 'asc')->get();
            $filename = "Data_IPK_Lulusan_Tahun_{$tahun}.xlsx";
        } else {
            $data = IpkLulusan::orderBy('tahun_lulusan', 'desc')->orderBy('nama_mahasiswa', 'asc')->get();
            $filename = "Data_IPK_Lulusan_Semua_Tahun.xlsx";
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIM');
        $sheet->setCellValue('C1', 'NAMA MAHASISWA');
        $sheet->setCellValue('D1', 'KELAS');
        $sheet->setCellValue('E1', 'TAHUN LULUSAN');
        $sheet->setCellValue('F1', 'IPK');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'], // Green color
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Set Width
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(10);

        // Data
        $row = 2;
        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->nim);
            $sheet->setCellValue('C' . $row, $item->nama_mahasiswa);
            $sheet->setCellValue('D' . $row, $item->kelas);
            $sheet->setCellValue('E' . $row, $item->tahun_lulusan);
            $sheet->setCellValue('F' . $row, $item->ipk);
            
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }

        // Add borders to all data
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:F' . ($row - 1))->applyFromArray($borderStyle);

        $writer = new Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'export_ipk_lulusan');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
