<?php
$db = new PDO('sqlite:database/database.sqlite');
$countHibah = $db->query("SELECT COUNT(*) FROM hibah_penelitians")->fetchColumn();
echo "Hibah Penelitian: " . $countHibah . "\n";
