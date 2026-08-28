import re

with open('app/Http/Controllers/PenelitianDosenController.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("htmlspecialchars($judulRaw, ENT_XML1, 'UTF-8')", "$judulRaw")
content = content.replace("htmlspecialchars($penelitian_dosen->nama_dosen ?? ($dosen ? $dosen->nama_dosen : 'Nama Ketua Belum Diisi'), ENT_XML1, 'UTF-8')", "$penelitian_dosen->nama_dosen ?? ($dosen ? $dosen->nama_dosen : 'Nama Ketua Belum Diisi')")
content = content.replace("htmlspecialchars($dosen ? $dosen->nidn : '-', ENT_XML1, 'UTF-8')", "$dosen ? $dosen->nidn : '-'")
content = content.replace("htmlspecialchars($dosen ? $dosen->jfa : 'Lektor', ENT_XML1, 'UTF-8')", "$dosen ? $dosen->jfa : 'Lektor'")
content = content.replace("htmlspecialchars($dosen ? $dosen->homebase_dosen : 'Sistem Informasi (S1)', ENT_XML1, 'UTF-8')", "$dosen ? $dosen->homebase_dosen : 'Sistem Informasi (S1)'")
content = content.replace("htmlspecialchars($penelitian_dosen->nama_mahasiswa ?? '-', ENT_XML1, 'UTF-8')", "$penelitian_dosen->nama_mahasiswa ?? '-'")
content = content.replace("htmlspecialchars($penelitian_dosen->nim_mhs ?? '-', ENT_XML1, 'UTF-8')", "$penelitian_dosen->nim_mhs ?? '-'")
content = content.replace("htmlspecialchars($penelitian_dosen->anggota_mitra ?? '-', ENT_XML1, 'UTF-8')", "$penelitian_dosen->anggota_mitra ?? '-'")
content = content.replace("htmlspecialchars(number_format($biaya, 0, ',', '.'), ENT_XML1, 'UTF-8')", "number_format($biaya, 0, ',', '.')")

with open('app/Http/Controllers/PenelitianDosenController.php', 'w', encoding='utf-8') as f:
    f.write(content)
