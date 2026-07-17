<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use Illuminate\Http\Request;

class CplController extends Controller
{
    public function index()
    {
        $cpls = Cpl::orderBy('kode_cpl', 'asc')->get();
        return view('rps.cpl.index', compact('cpls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cpl'     => 'required|string|max:20|unique:cpls,kode_cpl',
            'deskripsi_cpl' => 'required|string',
        ]);

        Cpl::create($request->all());

        return redirect()->route('rps-cpl.index')->with('success', 'Data CPL berhasil ditambahkan.');
    }

    public function edit(Cpl $rpsCpl)
    {
        return view('rps.cpl.edit', ['cpl' => $rpsCpl]);
    }

    public function update(Request $request, Cpl $rpsCpl)
    {
        $request->validate([
            'kode_cpl'     => 'required|string|max:20|unique:cpls,kode_cpl,' . $rpsCpl->id,
            'deskripsi_cpl' => 'required|string',
        ]);

        $rpsCpl->update($request->all());

        return redirect()->route('rps-cpl.index')->with('success', 'Data CPL berhasil diperbarui.');
    }

    public function destroy(Cpl $rpsCpl)
    {
        $rpsCpl->delete();
        return redirect()->route('rps-cpl.index')->with('success', 'Data CPL berhasil dihapus.');
    }
}
