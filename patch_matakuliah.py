import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add th
new_th = r'<th style="width: 20%;">Dokumen Pembelajaran</th>\n                                <th style="width: 12%;">Integrasi (OBE)</th>\n                                <th class="text-center d-print-none" style="width: 9%;">Aksi</th>'
content = re.sub(r'<th style="width: 20%;">Dokumen Pembelajaran</th>\s*<th class="text-center d-print-none" style="width: 9%;">Aksi</th>', new_th, content)

# Adjust widths in th to make room for 12%
content = content.replace('<th style="width: 10%;">Kode MK</th>', '<th style="width: 8%;">Kode MK</th>')
content = content.replace('<th style="width: 25%;">Nama Matakuliah</th>', '<th style="width: 20%;">Nama Matakuliah</th>')
content = content.replace('<th style="width: 15%;">Jenis</th>', '<th style="width: 12%;">Jenis</th>')
content = content.replace('<th style="width: 20%;">Dokumen Pembelajaran</th>', '<th style="width: 18%;">Dokumen Pemb.</th>')

# Find the end of the td for Dokumen Pembelajaran to insert the new td
# The td for Dokumen Pembelajaran ends with </td> just before <td class="text-center d-print-none">
# Let's use a regex to find the closing td of Dokumen Pembelajaran.
# It looks like:
#                                         @endif
#                                     </td>
#                                     <td class="text-center d-print-none">
# Let's replace the last </td>\s*<td class="text-center d-print-none"> in the loop

td_html = """                                        @endif
                                    </td>
                                    <td>
                                        @if($mk->rps)
                                            @php
                                                $countPenelitian = $mk->rps->penelitians ? $mk->rps->penelitians->count() : 0;
                                                $countPkm = $mk->rps->pkms ? $mk->rps->pkms->count() : 0;
                                            @endphp
                                            @if($countPenelitian > 0 || $countPkm > 0)
                                                <div class="d-flex flex-column gap-1 align-items-start">
                                                @if($countPenelitian > 0)
                                                    <span class="badge bg-success" style="font-size: 0.7rem;"><i class="bi bi-journal-text me-1"></i>Penelitian ({{ $countPenelitian }})</span>
                                                @endif
                                                @if($countPkm > 0)
                                                    <span class="badge bg-info text-dark" style="font-size: 0.7rem;"><i class="bi bi-people-fill me-1"></i>PkM ({{ $countPkm }})</span>
                                                @endif
                                                </div>
                                            @else
                                                <span class="text-muted small fst-italic">N/A</span>
                                            @endif
                                        @else
                                            <span class="text-muted small fst-italic">No RPS</span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">"""

content = re.sub(r'(\s*@endif\s*</td>\s*)<td class="text-center d-print-none">', r'\1' + td_html.split('                                    </td>\n')[1], content)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
