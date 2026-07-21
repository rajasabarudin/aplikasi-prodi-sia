<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Use raw SQL to alter columns because SQLite ignores length, but MySQL enforces it
        // We catch exception to ignore errors if it's sqlite
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE pkm_dosens MODIFY kode_dosen TEXT, MODIFY nama_dosen TEXT, MODIFY nim_mhs TEXT, MODIFY nama_mahasiswa TEXT;');
        } catch (\Exception $e) {}
        
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE penelitian_dosens MODIFY kode_dosen TEXT, MODIFY nama_dosen TEXT, MODIFY nim_mhs TEXT, MODIFY nama_mahasiswa TEXT;');
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No down migration needed for this hotfix
    }
};
