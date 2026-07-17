<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PesertaKegiatan;
use App\Models\KegiatanDosen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::withCount('pesertas')->latest()->get();
        return view('kegiatan.index', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'bidang_kegiatan' => 'required|string',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:255',
            'narasumber' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanda_tangan_nama' => 'nullable|string|max:255',
            'tanda_tangan_jabatan' => 'nullable|string|max:255',
            'jenis_kegiatan' => 'required|string|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0',
            'rekening_info' => 'nullable|string',
            'tgl_pendaftaran_buka' => 'required|date',
            'tgl_pendaftaran_tutup' => 'required|date|after_or_equal:tgl_pendaftaran_buka',
            'pin_masuk' => 'nullable|string|max:50',
            'pin_pulang' => 'nullable|string|max:50',
        ]);

        Kegiatan::create($request->all());

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $pesertas = $kegiatan->pesertas()->latest()->get();
        return view('kegiatan.show', compact('kegiatan', 'pesertas'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'bidang_kegiatan' => 'required|string',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:255',
            'narasumber' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanda_tangan_nama' => 'nullable|string|max:255',
            'tanda_tangan_jabatan' => 'nullable|string|max:255',
            'jenis_kegiatan' => 'required|string|in:gratis,berbayar',
            'harga' => 'nullable|integer|min:0',
            'rekening_info' => 'nullable|string',
            'tgl_pendaftaran_buka' => 'required|date',
            'tgl_pendaftaran_tutup' => 'required|date|after_or_equal:tgl_pendaftaran_buka',
            'pin_masuk' => 'nullable|string|max:50',
            'pin_pulang' => 'nullable|string|max:50',
        ]);

        $kegiatan->update($request->all());

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Detail kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function addPeserta(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'identifier' => 'required|string|max:255',
            'kategori' => 'required|string|in:Mahasiswa,Dosen,Tendik,Umum,Lainnya',
        ]);

        $kegiatan->pesertas()->create([
            'nama' => $request->nama,
            'identifier' => $request->identifier,
            'kategori' => $request->kategori,
            'status_kehadiran' => 'absen',
            'status_pembayaran' => $kegiatan->jenis_kegiatan === 'berbayar' ? 'belum_bayar' : 'lunas',
        ]);

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function destroyPeserta(Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        $peserta->delete();
        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Peserta berhasil dihapus.');
    }

    public function verifikasiPembayaran(Request $request, Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        if ((int)$peserta->kegiatan_id !== (int)$kegiatan->id) {
            abort(404);
        }

        $request->validate([
            'status_pembayaran' => 'required|string|in:lunas,belum_bayar,menunggu_verifikasi',
        ]);

        $peserta->status_pembayaran = $request->status_pembayaran;
        $peserta->save();

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Status pembayaran peserta ' . $peserta->nama . ' berhasil diperbarui.');
    }

    public function presensi(Kegiatan $kegiatan)
    {
        return view('kegiatan.presensi', compact('kegiatan'));
    }

    public function submitPresensi(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'barcode_token' => 'required|string',
            'tipe' => 'required|string|in:masuk,pulang',
        ]);

        $peserta = PesertaKegiatan::where('kegiatan_id', $kegiatan->id)
            ->where('barcode_token', $request->barcode_token)
            ->first();

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak terdaftar untuk kegiatan ini.'
            ], 404);
        }

        $now = Carbon::now();

        if ($request->tipe === 'masuk') {
            if ($peserta->jam_masuk) {
                return response()->json([
                    'success' => true,
                    'message' => $peserta->nama . ' sudah melakukan presensi masuk sebelumnya.',
                    'peserta' => $peserta
                ]);
            }
            $peserta->jam_masuk = $now;
            $peserta->status_kehadiran = 'hadir_masuk';
            $peserta->save();

            return response()->json([
                'success' => true,
                'message' => 'Presensi masuk berhasil untuk ' . $peserta->nama,
                'peserta' => $peserta
            ]);
        } else {
            if (!$peserta->jam_masuk) {
                return response()->json([
                    'success' => false,
                    'message' => $peserta->nama . ' belum presensi masuk. Silakan presensi masuk terlebih dahulu.'
                ], 400);
            }
            if ($peserta->jam_pulang) {
                return response()->json([
                    'success' => true,
                    'message' => $peserta->nama . ' sudah melakukan presensi pulang sebelumnya.',
                    'peserta' => $peserta
                ]);
            }
            $peserta->jam_pulang = $now;
            $peserta->status_kehadiran = 'hadir_lengkap';
            $peserta->save();
            
            $this->syncToKegiatanDosen($kegiatan, $peserta);
            $this->syncToKegiatanTendik($kegiatan, $peserta);

            return response()->json([
                'success' => true,
                'message' => 'Presensi selesai berhasil! ' . $peserta->nama . ' berhak mendapatkan sertifikat.',
                'peserta' => $peserta
            ]);
        }
    }

    public function markHadir(Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        if ((int)$peserta->kegiatan_id !== (int)$kegiatan->id) {
            abort(404);
        }

        $peserta->status_kehadiran = 'hadir_lengkap';
        if (!$peserta->jam_masuk) {
            $peserta->jam_masuk = now();
        }
        if (!$peserta->jam_pulang) {
            $peserta->jam_pulang = now();
        }
        $peserta->save();

        $this->syncToKegiatanDosen($kegiatan, $peserta);
        $this->syncToKegiatanTendik($kegiatan, $peserta);

        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Peserta ' . $peserta->nama . ' berhasil ditandai hadir lengkap secara manual.');
    }

    public function cetakPresensi(Kegiatan $kegiatan)
    {
        $kegiatan->load(['pesertas' => function($q) {
            $q->orderBy('nama', 'asc');
        }]);
        $title = "Daftar Hadir Peserta - " . $kegiatan->nama_kegiatan;
        return view('kegiatan.cetak_presensi', compact('kegiatan', 'title'));
    }

    public function cetakSertifikat(Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        if ((int)$peserta->kegiatan_id !== (int)$kegiatan->id) {
            abort(404);
        }
        if ($peserta->status_kehadiran !== 'hadir_lengkap') {
            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('error', 'Sertifikat belum dapat dicetak karena peserta belum hadir lengkap (masuk & pulang).');
        }
        if ($kegiatan->jenis_kegiatan === 'berbayar' && $peserta->status_pembayaran !== 'lunas') {
            return redirect()->route('kegiatan.show', $kegiatan)
                ->with('error', 'Sertifikat belum dapat dicetak karena status pembayaran peserta belum Lunas.');
        }

        return view('kegiatan.sertifikat', compact('kegiatan', 'peserta'));
    }

    public function togglePresensiOnline(Kegiatan $kegiatan)
    {
        $kegiatan->presensi_online_aktif = !$kegiatan->presensi_online_aktif;
        $kegiatan->save();

        $status = $kegiatan->presensi_online_aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('kegiatan.show', $kegiatan)->with('success', 'Link presensi mandiri online berhasil ' . $status . '.');
    }

    protected function syncToKegiatanDosen(Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        if ($peserta->kategori === 'Dosen' && $peserta->status_kehadiran === 'hadir_lengkap') {
            $ts = \App\Models\Ts::where('label_ts', 'TS')->first() ?? \App\Models\Ts::first();
            $tsId = $ts ? $ts->id : 1;
            
            $dosen = \App\Models\Dosen::where('nidn', $peserta->identifier)->orWhere('nip', $peserta->identifier)->orWhere('kode_dosen', $peserta->identifier)->first();
            
            if ($dosen) {
                KegiatanDosen::updateOrCreate(
                    [
                        'kode_dosen' => $dosen->kode_dosen,
                        'kegiatan_prodi_id' => $kegiatan->id,
                    ],
                    [
                        'nama_dosen' => $peserta->nama,
                        'nama_kegiatan' => $kegiatan->nama_kegiatan,
                        'tahun' => $kegiatan->tanggal ? date('Y', strtotime($kegiatan->tanggal)) : date('Y'),
                        'ts_id' => $tsId,
                        'penyelenggara' => 'Internal Prodi SIA',
                        'jenis' => 'Internal',
                    ]
                );
            }
        }
    }

    protected function syncToKegiatanTendik(Kegiatan $kegiatan, PesertaKegiatan $peserta)
    {
        if ($peserta->kategori === 'Tendik' && $peserta->status_kehadiran === 'hadir_lengkap') {
            $ts = \App\Models\Ts::where('label_ts', 'TS')->first() ?? \App\Models\Ts::first();
            $tsId = $ts ? $ts->id : 1;
            
            $tendik = \App\Models\Tendik::where('nip_nik', $peserta->identifier)->first();
            
            if ($tendik) {
                \App\Models\KegiatanTendik::updateOrCreate(
                    [
                        'nip_nik' => $tendik->nip_nik,
                        'kegiatan_prodi_id' => $kegiatan->id,
                    ],
                    [
                        'nama_tendik' => $peserta->nama,
                        'nama_kegiatan' => $kegiatan->nama_kegiatan,
                        'tahun' => $kegiatan->tanggal ? date('Y', strtotime($kegiatan->tanggal)) : date('Y'),
                        'ts_id' => $tsId,
                        'penyelenggara' => 'Internal Prodi SIA',
                        'jenis' => 'Internal',
                    ]
                );
            }
        }
    }
}
