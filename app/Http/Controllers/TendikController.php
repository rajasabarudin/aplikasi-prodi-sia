<?php

namespace App\Http\Controllers;

use App\Models\Tendik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TendikController extends Controller
{
    public function index()
    {
        $tendiks = Tendik::all();
        return view('tendik.index', compact('tendiks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip_nik' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan_tugas' => 'nullable|string|max:255',
            'status_pegawai' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'bukti_sertifikasi' => 'nullable|url'
        ]);

        Tendik::create($validated);

        return redirect()->route('tendik.index')->with('success', 'Data tenaga kependidikan berhasil ditambahkan.');
    }

    public function update(Request $request, Tendik $tendik)
    {
        $validated = $request->validate([
            'nip_nik' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan_tugas' => 'nullable|string|max:255',
            'status_pegawai' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'bukti_sertifikasi' => 'nullable|url'
        ]);

        $tendik->update($validated);

        return redirect()->route('tendik.index')->with('success', 'Data tenaga kependidikan berhasil diperbarui.');
    }

    public function destroy(Tendik $tendik)
    {
        $tendik->delete();
        return redirect()->route('tendik.index')->with('success', 'Data tenaga kependidikan berhasil dihapus.');
    }
}
