@extends('layouts.app')

@section('title', 'Detail Penelitian Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail Penelitian Dosen</h1>
    <div>
        <a href="{{ route('penelitian-dosen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        <a href="{{ route('penelitian-dosen.edit', $penelitianDosen) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Pelaksana & Anggota -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Tim Peneliti & Pelaksana</h5>
            </div>
            <div class="card-body bg-light">
                <!-- Dosen Pelaksana -->
                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted small d-block mb-2 fw-bold text-uppercase">Dosen Pelaksana</span>
                    @php
                        $kodes = explode(', ', $penelitianDosen->kode_dosen);
                        $namas = explode(', ', $penelitianDosen->nama_dosen);
                    @endphp
                    @foreach ($kodes as $idx => $kode)
                        <div class="p-2 bg-white rounded border border-light mb-2 shadow-sm">
                            <strong class="text-dark d-block">{{ $namas[$idx] ?? '' }}</strong>
                            <span class="badge bg-dark mt-1">Kode: {{ $kode }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Anggota Mahasiswa -->
                <div class="mb-3 pb-3 border-bottom">
                    <span class="text-muted small d-block mb-2 fw-bold text-uppercase">Anggota Mahasiswa</span>
                    @if ($penelitianDosen->nim_mhs)
                        @php
                            $nims = explode(', ', $penelitianDosen->nim_mhs);
                            $mhsNamas = explode(', ', $penelitianDosen->nama_mahasiswa);
                        @endphp
                        @foreach ($nims as $idx => $nim)
                            <div class="p-2 bg-white rounded border border-light mb-2 shadow-sm">
                                <strong class="text-dark d-block">{{ $mhsNamas[$idx] ?? '' }}</strong>
                                <span class="badge bg-secondary mt-1">NIM: {{ $nim }}</span>
                            </div>
                        @endforeach
                    @else
                        <span class="text-muted italic small d-block">Tidak ada anggota mahasiswa</span>
                    @endif
                </div>

                <!-- Anggota Mitra -->
                <div>
                    <span class="text-muted small d-block mb-2 fw-bold text-uppercase">Anggota Mitra</span>
                    @if ($penelitianDosen->anggota_mitra)
                        @php
                            $mitras = explode(', ', $penelitianDosen->anggota_mitra);
                        @endphp
                        @foreach ($mitras as $mitra)
                            <div class="p-2 bg-white rounded border border-light mb-2 shadow-sm">
                                <strong class="text-dark d-block"><i class="bi bi-building me-1"></i> {{ $mitra }}</strong>
                            </div>
                        @endforeach
                    @else
                        <span class="text-muted italic small d-block">Tidak ada anggota mitra</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Informasi Jurnal -->
    <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-journal-bookmark-fill me-2"></i>Detail Jurnal & Publikasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0 align-middle">
                    <tr>
                        <th style="width: 35%;">Jenis Jurnal</th>
                        <td><span class="badge bg-primary">{{ $penelitianDosen->jenis_jurnal }}</span></td>
                    </tr>
                    <tr>
                        <th>Jenis Penelitian</th>
                        <td>{{ $penelitianDosen->jenis_penelitian }}</td>
                    </tr>
                    <tr>
                        <th>Nama Jurnal</th>
                        <td class="fw-bold">{{ $penelitianDosen->nama_jurnal }}</td>
                    </tr>
                    <tr>
                        <th>Tahun / TA</th>
                        <td>{{ $penelitianDosen->ts->tahun_sekarang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Link Jurnal</th>
                        <td>
                            @if ($penelitianDosen->link_jurnal)
                                <a href="{{ $penelitianDosen->link_jurnal }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-up-right me-1"></i> Kunjungi Jurnal</a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td class="text-muted small">{{ $penelitianDosen->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td class="text-muted small">{{ $penelitianDosen->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Baris Dokumen Pendukung -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-link-45deg me-2"></i>Tautan Berkas Pendukung</h5>
            </div>
            <div class="card-body">
                @if (session('success') && (session('success') == 'Dokumen berhasil ditambahkan.' || session('success') == 'Dokumen berhasil dihapus.'))
                    <div class="alert alert-success d-print-none small py-2 mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row text-center">
                    @php
                        $docs = [
                            'Sertifikat' => ['link' => $penelitianDosen->berkas_sertifikat, 'field' => 'berkas_sertifikat', 'color' => 'success', 'icon' => 'patch-check-fill'],
                            'Paper / Artikel' => ['link' => $penelitianDosen->berkas_paper, 'field' => 'berkas_paper', 'color' => 'primary', 'icon' => 'file-earmark-text-fill'],
                            'Proposal' => ['link' => $penelitianDosen->proposal, 'field' => 'proposal', 'color' => 'info', 'icon' => 'file-earmark-medical-fill'],
                            'Laporan Akhir' => ['link' => $penelitianDosen->laporan, 'field' => 'laporan', 'color' => 'warning', 'icon' => 'file-earmark-check-fill'],
                            'Lainnya' => ['link' => $penelitianDosen->lainnya, 'field' => 'lainnya', 'color' => 'secondary', 'icon' => 'folder-fill'],
                        ];
                    @endphp

                    @foreach ($docs as $label => $data)
                        <div class="col-md-2 col-sm-4 mb-3">
                            <div class="border rounded p-3 h-100 d-flex flex-column justify-content-between bg-light shadow-sm">
                                <span class="fw-bold d-block small mb-2 text-dark">{{ $label }}</span>
                                @if ($data['link'])
                                    <div class="my-3 text-{{ $data['color'] }} fs-2"><i class="bi bi-{{ $data['icon'] }}"></i></div>
                                    <div class="d-flex gap-1 mt-auto">
                                        <a href="{{ $data['link'] }}" target="_blank" class="btn btn-sm btn-{{ $data['color'] }} w-100"><i class="bi bi-box-arrow-up-right"></i> Buka</a>
                                        <form action="{{ route('penelitian-dosen.update-document', $penelitianDosen) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berkas {{ $label }}?')">
                                            @csrf
                                            <input type="hidden" name="field_name" value="{{ $data['field'] }}">
                                            <input type="hidden" name="link_value" value="">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" style="height: 31px; padding: 4px 8px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="my-3 text-muted fs-2" style="opacity: 0.5;"><i class="bi bi-file-earmark-plus"></i></div>
                                    <button type="button" onclick="openAddDocumentModal('{{ $data['field'] }}', '{{ $label }}')" class="btn btn-sm btn-outline-success w-100 mt-auto">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                @endif
                            </div>
                        </div>
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
            <form action="{{ route('penelitian-dosen.update-document', $penelitianDosen) }}" method="POST">
                @csrf
                <input type="hidden" name="field_name" id="modal_field_name">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahDokumenModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Berkas <span id="modal_document_label"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_link_value" class="form-label fw-semibold">Link Berkas (URL) <span class="text-danger">*</span></label>
                        <input type="url" name="link_value" id="modal_link_value" class="form-control" required placeholder="https://example.com/berkas-anda">
                        <div class="form-text text-muted">Masukkan URL/link valid (harus diawali dengan http:// atau https://).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Berkas</button>
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
