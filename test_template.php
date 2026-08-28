<?php
require 'vendor/autoload.php';

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('storage/app/templates/Template_Laporan.docx');
$templateProcessor->setValue('JUDUL', 'IMPLEMENTASI AI UNTUK DIAGNOSA PENYAKIT');
$templateProcessor->setValue('BULAN_TAHUN', 'AGUSTUS 2026');
$templateProcessor->saveAs('test_laporan.docx');

echo "Done\n";
