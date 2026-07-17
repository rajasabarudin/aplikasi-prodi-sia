import fitz
import glob
import re
import os

pdf_files = glob.glob("rps/*.pdf")

php_script = """<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

use App\\Models\\RpsBahanKajian;
use App\\Models\\Matakuliah;
use Illuminate\\Support\\Facades\\DB;

DB::transaction(function () {
    RpsBahanKajian::truncate();
    
"""

for pdf in pdf_files:
    filename = os.path.basename(pdf)
    parts = filename.split('_')
    # RPS_101_1130.pdf -> 101, or RPS_0002_1130.pdf -> 0002 -> which could map to MK02
    kode = parts[1] 
    
    doc = fitz.open(pdf)
    text = ""
    for page in doc:
        text += page.get_text()
    
    # Try to find "Bahan Kajian (Materi Ajar)" and "Daftar Referensi"
    start_match = re.search(r'Bahan Kajian \(Materi Ajar\)', text, re.IGNORECASE)
    end_match = re.search(r'Daftar Referensi', text, re.IGNORECASE)
    
    if start_match and end_match:
        section = text[start_match.end():end_match.start()]
        # Extract lines starting with number
        lines = section.split('\n')
        
        bahan_kajians = []
        current_bahan = ""
        for line in lines:
            line = line.strip()
            if not line:
                continue
            # If line starts with a number like "1. " or "1) "
            if re.match(r'^\d+[\.\)]', line):
                if current_bahan:
                    bahan_kajians.append(current_bahan)
                current_bahan = re.sub(r'^\d+[\.\)]\s*', '', line)
            else:
                if current_bahan:
                    current_bahan += " " + line
        if current_bahan:
            bahan_kajians.append(current_bahan)
            
        # Add to php script
        php_script += f"\n    // {filename}\n"
        php_script += f"    $kode_raw = '{kode}';\n"
        php_script += f"    $mk = Matakuliah::where('kode_matakuliah', $kode_raw)->orWhere('kode_matakuliah', 'like', '%' . ltrim($kode_raw, '0'))->orWhere('kode_matakuliah', 'like', 'MK' . $kode_raw)->first();\n"
        php_script += f"    if ($mk) {{\n"
        
        for idx, bahan in enumerate(bahan_kajians):
            bahan_clean = bahan.replace("'", "\\'")
            php_script += f"        RpsBahanKajian::create(['kode_matakuliah' => $mk->kode_matakuliah, 'urutan' => {idx + 1}, 'topik' => '{bahan_clean}', 'sub_topik' => '']);\n"
            
        php_script += f"    }}\n"

php_script += "    echo 'Selesai import bahan kajian.';\n});\n"

with open("import_bahan_kajian.php", "w", encoding="utf-8") as f:
    f.write(php_script)

print("PHP script generated!")
