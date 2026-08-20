import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Add data-sistem_pembelajaran to edit buttons (multiple occurrences)
content = content.replace(
    'data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"',
    'data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"\n                                                    data-sistem_pembelajaran="{{ $mk->sistem_pembelajaran }}"'
)

# Wait, there's another occurrence formatted with smaller indentation in the Edit button at the end
content = content.replace(
    'data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"\n                                                data-semester="{{ $mk->semester }}"',
    'data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"\n                                                data-sistem_pembelajaran="{{ $mk->sistem_pembelajaran }}"\n                                                data-semester="{{ $mk->semester }}"'
)
# Let's just use regex to replace all data-jenis_matakuliah
content = re.sub(
    r'(data-jenis_matakuliah="\{\{\s*\$mk->jenis_matakuliah\s*\}\}")',
    r'\1\n                                                    data-sistem_pembelajaran="{{ $mk->sistem_pembelajaran }}"',
    content
)

# 2. Add input to Add Modal
add_input = """                      <!-- Sistem Pembelajaran -->
                      <div class="mb-3">
                          <label for="add_sistem_pembelajaran" class="form-label fw-semibold">Sistem Pembelajaran <span class="text-danger">*</span></label>
                          <select name="sistem_pembelajaran" id="add_sistem_pembelajaran" class="form-select" required>
                              <option value="Reguler">Reguler</option>
                              <option value="PBL">PBL</option>
                              <option value="PBL/Elearning">PBL / E-Learning</option>
                          </select>
                      </div>

                      <!-- Semester -->"""
content = content.replace('                      <!-- Semester -->', add_input, 1)

# 3. Add input to Edit Modal
edit_input = """                      <!-- Sistem Pembelajaran -->
                      <div class="mb-3">
                          <label for="edit_sistem_pembelajaran" class="form-label fw-semibold">Sistem Pembelajaran <span class="text-danger">*</span></label>
                          <select name="sistem_pembelajaran" id="edit_sistem_pembelajaran" class="form-select" required>
                              <option value="Reguler">Reguler</option>
                              <option value="PBL">PBL</option>
                              <option value="PBL/Elearning">PBL / E-Learning</option>
                          </select>
                      </div>

                      <!-- Semester -->"""
content = content.replace('                      <!-- Semester -->', edit_input, 1) # Only 1 left since first was replaced

# 4. Add JS logic
js_logic = """                  var jenis = button.getAttribute('data-jenis_matakuliah');
                  var sistem_pembelajaran = button.getAttribute('data-sistem_pembelajaran');"""
content = content.replace("                  var jenis = button.getAttribute('data-jenis_matakuliah');", js_logic)

js_assign = """                  document.getElementById('edit_jenis_matakuliah').value = jenis || '';
                  document.getElementById('edit_sistem_pembelajaran').value = sistem_pembelajaran || 'Reguler';"""
content = content.replace("                  document.getElementById('edit_jenis_matakuliah').value = jenis || '';", js_assign)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
