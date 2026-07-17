@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .bg-gradient-blue {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
    }
    .bg-gradient-green {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    .bg-gradient-orange {
        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    }
    .bg-gradient-dark {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
    }
    .bg-gradient-cyan {
        background: linear-gradient(135deg, #06b6d4, #0891b2) !important;
    }
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
    }
    
    /* Hover scale effect on dashboard stats cards */
    .stat-card {
        transition: all 0.3s ease !important;
        border: none !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06) !important;
    }
    .stat-card:hover {
        transform: translateY(-4px) scale(1.02) !important;
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.15) !important;
    }
</style>

@if (session('error'))
    <div class="alert alert-danger shadow-sm border-0 border-start border-danger border-4 mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>{{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger shadow-sm border-0 border-start border-danger border-4 mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success shadow-sm border-0 border-start border-success border-4 mb-4">
        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-blue text-white">
            <div class="card-body d-flex align-items-center">
                <i class="bi bi-person-badge fs-1 me-3"></i>
                <div>
                    <h5 class="card-title mb-0 text-white-50 small fw-bold">Jumlah Dosen</h5>
                    <span class="fs-2 fw-bold text-white">{{ $jumlahDosen }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-green text-white">
            <div class="card-body d-flex align-items-center">
                <i class="bi bi-people fs-1 me-3"></i>
                <div>
                    <h5 class="card-title mb-0 text-white-50 small fw-bold">Jumlah Mahasiswa</h5>
                    <span class="fs-2 fw-bold text-white">{{ $jumlahMahasiswa }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-orange text-white">
            <div class="card-body d-flex align-items-center">
                <i class="bi bi-layers fs-1 me-3"></i>
                <div>
                    <h5 class="card-title mb-0 text-white-50 small fw-bold">Jumlah Kelas</h5>
                    <span class="fs-2 fw-bold text-white">{{ $jumlahKelas }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-dark text-white">
            <div class="card-body d-flex align-items-center">
                <i class="bi bi-arrow-left-right fs-1 me-3"></i>
                <div>
                    <h5 class="card-title mb-0 text-white-50 small fw-bold">Rasio Dosen : Mahasiswa</h5>
                    <span class="fs-2 fw-bold text-white">1 : {{ $rasioDosenMahasiswa }}</span>
                    <small class="d-block text-white-50 mt-1">{{ $jumlahDosen }} dosen : {{ $jumlahMahasiswa }} mhs</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-cyan text-white">
            <div class="card-body d-flex align-items-center">
                <i class="bi bi-award fs-1 me-3"></i>
                <div>
                    <h5 class="card-title mb-0 text-white-50 small fw-bold">Total Rekognisi</h5>
                    <span class="fs-2 fw-bold text-white">{{ $totalRekognisi }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-gradient-indigo text-white">
            <div class="card-body d-flex align-items-center">
                <i class="bi bi-mortarboard fs-1 me-3"></i>
                <div>
                    <h5 class="card-title mb-0 text-white-50 small fw-bold mb-2">Dosen per Pendidikan</h5>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($pendidikanData as $pendidikan => $total)
                            <span class="badge bg-white text-dark" style="font-size: 11px; font-weight: 600; border-radius: 6px;">{{ $pendidikan }}: {{ $total }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- ROW 1 -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik Tren PMB (Penerimaan Mahasiswa Baru)</div>
            <div class="card-body">
                <canvas id="chartPmb" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center" style="height: 51px;">
                <span class="fw-bold"><i class="bi bi-award-fill me-2 text-warning"></i>Statistik HKI</span>
            </div>
            <div class="card-body">
                <div class="row align-items-center h-100">
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;"><i class="bi bi-people-fill me-1 text-primary"></i>Mhs Mandiri</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="border-radius: 30px; font-size: 0.75rem;">
                                {{ $hkiMhsMandiriCount }} Karya
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;"><i class="bi bi-person-fill-check me-1 text-success"></i>Dsn Mandiri</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="border-radius: 30px; font-size: 0.75rem;">
                                {{ $hkiDosenMandiriCount }} Karya
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <span class="text-dark fw-semibold" style="font-size: 0.85rem;"><i class="bi bi-arrow-left-right me-1 text-warning"></i>Kolaborasi</span>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="border-radius: 30px; font-size: 0.75rem;">
                                {{ $hkiKolaborasiCount }} Karya @if($hkiKolaborasiMhsCount > 0) ({{ $hkiKolaborasiMhsCount }} Mhs) @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-1 mb-3">
                            <span class="text-muted fw-bold" style="font-size: 0.85rem;">Total HKI</span>
                            <span class="badge bg-dark px-2 py-1" style="font-size: 0.8rem;">
                                {{ $totalHkiCount }} Karya ({{ $totalMhsPunyaHkiCount }} Mhs)
                            </span>
                        </div>

                        <!-- Persentase HKI -->
                        <div class="row pt-2 border-top g-2 text-center">
                            <div class="col-6 border-end">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Mhs Miliki HKI</span>
                                <span class="fw-bold text-primary" style="font-size: 0.95rem;">{{ $persenMhsHki }}%</span>
                                <small class="text-muted d-block" style="font-size: 0.65rem;">({{ $totalMhsPunyaHkiCount }}/{{ $jumlahMahasiswa }} Mhs)</small>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Dosen Miliki HKI</span>
                                <span class="fw-bold text-success" style="font-size: 0.95rem;">{{ $persenDosenHki }}%</span>
                                <small class="text-muted d-block" style="font-size: 0.65rem;">({{ $totalDosenPunyaHkiCount }}/{{ $jumlahDosen }} Dsn)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <div style="position: relative; height: 80px; width: 100%;">
                            <canvas id="chartHkiRatio"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik Jumlah Dosen, Mahasiswa & Kelas</div>
            <div class="card-body">
                <canvas id="chartCount" height="220"></canvas>
            </div>
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik JFA Dosen</div>
            <div class="card-body">
                <canvas id="chartJFA" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik Kepangkatan Dosen</div>
            <div class="card-body">
                <canvas id="chartKepangkatan" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span>Grafik Rekognisi per TA</span>
                <span class="badge bg-info text-dark">Total: {{ $totalRekognisi }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartRekognisiTS" height="220"></canvas>
            </div>
        </div>
    </div>

    <!-- ROW 3 -->
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span>Grafik Rekognisi per Dosen</span>
                <span class="badge bg-primary">Total: {{ $totalRekognisi }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartRekognisiDosen" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik Tren Rerata IPK per TA</div>
            <div class="card-body">
                <canvas id="chartIpkTrend" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span>Grafik Tren PKS & IA per Tahun</span>
                <span class="badge bg-success">PKS: {{ $totalPks }} | IA: {{ $totalIa }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartPksIa" height="220"></canvas>
            </div>
        </div>
    </div>
    
    <!-- ROW 4 -->
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik Tren Prestasi Mahasiswa per Bidang</div>
            <div class="card-body">
                <canvas id="chartPrestasiMhs" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">Grafik Sertifikasi Mahasiswa per Skema</div>
            <div class="card-body">
                <canvas id="chartSerkomMhs" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header text-white fw-bold d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg,#7c3aed,#4f46e5);">
                <span><i class="bi bi-person-workspace me-2"></i>Grafik Praktisi per TA</span>
                <span class="badge bg-light text-dark">Total: {{ $totalPraktisi }}</span>
            </div>
            <div class="card-body">
                <canvas id="chartPraktisiPerTs" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- INTEGRASI PENELITIAN & PKM DALAM PEMBELAJARAN (OBE) REPORT -->
<div class="row mt-4 mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold text-white"><i class="bi bi-mortarboard-fill me-2 text-warning"></i>Pemetaan Integrasi Hasil Penelitian & PkM dalam Kurikulum (OBE)</h5>
                <span class="badge bg-warning text-dark fw-bold">Kriteria 6 Pendidikan</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3 small">Berikut adalah rekapitulasi mata kuliah yang telah mengintegrasikan luaran penelitian dan pengabdian kepada masyarakat (PkM) dosen ke dalam proses pembelajaran (RPS/RTM/Silabus).</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 25%;">Mata Kuliah & Kode</th>
                                <th style="width: 10%; text-align: center;">Semester</th>
                                <th style="width: 30%;">Penelitian Dosen Terintegrasi</th>
                                <th style="width: 30%;">PkM Dosen Terintegrasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rpsIntegrasi as $index => $ri)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $ri->matakuliah?->nama_matakuliah }}</strong><br>
                                    <small class="text-muted">Kode: {{ $ri->kode_matakuliah }}</small>
                                </td>
                                <td class="text-center">Semester {{ $ri->matakuliah?->semester }}</td>
                                <td>
                                    @if($ri->penelitians->count() > 0)
                                        <ul class="ps-3 mb-0" style="font-size: 0.85rem;">
                                            @foreach($ri->penelitians as $penel)
                                            <li class="mb-2">
                                                <strong>{{ $penel->nama_dosen }}:</strong> 
                                                <span class="fst-italic">"{{ $penel->nama_jurnal }}"</span><br>
                                                <small class="text-primary fw-semibold">Integrasi: {{ $penel->pivot->bentuk_integrasi }}</small>
                                            </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted small fst-italic">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ri->pkms->count() > 0)
                                        <ul class="ps-3 mb-0" style="font-size: 0.85rem;">
                                            @foreach($ri->pkms as $pkm)
                                            <li class="mb-2">
                                                <strong>{{ $pkm->nama_dosen }}:</strong> 
                                                <span>Tema: "{{ $pkm->tema_pkm }}"</span><br>
                                                <small class="text-success fw-semibold">Integrasi: {{ $pkm->pivot->bentuk_integrasi }}</small>
                                            </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted small fst-italic">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                    Belum ada data integrasi Penelitian & PkM dalam RPS mata kuliah. Silakan edit/buat RPS baru untuk mengintegrasikannya.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('chartCount'), {
    type: 'bar',
    data: {
        labels: ['Dosen', 'Mahasiswa', 'Kelas'],
        datasets: [{
            label: 'Jumlah',
            data: [{{ $jumlahDosen }}, {{ $jumlahMahasiswa }}, {{ $jumlahKelas }}],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartJFA'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($jfaData->keys()) !!},
        datasets: [{
            label: 'Jumlah',
            data: {!! json_encode($jfaData->values()) !!},
            backgroundColor: '#6610f2',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartKepangkatan'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($kepangkatanData->keys()) !!},
        datasets: [{
            label: 'Jumlah',
            data: {!! json_encode($kepangkatanData->values()) !!},
            backgroundColor: '#dc3545',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartRekognisiTS'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($rekognisiPerTSLabeled)) !!},
        datasets: [{
            label: 'Jumlah Rekognisi',
            data: {!! json_encode(array_values($rekognisiPerTSLabeled)) !!},
            backgroundColor: '#0dcaf0',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartRekognisiDosen'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($rekognisiPerDosen->pluck('kode_dosen')) !!},
        datasets: [{
            label: 'Jumlah Rekognisi',
            data: {!! json_encode($rekognisiPerDosen->pluck('total')) !!},
            backgroundColor: '#6f42c1',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartPmb'), {
    type: 'line',
    data: {
        labels: {!! json_encode($pmbData->pluck('tahun')) !!},
        datasets: [{
            label: 'Jumlah Mhs Baru',
            data: {!! json_encode($pmbData->pluck('jumlah_pmb')) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            fill: true,
            tension: 0.3,
            borderWidth: 2,
            pointBackgroundColor: '#0d6efd',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 50 }
            }
        }
    }
});

new Chart(document.getElementById('chartIpkTrend'), {
    type: 'line',
    data: {
        labels: {!! json_encode($ipkChartLabels) !!},
        datasets: [{
            label: 'Rerata IPK',
            data: {!! json_encode($ipkChartData) !!},
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
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                min: 0.00,
                max: 4.00,
                ticks: { stepSize: 1.00 }
            }
        }
    }
});

new Chart(document.getElementById('chartPksIa'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($pksChartLabels) !!},
        datasets: [
            {
                label: 'PKS',
                data: {!! json_encode($pksChartData) !!},
                backgroundColor: '#10b981',
                borderRadius: 6
            },
            {
                label: 'IA',
                data: {!! json_encode($iaChartData) !!},
                backgroundColor: '#06b6d4',
                borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 12,
                    font: { size: 10 }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartHkiRatio'), {
    type: 'doughnut',
    data: {
        labels: ['Mhs Mandiri', 'Dosen Mandiri', 'Kolaborasi'],
        datasets: [{
            data: [{{ $hkiMhsMandiriCount }}, {{ $hkiDosenMandiriCount }}, {{ $hkiKolaborasiCount }}],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});

new Chart(document.getElementById('chartPrestasiMhs'), {
    type: 'line',
    data: {
        labels: {!! json_encode($prestasiMhsTsLabels) !!},
        datasets: [
            {
                label: 'Akademik',
                data: {!! json_encode($prestasiMhsTsData['Akademik']) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: false,
                borderWidth: 2,
                pointRadius: 4
            },
            {
                label: 'Non Akademik',
                data: {!! json_encode($prestasiMhsTsData['Non Akademik']) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.3,
                fill: false,
                borderWidth: 2,
                pointRadius: 4
            },
            {
                label: 'Akademik Non Lomba',
                data: {!! json_encode($prestasiMhsTsData['Akademik Non Lomba']) !!},
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.3,
                fill: false,
                borderWidth: 2,
                pointRadius: 4
            },
            {
                label: 'Partisipan',
                data: {!! json_encode($prestasiMhsTsData['Partisipan']) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.3,
                fill: false,
                borderWidth: 2,
                pointRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 12,
                    font: { size: 10 }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

new Chart(document.getElementById('chartSerkomMhs'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($serkomChartLabels) !!},
        datasets: [{
            label: 'Jumlah Mahasiswa',
            data: {!! json_encode($serkomChartData) !!},
            backgroundColor: '#8b5cf6',
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<script>
new Chart(document.getElementById('chartPraktisiPerTs'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($praktisiChartLabels) !!},
        datasets: [{
            label: 'Jumlah Praktisi',
            data: {!! json_encode($praktisiChartData) !!},
            backgroundColor: [
                'rgba(124,58,237,0.8)',
                'rgba(79,70,229,0.8)',
                'rgba(139,92,246,0.8)',
                'rgba(167,139,250,0.8)',
            ],
            borderColor: [
                'rgba(124,58,237,1)',
                'rgba(79,70,229,1)',
                'rgba(139,92,246,1)',
                'rgba(167,139,250,1)',
            ],
            borderWidth: 1.5,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        return ' ' + ctx.parsed.y + ' Praktisi';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            },
            x: {
                ticks: {
                    maxRotation: 30,
                    minRotation: 15,
                    font: { size: 10 }
                }
            }
        }
    }
});
</script>
@endpush
