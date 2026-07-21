<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IotData;
use Illuminate\Support\Facades\Http;

class SyncDigitalTwinData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iot:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data IoT Digital Twin dari API ke database lokal';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data IoT...');

        try {
            $response = Http::get('https://iotproject.my.id/api/api_public_data.php');
            
            if ($response->successful()) {
                $data = $response->json('data');
                $count = 0;

                if ($data && is_array($data)) {
                    foreach ($data as $item) {
                        IotData::updateOrCreate(
                            ['waktu' => $item['waktu']],
                            [
                                'device_id' => $item['device_id'],
                                'kelembaban_tanah_persen' => $item['kelembaban_tanah_persen'],
                                'suhu_tanah_celcius' => $item['suhu_tanah_celcius'],
                                'suhu_udara_celcius' => $item['suhu_udara_celcius'],
                                'kelembaban_udara_persen' => $item['kelembaban_udara_persen'],
                            ]
                        );
                        $count++;
                    }
                }
                $this->info("Berhasil sinkronisasi $count data terbaru.");
                return Command::SUCCESS;
            }
            
            $this->error('Gagal mengambil data dari API (Response tidak success).');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
