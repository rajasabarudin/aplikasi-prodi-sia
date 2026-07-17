@extends('layouts.app')

@section('title', 'Data PKS & IA')

@section('content')
<!-- Custom Premium Stats Styles -->
<style>
    .stats-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 16px !important;
        overflow: hidden;
        position: relative;
    }
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.08), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    .stats-card:hover::before {
        transform: translateX(100%);
    }
    .stats-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.15) !important;
    }
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stats-card:hover .icon-box {
        background: rgba(255, 255, 255, 0.28);
        transform: rotate(8deg) scale(1.08);
        border-color: rgba(255, 255, 255, 0.4);
    }
</style>

<!-- Statistics Row -->
<div class="row g-3 mb-4">
    <!-- Total -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-white stats-card" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px; font-family: 'Inter', sans-serif;">Total PKS & IA</h6>
                    <h3 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">{{ $totalPksIa }}</h3>
                </div>
                <div class="icon-box">
                    <i class="bi bi-file-earmark-text fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Pendidikan -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-white stats-card" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px; font-family: 'Inter', sans-serif;">Pendidikan</h6>
                    <h3 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">{{ $pendidikanCount }}</h3>
                </div>
                <div class="icon-box">
                    <i class="bi bi-book fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Penelitian -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-white stats-card" style="background: linear-gradient(135deg, #0d9488, #115e59);">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px; font-family: 'Inter', sans-serif;">Penelitian</h6>
                    <h3 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">{{ $penelitianCount }}</h3>
                </div>
                <div class="icon-box">
                    <i class="bi bi-journal-text fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- PKM -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-white stats-card" style="background: linear-gradient(135deg, #10b981, #047857);">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px; font-family: 'Inter', sans-serif;">PKM</h6>
                    <h3 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">{{ $pkmCount }}</h3>
                </div>
                <div class="icon-box">
                    <i class="bi bi-people fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Main Table -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 fw-bold text-dark">Data PKS & IA</h1>
                        <p class="text-muted mb-0">Total: <strong>{{ $totalPksIa }}</strong> data PKS & IA tercatat</p>
                    </div>
                    <div class="d-print-none">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPksIaModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah PKS & IA
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger d-print-none shadow-sm border-0 border-start border-danger border-4 mb-4">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success d-print-none shadow-sm border-0 border-start border-success border-4 mb-4">
                        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th class="text-center" style="width: 15%;">Tanggal</th>
                                <th>Nama Mitra</th>
                                <th>Nomor PKS</th>
                                <th class="text-center" style="width: 12%;">Kategori</th>
                                <th class="text-center" style="width: 12%;">Level</th>
                                <th class="text-center" style="width: 15%;">Berkas</th>
                                <th class="text-center d-print-none" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pksIaList as $p)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-center" style="font-size: 0.85rem;">{{ $p->tgl_pks->format('d-m-Y') }}</td>
                                    <td class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        <i class="bi bi-building me-1 text-secondary"></i>{{ $p->nama_mitra }}
                                    </td>
                                    <td style="font-size: 0.85rem;"><code class="text-dark">{{ $p->no_pks_ubsi }}</code></td>
                                    <td class="text-center">
                                        @if ($p->kategori === 'Pendidikan')
                                            <span class="badge bg-primary px-2 py-1" style="font-size: 10px;">Pendidikan</span>
                                        @elseif ($p->kategori === 'Penelitian')
                                            <span class="badge bg-info text-dark px-2 py-1" style="font-size: 10px;">Penelitian</span>
                                        @else
                                            <span class="badge bg-success px-2 py-1" style="font-size: 10px;">PKM</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($p->level_pks === 'Internasional')
                                            <span class="badge bg-danger px-2 py-1" style="font-size: 10px;">Internasional</span>
                                        @elseif ($p->level_pks === 'Nasional')
                                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 10px;">Nasional</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1" style="font-size: 10px;">Lokal</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column gap-1 align-items-center">
                                            @if ($p->file_pks)
                                                <a href="{{ asset('storage/' . $p->file_pks) }}" target="_blank" class="badge bg-success text-decoration-none w-100" style="font-size: 9px;" title="Lihat Berkas PKS">PKS <i class="bi bi-file-earmark-pdf"></i></a>
                                            @endif
                                            @if ($p->file_ia)
                                                <a href="{{ asset('storage/' . $p->file_ia) }}" target="_blank" class="badge bg-info text-dark text-decoration-none w-100" style="font-size: 9px;" title="Lihat Berkas IA">IA <i class="bi bi-file-earmark-pdf"></i></a>
                                            @endif
                                            @if ($p->file_tambahan)
                                                <a href="{{ asset('storage/' . $p->file_tambahan) }}" target="_blank" class="badge bg-secondary text-decoration-none w-100" style="font-size: 9px;" title="Lihat Berkas Lainnya">Lainnya <i class="bi bi-file-earmark-pdf"></i></a>
                                            @endif
                                            @if (!$p->file_pks && !$p->file_ia && !$p->file_tambahan)
                                                <span class="text-muted small italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pks-ia.show', $p->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="bi bi-eye text-white text-white"></i></a>
                                            <a href="{{ route('pks-ia.edit', $p->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil text-dark"></i></a>
                                            <form action="{{ route('pks-ia.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data PKS/IA dengan {{ $p->nama_mitra }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-file-earmark-text display-6 d-block mb-2 text-secondary"></i>
                                        Belum ada data PKS & IA.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar (List & Chart) -->
    <div class="col-lg-4">
        <!-- Card 1: Annual List -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;"><i class="bi bi-calendar-event me-2"></i>Mitra Ber-PKS / Tahun</h5>
            </div>
            <div class="card-body p-3">
                <p class="text-muted small mb-3">Jumlah mitra unik yang memiliki dokumen PKS aktif berdasarkan tahun:</p>
                <div class="list-group list-group-flush">
                    @forelse ($pksPerTahun as $tahun => $jumlah)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded px-2.5 py-0.5 fw-bold me-2" style="font-size: 0.9rem; min-width: 55px; text-align: center;">
                                    {{ $tahun }}
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill px-2.5 py-1" style="font-size: 0.8rem; font-weight: 600;">{{ $jumlah }} Mitra</span>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-3 px-0">
                            <i class="bi bi-calendar-x d-block fs-5 mb-1 text-secondary"></i>
                            Belum ada data tahunan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Card 2: Chart Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold" style="font-size: 1rem;"><i class="bi bi-bar-chart-line me-2"></i>Grafik Trend PKS & IA</h5>
            </div>
            <div class="card-body p-3">
                <div style="height: 180px; position: relative;">
                    <canvas id="pksChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah PKS & IA -->
