<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = App\Models\HakAkses::where('level', 'admin')->first();
if($admin) {
    $menus = json_decode($admin->menu, true) ?? [];
    if(!in_array('kegiatan-tendik', $menus)) {
        $menus[] = 'kegiatan-tendik';
        $admin->menu = json_encode($menus);
        $admin->save();
        echo "Added kegiatan-tendik to admin";
    } else {
        echo "kegiatan-tendik already exists";
    }
}
