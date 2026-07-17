@extends('layouts.app')

@section('title', 'Data Jumlah PMB')

@section('content')
<div class="row">
    <!-- Kiri: Panel Statistik PMB -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #0f172a !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik PMB</h5>
            </div>
            <div class="card-body">
                <!-- Total PMB -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Mahasiswa Baru</span>
                    <h2 class="fw-bold mb-0 text-white">{{ number_format($totalPmb, 0, ',', '.') }}</h2>
                </div>

                <!-- Rerata PMB -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <span class="fw-bold text-dark">Rerata Per Tahun</span>
                    </div>
                    <div class="ps-1">
                        <h4 class="fw-bold text-dark mb-0">{{ number_format($avgPmb, 1, ',', '.') }} <span class="fs-6 fw-normal text-muted">Mhs/Tahun</span></h4>
                    </div>
                </div>

                <!-- Chart Visualisasi Tren PMB -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <span class="fw-bold text-dark">Tren PMB</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="pmbLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data PMB -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data Jumlah PMB</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $pmbList->count() }}</strong> tahun akademik tercatat</p>
            </div>
            <div class="d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPmbModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Data PMB
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
                        <th class="text-center" style="width: 8%;">No</th>
                        <th class="text-center" style="width: 27%;">Tahun Akademik</th>
                        <th class="text-center" style="width: 25%;">Jumlah Mahasiswa Baru</th>
                        <th class="text-center" style="width: 25%;">Kenaikan / Penurunan (YoY)</th>
                        <th class="text-center d-print-none" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pmbList as $p)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-calendar-event me-2 text-secondary"></i>{{ $p->tahun }}
                            </td>
                            <td class="text-center fw-bold text-primary">
                                {{ number_format($p->jumlah_pmb, 0, ',', '.') }} Mahasiswa
                            </td>
                            <td class="text-center">
                                @if ($p->yoy_change === null)
                                    <span class="text-muted small italic">-</span>
                                @elseif ($p->yoy_change > 0)
                                    <span class="badge bg-success px-3 py-2 shadow-sm" style="font-size: 11px; font-weight: 600; border-radius: 50px;">
                                        <i class="bi bi-arrow-up-short"></i> Naik {{ number_format($p->yoy_change, 1, ',', '.') }}%
                                    </span>
                                @elseif ($p->yoy_change < 0)
                                    <span class="badge bg-danger px-3 py-2 shadow-sm" style="font-size: 11px; font-weight: 600; border-radius: 50px;">
                                        <i class="bi bi-arrow-down-short"></i> Turun {{ number_format(abs($p->yoy_change), 1, ',', '.') }}%
                                    </span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 shadow-sm" style="font-size: 11px; font-weight: 600; border-radius: 50px;">
                                        Tetap (0%)
                                    </span>
                                @endif
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pmb.edit', $p->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('pmb.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data PMB tahun {{ $p->tahun }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-graph-up-arrow display-6 d-block mb-2 text-secondary"></i>
                                Belum ada data PMB.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah PMB -->
<div class="modal fade" id="tambahPmbModal" tabindex="-1" aria-labelledby="tambahPmbModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pmb.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahPmbModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Data PMB</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tahun -->
                    <div class="mb-3">
                        <label for="tahun" class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar text-secondary"></i></span>
                            <input type="number" name="tahun" id="tahun" class="form-control border-start-0 @error('tahun') is-invalid @enderror" value="{{ old('tahun', date('Y')) }}" required min="1900" max="2100" placeholder="Contoh: 2024">
                        </div>
                        @error('tahun')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jumlah PMB -->
                    <div class="mb-3">
                        <label for="jumlah_pmb" class="form-label fw-semibold">Jumlah PMB <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-people text-secondary"></i></span>
                            <input type="number" name="jumlah_pmb" id="jumlah_pmb" class="form-control border-start-0 @error('jumlah_pmb') is-invalid @enderror" value="{{ old('jumlah_pmb') }}" required min="0" placeholder="Masukkan jumlah mahasiswa">
                        </div>
                        @error('jumlah_pmb')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('tambahPmbModal'));
            myModal.show();
        @endif

        var ctx = document.getElementById('pmbLineChart').getContext('2d');
        
        // Sort data chronologically for the chart
        var chartData = {!! json_encode($pmbList->sortBy('tahun')->values()) !!};
        var labels = chartData.map(item => item.tahun);
        var data = chartData.map(item => item.jumlah_pmb);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Mhs Baru',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4
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
                        beginAtZero: true,
                        ticks: { stepSize: 50 }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
