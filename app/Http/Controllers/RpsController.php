<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use App\Models\RpsPertemuan;
use App\Models\Matakuliah;
use App\Models\Ts;
use App\Models\Dosen;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf;

class RpsController extends Controller
{
    public function index()
    {
        $rps = Rps::with(['matakuliah'])->get();
        return view('rps.index', compact('rps'));
    }

    public function create()
    {
        $matakuliahs = Matakuliah::all();
        $dosens = Dosen::orderBy('nama_dosen')->get();
        $penelitians = \App\Models\PenelitianDosen::orderBy('nama_dosen')->get();
        $pkms = \App\Models\PKMDosen::orderBy('nama_dosen')->get();
        return view('rps.create', compact('matakuliahs', 'dosens', 'penelitians', 'pkms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_matakuliah' => 'required',
            'nomor_dokumen' => 'nullable',
            'tanggal_penyusunan' => 'nullable|date',
            'dosen_internal' => 'nullable|array',
            'dosen_eksternal' => 'nullable|string',
            'koordinator' => 'nullable',
            'kaprodi' => 'nullable',
            'deskripsi_singkat' => 'nullable',
            'bobot_kehadiran' => 'nullable|integer',
            'bobot_tugas' => 'nullable|integer',
            'bobot_project' => 'nullable|integer',
            'bobot_praktek' => 'nullable|integer',
            'bobot_kuis' => 'nullable|integer',
            'bobot_uts' => 'nullable|integer',
            'bobot_uas' => 'nullable|integer',
            'bobot_presentasi' => 'nullable|integer',
            'asesmen_tertulis' => 'nullable|boolean',
            'asesmen_lisan' => 'nullable|boolean',
            'asesmen_kinerja' => 'nullable|boolean',
            'asesmen_portofolio' => 'nullable|boolean',
            'pertemuan' => 'nullable|array'
        ]);

        $data = $request->except(['dosen_internal', 'dosen_eksternal', 'penelitian_ids', 'penelitian_integrasi', 'pkm_ids', 'pkm_integrasi']);
        
        $dosen_internal = $request->input('dosen_internal', []);
        $dosen_eksternal = $request->input('dosen_eksternal', '');
        
        $semua_dosen = array_filter(array_merge($dosen_internal, array_map('trim', explode(';', $dosen_eksternal))));
        $data['dosen_pengembang'] = implode(', ', $semua_dosen);
        $data['bobot_kehadiran'] = $request->bobot_kehadiran ?? 0;
        $data['bobot_tugas'] = $request->bobot_tugas ?? 0;
        $data['bobot_project'] = $request->bobot_project ?? 0;
        $data['bobot_praktek'] = $request->bobot_praktek ?? 0;
        $data['bobot_kuis'] = $request->bobot_kuis ?? 0;
        $data['bobot_uts'] = $request->bobot_uts ?? 0;
        $data['bobot_uas'] = $request->bobot_uas ?? 0;
        $data['bobot_presentasi'] = $request->bobot_presentasi ?? 0;
        $data['asesmen_tertulis'] = $request->has('asesmen_tertulis') ? 1 : 0;
        $data['asesmen_lisan'] = $request->has('asesmen_lisan') ? 1 : 0;
        $data['asesmen_kinerja'] = $request->has('asesmen_kinerja') ? 1 : 0;
        $data['asesmen_portofolio'] = $request->has('asesmen_portofolio') ? 1 : 0;

        $rps = Rps::create($data);

        // Sync Penelitian & PKM Integrations
        if ($request->has('penelitian_ids')) {
            $penelitianData = [];
            foreach ($request->penelitian_ids as $pId) {
                $penelitianData[$pId] = ['bentuk_integrasi' => $request->input("penelitian_integrasi.{$pId}")];
            }
            $rps->penelitians()->sync($penelitianData);
        }
        if ($request->has('pkm_ids')) {
            $pkmData = [];
            foreach ($request->pkm_ids as $pId) {
                $pkmData[$pId] = ['bentuk_integrasi' => $request->input("pkm_integrasi.{$pId}")];
            }
            $rps->pkms()->sync($pkmData);
        }

        // Ekstrak 16 pertemuan otomatis dari PDF via Python
        $tempFile = storage_path('app/temp_rps_' . $rps->id . '.json');
        $command = 'python ' . escapeshellarg(base_path('extract_pertemuan.py')) . ' ' . escapeshellarg($rps->kode_matakuliah) . ' ' . escapeshellarg($tempFile);
        shell_exec($command);
        
        $extractedData = null;
        if (file_exists($tempFile)) {
            $jsonContent = file_get_contents($tempFile);
            $extractedData = json_decode($jsonContent, true);
            unlink($tempFile); // Hapus file temporary setelah dibaca
        }
        
        if (is_array($extractedData) && !isset($extractedData['error'])) {
            foreach ($extractedData as $item) {
                RpsPertemuan::create([
                    'rps_id' => $rps->id,
                    'minggu_ke' => $item['minggu_ke'] ?? '',
                    'sub_cpmk' => $item['sub_cpmk'] ?? '',
                    'bahan_kajian' => $item['bahan_kajian'] ?? '',
                    'metode_pembelajaran' => $item['metode_pembelajaran'] ?? '',
                    'waktu_pembelajaran' => $item['waktu_pembelajaran'] ?? '',
                    'pengalaman_belajar' => $item['pengalaman_belajar'] ?? '',
                    'kriteria_penilaian' => $item['kriteria_penilaian'] ?? '',
                    'indikator_penilaian' => $item['indikator_penilaian'] ?? '',
                    'bobot_penilaian' => $item['bobot_penilaian'] ?? 0,
                ]);
            }
        } elseif ($request->has('pertemuan')) {
            foreach ($request->pertemuan as $item) {
                RpsPertemuan::create([
                    'rps_id' => $rps->id,
                    'minggu_ke' => $item['minggu_ke'] ?? '',
                    'sub_cpmk' => $item['sub_cpmk'] ?? '',
                    'bahan_kajian' => $item['bahan_kajian'] ?? '',
                    'metode_pembelajaran' => $item['metode_pembelajaran'] ?? '',
                    'waktu_pembelajaran' => $item['waktu_pembelajaran'] ?? '',
                    'pengalaman_belajar' => $item['pengalaman_belajar'] ?? '',
                    'kriteria_penilaian' => $item['kriteria_penilaian'] ?? '',
                    'indikator_penilaian' => $item['indikator_penilaian'] ?? '',
                    'bobot_penilaian' => $item['bobot_penilaian'] ?? 0,
                ]);
            }
        } else {
            $errorMsg = $extractedData['error'] ?? 'Gagal membaca tabel PDF';
            return redirect()->route('penyusunan-rps.index')->with('success', 'RPS ditambahkan, namun rincian 16 pertemuan gagal diekstrak otomatis: ' . $errorMsg);
        }

        return redirect()->route('penyusunan-rps.index')->with('success', 'RPS berhasil ditambahkan dan rincian pertemuan otomatis di-generate.');
    }

