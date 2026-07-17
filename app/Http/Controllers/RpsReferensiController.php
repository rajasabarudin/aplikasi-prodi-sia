<?php

namespace App\Http\Controllers;

use App\Models\RpsReferensi;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class RpsReferensiController extends Controller
{
    public function index()
    {
        $referensis = RpsReferensi::with('matakuliah')->orderBy('kode_matakuliah')->orderBy('jenis')->orderBy('penulis')->get();
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        return view('rps.referensi.index', compact('referensis', 'matakuliahList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matakuliah' => 'required|exists:matakuliahs,kode_matakuliah',
            'jenis'           => 'required|in:utama,pendukung',
            'penulis'         => 'required|string|max:255',
            'tahun'           => 'required|integer|min:1900|max:2099',
            'judul'           => 'required|string|max:500',
            'penerbit'        => 'nullable|string|max:255',
            'kota'            => 'nullable|string|max:100',
            'url'             => 'nullable|url',
        ]);

        RpsReferensi::create($request->all());

        return redirect()->route('rps-referensi.index')->with('success', 'Referensi berhasil ditambahkan.');
    }

    public function edit(RpsReferensi $rpsReferensi)
    {
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        return view('rps.referensi.edit', ['referensi' => $rpsReferensi, 'matakuliahList' => $matakuliahList]);
    }

    public function update(Request $request, RpsReferensi $rpsReferensi)
    {
        $request->validate([
            'kode_matakuliah' => 'required|exists:matakuliahs,kode_matakuliah',
            'jenis'           => 'required|in:utama,pendukung',
            'penulis'         => 'required|string|max:255',
            'tahun'           => 'required|integer|min:1900|max:2099',
            'judul'           => 'required|string|max:500',
            'penerbit'        => 'nullable|string|max:255',
            'kota'            => 'nullable|string|max:100',
            'url'             => 'nullable|url',
        ]);

        $rpsReferensi->update($request->all());

        return redirect()->route('rps-referensi.index')->with('success', 'Referensi berhasil diperbarui.');
    }

    public function destroy(RpsReferensi $rpsReferensi)
    {
        $rpsReferensi->delete();
        return redirect()->route('rps-referensi.index')->with('success', 'Referensi berhasil dihapus.');
    }
}
