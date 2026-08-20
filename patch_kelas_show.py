import re

with open('resources/views/kelas/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "route('kelas.edit', $kela)",
    "route('kelas.edit', $kela->id)"
)

with open('resources/views/kelas/show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
