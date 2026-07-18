<?php

namespace App\Http\Controllers;

use App\Models\BeasiswaMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class BeasiswaMahasiswaController extends Controller
{
    public function index()
    {
        $beasiswas = BeasiswaMahasiswa::with('mahasiswa')->orderBy('created_at', 'desc')->get();
        return view('beasiswa_mahasiswa.index', compact('beasiswas'));
    }

    public function getMahasiswa($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if ($mahasiswa) {
            return response()->json(['success' => true, 'nama' => $mahasiswa->nama]);
        }
        return response()->json(['success' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'jenis_beasiswa' => 'required|in:internal,eksternal',
            'kategori_beasiswa' => 'required',
            'link_dokumen' => 'nullable|url',
        ]);

        BeasiswaMahasiswa::create($request->all());

        return redirect()->route('beasiswa-mahasiswa.index')->with('success', 'Data beasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, BeasiswaMahasiswa $beasiswa_mahasiswa)
    {
        $request->validate([
            'nim' => 'required',
            'jenis_beasiswa' => 'required|in:internal,eksternal',
            'kategori_beasiswa' => 'required',
            'link_dokumen' => 'nullable|url',
        ]);

        $beasiswa_mahasiswa->update($request->all());

        return redirect()->route('beasiswa-mahasiswa.index')->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function destroy(BeasiswaMahasiswa $beasiswa_mahasiswa)
    {
        $beasiswa_mahasiswa->delete();
        return redirect()->route('beasiswa-mahasiswa.index')->with('success', 'Data beasiswa berhasil dihapus.');
    }

    // --- PUBLIC METHODS ---

    public function publicIndex()
    {
        $beasiswas = BeasiswaMahasiswa::with('mahasiswa')->orderBy('created_at', 'desc')->get();
        return view('beasiswa_mahasiswa.public_index', compact('beasiswas'));
    }

    public function publicStore(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'jenis_beasiswa' => 'required|in:internal,eksternal',
            'kategori_beasiswa' => 'required',
            'link_dokumen' => 'nullable|url',
        ]);

        // Cek apakah mahasiswa valid
        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        BeasiswaMahasiswa::create($request->all());

        return redirect()->route('portal.beasiswa')->with('success', 'Data beasiswa berhasil didaftarkan.');
    }

    public function publicUpdate(Request $request, $id)
    {
        $request->validate([
            'jenis_beasiswa' => 'required|in:internal,eksternal',
            'kategori_beasiswa' => 'required',
            'link_dokumen' => 'nullable|url',
        ]);

        $beasiswa = BeasiswaMahasiswa::findOrFail($id);
        $beasiswa->update($request->all());

        return redirect()->route('portal.beasiswa')->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function publicDestroy($id)
    {
        $beasiswa = BeasiswaMahasiswa::findOrFail($id);
        $beasiswa->delete();
        return redirect()->route('portal.beasiswa')->with('success', 'Data beasiswa berhasil dihapus.');
    }
}
