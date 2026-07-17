<?php
$content = file_get_contents('resources/views/obe/index.blade.php');

$old_select = '<select name="kriteria" id="ppepp_select_kriteria" class="form-select" required>
                            <option value="C1">C1: Visi, Misi, Tujuan & Strategi</option>
                            <option value="C2">C2: Tata Pamong & Kerjasama</option>
                            <option value="C3">C3: Mahasiswa</option>
                            <option value="C4">C4: Sumber Daya Manusia (SDM)</option>
                            <option value="C5">C5: Keuangan & Sarpras</option>
                            <option value="C6">C6: Pendidikan & Kurikulum</option>
                            <option value="C7">C7: Penelitian Dosen</option>
                            <option value="C8">C8: Pengabdian Masyarakat (PkM)</option>
                            <option value="C9">C9: Luaran & Capaian Tridharma</option>
                        </select>';

$new_select = '<select name="kriteria" id="ppepp_select_kriteria" class="form-select" required>
                            <option value="C1">C1: Budaya Mutu</option>
                            <option value="C2">C2: Relevansi Pendidikan</option>
                            <option value="C3">C3: Relevansi Penelitian</option>
                            <option value="C4">C4: Relevansi Pengabdian Kepada Masyarakat</option>
                            <option value="C5">C5: Akuntabilitas</option>
                            <option value="C6">C6: Diferensiasi Misi</option>
                        </select>';

$content = str_replace($old_select, $new_select, $content);

// Also need to update the PHP logic in ObePortalController if it iterates over C1-C9
file_put_contents('resources/views/obe/index.blade.php', $content);
echo "PPEPP options updated.";
