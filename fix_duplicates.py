import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = re.sub(
    r'data-sistem_pembelajaran="\{\{\s*\$mk->sistem_pembelajaran\s*\}\}"\s*data-sistem_pembelajaran="\{\{\s*\$mk->sistem_pembelajaran\s*\}\}"',
    r'data-sistem_pembelajaran="{{ $mk->sistem_pembelajaran }}"',
    content
)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
