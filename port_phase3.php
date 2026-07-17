<?php
$backup = file_get_contents('resources/views/obe/index_backup.blade.php');
$new = file_get_contents('resources/views/obe/index.blade.php');

function get_between($content, $start, $end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return '';
}

// 1. Extract PPEPP Matrix (for C1)
// In backup, it starts with <!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->
// Ends right before the Modals (or end of file). Let's extract by string search.
$ppepp_section = get_between($backup, '<!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->', '<!-- MODAL: UPLOAD BAAK -->');
if (!$ppepp_section) {
    // try fallback
    $ppepp_section = get_between($backup, '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>', '<!-- MODAL: UPLOAD BAAK -->');
}
// Clean up the h4
$ppepp_section = str_replace('<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>', '', $ppepp_section);


// 2. Extract CPL/OBE Dashboard (for C2)
// Starts with <!-- KRITERIA A: CPL -->
// Ends with <!-- KRITERIA B: KURIKULUM -->
$cpl_section = get_between($backup, '<!-- KRITERIA A: CPL -->', '<!-- KRITERIA B: KURIKULUM -->');
// Clean up div wrappers
$cpl_section = preg_replace('/<div class="tab-pane fade" id="tab-c2" role="tabpanel">/', '', $cpl_section, 1);
$cpl_section = preg_replace('/<h4 class="mb-4 fw-bold">C.2 Relevansi Pendidikan \(Capaian Pembelajaran\)<\/h4>/', '', $cpl_section, 1);

// 3. Extract Kurikulum (for C2)
$kurikulum_section = get_between($backup, '<!-- KRITERIA B: KURIKULUM -->', '<!-- KRITERIA C: ASESMEN & CQI -->');

// 4. Extract Mahasiswa (for C2)
$mahasiswa_section = get_between($backup, '<!-- KRITERIA D: MAHASISWA -->', '<!-- KRITERIA E: DOSEN & SUMBER DAYA -->');

// 5. Extract Dosen/Akuntabilitas (for C5)
$dosen_section = get_between($backup, '<!-- KRITERIA E: DOSEN & SUMBER DAYA -->', '<!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->');
$dosen_section = preg_replace('/<div class="tab-pane fade" id="tab-c5" role="tabpanel">/', '', $dosen_section, 1);
$dosen_section = preg_replace('/<h4 class="mb-4 fw-bold">C.5 Akuntabilitas \(SDM Dosen\)<\/h4>/', '', $dosen_section, 1);


// Now inject into the new layout
$c1_placeholder = '<div class="alert alert-info">
                    <h4>C.1 Budaya Mutu</h4>
                    <p>Ruang untuk Matriks PPEPP dan Audit Mutu Internal akan dibangun di sini.</p>
                </div>';
$new = str_replace($c1_placeholder, $ppepp_section, $new);

$c2_placeholder = '<div class="alert alert-info">
                    <h4>C.2 Relevansi Pendidikan</h4>
                    <p>Ruang untuk Dasbor OBE, Kurikulum, dan Data Mahasiswa akan dibangun di sini.</p>
                </div>';
$c2_combined = $cpl_section . $kurikulum_section . $mahasiswa_section;
// fix the closing div from CPL extraction
$c2_combined = str_replace('</div>

    <!-- KRITERIA B: KURIKULUM -->', '', $c2_combined);
$new = str_replace($c2_placeholder, $c2_combined, $new);

$c5_placeholder = '<div class="alert alert-info">
                    <h4>C.5 Akuntabilitas</h4>
                    <p>Ruang untuk Data Dosen (SDM), Keuangan, dan Lulusan akan dibangun di sini.</p>
                </div>';
$new = str_replace($c5_placeholder, $dosen_section, $new);

// Add the modals back at the end of the content section
$modals = get_between($backup, '<!-- MODAL: UPLOAD BAAK -->', '@endsection');
$new = str_replace('@endsection', '<!-- MODALS -->
<!-- MODAL: UPLOAD BAAK -->' . $modals . "\n@endsection", $new);

// Fix scripts if any
$scripts = get_between($backup, '@section(\'scripts\')', '@endsection');
if ($scripts) {
    $new .= "\n@section('scripts')" . $scripts . "@endsection";
}

file_put_contents('resources/views/obe/index.blade.php', $new);
echo "Phase 3 Porting Complete.";
