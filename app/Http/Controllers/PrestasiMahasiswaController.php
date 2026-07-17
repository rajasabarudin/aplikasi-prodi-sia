<?php

namespace App\Http\Controllers;

use App\Models\PrestasiMahasiswa;
use Illuminate\Http\Request;

class PrestasiMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'nama_prestasi' => 'required|string|max:255',
            'tahun' => 'required|string|max:255',
            'ts_id' => 'required|exists:ts,id',
            'penyelenggara' => 'required|string|max:255',
            'bidang_prestasi' => 'required|in:Akademik,Non Akademik,Akademik Non Lomba,Partisipan',
            'prestasi_diraih' => 'required|in:Juara 1,Juara 2,Juara 3,Harapan 1,Harapan 2,Partisipan',
            'level_prestasi' => 'required|in:Lokal,Wilayah,Nasional,Internasional',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        PrestasiMahasiswa::create($request->all());

        return redirect()->back()->with('success', 'Data prestasi mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'tahun' => 'required|string|max:255',
            'ts_id' => 'required|exists:ts,id',
            'penyelenggara' => 'required|string|max:255',
            'bidang_prestasi' => 'required|in:Akademik,Non Akademik,Akademik Non Lomba,Partisipan',
            'prestasi_diraih' => 'required|in:Juara 1,Juara 2,Juara 3,Harapan 1,Harapan 2,Partisipan',
            'level_prestasi' => 'required|in:Lokal,Wilayah,Nasional,Internasional',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        $prestasi->update($request->all());

        return redirect()->back()->with('success', 'Data prestasi mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);
        $prestasi->delete();

        return redirect()->back()->with('success', 'Data prestasi mahasiswa berhasil dihapus.');
    }
}
