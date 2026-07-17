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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->string('jenis_kegiatan')->default('gratis'); // gratis, berbayar
            $table->integer('harga')->default(0);
            $table->text('rekening_info')->nullable();
        });

        Schema::table('peserta_kegiatans', function (Blueprint $table) {
            $table->string('status_pembayaran')->default('belum_bayar'); // lunas, menunggu_verifikasi, belum_bayar
            $table->string('bukti_pembayaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn(['jenis_kegiatan', 'harga', 'rekening_info']);
        });

        Schema::table('peserta_kegiatans', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'bukti_pembayaran']);
        });
    }
};
