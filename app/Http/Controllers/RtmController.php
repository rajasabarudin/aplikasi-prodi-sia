<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use App\Models\Rtm;
use App\Models\RtmTugas;
use App\Models\RtmPenilaian;
use Illuminate\Http\Request;

class RtmController extends Controller
{
    public function index()
    {
        $rpsList = Rps::with(['matakuliah', 'rtm'])->get();
        return view('rtm.index', compact('rpsList'));
    }

    public function generate($id)
    {
        $rps = Rps::with('pertemuans')->findOrFail($id);
        
        $pertemuans = $rps->pertemuans->sortBy(function($item) {
            return (int) $item->minggu_ke;
        });

        Rtm::where('rps_id', $rps->id)->delete();
        $rtm = Rtm::create([
            'rps_id' => $rps->id,
            'nomor_dokumen' => 'UBSI/DA RTM.' . $rps->kode_matakuliah,
            'dosen_pengampu' => $rps->dosen_pengembang,
            'semester' => (int) ($rps->matakuliah?->semester ?: 1)
        ]);

        $tugas_ke = 1;
        $hasTugas = false;
        foreach ($pertemuans as $pertemuan) {
            $bobot = (float) $pertemuan->bobot_penilaian;
            if ($bobot > 0) {
                $hasTugas = true;
                $rtmTugas = RtmTugas::create([
                    'rtm_id' => $rtm->id,
                    'minggu_ke' => $pertemuan->minggu_ke,
                    'tugas_ke' => (string) $tugas_ke,
                    'bentuk_tugas' => 'Tugas ' . ($pertemuan->metode_pembelajaran ?: 'Mandiri'),
                    'judul_tugas' => 'Tugas ' . $tugas_ke,
                    'sub_cpmk' => $pertemuan->sub_cpmk ?: '',
                    'obyek_garapan' => $pertemuan->bahan_kajian ?: '',
                    'metode_pengerjaan' => $pertemuan->pengalaman_belajar ?: '',
                    'bentuk_format_luaran' => '',
                    'waktu_pengerjaan' => $pertemuan->waktu_pembelajaran ?: '',
                    'waktu_pengumpulan' => 'Minggu ke-' . $pertemuan->minggu_ke,
                    'lain_lain' => '',
                    'daftar_rujukan' => '',
                ]);
                
                RtmPenilaian::create([
                    'rtm_tugas_id' => $rtmTugas->id,
                    'indikator' => $pertemuan->indikator_penilaian ?: '',
                    'teknik_penilaian' => $pertemuan->kriteria_penilaian ?: '',
                    'bobot_penilaian' => $pertemuan->bobot_penilaian ?: '0'
                ]);
                
                $tugas_ke++;
            }
        }
        
        // Jika tidak ada tugas sama sekali, buat 1 draft
        if (!$hasTugas) {
            RtmTugas::create([
                'rtm_id' => $rtm->id,
                'minggu_ke' => '1',
                'tugas_ke' => '1',
                'bentuk_tugas' => 'Tugas Mandiri',
                'judul_tugas' => 'Tugas 1 (Draft)',
                'sub_cpmk' => '',
                'obyek_garapan' => '',
                'metode_pengerjaan' => '',
                'bentuk_format_luaran' => '',
                'waktu_pengerjaan' => '',
                'waktu_pengumpulan' => '',
                'lain_lain' => '',
                'daftar_rujukan' => '',
            ]);
        }

        return redirect()->route('penyusunan-rtm.index')->with('success', 'RTM berhasil digenerate otomatis dan akurat dari Rincian Pertemuan RPS!');
    }


    public function edit($id)
    {
        $rtm = Rtm::with(['tugas.penilaians', 'rps.matakuliah'])->findOrFail($id);
        return view('rtm.edit', compact('rtm'));
    }

    public function update(Request $request, $id)
    {
        $rtm = Rtm::findOrFail($id);
        
        $rtm->update([
            'nomor_dokumen' => $request->nomor_dokumen,
            'dosen_pengampu' => $request->dosen_pengampu,
            'semester' => $request->semester
        ]);
        
        if ($request->has('tugas')) {
            foreach ($request->tugas as $tugasId => $tData) {
                $tugas = RtmTugas::findOrFail($tugasId);
                $tugas->update([
                    'bentuk_tugas' => $tData['bentuk_tugas'] ?? '',
                    'judul_tugas' => $tData['judul_tugas'] ?? '',
                    'sub_cpmk' => $tData['sub_cpmk'] ?? '',
                    'obyek_garapan' => $tData['obyek_garapan'] ?? '',
                    'metode_pengerjaan' => $tData['metode_pengerjaan'] ?? '',
                    'bentuk_format_luaran' => $tData['bentuk_format_luaran'] ?? '',
                    'waktu_pengerjaan' => $tData['waktu_pengerjaan'] ?? '',
                    'waktu_pengumpulan' => $tData['waktu_pengumpulan'] ?? '',
                    'lain_lain' => $tData['lain_lain'] ?? '',
                    'daftar_rujukan' => $tData['daftar_rujukan'] ?? '',
                ]);
            }
        }
        
        return redirect()->route('penyusunan-rtm.index')->with('success', 'Data RTM berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rtm = Rtm::findOrFail($id);
        $rtm->delete();
        return redirect()->route('penyusunan-rtm.index')->with('success', 'Data RTM berhasil dihapus.');
    }

    public function cetak($id)
    {
        $rtm = Rtm::with(['tugas.penilaians', 'rps.matakuliah', 'rps.penelitians', 'rps.pkms'])->findOrFail($id);
        return view('rtm.cetak', compact('rtm'));
    }
}
