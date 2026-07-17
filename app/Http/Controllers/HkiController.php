<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\TS;
use App\Models\RekognisiDosen;
use Illuminate\Http\Request;

class HkiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'nullable|exists:mahasiswas,nim',
            'no_permohonan' => 'required|string|max:255',
            'tgl_permohonan' => 'required|date',
            'jenis_ciptaan' => 'required|string|max:255',
            'judul_ciptaan' => 'required|string|max:255',
            'kode_dosen' => 'nullable|string|max:255',
            'nama_dosen' => 'nullable|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        $hki = Hki::create($request->all());

        $this->syncToRekognisi($hki, $request);

        return redirect()->back()->with('success', 'Data HKI berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $hki = Hki::findOrFail($id);

        $request->validate([
            'nim' => 'nullable|exists:mahasiswas,nim',
            'no_permohonan' => 'required|string|max:255',
            'tgl_permohonan' => 'required|date',
            'jenis_ciptaan' => 'required|string|max:255',
            'judul_ciptaan' => 'required|string|max:255',
            'kode_dosen' => 'nullable|string|max:255',
            'nama_dosen' => 'nullable|string|max:255',
            'link_dokumen' => 'nullable|url|max:255',
        ]);

        $hki->update($request->all());

        $this->syncToRekognisi($hki, $request);

        return redirect()->back()->with('success', 'Data HKI berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hki = Hki::findOrFail($id);
        $hkiNoPermohonan = $hki->no_permohonan;
        
        $hki->rekognisiDosen()->delete();
        $hki->delete();

        // Re-sync next HKI with the same no_permohonan to preserve the Rekognisi record
        if (!empty($hkiNoPermohonan)) {
            $nextHki = Hki::where('no_permohonan', $hkiNoPermohonan)->first();
            if ($nextHki) {
                $dummyRequest = new Request($nextHki->toArray());
                $this->syncToRekognisi($nextHki, $dummyRequest);
            }
        }

        return redirect()->back()->with('success', 'Data HKI berhasil dihapus.');
    }

    private function syncToRekognisi($hki, Request $request)
    {
        $hkiNoPermohonan = $hki->no_permohonan;
        if (empty($hkiNoPermohonan)) {
            return;
        }

        // Find primary Hki row with this no_permohonan to own the Rekognisi relation
        $primaryHki = Hki::where('no_permohonan', $hkiNoPermohonan)->orderBy('id', 'asc')->first();
        if (!$primaryHki) {
            return;
        }

        // Delete existing rekognisi records linked to any HKI row sharing the same no_permohonan
        $hkiIds = Hki::where('no_permohonan', $hkiNoPermohonan)->pluck('id');
        RekognisiDosen::whereIn('hki_id', $hkiIds)->delete();

        $kodeDosenStr = $request->input('kode_dosen', '');
        $namaDosenStr = $request->input('nama_dosen', '');

        $kodeDosens = array_filter(array_map('trim', explode(',', $kodeDosenStr)));
        $namaDosens = array_filter(array_map('trim', explode(',', $namaDosenStr)));

        if (empty($kodeDosens)) {
            return;
        }

        // Determine TS based on tgl_permohonan
        $tglPermohonan = $request->input('tgl_permohonan');
        $tahun = date('Y', strtotime($tglPermohonan));
        
        $ts = TS::where('tahun_sekarang', 'like', "%{$tahun}%")->first();
        if (!$ts) {
            $ts = TS::orderBy('tahun_sekarang', 'desc')->first();
        }
        $tsId = $ts ? $ts->id : null;

        if (!$tsId) {
            return;
        }

        $level = 'nasional';
        $jenis = strtolower($request->input('jenis_ciptaan', ''));
        $judulCiptaan = strtolower($request->input('judul_ciptaan', ''));
        if (str_contains($jenis, 'internasional') || str_contains($judulCiptaan, 'internasional') || str_contains($jenis, 'international') || str_contains($judulCiptaan, 'international')) {
            $level = 'internasional';
        }

        $judul = $request->input('judul_ciptaan');
        $jenisCiptaan = $request->input('jenis_ciptaan');
        $namaRekognisi = "HKI ({$jenisCiptaan}): {$judul}";
        if (strlen($namaRekognisi) > 200) {
            $namaRekognisi = substr($namaRekognisi, 0, 197) . '...';
        }

        $linkDokumen = $request->input('link_dokumen');

        foreach ($kodeDosens as $index => $kode) {
            if (empty($kode)) continue;
            
            $nama = '';
            if (isset($namaDosens[$index])) {
                $nama = $namaDosens[$index];
            } else {
                $dosen = \App\Models\Dosen::where('kode_dosen', $kode)->first();
                $nama = $dosen ? $dosen->nama_dosen : '';
            }

            RekognisiDosen::create([
                'kode_dosen' => $kode,
                'nama_dosen' => $nama,
                'nama_rekognisi' => $namaRekognisi,
                'tahun' => $tahun,
                'ts_id' => $tsId,
                'level' => $level,
                'link_dokumen' => $linkDokumen,
                'is_keanggotaan' => false,
                'hki_id' => $primaryHki->id,
            ]);
        }
    }
}
