@extends('layouts.app')

@section('title', 'Data Hibah Penelitian')

@section('content')
<style>
    /* Styling khusus cetak */
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
        }
        body {
            font-size: 11px;
            color: #000;
            background: #fff;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 10px;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 4px 5px !important;
            vertical-align: middle !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .table th {
            background-color: #212529 !important;
            color: #fff !important;
            text-align: center !important;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9 !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef !important;
        }
        .col-lg-9, .col-md-8 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Hibah Penelitian Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="row">
    <!-- Kiri: Statistik (d-print-none) -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Hibah</h5>
            </div>
            <div class="card-body">
                <!-- Total Hibah -->
                <div class="mb-3 text-center py-3 text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5) !important; border-radius: 14px; box-shadow: 0 8px 24px rgba(99, 102, 241, 0.2); position: relative; overflow: hidden;">
                    <span class="text-white-50 small d-block mb-1 fw-bold">Total Hibah</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalHibah }}</h2>
                    <div style="position: absolute; top: -30%; right: -20%; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%;"></div>
                </div>

                <!-- Total Dana Hibah -->
                <div class="mb-4 text-center py-3 text-white" style="background: linear-gradient(135deg, #10b981, #059669) !important; border-radius: 14px; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2); position: relative; overflow: hidden;">
                    <span class="text-white-50 small d-block mb-1 fw-bold">Total Dana Hibah</span>
                    <h5 class="fw-bold mb-0 text-white" style="font-size: 1.15rem; letter-spacing: -0.5px;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h5>
                    <div style="position: absolute; top: -30%; right: -20%; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%;"></div>
                </div>

                <!-- Berdasarkan Jenis -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Jenis</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                            <span>Internal</span>
                            <span class="badge bg-primary rounded-pill">{{ $jenisCounts['internal'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                            <span>Eksternal</span>
                            <span class="badge bg-secondary rounded-pill">{{ $jenisCounts['eksternal'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Berdasarkan TS -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan TS</span>
                    </div>
                    <div class="ps-1" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($labelTsStats as $labelTs => $stat)
                            <div class="py-2 border-bottom border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-dark fw-semibold">{{ $labelTs }}</span>
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $stat['count'] }} Hibah</span>
                                </div>
                                <div class="text-warning-emphasis small fw-bold mt-1" style="font-size: 11px;">
                                    Rp {{ number_format($stat['total_biaya'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data TS</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan TA -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info text-dark rounded p-1 px-2 me-2">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan TA</span>
                    </div>
                    <div class="ps-1" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($tsStats as $tsName => $stat)
                            <div class="py-2 border-bottom border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-semibold">{{ $tsName }}</span>
                                    <span class="badge bg-info text-dark rounded-pill">{{ $stat['count'] }} Hibah</span>
                                </div>
                                <div class="text-info small fw-bold mt-1" style="font-size: 11px;">
                                    Rp {{ number_format($stat['total_biaya'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan Dosen -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Dosen</span>
                    </div>
                    <div class="ps-1" style="max-height: 220px; overflow-y: auto;">
                        @forelse ($dosenStats as $dosenName => $stat)
                            <div class="py-2 border-bottom border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-truncate fw-semibold text-dark" style="max-width: 150px;" title="{{ $dosenName }}">{{ $dosenName }}</span>
                                    <span class="badge bg-success rounded-pill">{{ $stat['count'] }} Hibah</span>
                                </div>
                                <div class="text-success small fw-bold mt-1" style="font-size: 11px;">
                                    Rp {{ number_format($stat['total_biaya'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan Mahasiswa -->
                <div class="mb-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Mahasiswa</span>
                    </div>
                    <div class="ps-1" style="max-height: 220px; overflow-y: auto;">
                        @forelse ($mhsStats as $mhsName => $stat)
                            <div class="py-2 border-bottom border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-truncate fw-semibold text-dark" style="max-width: 150px;" title="{{ $mhsName }}">{{ $mhsName }}</span>
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $stat['count'] }} Hibah</span>
                                </div>
                                <div class="text-warning small fw-bold mt-1" style="font-size: 11px;">
                                    Rp {{ number_format($stat['total_biaya'], 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
            <div>
                <h1 class="mb-0">Data Hibah Penelitian</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $totalHibah }}</strong> hibah terdaftar</p>
            </div>
        </div>

        <div class="card mb-3 d-print-none shadow-sm border-0">
            <div class="card-body py-2">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari dosen, mahasiswa, judul, pemberi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="per_page" class="form-select" onchange="this.form.submit()" title="Tampilkan baris">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 Baris</option>
                        </select>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="submit" class="btn btn-primary me-1"><i class="bi bi-search"></i> Cari</button>
                        <a href="{{ route('hibah-penelitian.index', array_merge(request()->query(), ['print' => 'all'])) }}" target="_blank" class="btn btn-success me-1"><i class="bi bi-printer"></i> Cetak</a>
                        <button type="button" class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#tambahHibahModal">
                            <i class="bi bi-plus-circle"></i> Tambah Hibah
                        </button>
                        @if (request('search') || request('per_page'))
                            <a href="{{ route('hibah-penelitian.index') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger d-print-none">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success d-print-none">{{ session('success') }}</div>
        @endif

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jenis</th>
                        <th>Dosen</th>
                        <th>Mahasiswa</th>
                        <th>Skema & Judul</th>
                        <th class="text-end">Biaya</th>
                        <th>Pemberi & TA</th>
                        <th class="text-center d-print-none">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hibah as $item)
                        <tr>
                            <td class="text-center">{{ $hibah instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($hibah->currentPage() - 1) * $hibah->perPage() + $loop->iteration : $loop->iteration }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $item->jenis_hibah == 'internal' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($item->jenis_hibah) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $kodes = explode(', ', $item->kode_dosen);
                                    $namas = explode(', ', $item->nama_dosen);
                                @endphp
                                @foreach ($kodes as $idx => $kode)
                                    <div class="mb-1">
                                        <strong class="text-dark">{{ $namas[$idx] ?? '' }}</strong>
                                        <div class="text-muted small">Kode: {{ $kode }}</div>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if ($item->nim_mhs)
                                    @php
                                        $nims = explode(', ', $item->nim_mhs);
                                        $mhsNamas = explode(', ', $item->nama_mahasiswa);
                                    @endphp
                                    @foreach ($nims as $idx => $nim)
                                        <div class="mb-1">
                                            <strong class="text-dark">{{ $mhsNamas[$idx] ?? '' }}</strong>
                                            <div class="text-muted small">NIM: {{ $nim }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="badge bg-dark mb-1">{{ $item->skema_hibah }}</div>
                                <div class="fw-bold" style="max-width: 250px; white-space: normal;">{{ $item->judul }}</div>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($item->biaya, 0, ',', '.') }}
                            </td>
                            <td>
                                <div>{{ $item->pemberi_hibah }}</div>
                                <div class="text-muted small">TA: {{ $item->ts ? ($item->ts->label_ts ? $item->ts->label_ts . ' (' . $item->ts->tahun_sekarang . ')' : $item->ts->tahun_sekarang) : '-' }}</div>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hibah-penelitian.show', $item) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                    <a href="{{ route('hibah-penelitian.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('hibah-penelitian.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data hibah penelitian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($hibah instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center d-print-none mt-3">
                {{ $hibah->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Hibah Penelitian -->
<div class="modal fade" id="tambahHibahModal" tabindex="-1" aria-labelledby="tambahHibahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('hibah-penelitian.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahHibahModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Hibah Penelitian</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Baris 1: Jenis, TA, Pemberi -->
                        <div class="col-md-4 mb-3">
                            <label for="modal_jenis_hibah" class="form-label">Jenis Hibah <span class="text-danger">*</span></label>
                            <select name="jenis_hibah" id="modal_jenis_hibah" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="internal">Internal</option>
                                <option value="eksternal">Eksternal</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_ts_id" class="form-label">TA <span class="text-danger">*</span></label>
                            <select name="ts_id" id="modal_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_pemberi_hibah" class="form-label">Pemberi Hibah <span class="text-danger">*</span></label>
                            <input type="text" name="pemberi_hibah" id="modal_pemberi_hibah" class="form-control" required placeholder="Contoh: Ristekdikti, UBSI, dsb">
                        </div>

                        <!-- Section: Dosen (Multiple) -->
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-bold">Dosen Pengaju / Anggota <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-dosen"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
                            </div>
                            <div id="dosen-rows-container">
                                <div class="row g-2 mb-2 dosen-row">
                                    <div class="col-md-5">
                                        <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('hibah-penelitian.get-dosen', '') }}/" required>
                                            <option value="">-- Pilih Kode Dosen --</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->kode_dosen }}">
                                                    {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" readonly required placeholder="Nama Dosen">
                                    </div>
                                    <div class="col-md-2">
                                        <!-- Baris pertama tidak bisa dihapus -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Mahasiswa (Multiple) -->
                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-bold">Mahasiswa Terlibat (Opsional)</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-add-mahasiswa"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>
                            </div>
                            <div id="mahasiswa-rows-container">
                                <div class="row g-2 mb-2 mahasiswa-row">
                                    <div class="col-md-5">
                                        <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('hibah-penelitian.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4: Skema, Tema, Topik -->
                        <div class="col-md-4 mb-3">
                            <label for="modal_skema_hibah" class="form-label">Skema Hibah <span class="text-danger">*</span></label>
                            <input type="text" name="skema_hibah" id="modal_skema_hibah" class="form-control" required placeholder="Contoh: PDP, PKM, Penelitian Unggulan">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_tema_hibah" class="form-label">Tema Hibah <span class="text-danger">*</span></label>
                            <input type="text" name="tema_hibah" id="modal_tema_hibah" class="form-control" required placeholder="Contoh: Teknologi, Sosial Humiora">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_topik_hibah" class="form-label">Topik Hibah <span class="text-danger">*</span></label>
                            <input type="text" name="topik_hibah" id="modal_topik_hibah" class="form-control" required placeholder="Contoh: AI, Big Data, Akuntansi Syariah">
                        </div>

                        <!-- Baris 5: Judul & Biaya -->
                        <div class="col-md-8 mb-3">
                            <label for="modal_judul" class="form-label">Judul Penelitian <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="modal_judul" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_biaya" class="form-label">Biaya (Rupiah) <span class="text-danger">*</span></label>
                            <input type="number" name="biaya" id="modal_biaya" class="form-control" required min="0">
                        </div>

                        <!-- Baris 6: Dokumen Pendukung (Link) -->
                        <div class="col-md-4 mb-3">
                            <label for="modal_link_proposal" class="form-label">Link Proposal</label>
                            <input type="url" name="link_proposal" id="modal_link_proposal" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_link_st" class="form-label">Link ST (Surat Tugas)</label>
                            <input type="url" name="link_st" id="modal_link_st" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_link_spk" class="form-label">Link SPK (Perjanjian)</label>
                            <input type="url" name="link_spk" id="modal_link_spk" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_link_luaran" class="form-label">Link Luaran</label>
                            <input type="url" name="link_luaran" id="modal_link_luaran" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_link_laporan" class="form-label">Link Laporan</label>
                            <input type="url" name="link_laporan" id="modal_link_laporan" class="form-control" placeholder="https://...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_link_persentasi" class="form-label">Link press Release</label>
                            <input type="url" name="link_persentasi" id="modal_link_persentasi" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (request('print') == 'all')
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        });
    </script>
@endif

@if ($errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('tambahHibahModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
    // Fungsi untuk memasang event listener autocomplete Dosen pada baris tertentu
    function attachDosenEvents(row) {
        var select = row.querySelector('.select-kode-dosen');
        var input = row.querySelector('.input-nama-dosen');
        
        select.addEventListener('change', function() {
            var kode = this.value;
            if (kode) {
                var route = this.getAttribute('data-route');
                fetch(route + kode)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        input.value = data.nama_dosen;
                    })
                    .catch(function() {
                        input.value = '';
                    });
            } else {
                input.value = '';
            }
        });

        var removeBtn = row.querySelector('.btn-remove-dosen');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Fungsi untuk memasang event listener autocomplete Mahasiswa pada baris tertentu
    function attachMahasiswaEvents(row) {
        var nimInput = row.querySelector('.input-nim-mhs');
        var namaInput = row.querySelector('.input-nama-mahasiswa');
        
        nimInput.addEventListener('input', function() {
            var nim = this.value.trim();
            if (nim.length >= 3) {
                var route = this.getAttribute('data-route');
                fetch(route + nim)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if(data) {
                            namaInput.value = data.nama;
                        } else {
                            namaInput.value = '';
                        }
                    })
                    .catch(function() {
                        namaInput.value = '';
                    });
            } else {
                namaInput.value = '';
            }
        });

        var removeBtn = row.querySelector('.btn-remove-mahasiswa');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Daftarkan event pada baris pertama yang sudah ada
    document.querySelectorAll('.dosen-row').forEach(function(row) {
        attachDosenEvents(row);
    });
    document.querySelectorAll('.mahasiswa-row').forEach(function(row) {
        attachMahasiswaEvents(row);
    });

    // Aksi Tambah Baris Dosen
    document.getElementById('btn-add-dosen').addEventListener('click', function() {
        var container = document.getElementById('dosen-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 dosen-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('hibah-penelitian.get-dosen', '') }}/" required>
                    <option value="">-- Pilih Kode Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->kode_dosen }}">
                            {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" readonly required placeholder="Nama Dosen">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-dosen"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
        attachDosenEvents(newRow);
    });

    // Aksi Tambah Baris Mahasiswa
    document.getElementById('btn-add-mahasiswa').addEventListener('click', function() {
        var container = document.getElementById('mahasiswa-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 mahasiswa-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('hibah-penelitian.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
        attachMahasiswaEvents(newRow);
    });
</script>
@endpush
@endsection
