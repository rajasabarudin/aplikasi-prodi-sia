@extends('layouts.app')

@section('title', 'Data Keuangan & Sarpras')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-wallet2 text-success me-2"></i>Data Keuangan Program Studi</h2>
            <p class="text-muted mb-0">Realisasi Anggaran Tridharma & Investasi (Kriteria C5 LAM INFOKOM)</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('keuangan-prodi.cetak') }}" target="_blank" class="btn btn-outline-danger rounded-pill px-4 shadow-sm mb-2 mb-md-0">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan PDF
            </a>
            @if(Auth::user()->level === 'king' || Auth::user()->level === 'jendral')
            <button class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addKeuanganModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Data
            </button>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Chart Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Grafik Tren Pendanaan Prodi</h5>
                    <div style="height: 300px;">
                        <canvas id="keuanganChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Tahun Akademik</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Jml Mhs Aktif</th>
                            <th class="text-center" colspan="4">Realisasi Penggunaan Dana (Rupiah)</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Total (Rp)</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Rasio / Mhs</th>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center">Pendidikan</th>
                            <th class="text-center">Penelitian</th>
                            <th class="text-center">PkM</th>
                            <th class="text-center">Investasi Sarpras/SDM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keuangans as $row)
                        @php
                            $totalDana = $row->dana_pendidikan + $row->dana_penelitian + $row->dana_pkm + $row->dana_investasi;
                            $rasio = $row->jumlah_mahasiswa_aktif > 0 ? $totalDana / $row->jumlah_mahasiswa_aktif : 0;
                        @endphp
                        <tr>
                            <td class="text-center fw-bold">{{ $row->tahun_akademik }}</td>
                            <td class="text-center">{{ number_format($row->jumlah_mahasiswa_aktif) }}</td>
                            <td class="text-end">Rp {{ number_format($row->dana_pendidikan, 0, ',', '.') }}</td>
                            <td class="text-end">
                                Rp {{ number_format($row->dana_penelitian, 0, ',', '.') }}
                                @if(isset($row->auto_penelitian) && $row->auto_penelitian > 0)
                                    <br><span class="badge bg-info" style="font-size: 0.6rem;"><i class="bi bi-arrow-repeat"></i> Auto</span>
                                @endif
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($row->dana_pkm, 0, ',', '.') }}
                                @if(isset($row->auto_pkm) && $row->auto_pkm > 0)
                                    <br><span class="badge bg-info" style="font-size: 0.6rem;"><i class="bi bi-arrow-repeat"></i> Auto</span>
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($row->dana_investasi, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-primary">Rp {{ number_format($totalDana, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($rasio, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if(Auth::user()->level === 'king' || Auth::user()->level === 'jendral')
                                <button type="button" class="btn btn-sm btn-warning rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editKeuanganModal{{ $row->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('keuangan-prodi.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data keuangan tahun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                                @else
                                <span class="badge bg-secondary"><i class="bi bi-lock-fill"></i> Locked</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">Belum ada data keuangan. Silakan tambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addKeuanganModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah / Update Data Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('keuangan-prodi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2 small">
                        <strong>Catatan:</strong> Masukkan nominal dana secara rill/penuh (Contoh: Rp 150.000.000 ditulis <code>150000000</code> tanpa titik).
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <select name="tahun_akademik" class="form-select" required>
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->tahun_sekarang }}">{{ $ts->tahun_sekarang }} - {{ $ts->label_ts ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jml Mahasiswa Aktif</label>
                            <input type="number" name="jumlah_mahasiswa_aktif" class="form-control" required min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana Operasional Pendidikan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_pendidikan" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana Penelitian</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_penelitian" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana PkM</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_pkm" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana Investasi Sarpras/SDM</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_investasi" class="form-control" value="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Looping for Edit Modals (Must be outside .table-responsive to avoid backdrop bug) -->
@foreach($keuangans as $row)
<div class="modal fade" id="editKeuanganModal{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Data Keuangan ({{ $row->tahun_akademik }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('keuangan-prodi.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tahun_akademik" value="{{ $row->tahun_akademik }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Akademik</label>
                            <input type="text" class="form-control" value="{{ $row->tahun_akademik }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jml Mahasiswa Aktif</label>
                            <input type="number" name="jumlah_mahasiswa_aktif" class="form-control" required min="1" value="{{ $row->jumlah_mahasiswa_aktif }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana Operasional Pendidikan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_pendidikan" class="form-control" value="{{ $row->dana_pendidikan }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana Penelitian</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_penelitian" class="form-control" value="{{ $row->dana_penelitian }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana PkM</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_pkm" class="form-control" value="{{ $row->dana_pkm }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dana Investasi Sarpras/SDM</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="dana_investasi" class="form-control" value="{{ $row->dana_investasi }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for Chart
        const labels = {!! json_encode($keuangans->pluck('tahun_akademik')->reverse()->values()) !!};
        const danaPendidikan = {!! json_encode($keuangans->pluck('dana_pendidikan')->reverse()->values()) !!};
        const danaPenelitian = {!! json_encode($keuangans->pluck('dana_penelitian')->reverse()->values()) !!};
        const danaPkm = {!! json_encode($keuangans->pluck('dana_pkm')->reverse()->values()) !!};
        
        const ctx = document.getElementById('keuanganChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendidikan (Rp)',
                        data: danaPendidikan,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderRadius: 4
                    },
                    {
                        label: 'Penelitian (Rp)',
                        data: danaPenelitian,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderRadius: 4
                    },
                    {
                        label: 'PkM (Rp)',
                        data: danaPkm,
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection
