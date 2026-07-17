<?php
// Function to generate the correct PPEPP table for each new criteria
function generate_clean_ppepp($kriteria_id, $title, $badges_logic) {
    return '
    <div class="card shadow-sm border-0 mt-4 mb-4">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks Siklus PPEPP - '.$title.'</h6>
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
                        <tr>
                            <td class="fw-bold bg-light">'.$title.'</td>
                            
                            <!-- PENETAPAN -->
                            <td>
                                '.$badges_logic['P1'].'
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P1", "kName" => "'.$title.'", "pName" => "Penetapan"])
                            </td>
                            
                            <!-- PELAKSANAAN -->
                            <td>
                                '.$badges_logic['P2'].'
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P2", "kName" => "'.$title.'", "pName" => "Pelaksanaan"])
                            </td>
                            
                            <!-- EVALUASI -->
                            <td>
                                '.$badges_logic['P3'].'
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P3", "kName" => "'.$title.'", "pName" => "Evaluasi"])
                            </td>
                            
                            <!-- PENGENDALIAN -->
                            <td>
                                '.$badges_logic['P4'].'
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P4", "kName" => "'.$title.'", "pName" => "Pengendalian"])
                            </td>
                            
                            <!-- PENINGKATAN -->
                            <td>
                                '.$badges_logic['P5'].'
                                @include("obe.partials.ppepp_docs", ["kCode" => "'.$kriteria_id.'", "pCode" => "P5", "kName" => "'.$title.'", "pName" => "Peningkatan"])
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>';
}

$c1_badges = [
    'P1' => '',
    'P2' => '',
    'P3' => '
        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-chat-square-heart me-1"></i>{{ $surveiKepuasanCount ?? 0 }} Survei Kepuasan</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'Survei\', \'ppepp\' => \'P3\']) }}" target="_blank" class="badge bg-danger text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
            <span><i class="bi bi-graph-up me-1"></i>Evaluasi CPL (Audit Mutu)</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C1\', \'ppepp\' => \'P3\']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
        </div>
    ',
    'P4' => '',
    'P5' => '
        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-arrow-repeat me-1"></i>Live: {{ isset($cqiLogs) ? $cqiLogs->count() : 0 }} CQI Logs</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C1\', \'ppepp\' => \'P5\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
        </div>
    '
];

$c2_badges = [
    'P1' => '
        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-graph-up-arrow me-1"></i>Tren PMB: {{ $pmbCount ?? 0 }} Thn</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P1\']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-file-pdf me-1"></i>Buku Kurikulum</span>
            <a href="/buku kurikulum.pdf" target="_blank" class="badge bg-success text-decoration-none">Buka</a>
        </div>
    ',
    'P2' => '
        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-people-fill me-1"></i>Live: {{ $jumlahMahasiswa ?? 0 }} Mhs</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P2_Mhs\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-file-earmark-check me-1"></i>Live: {{ $totalRps ?? 0 }} RPS</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P2_Rps\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
        </div>
    ',
    'P3' => '
        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
            <span><i class="bi bi-graph-up me-1"></i>Rerata IPK: {{ $avgIpk ?? 0 }}</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P3\']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
        </div>
    ',
    'P4' => '
        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
            <span><i class="bi bi-briefcase me-1"></i>Tracer Study Alumni</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P4_Tracer\']) }}" target="_blank" class="badge bg-success text-white text-decoration-none">PDF</a>
        </div>
    ',
    'P5' => '
        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
            <span><i class="bi bi-journal-code me-1"></i>{{ $capstoneCount ?? 0 }} Capstone/TA</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C2\', \'ppepp\' => \'P5\']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
        </div>
    '
];

$c3_badges = [
    'P1' => '',
    'P2' => '
        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-search me-1"></i>{{ $penelitianCount ?? 0 }} Penelitian</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C3\', \'ppepp\' => \'P2\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-warning border-0 p-1 mb-2 small d-flex justify-content-between align-items-center text-dark" style="border-radius: 6px;">
            <span><i class="bi bi-cash-coin me-1"></i>{{ $hibahCount ?? 0 }} Hibah Penelitian</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C3\', \'ppepp\' => \'P2_Hibah\']) }}" target="_blank" class="badge bg-warning text-dark text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPenelitianCount ?? 0 }} Integrasi RPS</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C3\', \'ppepp\' => \'Integrasi\']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
        </div>
    ',
    'P3' => '',
    'P4' => '',
    'P5' => ''
];

