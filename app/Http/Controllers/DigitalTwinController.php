<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DigitalTwinController extends Controller
{
    public function index()
    {
        // Get the latest 100 data points for the dashboard
        $dataset = \App\Models\IotData::orderBy('waktu', 'desc')->take(100)->get();
        return view('digital-twin.index', compact('dataset'));
    }

    public function syncData()
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('https://iotproject.my.id/api/api_public_data.php');
            
            if ($response->successful()) {
                $data = $response->json('data');
                $count = 0;

                if ($data && is_array($data)) {
                    foreach ($data as $item) {
                        // Use updateOrCreate to prevent duplicate entries based on 'waktu'
                        \App\Models\IotData::updateOrCreate(
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
                return redirect()->back()->with('success', "Berhasil sinkronisasi $count data terbaru dari API.");
            }
            
            return redirect()->back()->with('error', 'Gagal mengambil data dari API.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
