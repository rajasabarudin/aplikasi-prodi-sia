<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Ts;
use App\Models\RekognisiDosen;
use Illuminate\Http\Request;

class RekognisiDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = RekognisiDosen::with('ts')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_rekognisi', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('kategori_tridharma', 'like', "%{$search}%");
            });
        }

        $allRekognisi = (clone $query)->get();
        
        $totalRekognisi = $allRekognisi->count();
        $levelCounts = $allRekognisi->groupBy('level')->map->count()->toArray();

        // Statistik Tri Dharma
        $tridharmaCounts = [
            'penelitian'             => $allRekognisi->where('kategori_tridharma', 'penelitian')->count(),
            'pengabdian_masyarakat'  => $allRekognisi->where('kategori_tridharma', 'pengabdian_masyarakat')->count(),
            'pendidikan'             => $allRekognisi->where('kategori_tridharma', 'pendidikan')->count(),
            'belum_dikategorikan'    => $allRekognisi->whereNull('kategori_tridharma')->count(),
        ];
        $tsCounts = Ts::orderBy('tahun_sekarang')
            ->get()
            ->mapWithKeys(function ($ts) use ($allRekognisi) {
                $count = $allRekognisi->where('ts_id', $ts->id)->count();
                $name = $ts->tahun_sekarang . ' - ' . $ts->semester;
                return [$name => $count];
            })
            ->toArray();
            
        $labelTsCounts = $allRekognisi->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map->count()
            ->sortDesc()
            ->toArray();
        $dosenCounts = $allRekognisi->groupBy(function($item) {
            return $item->kode_dosen . ' - ' . $item->nama_dosen;
        })->map->count()->sortDesc()->toArray();

        $perPage = in_array($request->get('per_page'), [10, 50, 100, 200]) ? intval($request->get('per_page')) : 10;

        if ($request->get('print') === 'all') {
            $rekognisi = $query->get();
        } else {
            $rekognisi = $query->paginate($perPage)->withQueryString();
        }

        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();

        return view('rekognisi_dosen.index', compact('rekognisi', 'totalRekognisi', 'levelCounts', 'tridharmaCounts', 'tsCounts', 'labelTsCounts', 'dosenCounts', 'dosens', 'tsList'));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        return view('rekognisi_dosen.create', compact('dosens', 'tsList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen'         => 'required',
            'nama_dosen'         => 'required',
            'nama_rekognisi'     => 'required',
            'tahun'              => 'required',
            'ts_id'              => 'required|exists:ts,id',
            'level'              => 'required|in:lokal,nasional,internasional',
            'link_dokumen'       => 'nullable|url',
            'kategori_tridharma' => 'nullable|in:penelitian,pengabdian_masyarakat,pendidikan',
        ]);

        RekognisiDosen::create($request->all());

        return redirect()->route('rekognisi-dosen.index')
            ->with('success', 'Data rekognisi dosen berhasil ditambahkan.');
    }

    public function show(RekognisiDosen $rekognisiDosen)
    {
        $rekognisiDosen->load('ts');
        return view('rekognisi_dosen.show', compact('rekognisiDosen'));
    }

    public function edit(RekognisiDosen $rekognisiDosen)
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        return view('rekognisi_dosen.edit', compact('rekognisiDosen', 'dosens', 'tsList'));
    }

    public function update(Request $request, RekognisiDosen $rekognisiDosen)
    {
        $request->validate([
            'kode_dosen'         => 'required',
            'nama_dosen'         => 'required',
            'nama_rekognisi'     => 'required',
            'tahun'              => 'required',
            'ts_id'              => 'required|exists:ts,id',
            'level'              => 'required|in:lokal,nasional,internasional',
            'link_dokumen'       => 'nullable|url',
            'kategori_tridharma' => 'nullable|in:penelitian,pengabdian_masyarakat,pendidikan',
        ]);

        $rekognisiDosen->update($request->all());

        return redirect()->route('rekognisi-dosen.index')
            ->with('success', 'Data rekognisi dosen berhasil diperbarui.');
    }

    public function destroy(RekognisiDosen $rekognisiDosen)
    {
        $rekognisiDosen->delete();

        return redirect()->route('rekognisi-dosen.index')
            ->with('success', 'Data rekognisi dosen berhasil dihapus.');
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
