import re

# PATCH RTM CONTROLLER
with open('app/Http/Controllers/RtmController.php', 'r', encoding='utf-8') as f:
    rtm_content = f.read()

new_generate_rtm = """    public function generate($id)
    {
        $rps = Rps::with(['pertemuans' => function($query) {
            $query->orderBy('minggu_ke', 'asc');
        }])->findOrFail($id);
        
        Rtm::where('rps_id', $rps->id)->delete();
        $rtm = Rtm::create([
            'rps_id' => $rps->id,
            'nomor_dokumen' => 'UBSI/DA RTM.' . $rps->kode_matakuliah,
            'dosen_pengampu' => $rps->dosen_pengembang,
            'semester' => (int) ($rps->matakuliah?->semester ?: 1)
        ]);

        $tugas_ke = 1;
        $hasTugas = false;
        foreach ($rps->pertemuans as $pertemuan) {
            $bobot = (float) $pertemuan->bobot_penilaian;
            if ($bobot > 0) {
                $hasTugas = true;
                $rtmTugas = RtmTugas::create([
                    'rtm_id' => $rtm->id,
                    'minggu_ke' => $pertemuan->minggu_ke,
                    'tugas_ke' => (string) $tugas_ke,
                    'bentuk_tugas' => 'Tugas ' . ($pertemuan->metode_pembelajaran ?: 'Mandiri'),
                    'judul_tugas' => 'Tugas ' . $tugas_ke,
                    'sub_cpmk' => $pertemuan->sub_cpmk ?: '',
                    'obyek_garapan' => $pertemuan->bahan_kajian ?: '',
                    'metode_pengerjaan' => $pertemuan->pengalaman_belajar ?: '',
                    'bentuk_format_luaran' => '',
                    'waktu_pengerjaan' => $pertemuan->waktu_pembelajaran ?: '',
                    'waktu_pengumpulan' => 'Minggu ke-' . $pertemuan->minggu_ke,
                    'lain_lain' => '',
                    'daftar_rujukan' => '',
                ]);
                
                RtmPenilaian::create([
                    'rtm_tugas_id' => $rtmTugas->id,
                    'indikator' => $pertemuan->indikator_penilaian ?: '',
                    'teknik_penilaian' => $pertemuan->kriteria_penilaian ?: '',
                    'bobot_penilaian' => $pertemuan->bobot_penilaian ?: '0'
                ]);
                
                $tugas_ke++;
            }
        }
        
        // Jika tidak ada tugas sama sekali, buat 1 draft
        if (!$hasTugas) {
            RtmTugas::create([
                'rtm_id' => $rtm->id,
                'minggu_ke' => '1',
                'tugas_ke' => '1',
                'bentuk_tugas' => 'Tugas Mandiri',
                'judul_tugas' => 'Tugas 1 (Draft)',
                'sub_cpmk' => '',
                'obyek_garapan' => '',
                'metode_pengerjaan' => '',
                'bentuk_format_luaran' => '',
                'waktu_pengerjaan' => '',
                'waktu_pengumpulan' => '',
                'lain_lain' => '',
                'daftar_rujukan' => '',
            ]);
        }

        return redirect()->route('penyusunan-rtm.index')->with('success', 'RTM berhasil digenerate otomatis dan akurat dari Rincian Pertemuan RPS!');
    }"""

# Replace the entire generate function for RTM
rtm_content = re.sub(
    r"    public function generate\(\$id\)\s*\{.*?\n    \}\n",
    new_generate_rtm + "\n\n",
    rtm_content,
    flags=re.DOTALL
)

with open('app/Http/Controllers/RtmController.php', 'w', encoding='utf-8') as f:
    f.write(rtm_content)


# PATCH SILABUS CONTROLLER
with open('app/Http/Controllers/SilabusController.php', 'r', encoding='utf-8') as f:
    silabus_content = f.read()

new_generate_silabus = """    public function generate($id)
    {
        $rps = Rps::with(['pertemuans' => function($query) {
            $query->orderBy('minggu_ke', 'asc');
        }])->findOrFail($id);
        
        Silabus::where('rps_id', $rps->id)->delete();
        
        $silabus = Silabus::create([
            'rps_id' => $rps->id,
            'kode_dokumen' => 'UBSI/DA/PNK.' . $rps->kode_matakuliah,
            'cpmk' => '',
            'sub_cpmk' => ''
        ]);
        
        $hasMateri = false;
        foreach ($rps->pertemuans as $pertemuan) {
            if (!empty(trim($pertemuan->bahan_kajian))) {
                $hasMateri = true;
                SilabusMateri::create([
                    'silabus_id' => $silabus->id,
                    'pertemuan' => $pertemuan->minggu_ke,
                    'materi' => $pertemuan->bahan_kajian
                ]);
            }
        }
        
        if (!$hasMateri) {
            SilabusMateri::create([
                'silabus_id' => $silabus->id,
                'pertemuan' => '1',
                'materi' => 'Materi Pertemuan 1 (Draft)'
            ]);
        }
        
        return redirect()->route('penyusunan-silabus.index')->with('success', 'Silabus berhasil digenerate otomatis dari Rincian Pertemuan RPS!');
    }"""

silabus_content = re.sub(
    r"    public function generate\(\$id\)\s*\{.*?\n    \}\n",
    new_generate_silabus + "\n\n",
    silabus_content,
    flags=re.DOTALL
)

with open('app/Http/Controllers/SilabusController.php', 'w', encoding='utf-8') as f:
    f.write(silabus_content)
