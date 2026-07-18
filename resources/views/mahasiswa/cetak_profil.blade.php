<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; padding: 20px; background-color: #fff; }
        h1, h2, h3, h4 { margin: 0; padding: 0; }
        .no-print-btn { background-color: #4f46e5; color: white; border: none; padding: 10px 20px; font-size: 1rem; font-weight: bold; border-radius: 5px; cursor: pointer; margin-bottom: 20px; display: inline-flex; }
        
        .header-wrap { display: flex; align-items: center; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 20px; gap: 20px; }
        .header-logo img { width: 85px; height: auto; }
        .header-text { flex: 1; text-align: center; }
        .header-text h1 { font-size: 1.25rem; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        .header-text h2 { font-size: 1.15rem; text-transform: uppercase; margin-bottom: 5px; }
        .header-text p { font-size: 0.9rem; color: #444; }

        .profile-container { display: flex; border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; border-radius: 8px; background: #fafafa; }
        .profile-photo { flex: 0 0 120px; margin-right: 25px; text-align: center; }
        .profile-photo img { width: 110px; height: 140px; object-fit: cover; border: 2px solid #ccc; padding: 3px; background: #fff; }
        .profile-photo .placeholder { width: 110px; height: 140px; border: 2px dashed #999; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.8rem; background: #eee; }
        
        .profile-details { flex: 1; display: flex; flex-wrap: wrap; }
        .profile-details .col { flex: 0 0 50%; box-sizing: border-box; padding-right: 15px; margin-bottom: 10px; }
        .profile-details .label { font-weight: bold; font-size: 0.75rem; color: #666; text-transform: uppercase; margin-bottom: 2px; }
        .profile-details .val { font-size: 0.95rem; font-weight: bold; color: #111; }

        .section-title { font-size: 1.05rem; font-weight: bold; text-transform: uppercase; margin-top: 30px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 2px solid #333; color: #222; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 0.85rem; }
        table.data-table th, table.data-table td { border: 1px solid #333; padding: 6px 8px; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        
        .text-center { text-align: center !important; }
        .text-left { text-align: left !important; }
        .empty-row td { text-align: center; color: #777; font-style: italic; padding: 15px; }

        .footer { margin-top: 50px; text-align: right; padding-right: 30px; page-break-inside: avoid; }
        .footer p { margin: 3px 0; }
        
        @media print { 
            .no-print { display: none !important; } 
            body { padding: 0; } 
            .section-title { page-break-after: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="no-print-btn">Cetak / Simpan Sebagai PDF</button>
    </div>

    <!-- HEADER -->
    <div class="header-wrap">
        <div class="header-logo">
            <img src="{{ asset('img/logo_ubsi.png') }}" alt="Logo">
        </div>
        <div class="header-text">
            <h1>Universitas Bina Sarana Informatika</h1>
            <h1>Kampus Kota Pontianak</h1>
            <h2>Program Studi Sistem Informasi Akuntansi (D3)</h2>
            <p>Buku Profil & Portofolio Mahasiswa (Kurikulum OBE)</p>
        </div>
    </div>

    <!-- PROFILE DATA -->
    <h3 class="section-title">A. Data Pribadi Mahasiswa</h3>
    <div class="profile-container">
        <div class="profile-details">
            <div class="col">
                <div class="label">NIM (Nomor Induk Mahasiswa)</div>
                <div class="val">{{ $mahasiswa->nim }}</div>
            </div>
            <div class="col">
                <div class="label">Nama Lengkap</div>
                <div class="val">{{ $mahasiswa->nama }}</div>
            </div>
            <div class="col">
                <div class="label">Kelas / Rombel</div>
                <div class="val">{{ $mahasiswa->kelas }}</div>
            </div>
            <div class="col">
                <div class="label">Terdaftar Sejak</div>
                <div class="val">{{ $mahasiswa->created_at->format('d M Y') }}</div>
            </div>
            <div class="col">
                <div class="label">Status Akademik Terakhir</div>
                <div class="val">Aktif</div>
            </div>
            <div class="col">
                <div class="label">IPK Terakhir</div>
                <div class="val">{{ count($ipkList) > 0 ? number_format($ipkList->first()->ipk, 2) : '-' }}</div>
            </div>
        </div>
    </div>

    <!-- HKI -->
    <h3 class="section-title">B. Hak Kekayaan Intelektual (HKI)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 40%;">Judul Ciptaan / HKI</th>
                <th style="width: 20%;">Jenis Ciptaan</th>
                <th style="width: 20%;">Nomor Permohonan</th>
                <th style="width: 15%;">Tgl Permohonan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa->hki as $index => $hki)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $hki->judul_ciptaan }}</td>
                    <td class="text-center">{{ $hki->jenis_ciptaan }}</td>
                    <td class="text-center">{{ $hki->no_permohonan ?? '-' }}</td>
                    <td class="text-center">{{ $hki->tgl_permohonan ? \Carbon\Carbon::parse($hki->tgl_permohonan)->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada data HKI.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- PRESTASI -->
    <h3 class="section-title">C. Prestasi & Penghargaan</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 40%;">Nama Prestasi / Kejuaraan</th>
                <th style="width: 20%;">Level / Tingkat</th>
                <th style="width: 15%;">Prestasi Diraih</th>
                <th style="width: 20%;">Tahun (TS)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa->prestasi as $index => $prestasi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $prestasi->nama_prestasi }}</td>
                    <td class="text-center">{{ $prestasi->level_prestasi }}</td>
                    <td class="text-center">{{ $prestasi->prestasi_diraih }}</td>
                    <td class="text-center">{{ optional($prestasi->ts)->tahun_akademik ?? $prestasi->tahun }}</td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada riwayat prestasi perlombaan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- SERTIFIKASI KOMPETENSI -->
    <h3 class="section-title">D. Sertifikasi Kompetensi</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 75%;">Skema Sertifikasi</th>
                <th style="width: 20%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sertifikasiList as $index => $sert)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $sert->skema_serkom }}</td>
                    <td class="text-center">
                        @if($sert->link_dokumen)
                            <a href="{{ $sert->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="3">Belum ada data sertifikasi kompetensi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKAM AKADEMIK & IPK -->
    <h3 class="section-title">E. Indeks Prestasi Kumulatif (IPK)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Tahun Akademik</th>
                <th style="width: 25%;">IPK</th>
                <th style="width: 25%;">Status Akademik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ipkList as $index => $ipk)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $ipk->ts ? $ipk->ts->tahun_sekarang : '-' }}</td>
                    <td class="text-center">{{ number_format($ipk->ipk, 2) }}</td>
                    <td class="text-center">Aktif</td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="4">Belum ada data nilai/IPK tercatat.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ORGANISASI -->
    <h3 class="section-title">F. Organisasi Mahasiswa</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Organisasi</th>
                <th style="width: 20%;">Jabatan</th>
                <th style="width: 20%;">Tahun</th>
                <th style="width: 20%;">Link Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa->organisasi as $index => $org)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $org->nama_organisasi }}</td>
                    <td class="text-center">{{ $org->jabatan }}</td>
                    <td class="text-center">{{ $org->periode ?? '-' }}</td>
                    <td class="text-center">
                        @if($org->link_dokumen)
                            <a href="{{ $org->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada riwayat organisasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- TUGAS MANDIRI / KELOMPOK -->
    <h3 class="section-title">G. Tugas Mandiri & Kelompok</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Matakuliah</th>
                <th style="width: 35%;">Judul Tugas</th>
                <th style="width: 25%;">Link Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tugasList as $index => $tugas)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ optional($tugas->matakuliah)->nama_matakuliah ?? '-' }}</td>
                    <td>{{ $tugas->judul_tugas }}</td>
                    <td class="text-center">
                        @if($tugas->link_dokumen)
                            <a href="{{ $tugas->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="4">Belum ada data tugas mandiri / kelompok.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- CAPSTONE -->
    <h3 class="section-title">H. Proyek Capstone</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Judul Capstone</th>
                <th style="width: 25%;">Kategori</th>
                <th style="width: 20%;">Peran</th>
                <th style="width: 15%;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($capstoneList as $index => $capstone)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $capstone->judul_capstone }}</td>
                    <td class="text-center">{{ $capstone->kategori_capstone }}</td>
                    <td class="text-center">{{ $capstone->nim == $mahasiswa->nim ? 'Ketua' : 'Anggota' }}</td>
                    <td class="text-center">{{ $capstone->nilai_akhir ?? '-' }}</td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada data proyek capstone.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- BEASISWA -->
    <h3 class="section-title">I. Beasiswa Mahasiswa</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Jenis Beasiswa</th>
                <th style="width: 50%;">Kategori Beasiswa</th>
                <th style="width: 20%;">Link Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa->beasiswas as $index => $beasiswa)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ ucfirst($beasiswa->jenis_beasiswa) }}</td>
                    <td>{{ $beasiswa->kategori_beasiswa }}</td>
                    <td class="text-center">
                        @if($beasiswa->link_dokumen)
                            <a href="{{ $beasiswa->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="4">Belum ada data beasiswa.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER SIGNATURE -->
    <div class="footer">
        <p>Pontianak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p style="margin-bottom: 70px;">Mengetahui,<br>Mahasiswa Yang Bersangkutan</p>
        <p style="font-weight: bold; text-decoration: underline;">
            {{ $mahasiswa->nama }}
        </p>
        <p>NIM. {{ $mahasiswa->nim }}</p>
    </div>
</body>
</html>
