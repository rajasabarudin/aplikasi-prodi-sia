import re

# PATCH RPS CETAK
with open('resources/views/rps/cetak.blade.php', 'r', encoding='utf-8') as f:
    rps_content = f.read()

new_rps_pendukung = """                    <strong>Pendukung:</strong><br>
                    <ul style="margin: 5px 0; padding-left: 20px;">
                        @foreach($referensi_pendukung as $r)
                        <li>{{ $r->penulis }} ({{ $r->tahun }}). <em>{{ $r->judul }}</em>. {{ $r->kota }}: {{ $r->penerbit }}.</li>
                        @endforeach
                        @if($rps->penelitians)
                            @foreach($rps->penelitians as $penel)
                            <li>{{ $penel->nama_dosen }} ({{ $penel->ts?->tahun_sekarang ?? 'N/A' }}). <em>{{ $penel->judul_penelitian ?? $penel->nama_jurnal }}</em>. (Integrasi Penelitian: {{ $penel->pivot->bentuk_integrasi }}).</li>
                            @endforeach
                        @endif
                        @if($rps->pkms)
                            @foreach($rps->pkms as $pkm)
                            <li>{{ $pkm->nama_dosen }} ({{ $pkm->ts?->tahun_sekarang ?? 'N/A' }}). <em>{{ $pkm->tema_pkm }}</em>. (Integrasi PkM: {{ $pkm->pivot->bentuk_integrasi }}).</li>
                            @endforeach
                        @endif
                        @if(count($referensi_pendukung) == 0 && (!isset($rps->penelitians) || count($rps->penelitians) == 0) && (!isset($rps->pkms) || count($rps->pkms) == 0))
                        <li>-</li>
                        @endif
                    </ul>
                </td>
            </tr>"""

# Regex to replace from Pendukung to the end of the integrasi section
rps_content = re.sub(
    r"<strong>Pendukung:</strong><br>\s*<ul.*?>.*?</ul>\s*</td>\s*</tr>\s*<tr>\s*<td.*?Integrasi Hasil Penelitian.*?</td>\s*</tr>\s*<tr>\s*<td.*?>\s*@if.*?@endif\s*</td>\s*</tr>",
    new_rps_pendukung,
    rps_content,
    flags=re.DOTALL
)

with open('resources/views/rps/cetak.blade.php', 'w', encoding='utf-8') as f:
    f.write(rps_content)


# PATCH SILABUS CETAK
with open('resources/views/silabus/cetak.blade.php', 'r', encoding='utf-8') as f:
    silabus_content = f.read()

new_silabus_pendukung = """        <tr>
            <td colspan="3" class="section-header">PUSTAKA PENDUKUNG</td>
        </tr>
        <tr>
            <td colspan="3">
                <ol class="numbered-list">
                @foreach($referensi_pendukung as $ref)
                    <li>{{ $ref->penulis }} ({{ $ref->tahun }}). {{ $ref->judul }}. {{ $ref->penerbit }}.</li>
                @endforeach
                @if($silabus->rps?->penelitians)
                    @foreach($silabus->rps->penelitians as $penel)
                    <li>{{ $penel->nama_dosen }} ({{ $penel->ts?->tahun_sekarang ?? 'N/A' }}). {{ $penel->judul_penelitian ?? $penel->nama_jurnal }}. (Integrasi Penelitian: {{ $penel->pivot->bentuk_integrasi }}).</li>
                    @endforeach
                @endif
                @if($silabus->rps?->pkms)
                    @foreach($silabus->rps->pkms as $pkm)
                    <li>{{ $pkm->nama_dosen }} ({{ $pkm->ts?->tahun_sekarang ?? 'N/A' }}). {{ $pkm->tema_pkm }}. (Integrasi PkM: {{ $pkm->pivot->bentuk_integrasi }}).</li>
                    @endforeach
                @endif
                @if(count($referensi_pendukung) == 0 && (!isset($silabus->rps->penelitians) || count($silabus->rps->penelitians) == 0) && (!isset($silabus->rps->pkms) || count($silabus->rps->pkms) == 0))
                    <li>-</li>
                @endif
                </ol>
            </td>
        </tr>"""

# Regex to replace from PUSTAKA PENDUKUNG to the end of the integrasi section
silabus_content = re.sub(
    r"<tr>\s*<td colspan=\"3\" class=\"section-header\">PUSTAKA PENDUKUNG</td>\s*</tr>.*?<tr>\s*<td colspan=\"3\">\s*@if.*?@endif\s*</td>\s*</tr>\s*<tr>\s*<td colspan=\"3\" class=\"section-header\">INTEGRASI HASIL PENELITIAN.*?</td>\s*</tr>\s*<tr>\s*<td colspan=\"3\">\s*@if.*?@endif\s*</td>\s*</tr>",
    new_silabus_pendukung,
    silabus_content,
    flags=re.DOTALL
)

with open('resources/views/silabus/cetak.blade.php', 'w', encoding='utf-8') as f:
    f.write(silabus_content)
