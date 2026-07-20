<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Ts;
use App\Models\HibahPenelitian;
use App\Models\RekognisiDosen;
use Illuminate\Http\Request;

class HibahPenelitianController extends Controller
{
    public function index(Request $request)
    {
        $query = HibahPenelitian::with('ts')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nim_mhs', 'like', "%{$search}%")
                  ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('pemberi_hibah', 'like', "%{$search}%")
                  ->orWhere('skema_hibah', 'like', "%{$search}%");
            });
        }

        $allHibah = (clone $query)->get();

        $totalHibah = $allHibah->count();
        $totalBiaya = $allHibah->sum('biaya');
        
        $jenisCounts = [
            'internal' => $allHibah->where('jenis_hibah', 'internal')->count(),
            'eksternal' => $allHibah->where('jenis_hibah', 'eksternal')->count(),
        ];

        $tsStats = Ts::orderBy('tahun_sekarang')
            ->get()
            ->mapWithKeys(function ($ts) use ($allHibah) {
                $matching = $allHibah->where('ts_id', $ts->id);
                $name = $ts->tahun_sekarang . ' - ' . $ts->semester;
                return [$name => [
                    'count' => $matching->count(),
                    'total_biaya' => $matching->sum('biaya')
                ]];
            })
            ->toArray();
            
        $labelTsStats = $allHibah->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_biaya' => $group->sum('biaya')
                ];
            })
            ->toArray();

        $dosenStats = [];
        foreach ($allHibah as $item) {
            $kodes = explode(', ', $item->kode_dosen);
            $namas = explode(', ', $item->nama_dosen);
            foreach ($kodes as $idx => $kode) {
                if (empty($kode)) continue;
                $nama = $namas[$idx] ?? '';
                $key = $kode . ' - ' . $nama;
                if (!isset($dosenStats[$key])) {
                    $dosenStats[$key] = [
                        'count' => 0,
                        'total_biaya' => 0
                    ];
                }
                $dosenStats[$key]['count']++;
                $dosenStats[$key]['total_biaya'] += $item->biaya;
            }
        }
        
        // Urutkan berdasarkan jumlah hibah terbanyak
        uasort($dosenStats, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $mhsStats = [];
        foreach ($allHibah as $item) {
            if (empty($item->nim_mhs)) continue;
            $nims = explode(', ', $item->nim_mhs);
            $namas = explode(', ', $item->nama_mahasiswa);
            foreach ($nims as $idx => $nim) {
                if (empty($nim)) continue;
                $nama = $namas[$idx] ?? '';
                $key = $nim . ' - ' . $nama;
                if (!isset($mhsStats[$key])) {
                    $mhsStats[$key] = [
                        'count' => 0,
                        'total_biaya' => 0
                    ];
                }
                $mhsStats[$key]['count']++;
                $mhsStats[$key]['total_biaya'] += $item->biaya;
            }
        }

        // Urutkan berdasarkan jumlah hibah terbanyak
        uasort($mhsStats, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $perPage = in_array($request->get('per_page'), [10, 50, 100, 200]) ? intval($request->get('per_page')) : 10;

        if ($request->get('print') === 'all') {
            $hibah = $query->get();
        } else {
            $hibah = $query->paginate($perPage)->withQueryString();
        }

        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();

        return view('hibah_penelitian.index', compact(
            'hibah', 'totalHibah', 'totalBiaya', 'jenisCounts', 'tsStats', 'labelTsStats', 'dosenStats', 'mhsStats', 'dosens', 'tsList', 'mahasiswas'
        ));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();
        return view('hibah_penelitian.create', compact('dosens', 'tsList', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_hibah' => 'required|in:internal,eksternal',
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string',
            'skema_hibah' => 'required',
            'tema_hibah' => 'required',
            'topik_hibah' => 'required',
            'judul' => 'required',
            'biaya' => 'required|numeric|min:0',
            'link_proposal' => 'nullable|url',
            'link_st' => 'nullable|url',
            'link_spk' => 'nullable|url',
            'link_luaran' => 'nullable|url',
            'link_laporan' => 'nullable|url',
            'link_persentasi' => 'nullable|url',
            'ts_id' => 'required|exists:ts,id',
            'pemberi_hibah' => 'required',
        ]);

        $data = $request->all();
        
        // Gabungkan data dosen
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));
        
        // Gabungkan data mahasiswa
        $nimArray = array_filter($request->input('nim_mhs', []));
        $namaArray = array_filter($request->input('nama_mahasiswa', []));
        
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $namaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        $hibahPenelitian = HibahPenelitian::create($data);

        $this->syncToRekognisi($hibahPenelitian, $request);
        $this->syncToPrestasi($hibahPenelitian, $request);

        return redirect()->route('hibah-penelitian.index')
            ->with('success', 'Data hibah penelitian berhasil ditambahkan.');
    }

    public function show(HibahPenelitian $hibahPenelitian)
    {
        $hibahPenelitian->load('ts');
        return view('hibah_penelitian.show', compact('hibahPenelitian'));
    }

    public function edit(HibahPenelitian $hibahPenelitian)
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();
        return view('hibah_penelitian.edit', compact('hibahPenelitian', 'dosens', 'tsList', 'mahasiswas'));
    }

    public function update(Request $request, HibahPenelitian $hibahPenelitian)
    {
        $request->validate([
            'jenis_hibah' => 'required|in:internal,eksternal',
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string',
            'skema_hibah' => 'required',
            'tema_hibah' => 'required',
            'topik_hibah' => 'required',
            'judul' => 'required',
            'biaya' => 'required|numeric|min:0',
            'link_proposal' => 'nullable|url',
            'link_st' => 'nullable|url',
            'link_spk' => 'nullable|url',
            'link_luaran' => 'nullable|url',
            'link_laporan' => 'nullable|url',
            'link_persentasi' => 'nullable|url',
            'ts_id' => 'required|exists:ts,id',
            'pemberi_hibah' => 'required',
        ]);

        $data = $request->all();
        
        // Gabungkan data dosen
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));
        
        // Gabungkan data mahasiswa
        $nimArray = array_filter($request->input('nim_mhs', []));
        $namaArray = array_filter($request->input('nama_mahasiswa', []));
        
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $namaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        $hibahPenelitian->update($data);

        $this->syncToRekognisi($hibahPenelitian, $request);
        $this->syncToPrestasi($hibahPenelitian, $request);

        return redirect()->route('hibah-penelitian.index')
            ->with('success', 'Data hibah penelitian berhasil diperbarui.');
    }

    public function destroy(HibahPenelitian $hibahPenelitian)
    {
        $hibahPenelitian->rekognisiDosen()->delete();
        $hibahPenelitian->prestasiDosen()->each(function($p) {
            $p->rekognisiDosen()->delete();
            $p->delete();
        });
        $hibahPenelitian->delete();

        return redirect()->route('hibah-penelitian.index')
            ->with('success', 'Data hibah penelitian berhasil dihapus.');
    }

    public function updateDocument(Request $request, HibahPenelitian $hibahPenelitian)
    {
        $request->validate([
            'field_name' => 'required|in:link_proposal,link_st,link_spk,link_luaran,link_laporan,link_persentasi',
            'link_value' => 'nullable|url',
        ]);

        $fieldName = $request->input('field_name');
        $linkValue = $request->input('link_value');

        $hibahPenelitian->update([
            $fieldName => $linkValue
        ]);

        $primaryDocFields = ['link_st', 'link_spk', 'link_laporan', 'link_proposal'];
        if (in_array($fieldName, $primaryDocFields)) {
            $bestLink = $hibahPenelitian->link_st ?: ($hibahPenelitian->link_spk ?: ($hibahPenelitian->link_laporan ?: $hibahPenelitian->link_proposal));
            
            $hibahPenelitian->rekognisiDosen()->update([
                'link_dokumen' => $bestLink
            ]);

            $hibahPenelitian->prestasiDosen()->update([
                'link_dokumen' => $bestLink
            ]);

            $prestasiIds = $hibahPenelitian->prestasiDosen()->pluck('id');
            RekognisiDosen::whereIn('prestasi_dosen_id', $prestasiIds)->update([
                'link_dokumen' => $bestLink
            ]);
        }

        $message = $linkValue ? 'Dokumen berhasil ditambahkan.' : 'Dokumen berhasil dihapus.';

        return redirect()->back()
            ->with('success', $message);
    }

    private function syncToRekognisi($hibahPenelitian, Request $request)
    {
        // Delete any existing rekognisi records linked to this hibah
        $hibahPenelitian->rekognisiDosen()->delete();

        // Get arrays of kode_dosen and nama_dosen
        $kodeDosens = array_filter($request->input('kode_dosen', []));
        $namaDosens = array_filter($request->input('nama_dosen', []));

        $tsId = $request->input('ts_id');
        $ts = Ts::find($tsId);
        $tahun = date('Y');
        if ($ts) {
            if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                $tahun = $matches[0];
            } else {
                $tahun = substr($ts->tahun_sekarang, 0, 10);
            }
        }

        $jenisHibah = $request->input('jenis_hibah');
        $level = 'lokal';
        if ($jenisHibah === 'eksternal') {
            $level = 'nasional';
            $skema = strtolower($request->input('skema_hibah', ''));
            $pemberi = strtolower($request->input('pemberi_hibah', ''));
            if (str_contains($skema, 'internasional') || str_contains($pemberi, 'internasional') || str_contains($skema, 'international') || str_contains($pemberi, 'international')) {
                $level = 'internasional';
            }
        }

        $judul = $request->input('judul');
        $skemaHibah = $request->input('skema_hibah');
        $namaRekognisi = "Hibah Penelitian: {$judul} ({$skemaHibah})";
        if (strlen($namaRekognisi) > 200) {
            $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
        }

        $linkDokumen = $hibahPenelitian->link_st ?: ($hibahPenelitian->link_spk ?: ($hibahPenelitian->link_laporan ?: $hibahPenelitian->link_proposal));

        foreach ($kodeDosens as $index => $kode) {
            if (empty($kode)) continue;
            $nama = $namaDosens[$index] ?? '';

            RekognisiDosen::create([
                'kode_dosen' => $kode,
                'nama_dosen' => $nama,
                'nama_rekognisi' => $namaRekognisi,
                'tahun' => $tahun,
                'ts_id' => $tsId,
                'level' => $level,
                'link_dokumen' => $linkDokumen,
                'is_keanggotaan' => false,
                'hibah_penelitian_id' => $hibahPenelitian->id,
            ]);
        }
    }

    private function syncToPrestasi($hibahPenelitian, Request $request)
    {
        // Delete any existing prestasi records linked to this hibah
        // This will also delete their associated RekognisiDosen records
        $hibahPenelitian->prestasiDosen()->each(function($p) {
            $p->rekognisiDosen()->delete();
            $p->delete();
        });

        // Also delete any existing student achievements linked to this hibah
        \App\Models\PrestasiMahasiswa::where('hibah_penelitian_id', $hibahPenelitian->id)->delete();

        $jenisHibah = $request->input('jenis_hibah');

        if ($jenisHibah === 'eksternal') {
            // Get arrays of kode_dosen and nama_dosen
            $kodeDosens = array_filter($request->input('kode_dosen', []));
            $namaDosens = array_filter($request->input('nama_dosen', []));

            $tsId = $request->input('ts_id');
            $ts = Ts::find($tsId);
            $tahun = date('Y');
            if ($ts) {
                if (preg_match('/\d{4}/', $ts->tahun_sekarang, $matches)) {
                    $tahun = $matches[0];
                } else {
                    $tahun = substr($ts->tahun_sekarang, 0, 10);
                }
            }

            $level = 'nasional';
            $skema = strtolower($request->input('skema_hibah', ''));
            $pemberi = strtolower($request->input('pemberi_hibah', ''));
            if (str_contains($skema, 'internasional') || str_contains($pemberi, 'internasional') || str_contains($skema, 'international') || str_contains($pemberi, 'international')) {
                $level = 'internasional';
            }

            $judul = $request->input('judul');
            $skemaHibah = $request->input('skema_hibah');
            $namaPrestasi = "Hibah Penelitian Eksternal: {$judul} ({$skemaHibah})";
            if (strlen($namaPrestasi) > 200) {
                $namaPrestasi = substr($namaPrestasi, 0, 197) . '...';
            }

            $penyelenggara = $request->input('pemberi_hibah') ?: 'Pemberi Hibah';
            $linkDokumen = $hibahPenelitian->link_st ?: ($hibahPenelitian->link_spk ?: ($hibahPenelitian->link_laporan ?: $hibahPenelitian->link_proposal));

            // Sync PrestasiDosen
            foreach ($kodeDosens as $index => $kode) {
                if (empty($kode)) continue;
                $nama = $namaDosens[$index] ?? '';

                // Create the PrestasiDosen record
                $prestasi = \App\Models\PrestasiDosen::create([
                    'kode_dosen' => $kode,
                    'nama_dosen' => $nama,
                    'nama_prestasi' => $namaPrestasi,
                    'tahun' => $tahun,
                    'ts_id' => $tsId,
                    'penyelenggara' => $penyelenggara,
                    'level_prestasi' => $level,
                    'prestasi_diraih' => 'Hibah Eksternal',
                    'link_dokumen' => $linkDokumen,
                    'hibah_penelitian_id' => $hibahPenelitian->id,
                ]);
            }


            // Sync PrestasiMahasiswa
            $nimMhsList = array_filter($request->input('nim_mhs', []));
            foreach ($nimMhsList as $nim) {
                if (empty($nim)) continue;
                
                \App\Models\PrestasiMahasiswa::create([
                    'nim' => $nim,
                    'nama_prestasi' => $namaPrestasi,
                    'tahun' => $tahun,
                    'ts_id' => $tsId,
                    'penyelenggara' => $penyelenggara,
                    'bidang_prestasi' => 'Akademik Non Lomba',
                    'prestasi_diraih' => 'Partisipan',
                    'level_prestasi' => $level,
                    'link_dokumen' => $linkDokumen,
                    'hibah_penelitian_id' => $hibahPenelitian->id,
                ]);
            }
        }
    }

    public function getDosen($kode)
    {
        $dosen = Dosen::where('kode_dosen', $kode)->first();
        return response()->json($dosen);
    }

    public function getMahasiswa($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        return response()->json($mahasiswa);
    }
}
