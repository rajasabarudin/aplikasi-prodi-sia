<?php

namespace App\Http\Controllers;

use App\Models\OrganisasiMahasiswa;
use Illuminate\Http\Request;

class OrganisasiMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'nama_organisasi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'periode' => 'required|string|max:255',
            'ts_id' => 'required|exists:ts,id',
            'link_sk' => 'nullable|url|max:255',
        ]);

        OrganisasiMahasiswa::create($request->all());

        return redirect()->back()->with('success', 'Data organisasi mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $organisasi = OrganisasiMahasiswa::findOrFail($id);

        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'periode' => 'required|string|max:255',
            'ts_id' => 'required|exists:ts,id',
            'link_sk' => 'nullable|url|max:255',
        ]);

        $organisasi->update($request->all());

        return redirect()->back()->with('success', 'Data organisasi mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $organisasi = OrganisasiMahasiswa::findOrFail($id);
        $organisasi->delete();

        return redirect()->back()->with('success', 'Data organisasi mahasiswa berhasil dihapus.');
    }
}
