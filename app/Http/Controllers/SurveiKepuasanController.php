<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SurveiKepuasan;

class SurveiKepuasanController extends Controller
{
    public function index()
    {
        $surveis = SurveiKepuasan::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis_survei')
            ->get();
            
        $tsList = \App\Models\TS::orderBy('tahun_sekarang', 'desc')->get();
        
        return view('survei_kepuasan.index', compact('surveis', 'tsList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string',
            'jenis_survei' => 'required|string',
            'sangat_baik' => 'required|numeric|min:0|max:100',
            'baik' => 'required|numeric|min:0|max:100',
            'cukup' => 'required|numeric|min:0|max:100',
            'kurang' => 'required|numeric|min:0|max:100',
            'tindak_lanjut' => 'nullable|string'
        ]);

        SurveiKepuasan::updateOrCreate(
            [
                'tahun_akademik' => $request->tahun_akademik,
                'jenis_survei' => $request->jenis_survei
            ],
            $request->only(['sangat_baik', 'baik', 'cukup', 'kurang', 'tindak_lanjut'])
        );

        return redirect()->back()->with('success', 'Data Survei Kepuasan berhasil disimpan.');
    }

    public function destroy(SurveiKepuasan $surveiKepuasan)
    {
        $surveiKepuasan->delete();
        return redirect()->back()->with('success', 'Data dihapus.');
    }
}
