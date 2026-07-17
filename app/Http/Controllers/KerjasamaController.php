<?php

namespace App\Http\Controllers;

use App\Models\Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Kerjasama::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_mitra', 'like', "%{$search}%")
                  ->orWhere('nomor_mou_ubsi', 'like', "%{$search}%")
                  ->orWhere('nomor_mou_mitra', 'like', "%{$search}%")
                  ->orWhere('ketua_mewakili', 'like', "%{$search}%")
                  ->orWhere('tahun_mou', 'like', "%{$search}%")
                  ->orWhere('tahun_berakhir', 'like', "%{$search}%");
            });
        }

        // Get the limit parameter
        $limit = $request->input('limit', 10);
        if (!in_array($limit, [10, 20, 50, 100, 500])) {
            $limit = 10;
        }

        // Paginate the table list
        $kerjasamaList = $query->orderBy('id', 'desc')->paginate($limit)->withQueryString();

        // Calculate statistics using all records to keep them accurate
        $allKerjasama = Kerjasama::all();
        $totalKerjasama = $allKerjasama->count();
        $kerjasamaTahunIni = $allKerjasama->where('tahun_mou', date('Y'))->count();
        
        // Calculate the number of PKS documents for each partner
        $pksPerMitra = \App\Models\PksIa::selectRaw('nama_mitra, COUNT(*) as jumlah')
            ->groupBy('nama_mitra')
            ->orderBy('jumlah', 'desc')
            ->get()
            ->pluck('jumlah', 'nama_mitra');

        // Calculate MoUs that will expire this year or have already expired
        $mouAkanBerakhir = $allKerjasama->where('tahun_berakhir', date('Y'))->count();
        $mouTelahBerakhir = $allKerjasama->where('tahun_berakhir', '<', date('Y'))->count();
        $mouAktif = $allKerjasama->filter(function($item) {
            return is_null($item->tahun_berakhir) || $item->tahun_berakhir > date('Y');
        })->count();

        // Calculate MoU per year for the Dosen-style sidebar statistics
        $mouPerTahun = $allKerjasama->groupBy('tahun_mou')->map->count()->sortKeysDesc();

        return view('kerjasama.index', compact(
            'kerjasamaList', 
            'totalKerjasama', 
            'kerjasamaTahunIni', 
            'pksPerMitra', 
            'mouAkanBerakhir', 
            'mouTelahBerakhir', 
            'mouAktif', 
            'mouPerTahun',
            'limit'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kerjasama.create');
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
            'tahun_mou' => 'required|integer|min:1900|max:2100',
            'tahun_berakhir' => 'nullable|integer|min:1900|max:2100|gte:tahun_mou',
            'nomor_mou_ubsi' => 'required|string|max:255',
            'nomor_mou_mitra' => 'nullable|string|max:255',
            'nama_mitra' => 'required|string|max:255',
            'ketua_mewakili' => 'required|string|max:255',
            'no_wa_mitra' => 'nullable|string|max:20',
            'file_mou' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_mou')) {
            $data['file_mou'] = $request->file('file_mou')->store('file_mou', 'public');
        }

        Kerjasama::create($data);

        return redirect()->route('kerjasama.index')
            ->with('success', 'Data kerjasama berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function show(Kerjasama $kerjasama)
    {
        return view('kerjasama.show', compact('kerjasama'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function edit(Kerjasama $kerjasama)
    {
        return view('kerjasama.edit', compact('kerjasama'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kerjasama $kerjasama)
    {
        $request->validate([
            'tahun_mou' => 'required|integer|min:1900|max:2100',
            'tahun_berakhir' => 'nullable|integer|min:1900|max:2100|gte:tahun_mou',
            'nomor_mou_ubsi' => 'required|string|max:255',
            'nomor_mou_mitra' => 'nullable|string|max:255',
            'nama_mitra' => 'required|string|max:255',
            'ketua_mewakili' => 'required|string|max:255',
            'no_wa_mitra' => 'nullable|string|max:20',
            'file_mou' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_mou')) {
            if ($kerjasama->file_mou && Storage::disk('public')->exists($kerjasama->file_mou)) {
                Storage::disk('public')->delete($kerjasama->file_mou);
            }
            $data['file_mou'] = $request->file('file_mou')->store('file_mou', 'public');
        }

        $kerjasama->update($data);

        return redirect()->route('kerjasama.index')
            ->with('success', 'Data kerjasama berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kerjasama  $kerjasama
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kerjasama $kerjasama)
    {
        if ($kerjasama->file_mou && Storage::disk('public')->exists($kerjasama->file_mou)) {
            Storage::disk('public')->delete($kerjasama->file_mou);
        }

        $kerjasama->delete();

        return redirect()->route('kerjasama.index')
            ->with('success', 'Data kerjasama berhasil dihapus.');
    }
}
