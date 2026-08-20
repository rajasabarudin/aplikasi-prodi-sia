import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "if($mk->sistem_pembelajaran == 'PBL' || $mk->sistem_pembelajaran == 'PBL/Elearning' || $mk->sistem_pembelajaran == 'Elearning') $pembelajaranColor = 'bg-info text-dark';",
    "if($mk->sistem_pembelajaran == 'PBL' || $mk->sistem_pembelajaran == 'PBL/Elearning') $pembelajaranColor = 'bg-info text-dark';"
)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
