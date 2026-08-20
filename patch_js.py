import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace JS variable extraction
content = content.replace(
    "var jenis = button.getAttribute('data-jenis_matakuliah');",
    "var jenis = button.getAttribute('data-jenis_matakuliah');\n                var sistem_pembelajaran = button.getAttribute('data-sistem_pembelajaran');"
)

# Replace JS value assignment
content = content.replace(
    "document.getElementById('edit_jenis_matakuliah').value = jenis || '';",
    "document.getElementById('edit_jenis_matakuliah').value = jenis || '';\n                document.getElementById('edit_sistem_pembelajaran').value = sistem_pembelajaran || 'Reguler';"
)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
