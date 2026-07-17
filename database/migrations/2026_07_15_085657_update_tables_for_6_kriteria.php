<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Update Tabel Dosens
        Schema::table('dosens', function (Blueprint $table) {
            $table->boolean('is_dosen_tetap')->default(true)->after('homebase_dosen')->comment('True jika Dosen Tetap (DT), False jika Tidak Tetap (DTT)');
            $table->string('status_ikatan_kerja')->nullable()->after('is_dosen_tetap')->comment('Misal: Dosen Tetap PT, Dosen Tetap Yayasan');
            $table->string('sertifikat_pendidik')->nullable()->after('status_ikatan_kerja')->comment('Nomor atau Status Serdik');
            $table->string('bidang_keahlian')->nullable()->after('sertifikat_pendidik')->comment('Sesuai dengan PS atau tidak');
            $table->decimal('ewmp', 5, 2)->default(0)->after('bidang_keahlian')->comment('Ekuivalensi Waktu Mengajar Penuh');
        });

        // 2. Update Tabel Penelitian
        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->string('sumber_dana')->nullable()->after('biaya')->comment('Mandiri, Perguruan Tinggi, Nasional, Internasional');
        });

        // 3. Update Tabel PkM
        Schema::table('pkm_dosens', function (Blueprint $table) {
            $table->string('sumber_dana')->nullable()->after('biaya')->comment('Mandiri, Perguruan Tinggi, Nasional, Internasional');
        });

        // 4. Create Tabel Seleksi Mahasiswa (Untuk LKPS Kriteria C2)
        Schema::create('lkps_seleksi_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ts_id')->constrained('ts')->onDelete('cascade');
            $table->integer('daya_tampung')->default(0);
            $table->integer('pendaftar')->default(0);
            $table->integer('lulus_seleksi')->default(0);
            $table->integer('mendaftar_ulang_reguler')->default(0);
            $table->integer('mendaftar_ulang_transfer')->default(0);
            $table->integer('mahasiswa_aktif_reguler')->default(0);
            $table->integer('mahasiswa_aktif_transfer')->default(0);
            $table->timestamps();
        });

        // 5. Create Tabel Narasi LED (Kriteria A, B, C.6, D)
        Schema::create('led_narratives', function (Blueprint $table) {
            $table->id();
            $table->string('kriteria_kode')->unique()->comment('Misal: A, B, C6, D');
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('led_narratives');
        Schema::dropIfExists('lkps_seleksi_mahasiswa');

        Schema::table('pkm_dosens', function (Blueprint $table) {
            $table->dropColumn('sumber_dana');
        });

        Schema::table('penelitian_dosens', function (Blueprint $table) {
            $table->dropColumn('sumber_dana');
        });

        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn([
                'is_dosen_tetap',
                'status_ikatan_kerja',
                'sertifikat_pendidik',
                'bidang_keahlian',
                'ewmp'
            ]);
        });
    }
};
