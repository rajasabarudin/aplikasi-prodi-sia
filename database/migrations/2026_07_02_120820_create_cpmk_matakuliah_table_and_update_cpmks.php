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
        // 1. Ambil semua data cpmk yang ada saat ini sebelum kolomnya didrop
        $existingCpmks = \Illuminate\Support\Facades\DB::table('cpmks')
            ->whereNotNull('kode_matakuliah')
            ->get(['id', 'kode_matakuliah']);

        // 2. Buat tabel pivot
        Schema::create('cpmk_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cpmk_id');
            $table->string('kode_matakuliah');
            $table->timestamps();

            $table->foreign('cpmk_id')->references('id')->on('cpmks')->onDelete('cascade');
            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliahs')->onDelete('cascade');
        });

        // 3. Pindahkan data lama ke tabel pivot
        foreach ($existingCpmks as $cpmk) {
            \Illuminate\Support\Facades\DB::table('cpmk_matakuliah')->insert([
                'cpmk_id' => $cpmk->id,
                'kode_matakuliah' => $cpmk->kode_matakuliah,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpmks', function (Blueprint $table) {
            $table->string('kode_matakuliah')->nullable();
            $table->foreign('kode_matakuliah')->references('kode_matakuliah')->on('matakuliahs')->onDelete('cascade');
        });

        Schema::dropIfExists('cpmk_matakuliah');
    }
};
