<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');
$content = str_replace(
    "return redirect()->back()->with('success', 'Dokumen akreditasi berhasil dihapus!');\n\n    public function pdfRecap",
    "return redirect()->back()->with('success', 'Dokumen akreditasi berhasil dihapus!');\n    }\n\n    public function pdfRecap",
    $content
);
file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Fixed missing closing brace.";
