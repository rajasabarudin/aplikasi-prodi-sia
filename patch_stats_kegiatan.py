import re

with open('resources/views/mahasiswa/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

capstone_block = """                    <!-- Capstone -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-dark text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-cpu-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Proyek Capstone</span>
                        </div>
                        <span class="badge bg-dark rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ $capstoneList->count() }}
                        </span>
                    </div>"""

kegiatan_block = """
                    <!-- Kegiatan -->
                    <div class="d-flex justify-content-between align-items-center bg-white p-2 rounded border border-light shadow-sm mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-info text-white rounded-3 px-2 py-1 fs-5">
                                <i class="bi bi-calendar-event-fill"></i>
                            </span>
                            <span class="fw-semibold text-dark small">Mengikuti Kegiatan</span>
                        </div>
                        <span class="badge bg-info rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                            {{ ($mahasiswa->kegiatan ?? collect())->count() }}
                        </span>
                    </div>"""

if capstone_block in content:
    content = content.replace(capstone_block, capstone_block + kegiatan_block)
    with open('resources/views/mahasiswa/show.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)
    print("Successfully replaced.")
else:
    print("Capstone block not found. I'll print the exact string from the file.")
    import textwrap
    lines = content.split('\n')
    for i, line in enumerate(lines):
        if "bi-cpu-fill" in line:
            start = max(0, i - 10)
            end = min(len(lines), i + 10)
            print('\n'.join(lines[start:end]))
