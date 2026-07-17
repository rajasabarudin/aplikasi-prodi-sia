<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use App\Models\Rtm;
use App\Models\RtmTugas;
use App\Models\RtmPenilaian;
use Illuminate\Http\Request;

class RtmController extends Controller
{
    public function index()
    {
        $rpsList = Rps::with(['matakuliah', 'rtm'])->get();
        return view('rtm.index', compact('rpsList'));
    }

    public function generate($id)
    {
        $rps = Rps::findOrFail($id);
        
        $tempFile = storage_path('app/temp_rtm_' . $rps->id . '.json');
        $command = 'python ' . escapeshellarg(base_path('extract_rtm.py')) . ' ' . escapeshellarg($rps->kode_matakuliah) . ' ' . escapeshellarg($tempFile);
        shell_exec($command);
        
        $extractedData = null;
        if (file_exists($tempFile)) {
            $jsonContent = file_get_contents($tempFile);
            $extractedData = json_decode($jsonContent, true);
            unlink($tempFile);
        }
        
        if (is_array($extractedData) && !isset($extractedData['error']) && !empty($extractedData['tasks'])) {
            Rtm::where('rps_id', $rps->id)->delete();
            
            $rtm = Rtm::create([
                'rps_id' => $rps->id,
                'nomor_dokumen' => $extractedData['metadata']['nomor_dokumen'] ?: 'UBSI/DA RTM.' . $rps->kode_matakuliah,
                'dosen_pengampu' => $extractedData['metadata']['dosen_pengampu'] ?: $rps->dosen_pengembang,
                'semester' => (int) $extractedData['metadata']['semester'] ?: ($rps->matakuliah?->semester ?: 1)
            ]);
            
            foreach ($extractedData['tasks'] as $task) {
                $rtmTugas = RtmTugas::create([
                    'rtm_id' => $rtm->id,
                    'minggu_ke' => $task['minggu_ke'],
                    'tugas_ke' => $task['tugas_ke'],
                    'bentuk_tugas' => $task['bentuk_tugas'],
                    'judul_tugas' => $task['judul_tugas'],
                    'sub_cpmk' => $task['sub_cpmk'],
                    'obyek_garapan' => $task['obyek_garapan'],
                    'metode_pengerjaan' => $task['metode_pengerjaan'],
                    'bentuk_format_luaran' => $task['bentuk_format_luaran'],
                    'waktu_pengerjaan' => $task['waktu_pengerjaan'],
                    'waktu_pengumpulan' => $task['waktu_pengumpulan'],
                    'lain_lain' => $task['lain_lain'],
                    'daftar_rujukan' => $task['daftar_rujukan'],
                ]);
                
                if (isset($task['penilaians']) && is_array($task['penilaians'])) {
                    foreach ($task['penilaians'] as $penilaian) {
                        RtmPenilaian::create([
                            'rtm_tugas_id' => $rtmTugas->id,
                            'indikator' => $penilaian['indikator'],
                            'teknik_penilaian' => $penilaian['teknik_penilaian'],
                            'bobot_penilaian' => $penilaian['bobot_penilaian']
                        ]);
                    }
                }
            }
            
            return redirect()->route('penyusunan-rtm.index')->with('success', 'RTM untuk matakuliah ' . ($rps->matakuliah?->nama_matakuliah) . ' berhasil digenerate otomatis!');
        } else {
            $errorMsg = isset($extractedData['error']) ? $extractedData['error'] : 'File PDF RTM tidak dapat diekstrak atau tidak ditemukan di folder rtm.';
            return redirect()->route('penyusunan-rtm.index')->with('error', $errorMsg);
        }
    }

    public function edit($id)
    {
        $rtm = Rtm::with(['tugas.penilaians', 'rps.matakuliah'])->findOrFail($id);
        return view('rtm.edit', compact('rtm'));
    }

    public function update(Request $request, $id)
    {
        $rtm = Rtm::findOrFail($id);
        
        $rtm->update([
            'nomor_dokumen' => $request->nomor_dokumen,
            'dosen_pengampu' => $request->dosen_pengampu,
            'semester' => $request->semester
        ]);
        
        if ($request->has('tugas')) {
            foreach ($request->tugas as $tugasId => $tData) {
                $tugas = RtmTugas::findOrFail($tugasId);
                $tugas->update([
                    'bentuk_tugas' => $tData['bentuk_tugas'] ?? '',
                    'judul_tugas' => $tData['judul_tugas'] ?? '',
                    'sub_cpmk' => $tData['sub_cpmk'] ?? '',
                    'obyek_garapan' => $tData['obyek_garapan'] ?? '',
                    'metode_pengerjaan' => $tData['metode_pengerjaan'] ?? '',
                    'bentuk_format_luaran' => $tData['bentuk_format_luaran'] ?? '',
                    'waktu_pengerjaan' => $tData['waktu_pengerjaan'] ?? '',
                    'waktu_pengumpulan' => $tData['waktu_pengumpulan'] ?? '',
                    'lain_lain' => $tData['lain_lain'] ?? '',
                    'daftar_rujukan' => $tData['daftar_rujukan'] ?? '',
                ]);
            }
        }
        
        return redirect()->route('penyusunan-rtm.index')->with('success', 'Data RTM berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rtm = Rtm::findOrFail($id);
        $rtm->delete();
        return redirect()->route('penyusunan-rtm.index')->with('success', 'Data RTM berhasil dihapus.');
    }

    public function cetak($id)
    {
        $rtm = Rtm::with(['tugas.penilaians', 'rps.matakuliah', 'rps.penelitians', 'rps.pkms'])->findOrFail($id);
        return view('rtm.cetak', compact('rtm'));
    }
}
