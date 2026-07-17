<?php
$template = file_get_contents('ppepp_tbody_backup.html');

// Create a generator function that takes an array of kriteria to show
function make_ppepp($kriteriaArray, $template, $tabName) {
    // Replace the $kriteriaList array definition with just the ones we want
    $kListStr = "[\n";
    foreach ($kriteriaArray as $k => $v) {
        $kListStr .= "        '$k' => '$v',\n";
    }
    $kListStr .= "    ]";
    
    // Replace the definition in the template
    $html = preg_replace('/\$kriteriaList = \[.*?\];/s', '$kriteriaList = ' . $kListStr . ';', $template);
    
    return '
    <div class="card shadow-sm border-0 mt-4 mb-4">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks Siklus PPEPP - '.$tabName.'</h6>
            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" style="font-size: 0.85rem; min-width: 800px;">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 15%;">Komponen Matriks</th>
                            <th style="width: 17%;">P (Penetapan)</th>
                            <th style="width: 17%;">P (Pelaksanaan)</th>
                            <th style="width: 17%;">E (Evaluasi)</th>
                            <th style="width: 17%;">P (Pengendalian)</th>
                            <th style="width: 17%;">P (Peningkatan)</th>
                        </tr>
                    </thead>
                    <tbody>
' . $html . '
                    </tbody>
                </table>
            </div>
        </div>
    </div>';
}

$c1_ppepp = make_ppepp([
    'C1' => 'Visi, Misi, Tujuan & Strategi',
    'C2' => 'Tata Pamong & Kerjasama'
], $template, 'C.1 Budaya Mutu');

$c2_ppepp = make_ppepp([
    'C3' => 'Mahasiswa & Profil Lulusan',
    'C6' => 'Pendidikan & Kurikulum',
    'C9' => 'Luaran & Capaian Tridharma'
], $template, 'C.2 Relevansi Pendidikan');

$c3_ppepp = make_ppepp([
    'C7' => 'Penelitian Dosen'
], $template, 'C.3 Relevansi Penelitian');

$c4_ppepp = make_ppepp([
    'C8' => 'Pengabdian Masyarakat (PkM)'
], $template, 'C.4 Relevansi PkM');

$c5_ppepp = make_ppepp([
    'C4' => 'Sumber Daya Manusia (SDM)',
    'C5' => 'Keuangan & Sarpras'
], $template, 'C.5 Akuntabilitas');

$c6_ppepp = make_ppepp([
    'C1' => 'Diferensiasi Misi (Spesifik)'
], $template, 'C.6 Diferensiasi Misi');

// Now replace the placeholder static PPEPP in index.blade.php with these dynamic ones
$index = file_get_contents('resources/views/obe/index.blade.php');

$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.1 Budaya Mutu.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c1_ppepp, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.2 Relevansi Pendidikan.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c2_ppepp, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.3 Relevansi Penelitian.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c3_ppepp, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.4 Relevansi PkM.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c4_ppepp, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.5 Akuntabilitas.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c5_ppepp, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.6 Diferensiasi Misi.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c6_ppepp, $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Dynamic PPEPP tables injected.";
