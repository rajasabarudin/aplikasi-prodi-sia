<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IotData;

$file = fopen(__DIR__ . '/dataset.csv', 'r');
if (!$file) {
    die("Gagal membuka dataset.csv\n");
}

$header = fgetcsv($file);

$count = 0;
while (($row = fgetcsv($file)) !== false) {
    if (count($row) < 5) continue;
    
    $tanggal = $row[0];
    $waktu_str = $row[1];
    $tree_id = $row[2];
    $suhu_tanah = (float)$row[3];
    $kelembaban_tanah = (float)$row[4];
    
    // Generate data masuk akal untuk udara
    // Suhu udara biasanya sedikit lebih hangat atau dekat dengan suhu tanah di siang hari
    $suhu_udara = $suhu_tanah + (rand(-10, 30) / 10); 
    // Kelembaban udara biasanya lebih rendah dibanding kelembaban tanah
    $kelembaban_udara = $kelembaban_tanah - rand(5, 20);
    
    if ($kelembaban_udara > 100) $kelembaban_udara = 100;
    if ($kelembaban_udara < 0) $kelembaban_udara = 0;

    // Tambahkan detik agar waktu selalu unik (menghindari constraint UNIQUE pada tabel)
    $waktu_dt = new DateTime($tanggal . ' ' . $waktu_str);
    $waktu_dt->modify("+{$count} seconds");
    $waktu = $waktu_dt->format('Y-m-d H:i:s');
    
    IotData::updateOrCreate(
        [
            'waktu' => $waktu,
        ],
        [
            'device_id' => $tree_id,
            'suhu_tanah_celcius' => $suhu_tanah,
            'kelembaban_tanah_persen' => $kelembaban_tanah,
            'suhu_udara_celcius' => round($suhu_udara, 1),
            'kelembaban_udara_persen' => round($kelembaban_udara, 1),
        ]
    );
    $count++;
}
fclose($file);

echo "Berhasil mengimpor $count baris data dari dataset.csv ke tabel iot_data!\n";
