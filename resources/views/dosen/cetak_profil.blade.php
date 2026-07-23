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
            <p>Buku Profil & Portofolio Dosen Tetap (DTPR)</p>
        </div>
    </div>

    <!-- PROFILE DATA -->
    <h3 class="section-title">A. Data Pribadi Dosen</h3>
    <div class="profile-container">
        <div class="profile-photo">
            @if($dosen->foto)
                <img src="{{ asset('storage/' . $dosen->foto) }}" alt="Foto">
            @else
                <div class="placeholder">Tanpa Foto</div>
            @endif
        </div>
        <div class="profile-details">
            <div class="col">
                <div class="label">Kode Dosen</div>
                <div class="val">{{ $dosen->kode_dosen }}</div>
            </div>
            <div class="col">
                <div class="label">Nama Lengkap</div>
                <div class="val">{{ $dosen->nama_dosen }}</div>
            </div>
            <div class="col">
                <div class="label">NIDN</div>
                <div class="val">{{ $dosen->nidn ?? '-' }}</div>
            </div>
            <div class="col">
                <div class="label">Pendidikan Terakhir</div>
                <div class="val">{{ $dosen->pendidikan ?? '-' }}</div>
            </div>
            <div class="col">
                <div class="label">Jabatan Fungsional (JFA)</div>
                <div class="val">{{ $dosen->jfa ?? '-' }}</div>
            </div>
            <div class="col">
                <div class="label">Golongan / Kepangkatan</div>
                <div class="val">{{ $dosen->kepangkatan ?? '-' }}</div>
            </div>
            <div class="col">
                <div class="label">Status Sertifikasi Dosen</div>
                <div class="val">{{ $dosen->keterangan_serdos ?? '-' }}</div>
            </div>
            <div class="col">
                <div class="label">Homebase</div>
                <div class="val">{{ $dosen->homebase_dosen ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- PENELITIAN -->
    <h3 class="section-title">B. Publikasi Penelitian</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Judul Penelitian</th>
                <th style="width: 20%;">Peran</th>
                <th style="width: 15%;">Jenis Publikasi</th>
                <th style="width: 10%;">Tahun Akademik (TA)</th>
                <th style="width: 15%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penelitianList as $index => $penelitian)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $penelitian->nama_jurnal }}</td>
                    <td class="text-center">{{ $penelitian->jenis_penelitian }}</td>
                    <td class="text-center">{{ $penelitian->jenis_jurnal }}</td>
                    <td class="text-center">{{ optional($penelitian->ts)->tahun_sekarang ?? '-' }}</td>
                    <td class="text-center">
                        @if($penelitian->link_jurnal)
                            <a href="{{ $penelitian->link_jurnal }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data publikasi penelitian.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- PENGABDIAN (PKM) -->
    <h3 class="section-title">C. Pengabdian Kepada Masyarakat (PkM)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Tema PkM</th>
                <th style="width: 20%;">Mitra</th>
                <th style="width: 15%;">Sumber Dana</th>
                <th style="width: 10%;">Tahun Akademik (TA)</th>
                <th style="width: 15%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pkmList as $index => $pkm)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pkm->tema_pkm }}</td>
                    <td class="text-center">{{ $pkm->mitra }}</td>
                    <td class="text-center">{{ $pkm->sumber_dana ?? '-' }}</td>
                    <td class="text-center">{{ optional($pkm->ts)->tahun_sekarang ?? '-' }}</td>
                    <td class="text-center">
                        @if($pkm->proposal)
                            <a href="{{ asset('storage/' . $pkm->proposal) }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data PkM.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- HIBAH PENELITIAN / PKM -->
    <h3 class="section-title">D. Hibah Penelitian / PkM</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Judul Hibah</th>
                <th style="width: 15%;">Jenis Hibah</th>
                <th style="width: 20%;">Pemberi Hibah / Mitra</th>
                <th style="width: 10%;">Tahun Akademik (TA)</th>
                <th style="width: 15%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hibahList as $index => $hibah)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $hibah->judul }}</td>
                    <td class="text-center">{{ $hibah->jenis_hibah }}</td>
                    <td class="text-center">{{ $hibah->pemberi_hibah }}</td>
                    <td class="text-center">{{ optional($hibah->ts)->tahun_sekarang ?? '-' }}</td>
                    <td class="text-center">
                        @if($hibah->proposal)
                            <a href="{{ asset('storage/' . $hibah->proposal) }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data hibah penelitian / PkM.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- HKI DOSEN -->
    <h3 class="section-title">E. Hak Kekayaan Intelektual (HKI)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Judul Ciptaan / HKI</th>
                <th style="width: 20%;">Jenis Ciptaan</th>
                <th style="width: 15%;">Nomor Permohonan</th>
                <th style="width: 15%;">Tgl Permohonan</th>
                <th style="width: 15%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hkis as $index => $hki)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $hki->judul_ciptaan }}</td>
                    <td class="text-center">{{ $hki->jenis_ciptaan }}</td>
                    <td class="text-center">{{ $hki->no_permohonan ?? '-' }}</td>
                    <td class="text-center">{{ $hki->tgl_permohonan ? \Carbon\Carbon::parse($hki->tgl_permohonan)->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        @if($hki->link_dokumen)
                            <a href="{{ $hki->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada produk Hak Kekayaan Intelektual.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKOGNISI DOSEN -->
    <h3 class="section-title">F. Rekognisi / Pengakuan Dosen</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 40%;">Bentuk Rekognisi / Nama Pengakuan</th>
                <th style="width: 20%;">Tingkat</th>
                <th style="width: 15%;">Tahun Akademik (TA)</th>
                <th style="width: 20%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekognisiList as $index => $rekognisi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $rekognisi->nama_rekognisi }}
                        @if($rekognisi->hki_id && $rekognisi->hki && $rekognisi->hki->mahasiswa)
                            <br><small style="color: #6c757d;">(Bersama Mhs: {{ $rekognisi->hki->mahasiswa->nama }})</small>
                        @elseif($rekognisi->penelitian_dosen_id && $rekognisi->penelitianDosen && $rekognisi->penelitianDosen->nama_mahasiswa)
                            <br><small style="color: #6c757d;">(Bersama Mhs: {{ $rekognisi->penelitianDosen->nama_mahasiswa }})</small>
                        @elseif($rekognisi->hibah_penelitian_id && $rekognisi->hibahPenelitian && $rekognisi->hibahPenelitian->nama_mahasiswa)
                            <br><small style="color: #6c757d;">(Bersama Mhs: {{ $rekognisi->hibahPenelitian->nama_mahasiswa }})</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $rekognisi->level }}</td>
                    <td class="text-center">{{ optional($rekognisi->ts)->tahun_sekarang ?? $rekognisi->tahun ?? '-' }}</td>
                    <td class="text-center">
                        @if($rekognisi->link_dokumen)
                            <a href="{{ $rekognisi->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada data rekognisi dosen.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- KEIKUTSERTAAN KEGIATAN -->
    <h3 class="section-title">G. Keikutsertaan Kegiatan</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Kegiatan</th>
                <th style="width: 25%;">Jenis Kegiatan Tri Dharma</th>
                <th style="width: 20%;">Tahun & Penyelenggara</th>
                <th style="width: 15%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosen->kegiatan as $index => $kegiatan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $kegiatan->nama_kegiatan }}</td>
                    <td class="text-center">{{ $kegiatan->jenis }}</td>
                    <td class="text-center">
                        {{ optional($kegiatan->ts)->tahun_sekarang ?? $kegiatan->tahun ?? '-' }}<br>
                        <small>{{ $kegiatan->penyelenggara ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                        @if($kegiatan->link_dokumen)
                            <a href="{{ $kegiatan->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada data keikutsertaan kegiatan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- SERTIFIKASI KOMPETENSI / PROFESI -->
    <h3 class="section-title">H. Sertifikasi Kompetensi / Profesi Dosen</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Skema / Sertifikasi</th>
                <th style="width: 20%;">Lembaga Penerbit</th>
                <th style="width: 15%;">Bidang Keahlian</th>
                <th style="width: 15%;">Tingkat</th>
                <th style="width: 20%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosen->sertifikasi as $index => $sert)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $sert->nama_sertifikasi }}</td>
                    <td class="text-center">{{ $sert->penerbit }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">
                        @if($sert->link_dokumen)
                            <a href="{{ $sert->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data sertifikasi dosen.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- PRESTASI -->
    <h3 class="section-title">I. Prestasi & Penghargaan</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Prestasi / Kejuaraan</th>
                <th style="width: 20%;">Tingkat</th>
                <th style="width: 15%;">Prestasi Diraih</th>
                <th style="width: 15%;">Tahun Akademik (TA)</th>
                <th style="width: 15%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosen->prestasi as $index => $prestasi)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $prestasi->nama_prestasi }}</td>
                    <td class="text-center">{{ $prestasi->level_prestasi ?? $prestasi->tingkat ?? '-' }}</td>
                    <td class="text-center">{{ $prestasi->prestasi_diraih ?? $prestasi->prestasi ?? '-' }}</td>
                    <td class="text-center">{{ optional($prestasi->ts)->tahun_sekarang ?? $prestasi->tahun ?? '-' }}</td>
                    <td class="text-center">
                        @if($prestasi->link_dokumen)
                            <a href="{{ $prestasi->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @elseif($prestasi->berkas_sertifikat)
                            <a href="{{ asset('storage/' . $prestasi->berkas_sertifikat) }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="6">Belum ada data prestasi perlombaan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- KEANGGOTAAN ORGANISASI -->
    <h3 class="section-title">J. Keanggotaan Organisasi / Profesi</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 40%;">Nama Organisasi</th>
                <th style="width: 20%;">Peran / Jabatan</th>
                <th style="width: 15%;">Tahun Akademik (TA)</th>
                <th style="width: 20%;">Dokumen / Link</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keanggotaanList as $index => $keanggotaan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $keanggotaan->nama_rekognisi }}</td>
                    <td class="text-center">{{ $keanggotaan->level }}</td>
                    <td class="text-center">{{ optional($keanggotaan->ts)->tahun_sekarang ?? $keanggotaan->tahun ?? '-' }}</td>
                    <td class="text-center">
                        @if($keanggotaan->link_dokumen)
                            <a href="{{ $keanggotaan->link_dokumen }}" target="_blank" style="text-decoration: none; color: #4f46e5;">Lihat Dokumen</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr class="empty-row"><td colspan="5">Belum ada data keanggotaan organisasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER SIGNATURE -->
    <div class="footer">
        <p>Pontianak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p style="margin-bottom: 10px;">Mengetahui,<br>Dosen yang bersangkutan,</p>
        <div style="margin-bottom: 10px;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('portal.dosen.profil', $dosen->id)) }}" alt="QR Code" style="width: 90px; height: 90px; display: inline-block;">
        </div>
        <p style="font-weight: bold; text-decoration: underline; margin-top: 5px;">
            {{ $dosen->nama_dosen }}
        </p>
        @if($dosen->nidn)
        <p style="font-size: 0.9rem; margin-top: -3px;">NIDN: {{ $dosen->nidn }}</p>
        @endif
    </div>
</body>
</html>
