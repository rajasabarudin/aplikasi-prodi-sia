import re

with open('app/Http/Controllers/MatakuliahController.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "$totalPbl = Matakuliah::whereIn('sistem_pembelajaran', ['PBL', 'PBL/Elearning'])->count();",
    "$totalPbl = Matakuliah::whereIn('sistem_pembelajaran', ['PBL', 'PBL/Elearning', 'Elearning'])->count();"
)

with open('app/Http/Controllers/MatakuliahController.php', 'w', encoding='utf-8') as f:
    f.write(content)
