<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use App\Models\Silabus;
use App\Models\SilabusMateri;
use Illuminate\Http\Request;

class SilabusController extends Controller
{
    public function index()
    {
        $rpsList = Rps::with(['matakuliah', 'silabus'])->get();
        return view('silabus.index', compact('rpsList'));
    }

    public function generate($id)
    {
        $rps = Rps::findOrFail($id);
        
        $tempFile = storage_path('app/temp_silabus_' . $rps->id . '.json');
        $command = 'python ' . escapeshellarg(base_path('extract_silabus.py')) . ' ' . escapeshellarg($rps->kode_matakuliah) . ' ' . escapeshellarg($tempFile);
        shell_exec($command);
        
        $extractedData = null;
        if (file_exists($tempFile)) {
            $jsonContent = file_get_contents($tempFile);
            $extractedData = json_decode($jsonContent, true);
            unlink($tempFile);
        }
        
        if (is_array($extractedData) && !isset($extractedData['error']) && !empty($extractedData['materis'])) {
            Silabus::where('rps_id', $rps->id)->delete();
            
            $silabus = Silabus::create([
                'rps_id' => $rps->id,
                'kode_dokumen' => $extractedData['kode_dokumen'] ?: 'UBSI/DA/PNK.' . $rps->kode_matakuliah,
                'cpmk' => $extractedData['cpmk'],
                'sub_cpmk' => $extractedData['sub_cpmk']
            ]);
            
            foreach ($extractedData['materis'] as $item) {
                SilabusMateri::create([
                    'silabus_id' => $silabus->id,
                    'pertemuan' => $item['pertemuan'],
                    'materi' => $item['materi']
                ]);
            }
            
            return redirect()->route('penyusunan-silabus.index')->with('success', 'Silabus untuk matakuliah ' . ($rps->matakuliah?->nama_matakuliah) . ' berhasil digenerate otomatis!');
        } else {
            $errorMsg = isset($extractedData['error']) ? $extractedData['error'] : 'File PDF Silabus tidak dapat diekstrak atau tidak ditemukan di folder silabus.';
            return redirect()->route('penyusunan-silabus.index')->with('error', $errorMsg);
        }
    }

    public function edit($id)
    {
        $silabus = Silabus::with(['materis', 'rps.matakuliah'])->findOrFail($id);
        return view('silabus.edit', compact('silabus'));
    }

    public function update(Request $request, $id)
    {
        $silabus = Silabus::findOrFail($id);
        
        $silabus->update([
            'kode_dokumen' => $request->kode_dokumen,
            'cpmk' => $request->cpmk,
            'sub_cpmk' => $request->sub_cpmk,
        ]);
        
        if ($request->has('materi')) {
            foreach ($request->materi as $materiId => $mText) {
                $m = SilabusMateri::findOrFail($materiId);
                $m->update([
                    'materi' => $mText ?? ''
                ]);
            }
        }
        
        return redirect()->route('penyusunan-silabus.index')->with('success', 'Data Silabus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $silabus = Silabus::findOrFail($id);
        $silabus->delete();
        return redirect()->route('penyusunan-silabus.index')->with('success', 'Data Silabus berhasil dihapus.');
    }

    public function cetak($id)
    {
        $silabus = Silabus::with(['materis', 'rps.matakuliah', 'rps.penelitians', 'rps.pkms'])->findOrFail($id);
        
        $referensi_utama = \App\Models\RpsReferensi::where('kode_matakuliah', $silabus->rps->kode_matakuliah)->where('jenis', 'utama')->get();
        $referensi_pendukung = \App\Models\RpsReferensi::where('kode_matakuliah', $silabus->rps->kode_matakuliah)->where('jenis', 'pendukung')->get();

        return view('silabus.cetak', compact('silabus', 'referensi_utama', 'referensi_pendukung'));
    }
}
