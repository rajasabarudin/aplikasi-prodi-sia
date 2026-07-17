<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$levels = \App\Models\HakAkses::where('menu', 'tendik')->pluck('level');
foreach($levels as $level) {
    \App\Models\HakAkses::firstOrCreate(['level' => $level, 'menu' => 'kegiatan-tendik']);
}
echo "Done";
