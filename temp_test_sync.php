<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $response = \Illuminate\Support\Facades\Http::get('https://iotproject.my.id/api/api_public_data.php');
    if ($response->successful()) {
        $data = $response->json('data');
        echo "Data count: " . count($data) . "\n";
        foreach ($data as $item) {
            $model = \App\Models\IotData::updateOrCreate(
                ['waktu' => $item['waktu']],
                [
                    'device_id' => $item['device_id'],
                    'kelembaban_tanah_persen' => $item['kelembaban_tanah_persen'],
                    'suhu_tanah_celcius' => $item['suhu_tanah_celcius'],
                    'suhu_udara_celcius' => $item['suhu_udara_celcius'],
                    'kelembaban_udara_persen' => $item['kelembaban_udara_persen'],
                ]
            );
            echo "Inserted: " . $model->id . "\n";
            break; // Just test one
        }
    } else {
        echo "Failed to fetch API: " . $response->status() . "\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
