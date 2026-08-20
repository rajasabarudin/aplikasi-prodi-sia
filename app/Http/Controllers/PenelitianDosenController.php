<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Ts;
use App\Models\PenelitianDosen;
use Illuminate\Http\Request;

class PenelitianDosenController extends Controller
{
    public function index(Request $request)
    {
        $query = PenelitianDosen::with('ts')->latest();

        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nim_mhs', 'like', "%{$search}%")
                  ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('jenis_jurnal', 'like', "%{$search}%")
                  ->orWhere('jenis_penelitian', 'like', "%{$search}%")
                  ->orWhere('nama_jurnal', 'like', "%{$search}%")
                  ->orWhere('anggota_mitra', 'like', "%{$search}%");
            });
        }

        // Clone query untuk statistik agar akurat sesuai filter
        $statsQuery = clone $query;
        $allPenelitian = $statsQuery->get();

        $totalPenelitian = $allPenelitian->count();

        // Berdasarkan Jenis Jurnal
        $jenisJurnalCounts = [
            'Jurnal Nasional' => $allPenelitian->where('jenis_jurnal', 'Jurnal Nasional')->count(),
            'Jurnal Nasional Terakreditasi (SINTA)' => $allPenelitian->where('jenis_jurnal', 'Jurnal Nasional Terakreditasi (SINTA)')->count(),
            'Jurnal Internasional' => $allPenelitian->where('jenis_jurnal', 'Jurnal Internasional')->count(),
            'Jurnal Internasional Bereputasi (Scopus/WoS)' => $allPenelitian->where('jenis_jurnal', 'Jurnal Internasional Bereputasi (Scopus/WoS)')->count(),
        ];

        // Berdasarkan Jenis Penelitian
        $jenisPenelitianCounts = [
            'Penelitian Mandiri' => $allPenelitian->where('jenis_penelitian', 'Penelitian Mandiri')->count(),
            'Publikasi Karya Ilmiah hasil Penelitian' => $allPenelitian->where('jenis_penelitian', 'Publikasi Karya Ilmiah hasil Penelitian')->count(),
        ];

        // Berdasarkan TS
        $tsCounts = Ts::orderBy('tahun_sekarang')
            ->get()
            ->mapWithKeys(function ($ts) use ($allPenelitian) {
                $count = $allPenelitian->where('ts_id', $ts->id)->count();
                $name = $ts->tahun_sekarang . ' - ' . $ts->semester;
                return [$name => $count];
            })
            ->toArray();

        $labelTsCounts = $allPenelitian->filter(function ($item) {
                return $item->ts && $item->ts->label_ts;
            })
            ->groupBy(function ($item) {
                return $item->ts->label_ts;
            })
            ->map->count()
            ->sortDesc()
            ->toArray();

        // Berdasarkan Dosen (Dihitung Terpisah)
        $dosenCounts = [];
        foreach ($allPenelitian as $item) {
            $kodes = explode(', ', $item->kode_dosen);
            $namas = explode(', ', $item->nama_dosen);
            foreach ($kodes as $idx => $kode) {
                if (empty($kode)) continue;
                $nama = $namas[$idx] ?? '';
                $key = $kode . ' - ' . $nama;
                $dosenCounts[$key] = ($dosenCounts[$key] ?? 0) + 1;
            }
        }
        arsort($dosenCounts);

        // Berdasarkan Mahasiswa (Dihitung Terpisah)
        $mhsCounts = [];
        foreach ($allPenelitian as $item) {
            if (empty($item->nim_mhs)) continue;
            $nims = explode(', ', $item->nim_mhs);
            $namas = explode(', ', $item->nama_mahasiswa);
            foreach ($nims as $idx => $nim) {
                if (empty($nim)) continue;
                $nama = $namas[$idx] ?? '';
                $key = $nim . ' - ' . $nama;
                $mhsCounts[$key] = ($mhsCounts[$key] ?? 0) + 1;
            }
        }
        arsort($mhsCounts);

        // Hitung kolaborasi dosen bersama mahasiswa
        $kolaborasiCount = 0;
        $nonKolaborasiCount = 0;
        foreach ($allPenelitian as $item) {
            if (!empty($item->nim_mhs)) {
                $kolaborasiCount++;
            } else {
                $nonKolaborasiCount++;
            }
        }

        // Paginasi & Cetak Semua
        $perPage = in_array($request->get('per_page'), [10, 50, 100, 200]) ? intval($request->get('per_page')) : 10;

        if ($request->get('print') === 'all') {
            $penelitian = $query->get();
        } else {
            $penelitian = $query->paginate($perPage)->withQueryString();
        }

        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();

        return view('penelitian_dosen.index', compact(
            'penelitian', 'totalPenelitian', 'jenisJurnalCounts', 'jenisPenelitianCounts', 'tsCounts', 'labelTsCounts', 'dosenCounts', 'mhsCounts', 'dosens', 'tsList', 'mahasiswas', 'kolaborasiCount', 'nonKolaborasiCount'
        ));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();
        return view('penelitian_dosen.create', compact('dosens', 'tsList', 'mahasiswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string',
            'judul_penelitian' => 'required|string',
            'jenis_jurnal' => 'required',
            'jenis_penelitian' => 'required',
            'nama_jurnal' => 'required',
            'link_jurnal' => 'nullable|string',
            'ts_id' => 'required|exists:ts,id',
            'berkas_sertifikat' => 'nullable|string',
            'berkas_paper' => 'nullable|string',
            'proposal' => 'required_if:jenis_penelitian,Penelitian Mandiri|nullable|string',
            'laporan' => 'required_if:jenis_penelitian,Penelitian Mandiri|nullable|string',
            'lainnya' => 'nullable|string',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string',
            'anggota_mitra' => 'nullable|array',
            'anggota_mitra.*' => 'nullable|string',
        ]);

        $data = $request->all();

        // Gabungkan data dosen
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));

        // Gabungkan data mahasiswa
        $nimArray = array_filter($request->input('nim_mhs', []));
        $mhsNamaArray = array_filter($request->input('nama_mahasiswa', []));
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $mhsNamaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        // Gabungkan data mitra
        $mitraArray = array_filter($request->input('anggota_mitra', []));
        if (!empty($mitraArray)) {
            $data['anggota_mitra'] = implode(', ', $mitraArray);
        } else {
            $data['anggota_mitra'] = null;
        }

        $penelitianDosen = PenelitianDosen::create($data);


        return redirect()->route('penelitian-dosen.index')
            ->with('success', 'Data penelitian dosen berhasil ditambahkan.');
    }

    public function show(PenelitianDosen $penelitianDosen)
    {
        $penelitianDosen->load('ts');
        return view('penelitian_dosen.show', compact('penelitianDosen'));
    }

    public function edit(PenelitianDosen $penelitianDosen)
    {
        $dosens = Dosen::orderBy('kode_dosen')->get();
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        $mahasiswas = Mahasiswa::orderBy('nim')->get();
        return view('penelitian_dosen.edit', compact('penelitianDosen', 'dosens', 'tsList', 'mahasiswas'));
    }

    public function update(Request $request, PenelitianDosen $penelitianDosen)
    {
        $request->validate([
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string',
            'judul_penelitian' => 'required|string',
            'jenis_jurnal' => 'required',
            'jenis_penelitian' => 'required',
            'nama_jurnal' => 'required',
            'link_jurnal' => 'nullable|string',
            'ts_id' => 'required|exists:ts,id',
            'berkas_sertifikat' => 'nullable|string',
            'berkas_paper' => 'nullable|string',
            'proposal' => 'required_if:jenis_penelitian,Penelitian Mandiri|nullable|string',
            'laporan' => 'required_if:jenis_penelitian,Penelitian Mandiri|nullable|string',
            'lainnya' => 'nullable|string',
            'nim_mhs' => 'nullable|array',
            'nim_mhs.*' => 'nullable|string',
            'nama_mahasiswa' => 'nullable|array',
            'nama_mahasiswa.*' => 'nullable|string',
            'anggota_mitra' => 'nullable|array',
            'anggota_mitra.*' => 'nullable|string',
        ]);

        $data = $request->all();

        // Gabungkan data dosen
        $data['kode_dosen'] = implode(', ', array_filter($request->input('kode_dosen', [])));
        $data['nama_dosen'] = implode(', ', array_filter($request->input('nama_dosen', [])));

        // Gabungkan data mahasiswa
        $nimArray = array_filter($request->input('nim_mhs', []));
        $mhsNamaArray = array_filter($request->input('nama_mahasiswa', []));
        if (!empty($nimArray)) {
            $data['nim_mhs'] = implode(', ', $nimArray);
            $data['nama_mahasiswa'] = implode(', ', $mhsNamaArray);
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        // Gabungkan data mitra
        $mitraArray = array_filter($request->input('anggota_mitra', []));
        if (!empty($mitraArray)) {
            $data['anggota_mitra'] = implode(', ', $mitraArray);
        } else {
            $data['anggota_mitra'] = null;
        }

        $penelitianDosen->update($data);


        return redirect()->route('penelitian-dosen.index')
            ->with('success', 'Data penelitian dosen berhasil diperbarui.');
    }

    public function destroy(PenelitianDosen $penelitianDosen)
    {
        $penelitianDosen->delete();

        return redirect()->route('penelitian-dosen.index')
            ->with('success', 'Data penelitian dosen berhasil dihapus.');
    }

    public function updateDocument(Request $request, PenelitianDosen $penelitianDosen)
    {
        $request->validate([
            'field_name' => 'required|in:berkas_sertifikat,berkas_paper,proposal,laporan,lainnya',
            'link_value' => 'nullable|url',
        ]);

        $fieldName = $request->input('field_name');
        $linkValue = $request->input('link_value');

        $penelitianDosen->update([
            $fieldName => $linkValue
        ]);

        $message = $linkValue ? 'Dokumen berhasil ditambahkan.' : 'Dokumen berhasil dihapus.';

        return redirect()->back()->with('success', $message);
    }

    public function getDosen($kode)
    {
        $dosen = Dosen::where('kode_dosen', $kode)->first();
        if ($dosen) {
            return response()->json(['nama_dosen' => $dosen->nama_dosen]);
        }
        return response()->json(['nama_dosen' => ''], 404);
    }

    public function getMahasiswa($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();
        if ($mahasiswa) {
            return response()->json(['nama' => $mahasiswa->nama]);
        }
        return response()->json(['nama' => ''], 404);
    }

    public function publicIndex(Request $request)
    {
        $query = PenelitianDosen::with('ts')->latest();
        $penelitian = $query->paginate(10);
        $tsList = Ts::orderBy('tahun_sekarang')->get();
        return view('penelitian_dosen.public_index', compact('penelitian', 'tsList'));
    }

    public function publicStore(Request $request)
    {
        $request->validate([
            'kode_dosen' => 'required|array|min:1',
            'kode_dosen.*' => 'required|string|exists:dosens,kode_dosen',
            'nama_dosen' => 'required|array|min:1',
            'nama_dosen.*' => 'required|string',
            'judul_penelitian' => 'required|string',
            'jenis_jurnal' => 'required',
            'jenis_penelitian' => 'required',
            'nama_jurnal' => 'required',
            'link_jurnal' => 'nullable|string',
            'ts_id' => 'required|exists:ts,id',
            'nim_mhs' => 'nullable|array',
            'nama_mahasiswa' => 'nullable|array',
            'anggota_mitra' => 'nullable|array',
            'proposal' => 'required_if:jenis_penelitian,Penelitian Mandiri|nullable|string',
            'laporan' => 'required_if:jenis_penelitian,Penelitian Mandiri|nullable|string',
        ]);

        $data = $request->all();
        $data['kode_dosen'] = implode(', ', array_filter($request->kode_dosen));
        $data['nama_dosen'] = implode(', ', array_filter($request->nama_dosen));
        
        if (!empty($request->nim_mhs)) {
            $data['nim_mhs'] = implode(', ', array_filter($request->nim_mhs));
            $data['nama_mahasiswa'] = implode(', ', array_filter($request->nama_mahasiswa ?? []));
        } else {
            $data['nim_mhs'] = null;
            $data['nama_mahasiswa'] = null;
        }

        if (!empty($request->anggota_mitra)) {
            $data['anggota_mitra'] = implode(', ', array_filter($request->anggota_mitra));
        } else {
            $data['anggota_mitra'] = null;
        }

        $penelitian = PenelitianDosen::create($data);

        return redirect()->route('portal.penelitian')->with('success', 'Data Penelitian berhasil dikirim. Hubungi Kaprodi jika terdapat kesalahan input.');
    }

    public function generateProposal(PenelitianDosen $penelitian_dosen)
    {
        return $this->generateDocument($penelitian_dosen, 'Proposal');
    }

    public function generateLaporan(PenelitianDosen $penelitian_dosen)
    {
        return $this->generateDocument($penelitian_dosen, 'Laporan');
    }

    private function generateDocument(PenelitianDosen $penelitian_dosen, $type)
    {
        $templatePath = storage_path("app/templates/Template_{$type}.docx");
        if (!file_exists($templatePath)) {
            return back()->with('error', "Template {$type} tidak ditemukan di sistem.");
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        
        $judul = $penelitian_dosen->judul_penelitian ?? 'Judul Penelitian Belum Diisi';
        $ts = $penelitian_dosen->ts;
        
        $semester = $ts ? $ts->semester : 'Gasal';
        $tahunSekarang = $ts ? $ts->tahun_sekarang : date('Y');
        
        preg_match('/\d{4}/', $tahunSekarang, $matches);
        $tahun = isset($matches[0]) ? intval($matches[0]) : date('Y');
        
        $isGanjil = stripos($semester, 'Gasal') !== false || stripos($semester, 'Ganjil') !== false;
        
        if ($type === 'Proposal') {
            if ($isGanjil) {
                $bulan = 'AGUSTUS';
                $tahunStr = $tahun;
            } else {
                $bulan = 'FEBRUARI';
                $tahunStr = $tahun + 1;
            }
        } else {
            if ($isGanjil) {
                $bulan = 'FEBRUARI';
                $tahunStr = $tahun + 1;
            } else {
                $bulan = 'AGUSTUS';
                $tahunStr = $tahun + 1;
            }
        }

        $templateProcessor->setValue('JUDUL', $judul);
        $templateProcessor->setValue('BULAN_TAHUN', $bulan . ' ' . $tahunStr);
        
        $safeJudul = preg_replace('/[^a-zA-Z0-9]/', '_', substr($judul, 0, 30));
        $fileName = "{$type}_Penelitian_{$penelitian_dosen->nama_dosen}_{$safeJudul}.docx";
        
        $tempPath = storage_path('app/temp_' . time() . '.docx');
        $templateProcessor->saveAs($tempPath);
        
        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }
}
