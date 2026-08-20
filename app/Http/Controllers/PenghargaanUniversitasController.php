<?php

namespace App\Http\Controllers;

use App\Models\PenghargaanUniversitas;
use Illuminate\Http\Request;

class PenghargaanUniversitasController extends Controller
{
    public function index()
    {
        $penghargaan = PenghargaanUniversitas::orderBy('tahun', 'desc')->get();
        return view('penghargaan-universitas.index', compact('penghargaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:4',
            'nama_mitra' => 'required|string|max:255',
            'nomor_penghargaan' => 'nullable|string|max:255',
            'link_dokumen_penghargaan' => 'nullable|url|max:255',
            'link_berita' => 'nullable|url|max:255',
        ]);

        PenghargaanUniversitas::create($validated);
        return redirect()->route('penghargaan-universitas.index')->with('success', 'Data Penghargaan Universitas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $penghargaan = PenghargaanUniversitas::findOrFail($id);
        
        $validated = $request->validate([
            'tahun' => 'required|string|max:4',
            'nama_mitra' => 'required|string|max:255',
            'nomor_penghargaan' => 'nullable|string|max:255',
            'link_dokumen_penghargaan' => 'nullable|url|max:255',
            'link_berita' => 'nullable|url|max:255',
        ]);

        $penghargaan->update($validated);
        return redirect()->route('penghargaan-universitas.index')->with('success', 'Data Penghargaan Universitas berhasil diupdate.');
    }

    public function destroy($id)
    {
        $penghargaan = PenghargaanUniversitas::findOrFail($id);
        $penghargaan->delete();
        return redirect()->route('penghargaan-universitas.index')->with('success', 'Data Penghargaan Universitas berhasil dihapus.');
    }
}
