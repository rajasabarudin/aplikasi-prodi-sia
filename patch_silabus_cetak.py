import re

with open('resources/views/silabus/cetak.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "<li>{{ $penel->nama_dosen }} ({{ $penel->ts?->tahun_sekarang ?? 'N/A' }}). {{ $penel->judul_penelitian ?? $penel->nama_jurnal }}. (Integrasi Penelitian: {{ $penel->pivot->bentuk_integrasi }}).</li>",
    "<li>{{ $penel->nama_dosen }} ({{ $penel->ts?->tahun_sekarang ?? 'N/A' }}). <em>{{ $penel->judul_penelitian ?? $penel->nama_jurnal }}</em>.</li>"
)

content = content.replace(
    "<li>{{ $pkm->nama_dosen }} ({{ $pkm->ts?->tahun_sekarang ?? 'N/A' }}). {{ $pkm->tema_pkm }}. (Integrasi PkM: {{ $pkm->pivot->bentuk_integrasi }}).</li>",
    "<li>{{ $pkm->nama_dosen }} ({{ $pkm->ts?->tahun_sekarang ?? 'N/A' }}). <em>{{ $pkm->tema_pkm }}</em>.</li>"
)

with open('resources/views/silabus/cetak.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
