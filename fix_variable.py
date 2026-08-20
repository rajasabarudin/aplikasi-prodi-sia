import re

with open('resources/views/penghargaan-universitas/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "@foreach($penghargaanUniversitas as $item)",
    "@foreach($penghargaan as $item)"
)

with open('resources/views/penghargaan-universitas/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
