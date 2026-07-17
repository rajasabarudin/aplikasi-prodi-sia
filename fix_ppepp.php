<?php
$backup = file_get_contents('resources/views/obe/index_backup.blade.php');
$new = file_get_contents('resources/views/obe/index.blade.php');

function extract_block($content, $startMarker, $endMarker) {
    $parts = explode($startMarker, $content);
    if (count($parts) > 1) {
        $subparts = explode($endMarker, $parts[1]);
        return $subparts[0];
    }
    return '';
}

// Extract Surveys & Tracer Study from backup
$tracer_html = extract_block($backup, '<!-- C5: Tracer Study -->', '<!-- C6: Kepuasan Stakeholder -->');
// Clean up wrapper
$tracer_html = preg_replace('/<div class="tab-pane fade" id="sub-c5" role="tabpanel">/', '', $tracer_html);
$tracer_html = preg_replace('/<\/div>\s*$/', '', $tracer_html);

$survey_html = extract_block($backup, '<!-- C6: Kepuasan Stakeholder -->', '<!-- KRITERIA D: MAHASISWA -->');
$survey_html = preg_replace('/<div class="tab-pane fade" id="sub-c6" role="tabpanel">/', '', $survey_html);
$survey_html = preg_replace('/<\/div>\s*<\/div>\s*<\/div>\s*$/', '</div></div>', $survey_html);

// Extract CPL, Kurikulum, Mahasiswa, Dosen, Penelitian, PkM as before
$cpl_section = extract_block($backup, '<!-- KRITERIA A: CPL -->', '<!-- KRITERIA B: KURIKULUM -->');
$cpl_section = preg_replace('/<div class="tab-pane fade" id="tab-c2" role="tabpanel">/', '', $cpl_section);
$cpl_section = preg_replace('/<h4 class="mb-4 fw-bold">C.2 Relevansi Pendidikan \(Capaian Pembelajaran\)<\/h4>/', '', $cpl_section);

$kurikulum_section = extract_block($backup, '<!-- KRITERIA B: KURIKULUM -->', '<!-- KRITERIA C: ASESMEN & CQI -->');
$mahasiswa_section = extract_block($backup, '<!-- KRITERIA D: MAHASISWA -->', '<!-- KRITERIA E: DOSEN & SUMBER DAYA -->');

$dosen_raw = extract_block($backup, '<!-- KRITERIA E: DOSEN & SUMBER DAYA -->', '<!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->');
$dosen_raw = preg_replace('/<div class="tab-pane fade" id="tab-c5" role="tabpanel">/', '', $dosen_raw);
$dosen_html = preg_replace('/<h4 class="mb-4 fw-bold">C.5 Akuntabilitas \(SDM Dosen\)<\/h4>/', '', $dosen_raw);
$dosen_html = preg_replace('/<\/div>\s*$/', '', $dosen_html);

// Now, the user wants PPEPP in EVERY criteria from C1 to C6.
// Let's create a reusable PPEPP UI template.
function make_ppepp_ui($kriteria_name) {
    return '
    <div class="card shadow-sm border-0 mt-4 mb-4">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-grid-3x3-gap text-warning me-2"></i>Matriks Siklus PPEPP - '.$kriteria_name.'</h6>
            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUploadPpepp">
                <i class="bi bi-upload me-1"></i>Unggah Dokumen Mutu
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center" style="font-size: 0.85rem;">
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
                            <td><span class="text-muted fst-italic">Belum ada dokumen penetapan</span></td>
                            <td><span class="text-muted fst-italic">Belum ada dokumen pelaksanaan</span></td>
                            <td><span class="text-muted fst-italic">Belum ada dokumen evaluasi</span></td>
                            <td><span class="text-muted fst-italic">Belum ada dokumen pengendalian</span></td>
                            <td><span class="text-muted fst-italic">Belum ada dokumen peningkatan</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>';
}

