import re

with open('app/Http/Controllers/PenelitianDosenController.php', 'r', encoding='utf-8') as f:
    content = f.read()

pattern = re.compile(r'private function generateDocument\(PenelitianDosen \$penelitian_dosen, \$type\)\s*\{.*?\n    \}', re.DOTALL)

new_method = """private function generateDocument(PenelitianDosen $penelitian_dosen, $type)
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

        // Get Dosen data
        $dosen = \\App\\Models\\Dosen::where('kode_dosen', $penelitian_dosen->kode_dosen)->first();
        $nama_ketua = $penelitian_dosen->nama_dosen ?? ($dosen ? $dosen->nama_dosen : 'Nama Ketua Belum Diisi');
        $nidn_ketua = $dosen ? $dosen->nidn : '-';
        $jabatan_ketua = $dosen ? $dosen->jfa : '-';
        $prodi_ketua = $dosen ? $dosen->homebase_dosen : 'Sistem Informasi Akuntansi (D3)';
        
        $nama_anggota = $penelitian_dosen->nama_mahasiswa ?? '-';
        if(empty(trim($nama_anggota))) $nama_anggota = '-';
        $nidn_anggota = $penelitian_dosen->nim_mhs ?? '-';
        if(empty(trim($nidn_anggota))) $nidn_anggota = '-';
        
        $nama_mitra = $penelitian_dosen->anggota_mitra ?? '-';
        if(empty(trim($nama_mitra))) $nama_mitra = '-';
        
        $biaya = $penelitian_dosen->biaya ? number_format($penelitian_dosen->biaya, 0, ',', '.') : '0';

        // Apply variables
        $templateProcessor->setValue('JUDUL', $judul);
        $templateProcessor->setValue('BULAN_TAHUN', $bulan . ' ' . $tahunStr);
        
        $templateProcessor->setValue('NAMA_KETUA', $nama_ketua);
        $templateProcessor->setValue('NIDN_KETUA', $nidn_ketua);
        $templateProcessor->setValue('JABATAN_KETUA', $jabatan_ketua);
        $templateProcessor->setValue('PRODI_KETUA', $prodi_ketua);
        $templateProcessor->setValue('HP_KETUA', '-');
        $templateProcessor->setValue('EMAIL_KETUA', '-');
        
        $templateProcessor->setValue('NAMA_ANGGOTA', $nama_anggota);
        $templateProcessor->setValue('NIDN_ANGGOTA', $nidn_anggota);
        $templateProcessor->setValue('JABATAN_ANGGOTA', 'Mahasiswa');
        $templateProcessor->setValue('PRODI_ANGGOTA', 'Sistem Informasi Akuntansi');
        
        $templateProcessor->setValue('NAMA_MITRA', $nama_mitra);
        $templateProcessor->setValue('ALAMAT_MITRA', '-');
        $templateProcessor->setValue('PJ_MITRA', '-');
        
        $templateProcessor->setValue('BIAYA', $biaya);
        $templateProcessor->setValue('KETUA_LPPM', '[Nama Ketua LPPM]');
        $templateProcessor->setValue('REKTOR', '[Nama Rektor]');
        $templateProcessor->setValue('INSTITUSI', 'Universitas Bina Sarana Informatika');
        $templateProcessor->setValue('WAKTU_PENELITIAN', '6 Bulan');
        
        $safeJudul = preg_replace('/[^a-zA-Z0-9]/', '_', substr($judul, 0, 30));
        $fileName = "{$type}_Penelitian_{$penelitian_dosen->nama_dosen}_{$safeJudul}.docx";
        
        $tempPath = storage_path('app/temp_' . time() . '.docx');
        $templateProcessor->saveAs($tempPath);
        
        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }"""

match = pattern.search(content)
if match:
    content = content.replace(match.group(0), new_method)

with open('app/Http/Controllers/PenelitianDosenController.php', 'w', encoding='utf-8') as f:
    f.write(content)
