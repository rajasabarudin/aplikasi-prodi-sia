import re

with open('app/Http/Controllers/MatakuliahController.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "'sistem_pembelajaran' => 'required|string|in:Reguler,PBL,PBL/Elearning',",
    "'sistem_pembelajaran' => 'required|string|in:Reguler,PBL,PBL/Elearning,Elearning',"
)

with open('app/Http/Controllers/MatakuliahController.php', 'w', encoding='utf-8') as f:
    f.write(content)
