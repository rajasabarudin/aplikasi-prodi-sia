<?php

namespace App\Http\Controllers;

use App\Models\PksIa;
use App\Models\Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PksIaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pksIaList = PksIa::orderBy('id', 'desc')->get();
        $totalPksIa = $pksIaList->count();
        $kerjasamaList = Kerjasama::orderBy('nama_mitra', 'asc')->get();

        // Count by category
        $pendidikanCount = PksIa::where('kategori', 'Pendidikan')->count();
        $penelitianCount = PksIa::where('kategori', 'Penelitian')->count();
        $pkmCount = PksIa::where('kategori', 'PKM')->count();

        // Calculate unique partners with PKS grouped by year
        $pksPerTahun = $pksIaList->groupBy(function($item) {
            return $item->tgl_pks->format('Y');
        })->map(function($group) {
            return $group->pluck('nama_mitra')->unique()->count();
        })->sortKeysDesc();

        // Calculate total PKS documents per year sorted ascending for chronological chart
        $pksDokumenPerTahun = $pksIaList->groupBy(function($item) {
            return $item->tgl_pks->format('Y');
        })->map(function($group) {
            return $group->count();
        })->sortKeys();

        $chartLabels = $pksDokumenPerTahun->keys()->toArray();
        $chartData = $pksDokumenPerTahun->values()->toArray();

        return view('pks_ia.index', compact(
            'pksIaList', 
            'totalPksIa', 
            'kerjasamaList', 
            'pendidikanCount', 
            'penelitianCount', 
            'pkmCount',
            'pksPerTahun',
            'chartLabels',
            'chartData'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pks_ia.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'tgl_pks' => 'required|date',
            'tgl_ia' => 'nullable|date',
            'no_pks_ubsi' => 'required|string|max:255',
            'no_pks_mitra' => 'nullable|string|max:255',
            'no_ia_ubsi' => 'nullable|string|max:255',
            'no_ia_mitra' => 'nullable|string|max:255',
            'tema_pks' => 'required|string|max:255',
            'judul_ia' => 'nullable|string|max:255',
            'kategori' => 'required|in:Pendidikan,PKM,Penelitian',
            'level_pks' => 'required|in:Lokal/Wilayah,Nasional,Internasional',
            'file_pks' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
            'file_ia' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
            'file_tambahan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_pks')) {
            $data['file_pks'] = $request->file('file_pks')->store('pks_ia/pks', 'public');
        }
        if ($request->hasFile('file_ia')) {
            $data['file_ia'] = $request->file('file_ia')->store('pks_ia/ia', 'public');
        }
        if ($request->hasFile('file_tambahan')) {
            $data['file_tambahan'] = $request->file('file_tambahan')->store('pks_ia/tambahan', 'public');
        }

        PksIa::create($data);

        return redirect()->route('pks-ia.index')
            ->with('success', 'Data PKS dan IA berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PksIa  $pksIa
     * @return \Illuminate\Http\Response
     */
    public function show(PksIa $pksIa)
    {
        return view('pks_ia.show', compact('pksIa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PksIa  $pksIa
     * @return \Illuminate\Http\Response
     */
    public function edit(PksIa $pksIa)
    {
        $kerjasamaList = Kerjasama::orderBy('nama_mitra', 'asc')->get();
        return view('pks_ia.edit', compact('pksIa', 'kerjasamaList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PksIa  $pksIa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PksIa $pksIa)
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'tgl_pks' => 'required|date',
            'tgl_ia' => 'nullable|date',
            'no_pks_ubsi' => 'required|string|max:255',
            'no_pks_mitra' => 'nullable|string|max:255',
            'no_ia_ubsi' => 'nullable|string|max:255',
            'no_ia_mitra' => 'nullable|string|max:255',
            'tema_pks' => 'required|string|max:255',
            'judul_ia' => 'nullable|string|max:255',
            'kategori' => 'required|in:Pendidikan,PKM,Penelitian',
            'level_pks' => 'required|in:Lokal/Wilayah,Nasional,Internasional',
            'file_pks' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
            'file_ia' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
            'file_tambahan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_pks')) {
            if ($pksIa->file_pks && Storage::disk('public')->exists($pksIa->file_pks)) {
                Storage::disk('public')->delete($pksIa->file_pks);
            }
            $data['file_pks'] = $request->file('file_pks')->store('pks_ia/pks', 'public');
        }
        if ($request->hasFile('file_ia')) {
            if ($pksIa->file_ia && Storage::disk('public')->exists($pksIa->file_ia)) {
                Storage::disk('public')->delete($pksIa->file_ia);
            }
            $data['file_ia'] = $request->file('file_ia')->store('pks_ia/ia', 'public');
        }
        if ($request->hasFile('file_tambahan')) {
            if ($pksIa->file_tambahan && Storage::disk('public')->exists($pksIa->file_tambahan)) {
                Storage::disk('public')->delete($pksIa->file_tambahan);
            }
            $data['file_tambahan'] = $request->file('file_tambahan')->store('pks_ia/tambahan', 'public');
        }

        $pksIa->update($data);

        return redirect()->route('pks-ia.index')
            ->with('success', 'Data PKS dan IA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PksIa  $pksIa
     * @return \Illuminate\Http\Response
     */
    public function destroy(PksIa $pksIa)
    {
        if ($pksIa->file_pks && Storage::disk('public')->exists($pksIa->file_pks)) {
            Storage::disk('public')->delete($pksIa->file_pks);
        }
        if ($pksIa->file_ia && Storage::disk('public')->exists($pksIa->file_ia)) {
            Storage::disk('public')->delete($pksIa->file_ia);
        }
        if ($pksIa->file_tambahan && Storage::disk('public')->exists($pksIa->file_tambahan)) {
            Storage::disk('public')->delete($pksIa->file_tambahan);
        }

        $pksIa->delete();

        return redirect()->route('pks-ia.index')
            ->with('success', 'Data PKS dan IA berhasil dihapus.');
    }
}
