<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDosen;
use App\Models\Dosen;
use App\Models\Ts;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = KegiatanDosen::with(['dosen', 'ts']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('kode_dosen', 'like', "%{$search}%")
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

        // Berdasarkan Dosen
        $dosenCounts = [];
        foreach ($allKegiatan as $item) {
            $nama = $item->nama_dosen ?: $item->kode_dosen;
            $dosenCounts[$nama] = ($dosenCounts[$nama] ?? 0) + 1;
        }
        arsort($dosenCounts);

        if ($request->get('print') == 'all') {
            $kegiatans = $query->get();
        } else {
            $perPage = in_array($request->get('per_page'), [10, 25, 50, 100]) ? intval($request->get('per_page')) : 10;
            $kegiatans = $query->paginate($perPage)->withQueryString();
        }

        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $kegiatanSistem = Kegiatan::orderBy('created_at', 'desc')->get();

        return view('kegiatan_dosen.index', compact(
            'kegiatans', 'dosens', 'tsList', 'kegiatanSistem',
            'totalkegiatan', 'jenisCounts', 'labelTsCounts', 'tsCounts', 'dosenCounts'
        ));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $kegiatanSistem = Kegiatan::orderBy('created_at', 'desc')->get();
        return view('kegiatan_dosen.create', compact('dosens', 'tsList', 'kegiatanSistem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required',
            'nama_dosen' => 'required|string|max:255',
            'nama_kegiatan' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2100',
            'ts_id' => 'required|exists:ts,id',
            'penyelenggara' => 'required|string|max:255',
            'jenis' => 'required|string|in:Internal,Eksternal',
            'link_dokumen' => 'nullable|url|max:255',
            'kegiatan_prodi_id' => 'nullable|exists:kegiatans,id',
        ]);

        KegiatanDosen::create($request->all());

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data kegiatan dosen berhasil ditambahkan.');
        }

        return redirect()->route('kegiatan-dosen.index')
            ->with('success', 'Data kegiatan dosen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kegiatanDosen = KegiatanDosen::with('ts')->findOrFail($id);
        return view('kegiatan_dosen.show', compact('kegiatanDosen'));
    }

    public function edit($id)
    {
        $kegiatanDosen = KegiatanDosen::findOrFail($id);
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $kegiatanSistem = Kegiatan::orderBy('created_at', 'desc')->get();
        return view('kegiatan_dosen.edit', compact('kegiatanDosen', 'dosens', 'tsList', 'kegiatanSistem'));
    }

    public function update(Request $request, $id)
    {
        $kegiatan = KegiatanDosen::findOrFail($id);

        $request->validate([
            'kode_dosen' => 'required',
            'nama_dosen' => 'required|string|max:255',
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
                ->with('success', 'Data kegiatan dosen berhasil diperbarui.');
        }

        return redirect()->route('kegiatan-dosen.index')
            ->with('success', 'Data kegiatan dosen berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $kegiatan = KegiatanDosen::findOrFail($id);
        $kegiatan->delete();

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data kegiatan dosen berhasil dihapus.');
        }

        return redirect()->route('kegiatan-dosen.index')
            ->with('success', 'Data kegiatan dosen berhasil dihapus.');
    }

    public function getDosen($kode)
    {
        $dosen = Dosen::where('kode_dosen', $kode)->first();
        if ($dosen) {
            return response()->json(['nama_dosen' => $dosen->nama_dosen]);
        }
        return response()->json(['nama_dosen' => ''], 404);
    }
}
