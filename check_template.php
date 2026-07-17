<?php
require 'vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('public/Template_Nilai_BAAK.xlsx');
$sheet = $spreadsheet->getActiveSheet();
echo $sheet->getCell('A1')->getValue() . ',' . $sheet->getCell('L1')->getValue();
