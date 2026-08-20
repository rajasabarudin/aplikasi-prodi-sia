import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

td_jenis = """                                        @php
                                            $badgeColor = 'bg-secondary text-white';
                                            if($mk->jenis_matakuliah == 'Ciri Nasional') $badgeColor = 'bg-danger text-white';
                                            elseif($mk->jenis_matakuliah == 'Ciri Institusi') $badgeColor = 'bg-warning text-dark';
                                            elseif($mk->jenis_matakuliah == 'Inti Program Studi') $badgeColor = 'bg-primary text-white';
                                            elseif($mk->jenis_matakuliah == 'Pendukung') $badgeColor = 'bg-info text-dark';
                                            
                                            $pembelajaranColor = 'bg-secondary';
                                            if($mk->sistem_pembelajaran == 'PBL' || $mk->sistem_pembelajaran == 'PBL/Elearning') $pembelajaranColor = 'bg-info text-dark';
                                        @endphp
                                        <div class="d-flex flex-column gap-1 align-items-start">
                                            <span class="badge {{ $badgeColor }} py-1.5 px-2.5" style="border-radius: 6px; font-size: 0.7rem; font-weight: 600;">{{ $mk->jenis_matakuliah }}</span>
                                            <span class="badge {{ $pembelajaranColor }} py-1 px-2" style="border-radius: 4px; font-size: 0.65rem;"><i class="bi bi-laptop me-1"></i>{{ $mk->sistem_pembelajaran ?? 'Reguler' }}</span>
                                        </div>"""

content = re.sub(r'                                        @php\s*\$badgeColor = \'bg-secondary text-white\';\s*if\(\$mk->jenis_matakuliah == \'Ciri Nasional\'\) \$badgeColor = \'bg-danger text-white\';\s*elseif\(\$mk->jenis_matakuliah == \'Ciri Institusi\'\) \$badgeColor = \'bg-warning text-dark\';\s*elseif\(\$mk->jenis_matakuliah == \'Inti Program Studi\'\) \$badgeColor = \'bg-primary text-white\';\s*elseif\(\$mk->jenis_matakuliah == \'Pendukung\'\) \$badgeColor = \'bg-info text-dark\';\s*@endphp\s*<span class="badge \{\{ \$badgeColor \}\} py-1\.5 px-2\.5" style="border-radius: 6px; font-size: 0\.75rem; font-weight: 600;">\{\{ \$mk->jenis_matakuliah \}\}</span>', td_jenis, content)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
