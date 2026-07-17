<?php

namespace App\Http\Controllers;

use App\Models\SertifikasiDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;

class SertifikasiDosenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|exists:dosens,kode_dosen',
            'nama_dosen' => 'required|string|max:255',
            'nama_sertifikasi' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        SertifikasiDosen::create($request->all());

        $dosen = Dosen::where('kode_dosen', $request->kode_dosen)->first();

        return redirect()->route('dosen.show', $dosen->id)
            ->with('success', 'Data sertifikasi dosen berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $sertifikasi = SertifikasiDosen::findOrFail($id);

        $request->validate([
            'kode_dosen' => 'required|exists:dosens,kode_dosen',
            'nama_dosen' => 'required|string|max:255',
            'nama_sertifikasi' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        $sertifikasi->update($request->all());

        $dosen = Dosen::where('kode_dosen', $request->kode_dosen)->first();

        return redirect()->route('dosen.show', $dosen->id)
            ->with('success', 'Data sertifikasi dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sertifikasi = SertifikasiDosen::findOrFail($id);
        $dosen = Dosen::where('kode_dosen', $sertifikasi->kode_dosen)->first();
        
        $sertifikasi->delete();

        return redirect()->route('dosen.show', $dosen->id)
            ->with('success', 'Data sertifikasi dosen berhasil dihapus.');
    }
}
