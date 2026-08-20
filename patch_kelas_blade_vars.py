import re

files = [
    'resources/views/kelas/show.blade.php',
    'resources/views/kelas/edit.blade.php'
]

for file_path in files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replace $kela with $kelas
    content = content.replace('$kela->id', '$kelas->id')
    content = content.replace('$kela->nama_kelas', '$kelas->nama_kelas')
    content = content.replace('$kela->created_at', '$kelas->created_at')
    content = content.replace('$kela->updated_at', '$kelas->updated_at')
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
