<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use Illuminate\Http\Request;

class HakAksesController extends Controller
{
    // Define the list of menus that can be configured
    private $menus = [
        'dosen' => 'Data Dosen',
        'mahasiswa' => 'Data Mahasiswa',
        'sertifikasi-mahasiswa' => 'Data Sertifikasi Mahasiswa',
        'kelas' => 'Data Kelas',
        'matakuliah' => 'Data Matakuliah',
        'ts' => 'Data Akademik',
        'rekognisi-dosen' => 'Data Rekognisi Dosen',
        'prestasi-dosen' => 'Data Prestasi Dosen',
        'penelitian-dosen' => 'Data Penelitian Dosen',
        'hibah-penelitian' => 'Data Hibah Penelitian',
        'pmb' => 'Data Jumlah PMB',
        'kerjasama' => 'Data Kerjasama',
        'pks-ia' => 'Data PKS dan IA',
        'praktisi' => 'Data Praktisi',
        'kegiatan' => 'Manajemen Kegiatan',
        'rps-cpl' => 'Data CPL (RPS)',
        'rps-cpmk' => 'Data CPMK (RPS)',
        'rps-bahan-kajian' => 'Bahan Kajian MK (RPS)',
        'rps-referensi' => 'Referensi MK (RPS)',
        'penyusunan-rps' => 'Penyusunan RPS',
    ];

    private $roles = ['jendral', 'lecture', 'mhs'];

    public function index()
    {
        // Get all active permissions
        $permissions = HakAkses::all();

        // Build a lookup structure: $activePermissions[$level][$menu] = true
        $activePermissions = [];
        foreach ($permissions as $p) {
            $activePermissions[$p->level][$p->menu] = true;
        }

        $menus = $this->menus;
        $roles = $this->roles;

        return view('hak_akses.index', compact('menus', 'roles', 'activePermissions'));
    }

    public function store(Request $request)
    {
        // Delete all existing permissions
        HakAkses::query()->delete();

        $submitted = $request->input('permissions', []);

        foreach ($submitted as $level => $menusData) {
            if (in_array($level, $this->roles)) {
                foreach ($menusData as $menu => $value) {
                    if (array_key_exists($menu, $this->menus) && $value == '1') {
                        HakAkses::create([
                            'level' => $level,
                            'menu' => $menu,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('hak-akses.index')
            ->with('success', 'Pengaturan hak akses berhasil diperbarui.');
    }
}
