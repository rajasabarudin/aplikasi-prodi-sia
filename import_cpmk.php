<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cpmk;
use Illuminate\Support\Facades\DB;

$data = [
    // CPL01
    ['cpl_id' => 1, 'kode_cpmk' => 'CPMK.01.1', 'deskripsi_cpmk' => 'Mampu bekerja sama dalam tim dan secara mandiri untuk menyelesaikan tugas dan proyek dengan disiplin dan tanggung jawab, serta memberikan kontribusi yang berarti dalam mencapai tujuan kelompok', 'matakuliahs' => ['MK25', 'MK31', 'MK34']],
    ['cpl_id' => 1, 'kode_cpmk' => 'CPMK.01.2', 'deskripsi_cpmk' => 'Mampu mengidentifikasi peluang kewirausahaan dan menerapkan prinsip kewirausahaan dalam pengembangan ide bisnis, dengan memperhatikan aspek tanggung jawab sosial dan etika bisnis', 'matakuliahs' => ['MK27', 'MK33', 'MK29']],

    // CPL02
    ['cpl_id' => 2, 'kode_cpmk' => 'CPMK.02.1', 'deskripsi_cpmk' => 'Mampu memahami konsep dasar teknologi informasi dan komunikasi', 'matakuliahs' => ['MK05']],
    ['cpl_id' => 2, 'kode_cpmk' => 'CPMK.02.2', 'deskripsi_cpmk' => 'Mampu menerapkan ilmu pengetahuan dan teknologi terkini dalam pengembangan dan implementasi sistem informasi akuntansi.', 'matakuliahs' => ['MK15', 'MK19', 'MK20', 'MK24']],
    ['cpl_id' => 2, 'kode_cpmk' => 'CPMK.02.3', 'deskripsi_cpmk' => 'Mampu mengorganisasi dan mengelola data akuntansi secara sistematis untuk mendukung penyusunan laporan keuangan yang akurat dan tepat waktu', 'matakuliahs' => ['MK03', 'MK16']],
    ['cpl_id' => 2, 'kode_cpmk' => 'CPMK.02.4', 'deskripsi_cpmk' => 'Mampu mengelola proses akuntansi, termasuk pencatatan, pelaporan, dan analisis, sesuai dengan standar akuntansi yang berlaku.', 'matakuliahs' => ['MK09', 'MK03', 'MK22', 'MK28', 'MK35']],
    ['cpl_id' => 2, 'kode_cpmk' => 'CPMK.02.5', 'deskripsi_cpmk' => 'Mampu mengevaluasi kinerja dan efektivitas sistem informasi akuntansi serta mengidentifikasi dan menyelesaikan masalah yang muncul dalam penerapan sistem tersebut.', 'matakuliahs' => ['MK25', 'MK31', 'MK34']],

    // CPL03
    ['cpl_id' => 3, 'kode_cpmk' => 'CPMK.03.1', 'deskripsi_cpmk' => 'Mampu memahami dan menerapkan prinsip-prinsip akuntansi dalam penyusunan laporan keuangan dan analisis transaksi keuangan sesuai dengan standar akuntansi yang berlaku', 'matakuliahs' => ['MK08', 'MK03', 'MK09', 'MK14', 'MK22']],
    ['cpl_id' => 3, 'kode_cpmk' => 'CPMK.03.2', 'deskripsi_cpmk' => 'Mampu memahami dan menerapkan konsep perpajakan dalam perencanaan dan pelaporan pajak untuk entitas bisnis sesuai dengan peraturan perpajakan yang berlaku', 'matakuliahs' => ['MK18', 'MK21']],
    ['cpl_id' => 3, 'kode_cpmk' => 'CPMK.03.3', 'deskripsi_cpmk' => 'Mampu menganalisis dan mengelola keputusan keuangan perusahaan, termasuk perencanaan anggaran, pengelolaan kas, dan investasi untuk mencapai tujuan keuangan organisasi', 'matakuliahs' => ['MK33', 'MK29', 'MK28']],
    ['cpl_id' => 3, 'kode_cpmk' => 'CPMK.03.4', 'deskripsi_cpmk' => 'Mampu memahami dan menerapkan konsep dasar sistem informasi akuntansi serta metodologi pengembangan sistem untuk mendukung pengelolaan data keuangan dan pengambilan keputusan yang efektif', 'matakuliahs' => ['MK15', 'MK17', 'MK23', 'MK25']],

    // CPL04
    ['cpl_id' => 4, 'kode_cpmk' => 'CPMK.04.1', 'deskripsi_cpmk' => 'Mampu memahami dan menerapkan konsep basis data dalam perancangan dan implementasi sistem informasi akuntansi, termasuk pemodelan data, normalisasi, dan pengelolaan database menggunakan SQL.', 'matakuliahs' => ['MK10', 'MK11', 'MK06', 'MK19']],
    ['cpl_id' => 4, 'kode_cpmk' => 'CPMK.04.2', 'deskripsi_cpmk' => 'Mampu menerapkan dasar-dasar logika dalam pemecahan masalah, pengambilan keputusan, dan perancangan algoritma.', 'matakuliahs' => ['MK04', 'MK06', 'MK19']],
    ['cpl_id' => 4, 'kode_cpmk' => 'CPMK.04.3', 'deskripsi_cpmk' => 'Mampu memahami dan menerapkan prinsip-prinsip matematika dalam analisis data keuangan, statistik.', 'matakuliahs' => ['MK12', 'MK28', 'MK33']],
    ['cpl_id' => 4, 'kode_cpmk' => 'CPMK.04.4', 'deskripsi_cpmk' => 'Mampu mengembangkan dan mengimplementasikan aplikasi sistem informasi akuntansi dengan menggunakan bahasa pemrograman yang sesuai, serta mengintegrasikan berbagai komponen sistem secara efektif.', 'matakuliahs' => ['MK06', 'MK19']],
    ['cpl_id' => 4, 'kode_cpmk' => 'CPMK.04.5', 'deskripsi_cpmk' => 'Mampu menganalisa kebutuhan organisasi dan merancang sistem informasi akuntansi yang tepat dengan metodologi pengembangan sistem yang sesuai', 'matakuliahs' => ['MK15', 'MK23', 'MK25']],

    // CPL05
    ['cpl_id' => 5, 'kode_cpmk' => 'CPMK.05.1', 'deskripsi_cpmk' => 'Mampu memahami konsep dasar teknologi dalam sistem informasi akuntansi untuk mengelola data keuangan.', 'matakuliahs' => ['MK15', 'MK20', 'MK24']],
    ['cpl_id' => 5, 'kode_cpmk' => 'CPMK.05.2', 'deskripsi_cpmk' => 'Mampu menganalisis dan memilih metode yang tepat dalam pengembangan dan evaluasi sistem informasi akuntansi.', 'matakuliahs' => ['MK23', 'MK26']],
    ['cpl_id' => 5, 'kode_cpmk' => 'CPMK.05.3', 'deskripsi_cpmk' => 'Mampu menghasilkan dan mengevaluasi laporan keuangan yang relevan dan sesuai dengan standar akuntansi.', 'matakuliahs' => ['MK09', 'MK14', 'MK35', 'MK28']],

    // CPL06
    ['cpl_id' => 6, 'kode_cpmk' => 'CPMK.06.1', 'deskripsi_cpmk' => 'Mampu mengembangkan kompetensi diri pada lingkungan kerja', 'matakuliahs' => ['MK32', 'MK02', 'MK07']],
    ['cpl_id' => 6, 'kode_cpmk' => 'CPMK.06.2', 'deskripsi_cpmk' => 'Mampu menunjukkan integritas dan disiplin dalam menyelesaikan tugas-tugas pada lingkungan kerja.', 'matakuliahs' => ['MK30', 'MK36', 'MK13', 'MK01']],
];

DB::transaction(function () use ($data) {
    // Delete existing CPMK to avoid duplicates (optional, based on requirement)
    // Cpmk::truncate();
    
    $count = 0;
    foreach ($data as $row) {
        $cpmk = Cpmk::updateOrCreate(
            ['kode_cpmk' => $row['kode_cpmk']],
            [
                'deskripsi_cpmk' => $row['deskripsi_cpmk'],
                'cpl_id' => $row['cpl_id']
            ]
        );
        $count++;

        // Attach matakuliah mappings
        $matakuliahIds = [];
        foreach ($row['matakuliahs'] as $kodeMk) {
            $kodeMkClean = str_replace('MK', '', $kodeMk); // the table might use 101 instead of MK01
            $mk = \App\Models\Matakuliah::where('kode_matakuliah', $kodeMk)->orWhere('kode_matakuliah', $kodeMkClean)->orWhere('kode_matakuliah', 'like', "%$kodeMkClean%")->first();
            if ($mk) {
                $matakuliahIds[] = $mk->kode_matakuliah;
            }
        }
        $cpmk->matakuliahs()->sync($matakuliahIds);
    }
    echo "Successfully imported $count CPMK records and their mappings.\n";
});
