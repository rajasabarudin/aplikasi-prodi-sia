import re

with open('app/Http/Controllers/MatakuliahController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add calculations
stats_code = """        $totalReguler = Matakuliah::whereIn('sistem_pembelajaran', ['Reguler', 'Elearning'])->orWhereNull('sistem_pembelajaran')->count();
        $persentaseReguler = $totalMatakuliah > 0 ? round(($totalReguler / $totalMatakuliah) * 100, 1) : 0;
        
        $totalPenelitian = Matakuliah::whereHas('rps.penelitians')->count();
        $persentasePenelitian = $totalMatakuliah > 0 ? round(($totalPenelitian / $totalMatakuliah) * 100, 1) : 0;
        
        $totalPkm = Matakuliah::whereHas('rps.pkms')->count();
        $persentasePkm = $totalMatakuliah > 0 ? round(($totalPkm / $totalMatakuliah) * 100, 1) : 0;"""

content = content.replace(
    "$totalReguler = Matakuliah::whereIn('sistem_pembelajaran', ['Reguler', 'Elearning'])->orWhereNull('sistem_pembelajaran')->count();\n        $persentaseReguler = $totalMatakuliah > 0 ? round(($totalReguler / $totalMatakuliah) * 100, 1) : 0;",
    stats_code
)

# Update compact
content = content.replace(
    "compact('matakuliahs', 'search', 'perPage', 'totalMatakuliah', 'totalSks', 'matakuliahByJenis', 'allMatakuliah', 'matakuliahBySemester', 'mkPenciri', 'totalPbl', 'persentasePbl', 'totalReguler', 'persentaseReguler')",
    "compact('matakuliahs', 'search', 'perPage', 'totalMatakuliah', 'totalSks', 'matakuliahByJenis', 'allMatakuliah', 'matakuliahBySemester', 'mkPenciri', 'totalPbl', 'persentasePbl', 'totalReguler', 'persentaseReguler', 'totalPenelitian', 'persentasePenelitian', 'totalPkm', 'persentasePkm')"
)

with open('app/Http/Controllers/MatakuliahController.php', 'w', encoding='utf-8') as f:
    f.write(content)
