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
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $perPage = in_array($request->get('per_page'), [10, 20, 50, 100]) ? intval($request->get('per_page')) : 20;

        $ipkList = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        return view('ipk_lulusan.index', compact('ipkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:50',
            'nama_mahasiswa' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
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
            $ipkVal = trim($row[3] ?? '');

            if (empty($nim) || empty($nama) || empty($kelas) || $ipkVal === '') {
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
                    'ipk' => $ipkVal,
                ]);
                $inserted++;
                continue;
            }

            IpkLulusan::create([
                'nim' => $nim,
                'nama_mahasiswa' => $nama,
                'kelas' => $kelas,
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
        $sheet->setCellValue('D1', 'IPK');

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
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(10);

        $sheet->setCellValue('A3', 'Contoh:');
        $sheet->setCellValue('A4', '12210001');
        $sheet->setCellValue('B4', 'Budi Santoso');
        $sheet->setCellValue('C4', 'SI-4A');
        $sheet->setCellValue('D4', '3.85');

        $sheet->getStyle('A3:D3')->getFont()->setItalic(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-ipk-lulusan.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
