<?php
$content = file_get_contents('resources/views/obe/index.blade.php');

// Rename kriteria-c (Asesmen & CQI) and kriteria-ppepp (PPEPP) into tab-c1
$content = str_replace('<div class="tab-pane fade" id="kriteria-c" role="tabpanel">', '<div class="tab-pane fade show active" id="tab-c1" role="tabpanel"><h4 class="mb-4 fw-bold">Budaya Mutu (Asesmen & CQI)</h4>', $content);
$content = str_replace('<div class="tab-pane fade" id="kriteria-ppepp" role="tabpanel">', '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Siklus PPEPP</h4>', $content); // Merge PPEPP into tab-c1

// Rename kriteria-a (CPL) into tab-c2
$content = str_replace('<div class="tab-pane fade show active" id="kriteria-a" role="tabpanel">', '<div class="tab-pane fade" id="tab-c2" role="tabpanel"><h4 class="mb-4 fw-bold">C.2 Relevansi Pendidikan (Capaian Pembelajaran)</h4>', $content);
// Rename kriteria-b (Kurikulum) into a section in tab-c2
$content = str_replace('<div class="tab-pane fade" id="kriteria-b" role="tabpanel">', '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Struktur Kurikulum & Matakuliah</h4>', $content);
// Rename kriteria-d (Mahasiswa) into a section in tab-c2
$content = str_replace('<div class="tab-pane fade" id="kriteria-d" role="tabpanel">', '<h4 class="mt-5 mb-4 fw-bold border-top pt-4">Data Mahasiswa</h4>', $content);

// Move kriteria-e (SDM) into tab-c5
$content = str_replace('<div class="tab-pane fade" id="kriteria-e" role="tabpanel">', '<div class="tab-pane fade" id="tab-c5" role="tabpanel"><h4 class="mb-4 fw-bold">C.5 Akuntabilitas (SDM Dosen)</h4>', $content);

// Add empty tabs for tab-a, tab-b, tab-c3, tab-c4, tab-c6, tab-d at the end of tab-content
$emptyTabs = '
    <!-- Empty Tabs -->
    <div class="tab-pane fade" id="tab-a" role="tabpanel">
        <h4 class="fw-bold">A. Kondisi Eksternal</h4>
        <p class="text-muted">Data kondisi eksternal akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-b" role="tabpanel">
        <h4 class="fw-bold">B. Profil UPPS</h4>
        <p class="text-muted">Data profil UPPS akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-c3" role="tabpanel">
        <h4 class="fw-bold">C.3 Relevansi Penelitian</h4>
        <p class="text-muted">Data integrasi penelitian akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-c4" role="tabpanel">
        <h4 class="fw-bold">C.4 Relevansi PkM</h4>
        <p class="text-muted">Data integrasi pengabdian masyarakat akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-c6" role="tabpanel">
        <h4 class="fw-bold">C.6 Diferensiasi Misi</h4>
        <p class="text-muted">Data diferensiasi misi akan ditampilkan di sini.</p>
    </div>
    <div class="tab-pane fade" id="tab-d" role="tabpanel">
        <h4 class="fw-bold">D. Suplemen</h4>
        <p class="text-muted">Data suplemen program studi akan ditampilkan di sini.</p>
    </div>
</div>
<!-- Modal Upload File BAAK -->
';
$content = str_replace('</div>

<!-- Modal Upload File BAAK -->', $emptyTabs, $content);

file_put_contents('resources/views/obe/index.blade.php', $content);
echo "Tabs restructured successfully.";
