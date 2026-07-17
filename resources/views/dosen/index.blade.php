@extends('layouts.app')

@section('title', 'Data Dosen')

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
        /* Zebra striping warna ganjil genap saat dicetak */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9 !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef !important;
        }
        /* Paksa kolom kanan memenuhi 100% lebar kertas saat dicetak */
        .col-lg-9, .col-md-8 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Dosen DTPR Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="row">
    <!-- Kiri: Statistik Dosen -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Dosen</h5>
            </div>
            <div class="card-body">
                <!-- Total Dosen -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Dosen DTPR</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalDosen }}</h2>
                </div>

                <!-- Card Pendidikan -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Pendidikan Terakhir</span>
                    </div>
                    <div class="ps-1">
                        @forelse ($pendidikanCounts as $key => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-dark">{{ $key ?: 'Tidak Diisi' }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Card JFA -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Jabatan Fungsional</span>
                    </div>
                    <div class="ps-1" style="max-height: 180px; overflow-y: auto;">
                        @forelse ($jfaCounts as $key => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-dark text-truncate" style="max-width: 150px;" title="{{ $key ?: 'Tidak Diisi' }}">{{ $key ?: 'Tidak Diisi' }}</span>
                                <span class="badge bg-success rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Card Serdos -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2" style="background-color: #ffc107 !important;">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Sertifikasi Dosen</span>
                    </div>
                    <div class="ps-1">
                        @forelse ($serdosCounts as $key => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-dark">{{ $key ?: 'Tidak Diisi' }}</span>
                                <span class="badge bg-warning text-dark rounded-pill" style="background-color: #ffc107 !important;">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Card PDDikti -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info text-dark rounded p-1 px-2 me-2" style="background-color: #0dcaf0 !important;">
                            <i class="bi bi-cloud-check-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Status PDDikti</span>
                    </div>
                    <div class="ps-1">
                        @forelse ($pddiktiCounts as $key => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-dark">{{ $key ?: 'Tidak Diisi' }}</span>
                                <span class="badge bg-info text-dark rounded-pill" style="background-color: #0dcaf0 !important;">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Grafik Pai (Pie Chart) JFA -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Visualisasi JFA</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="jfaPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Kolaborasi HKI -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-award-fill me-2 text-warning"></i>Kolaborasi HKI</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Dosen Terlibat HKI:</span>
                    <button class="btn btn-sm btn-outline-success fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #a7f3d0; color: #065f46; background-color: #ecfdf5;">
                        {{ $dosenPunyaHkiCount }} Dosen
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted small">Total Karya HKI:</span>
                    <button class="btn btn-sm btn-outline-primary fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #bfdbfe; color: #1e40af; background-color: #eff6ff;">
                        {{ $totalHkiCount }} Karya
                    </button>
                </div>
                <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                    @forelse ($collabHki as $d)
                        <div class="py-2 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                @if($d['id'])
                                    <a href="{{ route('dosen.show', $d['id']) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 0.9rem;">{{ $d['nama'] }}</a>
                                @else
                                    <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $d['nama'] }}</span>
                                @endif
                                <button class="btn btn-xs btn-warning fw-bold px-2 py-0" style="font-size: 10px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2;">
                                    {{ $d['count'] }} HKI
                                </button>
                            </div>
                            <small class="text-muted d-block">Kode: {{ $d['kode'] }}</small>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Belum ada dosen memiliki kolaborasi HKI.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Card Dosen Berprestasi -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-trophy-fill me-2 text-warning"></i>Dosen Berprestasi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Dosen Berprestasi:</span>
                    <button class="btn btn-sm btn-outline-success fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #a7f3d0; color: #065f46; background-color: #ecfdf5;">
                        {{ $dosenPunyaPrestasiCount }} Dosen
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <span class="text-muted small">Total Prestasi:</span>
                    <button class="btn btn-sm btn-outline-primary fw-bold px-2 py-0" style="font-size: 11px; border-radius: 30px; pointer-events: none; height: 22px; line-height: 1.2; border-color: #bfdbfe; color: #1e40af; background-color: #eff6ff;">
                        {{ $totalPrestasiCount }} Prestasi
                    </button>
                </div>
                <div class="custom-scroll" style="max-height: 200px; overflow-y: auto;">
                    @forelse ($dosenPrestasiList as $d)
                        <div class="py-2 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                @if($d['id'])
                                    <a href="{{ route('dosen.show', $d['id']) }}" class="text-dark fw-bold text-decoration-none" style="font-size: 0.9rem;">{{ $d['nama'] }}</a>
                                @else
                                    <span class="text-dark fw-bold" style="font-size: 0.9rem;">{{ $d['nama'] }}</span>
                                @endif
                                <button class="btn btn-xs btn-warning text-dark fw-bold px-2 py-0" style="font-size: 10px; border-radius: 30px; pointer-events: none; height: 18px; line-height: 1.2; background-color: #ffc107 !important; border-color: #ffc107 !important;">
                                    {{ $d['count'] }} Prestasi
                                </button>
                            </div>
                            <small class="text-muted d-block">Kode: {{ $d['kode'] }}</small>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Belum ada data prestasi dosen.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Data Dosen & Tabel -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
            <div>
                <h1 class="mb-0">Data Dosen</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $totalDosen }}</strong> dosen terdaftar</p>
            </div>
        </div>

        <div class="card mb-3 d-print-none shadow-sm border-0">
            <div class="card-body py-2">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Kode, Nama, atau NIP..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-7 text-md-end">
                        <button type="submit" class="btn btn-primary me-1"><i class="bi bi-search"></i> Cari</button>
                        <a href="{{ route('dosen.index', array_merge(request()->query(), ['print' => 'all'])) }}" target="_blank" class="btn btn-success me-1"><i class="bi bi-printer"></i> Cetak</a>
                        <button type="button" class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#tambahDosenModal"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
                        @if (request('search'))
                            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama Dosen</th>
                        <th class="text-center">Homebase</th>
                        <th class="text-center">NIDN</th>
                        <th class="text-center">NUPTK</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Pendidikan</th>
                        <th class="text-center d-none d-print-table-cell">Gelar</th>
                        <th class="text-center">JFA</th>
                        <th class="text-center d-none d-print-table-cell">Kepangkatan</th>
                        <th class="text-center d-none d-print-table-cell">Serdos</th>
                        <th class="text-center d-none d-print-table-cell">Jenjang</th>
                        <th class="text-center d-none d-print-table-cell">Sisfo</th>
                        <th class="text-center d-none d-print-table-cell">PDDikti</th>
                        <th class="text-center d-print-none">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dosens as $dosen)
                        <tr>
                            <td class="text-center">{{ $dosens instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration : $loop->iteration }}</td>
                            <td class="text-center fw-bold">{{ $dosen->kode_dosen }}</td>
                            <td>{{ $dosen->nama_dosen }}</td>
                            <td>{{ $dosen->homebase_dosen }}</td>
                            <td class="text-center">{{ $dosen->nidn ?? '-' }}</td>
                            <td class="text-center">{{ $dosen->nuptk ?? '-' }}</td>
                            <td class="text-center">{{ $dosen->nip ?? '-' }}</td>
                            <td class="text-center">{{ $dosen->pendidikan }}</td>
                            <td class="text-center d-none d-print-table-cell">{{ $dosen->gelar }}</td>
                            <td class="text-center">{{ $dosen->jfa }}</td>
                            <td class="text-center d-none d-print-table-cell">{{ $dosen->kepangkatan }}</td>
                            <td class="text-center d-none d-print-table-cell">{{ $dosen->keterangan_serdos }}</td>
                            <td class="text-center d-none d-print-table-cell">{{ $dosen->jenjang }}</td>
                            <td class="text-center d-none d-print-table-cell">{{ $dosen->kondisi_sisfo }}</td>
                            <td class="text-center d-none d-print-table-cell">{{ $dosen->kondisi_pddikti }}</td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('dosen.show', $dosen) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                    <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('dosen.destroy', $dosen) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="text-center">Belum ada data dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dosens instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center d-print-none mt-3">
                {{ $dosens->links('pagination::bootstrap-5') }}
            </div>
        @endif
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
            var myModal = new bootstrap.Modal(document.getElementById('tambahDosenModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('jfaPieChart').getContext('2d');
        
        var labels = {!! json_encode(array_keys($jfaCounts)) !!};
        var data = {!! json_encode(array_values($jfaCounts)) !!};
        
        // Bersihkan nama label kosong
        labels = labels.map(function(label) {
            return label ? label : 'Tidak Diisi';
        });

        // Warna palette premium modern
        var colors = [
            '#10b981', '#3b82f6', '#f59e0b', '#ec4899', 
            '#8b5cf6', '#06b6d4', '#6366f1', '#f97316'
        ];
        
        var backgroundColors = [];
        for (var i = 0; i < labels.length; i++) {
            backgroundColors.push(colors[i % colors.length]);
        }

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderWidth: 2,
                    borderColor: 'rgba(255, 255, 255, 0.6)'
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
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                return ' ' + label + ': ' + value + ' Dosen';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

<!-- Modal Tambah Dosen -->
<div class="modal fade" id="tambahDosenModal" tabindex="-1" aria-labelledby="tambahDosenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('dosen.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahDosenModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Data Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_kode_dosen" class="form-label">Kode Dosen <span class="text-danger">*</span></label>
                            <input type="text" name="kode_dosen" id="modal_kode_dosen" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nama_dosen" class="form-label">Nama Dosen <span class="text-danger">*</span></label>
                            <input type="text" name="modal_nama_dosen" id="modal_nama_dosen" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_homebase_dosen" class="form-label">Homebase Dosen</label>
                            <input type="text" name="homebase_dosen" id="modal_homebase_dosen" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nidn" class="form-label">NIDN</label>
                            <input type="text" name="nidn" id="modal_nidn" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nuptk" class="form-label">NUPTK</label>
                            <input type="text" name="nuptk" id="modal_nuptk" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nip" class="form-label">NIP</label>
                            <input type="text" name="nip" id="modal_nip" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_pendidikan" class="form-label">Pendidikan</label>
                            <input type="text" name="pendidikan" id="modal_pendidikan" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_gelar" class="form-label">Gelar</label>
                            <input type="text" name="gelar" id="modal_gelar" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_jfa" class="form-label">JFA</label>
                            <input type="text" name="jfa" id="modal_jfa" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_kepangkatan" class="form-label">Kepangkatan</label>
                            <input type="text" name="kepangkatan" id="modal_kepangkatan" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_keterangan_serdos" class="form-label">Keterangan Serdos</label>
                            <input type="text" name="keterangan_serdos" id="modal_keterangan_serdos" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_jenjang" class="form-label">Jenjang</label>
                            <input type="text" name="jenjang" id="modal_jenjang" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_kondisi_sisfo" class="form-label">Kondisi Sisfo</label>
                            <input type="text" name="kondisi_sisfo" id="modal_kondisi_sisfo" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_kondisi_pddikti" class="form-label">Kondisi PDDikti</label>
                            <input type="text" name="kondisi_pddikti" id="modal_kondisi_pddikti" class="form-control">
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
@endsection
