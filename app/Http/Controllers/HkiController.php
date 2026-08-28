<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Ts;
use App\Models\RekognisiDosen;
use Illuminate\Http\Request;

class HkiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'nullable|exists:mahasiswas,nim',
            'no_permohonan' => 'required|string|max:255',
            'tgl_permohonan' => 'required|date',
            'jenis_ciptaan' => 'required|string|max:255',
            'judul_ciptaan' => 'required|string|max:255',
            'kode_dosen' => 'nullable|string|max:255',
            'nama_dosen' => 'nullable|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        $hki = Hki::create($request->all());

        return redirect()->back()->with('success', 'Data HKI berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $hki = Hki::findOrFail($id);

        $request->validate([
            'nim' => 'nullable|exists:mahasiswas,nim',
            'no_permohonan' => 'required|string|max:255',
            'tgl_permohonan' => 'required|date',
            'jenis_ciptaan' => 'required|string|max:255',
            'judul_ciptaan' => 'required|string|max:255',
            'kode_dosen' => 'nullable|string|max:255',
            'nama_dosen' => 'nullable|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        $hki->update($request->all());

        return redirect()->back()->with('success', 'Data HKI berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hki = Hki::findOrFail($id);
        
        $hki->delete();

        return redirect()->back()->with('success', 'Data HKI berhasil dihapus.');
    }

}
