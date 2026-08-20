import re

with open('resources/views/mahasiswa/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

replacement = """                              <option value="pelatihan">Pelatihan</option>
                              <option value="seminar">Seminar</option>
                              <option value="workshop">Workshop</option>
                              <option value="sertifikasi">Sertifikasi</option>
                              <option value="kepanitiaan">Kepanitiaan</option>
                              <option value="kunjungan studi/industri">Kunjungan Studi/Industri</option>
                              <option value="kuliah umum">Kuliah Umum</option>"""

# Find the exact original block to replace (there are two of them, Add and Edit)
original = """                              <option value="pelatihan">Pelatihan</option>
                              <option value="seminar">Seminar</option>
                              <option value="workshop">Workshop</option>
                              <option value="sertifikasi">Sertifikasi</option>"""

content = content.replace(original, replacement)

with open('resources/views/mahasiswa/show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
