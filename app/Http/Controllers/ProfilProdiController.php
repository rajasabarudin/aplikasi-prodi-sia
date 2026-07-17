<?php

namespace App\Http\Controllers;

use App\Models\ProfilProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilProdiController extends Controller
{
    public function index()
    {
        $profil = ProfilProdi::first();
        if (!$profil) {
            $profil = ProfilProdi::create([]);
        }
        return view('admin.profil_prodi.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = ProfilProdi::first();
        if (!$profil) {
            $profil = ProfilProdi::create([]);
        }

        $request->validate([
            'deskripsi_profil' => 'nullable|string',
            'visi_keilmuan' => 'nullable|string',
            'nama_kaprodi' => 'nullable|string|max:255',
            'foto_kaprodi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'informasi_staf' => 'nullable|string',
            'akreditasi' => 'nullable|string|max:255',
            'lama_masa_studi' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['foto_kaprodi', '_token', '_method']);

        if ($request->hasFile('foto_kaprodi')) {
            if ($profil->foto_kaprodi && Storage::disk('public')->exists($profil->foto_kaprodi)) {
                Storage::disk('public')->delete($profil->foto_kaprodi);
            }
            $data['foto_kaprodi'] = $request->file('foto_kaprodi')->store('profil', 'public');
        }

        $profil->update($data);

        return redirect()->route('profil-prodi.index')->with('success', 'Profil Prodi berhasil diperbarui!');
    }
}
