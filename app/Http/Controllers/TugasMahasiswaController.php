<?php

namespace App\Http\Controllers;

use App\Models\TugasMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TugasMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'matakuliah_id' => [
                'required',
                'exists:matakuliahs,id',
                Rule::unique('tugas_mahasiswas')->where(function ($q) use ($request) {
                    return $q->where('nim', $request->nim);
                }),
            ],
            'link_dokumen' => 'nullable|url|max:255',
            'anggota_nims' => 'nullable|array',
            'anggota_nims.*' => 'exists:mahasiswas,nim',
        ], [
            'matakuliah_id.unique' => 'Mahasiswa ini sudah memiliki tugas untuk matakuliah tersebut.',
        ]);

        $matakuliahId = $request->input('matakuliah_id');
        $anggotaNims = $request->input('anggota_nims', []);

        // Validate group members does not already have an assignment as leader
        $existingLeaderNims = TugasMahasiswa::where('matakuliah_id', $matakuliahId)
            ->whereIn('nim', array_merge([$request->nim], $anggotaNims))
            ->pluck('nim')
            ->toArray();

        if (!empty($existingLeaderNims)) {
            $names = Mahasiswa::whereIn('nim', $existingLeaderNims)->pluck('nama')->toArray();
            return redirect()->back()
                ->withInput()
                ->withErrors(['anggota_nims' => 'Mahasiswa berikut sudah memiliki tugas untuk matakuliah ini (sebagai ketua): ' . implode(', ', $names)]);
        }

        // Validate group members does not already have an assignment as group member
        $allTugas = TugasMahasiswa::where('matakuliah_id', $matakuliahId)->whereNotNull('anggota_kelompok')->get();
        foreach ($allTugas as $t) {
            $tAnggota = array_map('trim', explode(',', $t->anggota_kelompok));
            if (in_array($request->nim, $tAnggota)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['matakuliah_id' => 'Mahasiswa ini sudah terdaftar sebagai anggota kelompok di tugas lain untuk matakuliah ini.']);
            }
            foreach ($anggotaNims as $memberNim) {
                if (in_array($memberNim, $tAnggota)) {
                    $mName = Mahasiswa::where('nim', $memberNim)->value('nama');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['anggota_nims' => "Mahasiswa '{$mName}' sudah terdaftar sebagai anggota kelompok di tugas lain untuk matakuliah ini."]);
                }
            }
        }

        $data = $request->all();
        if (!empty($anggotaNims)) {
            $data['anggota_kelompok'] = implode(',', $anggotaNims);
        } else {
            $data['anggota_kelompok'] = null;
        }

        TugasMahasiswa::create($data);

        $mhs = Mahasiswa::where('nim', $request->nim)->first();

        return redirect()->route('mahasiswa.show', $mhs->id)
            ->with('success', 'Data tugas mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tugas = TugasMahasiswa::findOrFail($id);

        $request->validate([
            'nim' => 'required|exists:mahasiswas,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'matakuliah_id' => [
                'required',
                'exists:matakuliahs,id',
                Rule::unique('tugas_mahasiswas')->ignore($id)->where(function ($q) use ($request) {
                    return $q->where('nim', $request->nim);
                }),
            ],
            'link_dokumen' => 'nullable|url|max:255',
            'anggota_nims' => 'nullable|array',
            'anggota_nims.*' => 'exists:mahasiswas,nim',
        ], [
            'matakuliah_id.unique' => 'Mahasiswa ini sudah memiliki tugas untuk matakuliah tersebut.',
        ]);

        $matakuliahId = $request->input('matakuliah_id');
        $anggotaNims = $request->input('anggota_nims', []);

        // Validate group members does not already have an assignment as leader
        $existingLeaderNims = TugasMahasiswa::where('matakuliah_id', $matakuliahId)
            ->where('id', '!=', $id)
            ->whereIn('nim', array_merge([$request->nim], $anggotaNims))
            ->pluck('nim')
            ->toArray();

        if (!empty($existingLeaderNims)) {
            $names = Mahasiswa::whereIn('nim', $existingLeaderNims)->pluck('nama')->toArray();
            return redirect()->back()
                ->withInput()
                ->withErrors(['anggota_nims' => 'Mahasiswa berikut sudah memiliki tugas untuk matakuliah ini (sebagai ketua): ' . implode(', ', $names)]);
        }

        // Validate group members does not already have an assignment as group member
        $allTugas = TugasMahasiswa::where('matakuliah_id', $matakuliahId)->where('id', '!=', $id)->whereNotNull('anggota_kelompok')->get();
        foreach ($allTugas as $t) {
            $tAnggota = array_map('trim', explode(',', $t->anggota_kelompok));
            if (in_array($request->nim, $tAnggota)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['matakuliah_id' => 'Mahasiswa ini sudah terdaftar sebagai anggota kelompok di tugas lain untuk matakuliah ini.']);
            }
            foreach ($anggotaNims as $memberNim) {
                if (in_array($memberNim, $tAnggota)) {
                    $mName = Mahasiswa::where('nim', $memberNim)->value('nama');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['anggota_nims' => "Mahasiswa '{$mName}' sudah terdaftar sebagai anggota kelompok di tugas lain untuk matakuliah ini."]);
                }
            }
        }

        $data = $request->all();
        if (!empty($anggotaNims)) {
            $data['anggota_kelompok'] = implode(',', $anggotaNims);
        } else {
            $data['anggota_kelompok'] = null;
        }

        $tugas->update($data);

        $mhs = Mahasiswa::where('nim', $request->nim)->first();

        return redirect()->route('mahasiswa.show', $mhs->id)
            ->with('success', 'Data tugas mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tugas = TugasMahasiswa::findOrFail($id);
        $mhs = Mahasiswa::where('nim', $tugas->nim)->first();
        
        $tugas->delete();

        return redirect()->route('mahasiswa.show', $mhs->id)
            ->with('success', 'Data tugas mahasiswa berhasil dihapus.');
    }
}
