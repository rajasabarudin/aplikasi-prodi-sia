<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matakuliah;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Matakuliah::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_matakuliah', 'like', '%' . $search . '%')
                  ->orWhere('nama_matakuliah', 'like', '%' . $search . '%')
                  ->orWhere('jenis_matakuliah', 'like', '%' . $search . '%')
                  ->orWhere('semester', 'like', '%' . $search . '%');
            });
        }

        $perPage = in_array($request->get('per_page'), [10, 20, 50, 100]) ? intval($request->get('per_page')) : 20;
        $matakuliahs = $query->orderByRaw("CASE semester WHEN 'I' THEN 1 WHEN 'II' THEN 2 WHEN 'III' THEN 3 WHEN 'IV' THEN 4 WHEN 'V' THEN 5 WHEN 'VI' THEN 6 WHEN 'VII' THEN 7 WHEN 'VIII' THEN 8 ELSE 9 END")
                             ->orderBy('kode_matakuliah', 'asc')
                             ->paginate($perPage)
                             ->withQueryString();

        // Statistics
        $totalMatakuliah = Matakuliah::count();
        $totalSks = Matakuliah::sum('sks');
        
        $matakuliahByJenis = Matakuliah::select('jenis_matakuliah')
            ->selectRaw('count(*) as total')
            ->groupBy('jenis_matakuliah')
            ->get();

        $matakuliahBySemester = Matakuliah::select('semester')
            ->selectRaw('count(*) as total')
            ->groupBy('semester')
            ->orderBy('semester', 'asc')
            ->get();

        $allMatakuliah = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();

        return view('matakuliah.index', compact('matakuliahs', 'search', 'perPage', 'totalMatakuliah', 'totalSks', 'matakuliahByJenis', 'allMatakuliah', 'matakuliahBySemester'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matakuliah' => 'required|string|max:50|unique:matakuliahs,kode_matakuliah',
            'nama_matakuliah' => 'required|string|max:255',
            'sks_t' => 'required|integer|min:0',
            'sks_pa' => 'required|integer|min:0',
            'sks_pu' => 'required|integer|min:0',
            'jenis_matakuliah' => 'required|string|in:Ciri Nasional,Ciri Institusi,Inti Program Studi,Pendukung',
            'semester' => 'required|string|in:I,II,III,IV,V,VI,VII,VIII',
            'link_modul' => 'nullable|url|max:255',
            'link_rps' => 'nullable|url|max:255',
            'link_rtm' => 'nullable|url|max:255',
            'dokumen_tambahan' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['sks'] = $request->sks_t + $request->sks_pa + $request->sks_pu;

        Matakuliah::create($data);

        return redirect()->route('matakuliah.index')->with('success', 'Data matakuliah berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);

        $request->validate([
            'kode_matakuliah' => 'required|string|max:50|unique:matakuliahs,kode_matakuliah,' . $id,
            'nama_matakuliah' => 'required|string|max:255',
            'sks_t' => 'required|integer|min:0',
            'sks_pa' => 'required|integer|min:0',
            'sks_pu' => 'required|integer|min:0',
            'jenis_matakuliah' => 'required|string|in:Ciri Nasional,Ciri Institusi,Inti Program Studi,Pendukung',
            'semester' => 'required|string|in:I,II,III,IV,V,VI,VII,VIII',
            'link_modul' => 'nullable|url|max:255',
            'link_rps' => 'nullable|url|max:255',
            'link_rtm' => 'nullable|url|max:255',
            'dokumen_tambahan' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['sks'] = $request->sks_t + $request->sks_pa + $request->sks_pu;

        $matakuliah->update($data);

        return redirect()->route('matakuliah.index')->with('success', 'Data matakuliah berhasil diperbarui.');
    }

    public function updateSks(Request $request, $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);

        $request->validate([
            'sks_t' => 'required|integer|min:0',
            'sks_pa' => 'required|integer|min:0',
            'sks_pu' => 'required|integer|min:0',
        ]);

        $sks = $request->sks_t + $request->sks_pa + $request->sks_pu;

        $matakuliah->update([
            'sks_t' => $request->sks_t,
            'sks_pa' => $request->sks_pa,
            'sks_pu' => $request->sks_pu,
            'sks' => $sks,
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'SKS matakuliah ' . $matakuliah->nama_matakuliah . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')->with('success', 'Data matakuliah berhasil dihapus.');
    }

    public function clearDocument($id, $type)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        
        $validTypes = ['modul', 'rps', 'rtm', 'dokumen_tambahan'];
        if (in_array($type, $validTypes)) {
            $columnName = $type == 'dokumen_tambahan' ? 'dokumen_tambahan' : 'link_' . $type;
            $matakuliah->update([$columnName => null]);
            return redirect()->route('matakuliah.index')->with('success', 'Link ' . strtoupper($type) . ' berhasil dihapus.');
        }

        return redirect()->route('matakuliah.index')->with('error', 'Tipe dokumen tidak valid.');
    }
}
