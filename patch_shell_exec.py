import re

# PATCH RTM CONTROLLER
with open('app/Http/Controllers/RtmController.php', 'r', encoding='utf-8') as f:
    rtm_content = f.read()

# Replace shell_exec
rtm_content = re.sub(
    r"shell_exec\(\$command\);",
    "if (function_exists('shell_exec')) {\n            @shell_exec($command);\n        }",
    rtm_content
)

# Replace the else block for RTM
new_else_rtm = """        } else {
            // FALLBACK MANUAL CREATION
            Rtm::where('rps_id', $rps->id)->delete();
            $rtm = Rtm::create([
                'rps_id' => $rps->id,
                'nomor_dokumen' => 'UBSI/DA RTM.' . $rps->kode_matakuliah,
                'dosen_pengampu' => $rps->dosen_pengembang,
                'semester' => (int) ($rps->matakuliah?->semester ?: 1)
            ]);
            
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
            return redirect()->route('penyusunan-rtm.index')->with('success', 'Berhasil dibuat dalam Mode Manual (Server Anda tidak mendukung Auto-Extract PDF). Silakan lengkapi data RTM secara mandiri dengan mengklik Edit.');
        }"""

rtm_content = re.sub(
    r"\} else \{\s+\$errorMsg = .*?;\s+return redirect\(\)->route\('penyusunan-rtm\.index'\)->with\('error', \$errorMsg\);\s+\}",
    new_else_rtm,
    rtm_content,
    flags=re.DOTALL
)

with open('app/Http/Controllers/RtmController.php', 'w', encoding='utf-8') as f:
    f.write(rtm_content)


# PATCH SILABUS CONTROLLER
with open('app/Http/Controllers/SilabusController.php', 'r', encoding='utf-8') as f:
    silabus_content = f.read()

# Replace shell_exec
silabus_content = re.sub(
    r"shell_exec\(\$command\);",
    "if (function_exists('shell_exec')) {\n            @shell_exec($command);\n        }",
    silabus_content
)

# Replace the else block for Silabus
new_else_silabus = """        } else {
            // FALLBACK MANUAL CREATION
            Silabus::where('rps_id', $rps->id)->delete();
            
            $silabus = Silabus::create([
                'rps_id' => $rps->id,
                'kode_dokumen' => 'UBSI/DA/PNK.' . $rps->kode_matakuliah,
                'cpmk' => '',
                'sub_cpmk' => ''
            ]);
            
            SilabusMateri::create([
                'silabus_id' => $silabus->id,
                'pertemuan' => '1',
                'materi' => 'Materi Pertemuan 1 (Draft)'
            ]);
            
            return redirect()->route('penyusunan-silabus.index')->with('success', 'Berhasil dibuat dalam Mode Manual (Server Anda tidak mendukung Auto-Extract PDF). Silakan lengkapi data Silabus secara mandiri dengan mengklik Edit.');
        }"""

silabus_content = re.sub(
    r"\} else \{\s+\$errorMsg = .*?;\s+return redirect\(\)->route\('penyusunan-silabus\.index'\)->with\('error', \$errorMsg\);\s+\}",
    new_else_silabus,
    silabus_content,
    flags=re.DOTALL
)

with open('app/Http/Controllers/SilabusController.php', 'w', encoding='utf-8') as f:
    f.write(silabus_content)
