<?php

namespace App\Http\Controllers;

use App\Models\RpsBahanKajian;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class RpsBahanKajianController extends Controller
{
    public function index()
    {
        $bahanKajians = RpsBahanKajian::with('matakuliah')->orderBy('kode_matakuliah')->orderBy('urutan')->get();
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        return view('rps.bahan_kajian.index', compact('bahanKajians', 'matakuliahList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matakuliah' => 'required|exists:matakuliahs,kode_matakuliah',
            'urutan'          => 'required|integer|min:1',
            'topik'           => 'required|string|max:255',
            'sub_topik'       => 'nullable|string',
        ]);

        RpsBahanKajian::create($request->all());

        return redirect()->route('rps-bahan-kajian.index')->with('success', 'Bahan Kajian berhasil ditambahkan.');
    }

    public function edit(RpsBahanKajian $rpsBahanKajian)
    {
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        return view('rps.bahan_kajian.edit', ['bahanKajian' => $rpsBahanKajian, 'matakuliahList' => $matakuliahList]);
    }

    public function update(Request $request, RpsBahanKajian $rpsBahanKajian)
    {
        $request->validate([
            'kode_matakuliah' => 'required|exists:matakuliahs,kode_matakuliah',
            'urutan'          => 'required|integer|min:1',
            'topik'           => 'required|string|max:255',
            'sub_topik'       => 'nullable|string',
        ]);

        $rpsBahanKajian->update($request->all());

        return redirect()->route('rps-bahan-kajian.index')->with('success', 'Bahan Kajian berhasil diperbarui.');
    }

    public function destroy(RpsBahanKajian $rpsBahanKajian)
    {
        $rpsBahanKajian->delete();
        return redirect()->route('rps-bahan-kajian.index')->with('success', 'Bahan Kajian berhasil dihapus.');
    }
}
