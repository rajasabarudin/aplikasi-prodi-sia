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
        $rps = Rps::with('pertemuans')->findOrFail($id);
        
        $pertemuans = $rps->pertemuans->sortBy(function($item) {
            return (int) $item->minggu_ke;
        });

        Silabus::where('rps_id', $rps->id)->delete();
        
        // Ambil CPMK dari tabel cpmk_matakuliah (pivot)
        $cpmk_ids = \Illuminate\Support\Facades\DB::table('cpmk_matakuliah')
            ->where('kode_matakuliah', $rps->kode_matakuliah)
            ->pluck('cpmk_id');
        $cpmks = \App\Models\Cpmk::whereIn('id', $cpmk_ids)->get();
        
        $cpmk_text = '';
        foreach($cpmks as $index => $c) {
            $cpmk_text .= ($index + 1) . ". " . trim($c->deskripsi_cpmk) . "
";
        }

        // Ambil Sub-CPMK unik dari pertemuan
        $subCpmkList = [];
        foreach ($pertemuans as $pertemuan) {
            if (!empty(trim($pertemuan->sub_cpmk))) {
                $subCpmkList[] = trim($pertemuan->sub_cpmk);
            }
        }
        $sub_cpmk_text = '';
        $uniqueSubCpmk = array_values(array_unique($subCpmkList));
        foreach($uniqueSubCpmk as $index => $s) {
            $sub_cpmk_text .= ($index + 1) . ". " . $s . "
";
        }
        
        $silabus = Silabus::create([
            'rps_id' => $rps->id,
            'kode_dokumen' => 'UBSI/DA/PNK.' . $rps->kode_matakuliah,
            'cpmk' => trim($cpmk_text),
            'sub_cpmk' => trim($sub_cpmk_text)
        ]);
        
        $hasMateri = false;
        foreach ($pertemuans as $pertemuan) {
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
