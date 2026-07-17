<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Hitung statistik berdasarkan data (termasuk filter pencarian jika ada)
        $pendidikanCounts = (clone $query)->select('pendidikan', \DB::raw('count(*) as total'))->groupBy('pendidikan')->pluck('total', 'pendidikan')->toArray();
        $jfaCounts = (clone $query)->select('jfa', \DB::raw('count(*) as total'))->groupBy('jfa')->pluck('total', 'jfa')->toArray();
        $serdosCounts = (clone $query)->select('keterangan_serdos', \DB::raw('count(*) as total'))->groupBy('keterangan_serdos')->pluck('total', 'keterangan_serdos')->toArray();
        $pddiktiCounts = (clone $query)->select('kondisi_pddikti', \DB::raw('count(*) as total'))->groupBy('kondisi_pddikti')->pluck('total', 'kondisi_pddikti')->toArray();

        $totalDosen = (clone $query)->count();

        // Hitung kolaborasi HKI Dosen bersama Mahasiswa
        $hkis = \App\Models\Hki::with('mahasiswa')->get();
        $totalHkiCount = $hkis->unique('no_permohonan')->count();

        $collabHki = [];
        $lecturerHkiSeen = [];
        foreach ($hkis as $h) {
            $kodes = array_map('trim', explode(',', $h->kode_dosen));
            $namas = array_map('trim', explode(',', $h->nama_dosen));
            $noPermohonan = trim($h->no_permohonan);
            
            foreach ($kodes as $idx => $kode) {
                if (empty($kode)) continue;
                $nama = $namas[$idx] ?? $kode;
                
                // Jangan hitung nomor permohonan yang sama lebih dari sekali untuk dosen yang sama
                $seenKey = $kode . '_' . $noPermohonan;
                if (in_array($seenKey, $lecturerHkiSeen)) {
                    continue;
                }
                $lecturerHkiSeen[] = $seenKey;
                
                $dosenModel = Dosen::where('kode_dosen', $kode)->first();
                $dosenId = $dosenModel ? $dosenModel->id : null;
                
                if (!isset($collabHki[$kode])) {
                    $collabHki[$kode] = [
                        'id' => $dosenId,
                        'kode' => $kode,
                        'nama' => $nama,
                        'count' => 0,
                    ];
                }
                $collabHki[$kode]['count']++;
            }
        }

        // Urutkan berdasarkan HKI terbanyak
        uasort($collabHki, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $dosenPunyaHkiCount = count($collabHki);

        // Hitung Prestasi Dosen
        $prestasis = \App\Models\PrestasiDosen::get();
        $totalPrestasiCount = $prestasis->count();

        $dosenPrestasiList = [];
        foreach ($prestasis as $p) {
            $kode = trim($p->kode_dosen);
            if (empty($kode)) continue;
            $nama = trim($p->nama_dosen);

            $dosenModel = Dosen::where('kode_dosen', $kode)->first();
            $dosenId = $dosenModel ? $dosenModel->id : null;

            if (!isset($dosenPrestasiList[$kode])) {
                $dosenPrestasiList[$kode] = [
                    'id' => $dosenId,
                    'kode' => $kode,
                    'nama' => $nama,
                    'count' => 0,
                ];
            }
            $dosenPrestasiList[$kode]['count']++;
        }

        // Urutkan berdasarkan prestasi terbanyak
        uasort($dosenPrestasiList, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $dosenPunyaPrestasiCount = count($dosenPrestasiList);

        if ($request->get('print') === 'all') {
            $dosens = $query->get();
        } else {
            $dosens = $query->paginate(10);
        }

        return view('dosen.index', compact(
            'dosens', 
            'pendidikanCounts', 
            'jfaCounts', 
            'serdosCounts', 
            'pddiktiCounts', 
            'totalDosen', 
            'totalHkiCount', 
            'collabHki', 
            'dosenPunyaHkiCount',
            'totalPrestasiCount',
            'dosenPrestasiList',
            'dosenPunyaPrestasiCount'
        ));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|unique:dosens,kode_dosen',
            'nama_dosen' => 'required',
            'nidn' => 'nullable|unique:dosens,nidn',
            'nuptk' => 'nullable|unique:dosens,nuptk',
            'nip' => 'nullable|unique:dosens,nip',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_dosen', 'public');
        }

        Dosen::create($data);

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen)
    {
        $dosen->load(['sertifikasi', 'kegiatan.ts', 'prestasi.ts', 'rekognisi']);

        $hkis = \App\Models\Hki::with('mahasiswa')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->get();
            
        $hkiBersamaMhs = $hkis->filter(function($h) {
            return !empty($h->nim);
        });
        
        $hkiMandiri = $hkis->filter(function($h) {
            return empty($h->nim);
        });

        $statCounts = [
            'hki' => $hkis->count(),
            'sertifikasi' => $dosen->sertifikasi->count(),
            'kegiatan' => $dosen->kegiatan->count(),
            'prestasi' => $dosen->prestasi->count(),
            'penelitian' => \App\Models\PenelitianDosen::where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")->count(),
            'pkm' => \App\Models\PKMDosen::where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")->count(),
            'hibah' => \App\Models\HibahPenelitian::where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")->count(),
            'rekognisi' => \App\Models\RekognisiDosen::where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")->count(),
        ];

        $penelitianList = \App\Models\PenelitianDosen::with('ts')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->orderBy('id', 'desc')
            ->get();

        $pkmList = \App\Models\PKMDosen::with('ts')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->orderBy('id', 'desc')
            ->get();

        $hibahList = \App\Models\HibahPenelitian::with('ts')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->orderBy('id', 'desc')
            ->get();
            
        $rekognisiList = \App\Models\RekognisiDosen::with('ts')
            ->where('kode_dosen', $dosen->kode_dosen)
            ->orderBy('tahun', 'desc')
            ->get();

        $mahasiswaList = \App\Models\Mahasiswa::orderBy('nama', 'asc')->get();
        $tsList = \App\Models\TS::orderBy('tahun_sekarang', 'desc')->get();

        return view('dosen.show', compact(
            'dosen', 'hkis', 'hkiBersamaMhs', 'hkiMandiri', 
            'penelitianList', 'pkmList', 'hibahList', 
            'rekognisiList', 'statCounts', 'mahasiswaList', 'tsList'
        ));
    }

    public function cetakProfil(Dosen $dosen)
    {
        $dosen->load(['sertifikasi', 'kegiatan.ts', 'prestasi.ts', 'rekognisi']);
        
        $hkis = \App\Models\Hki::with('mahasiswa')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->get();
            
        $penelitianList = \App\Models\PenelitianDosen::with('ts')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->orderBy('id', 'desc')
            ->get();

        $pkmList = \App\Models\PKMDosen::with('ts')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->orderBy('id', 'desc')
            ->get();

        $hibahList = \App\Models\HibahPenelitian::with('ts')
            ->where('kode_dosen', 'like', "%{$dosen->kode_dosen}%")
            ->orderBy('id', 'desc')
            ->get();
            
        $rekognisiList = \App\Models\RekognisiDosen::with('ts')
            ->where('kode_dosen', $dosen->kode_dosen)
            ->orderBy('tahun', 'desc')
            ->get();

        $title = "Profil & Portofolio Dosen - " . $dosen->nama_dosen;

        return view('dosen.cetak_profil', compact(
            'dosen', 'hkis', 'penelitianList', 'pkmList', 'hibahList', 'rekognisiList', 'title'
        ));
    }

    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'kode_dosen' => 'required|unique:dosens,kode_dosen,' . $dosen->id,
            'nama_dosen' => 'required',
            'nidn' => 'nullable|unique:dosens,nidn,' . $dosen->id,
            'nuptk' => 'nullable|unique:dosens,nuptk,' . $dosen->id,
            'nip' => 'nullable|unique:dosens,nip,' . $dosen->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_dosen', 'public');
        }

        $dosen->update($data);

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function updatePhoto(Request $request, Dosen $dosen)
    {
        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'action' => 'required|in:upload,delete',
        ]);

        if ($request->input('action') === 'delete') {
            if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $dosen->update(['foto' => null]);
            return redirect()->back()->with('success', 'Foto dosen berhasil dihapus.');
        }

        if ($request->hasFile('foto')) {
            if ($dosen->foto && Storage::disk('public')->exists($dosen->foto)) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $path = $request->file('foto')->store('foto_dosen', 'public');
            $dosen->update(['foto' => $path]);
            return redirect()->back()->with('success', 'Foto dosen berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Tidak ada file foto yang diunggah.');
    }

    public function card(Dosen $dosen)
    {
        $qrUrl = route('dosen.show', $dosen->id);
        return view('dosen.card', compact('dosen', 'qrUrl'));
    }

    public function updateKeanggotaan(Request $request, Dosen $dosen)
    {
        $selectedIds = $request->input('rekognisi_ids', []);

        \App\Models\RekognisiDosen::where('kode_dosen', $dosen->kode_dosen)->update(['is_keanggotaan' => false]);

        if (!empty($selectedIds)) {
            \App\Models\RekognisiDosen::where('kode_dosen', $dosen->kode_dosen)
                ->whereIn('id', $selectedIds)
                ->update(['is_keanggotaan' => true]);
        }

        return redirect()->back()->with('success', 'Keanggotaan organisasi berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil dihapus.');
    }
}
