<?php
$index = file_get_contents('D:\07 aplikasi\aplikasi-prodi-sia\resources\views\obe\index.blade.php');
$lines = explode("\n", $index);
$top = array_slice($lines, 0, 505);
$topStr = implode("\n", $top);

$backup = file_get_contents('D:\07 aplikasi\aplikasi-prodi-sia\resources\views\obe\index_backup.blade.php');
$backupLines = explode("\n", $backup);
$bottom = array_slice($backupLines, 1102);
$bottomStr = implode("\n", $bottom);

file_put_contents('D:\07 aplikasi\aplikasi-prodi-sia\resources\views\obe\index.blade.php', $topStr . "\n" . $bottomStr);
