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
        
        $judulRaw = $penelitian_dosen->judul_penelitian ?? 'Penelitian Pengembangan Teknologi dan Sistem Informasi';
        // Escape special XML chars to prevent document corruption
        $judul = htmlspecialchars($judulRaw, ENT_XML1, 'UTF-8');
        
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

        $dosen = \\App\\Models\\Dosen::where('kode_dosen', $penelitian_dosen->kode_dosen)->first();
        $nama_ketua = htmlspecialchars($penelitian_dosen->nama_dosen ?? ($dosen ? $dosen->nama_dosen : 'Nama Ketua Belum Diisi'), ENT_XML1, 'UTF-8');
        $nidn_ketua = htmlspecialchars($dosen ? $dosen->nidn : '-', ENT_XML1, 'UTF-8');
        if(empty(trim($nidn_ketua))) $nidn_ketua = '-';
        $jabatan_ketua = htmlspecialchars($dosen ? $dosen->jfa : 'Lektor', ENT_XML1, 'UTF-8');
        $prodi_ketua = htmlspecialchars($dosen ? $dosen->homebase_dosen : 'Sistem Informasi (S1)', ENT_XML1, 'UTF-8');
        
        $nama_anggota = htmlspecialchars($penelitian_dosen->nama_mahasiswa ?? '-', ENT_XML1, 'UTF-8');
        if(empty(trim($nama_anggota))) $nama_anggota = 'Anggota Peneliti (Data Belum Diisi)';
        $nidn_anggota = htmlspecialchars($penelitian_dosen->nim_mhs ?? '-', ENT_XML1, 'UTF-8');
        if(empty(trim($nidn_anggota))) $nidn_anggota = '-';
        
        $nama_mitra = htmlspecialchars($penelitian_dosen->anggota_mitra ?? '-', ENT_XML1, 'UTF-8');
        if(empty(trim($nama_mitra))) $nama_mitra = '-';
        
        $biaya = $penelitian_dosen->biaya ? $penelitian_dosen->biaya : 5000000;
        $biayaStr = htmlspecialchars(number_format($biaya, 0, ',', '.'), ENT_XML1, 'UTF-8');

        $templateProcessor->setValue('JUDUL', strtoupper($judul));
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
        $templateProcessor->setValue('PRODI_ANGGOTA', 'Informatika');
        
        $templateProcessor->setValue('NAMA_MITRA', $nama_mitra);
        $templateProcessor->setValue('ALAMAT_MITRA', '-');
        $templateProcessor->setValue('PJ_MITRA', '-');
        
        $templateProcessor->setValue('BIAYA', $biayaStr);
        $templateProcessor->setValue('KETUA_LPPM', '[Nama Ketua LPPM]');
        $templateProcessor->setValue('REKTOR', '[Nama Rektor]');
        $templateProcessor->setValue('INSTITUSI', 'Universitas Bina Sarana Informatika');
        $templateProcessor->setValue('WAKTU_PENELITIAN', '6 Bulan');
        
        $ringkasan = "Penelitian ini berjudul '$judul'. Fokus utama dari penelitian ini adalah untuk merancang, mengimplementasikan, dan menguji solusi inovatif yang relevan dengan perkembangan keilmuan terkini. Penelitian ini diharapkan dapat memberikan kontribusi signifikan baik dari segi teoritis maupun praktis. Melalui pendekatan sistematis, kajian ini mengeksplorasi metodologi yang relevan dan mengaplikasikannya dalam studi kasus yang terukur, sehingga menghasilkan luaran yang bermanfaat.";
        $pendahuluan = "Latar belakang penelitian ini dilandasi oleh kebutuhan yang semakin meningkat terhadap solusi inovatif dalam bidang ini. Perkembangan teknologi dan dinamika masyarakat menuntut adanya penelitian yang lebih komprehensif. Masalah utama yang akan dipecahkan adalah bagaimana meningkatkan efisiensi, akurasi, dan efektivitas melalui pendekatan baru. Penelitian ini memiliki urgensi yang tinggi mengingat dampak positif yang dapat dihasilkan bagi perbaikan sistem, optimalisasi proses, serta pengembangan keilmuan selanjutnya dalam jangka panjang.";
        $metode = "Metode yang digunakan dalam penelitian ini meliputi pendekatan kualitatif dan kuantitatif yang dipadukan (mixed-methods) untuk mendapatkan hasil komprehensif. Pengumpulan data dilakukan melalui studi literatur mendalam, observasi langsung, dokumentasi, dan wawancara dengan narasumber yang relevan. Data yang terkumpul kemudian dianalisis menggunakan metode statistik serta pemodelan sistem. Validasi hasil akan dilakukan melalui tahapan pengujian fungsionalitas dan triangulasi data untuk memastikan keakuratan dan keandalan temuan penelitian.";
        $luaran = "Luaran dari penelitian ini ditargetkan berupa publikasi jurnal nasional terakreditasi SINTA sesuai standar dikti. Selain itu, hasil penelitian ini juga diharapkan dapat diwujudkan dalam bentuk prototipe/model sistem yang berfungsi penuh dan dapat menjadi rujukan berharga bagi akademisi, peneliti selanjutnya, maupun pihak praktisi terkait.";
        
        // Correct Word XML line break insertion: </w:t><w:br/><w:t>
        $pustaka = "1. Setyawan, A., &amp; Budi, S. (2025). Metodologi Penelitian Modern dan Implementasinya. Jakarta: Penerbit Informatika.</w:t><w:br/><w:t>2. Wijaya, R. (2024). Inovasi dan Pengembangan Sistem di Era Digital. Jurnal Sains dan Teknologi, 12(3), 45-56.</w:t><w:br/><w:t>3. Referensi Jurnal Utama: '$judul'. (Disesuaikan).";
        
        $templateProcessor->setValue('RINGKASAN', $ringkasan);
        $templateProcessor->setValue('PENDAHULUAN', $pendahuluan);
        $templateProcessor->setValue('METODE', $metode);
        $templateProcessor->setValue('LUARAN', $luaran);
        $templateProcessor->setValue('PUSTAKA', $pustaka);

        $templateProcessor->setValue('B_NO', '1');
        $templateProcessor->setValue('B_ITEM', 'Pelaksanaan Penelitian (Pembelian ATK, Pengumpulan Data, dan Publikasi)');
        $templateProcessor->setValue('B_HARGA', $biayaStr);
        $templateProcessor->setValue('B_TOTAL', $biayaStr);
        $templateProcessor->setValue('B_GRAND', $biayaStr);

        $templateProcessor->setValue('J_NO', '1');
        $templateProcessor->setValue('J_KEGIATAN', 'Pelaksanaan Penelitian Berkelanjutan');

        $safeJudul = preg_replace('/[^a-zA-Z0-9]/', '_', substr($judulRaw, 0, 30));
        $fileName = "{$type}_Penelitian_{$penelitian_dosen->nama_dosen}_{$safeJudul}.docx";
        
        $tempPath = storage_path('app/temp_' . time() . '.docx');
        $templateProcessor->saveAs($tempPath);
        
        // Clear any previous output buffers to prevent file corruption
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }"""

match = pattern.search(content)
if match:
    content = content.replace(match.group(0), new_method)

with open('app/Http/Controllers/PenelitianDosenController.php', 'w', encoding='utf-8') as f:
    f.write(content)
