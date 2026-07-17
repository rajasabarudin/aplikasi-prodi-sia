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
    public function up(): void
    {
        Schema::table('rps_pertemuans', function (Blueprint $table) {
            $table->text('waktu_pembelajaran')->nullable()->after('metode_pembelajaran');
            $table->text('kriteria_penilaian')->nullable()->after('pengalaman_belajar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rps_pertemuans', function (Blueprint $table) {
            $table->dropColumn(['waktu_pembelajaran', 'kriteria_penilaian']);
        });
    }
};