    public function edit($id)
    {
        $rps = Rps::with(['pertemuans', 'penelitians', 'pkms'])->findOrFail($id);
        $matakuliahs = Matakuliah::all();
        $dosens = Dosen::orderBy('nama_dosen')->get();
        
        $semua_dosen = array_map('trim', explode(',', $rps->dosen_pengembang));
        $internal_names = $dosens->pluck('nama_dosen')->toArray();
        
        $dosen_internal_selected = array_intersect($semua_dosen, $internal_names);
        $dosen_eksternal_selected = array_diff($semua_dosen, $internal_names);
        $dosen_eksternal_str = implode('; ', $dosen_eksternal_selected);

        $penelitians = \App\Models\PenelitianDosen::orderBy('nama_dosen')->get();
        $pkms = \App\Models\PKMDosen::orderBy('nama_dosen')->get();
        
        return view('rps.edit', compact('rps', 'matakuliahs', 'dosens', 'dosen_internal_selected', 'dosen_eksternal_str', 'penelitians', 'pkms'));
    }

    public function update(Request $request, $id)
    {
        $rps = Rps::findOrFail($id);
        
        $validated = $request->validate([
            'kode_matakuliah' => 'required',
            'nomor_dokumen' => 'nullable',
            'tanggal_penyusunan' => 'nullable|date',
            'dosen_internal' => 'nullable|array',
            'dosen_eksternal' => 'nullable|string',
            'koordinator' => 'nullable',
            'kaprodi' => 'nullable',
            'deskripsi_singkat' => 'nullable',
            'bobot_kehadiran' => 'nullable|integer',
            'bobot_tugas' => 'nullable|integer',
            'bobot_project' => 'nullable|integer',
            'bobot_praktek' => 'nullable|integer',
            'bobot_kuis' => 'nullable|integer',
            'bobot_uts' => 'nullable|integer',
            'bobot_uas' => 'nullable|integer',
            'bobot_presentasi' => 'nullable|integer',
            'asesmen_tertulis' => 'nullable|boolean',
            'asesmen_lisan' => 'nullable|boolean',
            'asesmen_kinerja' => 'nullable|boolean',
            'asesmen_portofolio' => 'nullable|boolean',
            'pertemuan' => 'nullable|array'
        ]);

        $data = $request->except(['dosen_internal', 'dosen_eksternal', 'penelitian_ids', 'penelitian_integrasi', 'pkm_ids', 'pkm_integrasi']);
        
        $dosen_internal = $request->input('dosen_internal', []);
        $dosen_eksternal = $request->input('dosen_eksternal', '');
        
        $semua_dosen = array_filter(array_merge($dosen_internal, array_map('trim', explode(';', $dosen_eksternal))));
        $data['dosen_pengembang'] = implode(', ', $semua_dosen);
        $data['bobot_kehadiran'] = $request->bobot_kehadiran ?? 0;
        $data['bobot_tugas'] = $request->bobot_tugas ?? 0;
        $data['bobot_project'] = $request->bobot_project ?? 0;
        $data['bobot_praktek'] = $request->bobot_praktek ?? 0;
        $data['bobot_kuis'] = $request->bobot_kuis ?? 0;
        $data['bobot_uts'] = $request->bobot_uts ?? 0;
        $data['bobot_uas'] = $request->bobot_uas ?? 0;
        $data['bobot_presentasi'] = $request->bobot_presentasi ?? 0;
        $data['asesmen_tertulis'] = $request->has('asesmen_tertulis') ? 1 : 0;
        $data['asesmen_lisan'] = $request->has('asesmen_lisan') ? 1 : 0;
        $data['asesmen_kinerja'] = $request->has('asesmen_kinerja') ? 1 : 0;
        $data['asesmen_portofolio'] = $request->has('asesmen_portofolio') ? 1 : 0;

        $rps->update($data);

        // Sync Penelitian & PKM Integrations
        $penelitianData = [];
        if ($request->has('penelitian_ids')) {
            foreach ($request->penelitian_ids as $pId) {
                $penelitianData[$pId] = ['bentuk_integrasi' => $request->input("penelitian_integrasi.{$pId}")];
            }
        }
        $rps->penelitians()->sync($penelitianData);

        $pkmData = [];
        if ($request->has('pkm_ids')) {
            foreach ($request->pkm_ids as $pId) {
                $pkmData[$pId] = ['bentuk_integrasi' => $request->input("pkm_integrasi.{$pId}")];
            }
        }
        $rps->pkms()->sync($pkmData);

        RpsPertemuan::where('rps_id', $rps->id)->delete();

        if ($request->has('pertemuan')) {
            foreach ($request->pertemuan as $item) {
                RpsPertemuan::create([
                    'rps_id' => $rps->id,
                    'minggu_ke' => $item['minggu_ke'] ?? '',
                    'sub_cpmk' => $item['sub_cpmk'] ?? '',
                    'bahan_kajian' => $item['bahan_kajian'] ?? '',
                    'metode_pembelajaran' => $item['metode_pembelajaran'] ?? '',
                    'waktu_pembelajaran' => $item['waktu_pembelajaran'] ?? '',
                    'pengalaman_belajar' => $item['pengalaman_belajar'] ?? '',
                    'kriteria_penilaian' => $item['kriteria_penilaian'] ?? '',
                    'indikator_penilaian' => $item['indikator_penilaian'] ?? '',
                    'bobot_penilaian' => $item['bobot_penilaian'] ?? 0,
                ]);
            }
        }

        return redirect()->route('penyusunan-rps.index')->with('success', 'RPS berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rps = Rps::findOrFail($id);
        $rps->delete();
        return redirect()->route('penyusunan-rps.index')->with('success', 'RPS berhasil dihapus.');
    }

    public function cetak($id)
    {
        $rps = Rps::with(['matakuliah', 'pertemuans', 'penelitians', 'pkms'])->findOrFail($id);
        
        // Ambil data referensi dan CPMK dari Matakuliah terkait (opsional, jika perlu ditampilkan di RPS header)
        $referensi_utama = \App\Models\RpsReferensi::where('kode_matakuliah', $rps->kode_matakuliah)->where('jenis', 'utama')->get();
        $referensi_pendukung = \App\Models\RpsReferensi::where('kode_matakuliah', $rps->kode_matakuliah)->where('jenis', 'pendukung')->get();
        $cpmks = \App\Models\Cpmk::with('cpl')->whereHas('matakuliahs', function($q) use ($rps) {
            $q->where('matakuliahs.kode_matakuliah', $rps->kode_matakuliah);
        })->get();
        $bahan_kajian = \App\Models\RpsBahanKajian::where('kode_matakuliah', $rps->kode_matakuliah)->orderBy('urutan', 'asc')->get();

        return view('rps.cetak', compact('rps', 'referensi_utama', 'referensi_pendukung', 'cpmks', 'bahan_kajian'));
    }

    public function getBahanKajian($kode)
    {
        $bahanKajian = \App\Models\RpsBahanKajian::where('kode_matakuliah', $kode)->orderBy('urutan', 'asc')->get();
        return response()->json($bahanKajian);
    }
}
