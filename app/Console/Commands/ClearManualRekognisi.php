<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RekognisiDosen;

class ClearManualRekognisi extends Command
{
    protected $signature = 'rekognisi:clear-manual';
    protected $description = 'Menghapus data Rekognisi Dosen yang diinputkan secara manual.';

    public function handle()
    {
        $this->info('Mencari data Rekognisi yang diinputkan secara manual...');
        
        // Data manual ditandai dengan is_keanggotaan = true
        // atau tidak memiliki relasi id ke sumber-sumber lain
        $count = RekognisiDosen::where('is_keanggotaan', true)
            ->orWhere(function ($query) {
                $query->whereNull('penelitian_dosen_id')
                      ->whereNull('hibah_penelitian_id')
                      ->whereNull('hki_id')
                      ->whereNull('prestasi_dosen_id');
            })
            ->delete();

        $this->info("Berhasil! {$count} data Rekognisi manual telah dihapus dari database.");
        
        return Command::SUCCESS;
    }
}
