<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\RekognisiDosen;
use App\Models\Praktisi;
use App\Models\Ts;

use App\Models\Kegiatan;
use App\Models\PesertaKegiatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', $this->getDashboardData());
    }

    public function welcome()
    {
        return view('welcome', $this->getDashboardData());
    }

    public function profilProdiPublic()
    {
        $profilProdi = \App\Models\ProfilProdi::first();
        return view('profil_public', compact('profilProdi'));
    }

    public function beritaPublic()
    {
        $beritaList = \App\Models\Berita::orderBy('tanggal', 'desc')->paginate(9);
        return view('berita.index_public', compact('beritaList'));
    }

    public function portalKegiatan()
    {
        $kegiatans = Kegiatan::with('pesertas')->orderBy('created_at', 'desc')->get();
        return view('kegiatan.portal', compact('kegiatans'));
    }

    public function uploadBuktiPembayaran(Request $request, PesertaKegiatan $peserta)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
            $peserta->bukti_pembayaran = $buktiPath;
            $peserta->status_pembayaran = 'menunggu_verifikasi';
            $peserta->save();
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }

    public function uploadFotoPeserta(Request $request, PesertaKegiatan $peserta)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($peserta->foto && \Storage::disk('public')->exists($peserta->foto)) {
                \Storage::disk('public')->delete($peserta->foto);
            }

            $fotoPath = $request->file('foto')->store('peserta_foto', 'public');
            $peserta->foto = $fotoPath;
            $peserta->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function cetakSertifikatPublic(Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        if ((int)$peserta->kegiatan_id !== (int)$kegiatan->id) {
            abort(404);
        }
        if ($peserta->status_kehadiran !== 'hadir_lengkap') {
            return redirect()->back()
                ->with('error', 'Sertifikat belum dapat dicetak karena Anda belum melakukan scan kehadiran lengkap (masuk & pulang).');
        }
        if ($kegiatan->jenis_kegiatan === 'berbayar' && $peserta->status_pembayaran !== 'lunas') {
            return redirect()->back()
                ->with('error', 'Sertifikat belum dapat dicetak karena status pembayaran Anda belum diverifikasi lunas.');
        }

        return view('kegiatan.sertifikat', compact('kegiatan', 'peserta'));
    }

    public function cekIdentitas(Request $request)
    {
        $id = $request->query('identifier');
        if (!$id) {
            return response()->json(['success' => false]);
        }

        $mhs = \App\Models\Mahasiswa::where('nim', $id)->first();
        if ($mhs) {
            return response()->json([
                'success' => true,
                'kategori' => 'Mahasiswa',
                'nama' => $mhs->nama
            ]);
        }

        $dsn = \App\Models\Dosen::where('nidn', $id)
            ->orWhere('nip', $id)
            ->orWhere('kode_dosen', $id)
            ->first();
        if ($dsn) {
            return response()->json([
                'success' => true,
                'kategori' => 'Dosen',
                'nama' => $dsn->nama_dosen ?? $dsn->nama
            ]);
        }

        $tendik = \App\Models\Tendik::where('nip_nik', $id)->first();
        if ($tendik) {
            return response()->json([
                'success' => true,
                'kategori' => 'Tendik',
                'nama' => $tendik->nama_lengkap
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function daftarKegiatan(Request $request, Kegiatan $kegiatan)
    {
        $today = Carbon::now()->startOfDay();
        $buka = $kegiatan->tgl_pendaftaran_buka ? Carbon::parse($kegiatan->tgl_pendaftaran_buka)->startOfDay() : null;
        $tutup = $kegiatan->tgl_pendaftaran_tutup ? Carbon::parse($kegiatan->tgl_pendaftaran_tutup)->endOfDay() : null;

        if ($buka && $today->lt($buka)) {
            return redirect()->back()->with('error', 'Pendaftaran gagal. Pendaftaran untuk kegiatan ini belum dibuka.');
        }

        if ($tutup && $today->gt($tutup)) {
            return redirect()->back()->with('error', 'Pendaftaran gagal. Pendaftaran untuk kegiatan ini sudah ditutup.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'kategori' => 'required|string|in:Mahasiswa,Dosen,Umum,Lainnya',
            'bukti_pembayaran' => $kegiatan->jenis_kegiatan === 'berbayar' ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $exists = PesertaKegiatan::where('kegiatan_id', $kegiatan->id)
            ->where('identifier', $request->identifier)
            ->exists();

        if ($exists) {
            $peserta = PesertaKegiatan::where('kegiatan_id', $kegiatan->id)
                ->where('identifier', $request->identifier)
                ->first();
            return redirect()->route('portal.kegiatan.kartu', $peserta)->with('success', 'Anda sudah terdaftar sebelumnya. Berikut kartu kegiatan Anda.');
        }

        $buktiPath = null;
        if ($kegiatan->jenis_kegiatan === 'berbayar' && $request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('peserta_foto', 'public');
        }

        $namaInput = $request->nama;
        $kategoriInput = $request->kategori;

        // Auto-detect Mahasiswa atau Dosen berdasarkan identifier
        $cekMahasiswa = \App\Models\Mahasiswa::where('nim', $request->identifier)->first();
        if ($cekMahasiswa) {
            $namaInput = $cekMahasiswa->nama;
            $kategoriInput = 'Mahasiswa';
        } else {
            // Coba cek dosen (berdasarkan nidn atau nip)
            $cekDosen = \App\Models\Dosen::where('nidn', $request->identifier)
                ->orWhere('nip', $request->identifier)
                ->first();
            if ($cekDosen) {
                $namaInput = $cekDosen->nama_dosen ?? $cekDosen->nama; // Tergantung struktur field nama dosen
                $kategoriInput = 'Dosen';
            }
        }

        $peserta = $kegiatan->pesertas()->create([
            'nama' => $namaInput,
            'identifier' => $request->identifier,
            'kategori' => $kategoriInput,
            'status_kehadiran' => 'absen',
            'status_pembayaran' => $kegiatan->jenis_kegiatan === 'berbayar' ? 'menunggu_verifikasi' : 'lunas',
            'bukti_pembayaran' => $buktiPath,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('portal.kegiatan.kartu', $peserta)->with('success', 'Pendaftaran berhasil! ' . ($kegiatan->jenis_kegiatan === 'berbayar' ? 'Pembayaran Anda sedang diverifikasi admin.' : 'Silakan cetak kartu kegiatan Anda.'));
    }

    public function presensiMandiriOnline(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'identifier' => 'required|string',
            'pin' => 'required|string',
        ]);

        $peserta = PesertaKegiatan::where('kegiatan_id', $kegiatan->id)
            ->where(function($q) use ($request) {
                $q->where('identifier', $request->identifier)
                  ->orWhere('barcode_token', $request->identifier);
            })
            ->first();

        if (!$peserta) {
            return redirect()->back()->with('error', 'Peserta tidak ditemukan. Pastikan Anda telah terdaftar terlebih dahulu.');
        }

        $now = Carbon::now();
        $inputPin = trim($request->pin);

        if ($kegiatan->pin_masuk && $inputPin === trim($kegiatan->pin_masuk)) {
            if ($peserta->jam_masuk) {
                return redirect()->back()->with('success', 'Anda sudah melakukan presensi masuk sebelumnya pada ' . $peserta->jam_masuk);
            }
            $peserta->jam_masuk = $now;
            $peserta->save();

            return redirect()->back()->with('success', 'Presensi masuk online berhasil dicatat untuk ' . $peserta->nama);
        }

        if ($kegiatan->pin_pulang && $inputPin === trim($kegiatan->pin_pulang)) {
            if (!$peserta->jam_masuk) {
                return redirect()->back()->with('error', 'Presensi gagal. Anda belum melakukan presensi masuk.');
            }
            if ($peserta->jam_pulang) {
                return redirect()->back()->with('success', 'Anda sudah melakukan presensi pulang sebelumnya.');
            }
            $peserta->jam_pulang = $now;
            $peserta->status_kehadiran = 'hadir_lengkap';
            $peserta->save();

            return redirect()->back()->with('success', 'Presensi pulang online berhasil! Anda sekarang berhak mengunduh sertifikat.');
        }

        return redirect()->back()->with('error', 'PIN presensi yang Anda masukkan salah / tidak valid.');
    }

    public function absensiMandiriPage(Kegiatan $kegiatan)
    {
        $isOnline = \Str::contains(strtolower($kegiatan->tempat), 'online') || 
                    \Str::contains(strtolower($kegiatan->tempat), 'zoom') || 
                    \Str::contains(strtolower($kegiatan->tempat), 'meet');

        if (!$isOnline) {
            return redirect()->route('portal.kegiatan')->with('error', 'Fitur presensi mandiri online tidak tersedia untuk kegiatan tatap muka.');
        }

        if (!$kegiatan->presensi_online_aktif) {
            return view('kegiatan.absensi_mandiri_tutup', compact('kegiatan'));
        }

        return view('kegiatan.absensi_mandiri', compact('kegiatan'));
    }

    public function absensiMandiriSubmit(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        if (!$kegiatan->presensi_online_aktif) {
            return redirect()->back()->with('error', 'Presensi gagal. Akses link presensi sudah ditutup oleh panitia.');
        }

        $peserta = PesertaKegiatan::where('kegiatan_id', $kegiatan->id)
            ->where(function($q) use ($request) {
                $q->where('identifier', $request->identifier)
                  ->orWhere('barcode_token', $request->identifier);
            })
            ->first();

        if (!$peserta) {
            return redirect()->back()->with('error', 'Presensi gagal. NIM / NIP Anda belum terdaftar pada kegiatan ini.');
        }

        if ($kegiatan->jenis_kegiatan === 'berbayar' && $peserta->status_pembayaran !== 'lunas') {
            return redirect()->back()->with('error', 'Presensi gagal. Status pendaftaran Anda belum Lunas.');
        }

        $now = Carbon::now();
        $peserta->jam_masuk = $peserta->jam_masuk ?? $now;
        $peserta->jam_pulang = $peserta->jam_pulang ?? $now;
        $peserta->status_kehadiran = 'hadir_lengkap';
        $peserta->save();

        return redirect()->back()->with('success', 'Presensi online berhasil! Atas nama ' . $peserta->nama . '. Anda sekarang sudah bisa mengunduh sertifikat.');
    }

    public function kartuKegiatan(PesertaKegiatan $peserta)
    {
        $peserta->load('kegiatan');
        return view('kegiatan.kartu', compact('peserta'));
    }

    private function getDashboardData()
    {
        $jumlahDosen = Dosen::count();
        $jumlahMahasiswa = Mahasiswa::count();
        $jumlahKelas = Kelas::count();

        $jfaData = Dosen::select('jfa')
            ->selectRaw('count(*) as total')
            ->whereNotNull('jfa')
            ->groupBy('jfa')
            ->pluck('total', 'jfa');

        $kepangkatanData = Dosen::select('kepangkatan')
            ->selectRaw('count(*) as total')
            ->whereNotNull('kepangkatan')
            ->groupBy('kepangkatan')
            ->pluck('total', 'kepangkatan');

        $totalRekognisi = RekognisiDosen::where('is_keanggotaan', false)->count();

        $rekognisiPerTS = RekognisiDosen::where('is_keanggotaan', false)
            ->select('ts_id')
            ->selectRaw('count(*) as total')
            ->groupBy('ts_id')
            ->pluck('total', 'ts_id');

        $tsLabels = Ts::whereIn('id', $rekognisiPerTS->keys())->pluck('tahun_sekarang', 'id');
        $rekognisiPerTSLabeled = [];
        foreach ($rekognisiPerTS as $tsId => $total) {
            $label = $tsLabels[$tsId] ?? 'TS #'.$tsId;
            $rekognisiPerTSLabeled[$label] = $total;
        }

        $rekognisiPerDosen = RekognisiDosen::where('is_keanggotaan', false)
            ->select('kode_dosen', 'nama_dosen')
            ->selectRaw('count(*) as total')
            ->groupBy('kode_dosen', 'nama_dosen')
            ->orderByDesc('total')
            ->get();

        $rasioDosenMahasiswa = $jumlahDosen > 0 ? round($jumlahMahasiswa / $jumlahDosen, 2) : 0;

        $pendidikanData = Dosen::select('pendidikan')
            ->selectRaw('count(*) as total')
            ->whereNotNull('pendidikan')
            ->groupBy('pendidikan')
            ->orderByDesc('total')
            ->pluck('total', 'pendidikan');

        $pmbData = \App\Models\Pmb::orderBy('tahun', 'asc')->get();
        $dosenList = Dosen::orderBy('nama_dosen', 'asc')->get();
        $prestasiList = \App\Models\PrestasiMahasiswa::with('mahasiswa')->orderBy('tahun', 'desc')->get();
        $prestasiDosenList = \App\Models\PrestasiDosen::with('dosen')->orderBy('tahun', 'desc')->get();

        // Calculate PKS & IA separately per year for the dashboard chart
        $pksIaList = \App\Models\PksIa::all();
        $years = collect();
        foreach ($pksIaList as $item) {
            if ($item->tgl_pks) {
                $years->push($item->tgl_pks->format('Y'));
            }
            if ($item->tgl_ia) {
                $years->push($item->tgl_ia->format('Y'));
            }
        }
        $years = $years->unique()->sort()->values();
        
        $pksChartLabels = $years->toArray();
        $pksChartData = [];
        $iaChartData = [];
        
        foreach ($years as $year) {
            $pksCount = $pksIaList->filter(function($item) use ($year) {
                return $item->tgl_pks && $item->tgl_pks->format('Y') === $year && 
                       (!empty($item->file_pks) || !empty($item->no_pks_ubsi));
            })->count();
            
            $iaCount = $pksIaList->filter(function($item) use ($year) {
                if ($item->tgl_ia) {
                    return $item->tgl_ia->format('Y') === $year;
                } else {
                    return $item->tgl_pks && $item->tgl_pks->format('Y') === $year && 
                           (!empty($item->file_ia) || !empty($item->no_ia_ubsi));
                }
            })->count();
            
            $pksChartData[] = $pksCount;
            $iaChartData[] = $iaCount;
        }

        $totalPks = \App\Models\PksIa::where(function($q) {
            $q->whereNotNull('file_pks')->where('file_pks', '!=', '')
              ->orWhereNotNull('no_pks_ubsi')->where('no_pks_ubsi', '!=', '');
        })->count();

        $totalIa = \App\Models\PksIa::whereNotNull('file_ia')->where('file_ia', '!=', '')->count();

        // Calculate average GPA per TS for the dashboard chart
        $avgIpkPerTs = \App\Models\IpkMahasiswa::join('ts', 'ipk_mahasiswa.ts_id', '=', 'ts.id')
            ->selectRaw('ts.tahun_sekarang, AVG(ipk_mahasiswa.ipk) as average')
            ->groupBy('ts.tahun_sekarang', 'ipk_mahasiswa.ts_id')
            ->orderBy('ipk_mahasiswa.ts_id', 'desc')
            ->get();

        $ipkChartLabels = $avgIpkPerTs->pluck('tahun_sekarang')->toArray();
        $ipkChartData = $avgIpkPerTs->pluck('average')->map(function($val) {
            return round($val, 2);
        })->toArray();

        // Calculate student achievement trends by TS and Bidang for the dashboard chart
        $tsList = \App\Models\Ts::orderBy('tahun_sekarang', 'asc')->get();
        $prestasis = \App\Models\PrestasiMahasiswa::all();
        
        $prestasiMhsTsLabels = $tsList->pluck('tahun_sekarang')->toArray();
        $tsIds = $tsList->pluck('id')->toArray();
        
        $prestasiMhsTsData = [];
        $fields = ['Akademik', 'Non Akademik', 'Akademik Non Lomba', 'Partisipan'];
        foreach ($fields as $field) {
            $prestasiMhsTsData[$field] = [];
            foreach ($tsIds as $tsId) {
                $count = $prestasis->filter(function($p) use ($field, $tsId) {
                    return $p->bidang_prestasi === $field && $p->ts_id == $tsId;
                })->count();
                $prestasiMhsTsData[$field][] = $count;
            }
        }

        // Calculate student certifications by scheme for the dashboard chart
        $sertifikasiMhsBySkema = \App\Models\SertifikasiMahasiswa::select('skema_serkom')
            ->selectRaw('count(*) as total')
            ->groupBy('skema_serkom')
            ->orderBy('total', 'desc')
            ->get();

        $serkomChartLabels = $sertifikasiMhsBySkema->pluck('skema_serkom')->toArray();
        $serkomChartData = $sertifikasiMhsBySkema->pluck('total')->toArray();

        // Calculate HKI category counts for the dashboard (de-duplicated by no_permohonan)
        $hkis = \App\Models\Hki::all();
        
        $hkiMhsRecords = $hkis->filter(function($h) {
            return !empty($h->nim) && empty($h->kode_dosen);
        });
        $hkiMhsMandiriCount = $hkiMhsRecords->unique('no_permohonan')->count();
        
        $hkiDosenRecords = $hkis->filter(function($h) {
            return empty($h->nim) && !empty($h->kode_dosen);
        });
        $hkiDosenMandiriCount = $hkiDosenRecords->unique('no_permohonan')->count();
        
        $hkiKolaborasiRecords = $hkis->filter(function($h) {
            return !empty($h->nim) && !empty($h->kode_dosen);
        });
        $hkiKolaborasiCount = $hkiKolaborasiRecords->unique('no_permohonan')->count();
        $hkiKolaborasiMhsCount = $hkiKolaborasiRecords->pluck('nim')->unique()->filter()->count();
        
        $totalHkiCount = $hkis->unique('no_permohonan')->count();

        // Calculate unique students and lecturers owning HKI
        $totalMhsPunyaHkiCount = $hkis->whereNotNull('nim')->where('nim', '!=', '')->pluck('nim')->unique()->filter()->count();
        
        $lecturerCodesWithHki = [];
        foreach ($hkis as $h) {
            $kodes = array_map('trim', explode(',', $h->kode_dosen));
            foreach ($kodes as $k) {
                if (!empty($k)) {
                    $lecturerCodesWithHki[] = $k;
                }
            }
        }
        $totalDosenPunyaHkiCount = Dosen::whereIn('kode_dosen', array_unique($lecturerCodesWithHki))->count();

        // Calculate percentages
        $persenMhsHki = $jumlahMahasiswa > 0 ? round(($totalMhsPunyaHkiCount / $jumlahMahasiswa) * 100, 2) : 0;
        $persenDosenHki = $jumlahDosen > 0 ? round(($totalDosenPunyaHkiCount / $jumlahDosen) * 100, 2) : 0;

        // Praktisi per TS for dashboard chart
        $allTs = Ts::orderBy('id', 'asc')->get();
        $praktisiChartLabels = $allTs->pluck('tahun_sekarang')->toArray();
        $praktisiAll = Praktisi::all();
        $praktisiChartData = $allTs->map(function($ts) use ($praktisiAll) {
            return $praktisiAll->where('ts_id', $ts->id)->count();
        })->toArray();
        $totalPraktisi = $praktisiAll->count();

        // Query integrated RPS for Kaprodi accreditation support
        $rpsIntegrasi = \App\Models\Rps::with(['matakuliah', 'penelitians', 'pkms'])
            ->has('penelitians')
            ->orHas('pkms')
            ->get();

        $profilProdi = \App\Models\ProfilProdi::first();
        $beritaTerbaru = \App\Models\Berita::orderBy('tanggal', 'desc')->take(3)->get();
        $mitraList = \App\Models\Kerjasama::orderBy('created_at', 'desc')->get();
        
        $alumniInspiratif = \App\Models\Alumni::where('is_featured', true)
            ->whereNotNull('foto')
            ->whereNotNull('testimoni')
            ->orderBy('tahun_lulus', 'desc')
            ->take(5)
            ->get();

        return compact(
            'jumlahDosen', 'jumlahMahasiswa', 'jumlahKelas',
            'jfaData', 'kepangkatanData',
            'totalRekognisi', 'rekognisiPerTSLabeled', 'rekognisiPerDosen',
            'rasioDosenMahasiswa', 'pendidikanData', 'pmbData', 'dosenList', 'prestasiList', 'prestasiDosenList',
            'pksChartLabels', 'pksChartData', 'iaChartData', 'totalPks', 'totalIa', 'mitraList',
            'ipkChartLabels', 'ipkChartData',
            'hkiMhsMandiriCount', 'hkiDosenMandiriCount', 'hkiKolaborasiCount', 'hkiKolaborasiMhsCount', 'totalHkiCount',
            'totalMhsPunyaHkiCount', 'totalDosenPunyaHkiCount', 'persenMhsHki', 'persenDosenHki',
            'prestasiMhsTsLabels', 'prestasiMhsTsData', 'serkomChartLabels', 'serkomChartData',
            'praktisiChartLabels', 'praktisiChartData', 'totalPraktisi', 'rpsIntegrasi',
            'profilProdi', 'beritaTerbaru', 'alumniInspiratif'
        );
    }

    public function bacaBerita($slug)
    {
        $berita = \App\Models\Berita::where('slug', $slug)->firstOrFail();
        return view('berita.show', compact('berita'));
    }

    public function sitemap()
    {
        $berita = \App\Models\Berita::where('status_publish', 'published')->latest()->get();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static routes
        $urls = [
            route('welcome'),
            route('profil-prodi.public'),
            route('berita.public'),
            route('portal.kegiatan'),
            route('portal.beasiswa'),
        ];

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url) . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Dynamic routes (Berita)
        foreach ($berita as $b) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars(route('berita.baca', $b->slug)) . '</loc>';
            $xml .= '<lastmod>' . $b->updated_at->tz('UTC')->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
