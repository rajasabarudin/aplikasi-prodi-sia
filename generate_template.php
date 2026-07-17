<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$headers = ['nim', 'nama mhs', 'kelas', 'kd matakuliah', 'nama matakuliah', 'sks', 'nilai absen', 'nilai tugas', 'nilai uts', 'nilai uas', 'total nilai', 'grade akhir'];
foreach($headers as $i => $header) {
    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
    $sheet->setCellValue($col . '1', $header);
}
$sheet->setCellValue('A2', '123456');
$sheet->setCellValue('B2', 'John Doe');
$sheet->setCellValue('C2', 'IF-1');
$sheet->setCellValue('D2', 'MK001');
$sheet->setCellValue('E2', 'Pemrograman Web');
$sheet->setCellValue('F2', '3');
$sheet->setCellValue('G2', '100');
$sheet->setCellValue('H2', '85');
$sheet->setCellValue('I2', '90');
$sheet->setCellValue('J2', '80');
$sheet->setCellValue('K2', '87.5');
$sheet->setCellValue('L2', 'A');

$writer = new Xlsx($spreadsheet);
$writer->save('public/Template_Nilai_BAAK.xlsx');
echo "Template created.\n";
