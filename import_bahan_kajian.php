<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RpsBahanKajian;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    RpsBahanKajian::truncate();
    

    // RPS_0046_1130.pdf
    $kode_raw = '0046';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Pembentukan Usaha Persekutuan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Pembubaran / Likuidasi Perusahaan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Join Venture', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Penjualan Angsuran', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Konsinyasi', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Penggabungan Badan Usaha (Konsolidasi)', 'sub_topik' => '']);
    }

    // RPS_0576_1130.pdf
    $kode_raw = '0576';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Jurnal Umum', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Buku Besar', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Neraca saldo', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Ayat jurnal penyesuaian', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Neraca lajur', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Neraca saldo disesuaikan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Jurnal penutup', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'Jurnal pembalik', 'sub_topik' => '']);
    }

    // RPS_0588_1130.pdf
    $kode_raw = '0588';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
    }

    // RPS_101_1130.pdf
    $kode_raw = '101';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Pengetahuan tentang Pancasila', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Pancasila dalam sejarah', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Pancasila sebagai Dasar negara', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Pancasila sebagai ideologi negara', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Pancasila sebagai sistem filsafat', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Nilai Pancasila', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Pancasila sebagai sistem Etika', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'Pancasila sebagai dasar nilai pengembangan ilmu', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 9, 'topik' => 'Perilaku dalam perbuatan Korupsi', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 10, 'topik' => 'Pajak', 'sub_topik' => '']);
    }

    // RPS_104_1130.pdf
    $kode_raw = '104';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Introduction', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Talking about Daily Activity', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Checking Supply', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Entertainment and Invitation', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Tell me about Your Family', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Routines', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Review/ Quiz', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'We have great times', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 9, 'topik' => 'Caught in the Rush', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 10, 'topik' => 'Ok. No Problem!', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 11, 'topik' => 'What is this for?', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 12, 'topik' => 'I’ve never heard of that!', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 13, 'topik' => 'Let’s celebrate!', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 14, 'topik' => 'Review/ Quiz', 'sub_topik' => '']);
    }

    // RPS_207_1130.pdf
    $kode_raw = '207';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Konsep algoritma', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Tipe data', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Flowchart', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Branching', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Looping', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Rekursif', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Array', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'Matriks', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 9, 'topik' => 'Sorting', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 10, 'topik' => 'Searching', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 11, 'topik' => 'Metode Greedy', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 12, 'topik' => 'Model Graph dalam Metode Greedy', 'sub_topik' => '']);
    }

    // RPS_232_1130.pdf
    $kode_raw = '232';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Teori dan konsep Zahir Accounting versi 6.', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Database perusahaan baru.', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Daftar perkiraan dan setup master data.', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Modul Persediaan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Modul Kas & bank.', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Modul Penjualan.', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Modul Pembelian', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'Modul Buku Besar serta Analisa Laporan Keuangan.', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 9, 'topik' => 'Studi kasus perusahaan dagang dan jasa', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 10, 'topik' => 'Studi kasus perusahaan manufaktur', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 11, 'topik' => 'Project penyelesaian perusahaan jasa dan dagang secara berkelompok', 'sub_topik' => '']);
    }

    // RPS_240_1130.pdf
    $kode_raw = '240';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Konsep Dasar Sistem Informasi Manajemen', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Sistem Informasi dan CBIS', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Manajemen dan Organisasi', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'SIM dalam Pemasaran', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'SIM dalam Sistem Keuangan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'SIM dalam SDM dan SDI', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Transformasi Digital Menuju Era Disruptif dan Revolusi Industri 4.0', 'sub_topik' => '']);
    }

    // RPS_617_1130.pdf
    $kode_raw = '617';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Bentuk Laporan Keuangan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Analisa Pembandingan Laporan Keuangan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Analisa Ratio Likuiditas, Solvabilitas, Rentabilitas', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Analisa Sumber dan Penggunaan Modal Kerja', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Analisa Sumber dan Penggunaan Kas', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Analisa Break Event Point.', 'sub_topik' => '']);
    }

    // RPS_712_1130.pdf
    $kode_raw = '712';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Pengertian Kewirausahaan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Cashflow Quadrant', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'Mindset', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Bisnis Model Canvas', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Strategi pemasaran', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'Bidang Usaha dan Permodalan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Wirausaha Sukses, Kreatif dan Etika bisnis', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'Risiko Usaha dan Marketing langit', 'sub_topik' => '']);
    }

    // RPS_894_1130.pdf
    $kode_raw = '894';
    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();
    if ($mk) {
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 1, 'topik' => 'Struktur bahasa pemrograman Python', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 2, 'topik' => 'Tipe data & Variable', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 3, 'topik' => 'String, Bilangan & Operator', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 4, 'topik' => 'Seleksi Kondisi (percabangan)', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 5, 'topik' => 'Perulangan', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 6, 'topik' => 'List dan Tuple', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 7, 'topik' => 'Matrix dan Library (Pandas)', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 8, 'topik' => 'Fungsi', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 9, 'topik' => 'Modul & Eksepsi', 'sub_topik' => '']);
        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => 10, 'topik' => 'OOP', 'sub_topik' => '']);
    }
    echo 'Selesai import bahan kajian.';
});
