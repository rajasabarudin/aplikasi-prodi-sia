<?php
$content = file_get_contents('resources/views/obe/index.blade.php');

$empty_a = '<div class="tab-pane fade" id="tab-a" role="tabpanel">
        <h4 class="fw-bold">A. Kondisi Eksternal</h4>
        <p class="text-muted">Data kondisi eksternal akan ditampilkan di sini.</p>
    </div>';

$form_a = '<div class="tab-pane fade" id="tab-a" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-globe me-2 text-warning"></i>Kriteria A: Kondisi Eksternal</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Lingkungan makro dan mikro yang memengaruhi keberadaan dan pengembangan program studi (Misal: aspek politik, ekonomi, kebijakan, sosial, budaya, dll).</p>
                <form action="{{ route(\'obe.save-narrative\') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="A">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Kondisi Eksternal di sini...">{{ $narratives[\'A\'] ?? \'\' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>';

$empty_b = '<div class="tab-pane fade" id="tab-b" role="tabpanel">
        <h4 class="fw-bold">B. Profil UPPS</h4>
        <p class="text-muted">Data profil UPPS akan ditampilkan di sini.</p>
    </div>';

$form_b = '<div class="tab-pane fade" id="tab-b" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-warning"></i>Kriteria B: Profil UPPS & Program Studi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Sejarah, visi, misi, tujuan, sasaran, dan struktur organisasi Unit Pengelola Program Studi (UPPS) serta Program Studi.</p>
                <form action="{{ route(\'obe.save-narrative\') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="B">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Profil UPPS di sini...">{{ $narratives[\'B\'] ?? \'\' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>';

$empty_c6 = '<div class="tab-pane fade" id="tab-c6" role="tabpanel">
        <h4 class="fw-bold">C.6 Diferensiasi Misi</h4>
        <p class="text-muted">Data diferensiasi misi akan ditampilkan di sini.</p>
    </div>';

$form_c6 = '<div class="tab-pane fade" id="tab-c6" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-stars me-2 text-warning"></i>Kriteria C.6: Diferensiasi Misi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Keunggulan spesifik dan kekhasan/diferensiasi misi program studi dibandingkan program studi sejenis lainnya (Visi Keilmuan).</p>
                <form action="{{ route(\'obe.save-narrative\') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="C6">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Diferensiasi Misi di sini...">{{ $narratives[\'C6\'] ?? \'\' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>';

$empty_d = '<div class="tab-pane fade" id="tab-d" role="tabpanel">
        <h4 class="fw-bold">D. Suplemen</h4>
        <p class="text-muted">Data suplemen program studi akan ditampilkan di sini.</p>
    </div>';

$form_d = '<div class="tab-pane fade" id="tab-d" role="tabpanel">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-square me-2 text-warning"></i>Kriteria D: Suplemen</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Informasi tambahan, lampiran penting, atau suplemen spesifik yang disyaratkan oleh instrumen akreditasi prodi.</p>
                <form action="{{ route(\'obe.save-narrative\') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kriteria_kode" value="D">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="15" placeholder="Ketik narasi Suplemen di sini...">{{ $narratives[\'D\'] ?? \'\' }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Narasi</button>
                </form>
            </div>
        </div>
    </div>';

$content = str_replace($empty_a, $form_a, $content);
$content = str_replace($empty_b, $form_b, $content);
$content = str_replace($empty_c6, $form_c6, $content);
$content = str_replace($empty_d, $form_d, $content);

file_put_contents('resources/views/obe/index.blade.php', $content);
echo "Narrative forms created.";
