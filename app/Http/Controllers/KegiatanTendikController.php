<?php

namespace App\Http\Controllers;

use App\Models\KegiatanTendik;
use App\Models\Tendik;
use App\Models\Ts;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanTendikController extends Controller
{
    public function index(Request $request)
    {
        $query = KegiatanTendik::with(['Tendik', 'ts']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_tendik', 'like', "%{$search}%")
                  ->orWhere('nip_nik', 'like', "%{$search}%")
                  ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('penyelenggara', 'like', "%{$search}%");
            });
        }

        // Stats
        $allKegiatan = (clone $query)->get();
        $totalkegiatan = $allKegiatan->count();

        // Berdasarkan Jenis
        $jenisCounts = [
            'Internal' => $allKegiatan->where('jenis', 'Internal')->count(),
            'Eksternal' => $allKegiatan->where('jenis', 'Eksternal')->count(),
        ];

        // Berdasarkan TS
        $labelTsCounts = $allKegiatan->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map->count()
            ->sortDesc()
            ->toArray();

        // Berdasarkan TA
        $tsCounts = Ts::orderBy('tahun_sekarang')
            ->get()
            ->mapWithKeys(function ($ts) use ($allKegiatan) {
                $count = $allKegiatan->where('ts_id', $ts->id)->count();
                $name  = $ts->tahun_sekarang . ($ts->semester ? ' - ' . $ts->semester : '');
                return [$name => $count];
            })
            ->filter(fn($v) => $v > 0)
            ->toArray();

        // Berdasarkan Tendik
        $TendikCounts = [];
        foreach ($allKegiatan as $item) {
            $nama = $item->nama_tendik ?: $item->nip_nik;
            $TendikCounts[$nama] = ($TendikCounts[$nama] ?? 0) + 1;
        }
        arsort($TendikCounts);

        if ($request->get('print') == 'all') {
            $kegiatans = $query->get();
        } else {
            $perPage = in_array($request->get('per_page'), [10, 25, 50, 100]) ? intval($request->get('per_page')) : 10;
            $kegiatans = $query->paginate($perPage)->withQueryString();
        }

        $Tendiks = Tendik::orderBy('nip_nik')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $kegiatanSistem = Kegiatan::orderBy('created_at', 'desc')->get();

        return view('kegiatan_tendik.index', compact(
            'kegiatans', 'Tendiks', 'tsList', 'kegiatanSistem',
            'totalkegiatan', 'jenisCounts', 'labelTsCounts', 'tsCounts', 'TendikCounts'
        ));
    }

    public function create()
    {
        $Tendiks = Tendik::orderBy('nip_nik')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $kegiatanSistem = Kegiatan::orderBy('created_at', 'desc')->get();
        return view('kegiatan_tendik.create', compact('Tendiks', 'tsList', 'kegiatanSistem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip_nik' => 'required',
            'nama_tendik' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'ts_id' => 'required|exists:ts,id',
            'penyelenggara' => 'required|string|max:255',
            'jenis' => 'required|string|in:Internal,Eksternal',
            'link_dokumen' => 'nullable|url|max:255',
            'kegiatan_prodi_id' => 'nullable|exists:kegiatans,id',
        ]);

        KegiatanTendik::create($request->all());

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data kegiatan Tendik berhasil ditambahkan.');
        }

        return redirect()->route('kegiatan-tendik.index')
            ->with('success', 'Data kegiatan Tendik berhasil ditambahkan.');
    }

    public function show($id)
    {
        $KegiatanTendik = KegiatanTendik::with('ts')->findOrFail($id);
        return view('kegiatan_tendik.show', compact('KegiatanTendik'));
    }

    public function edit($id)
    {
        $KegiatanTendik = KegiatanTendik::findOrFail($id);
        $Tendiks = Tendik::orderBy('nip_nik')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $kegiatanSistem = Kegiatan::orderBy('created_at', 'desc')->get();
        return view('kegiatan_tendik.edit', compact('KegiatanTendik', 'Tendiks', 'tsList', 'kegiatanSistem'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = KegiatanTendik::findOrFail($id);

        $request->validate([
            'nip_nik' => 'required',
            'nama_tendik' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'ts_id' => 'required|exists:ts,id',
            'penyelenggara' => 'required|string|max:255',
            'jenis' => 'required|string|in:Internal,Eksternal',
            'link_dokumen' => 'nullable|url|max:255',
            'kegiatan_prodi_id' => 'nullable|exists:kegiatans,id',
        ]);

        $kegiatan->update($request->all());

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data kegiatan Tendik berhasil diperbarui.');
        }

        return redirect()->route('kegiatan-tendik.index')
            ->with('success', 'Data kegiatan Tendik berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $kegiatan = KegiatanTendik::findOrFail($id);
        $kegiatan->delete();

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data kegiatan Tendik berhasil dihapus.');
        }

        return redirect()->route('kegiatan-tendik.index')
            ->with('success', 'Data kegiatan Tendik berhasil dihapus.');
    }

    public function getTendik($kode)
    {
        $Tendik = Tendik::where('nip_nik', $kode)->first();
        if ($Tendik) {
            return response()->json(['nama_tendik' => $Tendik->nama_lengkap]);
        }
        return response()->json(['nama_tendik' => ''], 404);
    }
}
