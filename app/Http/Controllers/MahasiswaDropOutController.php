<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pmb;
use App\Models\Alumni;
use App\Models\MahasiswaDropOut;

class MahasiswaDropOutController extends Controller
{
    public function index()
    {
        // Gabungkan semua tahun dari PMB + Alumni supaya angkatan yg belum ada di PMB tetap muncul
        $pmbMap = Pmb::pluck('jumlah_pmb', 'tahun')->toArray(); // [tahun => jumlah_pmb]

        // Semua tahun masuk unik dari PMB dan dari alumni
        $tahunDariAlumni = Alumni::distinct()->pluck('tahun_masuk')->toArray();
        $semuaTahun = array_unique(array_merge(array_keys($pmbMap), $tahunDariAlumni));
        rsort($semuaTahun); // descending

        $kohorts = [];
        foreach ($semuaTahun as $tahun) {
            $lulus = Alumni::where('tahun_masuk', $tahun)->count();
            $do    = MahasiswaDropOut::where('tahun_masuk', $tahun)->value('jumlah_do') ?? 0;

            // Jika tidak ada di PMB, fallback ke total yang diketahui (lulus + do)
            $jumlahMasuk = $pmbMap[$tahun] ?? ($lulus + $do);

            $lulusanPerTahun = Alumni::where('tahun_masuk', $tahun)
                ->selectRaw('tahun_lulus, count(*) as total')
                ->groupBy('tahun_lulus')
                ->pluck('total', 'tahun_lulus')
                ->toArray();

            $kohorts[] = [
                'tahun_masuk'       => $tahun,
                'jumlah_masuk'      => $jumlahMasuk,
                'jumlah_lulus'      => $lulus,
                'jumlah_do'         => $do,
                'lulusan_per_tahun' => $lulusanPerTahun,
                'sumber_pmb'        => isset($pmbMap[$tahun]), // true = data PMB lengkap
            ];
        }

        return view('mahasiswa_drop_out.index', compact('kohorts'));
    }

    public function cetak()
    {
        $pmbMap = Pmb::pluck('jumlah_pmb', 'tahun')->toArray();
        $tahunDariAlumni = Alumni::distinct()->pluck('tahun_masuk')->toArray();
        $semuaTahun = array_unique(array_merge(array_keys($pmbMap), $tahunDariAlumni));
        rsort($semuaTahun);

        $kohorts = [];
        foreach ($semuaTahun as $tahun) {
            $lulus = Alumni::where('tahun_masuk', $tahun)->count();
            $do    = MahasiswaDropOut::where('tahun_masuk', $tahun)->value('jumlah_do') ?? 0;
            $jumlahMasuk = $pmbMap[$tahun] ?? ($lulus + $do);

            $kohorts[] = [
                'tahun_masuk'       => $tahun,
                'jumlah_masuk'      => $jumlahMasuk,
                'jumlah_lulus'      => $lulus,
                'jumlah_do'         => $do,
            ];
        }

        $title = "Laporan Matriks Kohort & Retensi Mahasiswa";
        return view('mahasiswa_drop_out.cetak', compact('kohorts', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_masuk' => 'required|integer',
            'jumlah_do'   => 'required|integer|min:0',
        ]);

        MahasiswaDropOut::updateOrCreate(
            ['tahun_masuk' => $request->tahun_masuk],
            ['jumlah_do'   => $request->jumlah_do]
        );

        return redirect()->back()->with('success', 'Data Drop Out berhasil disimpan.');
    }
}
