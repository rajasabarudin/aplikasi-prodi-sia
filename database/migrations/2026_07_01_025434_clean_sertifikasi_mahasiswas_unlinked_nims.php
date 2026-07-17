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
        \DB::table('sertifikasi_mahasiswas')
            ->whereNotIn('nim', function($query) {
                $query->select('nim')->from('mahasiswas');
            })
            ->delete();
    }

    public function down()
    {
        // No reverse operation since we deleted orphan data
    }
};
