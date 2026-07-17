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
        // 1. Read existing data
        $existingData = \Illuminate\Support\Facades\DB::table('sertifikasi_mahasiswas')->get()->map(function ($row) {
            return (array) $row;
        })->toArray();

        // 2. Drop the old table
        Schema::dropIfExists('sertifikasi_mahasiswas');

        // 3. Re-create the table without the foreign key
        Schema::create('sertifikasi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_mhs');
            $table->string('skema_serkom');
            $table->string('link_dokumen')->nullable();
            $table->timestamps();
        });

        // 4. Restore the data
        if (!empty($existingData)) {
            \Illuminate\Support\Facades\DB::table('sertifikasi_mahasiswas')->insert($existingData);
        }
    }

    public function down()
    {
        $existingData = \Illuminate\Support\Facades\DB::table('sertifikasi_mahasiswas')->get()->map(function ($row) {
            return (array) $row;
        })->toArray();

        Schema::dropIfExists('sertifikasi_mahasiswas');

        Schema::create('sertifikasi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->string('nama_mhs');
            $table->string('skema_serkom');
            $table->string('link_dokumen')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });

        if (!empty($existingData)) {
            \Illuminate\Support\Facades\DB::table('sertifikasi_mahasiswas')->insert($existingData);
        }
    }
};
