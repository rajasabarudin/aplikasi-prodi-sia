<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\PenelitianDosen::first();
$controller = app(\App\Http\Controllers\PenelitianDosenController::class);

$templatePath = storage_path("app/templates/Template_Laporan.docx");
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
$templateProcessor->setValue('JUDUL', 'Test');
$templateProcessor->setValue('B_NO', '1');
$templateProcessor->setValue('B_ITEM', 'Test Item');
$templateProcessor->setValue('B_HARGA', '100');
$templateProcessor->setValue('B_TOTAL', '100');
$templateProcessor->setValue('B_GRAND', '100');
$templateProcessor->setValue('J_NO', '1');
$templateProcessor->setValue('J_KEGIATAN', 'Keg');

$tempPath = storage_path('app/test_output3.docx');
$templateProcessor->saveAs($tempPath);
echo "Saved to $tempPath\n";
