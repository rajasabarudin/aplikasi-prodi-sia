<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silabus - {{ $silabus->rps->matakuliah->nama_matakuliah ?? $silabus->rps->kode_matakuliah }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: black;
        }
        .header-text {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 20px;
        }
        .kode-dokumen {
            text-align: right;
            margin-bottom: 20px;
            font-size: 11pt;
        }
        .title-silabus {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #000;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        .section-header {
            background-color: #d9d9d9;
            font-weight: bold;
            text-transform: uppercase;
        }
        .col-num {
            width: 5%;
            text-align: center;
        }
        .preserve-lines {
            white-space: pre-wrap;
        }
        .no-bullets {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }
        .numbered-list {
            padding-left: 20px;
            margin: 0;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
            .container { padding: 0; max-width: 100%; }
        }
        .container { width: 100%; max-width: 1000px; margin: 0 auto; padding: 20px; }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-bottom: 1px solid #ddd;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background: #000; color: #fff; border: none; cursor: pointer; border-radius: 5px;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
        <p style="margin: 5px 0 0; color: #666; font-family: sans-serif; font-size: 14px;">(Pastikan pengaturan print diset ke ukuran <strong>A4</strong>)</p>
    </div>

    <div class="container">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 2px solid #000;">
        <tr>
            <td style="border: 1px solid #000; width: 15%; text-align: center; vertical-align: middle;">
                <img src="{{ asset('img/logo_ubsi.png') }}" style="max-width: 90px; height: auto;" alt="Logo UBSI">
            </td>
            <td style="border: 1px solid #000; width: 65%; text-align: center; vertical-align: middle; padding: 0;">
                <div style="padding: 10px;">
                    <div style="font-weight: bold; font-size: 14pt;">UNIVERSITAS BINA SARANA INFORMATIKA</div>
                    <div style="font-weight: bold; font-size: 12pt;">FAKULTAS {{ strtoupper($silabus->rps->matakuliah->prodi->fakultas ?? 'TEKNIK DAN INFORMATIKA') }}</div>
                    <div style="font-weight: bold; font-size: 12pt;">PROGRAM STUDI {{ strtoupper($silabus->rps->matakuliah->prodi->nama_prodi ?? 'SISTEM INFORMASI AKUNTANSI') }} (D3)</div>
                    <div style="font-weight: bold; font-size: 12pt;">PSDKU KAMPUS KOTA PONTIANAK</div>
                </div>
                <div style="font-weight: bold; font-size: 14pt; background-color: #ddd; padding: 5px; border-top: 2px solid #000; margin: 0;">SILABUS</div>
            </td>
            <td style="border: 1px solid #000; width: 20%; text-align: right; vertical-align: bottom; padding: 10px; font-size: 10pt;">
                <strong>No. Dokumen:</strong><br>
                {{ $silabus->kode_dokumen }}<br>
                {{ \Carbon\Carbon::parse($silabus->rps->tanggal_penyusunan ?? now())->translatedFormat('d F Y') }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="section-header">IDENTITAS MATA KULIAH</td>
        </tr>
        <tr>
            <td colspan="2" width="20%">Nama</td>
            <td>{{ $silabus->rps->matakuliah->nama_matakuliah ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2">Kode</td>
            <td>{{ $silabus->rps->kode_matakuliah }}</td>
        </tr>
        <tr>
            <td colspan="2">sks</td>
            <td>{{ ($silabus->rps->matakuliah->sks_t ?? 0) + ($silabus->rps->matakuliah->sks_pa ?? 0) + ($silabus->rps->matakuliah->sks_pu ?? 0) }}</td>
        </tr>
        <tr>
            <td colspan="2">Semester</td>
            <td>{{ $silabus->rps->matakuliah->semester ?? '-' }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">CAPAIAN PEMBELAJARAN MATA KULIAH (CPMK)</td>
        </tr>
        @php
            $cpmk_parsed = false;
            if (preg_match_all('/^(\d+)\s*\r?\n(.*?)(?=(?:^\d+\s*\r?\n)|\z)/ms', trim($silabus->cpmk), $matches, PREG_SET_ORDER)) {
                $cpmk_parsed = $matches;
            }
        @endphp
        <tr>
            <td colspan="3">
                @if($cpmk_parsed)
                    <table style="width: 100%; border: none; margin: 0; padding: 0;">
                        @foreach($cpmk_parsed as $item)
                        <tr>
                            <td style="width: 3%; vertical-align: top; border: none; padding: 0 5px 5px 0;">{{ $item[1] }}.</td>
                            <td style="vertical-align: top; border: none; padding: 0 0 5px 0; text-align: justify;">{{ trim($item[2]) }}</td>
                        </tr>
                        @endforeach
                    </table>
                @else
                    <div style="text-align: justify;">{{ $silabus->cpmk }}</div>
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">SUB CAPAIAN PEMBELAJARAN MATA KULIAH (Sub-CPMK)</td>
        </tr>
        @php
            $subcpmk_parsed = false;
            if (preg_match_all('/^(\d+)\s*\r?\n(.*?)(?=(?:^\d+\s*\r?\n)|\z)/ms', trim($silabus->sub_cpmk), $matches, PREG_SET_ORDER)) {
                $subcpmk_parsed = $matches;
            }
        @endphp
        <tr>
            <td colspan="3">
                @if($subcpmk_parsed)
                    <table style="width: 100%; border: none; margin: 0; padding: 0;">
                        @foreach($subcpmk_parsed as $item)
                        <tr>
                            <td style="width: 3%; vertical-align: top; border: none; padding: 0 5px 5px 0;">{{ $item[1] }}.</td>
                            <td style="vertical-align: top; border: none; padding: 0 0 5px 0; text-align: justify;">{{ trim($item[2]) }}</td>
                        </tr>
                        @endforeach
                    </table>
                @else
                    <div style="text-align: justify;">{{ $silabus->sub_cpmk }}</div>
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">DESKRIPSI MATA KULIAH</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: justify;">{{ $silabus->rps->deskripsi_singkat_mk ?? $silabus->rps->deskripsi_singkat ?? '-' }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">DOSEN PENGAMPU</td>
        </tr>
        <tr>
            <td colspan="3">
                @php
                    $dosens = explode(',', $silabus->rps->dosen_pengembang ?? '');
                    $dosens = array_filter(array_map('trim', $dosens));
                @endphp
                @if(count($dosens) > 0)
                    <ul class="no-bullets">
                    @foreach($dosens as $dosen)
                        <li>{{ $dosen }}</li>
                    @endforeach
                    </ul>
                @else
                    -
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">MATERI PEMBELAJARAN</td>
        </tr>
        @foreach($silabus->materis as $materi)
        <tr>
            <td class="col-num">{{ $materi->pertemuan }}</td>
            <td colspan="2" class="preserve-lines">{{ $materi->materi }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="3" class="section-header">PUSTAKA UTAMA</td>
        </tr>
        <tr>
            <td colspan="3">
                @if(count($referensi_utama) > 0)
                    <ol class="numbered-list">
                    @foreach($referensi_utama as $ref)
                        <li>{{ $ref->penulis }} ({{ $ref->tahun }}). {{ $ref->judul }}. {{ $ref->penerbit }}.</li>
                    @endforeach
                    </ol>
                @else
                    -
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">PUSTAKA PENDUKUNG</td>
        </tr>
        <tr>
            <td colspan="3">
                @if(count($referensi_pendukung) > 0)
                    <ol class="numbered-list">
                    @foreach($referensi_pendukung as $ref)
                        <li>{{ $ref->penulis }} ({{ $ref->tahun }}). {{ $ref->judul }}. {{ $ref->penerbit }}.</li>
                    @endforeach
                    </ol>
                @else
                    -
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">INTEGRASI HASIL PENELITIAN & PKM DOSEN (OBE)</td>
        </tr>
        <tr>
            <td colspan="3">
                @if(($silabus->rps?->penelitians && $silabus->rps->penelitians->count() > 0) || ($silabus->rps?->pkms && $silabus->rps->pkms->count() > 0))
                    <ol class="numbered-list" style="text-align: justify;">
                        @foreach($silabus->rps->penelitians as $penel)
                        <li><strong>[Penelitian]</strong> {{ $penel->nama_jurnal }} (Dosen: {{ $penel->nama_dosen }}). Integrasi: {{ $penel->pivot->bentuk_integrasi }}.</li>
                        @endforeach
                        @foreach($silabus->rps->pkms as $pkm)
                        <li><strong>[Pengabdian/PkM]</strong> {{ $pkm->tema_pkm }} (Dosen: {{ $pkm->nama_dosen }}). Integrasi: {{ $pkm->pivot->bentuk_integrasi }}.</li>
                        @endforeach
                    </ol>
                @else
                    <span style="font-style: italic; color: #666; padding-left: 5px;">Mata kuliah ini belum mengintegrasikan hasil penelitian atau PkM dosen.</span>
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">PRASYARAT (Jika ada)</td>
        </tr>
        <tr>
            <td colspan="3">{{ $silabus->rps->matakuliah->prasyarat ?? '-' }}</td>
        </tr>
    </table>
    </div>

</body>
</html>
