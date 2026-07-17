<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed default accounts
        \App\Models\Akun::updateOrCreate(
            ['username' => 'king'],
            [
                'email' => 'king@sia.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'level' => 'king',
            ]
        );

        \App\Models\Akun::updateOrCreate(
            ['username' => 'jendral'],
            [
                'email' => 'jendral@sia.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'level' => 'jendral',
            ]
        );

        \App\Models\Akun::updateOrCreate(
            ['username' => 'lecture'],
            [
                'email' => 'lecture@sia.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'level' => 'lecture',
            ]
        );

        // Seed default PMB data
        \App\Models\Pmb::updateOrCreate(['tahun' => 2020], ['jumlah_pmb' => 120]);
        \App\Models\Pmb::updateOrCreate(['tahun' => 2021], ['jumlah_pmb' => 145]);
        \App\Models\Pmb::updateOrCreate(['tahun' => 2022], ['jumlah_pmb' => 180]);
        \App\Models\Pmb::updateOrCreate(['tahun' => 2023], ['jumlah_pmb' => 215]);
        \App\Models\Pmb::updateOrCreate(['tahun' => 2024], ['jumlah_pmb' => 250]);

        // Seed default Kerjasama data
        \App\Models\Kerjasama::updateOrCreate(
            ['nomor_mou_ubsi' => '045/MOU/UBSI-PT/III/2023'],
            [
                'tahun_mou' => 2023,
                'nomor_mou_mitra' => '12/STN-MOU/2023',
                'nama_mitra' => 'PT Solusi Teknologi Nusantara',
                'ketua_mewakili' => 'Budi Santoso, M.T.',
                'no_wa_mitra' => '081234567890',
            ]
        );
        \App\Models\Kerjasama::updateOrCreate(
            ['nomor_mou_ubsi' => '012/MOU/UBSI-UDI/I/2024'],
            [
                'tahun_mou' => 2024,
                'nomor_mou_mitra' => '78/UDI-MOU/2024',
                'nama_mitra' => 'Universitas Digital Indonesia',
                'ketua_mewakili' => 'Prof. Dr. Ir. Ahmad Dahlan',
                'no_wa_mitra' => '085712345678',
            ]
        );
        \App\Models\Kerjasama::updateOrCreate(
            ['nomor_mou_ubsi' => '089/MOU/UBSI-KMU/V/2024'],
            [
                'tahun_mou' => 2024,
                'nomor_mou_mitra' => 'CV-KMU/MOU-05/2024',
                'nama_mitra' => 'CV Kreatif Media Utama',
                'ketua_mewakili' => 'Eko Prasetyo, S.Kom.',
                'no_wa_mitra' => '08991234567',
            ]
        );

        // Seed default PksIa data
        \App\Models\PksIa::updateOrCreate(
            ['no_pks_ubsi' => '102/PKS/UBSI-PT/III/2024'],
            [
                'nama_mitra' => 'PT Cyber Security Nusantara',
                'tgl_pks' => '2024-03-15',
                'no_pks_mitra' => '56/CSN-PKS/2024',
                'tema_pks' => 'Pelatihan Keamanan Siber bagi Mahasiswa',
                'kategori' => 'Pendidikan',
                'level_pks' => 'Nasional',
            ]
        );
        \App\Models\PksIa::updateOrCreate(
            ['no_pks_ubsi' => '215/PKS/UBSI-KM/V/2024'],
            [
                'nama_mitra' => 'Kelurahan Margajaya',
                'tgl_pks' => '2024-05-10',
                'no_pks_mitra' => '89/KM-PKS/2024',
                'tema_pks' => 'Edukasi Literasi Digital Masyarakat',
                'kategori' => 'PKM',
                'level_pks' => 'Lokal/Wilayah',
            ]
        );
    }
}
