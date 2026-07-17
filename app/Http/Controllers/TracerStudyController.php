<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function index()
    {
        $alumnis = Alumni::with('tracerStudy')->orderBy('tahun_lulus', 'desc')->get();
        return view('tracer_study.index', compact('alumnis'));
    }

    public function create()
    {
        // Not used, modal approach
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:alumnis',
            'nama' => 'required|string',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer|gte:tahun_masuk',
            'ipk' => 'required|numeric|min:0|max:4',
            'status_kerja' => 'required|string',
        ]);

        $alumni = Alumni::create($request->only(['nim', 'nama', 'tahun_masuk', 'tahun_lulus', 'ipk', 'no_telepon', 'email']));
        
        $alumni->tracerStudy()->create($request->only([
            'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
            'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
        ]));

        return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study berhasil ditambahkan.');
    }

    public function show($id)
    {
        // 
    }

    public function update(Request $request, $id)
    {
        $alumni = Alumni::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer|gte:tahun_masuk',
            'ipk' => 'required|numeric|min:0|max:4',
            'status_kerja' => 'required|string',
        ]);

        $alumni->update($request->only(['nama', 'tahun_masuk', 'tahun_lulus', 'ipk', 'no_telepon', 'email']));
        
        if ($alumni->tracerStudy) {
            $alumni->tracerStudy->update($request->only([
                'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
                'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
            ]));
        } else {
            $alumni->tracerStudy()->create($request->only([
                'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
                'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
            ]));
        }

        return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alumni = Alumni::findOrFail($id);
        $alumni->delete();
        return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study berhasil dihapus.');
    }
}
