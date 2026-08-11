<?php

namespace App\Http\Controllers;

use App\Models\KegiatanMahasiswa;
use Illuminate\Http\Request;

class KegiatanMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'tgl_kegiatan' => 'required|date',
            'penyelenggara' => 'required|string',
            'link_bukti_kegiatan' => 'nullable|url',
        ]);

        KegiatanMahasiswa::create($request->all());

        return redirect()->back()->with('success', 'Kegiatan mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, KegiatanMahasiswa $kegiatan_mahasiswa)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'tgl_kegiatan' => 'required|date',
            'penyelenggara' => 'required|string',
            'link_bukti_kegiatan' => 'nullable|url',
        ]);

        $kegiatan_mahasiswa->update($request->all());

        return redirect()->back()->with('success', 'Kegiatan mahasiswa berhasil diperbarui.');
    }

    public function destroy(KegiatanMahasiswa $kegiatan_mahasiswa)
    {
        $kegiatan_mahasiswa->delete();

        return redirect()->back()->with('success', 'Kegiatan mahasiswa berhasil dihapus.');
    }
}
