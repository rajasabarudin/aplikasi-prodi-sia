import re

with open('resources/views/layouts/app.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Evaluasi & Laporan wrap
pattern_laporan = r'(<li class="nav-item mt-2">\s*<a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#laporanMenu")'
repl_laporan = r'@if(Auth::user()->hasPermission("obe-portal") || Auth::user()->hasPermission("tracer-study") || Auth::user()->hasPermission("kohort") || Auth::user()->hasPermission("keuangan-prodi") || Auth::user()->hasPermission("survei-kepuasan"))\n                \1'
content = re.sub(pattern_laporan, repl_laporan, content, count=1)

content = re.sub(
    r'(<li class="nav-item mt-1">\s*<a href="{{ route\(\'obe\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("obe-portal"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Portal Akreditasi \(OBE\)</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

content = re.sub(
    r'(<li class="nav-item mt-1">\s*<a href="{{ route\(\'tracer-study\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("tracer-study"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Tracer Study Alumni</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

content = re.sub(
    r'(<li class="nav-item mt-1">\s*<a href="{{ route\(\'kohort\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("kohort"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Matriks Kohort C3</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

content = re.sub(
    r'(<li class="nav-item mt-1">\s*<a href="{{ route\(\'keuangan-prodi\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("keuangan-prodi"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Keuangan & Dana C5</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

content = re.sub(
    r'(<li class="nav-item mt-1">\s*<a href="{{ route\(\'survei-kepuasan\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("survei-kepuasan"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Survei Kepuasan \(C1-C9\)</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

content = re.sub(
    r'(</ul>\s*</div>\s*</li>)\s*(<!-- Separator: Evaluasi & Asesmen -->)',
    r'\1\n                @endif\n\n                \2',
    content, count=1
)

# portalMenu (change King to hasPermission)
content = re.sub(
    r'@if\(Auth::user\(\)->level === \'king\'\)\s*(<li class="nav-item mt-2">\s*<a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#portalMenu")',
    r'@if(Auth::user()->hasPermission("profil-prodi") || Auth::user()->hasPermission("berita"))\n                \1',
    content
)

content = re.sub(
    r'(<li class="nav-item">\s*<a href="{{ route\(\'profil-prodi\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("profil-prodi"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Profil Prodi</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

content = re.sub(
    r'(<li class="nav-item">\s*<a href="{{ route\(\'berita\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("berita"))\n                            \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Berita & Pengumuman</span>\s*</a>\s*</li>)',
    r'\1\n                            @endif',
    content
)

# kegiatan
content = re.sub(
    r'(<li class="nav-item mt-2">\s*<a href="{{ route\(\'kegiatan\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("kegiatan"))\n                \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Manajemen Kegiatan</span>\s*</a>\s*</li>)',
    r'\1\n                @endif',
    content
)

# digital-twin
content = re.sub(
    r'(<li class="nav-item mt-2">\s*<a href="{{ route\(\'digital-twin\.index\'\) }})',
    r'@if(Auth::user()->hasPermission("digital-twin"))\n                \1',
    content
)
content = re.sub(
    r'(<span class="sidebar-text">Digital Twin \(IoT\)</span>\s*</a>\s*</li>)',
    r'\1\n                @endif',
    content
)

with open('resources/views/layouts/app.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print("Updated app.blade.php")
