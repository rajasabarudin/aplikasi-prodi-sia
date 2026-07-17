@extends('layouts.app')

@section('title', 'Data Sertifikasi Mahasiswa')

@section('content')
<datalist id="mahasiswa_list">
    @foreach ($mahasiswaList as $mhs)
        <option value="{{ $mhs->nim }}" data-nama="{{ $mhs->nama }}">{{ $mhs->nama }}</option>
    @endforeach
</datalist>

<div class="row">
    <!-- Kiri: Panel Statistik Sertifikasi (d-print-none) -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Sertifikasi</h5>
            </div>
            <div class="card-body">
                <!-- Total Sertifikasi -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Sertifikasi</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalSertifikasi }}</h2>
                </div>

                <!-- Persentase Mahasiswa Memiliki Serkom -->
                <div class="mb-4 pb-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Total Data Sertifikasi:</span>
                        <span class="fw-bold text-dark">{{ $mhsPunyaSerkomCount }} Data</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted">Rasio (terhadap {{ $totalMhsCount }} Mhs):</small>
                        <small class="fw-semibold text-primary">{{ $persenTotalSertifikasi }}%</small>
                    </div>
                    
                    <!-- Mhs Unik & Persentase (yang diminta user) -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Mhs Bersertifikat (Unik):</span>
                            <span class="fw-bold text-dark">{{ $mhsUnikBersertifikat }} Mhs</span>
                        </div>
                        <div class="progress" style="height: 6px;" title="{{ $persenMhsSerkom }}% dari total {{ $totalMhsCount }} mahasiswa memiliki sertifikasi">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenMhsSerkom }}%" aria-valuenow="{{ $persenMhsSerkom }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small class="text-muted">Persentase (dari {{ $totalMhsCount }} Mhs):</small>
                            <small class="fw-semibold text-success">{{ $persenMhsSerkom }}%</small>
                        </div>
                    </div>
                </div>

                <!-- Berdasarkan Skema -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <span class="fw-bold text-dark">Sebaran Skema</span>
                    </div>
                    <div class="ps-1 custom-scroll" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($sertifikasiBySkema as $s)
                            <div class="d-flex justify-content-between text-muted small py-2 border-bottom border-light">
                                <span class="text-dark fw-semibold" style="max-width: 70%; word-break: break-word;">{{ $s->skema_serkom }}</span>
                                <span class="badge bg-primary rounded-pill align-self-start">{{ $s->total }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Grafik Pai (Pie Chart) Sebaran Skema -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Visualisasi Skema</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="skemaPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data Sertifikasi -->
    <div class="col-lg-9 col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Data Sertifikasi Mahasiswa</h1>
                        <p class="text-muted mb-0">Manajemen sertifikasi kompetensi (serkom) mahasiswa</p>
                    </div>
                    <div class="d-flex gap-2 align-self-md-end">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                            <i class="bi bi-file-earmark-excel me-1"></i>Import Excel
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSerkomModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Sertifikasi
                        </button>
                    </div>
                </div>

                <!-- Filter Pencarian -->
                <form action="{{ route('sertifikasi-mahasiswa.index') }}" method="GET" class="mb-3 d-print-none">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan NIM, Nama Mahasiswa, atau Skema..." value="{{ $search }}">
                        @if($search)
                            <a href="{{ route('sertifikasi-mahasiswa.index') }}" class="btn btn-outline-secondary border-start-0"><i class="bi bi-x-lg"></i></a>
                        @endif
                        
                        <!-- Filter Baris Per Halaman -->
                        <span class="input-group-text bg-white border-end-0 border-start-0"><i class="bi bi-list-ol text-muted"></i></span>
                        <select name="per_page" class="form-select border-start-0" onchange="this.form.submit()" style="max-width: 130px; border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;">
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 Baris</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 Baris</option>
                            <option value="1000" {{ $perPage == 1000 ? 'selected' : '' }}>1000 Baris</option>
                        </select>
                        
                        <button class="btn btn-dark" type="submit">Cari</button>
                    </div>
                </form>

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

                <div class="table-responsive rounded">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 20%;">NIM</th>
                                <th style="width: 30%;">Nama Mahasiswa</th>
                                <th style="width: 25%;">Skema Sertifikasi</th>
                                <th style="width: 10%;">Dokumen</th>
                                <th class="text-center d-print-none" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sertifikasis as $s)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ ($sertifikasis->currentPage() - 1) * $sertifikasis->perPage() + $loop->iteration }}</td>
                                    <td class="fw-bold text-dark">{{ $s->nim }}</td>
                                    <td>{{ $s->nama_mhs }}</td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $s->skema_serkom }}</span>
                                    </td>
                                    <td>
                                        @if($s->link_dokumen)
                                            <a href="{{ $s->link_dokumen }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-2 px-3" style="border-radius: 6px;">
                                                <i class="bi bi-link-45deg"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-2 px-3" style="border-radius: 6px; font-weight: 600;">
                                                <i class="bi bi-x-circle me-1"></i> Belum Upload
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSerkomModal"
                                                data-id="{{ $s->id }}"
                                                data-nim="{{ $s->nim }}"
                                                data-nama_mhs="{{ $s->nama_mhs }}"
                                                data-skema_serkom="{{ $s->skema_serkom }}"
                                                data-link_dokumen="{{ $s->link_dokumen }}"
                                                title="Edit Sertifikasi">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('sertifikasi-mahasiswa.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data sertifikasi?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Sertifikasi"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-patch-check display-5 d-block mb-2 text-secondary"></i>
                                        Belum ada data sertifikasi mahasiswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    {{ $sertifikasis->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Sertifikasi -->
<div class="modal fade" id="tambahSerkomModal" tabindex="-1" aria-labelledby="tambahSerkomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sertifikasi-mahasiswa.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahSerkomModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Sertifikasi Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Mahasiswa (NIM) -->
                    <div class="mb-3">
                        <label for="add_serkom_nim" class="form-label fw-semibold">NIM Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="add_serkom_nim" list="mahasiswa_list" class="form-control" required placeholder="Ketik NIM atau pilih dari daftar...">
                    </div>
 
                    <!-- Nama Mahasiswa -->
                    <div class="mb-3">
                        <label for="add_serkom_nama_mhs" class="form-label fw-semibold">Nama Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mhs" id="add_serkom_nama_mhs" class="form-control" required placeholder="Masukkan nama mahasiswa...">
                    </div>

                    <!-- Skema Serkom -->
                    <div class="mb-3">
                        <label for="add_skema_serkom" class="form-label fw-semibold">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <input type="text" name="skema_serkom" id="add_skema_serkom" class="form-control" required placeholder="Contoh: Programmer, Analis Sistem, dll.">
                    </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="add_link_dokumen" class="form-label fw-semibold">Link Upload Dokumen / Sertifikat</label>
                        <input type="url" name="link_dokumen" id="add_link_dokumen" class="form-control" placeholder="https://example.com/sertifikat-kompetensi">
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

<!-- Modal Edit Sertifikasi -->
<div class="modal fade" id="editSerkomModal" tabindex="-1" aria-labelledby="editSerkomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editSerkomModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Sertifikasi Mahasiswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Mahasiswa (NIM) -->
                    <div class="mb-3">
                        <label for="edit_serkom_nim" class="form-label fw-semibold">NIM Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="edit_serkom_nim" list="mahasiswa_list" class="form-control" required placeholder="Ketik NIM atau pilih dari daftar...">
                    </div>
 
                    <!-- Nama Mahasiswa -->
                    <div class="mb-3">
                        <label for="edit_serkom_nama_mhs" class="form-label fw-semibold">Nama Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mhs" id="edit_serkom_nama_mhs" class="form-control" required placeholder="Masukkan nama mahasiswa...">
                    </div>

                    <!-- Skema Serkom -->
                    <div class="mb-3">
                        <label for="edit_skema_serkom" class="form-label fw-semibold">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <input type="text" name="skema_serkom" id="edit_skema_serkom" class="form-control" required>
                    </div>

                    <!-- Link Dokumen -->
                    <div class="mb-3">
                        <label for="edit_link_dokumen" class="form-label fw-semibold">Link Upload Dokumen / Sertifikat</label>
                        <input type="url" name="link_dokumen" id="edit_link_dokumen" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sertifikasi-mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="importExcelModalLabel"><i class="bi bi-file-earmark-excel-fill me-2 text-white"></i>Import Excel Sertifikasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label fw-semibold">Pilih File Excel / CSV <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="import_file" class="form-control" required accept=".xlsx,.xls,.csv">
                        <div class="form-text text-muted mt-2">
                            <i class="bi bi-info-circle me-1"></i> Format file harus sesuai dengan template. 
                            Silakan unduh template di bawah ini sebelum mengunggah.
                        </div>
                    </div>
                    <div class="text-center py-2">
                        <a href="{{ route('sertifikasi-mahasiswa.template') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                            <i class="bi bi-download me-1"></i>Unduh Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill student name in Tambah Modal
        var addNimInput = document.getElementById('add_serkom_nim');
        if (addNimInput) {
            addNimInput.addEventListener('input', function() {
                var val = this.value;
                var datalist = document.getElementById('mahasiswa_list');
                var options = datalist.querySelectorAll('option');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].value === val) {
                        document.getElementById('add_serkom_nama_mhs').value = options[i].getAttribute('data-nama') || '';
                        break;
                    }
                }
            });
        }
 
        // Auto-fill student name in Edit Modal
        var editNimInput = document.getElementById('edit_serkom_nim');
        if (editNimInput) {
            editNimInput.addEventListener('input', function() {
                var val = this.value;
                var datalist = document.getElementById('mahasiswa_list');
                var options = datalist.querySelectorAll('option');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].value === val) {
                        document.getElementById('edit_serkom_nama_mhs').value = options[i].getAttribute('data-nama') || '';
                        break;
                    }
                }
            });
        }

        // Populate Edit Modal
        var editSerkomModal = document.getElementById('editSerkomModal');
        if (editSerkomModal) {
            editSerkomModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                
                var id = button.getAttribute('data-id');
                var nim = button.getAttribute('data-nim');
                var namaMhs = button.getAttribute('data-nama_mhs');
                var skemaSerkom = button.getAttribute('data-skema_serkom');
                var linkDokumen = button.getAttribute('data-link_dokumen');
                
                var action = "{{ route('sertifikasi-mahasiswa.store') }}";
                action = action.replace('sertifikasi-mahasiswa', 'sertifikasi-mahasiswa/' + id);
                
                var form = editSerkomModal.querySelector('form');
                form.setAttribute('action', action);
                
                document.getElementById('edit_serkom_nim').value = nim || '';
                document.getElementById('edit_serkom_nama_mhs').value = namaMhs || '';
                document.getElementById('edit_skema_serkom').value = skemaSerkom || '';
                document.getElementById('edit_link_dokumen').value = linkDokumen || '';
            });
        }

        // Chart.js Pie Chart for Skema
        var ctx = document.getElementById('skemaPieChart').getContext('2d');
        var labels = {!! json_encode($sertifikasiBySkema->pluck('skema_serkom')) !!};
        var data = {!! json_encode($sertifikasiBySkema->pluck('total')) !!};
        
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
                        cornerRadius: 8
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