// Build Tabs Content
// C1: Budaya Mutu
// Let's just put the CQI logs here, and the PPEPP table.
$cqi_html = extract_block($backup, '<!-- C3: Siklus CQI -->', '<!-- C4: Portofolio & Serkom -->');
$cqi_html = preg_replace('/<div class="tab-pane fade" id="sub-c3" role="tabpanel">/', '', $cqi_html);
$cqi_html = preg_replace('/<\/div>\s*$/', '', $cqi_html);

$c1_content = $cqi_html . make_ppepp_ui('C.1 Budaya Mutu');

// C2: Relevansi Pendidikan
// CPL, Kurikulum, Mahasiswa, Tracer, Survey, PPEPP
$c2_content = $cpl_section . $kurikulum_section . $mahasiswa_section . '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Data Survei & Lulusan</h4>' . $tracer_html . $survey_html . make_ppepp_ui('C.2 Relevansi Pendidikan');

// C3: Relevansi Penelitian
$c3_content = '
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3 border-bottom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text text-primary me-2"></i>Data Penelitian Dosen Tetap (DT)</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Fitur Tabel Penelitian Dosen beserta sumber dana dan integrasinya ke mata kuliah akan ditampilkan secara rinci di sini. (Total: {{ $penelitianCount }} Penelitian)</div>
        </div>
    </div>' . make_ppepp_ui('C.3 Relevansi Penelitian');

// C4: Relevansi PkM
$c4_content = '
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3 border-bottom">
            <h5 class="mb-0 fw-bold"><i class="bi bi-people text-primary me-2"></i>Data Pengabdian Kepada Masyarakat (PkM) Dosen Tetap</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Fitur Tabel PkM Dosen beserta sumber dana dan integrasinya ke mata kuliah akan ditampilkan secara rinci di sini. (Total: {{ $pkmCount }} PkM)</div>
        </div>
    </div>' . make_ppepp_ui('C.4 Relevansi PkM');

// C5: Akuntabilitas
$c5_content = $dosen_html . make_ppepp_ui('C.5 Akuntabilitas');

