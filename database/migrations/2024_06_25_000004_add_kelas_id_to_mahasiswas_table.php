<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $cols = DB::select("PRAGMA table_info('mahasiswas')");
        $colNames = array_column($cols, 'name');
        $hasKelasId = in_array('kelas_id', $colNames);

        if (!$hasKelasId) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->unsignedBigInteger('kelas_id')->nullable()->after('kelas');
            });

            $distinctKelas = DB::table('mahasiswas')
                ->select('kelas')
                ->distinct()
                ->whereNotNull('kelas')
                ->get();

            foreach ($distinctKelas as $item) {
                $kelasId = DB::table('kelas')->insertGetId([
                    'nama_kelas' => $item->kelas,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('mahasiswas')
                    ->where('kelas', $item->kelas)
                    ->update(['kelas_id' => $kelasId]);
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('mahasiswas', 'kelas_id')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
            });
        }
    }
};
