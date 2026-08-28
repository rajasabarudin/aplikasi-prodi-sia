<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\PenelitianDosen::first();
$controller = app(\App\Http\Controllers\PenelitianDosenController::class);
// generateProposal returns a download response. Let's just catch the file.
// Wait, generateDocument saves to app/temp_XXX.docx before downloading.
// The file is deleted after send, but if we don't send, it might stay.
// Or we can just mock it.
// Let's copy the code from controller to test manually.

$templatePath = storage_path("app/templates/Template_Laporan.docx");
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
$templateProcessor->setValue('JUDUL', 'TEST');
try {
    $templateProcessor->cloneRow('B_NO', 3);
    $templateProcessor->setValue('B_NO#1', '1');
} catch (\Exception $e) {
    echo "Clone row B_NO error: " . $e->getMessage() . "\n";
}
try {
    $templateProcessor->cloneRow('J_NO', 4);
} catch (\Exception $e) {
    echo "Clone row J_NO error: " . $e->getMessage() . "\n";
}
$tempPath = storage_path('app/test_output.docx');
$templateProcessor->saveAs($tempPath);
echo "Saved to $tempPath\n";
