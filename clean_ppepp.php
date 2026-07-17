<?php
// Function to generate a blank 5-column PPEPP table
function generate_clean_ppepp($kriteria_id, $title, $show_cqi_btn = false) {
    $cqi_btn = '';
    if ($show_cqi_btn) {
        // Add CQI button next to upload document
        $cqi_btn = '<a href="#" class="btn btn-sm btn-primary me-2"><i class="bi bi-pencil-square me-1"></i>Input Perbaikan Mutu (CQI)</a>';
    }

    return '
    <div class="card shadow-sm border-0 mt-4 mb-4">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - '.$title.'</h6>
            <div>
                '.$cqi_btn.'
                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                    <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.85rem; min-width: 800px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">P (Penetapan)</th>
                            <th style="width: 20%;">P (Pelaksanaan)</th>
                            <th style="width: 20%;">E (Evaluasi)</th>
                            <th style="width: 20%;">P (Pengendalian)</th>
                            <th style="width: 20%;">P (Peningkatan)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- PENETAPAN -->
                            <td>
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P1", "kName" => "'.$title.'", "pName" => "Penetapan"])
                            </td>
                            
                            <!-- PELAKSANAAN -->
                            <td>
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P2", "kName" => "'.$title.'", "pName" => "Pelaksanaan"])
                            </td>
                            
                            <!-- EVALUASI -->
                            <td>
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P3", "kName" => "'.$title.'", "pName" => "Evaluasi"])
                            </td>
                            
                            <!-- PENGENDALIAN -->
                            <td>
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P4", "kName" => "'.$title.'", "pName" => "Pengendalian"])
                            </td>
                            
                            <!-- PENINGKATAN -->
                            <td>
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P5", "kName" => "'.$title.'", "pName" => "Peningkatan"])
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>';
}

$c1_html = generate_clean_ppepp('C1', 'C.1 Budaya Mutu', true); // Show CQI button in C1
$c2_html = generate_clean_ppepp('C2', 'C.2 Relevansi Pendidikan');
$c3_html = generate_clean_ppepp('C3', 'C.3 Relevansi Penelitian');
$c4_html = generate_clean_ppepp('C4', 'C.4 Relevansi PkM');
$c5_html = generate_clean_ppepp('C5', 'C.5 Akuntabilitas');
$c6_html = generate_clean_ppepp('C6', 'C.6 Diferensiasi Misi');

// Replace in index.blade.php
$index = file_get_contents('resources/views/obe/index.blade.php');

// Regex replace the entire card for each Matriks PPEPP
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.1 Budaya Mutu.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c1_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.2 Relevansi Pendidikan.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c2_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.3 Relevansi Penelitian.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c3_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.4 Relevansi PkM.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c4_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.5 Akuntabilitas.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c5_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.6 Diferensiasi Misi.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c6_html, $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Blank 5-column PPEPP injected.";
