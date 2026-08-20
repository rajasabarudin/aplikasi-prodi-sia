import re

with open('app/Http/Controllers/KelasController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace method signatures and variable names
content = content.replace("public function show(Kelas $kela)", "public function show(Kelas $kelas)")
content = content.replace("compact('kela')", "compact('kelas')")

content = content.replace("public function edit(Kelas $kela)", "public function edit(Kelas $kelas)")

content = content.replace("public function update(Request $request, Kelas $kela)", "public function update(Request $request, Kelas $kelas)")
content = content.replace("$kela->id", "$kelas->id")
content = content.replace("$kela->update", "$kelas->update")

content = content.replace("public function destroy(Kelas $kela)", "public function destroy(Kelas $kelas)")
content = content.replace("$kela->delete()", "$kelas->delete()")

with open('app/Http/Controllers/KelasController.php', 'w', encoding='utf-8') as f:
    f.write(content)
