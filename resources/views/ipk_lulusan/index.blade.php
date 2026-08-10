@extends('layouts.app')

@section('title', 'Data IPK Lulusan')

@section('content')
<div class="row">
    <!-- Kiri: Panel Statistik IPK Lulusan -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #0f172a !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik IPK Lulusan</h5>
            </div>
            <div class="card-body">
                <!-- Total Data -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Lulusan</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalIpk }}</h2>
                </div>

                <!-- Rerata IPK -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <span class="fw-bold text-dark">Rerata Keseluruhan</span>
                    </div>
                    <div class="ps-1">
                        <button class="btn btn-light border fw-bold px-3 py-1.5" style="border-radius: 30px; pointer-events: none; background: #f8fafc;">
                            <span class="text-primary fs-5">{{ number_format($avgIpk, 2, ',', '.') }}</span> <span class="text-muted small">/ 4.00</span>
                        </button>
                    </div>
                </div>

                <!-- Distribusi IPK -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <span class="fw-bold text-dark">Distribusi IPK Keseluruhan</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;">IPK &ge; 3.50 (Cum Laude)</span>
                            <button class="btn btn-sm btn-success fw-bold px-2 py-0" style="font-size: 0.75rem; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2;">
                                {{ $dist['sangat_memuaskan'] }} Lulusan
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;">IPK 3.00 - 3.49</span>
                            <button class="btn btn-sm btn-primary fw-bold px-2 py-0" style="font-size: 0.75rem; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2;">
                                {{ $dist['memuaskan'] }} Lulusan
                            </button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;">IPK &lt; 3.00</span>
                            <button class="btn btn-sm btn-danger fw-bold px-2 py-0" style="font-size: 0.75rem; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2;">
                                {{ $dist['cukup'] }} Lulusan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rerata & Distribusi per Tahun -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <span class="fw-bold text-dark">Rerata & Dist. per Tahun</span>
                    </div>
                    <div class="ps-1" style="max-height: 220px; overflow-y: auto;">
                        @foreach ($statsPerTahun as $stat)
                            <div class="mb-2 pb-2 border-bottom border-light">
                                <div class="d-flex justify-content-between align-items-center fw-bold text-dark mb-1" style="font-size: 0.85rem;">
                                    <span>Tahun {{ $stat['tahun'] }}</span>
                                    <button class="btn btn-sm btn-primary fw-bold px-2 py-0" style="font-size: 0.75rem; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2;">
                                        {{ number_format($stat['average'], 2, ',', '.') }}
                                    </button>
                                </div>
                                <div class="d-flex gap-1 flex-wrap">
                                    <button class="btn btn-sm btn-outline-success fw-bold px-2 py-0" style="font-size: 8px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2; border-color: #a7f3d0; color: #065f46; background-color: #ecfdf5;">
                                        &ge;3.5: {{ $stat['cumlaude'] }}
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary fw-bold px-2 py-0" style="font-size: 8px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2; border-color: #bfdbfe; color: #1e40af; background-color: #eff6ff;">
                                        3.0-3.5: {{ $stat['memuaskan'] }}
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger fw-bold px-2 py-0" style="font-size: 8px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2; border-color: #fecaca; color: #991b1b; background-color: #fef2f2;">
                                        &lt;3.0: {{ $stat['cukup'] }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @if(empty($statsPerTahun))
                            <span class="text-muted small">Belum ada data tahun lulusan.</span>
                        @endif
                    </div>
                </div>

                <!-- Chart Rerata IPK per Tahun -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #f59e0b, #d97706) !important; color: white !important;">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <span class="fw-bold text-dark">Tren Rerata IPK</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="ipkLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data & Pencarian -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data IPK Lulusan</h1>
                <p class="text-muted mb-0">Total terfilter: <strong>{{ $ipkList->total() }}</strong> data</p>
            </div>
            <div class="d-print-none">
                <button type="button" class="btn btn-info me-1 text-white" data-bs-toggle="modal" data-bs-target="#exportIpkModal">
                    <i class="bi bi-download me-1"></i>Export Excel
                </button>
                <button type="button" class="btn btn-success me-1" data-bs-toggle="modal" data-bs-target="#importIpkModal">
                    <i class="bi bi-upload me-1"></i>Import Excel
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahIpkModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Data IPK
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

        <!-- Panel Pencarian dan Filter -->
        <div class="card mb-3 d-print-none border-0 shadow-sm">
            <div class="card-body py-2">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari NIM, nama, atau kelas..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        @php
                            $currentLimit = request('per_page', 20);
                        @endphp
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ $currentLimit == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="20" {{ $currentLimit == 20 ? 'selected' : '' }}>20 Baris</option>
                            <option value="50" {{ $currentLimit == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ $currentLimit == 100 ? 'selected' : '' }}>100 Baris</option>
                        </select>
                    </div>
                    <div class="col-md-5 text-md-end d-flex gap-1 justify-content-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                        @if (request('search') || request('per_page'))
                            <a href="{{ route('ipk_lulusan.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 8%;">No</th>
                        <th style="width: 20%;">NIM</th>
                        <th style="width: 20%;">Nama Mahasiswa</th>
                        <th style="width: 15%;">Kelas</th>
                        <th class="text-center" style="width: 15%;">Tahun Lulusan</th>
                        <th class="text-center" style="width: 10%;">IPK</th>
                        <th class="text-center d-print-none" style="width: 12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ipkList as $ipk)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ ($ipkList->currentPage() - 1) * $ipkList->perPage() + $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $ipk->nim }}</td>
                            <td class="fw-semibold text-dark">{{ $ipk->nama_mahasiswa }}</td>
                            <td class="text-dark">{{ $ipk->kelas }}</td>
                            <td class="text-center text-dark">{{ $ipk->tahun_lulusan }}</td>
                            <td class="text-center">
                                @if($ipk->ipk >= 3.50)
                                    <button class="btn btn-sm btn-success fw-bold px-3 py-1" style="font-size: 0.8rem; border-radius: 30px; pointer-events: none; min-width: 60px;">{{ number_format($ipk->ipk, 2) }}</button>
                                @elseif($ipk->ipk >= 3.00)
                                    <button class="btn btn-sm btn-primary fw-bold px-3 py-1" style="font-size: 0.8rem; border-radius: 30px; pointer-events: none; min-width: 60px;">{{ number_format($ipk->ipk, 2) }}</button>
                                @else
                                    <button class="btn btn-sm btn-danger fw-bold px-3 py-1" style="font-size: 0.8rem; border-radius: 30px; pointer-events: none; min-width: 60px;">{{ number_format($ipk->ipk, 2) }}</button>
                                @endif
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editIpkModal{{ $ipk->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('ipk_lulusan.destroy', $ipk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data IPK {{ $ipk->nama_mahasiswa }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit IPK -->
                        <div class="modal fade" id="editIpkModal{{ $ipk->id }}" tabindex="-1" aria-labelledby="editIpkModalLabel{{ $ipk->id }}" aria-hidden="true">
                            <div class="modal-dialog text-start">
                                <div class="modal-content">
                                    <form action="{{ route('ipk_lulusan.update', $ipk->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title" id="editIpkModalLabel{{ $ipk->id }}"><i class="bi bi-pencil me-2"></i>Edit Data IPK Lulusan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                                <input type="text" name="nim" class="form-control" value="{{ $ipk->nim }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Nama Mahasiswa <span class="text-danger">*</span></label>
                                                <input type="text" name="nama_mahasiswa" class="form-control" value="{{ $ipk->nama_mahasiswa }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                                                <input type="text" name="kelas" class="form-control" value="{{ $ipk->kelas }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Tahun Lulusan <span class="text-danger">*</span></label>
                                                <input type="text" name="tahun_lulusan" class="form-control" value="{{ $ipk->tahun_lulusan }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">IPK <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" min="0.00" max="4.00" name="ipk" class="form-control" value="{{ $ipk->ipk }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-award display-6 d-block mb-2 text-secondary"></i>
                                Belum ada data IPK lulusan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4 d-print-none">
            <div class="text-muted small">
                Menampilkan {{ $ipkList->firstItem() ?? 0 }} sampai {{ $ipkList->lastItem() ?? 0 }} dari {{ $ipkList->total() }} data
            </div>
            <div>
                {{ $ipkList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah IPK -->
<div class="modal fade" id="tambahIpkModal" tabindex="-1" aria-labelledby="tambahIpkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ipk_lulusan.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahIpkModalLabel"><i class="bi bi-award-fill me-2 text-warning"></i>Tambah Data IPK Lulusan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- NIM -->
                    <div class="mb-3">
                        <label for="modal_nim" class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="modal_nim" class="form-control" required placeholder="Masukkan NIM...">
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="modal_nama" class="form-label fw-semibold">Nama Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mahasiswa" id="modal_nama" class="form-control" required placeholder="Masukkan Nama...">
                    </div>

                    <!-- Kelas -->
                    <div class="mb-3">
                        <label for="modal_kelas" class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="kelas" id="modal_kelas" class="form-control" required placeholder="Contoh: SI-4A">
                    </div>

                    <!-- Tahun Lulusan -->
                    <div class="mb-3">
                        <label for="modal_tahun" class="form-label fw-semibold">Tahun Lulusan <span class="text-danger">*</span></label>
                        <input type="text" name="tahun_lulusan" id="modal_tahun" class="form-control" required placeholder="Contoh: 2024">
                    </div>

                    <!-- IPK -->
                    <div class="mb-3">
                        <label for="modal_ipk" class="form-label fw-semibold">IPK <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.00" max="4.00" name="ipk" id="modal_ipk" class="form-control" required placeholder="Contoh: 3.85">
                        <div class="form-text">Nilai IPK berkisar antara 0.00 hingga 4.00</div>
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

<!-- Modal Import IPK -->
<div class="modal fade" id="importIpkModal" tabindex="-1" aria-labelledby="importIpkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ipk_lulusan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="importIpkModalLabel"><i class="bi bi-file-earmark-excel-fill me-2 text-white"></i>Import Data IPK Lulusan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="alert alert-info mb-0">
                        <small>
                            <strong>Format kolom Excel:</strong>
                            <ul class="mb-2 ps-3">
                                <li>Kolom A (A1) = NIM</li>
                                <li>Kolom B (B1) = Nama Mahasiswa</li>
                                <li>Kolom C (C1) = Kelas (Contoh: SI-4A)</li>
                                <li>Kolom D (D1) = Tahun Lulusan (Contoh: 2024)</li>
                                <li>Kolom E (E1) = IPK (Contoh: 3.85)</li>
                            </ul>
                            Baris pertama adalah header dan akan dilewati otomatis.
                            <br><a href="{{ route('ipk_lulusan.template') }}" class="alert-link fw-bold"><i class="bi bi-download"></i> Unduh Template Excel</a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Export IPK Lulusan -->
<div class="modal fade" id="exportIpkModal" tabindex="-1" aria-labelledby="exportIpkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="exportIpkModalLabel"><i class="bi bi-download me-2"></i>Export Data IPK Lulusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ipk_lulusan.export') }}" method="GET">
                <div class="modal-body">
                    <p>Pilih tahun lulusan yang ingin di-export ke format Excel. Biarkan "Semua Tahun" untuk mengunduh seluruh data.</p>
                    <div class="mb-3">
                        <label for="export_tahun" class="form-label fw-semibold">Pilih Tahun Lulusan</label>
                        <select name="tahun_lulusan" id="export_tahun" class="form-select">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($tahunList as $tahun)
                                @if(!empty($tahun))
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Export Excel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js Initialization for IPK Trend
    var ctx = document.getElementById('ipkLineChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Rerata IPK',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        min: 0.00,
                        max: 4.00,
                        ticks: { stepSize: 1.00 }
                    }
                }
            }
        });
    }
});
</script>

@endsection
