import re

# PATCH RTM CONTROLLER
with open('app/Http/Controllers/RtmController.php', 'r', encoding='utf-8') as f:
    rtm_content = f.read()

new_rtm = """    public function generate($id)
    {
        $rps = Rps::with('pertemuans')->findOrFail($id);
        
        $pertemuans = $rps->pertemuans->sortBy(function($item) {
            return (int) $item->minggu_ke;
        });

        Rtm::where('rps_id', $rps->id)->delete();
        $rtm = Rtm::create([
            'rps_id' => $rps->id,
            'nomor_dokumen' => 'UBSI/DA RTM.' . $rps->kode_matakuliah,
            'dosen_pengampu' => $rps->dosen_pengembang,
            'semester' => (int) ($rps->matakuliah?->semester ?: 1)
        ]);

        $tugas_ke = 1;
        $hasTugas = false;
        foreach ($pertemuans as $pertemuan) {"""

rtm_content = re.sub(
    r"    public function generate\(\$id\)\s*\{.*?foreach \(\$rps->pertemuans as \$pertemuan\) \{",
    new_rtm,
    rtm_content,
    flags=re.DOTALL
)

with open('app/Http/Controllers/RtmController.php', 'w', encoding='utf-8') as f:
    f.write(rtm_content)


# PATCH SILABUS CONTROLLER
with open('app/Http/Controllers/SilabusController.php', 'r', encoding='utf-8') as f:
    silabus_content = f.read()

new_silabus = """    public function generate($id)
    {
        $rps = Rps::with('pertemuans')->findOrFail($id);
        
        $pertemuans = $rps->pertemuans->sortBy(function($item) {
            return (int) $item->minggu_ke;
        });

        Silabus::where('rps_id', $rps->id)->delete();
        
        // Ambil CPMK dari tabel cpmks
        $cpmks = \\\\App\\\\Models\\\\Cpmk::where('kode_matakuliah', $rps->kode_matakuliah)->get();
        $cpmk_text = '';
        foreach($cpmks as $index => $c) {
            $cpmk_text .= ($index + 1) . ". " . trim($c->deskripsi_cpmk) . "\\n";
        }

        // Ambil Sub-CPMK unik dari pertemuan
        $subCpmkList = [];
        foreach ($pertemuans as $pertemuan) {
            if (!empty(trim($pertemuan->sub_cpmk))) {
                $subCpmkList[] = trim($pertemuan->sub_cpmk);
            }
        }
        $sub_cpmk_text = '';
        $uniqueSubCpmk = array_values(array_unique($subCpmkList));
        foreach($uniqueSubCpmk as $index => $s) {
            $sub_cpmk_text .= ($index + 1) . ". " . $s . "\\n";
        }
        
        $silabus = Silabus::create([
            'rps_id' => $rps->id,
            'kode_dokumen' => 'UBSI/DA/PNK.' . $rps->kode_matakuliah,
            'cpmk' => trim($cpmk_text),
            'sub_cpmk' => trim($sub_cpmk_text)
        ]);
        
        $hasMateri = false;
        foreach ($pertemuans as $pertemuan) {"""

silabus_content = re.sub(
    r"    public function generate\(\$id\)\s*\{.*?foreach \(\$rps->pertemuans as \$pertemuan\) \{",
    new_silabus,
    silabus_content,
    flags=re.DOTALL
)

with open('app/Http/Controllers/SilabusController.php', 'w', encoding='utf-8') as f:
    f.write(silabus_content)
