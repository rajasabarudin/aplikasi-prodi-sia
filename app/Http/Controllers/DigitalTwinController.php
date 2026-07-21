<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DigitalTwinController extends Controller
{
    public function index()
    {
        // Get the latest 100 data points for the dashboard
        $dataset = \App\Models\IotData::orderBy('waktu', 'desc')->take(100)->get();
        
        // Fetch photos from the new API
        $photos = [];
        $photosByDate = [];
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://iotproject.my.id/api/api_public_photos.php');
            if ($response->successful()) {
                $photos = $response->json('data') ?? [];
                
                // Group by Date
                foreach ($photos as $photo) {
                    $date = date('Y-m-d', strtotime($photo['waktu']));
                    $photosByDate[$date][] = $photo;
                }
            }
        } catch (\Exception $e) {
            // Silently ignore if API fails, so page still loads
        }

        return view('digital-twin.index', compact('dataset', 'photos', 'photosByDate'));
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

    public function exportCsv()
    {
        $fileName = 'dataset_iot_sawit_' . date('Ymd_His') . '.csv';
        $dataset = \App\Models\IotData::orderBy('waktu', 'desc')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Waktu (WIB)', 'Device ID', 'Suhu Udara (C)', 'Kelembapan Udara (%)', 'Suhu Tanah (C)', 'Kelembapan Tanah (%)');

        $callback = function() use($dataset, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($dataset as $row) {
                fputcsv($file, array(
                    $row->waktu,
                    $row->device_id,
                    $row->suhu_udara_celcius,
                    $row->kelembaban_udara_persen,
                    $row->suhu_tanah_celcius,
                    $row->kelembaban_tanah_persen
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
