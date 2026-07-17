<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KeuanganProdi;
use App\Models\Ts;

class KeuanganProdiController extends Controller
{
    public function index()
    {
        // Ambil semua tahun dari tabel TS secara unik dan urutkan menurun
        $tahunList = \Illuminate\Support\Facades\DB::table('ts')
            ->select('tahun_sekarang')
            ->distinct()
            ->orderBy('tahun_sekarang', 'desc')
            ->pluck('tahun_sekarang');
            
        // Pastikan setiap tahun ada di tabel keuangan_prodis
        foreach ($tahunList as $tahun) {
            if ($tahun) {
                KeuanganProdi::firstOrCreate(
                    ['tahun_akademik' => $tahun],
                    [
                        'jumlah_mahasiswa_aktif' => \App\Models\Mahasiswa::count(), // Ambil dari tabel mahasiswas
                        'dana_pendidikan' => 0,
                        'dana_penelitian' => 0,
                        'dana_pkm' => 0,
                        'dana_investasi' => 0
                    ]
                );
            }
        }

        $keuangans = KeuanganProdi::orderBy('tahun_akademik', 'desc')->get();
        
        // Auto-sync dana penelitian & pkm dari tabel terkait
        foreach ($keuangans as $keuangan) {
            // Jika jumlah mahasiswa aktif masih 0, ambil dari data real mahasiswas
            if ($keuangan->jumlah_mahasiswa_aktif == 0) {
                $keuangan->jumlah_mahasiswa_aktif = \App\Models\Mahasiswa::count();
                $keuangan->save(); // Simpan agar bisa diedit manual nanti
            }
            
            $tahun = $keuangan->tahun_akademik;
            
            $autoPenelitian = \Illuminate\Support\Facades\DB::table('penelitian_dosens')
                ->join('ts', 'ts.id', '=', 'penelitian_dosens.ts_id')
                ->where('ts.tahun_sekarang', $tahun)
                ->sum('penelitian_dosens.biaya');
                
            $autoHibah = \Illuminate\Support\Facades\DB::table('hibah_penelitians')
                ->join('ts', 'ts.id', '=', 'hibah_penelitians.ts_id')
                ->where('ts.tahun_sekarang', $tahun)
                ->sum('hibah_penelitians.biaya');
                
            $autoPkm = \Illuminate\Support\Facades\DB::table('pkm_dosens')
                ->join('ts', 'ts.id', '=', 'pkm_dosens.ts_id')
                ->where('ts.tahun_sekarang', $tahun)
                ->sum('pkm_dosens.biaya');
                
            // Gunakan dana auto-sync secara rill (jangan dibagi 1 juta)
            $totalAutoPen = $autoPenelitian + $autoHibah; 
            $totalAutoPkm = $autoPkm; 
            
            $keuangan->auto_penelitian = $totalAutoPen;
            $keuangan->auto_pkm = $totalAutoPkm;
            
            // Override display value
            $keuangan->dana_penelitian = max($keuangan->dana_penelitian, $totalAutoPen);
            $keuangan->dana_pkm = max($keuangan->dana_pkm, $totalAutoPkm);
        }
        
        $tsList = \App\Models\Ts::orderBy('tahun_sekarang', 'desc')->get();
        
        return view('keuangan_prodi.index', compact('keuangans', 'tsList'));
    }

    public function cetak()
    {
        $keuangans = KeuanganProdi::orderBy('tahun_akademik', 'desc')->get();
        
        foreach ($keuangans as $keuangan) {
            $tahun = $keuangan->tahun_akademik;
            
            $autoPenelitian = \Illuminate\Support\Facades\DB::table('penelitian_dosens')
                ->join('ts', 'ts.id', '=', 'penelitian_dosens.ts_id')
                ->where('ts.tahun_sekarang', $tahun)
                ->sum('penelitian_dosens.biaya');
                
            $autoHibah = \Illuminate\Support\Facades\DB::table('hibah_penelitians')
                ->join('ts', 'ts.id', '=', 'hibah_penelitians.ts_id')
                ->where('ts.tahun_sekarang', $tahun)
                ->sum('hibah_penelitians.biaya');
                
            $autoPkm = \Illuminate\Support\Facades\DB::table('pkm_dosens')
                ->join('ts', 'ts.id', '=', 'pkm_dosens.ts_id')
                ->where('ts.tahun_sekarang', $tahun)
                ->sum('pkm_dosens.biaya');
                
            $totalAutoPen = $autoPenelitian + $autoHibah; 
            $totalAutoPkm = $autoPkm; 
            
            $keuangan->dana_penelitian = max($keuangan->dana_penelitian, $totalAutoPen);
            $keuangan->dana_pkm = max($keuangan->dana_pkm, $totalAutoPkm);
        }

        $title = "Laporan Penggunaan Dana Akademik & Tridharma";
        return view('keuangan_prodi.cetak', compact('keuangans', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string',
            'jumlah_mahasiswa_aktif' => 'required|integer|min:1',
            'dana_pendidikan' => 'required|numeric|min:0',
            'dana_penelitian' => 'required|numeric|min:0',
            'dana_pkm' => 'required|numeric|min:0',
            'dana_investasi' => 'required|numeric|min:0',
        ]);

        KeuanganProdi::updateOrCreate(
            ['tahun_akademik' => $request->tahun_akademik],
            $request->only([
                'jumlah_mahasiswa_aktif', 'dana_pendidikan', 
                'dana_penelitian', 'dana_pkm', 'dana_investasi'
            ])
        );

        return redirect()->back()->with('success', 'Data Keuangan Prodi berhasil disimpan.');
    }

    public function destroy(KeuanganProdi $keuanganProdi)
    {
        $keuanganProdi->delete();
        return redirect()->back()->with('success', 'Data dihapus.');
    }
}
