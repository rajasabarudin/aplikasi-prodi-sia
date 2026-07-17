<?php
require 'vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(base_path('nilai cpl mhs/Pengukuran CPL SIA SMI Genap 2024-2025.xlsx'));
$sheet = $spreadsheet->getActiveSheet();
echo $sheet->getCell('A1')->getValue() . ',' . $sheet->getCell('L1')->getValue();
