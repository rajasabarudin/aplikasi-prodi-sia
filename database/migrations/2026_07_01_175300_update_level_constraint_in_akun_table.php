<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Rename table
        DB::statement('ALTER TABLE akun RENAME TO akun_old');

        // 2. Create new table without CHECK constraint on level
        DB::statement('CREATE TABLE "akun" (
            "id" integer not null primary key autoincrement, 
            "username" varchar not null, 
            "email" varchar not null, 
            "password" varchar not null, 
            "level" varchar not null, 
            "remember_token" varchar, 
            "created_at" datetime, 
            "updated_at" datetime, 
            "foto" varchar
        )');

        // 3. Copy data from old table to new table
        DB::statement('INSERT INTO "akun" ("id", "username", "email", "password", "level", "remember_token", "created_at", "updated_at", "foto") 
            SELECT "id", "username", "email", "password", "level", "remember_token", "created_at", "updated_at", "foto" FROM "akun_old"');

        // 4. Drop the old table
        DB::statement('DROP TABLE "akun_old"');
    }

    public function down()
    {
        // Revert back (we can keep the table without CHECK constraint since it is backward compatible)
    }
};
