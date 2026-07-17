<?php

namespace App\Http\Controllers;

use App\Models\Cpmk;
use App\Models\Cpl;
use App\Models\Matakuliah;
use Illuminate\Http\Request;

class CpmkController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit', 10);

        $query = Cpmk::with(['cpl', 'matakuliahs']);

        if ($search) {
            $query->where('kode_cpmk', 'like', "%{$search}%")
                  ->orWhere('deskripsi_cpmk', 'like', "%{$search}%");
        }

        if ($limit === 'all') {
            $cpmks = $query->orderBy('kode_cpmk', 'asc')->get();
        } else {
            $cpmks = $query->orderBy('kode_cpmk', 'asc')->paginate($limit)->withQueryString();
        }

        $totalCpmk = Cpmk::count();
        $distribusiCpl = Cpl::withCount('cpmks')->orderBy('kode_cpl', 'asc')->get();
        $cpls = Cpl::orderBy('kode_cpl', 'asc')->get();
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        return view('rps.cpmk.index', compact('cpmks', 'totalCpmk', 'distribusiCpl', 'cpls', 'matakuliahList', 'search', 'limit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cpmk'     => 'required|string|max:30|unique:cpmks,kode_cpmk',
            'deskripsi_cpmk' => 'required|string',
            'cpl_id'        => 'required|exists:cpls,id',
            'kode_matakuliah' => 'required|array',
            'kode_matakuliah.*' => 'exists:matakuliahs,kode_matakuliah',
        ]);

        $cpmk = Cpmk::create($request->except('kode_matakuliah'));
        $cpmk->matakuliahs()->sync($request->kode_matakuliah);

        return redirect()->route('rps-cpmk.index')->with('success', 'Data CPMK berhasil ditambahkan.');
    }

    public function edit(Cpmk $rpsCpmk)
    {
        $cpls = Cpl::orderBy('kode_cpl', 'asc')->get();
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        return view('rps.cpmk.edit', ['cpmk' => $rpsCpmk, 'cpls' => $cpls, 'matakuliahList' => $matakuliahList]);
    }

    public function update(Request $request, Cpmk $rpsCpmk)
    {
        $request->validate([
            'kode_cpmk'     => 'required|string|max:30|unique:cpmks,kode_cpmk,' . $rpsCpmk->id,
            'deskripsi_cpmk' => 'required|string',
            'cpl_id'        => 'required|exists:cpls,id',
            'kode_matakuliah' => 'required|array',
            'kode_matakuliah.*' => 'exists:matakuliahs,kode_matakuliah',
        ]);

        $rpsCpmk->update($request->except('kode_matakuliah'));
        $rpsCpmk->matakuliahs()->sync($request->kode_matakuliah);

        return redirect()->route('rps-cpmk.index')->with('success', 'Data CPMK berhasil diperbarui.');
    }

    public function destroy(Cpmk $rpsCpmk)
    {
        $rpsCpmk->delete();
        return redirect()->route('rps-cpmk.index')->with('success', 'Data CPMK berhasil dihapus.');
    }
}
