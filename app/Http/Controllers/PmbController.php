<?php

namespace App\Http\Controllers;

use App\Models\Pmb;
use Illuminate\Http\Request;

class PmbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pmbListAsc = Pmb::orderBy('tahun', 'asc')->get();
        
        $prevPmb = null;
        foreach ($pmbListAsc as $p) {
            if ($prevPmb !== null && $prevPmb > 0) {
                $diff = $p->jumlah_pmb - $prevPmb;
                $p->yoy_change = ($diff / $prevPmb) * 100;
            } else {
                $p->yoy_change = null;
            }
            $prevPmb = $p->jumlah_pmb;
        }

        $pmbList = $pmbListAsc->sortByDesc('tahun');
        $totalPmb = $pmbList->sum('jumlah_pmb');
        $avgPmb = $pmbList->count() > 0 ? round($pmbList->avg('jumlah_pmb'), 1) : 0;

        return view('pmb.index', compact('pmbList', 'totalPmb', 'avgPmb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pmb.create');
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
            'tahun' => 'required|integer|min:1900|max:2100|unique:pmb,tahun',
            'jumlah_pmb' => 'required|integer|min:0',
        ]);

        Pmb::create($request->all());

        return redirect()->route('pmb.index')
            ->with('success', 'Data jumlah PMB berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pmb  $pmb
     * @return \Illuminate\Http\Response
     */
    public function show(Pmb $pmb)
    {
        return redirect()->route('pmb.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pmb  $pmb
     * @return \Illuminate\Http\Response
     */
    public function edit(Pmb $pmb)
    {
        return view('pmb.edit', compact('pmb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pmb  $pmb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pmb $pmb)
    {
        $request->validate([
            'tahun' => 'required|integer|min:1900|max:2100|unique:pmb,tahun,' . $pmb->id,
            'jumlah_pmb' => 'required|integer|min:0',
        ]);

        $pmb->update($request->all());

        return redirect()->route('pmb.index')
            ->with('success', 'Data jumlah PMB berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pmb  $pmb
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pmb $pmb)
    {
        $pmb->delete();

        return redirect()->route('pmb.index')
            ->with('success', 'Data jumlah PMB berhasil dihapus.');
    }
}