// C6: Diferensiasi Misi
// Keep the narrative form, add PPEPP
$c6_content = '
<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-stars me-2 text-warning"></i>Kriteria C.6: Diferensiasi Misi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route(\'obe.save-narrative\') }}" method="POST">
            @csrf
            <input type="hidden" name="kriteria_kode" value="C6">
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="12" placeholder="Ketik narasi Diferensiasi Misi di sini...">{{ $narratives[\'C6\'] ?? \'\' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary rounded-3 px-4"><i class="bi bi-save me-2"></i>Simpan Narasi</button>
        </form>
    </div>
</div>' . make_ppepp_ui('C.6 Diferensiasi Misi');


// Build the new index.blade.php from scratch (cleaner)
$final_html = <<<HTML
@extends('layouts.app')

@section('title', 'OBE Accreditation Portal')

@section('content')
<style>
    .obe-tab {
        border-radius: 8px 8px 0 0 !important;
        font-weight: 600;
        color: #475569 !important;
        border: none !important;
        padding: 12px 20px !important;
        background: #f1f5f9;
        margin-right: 4px;
        transition: all 0.3s ease;
    }
    .obe-tab:hover {
        background: #e2e8f0;
    }
    .obe-tab.active {
        background: #3b82f6 !important;
        color: #ffffff !important;
        box-shadow: 0 -4px 10px rgba(59, 130, 246, 0.2) !important;
    }
    .nav-tabs {
        border-bottom: 3px solid #3b82f6;
    }
    .bg-gradient-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important; }
    .bg-gradient-green { background: linear-gradient(135deg, #10b981, #059669) !important; }
    .bg-gradient-orange { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
</style>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-shield-check text-primary me-2"></i>Portal Evaluasi Diri (LED) & LKPS</h3>
            <p class="text-muted mb-0">Instrumen Akreditasi LAM INFOKOM (Standar 6 Kriteria Baru)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('obe.cetak') }}" target="_blank" class="btn btn-dark shadow-sm rounded-3">
                <i class="bi bi-printer text-warning me-2"></i>Cetak Laporan
            </a>
            <button type="button" class="btn btn-success shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalImportBaak">
                <i class="bi bi-file-earmark-excel me-2"></i>Upload File BAAK
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div style="overflow-x: auto; white-space: nowrap;">
            <ul class="nav nav-tabs border-0 flex-nowrap mb-4" id="obeTab" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab active" id="tab-a-btn" data-bs-toggle="tab" data-bs-target="#tab-a" type="button" role="tab">A. Kondisi Eksternal</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-b-btn" data-bs-toggle="tab" data-bs-target="#tab-b" type="button" role="tab">B. Profil UPPS</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c1-btn" data-bs-toggle="tab" data-bs-target="#tab-c1" type="button" role="tab">C.1 Budaya Mutu</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c2-btn" data-bs-toggle="tab" data-bs-target="#tab-c2" type="button" role="tab">C.2 Rel. Pendidikan</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c3-btn" data-bs-toggle="tab" data-bs-target="#tab-c3" type="button" role="tab">C.3 Penelitian</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c4-btn" data-bs-toggle="tab" data-bs-target="#tab-c4" type="button" role="tab">C.4 PkM</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c5-btn" data-bs-toggle="tab" data-bs-target="#tab-c5" type="button" role="tab">C.5 Akuntabilitas</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-c6-btn" data-bs-toggle="tab" data-bs-target="#tab-c6" type="button" role="tab">C.6 Diferensiasi</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link obe-tab" id="tab-d-btn" data-bs-toggle="tab" data-bs-target="#tab-d" type="button" role="tab">D. Suplemen</button></li>
            </ul>
        </div>

        <div class="tab-content" id="obeTabContent">
            
            <div class="tab-pane fade show active" id="tab-a" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-globe me-2 text-warning"></i>Kriteria A: Kondisi Eksternal</h5></div>
                    <div class="card-body">
                        <form action="{{ route('obe.save-narrative') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kriteria_kode" value="A">
                            <div class="mb-3"><textarea name="content" class="form-control" rows="12">{{ \$narratives['A'] ?? '' }}</textarea></div>
                            <button type="submit" class="btn btn-primary rounded-3 px-4"><i class="bi bi-save me-2"></i>Simpan Narasi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-b" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-warning"></i>Kriteria B: Profil UPPS</h5></div>
                    <div class="card-body">
                        <form action="{{ route('obe.save-narrative') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kriteria_kode" value="B">
                            <div class="mb-3"><textarea name="content" class="form-control" rows="12">{{ \$narratives['B'] ?? '' }}</textarea></div>
                            <button type="submit" class="btn btn-primary rounded-3 px-4"><i class="bi bi-save me-2"></i>Simpan Narasi</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-c1" role="tabpanel">$c1_content</div>
            <div class="tab-pane fade" id="tab-c2" role="tabpanel">$c2_content</div>
            <div class="tab-pane fade" id="tab-c3" role="tabpanel">$c3_content</div>
            <div class="tab-pane fade" id="tab-c4" role="tabpanel">$c4_content</div>
            <div class="tab-pane fade" id="tab-c5" role="tabpanel">$c5_content</div>
            <div class="tab-pane fade" id="tab-c6" role="tabpanel">$c6_content</div>

            <div class="tab-pane fade" id="tab-d" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3"><h5 class="mb-0 fw-bold"><i class="bi bi-plus-square me-2 text-warning"></i>Kriteria D: Suplemen</h5></div>
                    <div class="card-body">
                        <form action="{{ route('obe.save-narrative') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kriteria_kode" value="D">
                            <div class="mb-3"><textarea name="content" class="form-control" rows="12">{{ \$narratives['D'] ?? '' }}</textarea></div>
                            <button type="submit" class="btn btn-primary rounded-3 px-4"><i class="bi bi-save me-2"></i>Simpan Narasi</button>
                        </form>
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div>

<!-- MODALS -->
<?php
// Extracted modals and scripts
\$modals = <<<'MODAL'
$modals
MODAL;
\$scripts = <<<'SCRIPT'
$scripts
SCRIPT;
echo \$modals;
?>

@section('scripts')
<?php echo \$scripts; ?>
@endsection

@endsection
HTML;

file_put_contents('resources/views/obe/index.blade.php', $final_html);
echo "Perfect injection completed.";
