<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak RTM - {{ $rtm->rps?->matakuliah?->nama_matakuliah }}</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; color: #000; margin: 0; padding: 20px; }
        .page-container { width: 100%; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 2px solid #000; padding: 6px; font-size: 11pt; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-bold { font-weight: bold; }
        .section-header { background-color: #d9d9d9; font-weight: bold; text-align: left; vertical-align: top; width: 30%; }
        .title-doc { font-size: 14pt; font-weight: bold; margin: 15px 0; background-color: #d9d9d9; padding: 8px; border: 2px solid #000; }
        .rubric-table th { background-color: #d9d9d9; }
        .preserve-lines { white-space: pre-wrap; word-wrap: break-word; text-align: justify; }
        .page-break { page-break-after: always; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        .btn-print { padding: 10px 20px; background-color: #0d6efd; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn-print:hover { background-color: #0b5ed7; box-shadow: 0 6px 8px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="btn-print">Cetak Dokumen</button>
    </div>

    @foreach($rtm->tugas as $index => $t)
    <div class="rtm-document {{ !$loop->last ? 'page-break' : '' }}">
        <!-- Kop Surat -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 2px solid #000;">
            <tr>
                <td class="text-center" style="width: 15%; padding: 5px;">
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="max-width: 90px; height: auto;" alt="Logo UBSI">
                </td>
                <td class="text-center" style="width: 70%; padding: 0;">
                    <div style="padding: 10px 0;">
                        <h3 style="font-size: 16pt; margin: 2px 0;">UNIVERSITAS BINA SARANA INFORMATIKA</h3>
                        <h2 style="font-size: 14pt; margin: 2px 0;">FAKULTAS TEKNIK & INFORMATIKA</h2>
                        <h2 style="font-size: 14pt; margin: 2px 0;">PROGRAM STUDI {{ Auth::user()->prodi->nama_prodi ?? 'SISTEM INFORMASI AKUNTANSI' }}</h2>
                        <h2 style="font-size: 14pt; margin: 2px 0;">PSDKU KAMPUS KOTA PONTIANAK</h2>
                    </div>
                    <h4 style="font-size: 14pt; background-color: #ddd; padding: 5px; border-top: 2px solid #000; margin: 0;">RENCANA TUGAS MAHASISWA (RTM)</h4>
                </td>
                <td style="width: 15%; vertical-align: bottom; padding: 10px; font-size: 10pt; text-align: right;">
                    <strong>No. Dokumen:</strong><br>
                    {{ $rtm->nomor_dokumen ?? '-' }}
                </td>
            </tr>
        </table>

        <!-- Informasi Tugas -->
        <table>
            <tr>
                <td class="section-header">MATA KULIAH</td>
                <td>{{ $rtm->rps?->matakuliah?->nama_matakuliah }}</td>
            </tr>
            <tr>
                <td class="section-header">DOSEN PENGAMPU</td>
                <td>{{ $rtm->dosen_pengampu }}</td>
            </tr>
            <tr>
                <td class="section-header">KODE MATAKULIAH</td>
                <td>{{ $rtm->rps?->kode_matakuliah }}</td>
            </tr>
            <tr>
                <td class="section-header">SKS / SEMESTER</td>
                <td>{{ $rtm->rps?->matakuliah?->sks_t + $rtm->rps?->matakuliah?->sks_pa + $rtm->rps?->matakuliah?->sks_pu }} SKS / Semester {{ $rtm->semester }}</td>
            </tr>
            <tr>
                <td class="section-header">MINGGU KE / TUGAS KE</td>
                <td>Minggu ke-{{ $t->minggu_ke }} / Tugas ke-{{ $t->tugas_ke }}</td>
            </tr>
            <tr>
                <td class="section-header">BENTUK TUGAS</td>
                <td>{{ $t->bentuk_tugas }}</td>
            </tr>
            <tr>
                <td class="section-header">JUDUL TUGAS</td>
                <td class="text-bold">{{ $t->judul_tugas }}</td>
            </tr>
            <tr>
                <td class="section-header">SUB CPMK</td>
                <td style="text-align: justify;">{{ $t->sub_cpmk }}</td>
            </tr>
            <tr>
                <td class="section-header">URAIAN TUGAS</td>
                <td style="text-align: justify;">
                    <strong>a. Obyek Garapan:</strong><br>
                    {{ $t->obyek_garapan }}<br><br>
                    <strong>b. Metode Pengerjaan Tugas:</strong><br>
                    {{ $t->metode_pengerjaan }}<br><br>
                    <strong>c. Bentuk dan Format Luaran:</strong><br>
                    {{ $t->bentuk_format_luaran }}
                </td>
            </tr>
            <tr>
                <td class="section-header">INDIKATOR, TEKNIK & BOBOT PENILAIAN</td>
                <td>
                    @if($t->penilaians->count() > 0)
                        <table class="rubric-table" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th style="width: 8%;" class="text-center">No</th>
                                    <th class="text-center">Indikator Penilaian</th>
                                    <th style="width: 25%;" class="text-center">Teknik Penilaian</th>
                                    <th style="width: 15%;" class="text-center">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($t->penilaians as $pIndex => $p)
                                <tr>
                                    <td class="text-center">{{ $pIndex + 1 }}</td>
                                    <td class="text-left" style="text-align: justify;">{{ $p->indikator }}</td>
                                    <td class="text-center">{{ $p->teknik_penilaian }}</td>
                                    <td class="text-center">{{ $p->bobot_penilaian }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <span class="text-muted fst-italic">Rubrik penilaian tugas ini belum terinci atau dinilai secara umum.</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="section-header">WAKTU PENGERJAAN & PENGUMPULAN</td>
                <td>
                    <strong>Waktu Pengerjaan:</strong> {{ $t->waktu_pengerjaan ?: '-' }}<br>
                    <strong>Waktu Pengumpulan:</strong> {{ $t->waktu_pengumpulan ?: '-' }}
                </td>
            </tr>
            <tr>
                <td class="section-header">LAIN-LAIN</td>
                <td style="text-align: justify;">{{ $t->lain_lain }}</td>
            </tr>
            <tr>
                <td class="section-header">DAFTAR RUJUKAN</td>
                <td style="text-align: justify;">{{ $t->daftar_rujukan }}</td>
            </tr>
            <tr>
                <td class="section-header">INTEGRASI HASIL PENELITIAN & PKM DOSEN</td>
                <td>
                    @if(($rtm->rps?->penelitians && $rtm->rps->penelitians->count() > 0) || ($rtm->rps?->pkms && $rtm->rps->pkms->count() > 0))
                        <ul style="margin: 0; padding-left: 20px; text-align: justify;">
                            @foreach($rtm->rps->penelitians as $penel)
                            <li><strong>Hasil Penelitian Dosen:</strong> {{ $penel->nama_jurnal }} (Dosen: {{ $penel->nama_dosen }}). <br><span class="text-primary">Integrasi dalam pembelajaran:</span> {{ $penel->pivot->bentuk_integrasi }}</li>
                            @endforeach
                            @foreach($rtm->rps->pkms as $pkm)
                            <li><strong>Hasil Pengabdian (PkM) Dosen:</strong> {{ $pkm->tema_pkm }} (Dosen: {{ $pkm->nama_dosen }}, Mitra: {{ $pkm->mitra }}). <br><span class="text-success">Integrasi dalam pembelajaran:</span> {{ $pkm->pivot->bentuk_integrasi }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span style="font-style: italic; color: #666;">Tugas ini belum mengintegrasikan hasil penelitian atau PkM dosen secara spesifik.</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endforeach

</body>
</html>
