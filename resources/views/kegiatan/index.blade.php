@extends('layouts.app')

@section('title', 'Manajemen Kegiatan')

@section('content')
<div class="row">
    <!-- Panel Statistik Kegiatan -->
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #0f172a !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Statistik</h5>
            </div>
            <div class="card-body">
                <!-- Total Kegiatan -->
                <div class="mb-3 text-center py-3" style="background: linear-gradient(135deg, #6366f1, #4f46e5) !important; border-radius: 12px;">
                    <span class="text-white-50 small d-block mb-1">Total Kegiatan</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $kegiatans->count() }}</h2>
                </div>

                <!-- Statistik Jumlah Pendaftar Berdasarkan Kegiatan (Grafik) -->
                <div class="mb-2">
                    <span class="text-muted fw-bold small d-block mb-2"><i class="bi bi-graph-up text-success me-1"></i>Tren Pendaftar Kegiatan:</span>
                    <div style="height: 180px; width: 100%; position: relative;">
                        <canvas id="chartKegiatanPeserta"></canvas>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm mt-3" style="background-color: #f0fdf4; color: #15803d; border-radius: 12px;">
                    <small>
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Gunakan fitur ini untuk membuat kegiatan prodi, mengelola daftar peserta, melakukan presensi masuk & selesai (barcode/QR), dan menerbitkan sertifikat kehadiran otomatis.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kegiatan -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark" style="font-size: 1.8rem;">Daftar Kegiatan Program Studi</h1>
                <p class="text-muted mb-0">Kelola kegiatan, presensi, dan e-sertifikat prodi.</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal">
                    <i class="bi bi-plus-circle me-1"></i>Buat Kegiatan
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0 bg-white">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 30%;">Nama Kegiatan</th>
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 20%;">Tempat</th>
                        <th style="width: 15%;">Peserta</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatans as $k)
                        @php
                            $today = \Carbon\Carbon::now()->startOfDay();
                            $eventDate = \Carbon\Carbon::parse($k->tanggal)->startOfDay();
                            
                            if ($eventDate->lt($today)) {
                                $statusKegiatan = 'terlaksana';
                            } elseif ($eventDate->eq($today)) {
                                $statusKegiatan = 'sedang_berlangsung';
                            } else {
                                $statusKegiatan = 'akan_berlangsung';
                            }
                        @endphp
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('kegiatan.show', $k) }}" class="fw-bold text-primary text-decoration-none d-block mb-1">
                                    {{ $k->nama_kegiatan }}
                                </a>
                                @if($k->jenis_kegiatan === 'berbayar')
                                    <span class="badge bg-danger mb-1" style="font-size: 10px;"><i class="bi bi-cash-coin me-1"></i>Berbayar</span>
                                @else
                                    <span class="badge bg-success mb-1" style="font-size: 10px;"><i class="bi bi-gift-fill me-1"></i>Gratis</span>
                                @endif

                                @if($statusKegiatan === 'terlaksana')
                                    <span class="badge bg-secondary mb-1" style="font-size: 10px;"><i class="bi bi-calendar-check me-1"></i>Sudah Terlaksana</span>
                                @elseif($statusKegiatan === 'sedang_berlangsung')
                                    <span class="badge bg-warning text-dark mb-1" style="font-size: 10px;"><i class="bi bi-play-circle me-1"></i>Sedang Berlangsung</span>
                                @else
                                    <span class="badge bg-primary mb-1" style="font-size: 10px;"><i class="bi bi-calendar-event me-1"></i>Akan Berlangsung</span>
                                @endif

                                @if($k->narasumber)
                                    <small class="text-muted d-block"><i class="bi bi-person-workspace me-1"></i>Narsum: {{ $k->narasumber }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary p-2" style="font-size: 12px;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <small class="fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $k->tempat }}</small>
                            </td>
                            <td>
                                <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-people-fill me-1"></i>{{ $k->pesertas_count }} Peserta
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('kegiatan.show', $k) }}" class="btn btn-sm btn-info text-white" title="Detail / Kelola"><i class="bi bi-eye"></i> Kelola</a>
                                    <form action="{{ route('kegiatan.destroy', $k) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini beserta seluruh data pesertanya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data kegiatan prodi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Kegiatan -->
