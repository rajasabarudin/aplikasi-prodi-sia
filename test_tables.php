<?php
$db = new PDO('sqlite:database/database.sqlite');
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
foreach ($result as $row) {
    if (strpos($row['name'], 'penelitian') !== false || strpos($row['name'], 'pkm') !== false || strpos($row['name'], 'jurnal') !== false) {
        echo $row['name'] . "\n";
    }
}
