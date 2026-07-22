@extends('layouts.app')

@section('title', 'Data Rekognisi Dosen')

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

    /* Efek blur pada latar belakang modal */
    .modal-backdrop {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        background-color: rgba(0, 0, 0, 0.5) !important;
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Rekognisi Dosen Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="row">
    <!-- Kiri: Statistik Rekognisi Dosen (d-print-none) -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Rekognisi</h5>
            </div>
            <div class="card-body bg-light">
                <!-- Total Rekognisi -->
                <div class="mb-4 text-center py-2 bg-white rounded border border-light">
                    <span class="text-muted small d-block">Total Rekognisi</span>
                    <h2 class="fw-bold text-primary mb-0">{{ $totalRekognisi }}</h2>
                </div>

                <!-- Berdasarkan Tri Dharma -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Tri Dharma PT</span>
                    </div>
                    <div class="ps-1">
                        @php
                            $tridharmaLabels = [
                                'penelitian'            => ['label' => 'Penelitian',               'color' => 'bg-primary'],
                                'pengabdian_masyarakat' => ['label' => 'Pengabdian Masy.',         'color' => 'bg-success'],
                                'pendidikan'            => ['label' => 'Pendidikan',               'color' => 'bg-warning text-dark'],
                                'belum_dikategorikan'   => ['label' => 'Belum Dikategorikan',     'color' => 'bg-secondary'],
                            ];
                        @endphp
                        @foreach ($tridharmaLabels as $key => $info)
                            @if(($tridharmaCounts[$key] ?? 0) > 0)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span>{{ $info['label'] }}</span>
                                <span class="badge {{ $info['color'] }} rounded-pill">{{ $tridharmaCounts[$key] }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Berdasarkan Level -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Level</span>
                    </div>
                    <div class="ps-1">
                        @php
                            $levels = ['lokal' => 'Lokal', 'nasional' => 'Nasional', 'internasional' => 'Internasional'];
                        @endphp
                        @foreach ($levels as $key => $label)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span>{{ $label }}</span>
                                <span class="badge bg-success rounded-pill">{{ $levelCounts[$key] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Berdasarkan TS -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan TS</span>
                    </div>
                    <div class="ps-1" style="max-height: 150px; overflow-y: auto;">
                        @forelse ($labelTsCounts as $labelTs => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span>{{ $labelTs }}</span>
                                <span class="badge bg-warning text-dark rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data TS</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan TA -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info text-dark rounded p-1 px-2 me-2">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan TA</span>
                    </div>
                    <div class="ps-1" style="max-height: 150px; overflow-y: auto;">
                        @forelse ($tsCounts as $tsName => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span>{{ $tsName }}</span>
                                <span class="badge bg-info text-dark rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan Dosen -->
                <div class="mb-0">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Dosen</span>
                    </div>
                    <div class="ps-1" style="max-height: 180px; overflow-y: auto;">
                        @forelse ($dosenCounts as $dosenName => $count)
                            <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                <span class="text-truncate" style="max-width: 150px;" title="{{ $dosenName }}">{{ $dosenName }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Data & Tabel Rekognisi -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
            <div>
                <h1 class="mb-0">Data Rekognisi Dosen</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $totalRekognisi }}</strong> rekognisi terdaftar</p>
            </div>
        </div>

        <div class="card mb-3 d-print-none shadow-sm border-0">
            <div class="card-body py-2">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Kode, Nama, Rekognisi, atau Tahun..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="per_page" class="form-select" onchange="this.form.submit()" title="Tampilkan baris">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 Baris</option>
                        </select>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="submit" class="btn btn-primary me-1"><i class="bi bi-search"></i> Cari</button>
                        <a href="{{ route('rekognisi-dosen.index', array_merge(request()->query(), ['print' => 'all'])) }}" target="_blank" class="btn btn-success me-1"><i class="bi bi-printer"></i> Cetak</a>
                        <button type="button" class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#tambahRekognisiModal">
                            <i class="bi bi-plus-circle"></i> Tambah Rekognisi
                        </button>
                        @if (request('search') || request('per_page'))
                            <a href="{{ route('rekognisi-dosen.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th class="text-center">Kode Dosen</th>
                        <th>Nama Dosen</th>
                        <th>Nama Rekognisi</th>
                        <th class="text-center">Tri Dharma</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">TA</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Link Dokumen</th>
                        <th class="text-center d-print-none">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekognisi as $item)
                        <tr>
                            <td class="text-center">{{ $rekognisi instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($rekognisi->currentPage() - 1) * $rekognisi->perPage() + $loop->iteration : $loop->iteration }}</td>
                            <td class="text-center fw-bold">{{ $item->kode_dosen }}</td>
                            <td>{{ $item->nama_dosen }}</td>
                            <td>
                                {{ $item->nama_rekognisi }}
                                @if($item->penelitian_dosen_id)
                                    <div class="mt-1">
                                        <a href="{{ route('penelitian-dosen.show', $item->penelitian_dosen_id) }}" class="badge bg-info-subtle text-info text-decoration-none" style="font-size: 10px;">
                                            <i class="bi bi-journal-text me-1"></i> Penelitian Dosen
                                        </a>
                                    </div>
                                @endif
                                @if($item->hibah_penelitian_id)
                                    <div class="mt-1">
                                        <a href="{{ route('hibah-penelitian.show', $item->hibah_penelitian_id) }}" class="badge bg-warning-subtle text-warning text-decoration-none" style="font-size: 10px;">
                                            <i class="bi bi-cash-coin me-1"></i> Hibah Penelitian
                                        </a>
                                    </div>
                                @endif
                                @if($item->hki_id)
                                    <div class="mt-1">
                                        <span class="badge bg-success-subtle text-success" style="font-size: 10px;">
                                            <i class="bi bi-shield-check me-1"></i> HKI
                                        </span>
                                        @if($item->hki && $item->hki->mahasiswa)
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">
                                                <i class="bi bi-person-fill me-1"></i> Mhs: {{ $item->hki->mahasiswa->nama }} ({{ $item->hki->mahasiswa->nim }})
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                @if($item->prestasi_dosen_id)
                                    <div class="mt-1">
                                        <a href="{{ route('prestasi-dosen.show', $item->prestasi_dosen_id) }}" class="badge bg-danger-subtle text-danger text-decoration-none" style="font-size: 10px;">
                                            <i class="bi bi-trophy me-1"></i> Prestasi Dosen
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $tdMap = [
                                        'penelitian'            => ['label'=>'Penelitian',          'class'=>'bg-primary'],
                                        'pengabdian_masyarakat' => ['label'=>'Pengabdian Masy.',    'class'=>'bg-success'],
                                        'pendidikan'            => ['label'=>'Pendidikan',          'class'=>'bg-warning text-dark'],
                                    ];
                                @endphp
                                @if($item->kategori_tridharma && isset($tdMap[$item->kategori_tridharma]))
                                    <span class="badge {{ $tdMap[$item->kategori_tridharma]['class'] }} rounded-pill" style="font-size:10px;">
                                        {{ $tdMap[$item->kategori_tridharma]['label'] }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->tahun }}</td>
                            <td class="text-center">
                                {{ $item->ts ? ($item->ts->label_ts ? $item->ts->label_ts . ' (' . $item->ts->tahun_sekarang . ')' : $item->ts->tahun_sekarang) : '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $item->level == 'internasional' ? 'primary' : ($item->level == 'nasional' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($item->level) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($item->link_dokumen)
                                    <a href="{{ $item->link_dokumen }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-up-right"></i> Lihat</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rekognisi-dosen.show', $item) }}" class="btn btn-sm btn-info text-white">Detail</a>
                                    <a href="{{ route('rekognisi-dosen.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('rekognisi-dosen.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data rekognisi dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rekognisi instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="d-flex justify-content-center d-print-none mt-3">
                {{ $rekognisi->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

@if ($errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('tambahRekognisiModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

<!-- Modal Tambah Rekognisi Dosen -->
<div class="modal fade" id="tambahRekognisiModal" tabindex="-1" aria-labelledby="tambahRekognisiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('rekognisi-dosen.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahRekognisiModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Rekognisi Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_kode_dosen" class="form-label">Kode Dosen <span class="text-danger">*</span></label>
                            <select name="kode_dosen" id="modal_kode_dosen" class="form-select" data-route="{{ route('rekognisi-dosen.get-dosen', '') }}/" required>
                                <option value="">-- Pilih Kode Dosen --</option>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->kode_dosen }}">
                                        {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nama_dosen" class="form-label">Nama Dosen <span class="text-danger">*</span></label>
                            <input type="text" name="nama_dosen" id="modal_nama_dosen" class="form-control" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_nama_rekognisi" class="form-label">Nama Rekognisi <span class="text-danger">*</span></label>
                            <input type="text" name="nama_rekognisi" id="modal_nama_rekognisi" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="text" name="tahun" id="modal_tahun" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_ts_id" class="form-label">TA <span class="text-danger">*</span></label>
                            <select name="ts_id" id="modal_ts_id" class="form-select" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">
                                        {{ $ts->tahun_sekarang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_level" class="form-label">Level <span class="text-danger">*</span></label>
                            <select name="level" id="modal_level" class="form-select" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="lokal">Lokal</option>
                                <option value="nasional">Nasional</option>
                                <option value="internasional">Internasional</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_kategori_tridharma" class="form-label fw-semibold">
                                <i class="bi bi-diagram-3-fill me-1 text-purple" style="color:#8b5cf6;"></i>
                                Kategori Tri Dharma PT
                            </label>
                            <select name="kategori_tridharma" id="modal_kategori_tridharma" class="form-select">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian_masyarakat">Pengabdian kepada Masyarakat</option>
                                <option value="pendidikan">Pendidikan</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="modal_link_dokumen" class="form-label">Link Dokumen</label>
                            <input type="url" name="link_dokumen" id="modal_link_dokumen" class="form-control" placeholder="https://example.com/dokumen.pdf">
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

@push('scripts')
<script>
    document.getElementById('modal_kode_dosen').addEventListener('change', function() {
        var kode = this.value;
        if (kode) {
            var route = this.getAttribute('data-route');
            fetch(route + kode)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    document.getElementById('modal_nama_dosen').value = data.nama_dosen;
                })
                .catch(function() {
                    document.getElementById('modal_nama_dosen').value = '';
                });
        } else {
            document.getElementById('modal_nama_dosen').value = '';
        }
    });
</script>
@endpush
@endsection
