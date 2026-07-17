<?php
$index = file_get_contents('resources/views/obe/index.blade.php');

// We will extract EVERYTHING EXCEPT the tab contents for C1-C6.
// C1-C6 will be completely overwritten with clean PPEPP.
$top = explode('<div class="tab-pane fade" id="tab-c1"', $index)[0];

$bottom = explode('<div class="tab-pane fade" id="tab-d"', $index)[1];
$bottom = '<div class="tab-pane fade" id="tab-d"' . $bottom;

function make_ppepp($title, $id, $cqi_btn = false) {
    $cqi_html = '';
    if ($cqi_btn) {
        $cqi_html = '<a href="#" class="btn btn-sm btn-primary me-2"><i class="bi bi-pencil-square me-1"></i>Input Perbaikan Mutu (CQI)</a>';
    }
    
    return '
            <div class="tab-pane fade" id="tab-'.$id.'" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks PPEPP - '.$title.'</h6>
                        <div>
                            '.$cqi_html.'
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
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "'.strtoupper($id).'", "pCode" => "P1", "kName" => "'.$title.'", "pName" => "Penetapan"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "'.strtoupper($id).'", "pCode" => "P2", "kName" => "'.$title.'", "pName" => "Pelaksanaan"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "'.strtoupper($id).'", "pCode" => "P3", "kName" => "'.$title.'", "pName" => "Evaluasi"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "'.strtoupper($id).'", "pCode" => "P4", "kName" => "'.$title.'", "pName" => "Pengendalian"])</td>
                                        <td>@include("obe.partials.ppepp_docs", ["kCode" => "'.strtoupper($id).'", "pCode" => "P5", "kName" => "'.$title.'", "pName" => "Peningkatan"])</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
';
}

$c1 = make_ppepp('C.1 Budaya Mutu', 'c1', true);
$c2 = make_ppepp('C.2 Relevansi Pendidikan', 'c2');
$c3 = make_ppepp('C.3 Relevansi Penelitian', 'c3');
$c4 = make_ppepp('C.4 Relevansi PkM', 'c4');
$c5 = make_ppepp('C.5 Akuntabilitas', 'c5');
$c6 = make_ppepp('C.6 Diferensiasi Misi', 'c6');

$new_index = $top . $c1 . $c2 . $c3 . $c4 . $c5 . $c6 . $bottom;
file_put_contents('resources/views/obe/index.blade.php', $new_index);
echo "Hard rewrite completed.";
