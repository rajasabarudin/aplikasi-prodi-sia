import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# We need to insert the input block just before `<!-- Semester -->` for both Add and Edit modals.
add_input = """                      <!-- Sistem Pembelajaran -->
                      <div class="mb-3">
                          <label for="add_sistem_pembelajaran" class="form-label fw-semibold">Sistem Pembelajaran <span class="text-danger">*</span></label>
                          <select name="sistem_pembelajaran" id="add_sistem_pembelajaran" class="form-select" required>
                              <option value="Reguler">Reguler</option>
                              <option value="PBL">PBL</option>
                              <option value="PBL/Elearning">PBL / E-Learning</option>
                              <option value="Elearning">E-Learning</option>
                          </select>
                      </div>

                      <!-- Semester -->"""

edit_input = """                      <!-- Sistem Pembelajaran -->
                      <div class="mb-3">
                          <label for="edit_sistem_pembelajaran" class="form-label fw-semibold">Sistem Pembelajaran <span class="text-danger">*</span></label>
                          <select name="sistem_pembelajaran" id="edit_sistem_pembelajaran" class="form-select" required>
                              <option value="Reguler">Reguler</option>
                              <option value="PBL">PBL</option>
                              <option value="PBL/Elearning">PBL / E-Learning</option>
                              <option value="Elearning">E-Learning</option>
                          </select>
                      </div>

                      <!-- Semester -->"""

# Since there are two `<!-- Semester -->` comments, we'll split and re-join
parts = re.split(r'\s*<!-- Semester -->', content)

if len(parts) == 3:
    # First split is before Add modal's Semester
    # Second split is before Edit modal's Semester
    content = parts[0] + "\n" + add_input + parts[1] + "\n" + edit_input + parts[2]
else:
    print("Error: Could not find exactly 2 occurrences of '<!-- Semester -->'. Found:", len(parts)-1)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
