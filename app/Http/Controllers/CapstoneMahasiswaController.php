<?php

namespace App\Http\Controllers;

use App\Models\CapstoneMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class CapstoneMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'judul_capstone' => 'required|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
            'matakuliah_ids' => 'required|array|min:1',
            'matakuliah_ids.*' => 'exists:matakuliahs,id',
            'anggota_nims' => 'nullable|array',
            'anggota_nims.*' => 'exists:mahasiswas,nim',
        ]);

        $anggotaNims = $request->input('anggota_nims', []);

        // Validate group members does not already have a capstone project as leader
        $existingLeaderNims = CapstoneMahasiswa::whereIn('nim', array_merge([$request->nim], $anggotaNims))
            ->pluck('nim')
            ->toArray();

        if (!empty($existingLeaderNims)) {
            $names = Mahasiswa::whereIn('nim', $existingLeaderNims)->pluck('nama')->toArray();
            return redirect()->back()
                ->withInput()
                ->withErrors(['anggota_nims' => 'Mahasiswa berikut sudah memiliki proyek capstone (sebagai ketua): ' . implode(', ', $names)]);
        }

        // Validate group members does not already have a capstone project as group member
        $allCapstone = CapstoneMahasiswa::whereNotNull('anggota_kelompok')->get();
        foreach ($allCapstone as $c) {
            $cAnggota = array_map('trim', explode(',', $c->anggota_kelompok));
            if (in_array($request->nim, $cAnggota)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['judul_capstone' => 'Mahasiswa ini sudah terdaftar sebagai anggota kelompok di capstone lain.']);
            }
            foreach ($anggotaNims as $memberNim) {
                if (in_array($memberNim, $cAnggota)) {
                    $mName = Mahasiswa::where('nim', $memberNim)->value('nama');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['anggota_nims' => "Mahasiswa '{$mName}' sudah terdaftar sebagai anggota kelompok di capstone lain."]);
                }
            }
        }

        $data = $request->all();
        if (!empty($anggotaNims)) {
            $data['anggota_kelompok'] = implode(',', $anggotaNims);
        } else {
            $data['anggota_kelompok'] = null;
        }

        $capstone = CapstoneMahasiswa::create($data);
        $capstone->matakuliah()->sync($request->input('matakuliah_ids', []));

        $mhs = Mahasiswa::where('nim', $request->nim)->first();

        return redirect()->route('mahasiswa.show', $mhs->id)
            ->with('success', 'Data proyek capstone berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $capstone = CapstoneMahasiswa::findOrFail($id);

        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'judul_capstone' => 'required|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
            'matakuliah_ids' => 'required|array|min:1',
            'matakuliah_ids.*' => 'exists:matakuliahs,id',
            'anggota_nims' => 'nullable|array',
            'anggota_nims.*' => 'exists:mahasiswas,nim',
        ]);

        $anggotaNims = $request->input('anggota_nims', []);

        // Validate group members does not already have a capstone project as leader
        $existingLeaderNims = CapstoneMahasiswa::where('id', '!=', $id)
            ->whereIn('nim', array_merge([$request->nim], $anggotaNims))
            ->pluck('nim')
            ->toArray();

        if (!empty($existingLeaderNims)) {
            $names = Mahasiswa::whereIn('nim', $existingLeaderNims)->pluck('nama')->toArray();
            return redirect()->back()
                ->withInput()
                ->withErrors(['anggota_nims' => 'Mahasiswa berikut sudah memiliki proyek capstone (sebagai ketua): ' . implode(', ', $names)]);
        }

        // Validate group members does not already have a capstone project as group member
        $allCapstone = CapstoneMahasiswa::where('id', '!=', $id)->whereNotNull('anggota_kelompok')->get();
        foreach ($allCapstone as $c) {
            $cAnggota = array_map('trim', explode(',', $c->anggota_kelompok));
            if (in_array($request->nim, $cAnggota)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['judul_capstone' => 'Mahasiswa ini sudah terdaftar sebagai anggota kelompok di capstone lain.']);
            }
            foreach ($anggotaNims as $memberNim) {
                if (in_array($memberNim, $cAnggota)) {
                    $mName = Mahasiswa::where('nim', $memberNim)->value('nama');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['anggota_nims' => "Mahasiswa '{$mName}' sudah terdaftar sebagai anggota kelompok di capstone lain."]);
                }
            }
        }

        $data = $request->all();
        if (!empty($anggotaNims)) {
            $data['anggota_kelompok'] = implode(',', $anggotaNims);
        } else {
            $data['anggota_kelompok'] = null;
        }

        $capstone->update($data);
        $capstone->matakuliah()->sync($request->input('matakuliah_ids', []));

        $mhs = Mahasiswa::where('nim', $request->nim)->first();

        return redirect()->route('mahasiswa.show', $mhs->id)
            ->with('success', 'Data proyek capstone berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $capstone = CapstoneMahasiswa::findOrFail($id);
        $mhs = Mahasiswa::where('nim', $capstone->nim)->first();
        
        $capstone->delete();

        return redirect()->route('mahasiswa.show', $mhs->id)
            ->with('success', 'Data proyek capstone berhasil dihapus.');
    }
}
