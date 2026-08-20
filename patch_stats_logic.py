import re

with open('app/Http/Controllers/MatakuliahController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Update totalPbl
content = content.replace(
    "$totalPbl = Matakuliah::whereIn('sistem_pembelajaran', ['PBL', 'PBL/Elearning', 'Elearning'])->count();",
    "$totalPbl = Matakuliah::whereIn('sistem_pembelajaran', ['PBL', 'PBL/Elearning'])->count();"
)

# Update totalReguler
content = content.replace(
    "$totalReguler = Matakuliah::where('sistem_pembelajaran', 'Reguler')->count();",
    "$totalReguler = Matakuliah::whereIn('sistem_pembelajaran', ['Reguler', 'Elearning'])->orWhereNull('sistem_pembelajaran')->count();"
)

with open('app/Http/Controllers/MatakuliahController.php', 'w', encoding='utf-8') as f:
    f.write(content)
