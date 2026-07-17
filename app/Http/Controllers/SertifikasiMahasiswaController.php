<?php

namespace App\Http\Controllers;

use App\Models\SertifikasiMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SertifikasiMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = SertifikasiMahasiswa::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', '%' . $search . '%')
                  ->orWhere('nama_mhs', 'like', '%' . $search . '%')
                  ->orWhere('skema_serkom', 'like', '%' . $search . '%');
            });
        }

        $perPage = in_array($request->get('per_page'), [20, 50, 100, 1000]) ? intval($request->get('per_page')) : 20;
        $sertifikasis = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        $mahasiswaList = Mahasiswa::orderBy('nama', 'asc')->get();
        $totalSertifikasi = SertifikasiMahasiswa::count();

        // Hitung total inputan sertifikasi
        $mhsPunyaSerkomCount = SertifikasiMahasiswa::count();
        // Hitung mahasiswa unik
        $mhsUnikBersertifikat = SertifikasiMahasiswa::distinct()->count('nim');
        
        $totalMhsCount = Mahasiswa::count();
        $persenMhsSerkom = $totalMhsCount > 0 ? round(($mhsUnikBersertifikat / $totalMhsCount) * 100, 2) : 0;
        $persenTotalSertifikasi = $totalMhsCount > 0 ? round(($mhsPunyaSerkomCount / $totalMhsCount) * 100, 2) : 0;
        
        $sertifikasiBySkema = SertifikasiMahasiswa::select('skema_serkom')
            ->selectRaw('count(*) as total')
            ->groupBy('skema_serkom')
            ->orderBy('total', 'desc')
            ->get();

        return view('sertifikasi_mahasiswa.index', compact(
            'sertifikasis', 'mahasiswaList', 'totalSertifikasi', 'sertifikasiBySkema', 'search',
            'mhsPunyaSerkomCount', 'mhsUnikBersertifikat', 'persenMhsSerkom', 'persenTotalSertifikasi', 'totalMhsCount', 'perPage'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:255|exists:mahasiswas,nim',
            'nama_mhs' => 'required|string|max:255',
            'skema_serkom' => 'required|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ], [
            'nim.exists' => 'NIM tidak terdaftar dalam data master mahasiswa.',
        ]);

        SertifikasiMahasiswa::create($request->all());

        return redirect()->route('sertifikasi-mahasiswa.index')
            ->with('success', 'Data sertifikasi mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $sertifikasi = SertifikasiMahasiswa::findOrFail($id);

        $request->validate([
            'nim' => 'required|string|max:255|exists:mahasiswas,nim',
            'nama_mhs' => 'required|string|max:255',
            'skema_serkom' => 'required|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ], [
            'nim.exists' => 'NIM tidak terdaftar dalam data master mahasiswa.',
        ]);

        $sertifikasi->update($request->all());

        return redirect()->route('sertifikasi-mahasiswa.index')
            ->with('success', 'Data sertifikasi mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sertifikasi = SertifikasiMahasiswa::findOrFail($id);
        $sertifikasi->delete();

        return redirect()->route('sertifikasi-mahasiswa.index')
            ->with('success', 'Data sertifikasi mahasiswa berhasil dihapus.');
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
            $namaMhs = trim($row[1] ?? '');
            $skemaSerkom = trim($row[2] ?? '');
            $linkDokumen = trim($row[3] ?? '');

            if (empty($nim) || empty($skemaSerkom)) {
                $skipped++;
                continue;
            }

            $mhs = Mahasiswa::where('nim', $nim)->first();
            if (!$mhs) {
                $skipped++;
                continue;
            }
            
            if (empty($namaMhs)) {
                $namaMhs = $mhs->nama;
            }

            // Avoid duplicate NIM + Scheme
            $exists = SertifikasiMahasiswa::where('nim', $nim)->where('skema_serkom', $skemaSerkom)->first();
            if ($exists) {
                $exists->update([
                    'nama_mhs' => $namaMhs,
                    'link_dokumen' => $linkDokumen ?: $exists->link_dokumen,
                ]);
            } else {
                SertifikasiMahasiswa::create([
                    'nim' => $nim,
                    'nama_mhs' => $namaMhs,
                    'skema_serkom' => $skemaSerkom,
                    'link_dokumen' => $linkDokumen ?: null,
                ]);
            }

            $inserted++;
        }

        return redirect()->route('sertifikasi-mahasiswa.index')
            ->with('success', "Import selesai. $inserted data berhasil diproses (ditambahkan/diperbarui), $skipped dilewati.");
    }

    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama Mahasiswa');
        $sheet->setCellValue('C1', 'Skema Sertifikasi');
        $sheet->setCellValue('D1', 'Link Upload Dokumen (Opsional)');

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
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(35);

        $sheet->setCellValue('A3', 'Contoh:');
        $sheet->setCellValue('A4', '12210001');
        $sheet->setCellValue('B4', 'Rian Hidayat');
        $sheet->setCellValue('C4', 'Programmer');
        $sheet->setCellValue('D4', 'https://example.com/sertifikat-rian');

        $sheet->getStyle('A3:D3')->getFont()->setItalic(true);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template-import-sertifikasi.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
