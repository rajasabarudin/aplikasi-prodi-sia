import re

with open('resources/views/kelas/edit.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("route('kelas.update', $kela)", "route('kelas.update', $kela->id)")

with open('resources/views/kelas/edit.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
