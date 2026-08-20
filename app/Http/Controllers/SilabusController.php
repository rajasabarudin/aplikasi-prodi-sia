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
        $rps = Rps::with(['pertemuans' => function($query) {
            $query->orderBy('minggu_ke', 'asc');
        }])->findOrFail($id);
        
        Silabus::where('rps_id', $rps->id)->delete();
        
        $silabus = Silabus::create([
            'rps_id' => $rps->id,
            'kode_dokumen' => 'UBSI/DA/PNK.' . $rps->kode_matakuliah,
            'cpmk' => '',
            'sub_cpmk' => ''
        ]);
        
        $hasMateri = false;
        foreach ($rps->pertemuans as $pertemuan) {
            if (!empty(trim($pertemuan->bahan_kajian))) {
                $hasMateri = true;
                SilabusMateri::create([
                    'silabus_id' => $silabus->id,
                    'pertemuan' => $pertemuan->minggu_ke,
                    'materi' => $pertemuan->bahan_kajian
                ]);
            }
        }
        
        if (!$hasMateri) {
            SilabusMateri::create([
                'silabus_id' => $silabus->id,
                'pertemuan' => '1',
                'materi' => 'Materi Pertemuan 1 (Draft)'
            ]);
        }
        
        return redirect()->route('penyusunan-silabus.index')->with('success', 'Silabus berhasil digenerate otomatis dari Rincian Pertemuan RPS!');
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