<div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('kegiatan.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahKegiatanModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Buat Kegiatan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Nama Kegiatan -->
                        <div class="col-md-12 mb-3">
                            <label for="nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required placeholder="Masukkan Nama Kegiatan">
                        </div>

                        <!-- Tanggal & Tempat -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat" class="form-label fw-semibold">Tempat Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="text" name="tempat" id="tempat" class="form-control" required placeholder="Contoh: Aula lantai 3 / Zoom Meeting">
                        </div>

                        <!-- Tanggal Pendaftaran Buka & Tutup -->
                        <div class="col-md-6 mb-3">
                            <label for="tgl_pendaftaran_buka" class="form-label fw-semibold">Tanggal Pendaftaran Buka <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pendaftaran_buka" id="tgl_pendaftaran_buka" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_pendaftaran_tutup" class="form-label fw-semibold">Batas Tanggal Pendaftaran <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pendaftaran_tutup" id="tgl_pendaftaran_tutup" class="form-control" required>
                        </div>

                        <!-- Narasumber -->
                        <div class="col-md-12 mb-3">
                            <label for="narasumber" class="form-label fw-semibold">Narasumber / Pembicara <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="narasumber" id="narasumber" class="form-control" placeholder="Nama Narasumber">
                        </div>

                        <!-- Deskripsi -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="2" placeholder="Informasi tambahan kegiatan..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bidang_kegiatan" class="form-label fw-semibold">Bidang Tridharma <span class="text-danger">*</span></label>
                                <select name="bidang_kegiatan" id="bidang_kegiatan" class="form-select" required>
                                    <option value="Pendidikan">Pendidikan / Akademik</option>
                                    <option value="Penelitian">Penelitian</option>
                                    <option value="Pengabdian Masyarakat">Pengabdian Kepada Masyarakat</option>
                                    <option value="Lainnya">Lainnya / Umum</option>
                                </select>
                            </div>
                            <!-- Payment Option -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kegiatan" class="form-label fw-semibold">Sifat Kegiatan <span class="text-danger">*</span></label>
                                <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select" required onchange="togglePaymentInputs(this.value)">
                                    <option value="gratis">Gratis / Free</option>
                                    <option value="berbayar">Berbayar</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8 mb-3 payment-field" style="display: none;">
                            <label for="harga" class="form-label fw-semibold">Harga Tiket (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" id="harga" class="form-control" placeholder="Contoh: 50000" min="0" value="0">
                        </div>
                        <div class="col-md-12 mb-3 payment-field" style="display: none;">
                            <label for="rekening_info" class="form-label fw-semibold">Informasi Rekening / Metode Pembayaran <span class="text-danger">*</span></label>
                            <textarea name="rekening_info" id="rekening_info" class="form-control" rows="2" placeholder="Contoh: Transfer Bank BCA 123456789 a.n. UBSI atau scan QRIS berikut..."></textarea>
                        </div>

                        <script>
                            function togglePaymentInputs(val) {
                                const els = document.querySelectorAll('.payment-field');
                                const priceInput = document.getElementById('harga');
                                const bankInput = document.getElementById('rekening_info');
                                if (val === 'berbayar') {
                                    els.forEach(el => el.style.display = 'block');
                                    priceInput.required = true;
                                    bankInput.required = true;
                                } else {
                                    els.forEach(el => el.style.display = 'none');
                                    priceInput.required = false;
                                    bankInput.required = false;
                                    priceInput.value = 0;
                                    bankInput.value = '';
                                }
                            }
                        </script>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-laptop-fill text-primary me-2"></i>Presensi Mandiri Online (Khusus Zoom/Meet)</h6>
                        <div class="col-md-6 mb-3">
                            <label for="pin_masuk" class="form-label fw-semibold">PIN Presensi Masuk <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="pin_masuk" id="pin_masuk" class="form-control" placeholder="Contoh: MASUK123">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pin_pulang" class="form-label fw-semibold">PIN Presensi Pulang <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="pin_pulang" id="pin_pulang" class="form-control" placeholder="Contoh: PULANG456">
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-patch-check-fill text-warning me-2"></i>Konfigurasi E-Sertifikat</h6>

                        <!-- Tanda Tangan Sertifikat -->
                        <div class="col-md-6 mb-3">
                            <label for="tanda_tangan_nama" class="form-label fw-semibold">Nama Penandatangan <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="tanda_tangan_nama" id="tanda_tangan_nama" class="form-control" placeholder="Contoh: Dr. Ir. H. Budi Santoso, M.T.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanda_tangan_jabatan" class="form-label fw-semibold">Jabatan Penandatangan <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="tanda_tangan_jabatan" id="tanda_tangan_jabatan" class="form-control" placeholder="Contoh: Ketua Program Studi Teknik Informatika">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $sortedKegiatans = $kegiatans->sortBy('tanggal')->values();
            $chartLabels = $sortedKegiatans->pluck('nama_kegiatan')->map(function($name) {
                return strlen($name) > 12 ? substr($name, 0, 12) . '...' : $name;
            })->toArray();
            $chartData = $sortedKegiatans->pluck('pesertas_count')->toArray();
        @endphp

        new Chart(document.getElementById('chartKegiatanPeserta'), {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pendaftar',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#10b981',
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
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                const fullNames = {!! json_encode($sortedKegiatans->pluck('nama_kegiatan')->toArray()) !!};
                                return fullNames[index];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 8.5
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
