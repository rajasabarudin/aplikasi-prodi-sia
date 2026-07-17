@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
<div class="row">
    <!-- Kiri: Panel Statistik Kelas (d-print-none) -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Kelas</h5>
            </div>
            <div class="card-body">
                <!-- Total Kelas -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Kelas</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalKelas }}</h2>
                </div>

                <!-- Berdasarkan Semester -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Semester</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Semester 2</span>
                            <span class="badge bg-primary rounded-pill">{{ $semesterCounts[2] }} Mahasiswa</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Semester 4</span>
                            <span class="badge bg-success rounded-pill">{{ $semesterCounts[4] }} Mahasiswa</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Semester 6</span>
                            <span class="badge bg-warning text-dark rounded-pill" style="background-color: #ffc107 !important;">{{ $semesterCounts[6] }} Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <!-- Sebaran Mahasiswa (Teks) -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-houses-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Sebaran Mahasiswa</span>
                    </div>
                    <div class="ps-1 custom-scroll" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($kelas as $k)
                            <div class="d-flex justify-content-between text-muted small py-2 border-bottom border-light">
                                <span class="text-dark fw-semibold">{{ $k->nama_kelas }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $k->jumlah_mahasiswa }} Mahasiswa</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Grafik Pai (Pie Chart) Sebaran Mahasiswa -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Visualisasi Sebaran</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="kelasPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data Kelas -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data Kelas</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $totalKelas }}</strong> kelas terdaftar</p>
            </div>
            <div class="d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Kelas
                </button>
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
                        <th class="text-center" style="width: 10%;">No</th>
                        <th style="width: 50%;">Nama Kelas</th>
                        <th class="text-center" style="width: 25%;">Jumlah Mahasiswa</th>
                        <th class="text-center d-print-none" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelas as $k)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">{{ $k->nama_kelas }}</td>
                            <td class="text-center">
                                <!-- Klik jumlah mahasiswa akan mengarah ke daftar mahasiswa yang difilter berdasarkan kelas tersebut -->
                                <a href="{{ route('mahasiswa.index', ['search' => $k->nama_kelas]) }}" 
                                   class="badge bg-primary text-white px-3 py-2 text-decoration-none shadow-sm" 
                                   style="font-size: 11px; font-weight: 600; border-radius: 50px; transition: all 0.2s;"
                                   title="Lihat daftar mahasiswa kelas {{ $k->nama_kelas }}">
                                    <i class="bi bi-people-fill me-1"></i> {{ $k->jumlah_mahasiswa }} Mahasiswa
                                </a>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('kelas.show', $k) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('kelas.edit', $k) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('kelas.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahKelasModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Data Kelas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_nama_kelas" class="form-label fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" id="modal_nama_kelas" class="form-control" required placeholder="Contoh: 31.4A.07">
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

@if ($errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('tambahKelasModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('kelasPieChart').getContext('2d');
        
        var labels = {!! json_encode($kelas->pluck('nama_kelas')) !!};
        var data = {!! json_encode($kelas->pluck('jumlah_mahasiswa')) !!};
        
        // Warna palette premium modern
        var colors = [
            '#6366f1', '#10b981', '#f59e0b', '#3b82f6', 
            '#ec4899', '#8b5cf6', '#06b6d4', '#f97316'
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
                                return ' ' + label + ': ' + value + ' Mahasiswa';
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
