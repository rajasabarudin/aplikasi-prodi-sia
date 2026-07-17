<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');

// Add Use LedNarrative
$content = str_replace('use App\Models\ObePpeppDocument;', "use App\Models\ObePpeppDocument;\nuse App\Models\LedNarrative;", $content);

// Fetch narratives
$fetch_code = "        \$surveiKepuasanCount = DB::table('survei_kepuasans')->count();
        
        \$narratives = LedNarrative::pluck('content', 'kriteria_kode')->toArray();";
$content = str_replace("        \$surveiKepuasanCount = DB::table('survei_kepuasans')->count();", $fetch_code, $content);

// Update compact array
$compact_old = "              'pesertaMahasiswaCount', 'pesertaDosenCount',
              'ppeppDocs', 'surveiKepuasanCount'
          ));";
$compact_new = "              'pesertaMahasiswaCount', 'pesertaDosenCount',
              'ppeppDocs', 'surveiKepuasanCount', 'narratives'
          ));";
$content = str_replace($compact_old, $compact_new, $content);

// Add saveNarrative method
$method_code = "
    public function saveNarrative(Request \$request)
    {
        \$request->validate([
            'kriteria_kode' => 'required|string',
            'content'       => 'nullable|string'
        ]);

        LedNarrative::updateOrCreate(
            ['kriteria_kode' => \$request->kriteria_kode],
            ['content'       => \$request->content]
        );

        return redirect()->back()->with('success', 'Narasi LED Kriteria ' . \$request->kriteria_kode . ' berhasil disimpan!');
    }
";

// Insert before inputScore method
$content = str_replace("    public function inputScore()", $method_code . "\n    public function inputScore()", $content);

file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "ObePortalController updated.";
