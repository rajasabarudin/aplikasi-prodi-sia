<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::latest()->get();
        
        // Ambil jumlah mahasiswa per kelas
        $mahasiswaCounts = \App\Models\Mahasiswa::groupBy('kelas')
            ->selectRaw('kelas, count(*) as count')
            ->pluck('count', 'kelas')
            ->toArray();

        foreach ($kelas as $k) {
            $k->jumlah_mahasiswa = $mahasiswaCounts[$k->nama_kelas] ?? 0;
        }

        $totalKelas = $kelas->count();

        // Hitung jumlah mahasiswa berdasarkan tingkat semester (2, 4, 6)
        $semesterCounts = [
            2 => 0,
            4 => 0,
            6 => 0
        ];

        $allMahasiswa = \App\Models\Mahasiswa::all();
        foreach ($allMahasiswa as $m) {
            $kelasName = $m->kelas;
            $parts = explode('.', $kelasName);
            if (count($parts) >= 2) {
                $semChar = substr($parts[1], 0, 1);
                if (in_array($semChar, ['2', '4', '6'])) {
                    $semesterCounts[intval($semChar)]++;
                }
            } else {
                if (str_contains($kelasName, '2')) {
                    $semesterCounts[2]++;
                } elseif (str_contains($kelasName, '4')) {
                    $semesterCounts[4]++;
                } elseif (str_contains($kelasName, '6')) {
                    $semesterCounts[6]++;
                }
            }
        }

        return view('kelas.index', compact('kelas', 'totalKelas', 'semesterCounts'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kela)
    {
        return view('kelas.show', compact('kela'));
    }

    public function edit(Kelas $kela)
    {
        return view('kelas.edit', compact('kela'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $kela->id,
        ]);

        $kela->update($request->all());

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
