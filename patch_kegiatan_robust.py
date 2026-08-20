import re

with open('resources/views/mahasiswa/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace <option value="sertifikasi">Sertifikasi</option>
# with itself + the new options
replacement = """<option value="sertifikasi">Sertifikasi</option>
                              <option value="kepanitiaan">Kepanitiaan</option>
                              <option value="kunjungan studi/industri">Kunjungan Studi/Industri</option>
                              <option value="kuliah umum">Kuliah Umum</option>"""

content = content.replace('<option value="sertifikasi">Sertifikasi</option>', replacement)

with open('resources/views/mahasiswa/show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