<div class="modal fade" id="tambahPksIaModal" tabindex="-1" aria-labelledby="tambahPksIaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pks-ia.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahPksIaModalLabel"><i class="bi bi-file-earmark-plus-fill me-2"></i>Tambah Data PKS & IA Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nama Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_mitra" class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                            <select name="nama_mitra" id="nama_mitra" class="form-select @error('nama_mitra') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Mitra dari Data Kerjasama</option>
                                @foreach ($kerjasamaList as $k)
                                    <option value="{{ $k->nama_mitra }}" {{ old('nama_mitra') == $k->nama_mitra ? 'selected' : '' }}>{{ $k->nama_mitra }}</option>
                                @endforeach
                            </select>
                            @error('nama_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal PKS -->
                        <div class="col-md-6 mb-3">
                            <label for="tgl_pks" class="form-label fw-semibold">Tanggal PKS <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pks" id="tgl_pks" class="form-control @error('tgl_pks') is-invalid @enderror" value="{{ old('tgl_pks', date('Y-m-d')) }}" required>
                            @error('tgl_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor PKS UBSI -->
                        <div class="col-md-6 mb-3">
                            <label for="no_pks_ubsi" class="form-label fw-semibold">Nomor PKS UBSI <span class="text-danger">*</span></label>
                            <input type="text" name="no_pks_ubsi" id="no_pks_ubsi" class="form-control @error('no_pks_ubsi') is-invalid @enderror" value="{{ old('no_pks_ubsi') }}" required placeholder="Nomor PKS dari pihak UBSI">
                            @error('no_pks_ubsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor PKS Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="no_pks_mitra" class="form-label fw-semibold">Nomor PKS Mitra</label>
                            <input type="text" name="no_pks_mitra" id="no_pks_mitra" class="form-control @error('no_pks_mitra') is-invalid @enderror" value="{{ old('no_pks_mitra') }}" placeholder="Nomor PKS dari pihak Mitra">
                            @error('no_pks_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tema PKS -->
                        <div class="col-md-12 mb-3">
                            <label for="tema_pks" class="form-label fw-semibold">Tema PKS <span class="text-danger">*</span></label>
                            <input type="text" name="tema_pks" id="tema_pks" class="form-control @error('tema_pks') is-invalid @enderror" value="{{ old('tema_pks') }}" required placeholder="Tema atau ruang lingkup kerjasama perjanjian">
                            @error('tema_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Divider & IA Section -->
                        <div class="col-12 mt-2 mb-2">
                            <hr>
                            <h6 class="fw-bold text-primary"><i class="bi bi-file-earmark-check-fill me-1"></i>Detail IA (Implementation Agreement) <span class="text-muted font-monospace" style="font-size: 0.75rem;">(Opsional)</span></h6>
                        </div>

                        <!-- Tanggal IA -->
                        <div class="col-md-4 mb-3">
                            <label for="tgl_ia" class="form-label fw-semibold">Tanggal IA</label>
                            <input type="date" name="tgl_ia" id="tgl_ia" class="form-control @error('tgl_ia') is-invalid @enderror" value="{{ old('tgl_ia') }}">
                            @error('tgl_ia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor IA UBSI -->
                        <div class="col-md-4 mb-3">
                            <label for="no_ia_ubsi" class="form-label fw-semibold">Nomor IA UBSI</label>
                            <input type="text" name="no_ia_ubsi" id="no_ia_ubsi" class="form-control @error('no_ia_ubsi') is-invalid @enderror" value="{{ old('no_ia_ubsi') }}" placeholder="Nomor IA dari UBSI">
                            @error('no_ia_ubsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor IA Mitra -->
                        <div class="col-md-4 mb-3">
                            <label for="no_ia_mitra" class="form-label fw-semibold">Nomor IA Mitra</label>
                            <input type="text" name="no_ia_mitra" id="no_ia_mitra" class="form-control @error('no_ia_mitra') is-invalid @enderror" value="{{ old('no_ia_mitra') }}" placeholder="Nomor IA dari Mitra">
                            @error('no_ia_mitra')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Judul/Tema Kegiatan IA -->
                        <div class="col-md-12 mb-3">
                            <label for="judul_ia" class="form-label fw-semibold">Judul Kegiatan IA</label>
                            <input type="text" name="judul_ia" id="judul_ia" class="form-control @error('judul_ia') is-invalid @enderror" value="{{ old('judul_ia') }}" placeholder="Judul spesifik kegiatan implementasi">
                            @error('judul_ia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Pendidikan" {{ old('kategori') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="PKM" {{ old('kategori') == 'PKM' ? 'selected' : '' }}>PKM</option>
                                <option value="Penelitian" {{ old('kategori') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                            </select>
                            @error('kategori')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Level PKS -->
                        <div class="col-md-6 mb-3">
                            <label for="level_pks" class="form-label fw-semibold">Level PKS <span class="text-danger">*</span></label>
                            <select name="level_pks" id="level_pks" class="form-select @error('level_pks') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Level</option>
                                <option value="Lokal/Wilayah" {{ old('level_pks') == 'Lokal/Wilayah' ? 'selected' : '' }}>Lokal/Wilayah</option>
                                <option value="Nasional" {{ old('level_pks') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ old('level_pks') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                            @error('level_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File PKS -->
                        <div class="col-md-4 mb-3">
                            <label for="file_pks" class="form-label fw-semibold">Upload Berkas PKS</label>
                            <input type="file" name="file_pks" id="file_pks" class="form-control @error('file_pks') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Format: PDF/Doc/Img (Max 5MB).</div>
                            @error('file_pks')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File IA -->
                        <div class="col-md-4 mb-3">
                            <label for="file_ia" class="form-label fw-semibold">Upload Berkas IA</label>
                            <input type="file" name="file_ia" id="file_ia" class="form-control @error('file_ia') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Format: PDF/Doc/Img (Max 5MB).</div>
                            @error('file_ia')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Tambahan -->
                        <div class="col-md-4 mb-3">
                            <label for="file_tambahan" class="form-label fw-semibold">Upload Berkas Lainnya</label>
                            <input type="file" name="file_tambahan" id="file_tambahan" class="form-control @error('file_tambahan') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.png,.zip">
                            <div class="form-text text-muted">Format: PDF/Doc/Img (Max 5MB).</div>
                            @error('file_tambahan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('tambahPksIaModal'));
            myModal.show();
        @endif

        // Render Chart
        const ctx = document.getElementById('pksChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah PKS & IA',
                    data: @json($chartData),
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderColor: '#2563eb',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    barPercentage: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            color: '#64748b',
                            font: {
                                family: 'Inter'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
