<?php

/**
 * SQLite to MySQL Export Script
 * Jalankan: php sqlite_to_mysql_export.php
 * Output: database_mysql.sql (siap di-import ke MySQL hosting)
 */

$sqlitePath = __DIR__ . '/database/database.sqlite';
$outputFile = __DIR__ . '/database_mysql.sql';

if (!file_exists($sqlitePath)) {
    die("❌ File SQLite tidak ditemukan: $sqlitePath\n");
}

echo "🔄 Membaca database SQLite...\n";

try {
    $pdo = new PDO('sqlite:' . $sqlitePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("❌ Gagal membuka SQLite: " . $e->getMessage() . "\n");
}

$sql = [];
$sql[] = "-- ============================================================";
$sql[] = "-- Export SQLite -> MySQL";
$sql[] = "-- Dibuat: " . date('Y-m-d H:i:s');
$sql[] = "-- Project: Aplikasi Prodi SIA";
$sql[] = "-- ============================================================";
$sql[] = "";
$sql[] = "SET FOREIGN_KEY_CHECKS = 0;";
$sql[] = "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';";
$sql[] = "SET NAMES utf8mb4;";
$sql[] = "";

// Ambil semua tabel (kecuali tabel internal SQLite)
$tablesStmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

echo "📋 Ditemukan " . count($tables) . " tabel:\n";
foreach ($tables as $t) {
    echo "   - $t\n";
}
echo "\n";

// Urutan tabel berdasarkan dependency (parent dulu)
$orderedTables = [];
$remaining = $tables;

// Tabel tanpa foreign key dependency diproses dulu
$priorityTables = [
    'migrations',
    'users',
    'password_resets',
    'failed_jobs',
    'personal_access_tokens',
    'ts',
    'kelas',
    'dosens',
    'mahasiswas',
    'akuns',
    'hak_akses',
    'matakuliahs',
    'cpls',
    'cpmks',
];

foreach ($priorityTables as $pt) {
    if (in_array($pt, $remaining)) {
        $orderedTables[] = $pt;
        $remaining = array_diff($remaining, [$pt]);
    }
}

// Tambahkan sisa tabel
foreach ($remaining as $t) {
    $orderedTables[] = $t;
}

foreach ($orderedTables as $table) {
    echo "⚙️  Memproses tabel: $table\n";

    // Ambil schema tabel dari SQLite
    $schemaStmt = $pdo->query("PRAGMA table_info(`$table`)");
    $columns = $schemaStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($columns)) {
        echo "   ⚠️  Tabel kosong/tidak ada kolom, skip.\n";
        continue;
    }

    $sql[] = "-- ============================================================";
    $sql[] = "-- Tabel: `$table`";
    $sql[] = "-- ============================================================";
    $sql[] = "DROP TABLE IF EXISTS `$table`;";

    // Buat CREATE TABLE untuk MySQL
    $colDefs = [];
    $primaryKeys = [];

    foreach ($columns as $col) {
        $colName = $col['name'];
        $colType = strtoupper($col['type']);
        $notNull = $col['notnull'] ? 'NOT NULL' : 'NULL';
        $default = '';
        $autoIncrement = '';

        // Konversi tipe SQLite -> MySQL
        if (strpos($colType, 'INT') !== false || $colType === 'INTEGER') {
            $mysqlType = 'BIGINT';
        } elseif (strpos($colType, 'CHAR') !== false || strpos($colType, 'CLOB') !== false || strpos($colType, 'TEXT') !== false || $colType === '') {
            if (strpos($colType, 'TINYTEXT') !== false) {
                $mysqlType = 'TINYTEXT';
            } elseif (strpos($colType, 'MEDIUMTEXT') !== false) {
                $mysqlType = 'MEDIUMTEXT';
            } elseif (strpos($colType, 'LONGTEXT') !== false) {
                $mysqlType = 'LONGTEXT';
            } else {
                // Cek apakah VARCHAR dengan panjang
                if (preg_match('/VARCHAR\((\d+)\)/i', $colType, $m)) {
                    $mysqlType = "VARCHAR({$m[1]})";
                } else {
                    $mysqlType = 'TEXT';
                }
            }
        } elseif (strpos($colType, 'BLOB') !== false || $colType === '') {
            $mysqlType = 'BLOB';
        } elseif (strpos($colType, 'REAL') !== false || strpos($colType, 'FLOA') !== false || strpos($colType, 'DOUB') !== false) {
            $mysqlType = 'DOUBLE';
        } elseif (strpos($colType, 'DECIMAL') !== false || strpos($colType, 'NUMERIC') !== false) {
            if (preg_match('/DECIMAL\((\d+),(\d+)\)/i', $colType, $m)) {
                $mysqlType = "DECIMAL({$m[1]},{$m[2]})";
            } else {
                $mysqlType = 'DECIMAL(15,2)';
            }
        } elseif (strpos($colType, 'BOOLEAN') !== false || strpos($colType, 'BOOL') !== false) {
            $mysqlType = 'TINYINT(1)';
        } elseif (strpos($colType, 'DATETIME') !== false) {
            $mysqlType = 'DATETIME';
        } elseif (strpos($colType, 'DATE') !== false) {
            $mysqlType = 'DATE';
        } elseif (strpos($colType, 'TIME') !== false) {
            $mysqlType = 'TIME';
        } elseif (strpos($colType, 'TIMESTAMP') !== false) {
            $mysqlType = 'TIMESTAMP';
        } elseif (strpos($colType, 'JSON') !== false) {
            $mysqlType = 'LONGTEXT';
        } else {
            $mysqlType = 'TEXT'; // fallback
        }

        // Handle default value
        if ($col['dflt_value'] !== null) {
            $dv = $col['dflt_value'];
            if (strtoupper($dv) === 'NULL') {
                $default = 'DEFAULT NULL';
            } elseif (strtoupper($dv) === 'CURRENT_TIMESTAMP') {
                $default = 'DEFAULT CURRENT_TIMESTAMP';
            } else {
                // Hapus quote jika ada
                $dv = trim($dv, "'\"");
                $default = "DEFAULT '" . addslashes($dv) . "'";
            }
        }

        // Primary key & auto increment
        if ($col['pk'] == 1) {
            $primaryKeys[] = $colName;
            if (strpos($mysqlType, 'INT') !== false) {
                $autoIncrement = 'AUTO_INCREMENT';
                $notNull = 'NOT NULL';
                $default = '';
            }
        }

        $colDef = "  `$colName` $mysqlType";
        if ($notNull) $colDef .= " $notNull";
        if ($autoIncrement) $colDef .= " $autoIncrement";
        if ($default) $colDef .= " $default";

        $colDefs[] = $colDef;
    }

    // Tambah PRIMARY KEY
    if (!empty($primaryKeys)) {
        $colDefs[] = "  PRIMARY KEY (`" . implode("`,`", $primaryKeys) . "`)";
    }

    $sql[] = "CREATE TABLE `$table` (";
    $sql[] = implode(",\n", $colDefs);
    $sql[] = ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $sql[] = "";

    // Export data
    try {
        $dataStmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $colNames = array_keys($rows[0]);
            $colList = implode("`, `", $colNames);

            $sql[] = "LOCK TABLES `$table` WRITE;";
            $sql[] = "INSERT INTO `$table` (`$colList`) VALUES";

            $valueRows = [];
            foreach ($rows as $row) {
                $values = [];
                foreach ($row as $val) {
                    if ($val === null) {
                        $values[] = 'NULL';
                    } elseif (is_numeric($val) && !preg_match('/^0\d/', $val)) {
                        $values[] = $val;
                    } else {
                        $val = str_replace(['\\', "'"], ['\\\\', "\\'"], $val);
                        $val = str_replace(["\r\n", "\r", "\n"], ['\\r\\n', '\\r', '\\n'], $val);
                        $values[] = "'" . $val . "'";
                    }
                }
                $valueRows[] = "(" . implode(", ", $values) . ")";
            }

            // Bagi INSERT per 500 rows agar tidak terlalu besar
            $chunks = array_chunk($valueRows, 500);
            foreach ($chunks as $i => $chunk) {
                if ($i > 0) {
                    // Hapus INSERT terakhir yang sudah ditambah
                    $sql[] = "INSERT INTO `$table` (`$colList`) VALUES";
                }
                $sql[] = implode(",\n", $chunk) . ";";
            }

            $sql[] = "UNLOCK TABLES;";
            echo "   ✅ " . count($rows) . " baris data di-export\n";
        } else {
            echo "   ℹ️  Tabel kosong (0 baris)\n";
        }
    } catch (Exception $e) {
        echo "   ⚠️  Gagal export data: " . $e->getMessage() . "\n";
    }

    $sql[] = "";
}

$sql[] = "";
$sql[] = "SET FOREIGN_KEY_CHECKS = 1;";
$sql[] = "";
$sql[] = "-- ============================================================";
$sql[] = "-- Export selesai: " . date('Y-m-d H:i:s');
$sql[] = "-- ============================================================";

// Simpan ke file
file_put_contents($outputFile, implode("\n", $sql));

echo "\n";
echo "✅ Export selesai!\n";
echo "📁 File output: $outputFile\n";
echo "📊 Ukuran file: " . number_format(filesize($outputFile) / 1024, 2) . " KB\n";
echo "\n";
echo "📌 Langkah selanjutnya:\n";
echo "   1. Buka cPanel Rumahweb\n";
echo "   2. Buat database MySQL baru\n";
echo "   3. Import file 'database_mysql.sql' via phpMyAdmin\n";
echo "   4. Update file .env dengan kredensial MySQL\n";
