<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

foreach (['pmb', 'hibah_penelitians', 'hkis'] as $table) {
    echo "Table: $table\n";
    print_r(Schema::getColumnListing($table));
    $row = DB::table($table)->first();
    if ($row) {
        print_r($row);
    }
}
