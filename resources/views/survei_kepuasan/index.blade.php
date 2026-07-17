@extends('layouts.app')

@section('title', 'Data Survei Kepuasan & SPMI')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-ui-checks text-primary me-2"></i>Data Survei Kepuasan & Evaluasi (SPMI)</h2>
            <p class="text-muted mb-0">Rekapitulasi Kuesioner Kepuasan Layanan & Visi Misi (Kriteria C1, C3, C4, C6)</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('obe.pdf-recap', ['kriteria' => 'Survei', 'ppepp' => 'P3']) }}" target="_blank" class="btn btn-outline-danger rounded-pill px-4 shadow-sm mb-2 mb-md-0">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan PDF
            </a>
            @if(Auth::user()->level === 'king' || Auth::user()->level === 'jendral')
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSurveiModal">
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
                    <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Persentase Rata-rata Kepuasan Keseluruhan</h5>
                    <div style="height: 300px;">
                        <canvas id="surveiChart"></canvas>
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
                            <th class="text-center" style="width: 10%;">Tahun</th>
                            <th style="width: 25%;">Jenis Survei / Instrumen</th>
                            <th class="text-center" style="width: 10%;">Sangat Baik</th>
                            <th class="text-center" style="width: 10%;">Baik</th>
                            <th class="text-center" style="width: 10%;">Cukup</th>
                            <th class="text-center" style="width: 10%;">Kurang</th>
                            <th style="width: 20%;">Rencana Tindak Lanjut (CQI)</th>
                            <th class="text-center" style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surveis as $row)
                        <tr>
                            <td class="text-center fw-bold">{{ $row->tahun_akademik }}</td>
                            <td class="fw-semibold text-primary">{{ $row->jenis_survei }}</td>
                            <td class="text-center"><span class="badge bg-success">{{ $row->sangat_baik }}%</span></td>
                            <td class="text-center"><span class="badge bg-primary">{{ $row->baik }}%</span></td>
                            <td class="text-center"><span class="badge bg-warning text-dark">{{ $row->cukup }}%</span></td>
                            <td class="text-center"><span class="badge bg-danger">{{ $row->kurang }}%</span></td>
                            <td class="small text-muted">{{ $row->tindak_lanjut ?: '-' }}</td>
                            <td class="text-center">
                                @if(Auth::user()->level === 'king' || Auth::user()->level === 'jendral')
                                <button type="button" class="btn btn-sm btn-warning rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editSurveiModal{{ $row->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('survei-kepuasan.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data survei ini?');">
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
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data rekapitulasi survei.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSurveiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Rekapitulasi Survei</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('survei-kepuasan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Pelaksanaan</label>
                            <select name="tahun_akademik" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                                @foreach($tsList as $ts)
                                    <option value="{{ $ts->tahun_sekarang }}">{{ $ts->tahun_sekarang }} - {{ $ts->label_ts ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Instrumen Survei</label>
                            <select name="jenis_survei" class="form-select" required>
                                <option value="Pemahaman Visi Misi (Kriteria C1)">Pemahaman Visi Misi (Kriteria C1)</option>
                                <option value="Kepuasan Mahasiswa (Kriteria C3)">Kepuasan Mahasiswa thd Layanan (Kriteria C3)</option>
                                <option value="Kepuasan Dosen & Tendik (Kriteria C4)">Kepuasan Dosen & Tendik thd Manajemen (Kriteria C4)</option>
                                <option value="Kepuasan Pengguna Lulusan (Kriteria C9)">Kepuasan Pengguna Lulusan / Mitra (Kriteria C9)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="alert alert-info py-2 small mb-3">
                        Masukkan persentase responden (%) untuk masing-masing kriteria. Pastikan totalnya mendekati 100%.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sangat Baik (%)</label>
                            <input type="number" name="sangat_baik" class="form-control" value="0" step="0.01" max="100" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Baik (%)</label>
                            <input type="number" name="baik" class="form-control" value="0" step="0.01" max="100" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cukup (%)</label>
                            <input type="number" name="cukup" class="form-control" value="0" step="0.01" max="100" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kurang (%)</label>
                            <input type="number" name="kurang" class="form-control" value="0" step="0.01" max="100" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rencana Tindak Lanjut / Perbaikan (Opsional, untuk Siklus CQI)</label>
                        <textarea name="tindak_lanjut" class="form-control" rows="2" placeholder="Contoh: Meningkatkan kualitas layanan akademik dengan memperbarui sistem SIAKAD..."></textarea>
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

<!-- Edit Modals (Looping at bottom) -->
@foreach($surveis as $row)
<div class="modal fade" id="editSurveiModal{{ $row->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Rekapitulasi Survei ({{ $row->tahun_akademik }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('survei-kepuasan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tahun_akademik" value="{{ $row->tahun_akademik }}">
                    <input type="hidden" name="jenis_survei" value="{{ $row->jenis_survei }}">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Pelaksanaan</label>
                            <input type="text" class="form-control" value="{{ $row->tahun_akademik }}" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Instrumen Survei</label>
                            <input type="text" class="form-control" value="{{ $row->jenis_survei }}" disabled>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sangat Baik (%)</label>
                            <input type="number" name="sangat_baik" class="form-control" required min="0" max="100" step="0.01" value="{{ $row->sangat_baik }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Baik (%)</label>
                            <input type="number" name="baik" class="form-control" required min="0" max="100" step="0.01" value="{{ $row->baik }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cukup (%)</label>
                            <input type="number" name="cukup" class="form-control" required min="0" max="100" step="0.01" value="{{ $row->cukup }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Kurang (%)</label>
                            <input type="number" name="kurang" class="form-control" required min="0" max="100" step="0.01" value="{{ $row->kurang }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Tindak Lanjut (CQI)</label>
                            <textarea name="tindak_lanjut" class="form-control" rows="3" placeholder="Deskripsikan rencana tindak lanjut perbaikan berdasarkan hasil survei ini..." required>{{ $row->tindak_lanjut }}</textarea>
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
        // Average Data Calculation
        const dataSurvei = {!! json_encode($surveis) !!};
        let totalSangatBaik = 0, totalBaik = 0, totalCukup = 0, totalKurang = 0;
        let count = dataSurvei.length;
        
        if (count > 0) {
            dataSurvei.forEach(function(item) {
                totalSangatBaik += parseFloat(item.sangat_baik);
                totalBaik += parseFloat(item.baik);
                totalCukup += parseFloat(item.cukup);
                totalKurang += parseFloat(item.kurang);
            });
            
            totalSangatBaik /= count;
            totalBaik /= count;
            totalCukup /= count;
            totalKurang /= count;
        }

        const ctx = document.getElementById('surveiChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'],
                datasets: [{
                    data: [totalSangatBaik, totalBaik, totalCukup, totalKurang],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',   // Success
                        'rgba(0, 123, 255, 0.8)',   // Primary
                        'rgba(255, 193, 7, 0.8)',   // Warning
                        'rgba(220, 53, 69, 0.8)'    // Danger
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed.toFixed(1) + '%';
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
