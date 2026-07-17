<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\TS;
use App\Models\PrestasiDosen;
use App\Models\RekognisiDosen;
use Illuminate\Http\Request;

class PrestasiDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = PrestasiDosen::with('ts')->latest();

        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_prestasi', 'like', "%{$search}%")
                  ->orWhere('penyelenggara', 'like', "%{$search}%")
                  ->orWhere('level_prestasi', 'like', "%{$search}%")
                  ->orWhere('prestasi_diraih', 'like', "%{$search}%");
            });
        }

        // Ambil semua data untuk statistik
        $allPrestasi = (clone $query)->get();

        $totalPrestasi = $allPrestasi->count();

        // Berdasarkan Level Prestasi
        $levelCounts = [
            'Internasional' => $allPrestasi->where('level_prestasi', 'internasional')->count(),
            'Nasional'      => $allPrestasi->where('level_prestasi', 'nasional')->count(),
            'Lokal'         => $allPrestasi->where('level_prestasi', 'lokal')->count(),
        ];

        // Statistik Tri Dharma
        $tridharmaCounts = [
            'penelitian'             => $allPrestasi->where('kategori_tridharma', 'penelitian')->count(),
            'pengabdian_masyarakat'  => $allPrestasi->where('kategori_tridharma', 'pengabdian_masyarakat')->count(),
            'pendidikan'             => $allPrestasi->where('kategori_tridharma', 'pendidikan')->count(),
            'belum_dikategorikan'    => $allPrestasi->whereNull('kategori_tridharma')->count(),
        ];

        // Berdasarkan Prestasi yang Diraih
        $prestasiDiraihCounts = [
            'Juara 1'        => $allPrestasi->where('prestasi_diraih', 'Juara 1')->count(),
            'Juara 2'        => $allPrestasi->where('prestasi_diraih', 'Juara 2')->count(),
            'Juara 3'        => $allPrestasi->where('prestasi_diraih', 'Juara 3')->count(),
            'Harapan I'      => $allPrestasi->where('prestasi_diraih', 'harapan I')->count(),
            'Harapan II'     => $allPrestasi->where('prestasi_diraih', 'harapan II')->count(),
            'Finalis'        => $allPrestasi->where('prestasi_diraih', 'Finalis')->count(),
            'Hibah Eksternal'=> $allPrestasi->where('prestasi_diraih', 'Hibah Eksternal')->count(),
        ];
        // Hapus yang 0
        $prestasiDiraihCounts = array_filter($prestasiDiraihCounts, fn($v) => $v > 0);

        // Berdasarkan TS (label_ts)
        $labelTsCounts = $allPrestasi->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map->count()
            ->sortDesc()
            ->toArray();

        // Berdasarkan TA (tahun_sekarang)
        $tsCounts = TS::orderBy('tahun_sekarang')
            ->get()
            ->mapWithKeys(function ($ts) use ($allPrestasi) {
                $count = $allPrestasi->where('ts_id', $ts->id)->count();
                $name  = $ts->tahun_sekarang . ($ts->semester ? ' - ' . $ts->semester : '');
                return [$name => $count];
            })
            ->filter(fn($v) => $v > 0)
            ->toArray();

        // Berdasarkan Dosen (ranking terbanyak)
        $dosenCounts = [];
        foreach ($allPrestasi as $item) {
            $kodes = array_filter(array_map('trim', explode(',', $item->kode_dosen)));
            $namas = array_filter(array_map('trim', explode(',', $item->nama_dosen)));
            foreach ($kodes as $idx => $kode) {
                if (empty($kode)) continue;
                $nama = array_values($namas)[$idx] ?? '';
                $key = $nama ?: $kode;
                $dosenCounts[$key] = ($dosenCounts[$key] ?? 0) + 1;
            }
        }
        arsort($dosenCounts);

        // Paginasi
        $perPage = in_array($request->get('per_page'), [10, 25, 50, 100]) ? intval($request->get('per_page')) : 10;
        $prestasi = $query->paginate($perPage)->withQueryString();

        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = TS::orderBy('tahun_sekarang')->get();

        return view('prestasi_dosen.index', compact(
            'prestasi', 'dosens', 'tsList',
            'totalPrestasi', 'levelCounts', 'prestasiDiraihCounts',
            'labelTsCounts', 'tsCounts', 'dosenCounts', 'tridharmaCounts'
        ));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = TS::orderBy('tahun_sekarang')->get();
        return view('prestasi_dosen.create', compact('dosens', 'tsList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen'         => 'required',
            'nama_dosen'         => 'required',
            'nama_prestasi'      => 'required',
            'tahun'              => 'required',
            'ts_id'              => 'required|exists:ts,id',
            'penyelenggara'      => 'required',
            'level_prestasi'     => 'required|in:lokal,nasional,internasional',
            'prestasi_diraih'    => 'nullable|string|in:Juara 1,Juara 2,Juara 3,harapan I,harapan II,Finalis,Hibah Eksternal',
            'link_dokumen'       => 'nullable|url|max:255',
            'kategori_tridharma' => 'nullable|in:penelitian,pengabdian_masyarakat,pendidikan',
        ]);

        $prestasiDosen = PrestasiDosen::create($request->all());

        $this->syncToRekognisi($prestasiDosen, $request);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data prestasi dosen berhasil ditambahkan.');
        }

        return redirect()->route('prestasi-dosen.index')
            ->with('success', 'Data prestasi dosen berhasil ditambahkan.');
    }

    public function show(PrestasiDosen $prestasiDosen)
    {
        $prestasiDosen->load('ts');
        return view('prestasi_dosen.show', compact('prestasiDosen'));
    }

    public function edit(PrestasiDosen $prestasiDosen)
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = TS::orderBy('tahun_sekarang')->get();
        return view('prestasi_dosen.edit', compact('prestasiDosen', 'dosens', 'tsList'));
    }

    public function update(Request $request, PrestasiDosen $prestasiDosen)
    {
        $request->validate([
            'kode_dosen'         => 'required',
            'nama_dosen'         => 'required',
            'nama_prestasi'      => 'required',
            'tahun'              => 'required',
            'ts_id'              => 'required|exists:ts,id',
            'penyelenggara'      => 'required',
            'level_prestasi'     => 'required|in:lokal,nasional,internasional',
            'prestasi_diraih'    => 'nullable|string|in:Juara 1,Juara 2,Juara 3,harapan I,harapan II,Finalis,Hibah Eksternal',
            'link_dokumen'       => 'nullable|url|max:255',
            'kategori_tridharma' => 'nullable|in:penelitian,pengabdian_masyarakat,pendidikan',
        ]);

        $prestasiDosen->update($request->all());

        $this->syncToRekognisi($prestasiDosen, $request);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)
                ->with('success', 'Data prestasi dosen berhasil diperbarui.');
        }

        return redirect()->route('prestasi-dosen.index')
            ->with('success', 'Data prestasi dosen berhasil diperbarui.');
    }

    public function destroy(PrestasiDosen $prestasiDosen)
    {
        $prestasiDosen->rekognisiDosen()->delete();
        $prestasiDosen->delete();

        if (request()->has('redirect_to')) {
            return redirect(request('redirect_to'))
                ->with('success', 'Data prestasi dosen berhasil dihapus.');
        }

        return redirect()->route('prestasi-dosen.index')
            ->with('success', 'Data prestasi dosen berhasil dihapus.');
    }

    private function syncToRekognisi($prestasiDosen, Request $request)
    {
        // Delete any existing rekognisi records linked to this prestasi
        $prestasiDosen->rekognisiDosen()->delete();

        $kodeDosenStr = $request->input('kode_dosen', '');
        $namaDosenStr = $request->input('nama_dosen', '');

        $kodeDosens = array_filter(array_map('trim', explode(',', $kodeDosenStr)));
        $namaDosens = array_filter(array_map('trim', explode(',', $namaDosenStr)));

        if (empty($kodeDosens)) {
            return;
        }

        $tsId = $request->input('ts_id');
        $ts = TS::find($tsId);
        $tahun = $request->input('tahun');
        if (empty($tahun) && $ts) {
            if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                $tahun = $matches[0];
            } else {
                $tahun = substr($ts->tahun_sekarang, 0, 10);
            }
        }
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        $namaPrestasi = $request->input('nama_prestasi');
        $diraih = $request->input('prestasi_diraih');
        $namaRekognisi = "Prestasi: {$namaPrestasi}" . ($diraih ? " ({$diraih})" : "");
        if (strlen($namaRekognisi) > 200) {
            $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
        }

        $level = $request->input('level_prestasi') ?: 'lokal';
        $linkDokumen = $request->input('link_dokumen');

        $kategoriTridharma = $request->input('kategori_tridharma');

        foreach ($kodeDosens as $index => $kode) {
            if (empty($kode)) continue;

            $nama = '';
            if (isset($namaDosens[$index])) {
                $nama = $namaDosens[$index];
            } else {
                $dosen = Dosen::where('kode_dosen', $kode)->first();
                $nama = $dosen ? $dosen->nama_dosen : '';
            }

            RekognisiDosen::create([
                'kode_dosen'         => $kode,
                'nama_dosen'         => $nama,
                'nama_rekognisi'     => $namaRekognisi,
                'tahun'              => $tahun,
                'ts_id'              => $tsId,
                'level'              => $level,
                'link_dokumen'       => $linkDokumen,
                'is_keanggotaan'     => false,
                'kategori_tridharma' => $kategoriTridharma,
                'prestasi_dosen_id'  => $prestasiDosen->id,
            ]);
        }
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
