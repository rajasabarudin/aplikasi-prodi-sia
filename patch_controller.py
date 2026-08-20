import re

with open('app/Http/Controllers/MatakuliahController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add validation rule in store
content = content.replace(
    "'semester' => 'required|string|in:I,II,III,IV,V,VI,VII,VIII',",
    "'semester' => 'required|string|in:I,II,III,IV,V,VI,VII,VIII',\n            'sistem_pembelajaran' => 'required|string|in:Reguler,PBL,PBL/Elearning',"
)

# Also fix the search query in index to search in sistem_pembelajaran
content = content.replace(
    "->orWhere('semester', 'like', '%' . $search . '%');",
    "->orWhere('semester', 'like', '%' . $search . '%')\n                  ->orWhere('sistem_pembelajaran', 'like', '%' . $search . '%');"
)

with open('app/Http/Controllers/MatakuliahController.php', 'w', encoding='utf-8') as f:
    f.write(content)

