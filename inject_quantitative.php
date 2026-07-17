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

// 1. Get PPEPP Block for C1
// Starts at: <h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>
// Ends at: <!-- MODALS --> or <!-- MODAL: UPLOAD BAAK -->
$ppepp_html = extract_block($backup, '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>', '<!-- MODAL: UPLOAD BAAK -->');
// Clean up the header since we are putting it in C1 tab
$ppepp_html = str_replace('<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>', '', $ppepp_html);


// 2. Get CPL & Kurikulum Block for C2
// CPL starts at: <h4 class="mb-4 fw-bold">C.2 Relevansi Pendidikan (Capaian Pembelajaran)</h4>
// Ends at: <!-- KRITERIA C: ASESMEN & CQI -->
$c2_html = extract_block($backup, '<h4 class="mb-4 fw-bold">C.2 Relevansi Pendidikan (Capaian Pembelajaran)</h4>', '<!-- KRITERIA C: ASESMEN & CQI -->');


// 3. Get Mahasiswa Block for C2
// Starts at: <h4 class="mt-5 mb-4 fw-bold border-top pt-4">Data Mahasiswa</h4>
// Ends at: <!-- KRITERIA E: DOSEN & SUMBER DAYA -->
$mahasiswa_html = extract_block($backup, '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Data Mahasiswa</h4>', '<!-- KRITERIA E: DOSEN & SUMBER DAYA -->');
// Combine them
$c2_combined = $c2_html . '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Profil Lulusan & Data Mahasiswa</h4>' . $mahasiswa_html;


// 4. Get Dosen (SDM) Block for C5
// Starts at: <!-- KRITERIA E: DOSEN & SUMBER DAYA -->
// Ends at: <!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->
$dosen_raw = extract_block($backup, '<!-- KRITERIA E: DOSEN & SUMBER DAYA -->', '<!-- KRITERIA PPEPP: ACCREDITATION DOCUMENT CENTER -->');
// Clean up wrapper div if it exists
$dosen_raw = preg_replace('/<div class="tab-pane fade" id="tab-c5" role="tabpanel">/', '', $dosen_raw);
$dosen_html = preg_replace('/<h4 class="mb-4 fw-bold">C.5 Akuntabilitas \(SDM Dosen\)<\/h4>/', '', $dosen_raw);
// Fix any dangling closing divs at the end of dosen_html
$dosen_html = preg_replace('/<\/div>[\s]*$/', '', $dosen_html);


// 5. C3 and C4: In the backup, Penelitian & PkM were just tiny cards inside Dosen block.
// Let's create specific tables for C3 and C4.
$c3_html = '
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text text-primary me-2"></i>Data Penelitian Dosen Tetap (DT)</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Fitur Tabel Penelitian Dosen beserta sumber dana dan integrasinya ke mata kuliah akan ditampilkan secara rinci di sini berdasarkan data master. (Total: {{ $penelitianCount }} Penelitian)</div>
        </div>
    </div>
';

$c4_html = '
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-people text-primary me-2"></i>Data Pengabdian Kepada Masyarakat (PkM) Dosen Tetap</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Fitur Tabel PkM Dosen beserta sumber dana dan integrasinya ke mata kuliah akan ditampilkan secara rinci di sini berdasarkan data master. (Total: {{ $pkmCount }} PkM)</div>
        </div>
    </div>
';


// --- INJECTION ---
// Inject C1
$c1_target = '<i class="bi bi-tools text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold">Area Pengembangan</h5>
                        <p class="text-muted">Di sinilah kita akan membangun tabel Dokumen PPEPP dan Audit Mutu Internal yang baru & bersih.</p>';
$new = str_replace($c1_target, $ppepp_html, $new);

// Inject C2
$c2_target = '<i class="bi bi-tools text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold">Area Pengembangan</h5>
                        <p class="text-muted">Di sinilah kita akan merangkum tabel Kurikulum, Profil Lulusan, dan Seleksi Mahasiswa yang bebas dari tampilan membingungkan.</p>';
$new = str_replace($c2_target, $c2_combined, $new);

// Inject C3
$c3_target = '<i class="bi bi-tools text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold">Area Pengembangan</h5>
                        <p class="text-muted">Di sinilah kita akan menampilkan tabel Data Penelitian Dosen & Integrasi Pembelajaran.</p>';
$new = str_replace($c3_target, $c3_html, $new);

// Inject C4
$c4_target = '<i class="bi bi-tools text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold">Area Pengembangan</h5>
                        <p class="text-muted">Di sinilah kita akan menampilkan tabel Data Pengabdian Kepada Masyarakat (PkM) Dosen.</p>';
$new = str_replace($c4_target, $c4_html, $new);

// Inject C5
$c5_target = '<i class="bi bi-tools text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="fw-bold">Area Pengembangan</h5>
                        <p class="text-muted">Di sinilah kita akan meletakkan rekapitulasi data SDM (Dosen Tetap/Tidak Tetap), Keuangan, dan Sarpras.</p>';
$new = str_replace($c5_target, $dosen_html, $new);


// Append Modals and Scripts from Backup
$modals = extract_block($backup, '<!-- MODAL: UPLOAD BAAK -->', '@endsection');
$scripts = extract_block($backup, '@section(\'scripts\')', '@endsection');

// Remove current @endsection in new
$new = str_replace('@endsection', '', $new);

$new .= "\n<!-- MODALS -->\n<!-- MODAL: UPLOAD BAAK -->" . $modals;
$new .= "\n@section('scripts')" . $scripts . "\n@endsection";

file_put_contents('resources/views/obe/index.blade.php', $new);
echo "Injection successful.";
