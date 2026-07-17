<?php

namespace App\Http\Controllers;

use App\Models\Praktisi;
use App\Models\Matakuliah;
use App\Models\Kelas;
use App\Models\TS;
use Illuminate\Http\Request;

class PraktisiController extends Controller
{
    public function index()
    {
        $praktisi = Praktisi::with('ts')->latest()->get();
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $tsList = TS::orderBy('tahun_sekarang', 'desc')->get();

        // Calculate statistics
        $allCodes = [];
        $totalSksTaught = 0;
        foreach ($praktisi as $p) {
            $pMKs = $p->matakuliahs;
            $totalSksTaught += $pMKs->sum('sks');
            $allCodes = array_merge($allCodes, $p->kode_matakuliah ?? []);
        }
        $totalUniqueMatakuliah = count(array_unique($allCodes));

        // Count practitioners per TS
        $praktisiPerTs = [];
        foreach ($tsList as $ts) {
            $name = $ts->tahun_sekarang . ' - ' . $ts->semester;
            $praktisiPerTs[$name] = $praktisi->where('ts_id', $ts->id)->count();
        }
        
        $labelTsPraktisi = $praktisi->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map->count()
            ->sortDesc()
            ->toArray();

        return view('praktisi.index', compact('praktisi', 'matakuliahList', 'kelasList', 'tsList', 'totalSksTaught', 'totalUniqueMatakuliah', 'praktisiPerTs', 'labelTsPraktisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_praktisi' => 'required|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'ts_id' => 'required|exists:ts,id',
            'kode_matakuliah' => 'required|array',
            'kode_matakuliah.*' => 'required|string',
            'kelas' => 'required|array',
            'kelas.*' => 'required|string',
            'link_ijazah' => 'nullable|string|max:2048',
            'link_sertifikasi' => 'nullable|string|max:2048',
            'link_dokumen' => 'nullable|string|max:2048',
        ]);

        Praktisi::create($request->all());

        return redirect()->route('praktisi.index')
            ->with('success', 'Data praktisi berhasil ditambahkan.');
    }

    public function edit(Praktisi $praktisi)
    {
        $matakuliahList = Matakuliah::orderBy('nama_matakuliah', 'asc')->get();
        $kelasList = Kelas::orderBy('nama_kelas', 'asc')->get();
        $tsList = TS::orderBy('tahun_sekarang', 'desc')->get();

        return view('praktisi.edit', compact('praktisi', 'matakuliahList', 'kelasList', 'tsList'));
    }

    public function update(Request $request, Praktisi $praktisi)
    {
        $request->validate([
            'nama_praktisi' => 'required|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'ts_id' => 'required|exists:ts,id',
            'kode_matakuliah' => 'required|array',
            'kode_matakuliah.*' => 'required|string',
            'kelas' => 'required|array',
            'kelas.*' => 'required|string',
            'link_ijazah' => 'nullable|string|max:2048',
            'link_sertifikasi' => 'nullable|string|max:2048',
            'link_dokumen' => 'nullable|string|max:2048',
        ]);

        $praktisi->update($request->all());

        return redirect()->route('praktisi.index')
            ->with('success', 'Data praktisi berhasil diperbarui.');
    }

    public function destroy(Praktisi $praktisi)
    {
        $praktisi->delete();

        return redirect()->route('praktisi.index')
            ->with('success', 'Data praktisi berhasil dihapus.');
    }

    public function uploadDokumen(Request $request, Praktisi $praktisi)
    {
        $request->validate([
            'tipe_dokumen' => 'required|in:link_ijazah,link_sertifikasi,link_dokumen',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120',
        ]);

        $tipe = $request->tipe_dokumen;

        // Delete old file if it exists in local storage
        if ($praktisi->$tipe && !filter_var($praktisi->$tipe, FILTER_VALIDATE_URL)) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($praktisi->$tipe)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($praktisi->$tipe);
            }
        }

        // Store new file
        $path = $request->file('file_dokumen')->store('praktisi_dokumen', 'public');
        
        $praktisi->update([
            $tipe => $path
        ]);

        return redirect()->route('praktisi.index')
            ->with('success', 'Dokumen berhasil diunggah.');
    }
}
