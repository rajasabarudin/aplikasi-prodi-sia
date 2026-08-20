import re

# Patch RPS cetak
with open('resources/views/rps/cetak.blade.php', 'r', encoding='utf-8') as f:
    rps_content = f.read()

rps_content = rps_content.replace(
    '.content-table th, .content-table td { border: 1px solid #000; padding: 6px; }',
    '.content-table th, .content-table td { border: 1px solid #000; padding: 6px; word-wrap: break-word; word-break: break-word; }'
)

with open('resources/views/rps/cetak.blade.php', 'w', encoding='utf-8') as f:
    f.write(rps_content)

# Patch Silabus cetak
with open('resources/views/silabus/cetak.blade.php', 'r', encoding='utf-8') as f:
    silabus_content = f.read()

silabus_content = silabus_content.replace(
    'th, td {\n            border: 1px solid #000;\n            padding: 6px;\n            vertical-align: top;\n        }',
    'th, td {\n            border: 1px solid #000;\n            padding: 6px;\n            vertical-align: top;\n            word-wrap: break-word;\n            word-break: break-word;\n        }'
)

with open('resources/views/silabus/cetak.blade.php', 'w', encoding='utf-8') as f:
    f.write(silabus_content)

