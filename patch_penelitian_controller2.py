import re

with open('app/Http/Controllers/PenelitianDosenController.php', 'r', encoding='utf-8') as f:
    content = f.read()

generation_code = """
    public function generateProposal(PenelitianDosen $penelitian_dosen)
    {
        return $this->generateDocument($penelitian_dosen, 'Proposal');
    }

    public function generateLaporan(PenelitianDosen $penelitian_dosen)
    {
        return $this->generateDocument($penelitian_dosen, 'Laporan');
    }

    private function generateDocument(PenelitianDosen $penelitian_dosen, $type)
    {
        $templatePath = storage_path("app/templates/Template_{$type}.docx");
        if (!file_exists($templatePath)) {
            return back()->with('error', "Template {$type} tidak ditemukan di sistem.");
        }

        $templateProcessor = new \\PhpOffice\\PhpWord\\TemplateProcessor($templatePath);
        
        $judul = $penelitian_dosen->judul_penelitian ?? 'Judul Penelitian Belum Diisi';
        $ts = $penelitian_dosen->ts;
        
        $semester = $ts ? $ts->semester : 'Gasal';
        $tahunSekarang = $ts ? $ts->tahun_sekarang : date('Y');
        
        preg_match('/\\d{4}/', $tahunSekarang, $matches);
        $tahun = isset($matches[0]) ? intval($matches[0]) : date('Y');
        
        $isGanjil = stripos($semester, 'Gasal') !== false || stripos($semester, 'Ganjil') !== false;
        
        if ($type === 'Proposal') {
            if ($isGanjil) {
                $bulan = 'AGUSTUS';
                $tahunStr = $tahun;
            } else {
                $bulan = 'FEBRUARI';
                $tahunStr = $tahun + 1;
            }
        } else {
            if ($isGanjil) {
                $bulan = 'FEBRUARI';
                $tahunStr = $tahun + 1;
            } else {
                $bulan = 'AGUSTUS';
                $tahunStr = $tahun + 1;
            }
        }

        $templateProcessor->setValue('JUDUL', $judul);
        $templateProcessor->setValue('BULAN_TAHUN', $bulan . ' ' . $tahunStr);
        
        $safeJudul = preg_replace('/[^a-zA-Z0-9]/', '_', substr($judul, 0, 30));
        $fileName = "{$type}_Penelitian_{$penelitian_dosen->nama_dosen}_{$safeJudul}.docx";
        
        $tempPath = storage_path('app/temp_' . time() . '.docx');
        $templateProcessor->saveAs($tempPath);
        
        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}
"""

parts = content.rsplit('}', 1)
new_content = parts[0] + generation_code

with open('app/Http/Controllers/PenelitianDosenController.php', 'w', encoding='utf-8') as f:
    f.write(new_content)