$c4_badges = [
    'P1' => '',
    'P2' => '
        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-heart-pulse me-1"></i>{{ $pkmCount ?? 0 }} PkM Dosen</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C4\', \'ppepp\' => \'P2\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-secondary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-link-45deg me-1"></i>{{ $integrasiPkmCount ?? 0 }} Integrasi RPS</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C4\', \'ppepp\' => \'Integrasi\']) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none">PDF</a>
        </div>
    ',
    'P3' => '',
    'P4' => '',
    'P5' => ''
];

$c5_badges = [
    'P1' => '',
    'P2' => '
        <div class="alert alert-primary border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-person-video3 me-1"></i>Live: {{ $jumlahDosen ?? 0 }} Dosen</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P2_Dosen\']) }}" target="_blank" class="badge bg-primary text-decoration-none">PDF</a>
        </div>
        @php
            $totalDanaKeuangan = \App\Models\KeuanganProdi::sum(\'dana_pendidikan\') + \App\Models\KeuanganProdi::sum(\'dana_penelitian\') + \App\Models\KeuanganProdi::sum(\'dana_pkm\') + \App\Models\KeuanganProdi::sum(\'dana_investasi\');
            $totalDanaFormat = $totalDanaKeuangan >= 1000000000 ? \'Rp \' . number_format($totalDanaKeuangan/1000000000, 1) . \'M\' : ($totalDanaKeuangan >= 1000000 ? \'Rp \' . number_format($totalDanaKeuangan/1000000, 0) . \' Jt\' : \'Rp 0\');
        @endphp
        <div class="alert alert-success border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-wallet2 me-1"></i>Dana: {{ $totalDanaFormat }}</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P2_Dana\']) }}" target="_blank" class="badge bg-success text-decoration-none">PDF</a>
        </div>
        <div class="alert alert-info border-0 p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
            <span><i class="bi bi-file-earmark-text me-1"></i>{{ $pksCount ?? 0 }} PKS & MoA</span>
            <a href="{{ route(\'obe.pdf-recap\', [\'kriteria\' => \'C5\', \'ppepp\' => \'P2_MoA\']) }}" target="_blank" class="badge bg-info text-decoration-none">PDF</a>
        </div>
    ',
    'P3' => '',
    'P4' => '',
    'P5' => ''
];

$c6_badges = [
    'P1' => '',
    'P2' => '',
    'P3' => '',
    'P4' => '',
    'P5' => ''
];

$c1_html = generate_clean_ppepp('C1', 'C.1 Budaya Mutu', $c1_badges);
$c2_html = generate_clean_ppepp('C2', 'C.2 Relevansi Pendidikan', $c2_badges);
$c3_html = generate_clean_ppepp('C3', 'C.3 Relevansi Penelitian', $c3_badges);
$c4_html = generate_clean_ppepp('C4', 'C.4 Relevansi PkM', $c4_badges);
$c5_html = generate_clean_ppepp('C5', 'C.5 Akuntabilitas', $c5_badges);
$c6_html = generate_clean_ppepp('C6', 'C.6 Diferensiasi Misi', $c6_badges);

// Replace in index.blade.php
$index = file_get_contents('resources/views/obe/index.blade.php');

// Regex replace the entire card for each Matriks Siklus PPEPP
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.1 Budaya Mutu.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c1_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.2 Relevansi Pendidikan.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c2_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.3 Relevansi Penelitian.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c3_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.4 Relevansi PkM.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c4_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.5 Akuntabilitas.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c5_html, $index);
$index = preg_replace('/<div class="card shadow-sm border-0 mt-4 mb-4">.*?Matriks Siklus PPEPP - C\.6 Diferensiasi Misi.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>/s', $c6_html, $index);

file_put_contents('resources/views/obe/index.blade.php', $index);
echo "Clean rewrite completed.";
