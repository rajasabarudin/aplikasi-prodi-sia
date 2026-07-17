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
        // SQLite requires recreating the table to alter column to nullable.
        // We will create a temp table, copy data, drop original, and rename temp.
        DB::statement('CREATE TABLE cpmks_tmp (
            id integer not null primary key autoincrement, 
            kode_cpmk varchar not null, 
            deskripsi_cpmk varchar not null, 
            cpl_id integer, 
            kode_matakuliah varchar null, 
            created_at datetime, 
            updated_at datetime, 
            foreign key(cpl_id) references cpls(id) on delete cascade
        )');
        
        DB::statement('INSERT INTO cpmks_tmp (id, kode_cpmk, deskripsi_cpmk, cpl_id, kode_matakuliah, created_at, updated_at) 
                       SELECT id, kode_cpmk, deskripsi_cpmk, cpl_id, kode_matakuliah, created_at, updated_at FROM cpmks');
                       
        DB::statement('DROP TABLE cpmks');
        DB::statement('ALTER TABLE cpmks_tmp RENAME TO cpmks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpmks', function (Blueprint $table) {
            //
        });
    }
};
