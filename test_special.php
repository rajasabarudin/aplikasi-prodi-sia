<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$templatePath = storage_path("app/templates/Template_Laporan.docx");
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
$templateProcessor->setValue('JUDUL', 'Test & Test');
$templateProcessor->saveAs(storage_path('app/test_output2.docx'));
echo "Done\n";
