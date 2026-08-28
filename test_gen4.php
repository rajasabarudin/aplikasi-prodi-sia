<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\PenelitianDosen::first();
$controller = app(\App\Http\Controllers\PenelitianDosenController::class);

$templatePath = storage_path("app/templates/Template_Laporan.docx");
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
$templateProcessor->setValue('JUDUL', 'Test & Test <w:br/> line 2');
$templateProcessor->saveAs(storage_path('app/test_output4.docx'));
echo "Done\n";
