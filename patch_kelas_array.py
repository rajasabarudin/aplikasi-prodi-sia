import re

files = [
    'resources/views/kelas/index.blade.php',
    'resources/views/kelas/show.blade.php',
    'resources/views/kelas/edit.blade.php'
]

for file_path in files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # index.blade.php cases
    content = content.replace("route('kelas.show', $k->id)", "route('kelas.show', ['kelas' => $k->id])")
    content = content.replace("route('kelas.edit', $k->id)", "route('kelas.edit', ['kelas' => $k->id])")
    content = content.replace("route('kelas.destroy', $k->id)", "route('kelas.destroy', ['kelas' => $k->id])")
    
    # Just in case it was still using $k
    content = content.replace("route('kelas.show', $k)", "route('kelas.show', ['kelas' => $k->id])")
    content = content.replace("route('kelas.edit', $k)", "route('kelas.edit', ['kelas' => $k->id])")
    content = content.replace("route('kelas.destroy', $k)", "route('kelas.destroy', ['kelas' => $k->id])")

    # show.blade.php and edit.blade.php cases
    content = content.replace("route('kelas.edit', $kela->id)", "route('kelas.edit', ['kelas' => $kela->id])")
    content = content.replace("route('kelas.update', $kela->id)", "route('kelas.update', ['kelas' => $kela->id])")
    
    content = content.replace("route('kelas.edit', $kela)", "route('kelas.edit', ['kelas' => $kela->id])")
    content = content.replace("route('kelas.update', $kela)", "route('kelas.update', ['kelas' => $kela->id])")

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
