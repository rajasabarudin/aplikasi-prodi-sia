<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    '101' => ['t' => 1, 'pa' => 1, 'pu' => 0],
    '104' => ['t' => 1, 'pa' => 1, 'pu' => 0],
    '851' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '207' => ['t' => 2, 'pa' => 2, 'pu' => 0],
    '153' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '894' => ['t' => 0, 'pa' => 3, 'pu' => 1],
    '105' => ['t' => 1, 'pa' => 1, 'pu' => 0],
    '0334' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '0041' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '0561' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '0537' => ['t' => 0, 'pa' => 2, 'pu' => 1],
    '700' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '102' => ['t' => 1, 'pa' => 1, 'pu' => 0],
    '0046' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '0004' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '0576' => ['t' => 0, 'pa' => 2, 'pu' => 0],
    '240' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '804' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '0588' => ['t' => 0, 'pa' => 3, 'pu' => 1],
    '232' => ['t' => 0, 'pa' => 3, 'pu' => 1],
    '0047' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '230' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '0628' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '028' => ['t' => 0, 'pa' => 3, 'pu' => 1],
    '0608' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '0617' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '712' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '617' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '0002' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '154' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '615' => ['t' => 0, 'pa' => 4, 'pu' => 0],
    '253' => ['t' => 1, 'pa' => 1, 'pu' => 0],
    '722' => ['t' => 2, 'pa' => 1, 'pu' => 0],
    '997' => ['t' => 0, 'pa' => 4, 'pu' => 0],
    '0053' => ['t' => 1, 'pa' => 2, 'pu' => 0],
    '106' => ['t' => 1, 'pa' => 1, 'pu' => 0],
];

$count = 0;
foreach ($data as $kode => $sks) {
    $mk = App\Models\Matakuliah::where('kode_matakuliah', $kode)->first();
    if ($mk) {
        $mk->sks_t = $sks['t'];
        $mk->sks_pa = $sks['pa'];
        $mk->sks_pu = $sks['pu'];
        $mk->sks = $sks['t'] + $sks['pa'] + $sks['pu'];
        $mk->save();
        $count++;
    }
}

echo "Successfully updated $count matakuliah records.";
