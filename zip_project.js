/**
 * Script untuk membuat ZIP file project Laravel siap deploy
 * Jalankan: node zip_project.js
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectDir = __dirname;
const outputZip = path.join(projectDir, 'deploy_prodisia.zip');

// File & folder yang TIDAK perlu diupload ke hosting
const excludes = [
    '.git',
    '.gitignore',
    '.gitattributes',
    'node_modules',
    'deploy_prodisia.zip',
    'database_mysql.sql',
    'sqlite_to_mysql_export.php',
    'zip_project.js',
    '.env.production',
    // File-file utility/script tidak perlu di hosting
    'add_cases.php',
    'check_base.php',
    'check_c_details.py',
    'check_template.php',
    'check_ts.php',
    'clean_pdf_recap.php',
    'clean_ppepp.php',
    'dump_pdf.py',
    'extract_all.py',
    'extract_bahan.py',
    'extract_pertemuan.py',
    'extract_ppepp.php',
    'extract_rtm.py',
    'extract_silabus.py',
    'fix_blade_pdf.php',
    'fix_brace.php',
    'fix_dana.php',
    'fix_dup.php',
    'fix_ipk_blade.php',
    'fix_ipk_mhs.php',
    'fix_ipk_order.php',
    'fix_ipk_pdf.php',
    'fix_modals.php',
    'fix_pdf_alias.php',
    'fix_pdf_recap.php',
    'fix_pdf_return.php',
    'fix_ppepp.php',
    'fix_syntax.php',
    'fix_syntax_final.php',
    'generate_template.php',
    'get_tables.php',
    'hard_rewrite.php',
    'import_bahan_kajian.php',
    'import_cpmk.php',
    'inject_c1.php',
    'inject_c2.php',
    'inject_c3.php',
    'inject_c4.php',
    'inject_c5.php',
    'inject_c6.php',
    'inject_dynamic_ppepp.php',
    'inject_quantitative.php',
    'inspect_remaining.php',
    'port_phase3.php',
    'ppepp_tbody_backup.html',
    'rebuild_ppepp_properly.php',
    'refactor_tabs.php',
    'routes.txt',
    'search_pdf.py',
    'table_dump.json',
    'test_dana.php',
    'test_query.php',
    'test_table.py',
    'update_grid.php',
    'update_obe_controller.php',
    'update_obe_forms.php',
    'update_ppepp.php',
    'update_sks.php',
    'pdf_dump.txt',
    'rps.pdf',
    'buku kurikulum.pdf',
    'LED Akreditasi Program Studi Informatika Pontianak.docx',
];

console.log('🔄 Membuat ZIP file untuk deploy...\n');

// Hapus zip lama jika ada
if (fs.existsSync(outputZip)) {
    fs.unlinkSync(outputZip);
    console.log('🗑️  ZIP lama dihapus\n');
}

// Buat exclude pattern untuk PowerShell Compress-Archive
// Kita pakai 7zip atau powershell
const excludeArgs = excludes.map(e => `-xr!"${e}"`).join(' ');

try {
    // Coba pakai 7zip dulu (lebih handal)
    console.log('📦 Membuat ZIP...');
    
    const sevenZipPaths = [
        '"C:\\Program Files\\7-Zip\\7z.exe"',
        '"C:\\Program Files (x86)\\7-Zip\\7z.exe"',
    ];
    
    let sevenZipFound = false;
    for (const zipPath of sevenZipPaths) {
        try {
            execSync(`${zipPath} --help`, { stdio: 'ignore' });
            sevenZipFound = true;
            
            const excludeStr = excludes.map(e => `-xr!"${e}"`).join(' ');
            const cmd = `${zipPath} a -tzip "${outputZip}" * ${excludeStr}`;
            console.log('Menggunakan 7-Zip...');
            execSync(cmd, { cwd: projectDir, stdio: 'inherit' });
            break;
        } catch (e) {
            // 7zip tidak ada di path ini
        }
    }
    
    if (!sevenZipFound) {
        // Gunakan PowerShell sebagai fallback
        console.log('Menggunakan PowerShell Compress-Archive...');
        
        // Buat list file yang akan di-include
        const allItems = fs.readdirSync(projectDir);
        const included = allItems.filter(item => !excludes.includes(item));
        
        console.log('\n📋 File/folder yang akan di-zip:');
        included.forEach(i => console.log(`   ✅ ${i}`));
        console.log('');
        
        const pathList = included.map(i => `"${path.join(projectDir, i)}"`).join(',');
        const psCmd = `Compress-Archive -Path ${pathList} -DestinationPath "${outputZip}" -Force`;
        
        execSync(`powershell -Command "${psCmd.replace(/"/g, '\\"')}"`, { 
            cwd: projectDir,
            stdio: 'inherit'
        });
    }
    
    const stats = fs.statSync(outputZip);
    const sizeMB = (stats.size / 1024 / 1024).toFixed(2);
    
    console.log(`\n✅ ZIP berhasil dibuat!`);
    console.log(`📁 File: ${outputZip}`);
    console.log(`📊 Ukuran: ${sizeMB} MB`);
    console.log(`\n📌 Langkah selanjutnya:`);
    console.log(`   1. Upload 'deploy_prodisia.zip' ke File Manager cPanel`);
    console.log(`   2. Extract di sana`);
    console.log(`   3. Ikuti panduan konfigurasi hosting`);
    
} catch (error) {
    console.error('❌ Error:', error.message);
    console.log('\n💡 Coba zip manual:');
    console.log('   Buka Windows Explorer → klik kanan folder project → Send to → Compressed folder');
    console.log('   (kecualikan folder: .git, node_modules)');
}
