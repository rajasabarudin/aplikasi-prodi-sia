@extends('layouts.app')

@section('title', 'Data PKM Dosen')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-print-none" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-print-none" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Statistics -->
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-gray-800 fw-bold">Statistik PKM Dosen</h5>
            </div>

            <!-- Total PKM -->
            <div class="card border-0 shadow-sm text-white mb-3" style="background: linear-gradient(135deg, #4f46e5, #3b82f6); border-radius: 12px;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total PKM</h6>
                        <h2 class="fw-bold mb-0">{{ $totalPkm }}</h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-clipboard-data"></i></div>
                </div>
            </div>

            <!-- Mitra Non Produktif -->
            <div class="card border-0 shadow-sm text-white mb-3" style="background: linear-gradient(135deg, #10b981, #047857); border-radius: 12px;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Mitra Non Produktif</h6>
                        <h2 class="fw-bold mb-0">{{ $jenisPkmCounts['Mitra Non Produktif'] ?? 0 }}</h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-people"></i></div>
                </div>
            </div>

            <!-- Mitra Produktif -->
            <div class="card border-0 shadow-sm text-white mb-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Mitra Produktif</h6>
                        <h2 class="fw-bold mb-0">{{ $jenisPkmCounts['Mitra Produktif'] ?? 0 }}</h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-building"></i></div>
                </div>
            </div>

            <!-- Dengan Mahasiswa -->
            <div class="card border-0 shadow-sm text-white mb-3" style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 12px;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Dengan Mahasiswa</h6>
                        <h2 class="fw-bold mb-0">{{ $pkmDenganMhs }}</h2>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-mortarboard"></i></div>
                </div>
            </div>

            <!-- Kolaborasi Dosen-Mahasiswa -->
            <div class="card shadow-sm mb-3" style="border-radius: 12px; border-left: 5px solid #8b5cf6;">
                <div class="card-body">
                    <h6 class="fw-bold text-purple mb-3"><i class="bi bi-people-fill me-2"></i>Kolaborasi Dosen-Mahasiswa</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="text-muted">Total Dosen Terlibat</span>
                        <span class="fw-bold fs-5">{{ $totalDosenTerlibat }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="text-muted">Total Mahasiswa Terlibat</span>
                        <span class="fw-bold fs-5">{{ $totalMhsTerlibat }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="text-muted">PKM Dosen Saja</span>
                        <span class="fw-bold fs-5">{{ $totalPkmDosenSaja }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">PKM Dosen + Mahasiswa</span>
                        <span class="fw-bold fs-5">{{ $pkmDenganMhs }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistik per TS -->
            <div class="card shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3" style="border-radius: 12px 12px 0 0;">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-tags-fill me-2"></i>PKM per TS</h6>
                </div>
                <div class="card-body">
                    @forelse ($labelTsPkmCounts as $label => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="fw-medium">{{ $label }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $count }} PKM</span>
                    </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada data TS.</p>
                    @endforelse
                </div>
            </div>

            <!-- Statistik per TA -->
            <div class="card shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3" style="border-radius: 12px 12px 0 0;">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-bar-chart-line me-2"></i>PKM per TA</h6>
                </div>
                <div class="card-body">
                    @forelse ($tsPkmCounts as $tahun => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="fw-medium">{{ $tahun }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $count }} PKM</span>
                    </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Data Table -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-gray-800 fw-bold">Data PKM Dosen</h5>
                <div>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload"></i> Import Excel
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah PKM
                    </button>
                </div>
            </div>

            <!-- Search & Per Page -->
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <form method="GET" class="d-flex gap-2" id="searchForm">
                    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ $request->search ?? '' }}" style="width: 200px;">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                    @if(request()->filled('search'))
                        <a href="{{ route('pkm-dosen.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </form>
                <div class="d-flex gap-2">
                    <form method="GET" id="perPageForm">
                        <select name="per_page" class="form-select form-select-sm" onchange="document.getElementById('perPageForm').submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ $perPage == 200 ? 'selected' : '' }}>200</option>
                        </select>
                    </form>
                    <a href="{{ route('pkm-dosen.index', array_merge(request()->query(), ['print' => 'all'])) }}" class="btn btn-info text-white btn-sm" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
                </div>
            </div>

            <!-- Main Table -->
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" style="font-size: 0.85rem;">
                             <thead class="table-light">
                                 <tr>
                                     <th>No</th>
                                     <th>Kode Dosen</th>
                                     <th>Nama Dosen</th>
                                     <th>Tema PKM</th>
                                     <th>Jenis</th>
                                     <th>Nama Mhs</th>
                                     <th>TA</th>
                                     <th>Link Publikasi</th>
                                     <th>Aksi</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @forelse ($pkm as $item)
                                 <tr>
                                     <td>{{ $loop->iteration }}</td>
                                     <td>{{ $item->kode_dosen }}</td>
                                     <td>{{ $item->nama_dosen }}</td>
                                     <td>{{ $item->tema_pkm }}</td>
                                     <td>{{ $item->jenis_pkm }}</td>
                                     <td>{{ $item->nama_mahasiswa ?? '-' }}</td>
                                     <td class="text-center">
                                         {{ $item->ts ? ($item->ts->label_ts ? $item->ts->label_ts . ' (' . $item->ts->tahun_sekarang . ')' : $item->ts->tahun_sekarang) : '-' }}
                                     </td>
                                     <td class="text-center">
                                         @if($item->link_publikasi)
                                             <a href="{{ $item->link_publikasi }}" target="_blank" class="btn btn-sm btn-outline-info py-0">Buka Link</a>
                                         @else
                                             -
                                         @endif
                                     </td>
                                     <td>
                                         <button type="button" class="btn btn-sm btn-info py-0 text-white detail-btn" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#detailModal"
                                             data-dosen="{{ $item->kode_dosen }} - {{ $item->nama_dosen }}"
                                             data-tema="{{ $item->tema_pkm }}"
                                             data-jenis="{{ $item->jenis_pkm }}"
                                             data-mitra="{{ $item->mitra }}"
                                             data-iptek="{{ $item->sumber_iptek }}"
                                             data-nim="{{ $item->nim_mhs ?? '-' }}"
                                             data-mhs="{{ $item->nama_mahasiswa ?? '-' }}"
                                             data-ta="{{ $item->ts ? ($item->ts->label_ts ? $item->ts->label_ts . ' (' . $item->ts->tahun_sekarang . ')' : $item->ts->tahun_sekarang) : '-' }}"
                                             data-dokumen="{{ $item->link_dokumen ?? '-' }}"
                                             data-publikasi="{{ $item->link_publikasi ?? '-' }}">
                                             Detail
                                         </button>
                                         <a href="{{ route('pkm-dosen.edit', $item->id) }}" class="btn btn-sm btn-warning py-0">Edit</a>
                                         <form action="{{ route('pkm-dosen.destroy', $item->id) }}" method="POST" class="d-inline">
                                             @csrf
                                             @method('DELETE')
                                             <button class="btn btn-sm btn-danger py-0" onclick="return confirm('Hapus?')">Hapus</button>
                                         </form>
                                     </td>
                                 </tr>
                                 @empty
                                 <tr>
                                     <td colspan="9" class="text-center">Tidak ada data.</td>
                                 </tr>
                                 @endforelse
                             </tbody>
                        </table>
                    </div>
                    @if(!($request->get('print') === 'all'))
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pkm->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data PKM Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pkm-dosen.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Gunakan template yang sudah disediakan.
                        <a href="{{ route('pkm-dosen.template') }}" class="alert-link">Download template Excel</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah PKM Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pkm-dosen.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card p-3 mb-3">
                        <h6 class="card-title mb-3 text-primary"><i class="bi bi-people-fill"></i> Anggota Dosen Pelaksana</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0 fw-bold">Dosen <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="modal-btn-add-dosen"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
                        </div>
                        <div id="modal-dosen-rows-container">
                            <div class="row g-2 mb-2 dosen-row">
                                <div class="col-md-5">
                                    <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('pkm-dosen.get-dosen', '') }}/" required>
                                        <option value="">-- Pilih Kode Dosen --</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->kode_dosen }}">{{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" readonly required placeholder="Nama Dosen">
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 mb-3">
                        <h6 class="card-title mb-3 text-primary"><i class="bi bi-journal-bookmark-fill"></i> Detail PKM</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Tema PKM <span class="text-danger">*</span></label>
                                <input type="text" name="tema_pkm" class="form-control" value="{{ old('tema_pkm') }}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Mitra <span class="text-danger">*</span></label>
                                <input type="text" name="mitra" class="form-control" value="{{ old('mitra') }}" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Jenis PKM <span class="text-danger">*</span></label>
                                <select name="jenis_pkm" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Mitra Non Produktif" {{ old('jenis_pkm') == 'Mitra Non Produktif' ? 'selected' : '' }}>Mitra Non Produktif</option>
                                    <option value="Mitra Produktif" {{ old('jenis_pkm') == 'Mitra Produktif' ? 'selected' : '' }}>Mitra Produktif</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Sumber IPTEK <span class="text-danger">*</span></label>
                                <input type="text" name="sumber_iptek" class="form-control" value="{{ old('sumber_iptek') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 mb-3">
                        <h6 class="card-title mb-3 text-primary"><i class="bi bi-people-fill"></i> Mahasiswa (Opsional)</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0 fw-bold">Anggota Mahasiswa</label>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="modal-btn-add-mahasiswa"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>
                        </div>
                        <div id="modal-mahasiswa-rows-container">
                            <div class="row g-2 mb-2 mahasiswa-row">
                                <div class="col-md-5">
                                    <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('pkm-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card p-3 mb-3">
                        <h6 class="card-title mb-3 text-primary"><i class="bi bi-calendar"></i> TA & Dokumen</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">TA <span class="text-danger">*</span></label>
                                <select name="ts_id" class="form-select" required>
                                    <option value="">-- Pilih TA --</option>
                                    @foreach ($tsList as $ts)
                                        <option value="{{ $ts->id }}" {{ old('ts_id') == $ts->id ? 'selected' : '' }}>{{ $ts->tahun_sekarang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Link Dokumen</label>
                                <input type="url" name="link_dokumen" class="form-control" value="{{ old('link_dokumen') }}" placeholder="https://example.com/dokumen">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Link Publikasi</label>
                                <input type="url" name="link_publikasi" class="form-control" value="{{ old('link_publikasi') }}" placeholder="https://example.com/publikasi">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail PKM Dosen -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailModalLabel"><i class="bi bi-info-circle-fill me-2"></i>Detail PKM Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <table class="table table-striped table-bordered mb-0" style="font-size: 0.9rem;">
                    <tbody>
                        <tr>
                            <th style="width: 30%;">Dosen Pelaksana</th>
                            <td id="detailDosen"></td>
                        </tr>
                        <tr>
                            <th>Tema PKM</th>
                            <td id="detailTema"></td>
                        </tr>
                        <tr>
                            <th>Jenis PKM</th>
                            <td id="detailJenis"></td>
                        </tr>
                        <tr>
                            <th>Mitra</th>
                            <td id="detailMitra"></td>
                        </tr>
                        <tr>
                            <th>Sumber IPTEK</th>
                            <td id="detailIptek"></td>
                        </tr>
                        <tr>
                            <th>NIM Mahasiswa</th>
                            <td id="detailNim"></td>
                        </tr>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <td id="detailMhs"></td>
                        </tr>
                        <tr>
                            <th>Tahun Akademik (TA)</th>
                            <td id="detailTa"></td>
                        </tr>
                        <tr>
                            <th>Link Dokumen</th>
                            <td id="detailDokumen"></td>
                        </tr>
                        <tr>
                            <th>Link Publikasi</th>
                            <td id="detailPublikasi"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function attachDosenEvents(row) {
        var select = row.querySelector('.select-kode-dosen');
        var input = row.querySelector('.input-nama-dosen');
        if (select) {
            select.addEventListener('change', function() {
                var kode = this.value;
                if (kode) {
                    fetch(this.getAttribute('data-route') + kode)
                        .then(function(res) { return res.json(); })
                        .then(function(data) { input.value = data.nama_dosen; })
                        .catch(function() { input.value = ''; });
                } else { input.value = ''; }
            });
        }
        var btn = row.querySelector('.btn-remove-dosen');
        if (btn) btn.addEventListener('click', function() { row.remove(); });
    }

    function attachMahasiswaEvents(row) {
        var nimInput = row.querySelector('.input-nim-mhs');
        var namaInput = row.querySelector('.input-nama-mahasiswa');
        if (nimInput) {
            nimInput.addEventListener('input', function() {
                var nim = this.value.trim();
                if (nim.length >= 3) {
                    fetch(this.getAttribute('data-route') + nim)
                        .then(function(res) { return res.json(); })
                        .then(function(data) { namaInput.value = data ? data.nama : ''; })
                        .catch(function() { namaInput.value = ''; });
                } else { namaInput.value = ''; }
            });
        }
        var btn = row.querySelector('.btn-remove-mahasiswa');
        if (btn) btn.addEventListener('click', function() { row.remove(); });
    }

    document.querySelectorAll('#createModal .dosen-row').forEach(attachDosenEvents);
    document.querySelectorAll('#createModal .mahasiswa-row').forEach(attachMahasiswaEvents);

    document.getElementById('modal-btn-add-dosen').addEventListener('click', function() {
        var c = document.getElementById('modal-dosen-rows-container');
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 dosen-row';
        row.innerHTML = `
            <div class="col-md-5">
                <select name="kode_dosen[]" class="form-select select-kode-dosen" data-route="{{ route('pkm-dosen.get-dosen', '') }}/" required>
                    <option value="">-- Pilih Kode Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->kode_dosen }}">{{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen" readonly required placeholder="Nama Dosen">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-dosen"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        `;
        c.appendChild(row);
        attachDosenEvents(row);
    });

    document.getElementById('modal-btn-add-mahasiswa').addEventListener('click', function() {
        var c = document.getElementById('modal-mahasiswa-rows-container');
        var row = document.createElement('div');
        row.className = 'row g-2 mb-2 mahasiswa-row';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs" data-route="{{ route('pkm-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa" readonly placeholder="Nama Mahasiswa">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        `;
        c.appendChild(row);
        attachMahasiswaEvents(row);
    });

    // Populate Detail Modal
    document.querySelectorAll('.detail-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('detailDosen').textContent = this.getAttribute('data-dosen');
            document.getElementById('detailTema').textContent = this.getAttribute('data-tema');
            document.getElementById('detailJenis').textContent = this.getAttribute('data-jenis');
            document.getElementById('detailMitra').textContent = this.getAttribute('data-mitra');
            document.getElementById('detailIptek').textContent = this.getAttribute('data-iptek');
            document.getElementById('detailNim').textContent = this.getAttribute('data-nim');
            document.getElementById('detailMhs').textContent = this.getAttribute('data-mhs');
            document.getElementById('detailTa').textContent = this.getAttribute('data-ta');
            
            var docUrl = this.getAttribute('data-dokumen');
            var docTd = document.getElementById('detailDokumen');
            if (docUrl && docUrl !== '-') {
                docTd.innerHTML = `<a href="${docUrl}" target="_blank" class="btn btn-xs btn-outline-primary py-0.5 px-2 text-decoration-none small text-center">${docUrl}</a>`;
            } else {
                docTd.textContent = '-';
            }

            var pubUrl = this.getAttribute('data-publikasi');
            var pubTd = document.getElementById('detailPublikasi');
            if (pubUrl && pubUrl !== '-') {
                pubTd.innerHTML = `<a href="${pubUrl}" target="_blank" class="btn btn-xs btn-outline-info py-0.5 px-2 text-decoration-none small text-center">${pubUrl}</a>`;
            } else {
                pubTd.textContent = '-';
            }
        });
    });
</script>
@endpush
@endsection
