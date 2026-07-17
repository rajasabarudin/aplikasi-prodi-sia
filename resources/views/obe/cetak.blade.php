<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akreditasi & Portofolio OBE</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: black;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 2px solid black;
        }
        .header-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .text-bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        
        .section-title {
            background-color: #e0e0e0;
            font-weight: bold;
            padding: 6px;
            border: 1px solid black;
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 12pt;
        }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid black;
            padding: 6px 8px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-print {
            text-align: right;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .btn-print {
            padding: 10px 20px;
            font-size: 14px;
            background: #000;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .grid-summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-box {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Dokumen / Simpan PDF</button>
    </div>

    <!-- COVER / HEADER -->
    <table class="header-table">
        <tr>
            <td style="width: 15%;">
                <img src="{{ asset('img/logo_ubsi.png') }}" style="max-width: 80px; height: auto;" alt="Logo UBSI">
            </td>
            <td style="width: 70%;">
                <h3 style="margin: 2px 0; font-size: 14pt;">UNIVERSITAS BINA SARANA INFORMATIKA</h3>
                <h4 style="margin: 2px 0; font-weight: normal; font-size: 11pt;">FAKULTAS TEKNIK & INFORMATIKA</h4>
                <h4 style="margin: 2px 0; font-weight: normal; font-size: 11pt;">PROGRAM STUDI {{ Auth::user()->prodi->nama_prodi ?? 'SISTEM INFORMASI AKUNTANSI' }} (D3)</h4>
                <h2 style="margin: 10px 0 2px 0; font-size: 14pt; background-color: #e0e0e0; padding: 5px;">PORTFOLIO AKREDITASI OBE (KRITERIA A-E)</h2>
            </td>
            <td style="width: 15%; font-size: 9pt; text-align: right; vertical-align: bottom;">
                Tgl Cetak:<br>
                {{ date('d M Y') }}
            </td>
        </tr>
    </table>

    <!-- KRITERIA A -->
    <div class="section-title">KRITERIA A: CAPAIAN PEMBELAJARAN LULUSAN (CPL)</div>
    <p class="text-justify">Pemetaan ketercapaian kompetensi mahasiswa berdasarkan Standar CPL yang ditetapkan oleh Program Studi. Batas minimum ketuntasan kompetensi mahasiswa (threshold) ditetapkan sebesar <strong>70.00</strong> dengan target tingkat kelulusan per CPL minimal <strong>75%</strong>.</p>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Kode CPL</th>
                <th style="width: 60%;">Deskripsi Kompetensi Lulusan</th>
                <th style="width: 15%;">Rerata Nilai</th>
                <th style="width: 15%;">Persentase Ketercapaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cpls as $index => $cpl)
            <tr>
                <td class="text-center text-bold">{{ $cpl->kode_cpl }}</td>
                <td class="text-justify">{{ $cpl->deskripsi_cpl }}</td>
                <td class="text-center">{{ $cplAverages[$index] }}</td>
                <td class="text-center text-bold">{{ $cplTargetMetPerc[$index] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- KRITERIA B -->
    <div class="section-title">KRITERIA B: KURIKULUM & DOKUMEN PEMBELAJARAN</div>
    <div class="grid-summary">
        <div class="summary-box">
            <strong>Jumlah Mata Kuliah</strong><br>
            <span style="font-size: 16pt; font-weight: bold;">{{ $totalMk }} MK</span>
        </div>
        <div class="summary-box">
            <strong>Total SKS Kurikulum</strong><br>
            <span style="font-size: 16pt; font-weight: bold;">{{ $totalSks }} SKS</span>
        </div>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th style="width: 12%;">Semester</th>
                <th style="width: 12%;">SKS (T/PA/PU)</th>
                <th style="width: 15%;">RPS</th>
                <th style="width: 15%;">RTM & Silabus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matakuliahList as $idx => $mk)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="text-center"><code>{{ $mk->kode_matakuliah }}</code></td>
                <td class="text-bold">{{ $mk->nama_matakuliah }}</td>
                <td class="text-center">{{ $mk->semester }}</td>
                <td class="text-center">{{ $mk->sks_t + $mk->sks_pa + $mk->sks_pu }}</td>
                <td class="text-center">{{ $mk->rps ? 'Tersedia' : 'Belum Dibuat' }}</td>
                <td class="text-center">
                    {{ $mk->rps && $mk->rps->rtm ? 'RTM' : '-' }} / {{ $mk->rps && $mk->rps->silabus ? 'Silabus' : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- KRITERIA C -->
    <div class="section-title">KRITERIA C: SIKLUS PERBAIKAN BERKELANJUTAN (CQI)</div>
    <p class="text-justify">Riwayat evaluasi dosen pengampu mata kuliah terhadap kendala ketercapaian CPL serta rencana tindakan perbaikan kurikulum terintegrasi untuk siklus semester berikutnya.</p>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Mata Kuliah & Semester</th>
                <th style="width: 35%;">Temuan Masalah (Analisis)</th>
                <th style="width: 40%;">Rencana Perbaikan Pembelajaran (CQI)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cqiLogs as $log)
            <tr>
                <td>
                    <strong>{{ $log->rps?->matakuliah?->nama_matakuliah }}</strong><br>
                    <small>Semester: {{ $log->semester }}</small>
                </td>
                <td class="text-justify">{{ $log->analisis_masalah }}</td>
                <td class="text-justify">{{ $log->rencana_perbaikan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Belum ada riwayat perbaikan mutu (CQI) yang dicatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h4 class="text-bold" style="margin-top: 15px;">Evaluasi Survei Kepuasan Stakeholder</h4>
    @foreach($surveys as $tahun => $aspekList)
    <div style="margin-bottom: 20px;">
        <h5 class="text-bold" style="margin-bottom: 5px;">Survei Pengguna Lulusan & Alumni - Tahun {{ $tahun }}</h5>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Aspek Kompetensi Lulusan (CPL)</th>
                    <th style="text-align: center; width: 15%;">Sangat Baik (%)</th>
                    <th style="text-align: center; width: 15%;">Baik (%)</th>
                    <th style="text-align: center; width: 15%;">Cukup (%)</th>
                    <th style="text-align: center; width: 15%;">Kurang (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aspekList as $s)
                <tr>
                    <td><strong>{{ $s->aspek_penilaian }}</strong></td>
                    <td class="text-center">{{ $s->nilai_sangat_baik }}%</td>
                    <td class="text-center">{{ $s->nilai_baik }}%</td>
                    <td class="text-center">{{ $s->nilai_cukup }}%</td>
                    <td class="text-center">{{ $s->nilai_kurang }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <!-- KRITERIA D & E -->
    <div class="section-title">KRITERIA D & E: SUMBER DAYA & MAHASISWA</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Aspek Sumber Daya & Kesiswaan</th>
                <th style="text-align: center; width: 25%;">Total / Nilai Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Jumlah Dosen Aktif Pengampu Program Studi</td>
                <td class="text-center text-bold">{{ $jumlahDosen }} Dosen</td>
            </tr>
            <tr>
                <td>Karya Ilmiah, Buku & Hasil Penelitian Dosen</td>
                <td class="text-center text-bold">{{ $penelitianCount }} Judul Jurnal</td>
            </tr>
            <tr>
                <td>Pengabdian kepada Masyarakat (PkM) Terlaksana</td>
                <td class="text-center text-bold">{{ $pkmCount }} Kegiatan</td>
            </tr>
            <tr>
                <td>Dosen Praktisi Pengajar Industri (IKU 4)</td>
                <td class="text-center text-bold">{{ $praktisiCount }} Praktisi</td>
            </tr>
            <tr>
                <td>Kerjasama Aktif & PKS (IKU 3)</td>
                <td class="text-center text-bold">{{ $kerjasamaCount }} Lembaga Mitra</td>
            </tr>
            <tr>
                <td>Jumlah Mahasiswa Aktif Terdaftar</td>
                <td class="text-center text-bold">{{ $jumlahMahasiswa }} Mahasiswa</td>
            </tr>
            <tr>
                <td>Indeks Prestasi Kumulatif (IPK) Rata-rata Program Studi</td>
                <td class="text-center text-bold">{{ $avgIpk }}</td>
            </tr>
            <tr>
                <td>Prestasi Kemahasiswaan Terdata</td>
                <td class="text-center text-bold">{{ $prestasiCount }} Prestasi</td>
            </tr>
            <tr>
                <td>Mahasiswa Bersertifikat Kompetensi (Serkom BNSP)</td>
                <td class="text-center text-bold">{{ $serkomCount }} Sertifikasi</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; width: 100%; font-size: 0.95rem;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 60%; border: none;"></td>
                <td style="width: 40%; text-align: center; border: none;">
                    <p>Pontianak, {{ date('d F Y') }}</p>
                    <p>Ketua Program Studi D3 SIA Kampus Pontianak,</p>
                    <br><br><br>
                      @php
                          $profil = \App\Models\ProfilProdi::first();
                          $namaKaprodi = $profil && $profil->nama_kaprodi ? $profil->nama_kaprodi : 'Raja Sabaruddin, M.Kom';
                      @endphp
                      <p style="font-weight: bold; text-decoration: underline; margin-bottom: 5px;">{{ $namaKaprodi }}</p>
                    <p>NIP. 201610582</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
