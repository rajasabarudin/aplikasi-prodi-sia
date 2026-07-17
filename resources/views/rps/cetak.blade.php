<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak RPS - {{ $rps->matakuliah?->nama_matakuliah }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.3; }
        .container { width: 100%; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 2px solid #000; }
        .header-table td { border: 1px solid #000; padding: 8px; vertical-align: middle; }
        .header-logo { width: 15%; text-align: center; }
        .header-title { width: 85%; text-align: center; }
        .header-title h2, .header-title h3, .header-title h4 { margin: 2px 0; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 2px solid #000; }
        .info-table th, .info-table td { border: 1px solid #000; padding: 6px 10px; text-align: left; }
        .info-table th { background-color: #f2f2f2; }
        
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 2px solid #000; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 6px; }
        .content-table th { background-color: #f2f2f2; text-align: center; }
        
        .signature-table { width: 100%; margin-top: 30px; }
        .signature-table td { text-align: center; width: 33%; vertical-align: top; }
        .signature-space { height: 80px; }
        
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 5px; background: #e0e0e0; padding: 5px; border: 1px solid #000; }
        
        @media print {
            @page { margin: 1cm; size: landscape; }
            .no-print { display: none; }
            body { background: #fff; }
            .container { padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-bottom: 1px solid #ddd;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background: #000; color: #fff; border: none; cursor: pointer; border-radius: 5px;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
        <p style="margin: 5px 0 0; color: #666; font-family: sans-serif; font-size: 14px;">(Pastikan pengaturan print diset ke <strong>Landscape</strong>)</p>
    </div>

    <div class="container">
        <!-- HEADER KOP SURAT -->
        <table class="header-table">
            <tr>
                <td class="text-center" style="width: 15%;">
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="max-width: 90px; height: auto;" alt="Logo UBSI">
                </td>
                <td class="text-center" style="width: 70%;">
                    <h3 style="font-size: 16pt; margin: 2px 0;">UNIVERSITAS BINA SARANA INFORMATIKA</h3>
                    <h2 style="font-size: 14pt; margin: 2px 0;">FAKULTAS TEKNIK & INFORMATIKA</h2>
                    <h2 style="font-size: 14pt; margin: 2px 0;">PROGRAM STUDI {{ Auth::user()->prodi->nama_prodi ?? 'SISTEM INFORMASI AKUNTANSI' }}</h2>
                    <h2 style="font-size: 14pt; margin: 2px 0;">PSDKU KAMPUS KOTA PONTIANAK</h2>
                    <h4 style="font-size: 14pt; background-color: #ddd; padding: 5px; border-top: 2px solid #000; border-bottom: 2px solid #000; margin: 5px 0 0 0;">RENCANA PEMBELAJARAN SEMESTER (RPS)</h4>
                </td>
                <td style="width: 15%; vertical-align: bottom; padding: 10px; font-size: 10pt; text-align: right;">
                    <strong>No. Dokumen:</strong><br>
                    {{ $rps->nomor_dokumen ?? '-' }}
                </td>
            </tr>
        </table>

        <!-- IDENTITAS MATAKULIAH -->
        <table class="info-table">
            <tr>
                <th rowspan="2" style="width: 25%; text-align: center; vertical-align: middle;">MATA KULIAH</th>
                <th rowspan="2" style="width: 10%; text-align: center; vertical-align: middle;">KODE</th>
                <th rowspan="2" style="width: 20%; text-align: center; vertical-align: middle;">Kelompok MK (KMK)</th>
                <th colspan="3" style="width: 15%; text-align: center;">BOBOT (sks)</th>
                <th rowspan="2" style="width: 15%; text-align: center; vertical-align: middle;">SEMESTER</th>
                <th rowspan="2" style="width: 15%; text-align: center; vertical-align: middle;">Tgl Penyusunan</th>
            </tr>
            <tr>
                <th style="text-align: center; font-size: 0.85em; padding: 4px;">T</th>
                <th style="text-align: center; font-size: 0.85em; padding: 4px;">PA</th>
                <th style="text-align: center; font-size: 0.85em; padding: 4px;">PU</th>
            </tr>
            <tr>
                <td style="font-weight: bold; text-align: center;">{{ $rps->matakuliah?->nama_matakuliah }}</td>
                <td style="text-align: center;">{{ $rps->kode_matakuliah }}</td>
                <td style="text-align: center;">{{ $rps->matakuliah?->jenis_matakuliah ?? '-' }}</td>
                <td style="text-align: center;">{{ $rps->matakuliah?->sks_t }}</td>
                <td style="text-align: center;">{{ $rps->matakuliah?->sks_pa }}</td>
                <td style="text-align: center;">{{ $rps->matakuliah?->sks_pu }}</td>
                <td style="text-align: center;">{{ $rps->matakuliah?->semester }}</td>
                <td style="text-align: center;">{{ $rps->tanggal_penyusunan ? $rps->tanggal_penyusunan->format('d M Y') : '-' }}</td>
            </tr>
        </table>

        <!-- OTORISASI -->
        @php
            $pengembangs = array_filter(array_map('trim', explode(',', $rps->dosen_pengembang)));
        @endphp
        <table class="info-table" style="margin-top: -15px;">
            <tr>
                <th style="width: 15%; text-align: center; vertical-align: middle;" rowspan="2">OTORISASI</th>
                <th class="text-center" style="width: 28%;">Dosen Pengembang RPS</th>
                <th class="text-center" style="width: 28%;">Koordinator MK</th>
                <th class="text-center" style="width: 29%;">Ketua Program Studi</th>
            </tr>
            <tr>
                <td class="text-left" style="padding: 10px; vertical-align: top;">
                    <div style="margin-top: 5px;">
                    @if(count($pengembangs) > 0)
                        @foreach($pengembangs as $index => $pengembang)
                            {{ $index + 1 }}. {{ $pengembang }}<br>
                        @endforeach
                    @else
                        <div class="text-center" style="margin-top: 65px;">( ........................ )</div>
                    @endif
                    </div>
                </td>
                <td class="text-center" style="padding: 10px; vertical-align: middle;">
                    @if($rps->koordinator)
                        <div style="position: relative; display: block; margin: 0 auto 5px auto; width: 70px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&ecc=H&data={{ urlencode(route('penyusunan-rps.cetak', $rps->id)) }}" style="width: 70px; height: 70px; display: block;" alt="QR Koordinator">
                            <img src="{{ asset('img/logo_ubsi.png') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 18px; height: 18px; background: white; padding: 1px; border-radius: 2px;" alt="Logo UBSI">
                        </div>
                    @else
                        <div class="signature-space" style="height: 75px;"></div>
                    @endif
                    <div style="margin-top: 10px; text-align: center;"><strong>{{ $rps->koordinator ?? '( ........................ )' }}</strong></div>
                </td>
                <td class="text-center" style="padding: 10px; vertical-align: middle;">
                    @if($rps->kaprodi)
                        <div style="position: relative; display: block; margin: 0 auto 5px auto; width: 70px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&ecc=H&data={{ urlencode(route('penyusunan-rps.cetak', $rps->id)) }}" style="width: 70px; height: 70px; display: block;" alt="QR Kaprodi">
                            <img src="{{ asset('img/logo_ubsi.png') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 18px; height: 18px; background: white; padding: 1px; border-radius: 2px;" alt="Logo UBSI">
                        </div>
                    @else
                        <div class="signature-space" style="height: 75px;"></div>
                    @endif
                    <div style="margin-top: 10px; text-align: center;"><strong>{{ $rps->kaprodi ?? '( ........................ )' }}</strong></div>
                </td>
            </tr>
        </table>

        <!-- CAPAIAN PEMBELAJARAN -->
        <div class="section-title">CAPAIAN PEMBELAJARAN (CPL-PRODI & CPMK)</div>
        <table class="info-table">
            <tr>
                <td colspan="2" class="text-bold">Capaian Pembelajaran Lulusan (CPL-PRODI)</td>
            </tr>
            @forelse($cpmks->pluck('cpl')->unique('id') as $cpl)
            <tr>
                <td style="width: 10%; font-weight: bold;">{{ $cpl->kode_cpl }}</td>
                <td style="text-align: justify;">{{ $cpl->deskripsi_cpl }}</td>
            </tr>
            @empty
            <tr><td colspan="2">Belum ada CPL yang dipetakan ke matakuliah ini di Master Data.</td></tr>
            @endforelse
            
            <tr>
                <td colspan="2" class="text-bold" style="border-top: 2px solid #000;">Capaian Pembelajaran Mata Kuliah (CPMK)</td>
            </tr>
            @forelse($cpmks as $cpmk)
            <tr>
                <td style="width: 10%; font-weight: bold;">{{ $cpmk->kode_cpmk }}</td>
                <td style="text-align: justify;">{{ $cpmk->deskripsi_cpmk }}</td>
            </tr>
            @empty
            <tr><td colspan="2">Belum ada CPMK yang ditambahkan di Master Data.</td></tr>
            @endforelse
            
            <tr>
                <td colspan="2" class="text-bold" style="border-top: 2px solid #000;">Deskripsi Singkat MK</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: justify;">{{ $rps->deskripsi_singkat ?: 'Deskripsi singkat belum diisi.' }}</td>
            </tr>
            
            <tr>
                <td colspan="2" class="text-bold" style="border-top: 2px solid #000;">Bahan Kajian (Materi Pembelajaran)</td>
            </tr>
            <tr>
                <td colspan="2">
                    @if(isset($bahan_kajian) && $bahan_kajian->count() > 0)
                    <ol style="margin: 5px 0; padding-left: 20px;">
                        @foreach($bahan_kajian as $bk)
                        <li>{{ $bk->topik }}</li>
                        @endforeach
                    </ol>
                    @else
                    Belum ada bahan kajian.
                    @endif
                </td>
            </tr>
            
            <tr>
                <td colspan="2" class="text-bold" style="border-top: 2px solid #000;">Pustaka / Referensi</td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Utama:</strong><br>
                    <ul style="margin: 5px 0; padding-left: 20px;">
                        @forelse($referensi_utama as $r)
                        <li>{{ $r->penulis }} ({{ $r->tahun }}). <em>{{ $r->judul }}</em>. {{ $r->kota }}: {{ $r->penerbit }}.</li>
                        @empty
                        <li>Belum ada referensi utama.</li>
                        @endforelse
                    </ul>
                    <strong>Pendukung:</strong><br>
                    <ul style="margin: 5px 0; padding-left: 20px;">
                        @forelse($referensi_pendukung as $r)
                        <li>{{ $r->penulis }} ({{ $r->tahun }}). <em>{{ $r->judul }}</em>. {{ $r->kota }}: {{ $r->penerbit }}.</li>
                        @empty
                        <li>-</li>
                        @endforelse
                    </ul>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-bold" style="border-top: 2px solid #000;">Integrasi Hasil Penelitian & PkM dalam Pembelajaran (OBE)</td>
            </tr>
            <tr>
                <td colspan="2">
                    @if(($rps->penelitians && $rps->penelitians->count() > 0) || ($rps->pkms && $rps->pkms->count() > 0))
                        <table style="width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 10pt;" border="1">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th style="padding: 5px; width: 5%; text-align: center;">No</th>
                                    <th style="padding: 5px; width: 15%; text-align: center;">Jenis Kegiatan</th>
                                    <th style="padding: 5px; width: 45%; text-align: center;">Judul Penelitian / Tema PkM & Dosen</th>
                                    <th style="padding: 5px; width: 35%; text-align: center;">Bentuk Integrasi dalam Perkuliahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $integrasiNo = 1; @endphp
                                @foreach($rps->penelitians as $penel)
                                <tr>
                                    <td style="padding: 5px; text-align: center;">{{ $integrasiNo++ }}</td>
                                    <td style="padding: 5px; text-align: center; font-weight: bold; color: #1a5c8a;">Penelitian</td>
                                    <td style="padding: 5px;">
                                        <strong>{{ $penel->nama_jurnal }}</strong><br>
                                        <small>Dosen: {{ $penel->nama_dosen }} ({{ $penel->ts?->tahun_sekarang ?? 'N/A' }})</small>
                                    </td>
                                    <td style="padding: 5px;">{{ $penel->pivot->bentuk_integrasi }}</td>
                                </tr>
                                @endforeach
                                @foreach($rps->pkms as $pkm)
                                <tr>
                                    <td style="padding: 5px; text-align: center;">{{ $integrasiNo++ }}</td>
                                    <td style="padding: 5px; text-align: center; font-weight: bold; color: #2a7a4a;">Pengabdian (PkM)</td>
                                    <td style="padding: 5px;">
                                        <strong>{{ $pkm->tema_pkm }}</strong><br>
                                        <small>Dosen: {{ $pkm->nama_dosen }} | Mitra: {{ $pkm->mitra }} ({{ $pkm->ts?->tahun_sekarang ?? 'N/A' }})</small>
                                    </td>
                                    <td style="padding: 5px;">{{ $pkm->pivot->bentuk_integrasi }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="font-style: italic; color: #666; padding: 5px;">Mata kuliah ini belum mengintegrasikan hasil penelitian atau PkM dosen pengampu.</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- RINCIAN 16 PERTEMUAN -->
        <div style="page-break-before: always;"></div>
        <div class="section-title">RENCANA KEGIATAN PEMBELAJARAN</div>
        <table class="content-table" style="font-size: 10pt;">
            <thead>
                <tr>
                    <th style="width: 3%;" rowspan="2">Mg Ke-</th>
                    <th style="width: 15%;" rowspan="2">Sub-CPMK<br><small>(Kemampuan akhir tiap tahapan belajar)</small></th>
                    <th style="width: 15%;" rowspan="2">Bahan Kajian<br><small>(Materi Pembelajaran)</small></th>
                    <th style="width: 13%;" rowspan="2">Bentuk & Metode Pembelajaran</th>
                    <th style="width: 7%;" rowspan="2">Waktu</th>
                    <th style="width: 15%;" rowspan="2">Pengalaman Belajar Mahasiswa</th>
                    <th style="width: 32%;" colspan="3">Penilaian</th>
                </tr>
                <tr>
                    <th>Kriteria & Bentuk</th>
                    <th>Indikator</th>
                    <th style="width: 5%;">Bobot (%)</th>
                </tr>
                <tr style="background-color: #e9ecef;">
                    <th>(1)</th>
                    <th>(2)</th>
                    <th>(3)</th>
                    <th>(4)</th>
                    <th>(5)</th>
                    <th>(6)</th>
                    <th>(7)</th>
                    <th>(8)</th>
                    <th>(9)</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 16; $i++)
                @php 
                    $p = $rps->pertemuans->firstWhere('minggu_ke', $i); 
                @endphp
                <tr>
                    <td class="text-center">{{ $i }}</td>
                    <td>{!! nl2br(e($p?->sub_cpmk ?? '')) !!}</td>
                    <td>{!! nl2br(e($p?->bahan_kajian ?? '')) !!}</td>
                    <td>{!! nl2br(e($p?->metode_pembelajaran ?? '')) !!}</td>
                    <td class="text-left">{!! nl2br(e($p?->waktu_pembelajaran ?? '')) !!}</td>
                    <td>{!! nl2br(e($p?->pengalaman_belajar ?? '')) !!}</td>
                    <td>{!! nl2br(e($p?->kriteria_penilaian ?? '')) !!}</td>
                    <td>{!! nl2br(e($p?->indikator_penilaian ?? '')) !!}</td>
                    <td class="text-center">{{ $p?->bobot_penilaian }}</td>
                </tr>
                @endfor
            </tbody>
        </table>

        <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <tr>
                <!-- KOMPONEN PENILAIAN -->
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    <div class="section-title">BOBOT PENILAIAN</div>
                    <table class="info-table" style="width: 100%;">
                        @if($rps->bobot_kehadiran > 0)
                        <tr><td>Kehadiran/Partisipasi</td><td class="text-center text-bold">{{ $rps->bobot_kehadiran }}%</td></tr>
                        @endif
                        @if($rps->bobot_tugas > 0)
                        <tr><td>Tugas</td><td class="text-center text-bold">{{ $rps->bobot_tugas }}%</td></tr>
                        @endif
                        @if($rps->bobot_praktek > 0)
                        <tr><td>Praktek/Observasi</td><td class="text-center text-bold">{{ $rps->bobot_praktek }}%</td></tr>
                        @endif
                        @if($rps->bobot_kuis > 0)
                        <tr><td>Kuis</td><td class="text-center text-bold">{{ $rps->bobot_kuis }}%</td></tr>
                        @endif
                        @if($rps->bobot_uts > 0)
                        <tr><td>UTS</td><td class="text-center text-bold">{{ $rps->bobot_uts }}%</td></tr>
                        @endif
                        @if($rps->bobot_uas > 0)
                        <tr><td>UAS</td><td class="text-center text-bold">{{ $rps->bobot_uas }}%</td></tr>
                        @endif
                        @if($rps->bobot_presentasi > 0)
                        <tr><td>Unjuk Kerja/Presentasi</td><td class="text-center text-bold">{{ $rps->bobot_presentasi }}%</td></tr>
                        @endif
                        @if($rps->bobot_project > 0)
                        <tr><td>Project</td><td class="text-center text-bold">{{ $rps->bobot_project }}%</td></tr>
                        @endif
                        @php
                            $total = $rps->bobot_kehadiran + $rps->bobot_tugas + $rps->bobot_praktek + $rps->bobot_kuis + $rps->bobot_uts + $rps->bobot_uas + $rps->bobot_presentasi + $rps->bobot_project;
                        @endphp
                        <tr><td class="text-bold text-center">TOTAL</td><td class="text-center text-bold">{{ $total }}%</td></tr>
                    </table>
                    
                    <div class="section-title">JENIS ASESMEN</div>
                    <div style="padding: 10px; border: 1px solid #000;">
                        <span>{!! $rps->asesmen_tertulis ? '&#9745;' : '&#9744;' !!} Tertulis</span> &nbsp;&nbsp;
                        <span>{!! $rps->asesmen_lisan ? '&#9745;' : '&#9744;' !!} Lisan</span> &nbsp;&nbsp;
                        <span>{!! $rps->asesmen_kinerja ? '&#9745;' : '&#9744;' !!} Kinerja</span> &nbsp;&nbsp;
                        <span>{!! $rps->asesmen_portofolio ? '&#9745;' : '&#9744;' !!} Portofolio</span>
                    </div>
                </td>

                <!-- RANGE NILAI -->
                <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                    <div class="section-title">RANGE NILAI</div>
                    <table class="info-table" style="width: 100%; text-align: center;">
                        <tr><th class="text-center">Nilai Huruf</th><th class="text-center">Rentang Nilai Angka</th></tr>
                        <tr><td class="text-bold">A</td><td>80 - 100</td></tr>
                        <tr><td class="text-bold">B</td><td>70 - 79</td></tr>
                        <tr><td class="text-bold">C</td><td>60 - 69</td></tr>
                        <tr><td class="text-bold">D</td><td>31 - 59</td></tr>
                        <tr><td class="text-bold">E</td><td>0 - 30</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
