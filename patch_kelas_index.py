import re

with open('resources/views/kelas/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("route('kelas.show', $k)", "route('kelas.show', $k->id)")
content = content.replace("route('kelas.edit', $k)", "route('kelas.edit', $k->id)")
content = content.replace("route('kelas.destroy', $k)", "route('kelas.destroy', $k->id)")

with open('resources/views/kelas/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
