<?php
$content = file_get_contents('resources/views/obe/index.blade.php');

$old_kriteria_list = "\$kriteriaList = [
                                      'C1' => 'C1: Visi, Misi, Tujuan & Strategi',
                                      'C2' => 'C2: Tata Pamong & Kerjasama',
                                      'C3' => 'C3: Mahasiswa',
                                      'C4' => 'C4: Sumber Daya Manusia (SDM)',
                                      'C5' => 'C5: Keuangan & Sarpras',
                                      'C6' => 'C6: Pendidikan & Kurikulum',
                                      'C7' => 'C7: Penelitian Dosen',
                                      'C8' => 'C8: Pengabdian Masyarakat (PkM)',
                                      'C9' => 'C9: Luaran & Capaian Tridharma'
                                  ];";

$new_kriteria_list = "\$kriteriaList = [
                                      'C1' => 'C1: Budaya Mutu',
                                      'C2' => 'C2: Relevansi Pendidikan',
                                      'C3' => 'C3: Relevansi Penelitian',
                                      'C4' => 'C4: Relevansi Pengabdian Kepada Masyarakat',
                                      'C5' => 'C5: Akuntabilitas',
                                      'C6' => 'C6: Diferensiasi Misi'
                                  ];";

$content = str_replace($old_kriteria_list, $new_kriteria_list, $content);
file_put_contents('resources/views/obe/index.blade.php', $content);
echo "Grid updated.";
