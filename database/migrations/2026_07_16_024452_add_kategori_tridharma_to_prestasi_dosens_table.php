<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestasi_dosens', function (Blueprint $table) {
            $table->enum('kategori_tridharma', ['penelitian', 'pengabdian_masyarakat', 'pendidikan'])
                  ->nullable()
                  ->after('link_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('prestasi_dosens', function (Blueprint $table) {
            $table->dropColumn('kategori_tridharma');
        });
    }
};
