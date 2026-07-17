@extends('layouts.app')

@section('title', 'Detail Hibah Penelitian')

@section('content')
<style>
    @media print {
        @page {
            size: portrait;
            margin: 20mm;
        }
        body {
            font-size: 12px;
            color: #000;
            background: #fff;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: transparent !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
            padding-left: 0 !important;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #000 !important;
        }
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Detail Hibah Penelitian</h4>
    <h5 class="fw-bold">Program Studi Sistem Informasi Akuntansi (D3) UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
    <h1>Detail Hibah Penelitian</h1>
    <div>
        <button type="button" onclick="window.print()" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</button>
        <a href="{{ route('hibah-penelitian.edit', $hibahPenelitian) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('hibah-penelitian.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Info Utama -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Informasi Penelitian</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <tr>
                        <th width="30%">Judul Penelitian</th>
                        <td class="fw-bold text-dark">{{ $hibahPenelitian->judul }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Hibah</th>
                        <td>
                            <span class="badge bg-{{ $hibahPenelitian->jenis_hibah == 'internal' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($hibahPenelitian->jenis_hibah) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Skema Hibah</th>
                        <td>{{ $hibahPenelitian->skema_hibah }}</td>
                    </tr>
                    <tr>
                        <th>Tema Hibah</th>
                        <td>{{ $hibahPenelitian->tema_hibah }}</td>
                    </tr>
                    <tr>
                        <th>Topik Hibah</th>
                        <td>{{ $hibahPenelitian->topik_hibah }}</td>
                    </tr>
                    <tr>
                        <th>Biaya / Dana</th>
                        <td class="fw-bold text-success" style="font-size: 1.1rem;">
                            Rp {{ number_format($hibahPenelitian->biaya, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Pemberi Hibah</th>
                        <td>{{ $hibahPenelitian->pemberi_hibah }}</td>
                    </tr>
                    <tr>
                        <th>TA (Tahun Akademik)</th>
                        <td>{{ $hibahPenelitian->ts->tahun_sekarang ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Pelaksana & Dokumen -->
    <div class="col-md-4">
        <!-- Card Pelaksana -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Pelaksana</h5>
            </div>
            <div class="card-body bg-light">
                <div class="mb-3 pb-3 border-bottom border-secondary-subtle">
                    <span class="text-muted small d-block mb-1 fw-bold">Dosen Pengaju / Anggota</span>
                    @php
                        $kodes = explode(', ', $hibahPenelitian->kode_dosen);
                        $namas = explode(', ', $hibahPenelitian->nama_dosen);
                    @endphp
                    @foreach ($kodes as $idx => $kode)
                        <div class="mb-2 p-2 bg-white rounded border border-light">
                            <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $namas[$idx] ?? '' }}</strong>
                            <span class="badge bg-dark mt-1">Kode: {{ $kode }}</span>
                        </div>
                    @endforeach
                </div>
                <div>
                    <span class="text-muted small d-block mb-1 fw-bold">Mahasiswa Terlibat</span>
                    @if ($hibahPenelitian->nim_mhs)
                        @php
                            $nims = explode(', ', $hibahPenelitian->nim_mhs);
                            $mhsNamas = explode(', ', $hibahPenelitian->nama_mahasiswa);
                        @endphp
                        @foreach ($nims as $idx => $nim)
                            <div class="mb-2 p-2 bg-white rounded border border-light">
                                <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $mhsNamas[$idx] ?? '' }}</strong>
                                <span class="badge bg-secondary mt-1">NIM: {{ $nim }}</span>
                            </div>
                        @endforeach
                    @else
                        <span class="text-muted italic small">Tidak melibatkan mahasiswa</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Dokumen Pendukung -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-file-earmark-text-fill me-2"></i>Dokumen Pendukung</h5>
            </div>
            <div class="card-body">
                @if (session('success') && session('success') == 'Dokumen berhasil ditambahkan.')
                    <div class="alert alert-success d-print-none small py-2 mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="list-group">
                    @php
                        $docs = [
                            'Proposal' => ['link' => $hibahPenelitian->link_proposal, 'field' => 'link_proposal', 'icon' => 'file-earmark-medical', 'color' => 'primary'],
                            'Surat Tugas (ST)' => ['link' => $hibahPenelitian->link_st, 'field' => 'link_st', 'icon' => 'file-earmark-check', 'color' => 'info'],
                            'SPK (Perjanjian)' => ['link' => $hibahPenelitian->link_spk, 'field' => 'link_spk', 'icon' => 'file-earmark-ruled', 'color' => 'warning'],
                            'Luaran Penelitian' => ['link' => $hibahPenelitian->link_luaran, 'field' => 'link_luaran', 'icon' => 'journal-bookmark', 'color' => 'success'],
                            'Laporan Kemajuan/Akhir' => ['link' => $hibahPenelitian->link_laporan, 'field' => 'link_laporan', 'icon' => 'file-earmark-bar-graph', 'color' => 'danger'],
                            'Press Release' => ['link' => $hibahPenelitian->link_persentasi, 'field' => 'link_persentasi', 'icon' => 'easel2', 'color' => 'dark'],
                        ];
                    @endphp

                    @foreach ($docs as $label => $data)
                        @if ($data['link'])
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                                <div>
                                    <i class="bi bi-{{ $data['icon'] }} text-{{ $data['color'] }} me-2" style="font-size: 1.2rem;"></i>
                                    <span class="text-dark fw-semibold">{{ $label }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $data['link'] }}" target="_blank" class="btn btn-sm btn-{{ $data['color'] }} rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                    </a>
                                    <form action="{{ route('hibah-penelitian.update-document', $hibahPenelitian) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokumen {{ $label }}?')">
                                        @csrf
                                        <input type="hidden" name="field_name" value="{{ $data['field'] }}">
                                        <input type="hidden" name="link_value" value="">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Dokumen" style="width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 bg-light">
                                <div class="text-muted">
                                    <i class="bi bi-{{ $data['icon'] }} me-2" style="font-size: 1.2rem;"></i>
                                    <span>{{ $label }}</span>
                                </div>
                                <button type="button" onclick="openAddDocumentModal('{{ $data['field'] }}', '{{ $label }}')" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
                                </button>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="tambahDokumenModal" tabindex="-1" aria-labelledby="tambahDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('hibah-penelitian.update-document', $hibahPenelitian) }}" method="POST">
                @csrf
                <input type="hidden" name="field_name" id="modal_field_name">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahDokumenModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Dokumen <span id="modal_document_label"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_link_value" class="form-label fw-semibold">Link Dokumen (URL) <span class="text-danger">*</span></label>
                        <input type="url" name="link_value" id="modal_link_value" class="form-control" required placeholder="https://example.com/dokumen-anda">
                        <div class="form-text text-muted">Masukkan URL/link valid (harus diawali dengan http:// atau https://).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddDocumentModal(fieldName, label) {
        document.getElementById('modal_field_name').value = fieldName;
        document.getElementById('modal_document_label').innerText = label;
        document.getElementById('modal_link_value').value = '';
        var myModal = new bootstrap.Modal(document.getElementById('tambahDokumenModal'));
        myModal.show();
    }
</script>
@endpush
@endsection
