@extends('layouts.app')

@section('title', 'Data Matakuliah')

@section('content')
<div class="row">
    <!-- Kiri: Panel Statistik Matakuliah (d-print-none) -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Matakuliah</h5>
            </div>
            <div class="card-body">
                <!-- Total Matakuliah -->
                <div class="mb-3 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Matakuliah</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalMatakuliah }}</h2>
                </div>

                <!-- Total SKS -->
                <div class="mb-4 text-center py-3" style="background: linear-gradient(135deg, #10b981, #059669) !important; color: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                    <span class="text-white-50 small d-block mb-1">Total SKS</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalSks }} SKS</h2>
                </div>

                <!-- Berdasarkan Jenis -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;">
                            <i class="bi bi-tag-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Jenis</span>
                    </div>
                    <div class="ps-1 custom-scroll" style="max-height: 250px; overflow-y: auto;">
                        @forelse ($matakuliahByJenis as $m)
                            @php
                                $coursesOfType = $allMatakuliah->where('jenis_matakuliah', $m->jenis_matakuliah);
                            @endphp
                            <div class="mb-3 border-bottom border-light pb-2">
                                <div class="d-flex justify-content-between text-muted small py-1">
                                    <span class="text-dark fw-bold">{{ $m->jenis_matakuliah }}</span>
                                    <span class="badge bg-secondary rounded-pill">{{ $m->total }}</span>
                                </div>
                                <ul class="list-unstyled ps-2 mb-0 mt-1">
                                    @foreach($coursesOfType as $course)
                                        <li class="text-muted small py-1 d-flex align-items-start" style="font-size: 0.8rem; line-height: 1.2;">
                                            <i class="bi bi-dot text-primary me-1"></i>
                                            <span>{{ $course->nama_matakuliah }} <span class="text-secondary small">({{ $course->sks }} SKS)</span></span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan Semester -->
                <div class="mb-3 border-top pt-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-calendar-range-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Semester</span>
                    </div>
                    <div class="ps-1 custom-scroll" style="max-height: 200px; overflow-y: auto;">
                        @forelse ($matakuliahBySemester as $s)
                            <div class="d-flex justify-content-between text-muted small py-2 border-bottom border-light">
                                <span class="text-dark fw-semibold">Semester {{ $s->semester }}</span>
                                <span class="badge bg-success text-white rounded-pill">{{ $s->total }} Matakuliah</span>
                            </div>
                        @empty
                            <span class="text-muted small">Tidak ada data</span>
                        @endforelse
                    </div>
                </div>

                <!-- Visualisasi Jenis -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Visualisasi Jenis</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="jenisPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data Matakuliah -->
    <div class="col-lg-9 col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-dark">Data Matakuliah</h1>
                        <p class="text-muted mb-0">Manajemen kurikulum dan data matakuliah program studi</p>
                    </div>
                    <div class="d-flex gap-2 align-self-md-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMatakuliahModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Matakuliah
                        </button>
                    </div>
                </div>

                <!-- Filter Pencarian -->
                <form action="{{ route('matakuliah.index') }}" method="GET" class="mb-3 d-print-none">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan kode, nama, jenis, atau semester..." value="{{ $search }}">
                        @if($search)
                            <a href="{{ route('matakuliah.index') }}" class="btn btn-outline-secondary border-start-0"><i class="bi bi-x-lg"></i></a>
                        @endif
                        
                        <!-- Filter Baris Per Halaman -->
                        <span class="input-group-text bg-white border-end-0 border-start-0"><i class="bi bi-list-ol text-muted"></i></span>
                        <select name="per_page" class="form-select border-start-0" onchange="this.form.submit()" style="max-width: 130px; border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 Baris</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Baris</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 Baris</option>
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
                                <th style="width: 10%;">Kode MK</th>
                                <th style="width: 25%;">Nama Matakuliah</th>
                                <th class="text-center" style="width: 8%;">SKS</th>
                                <th style="width: 15%;">Jenis</th>
                                <th class="text-center" style="width: 8%;">Sem</th>
                                <th style="width: 20%;">Dokumen Pembelajaran</th>
                                <th class="text-center d-print-none" style="width: 9%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($matakuliahs as $mk)
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ ($matakuliahs->currentPage() - 1) * $matakuliahs->perPage() + $loop->iteration }}</td>
                                    <td class="fw-bold text-dark">{{ $mk->kode_matakuliah }}</td>
                                    <td class="fw-semibold text-secondary">{{ $mk->nama_matakuliah }}</td>
                                    <td class="text-center align-middle">
                                        <div class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">{{ $mk->sks }} SKS</div>
                                        <div class="d-flex flex-wrap gap-1 justify-content-center mt-1">
                                            <button type="button" class="btn btn-sm {{ $mk->sks_t > 0 ? 'btn-info text-white' : 'btn-light text-secondary border' }} rounded-pill" style="font-size: 0.65rem; padding: 1px 6px; line-height: 1.2;" data-bs-toggle="modal" data-bs-target="#editSksModal" data-id="{{ $mk->id }}" data-sks_t="{{ $mk->sks_t }}" data-sks_pa="{{ $mk->sks_pa }}" data-sks_pu="{{ $mk->sks_pu }}" data-nama="{{ $mk->nama_matakuliah }}">
                                                {{ $mk->sks_t > 0 ? 'T:' . $mk->sks_t : '+ T' }}
                                            </button>
                                            <button type="button" class="btn btn-sm {{ $mk->sks_pa > 0 ? 'btn-warning text-dark' : 'btn-light text-secondary border' }} rounded-pill" style="font-size: 0.65rem; padding: 1px 6px; line-height: 1.2;" data-bs-toggle="modal" data-bs-target="#editSksModal" data-id="{{ $mk->id }}" data-sks_t="{{ $mk->sks_t }}" data-sks_pa="{{ $mk->sks_pa }}" data-sks_pu="{{ $mk->sks_pu }}" data-nama="{{ $mk->nama_matakuliah }}">
                                                {{ $mk->sks_pa > 0 ? 'PA:' . $mk->sks_pa : '+ PA' }}
                                            </button>
                                            <button type="button" class="btn btn-sm {{ $mk->sks_pu > 0 ? 'btn-primary text-white' : 'btn-light text-secondary border' }} rounded-pill" style="font-size: 0.65rem; padding: 1px 6px; line-height: 1.2;" data-bs-toggle="modal" data-bs-target="#editSksModal" data-id="{{ $mk->id }}" data-sks_t="{{ $mk->sks_t }}" data-sks_pa="{{ $mk->sks_pa }}" data-sks_pu="{{ $mk->sks_pu }}" data-nama="{{ $mk->nama_matakuliah }}">
                                                {{ $mk->sks_pu > 0 ? 'PU:' . $mk->sks_pu : '+ PU' }}
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeColor = 'bg-secondary text-white';
                                            if($mk->jenis_matakuliah == 'Ciri Nasional') $badgeColor = 'bg-danger text-white';
                                            elseif($mk->jenis_matakuliah == 'Ciri Institusi') $badgeColor = 'bg-warning text-dark';
                                            elseif($mk->jenis_matakuliah == 'Inti Program Studi') $badgeColor = 'bg-primary text-white';
                                            elseif($mk->jenis_matakuliah == 'Pendukung') $badgeColor = 'bg-info text-dark';
                                        @endphp
                                        <span class="badge {{ $badgeColor }} py-1.5 px-2.5" style="border-radius: 6px; font-size: 0.75rem; font-weight: 600;">{{ $mk->jenis_matakuliah }}</span>
                                    </td>
                                    <td class="text-center fw-bold text-dark">{{ $mk->semester }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <!-- Modul -->
                                            @if($mk->link_modul)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ $mk->link_modul }}" target="_blank" class="btn btn-xs btn-dark py-1 px-2 text-decoration-none text-white fw-semibold" style="font-size: 0.7rem; border-radius: 4px 0 0 4px;" title="Lihat Modul"><i class="bi bi-book me-1"></i>Modul</a>
                                                    <form action="{{ route('matakuliah.clear-document', [$mk->id, 'modul']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus link Modul?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 0 4px 4px 0;" title="Hapus Modul"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 fw-semibold text-decoration-none" style="font-size: 0.7rem; border-radius: 4px;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMatakuliahModal"
                                                    data-id="{{ $mk->id }}"
                                                    data-kode_matakuliah="{{ $mk->kode_matakuliah }}"
                                                    data-nama_matakuliah="{{ $mk->nama_matakuliah }}"
                                                    data-sks_t="{{ $mk->sks_t }}"
                                                    data-sks_pa="{{ $mk->sks_pa }}"
                                                    data-sks_pu="{{ $mk->sks_pu }}"
                                                    data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"
                                                    data-semester="{{ $mk->semester }}"
                                                    data-link_modul="{{ $mk->link_modul }}"
                                                    data-link_rps="{{ $mk->link_rps }}"
                                                    data-link_rtm="{{ $mk->link_rtm }}"
                                                    data-dokumen_tambahan="{{ $mk->dokumen_tambahan }}"
                                                    title="Tambah Modul">
                                                    <i class="bi bi-plus-circle me-1"></i>Modul
                                                </button>
                                            @endif

                                            <!-- RPS (Terintegrasi) -->
                                            @php
                                                $rpsSystem = \App\Models\Rps::where('kode_matakuliah', $mk->kode_matakuliah)->first();
                                            @endphp
                                            @if($rpsSystem)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('penyusunan-rps.cetak', $rpsSystem->id) }}" target="_blank" class="btn btn-xs btn-primary py-1 px-2 text-decoration-none text-white fw-semibold" style="font-size: 0.7rem; border-radius: 4px;" title="Lihat RPS"><i class="bi bi-file-earmark-text me-1"></i>RPS</a>
                                                </div>
                                            @else
                                                <a href="{{ route('penyusunan-rps.index') }}" class="btn btn-xs btn-outline-secondary py-1 px-2 fw-semibold text-decoration-none" style="font-size: 0.7rem; border-radius: 4px;" title="Buat RPS di menu Penyusunan RPS">
                                                    <i class="bi bi-plus-circle me-1"></i>RPS
                                                </a>
                                            @endif

                                            <!-- RTM (Terintegrasi) -->
                                            @php
                                                $rtmSystem = null;
                                                $silabusSystem = null;
                                                if ($rpsSystem) {
                                                    $rtmSystem = \App\Models\Rtm::where('rps_id', $rpsSystem->id)->first();
                                                    $silabusSystem = \App\Models\Silabus::where('rps_id', $rpsSystem->id)->first();
                                                }
                                            @endphp
                                            @if($rtmSystem)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('penyusunan-rtm.cetak', $rtmSystem->id) }}" target="_blank" class="btn btn-xs btn-warning py-1 px-2 text-decoration-none text-dark fw-semibold" style="font-size: 0.7rem; border-radius: 4px;" title="Lihat RTM (Cetak)"><i class="bi bi-file-earmark-ruled me-1"></i>RTM</a>
                                                </div>
                                            @elseif($mk->link_rtm)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ $mk->link_rtm }}" target="_blank" class="btn btn-xs btn-warning py-1 px-2 text-decoration-none text-dark fw-semibold" style="font-size: 0.7rem; border-radius: 4px 0 0 4px;" title="Lihat RTM"><i class="bi bi-file-earmark-ruled me-1"></i>RTM</a>
                                                    <form action="{{ route('matakuliah.clear-document', [$mk->id, 'rtm']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus link RTM?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 0 4px 4px 0;" title="Hapus RTM"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 fw-semibold text-decoration-none" style="font-size: 0.7rem; border-radius: 4px;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMatakuliahModal"
                                                    data-id="{{ $mk->id }}"
                                                    data-kode_matakuliah="{{ $mk->kode_matakuliah }}"
                                                    data-nama_matakuliah="{{ $mk->nama_matakuliah }}"
                                                    data-sks_t="{{ $mk->sks_t }}"
                                                    data-sks_pa="{{ $mk->sks_pa }}"
                                                    data-sks_pu="{{ $mk->sks_pu }}"
                                                    data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"
                                                    data-semester="{{ $mk->semester }}"
                                                    data-link_modul="{{ $mk->link_modul }}"
                                                    data-link_rps="{{ $mk->link_rps }}"
                                                    data-link_rtm="{{ $mk->link_rtm }}"
                                                    data-dokumen_tambahan="{{ $mk->dokumen_tambahan }}"
                                                    title="Tambah RTM">
                                                    <i class="bi bi-plus-circle me-1"></i>RTM
                                                </button>
                                            @endif

                                            <!-- Silabus (Terintegrasi) -->
                                            @if($silabusSystem)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('penyusunan-silabus.cetak', $silabusSystem->id) }}" target="_blank" class="btn btn-xs btn-success py-1 px-2 text-decoration-none text-white fw-semibold" style="font-size: 0.7rem; border-radius: 4px;" title="Lihat Silabus (Cetak)"><i class="bi bi-file-earmark-medical me-1"></i>Silabus</a>
                                                </div>
                                            @else
                                                <a href="{{ route('penyusunan-silabus.index') }}" class="btn btn-xs btn-outline-secondary py-1 px-2 fw-semibold text-decoration-none" style="font-size: 0.7rem; border-radius: 4px;" title="Buat Silabus di menu Penyusunan Silabus">
                                                    <i class="bi bi-plus-circle me-1"></i>Silabus
                                                </a>
                                            @endif

                                            <!-- Dokumen Tambahan -->
                                            @if($mk->dokumen_tambahan)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ $mk->dokumen_tambahan }}" target="_blank" class="btn btn-xs btn-info py-1 px-2 text-decoration-none text-dark fw-semibold" style="font-size: 0.7rem; border-radius: 4px 0 0 4px;" title="Lihat Dokumen Tambahan"><i class="bi bi-folder-plus me-1"></i>Lain</a>
                                                    <form action="{{ route('matakuliah.clear-document', [$mk->id, 'dokumen_tambahan']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus Dokumen Tambahan?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-danger py-1 px-2" style="font-size: 0.7rem; border-radius: 0 4px 4px 0;" title="Hapus Dokumen Tambahan"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 fw-semibold text-decoration-none" style="font-size: 0.7rem; border-radius: 4px;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMatakuliahModal"
                                                    data-id="{{ $mk->id }}"
                                                    data-kode_matakuliah="{{ $mk->kode_matakuliah }}"
                                                    data-nama_matakuliah="{{ $mk->nama_matakuliah }}"
                                                    data-sks_t="{{ $mk->sks_t }}"
                                                    data-sks_pa="{{ $mk->sks_pa }}"
                                                    data-sks_pu="{{ $mk->sks_pu }}"
                                                    data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"
                                                    data-semester="{{ $mk->semester }}"
                                                    data-link_modul="{{ $mk->link_modul }}"
                                                    data-link_rps="{{ $mk->link_rps }}"
                                                    data-link_rtm="{{ $mk->link_rtm }}"
                                                    data-dokumen_tambahan="{{ $mk->dokumen_tambahan }}"
                                                    title="Tambah Dokumen Tambahan">
                                                    <i class="bi bi-plus-circle me-1"></i>Lain
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center d-print-none">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editMatakuliahModal"
                                                data-id="{{ $mk->id }}"
                                                data-kode_matakuliah="{{ $mk->kode_matakuliah }}"
                                                data-nama_matakuliah="{{ $mk->nama_matakuliah }}"
                                                data-sks_t="{{ $mk->sks_t }}"
                                                data-sks_pa="{{ $mk->sks_pa }}"
                                                data-sks_pu="{{ $mk->sks_pu }}"
                                                data-jenis_matakuliah="{{ $mk->jenis_matakuliah }}"
                                                data-semester="{{ $mk->semester }}"
                                                data-link_modul="{{ $mk->link_modul }}"
                                                data-link_rps="{{ $mk->link_rps }}"
                                                data-link_rtm="{{ $mk->link_rtm }}"
                                                data-dokumen_tambahan="{{ $mk->dokumen_tambahan }}"
                                                title="Edit Matakuliah">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('matakuliah.destroy', $mk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus matakuliah {{ $mk->nama_matakuliah }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Matakuliah"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-journal-x display-5 d-block mb-2 text-secondary"></i>
                                        Belum ada data matakuliah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    {{ $matakuliahs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Matakuliah -->
<div class="modal fade" id="tambahMatakuliahModal" tabindex="-1" aria-labelledby="tambahMatakuliahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('matakuliah.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahMatakuliahModalLabel"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Matakuliah</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Kode Matakuliah -->
                    <div class="mb-3">
                        <label for="add_kode_matakuliah" class="form-label fw-semibold">Kode Matakuliah <span class="text-danger">*</span></label>
                        <input type="text" name="kode_matakuliah" id="add_kode_matakuliah" class="form-control" required placeholder="Contoh: MK001, INF204">
                    </div>

                    <!-- Nama Matakuliah -->
                    <div class="mb-3">
                        <label for="add_nama_matakuliah" class="form-label fw-semibold">Nama Matakuliah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_matakuliah" id="add_nama_matakuliah" class="form-control" required placeholder="Contoh: Basis Data, Algoritma Pemrograman">
                    </div>

                    <!-- SKS (T, PA, PU) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah SKS <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-4">
                                <label for="add_sks_t" class="form-label small mb-1">Teori (T)</label>
                                <input type="number" name="sks_t" id="add_sks_t" class="form-control" min="0" value="0" required onchange="calculateSksAdd()">
                            </div>
                            <div class="col-4">
                                <label for="add_sks_pa" class="form-label small mb-1">Praktikum (PA)</label>
                                <input type="number" name="sks_pa" id="add_sks_pa" class="form-control" min="0" value="0" required onchange="calculateSksAdd()">
                            </div>
                            <div class="col-4">
                                <label for="add_sks_pu" class="form-label small mb-1">Praktek L. (PU)</label>
                                <input type="number" name="sks_pu" id="add_sks_pu" class="form-control" min="0" value="0" required onchange="calculateSksAdd()">
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light fw-bold">Total SKS</span>
                                <input type="text" id="add_sks_total" class="form-control bg-white fw-bold text-primary" readonly value="0">
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Matakuliah -->
                    <div class="mb-3">
                        <label for="add_jenis_matakuliah" class="form-label fw-semibold">Jenis Matakuliah <span class="text-danger">*</span></label>
                        <select name="jenis_matakuliah" id="add_jenis_matakuliah" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Ciri Nasional">Ciri Nasional</option>
                            <option value="Ciri Institusi">Ciri Institusi</option>
                            <option value="Inti Program Studi">Inti Program Studi</option>
                            <option value="Pendukung">Pendukung</option>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="mb-3">
                        <label for="add_semester" class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester" id="add_semester" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="I">I (Satu)</option>
                            <option value="II">II (Dua)</option>
                            <option value="III">III (Tiga)</option>
                            <option value="IV">IV (Empat)</option>
                            <option value="V">V (Lima)</option>
                            <option value="VI">VI (Enam)</option>
                            <option value="VII">VII (Tujuh)</option>
                            <option value="VIII">VIII (Delapan)</option>
                        </select>
                    </div>

                    <!-- Link Modul -->
                    <div class="mb-3">
                        <label for="add_link_modul" class="form-label fw-semibold">Link Modul Pembelajaran <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_modul" id="add_link_modul" class="form-control" placeholder="https://example.com/modul-mk">
                    </div>

                    <!-- Link RPS -->
                    <div class="mb-3">
                        <label for="add_link_rps" class="form-label fw-semibold">Link RPS <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_rps" id="add_link_rps" class="form-control" placeholder="https://example.com/rps-mk">
                    </div>

                    <!-- Link RTM -->
                    <div class="mb-3">
                        <label for="add_link_rtm" class="form-label fw-semibold">Link RTM <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_rtm" id="add_link_rtm" class="form-control" placeholder="https://example.com/rtm-mk">
                    </div>

                    <!-- Dokumen Tambahan -->
                    <div class="mb-3">
                        <label for="add_dokumen_tambahan" class="form-label fw-semibold">Link Dokumen Tambahan <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="dokumen_tambahan" id="add_dokumen_tambahan" class="form-control" placeholder="https://example.com/dokumen-tambahan">
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

<!-- Modal Edit Matakuliah -->
<div class="modal fade" id="editMatakuliahModal" tabindex="-1" aria-labelledby="editMatakuliahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editMatakuliahModalLabel"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Matakuliah</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Kode Matakuliah -->
                    <div class="mb-3">
                        <label for="edit_kode_matakuliah" class="form-label fw-semibold">Kode Matakuliah <span class="text-danger">*</span></label>
                        <input type="text" name="kode_matakuliah" id="edit_kode_matakuliah" class="form-control" required>
                    </div>

                    <!-- Nama Matakuliah -->
                    <div class="mb-3">
                        <label for="edit_nama_matakuliah" class="form-label fw-semibold">Nama Matakuliah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_matakuliah" id="edit_nama_matakuliah" class="form-control" required>
                    </div>

                    <!-- SKS (T, PA, PU) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah SKS <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-4">
                                <label for="edit_sks_t" class="form-label small mb-1">Teori (T)</label>
                                <input type="number" name="sks_t" id="edit_sks_t" class="form-control" min="0" required onchange="calculateSksEdit()">
                            </div>
                            <div class="col-4">
                                <label for="edit_sks_pa" class="form-label small mb-1">Praktikum (PA)</label>
                                <input type="number" name="sks_pa" id="edit_sks_pa" class="form-control" min="0" required onchange="calculateSksEdit()">
                            </div>
                            <div class="col-4">
                                <label for="edit_sks_pu" class="form-label small mb-1">Praktek L. (PU)</label>
                                <input type="number" name="sks_pu" id="edit_sks_pu" class="form-control" min="0" required onchange="calculateSksEdit()">
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light fw-bold">Total SKS</span>
                                <input type="text" id="edit_sks_total" class="form-control bg-white fw-bold text-primary" readonly value="0">
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Matakuliah -->
                    <div class="mb-3">
                        <label for="edit_jenis_matakuliah" class="form-label fw-semibold">Jenis Matakuliah <span class="text-danger">*</span></label>
                        <select name="jenis_matakuliah" id="edit_jenis_matakuliah" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Ciri Nasional">Ciri Nasional</option>
                            <option value="Ciri Institusi">Ciri Institusi</option>
                            <option value="Inti Program Studi">Inti Program Studi</option>
                            <option value="Pendukung">Pendukung</option>
                        </select>
                    </div>

                    <!-- Semester -->
                    <div class="mb-3">
                        <label for="edit_semester" class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester" id="edit_semester" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="I">I (Satu)</option>
                            <option value="II">II (Dua)</option>
                            <option value="III">III (Tiga)</option>
                            <option value="IV">IV (Empat)</option>
                            <option value="V">V (Lima)</option>
                            <option value="VI">VI (Enam)</option>
                            <option value="VII">VII (Tujuh)</option>
                            <option value="VIII">VIII (Delapan)</option>
                        </select>
                    </div>

                    <!-- Link Modul -->
                    <div class="mb-3">
                        <label for="edit_link_modul" class="form-label fw-semibold">Link Modul Pembelajaran <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_modul" id="edit_link_modul" class="form-control">
                    </div>

                    <!-- Link RPS -->
                    <div class="mb-3">
                        <label for="edit_link_rps" class="form-label fw-semibold">Link RPS <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_rps" id="edit_link_rps" class="form-control">
                    </div>

                    <!-- Link RTM -->
                    <div class="mb-3">
                        <label for="edit_link_rtm" class="form-label fw-semibold">Link RTM <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="link_rtm" id="edit_link_rtm" class="form-control">
                    </div>

                    <!-- Dokumen Tambahan -->
                    <div class="mb-3">
                        <label for="edit_dokumen_tambahan" class="form-label fw-semibold">Link Dokumen Tambahan <span class="text-muted small">(Opsional)</span></label>
                        <input type="url" name="dokumen_tambahan" id="edit_dokumen_tambahan" class="form-control">
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

<!-- Modal Edit SKS -->
<div class="modal fade" id="editSksModal" tabindex="-1" aria-labelledby="editSksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="" method="POST" id="formEditSks">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editSksModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit SKS</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2" id="editSksNama"></p>
                    <div class="mb-2">
                        <label for="edit_only_sks_t" class="form-label small mb-1">Teori (T)</label>
                        <input type="number" name="sks_t" id="edit_only_sks_t" class="form-control form-control-sm" min="0" required onchange="calculateSksOnly()">
                    </div>
                    <div class="mb-2">
                        <label for="edit_only_sks_pa" class="form-label small mb-1">Praktikum (PA)</label>
                        <input type="number" name="sks_pa" id="edit_only_sks_pa" class="form-control form-control-sm" min="0" required onchange="calculateSksOnly()">
                    </div>
                    <div class="mb-2">
                        <label for="edit_only_sks_pu" class="form-label small mb-1">Praktek L. (PU)</label>
                        <input type="number" name="sks_pu" id="edit_only_sks_pu" class="form-control form-control-sm" min="0" required onchange="calculateSksOnly()">
                    </div>
                    <div class="mt-3 border-top pt-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light fw-bold">Total SKS</span>
                            <input type="text" id="edit_only_sks_total" class="form-control bg-white fw-bold text-primary text-end" readonly value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function calculateSksAdd() {
        const t = parseInt(document.getElementById('add_sks_t').value) || 0;
        const pa = parseInt(document.getElementById('add_sks_pa').value) || 0;
        const pu = parseInt(document.getElementById('add_sks_pu').value) || 0;
        document.getElementById('add_sks_total').value = t + pa + pu;
    }

    function calculateSksEdit() {
        const t = parseInt(document.getElementById('edit_sks_t').value) || 0;
        const pa = parseInt(document.getElementById('edit_sks_pa').value) || 0;
        const pu = parseInt(document.getElementById('edit_sks_pu').value) || 0;
        document.getElementById('edit_sks_total').value = t + pa + pu;
    }

    function calculateSksOnly() {
        const t = parseInt(document.getElementById('edit_only_sks_t').value) || 0;
        const pa = parseInt(document.getElementById('edit_only_sks_pa').value) || 0;
        const pu = parseInt(document.getElementById('edit_only_sks_pu').value) || 0;
        document.getElementById('edit_only_sks_total').value = t + pa + pu;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Populate Edit SKS Modal
        var editSksModal = document.getElementById('editSksModal');
        if (editSksModal) {
            editSksModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var nama = button.getAttribute('data-nama');
                var sks_t = button.getAttribute('data-sks_t');
                var sks_pa = button.getAttribute('data-sks_pa');
                var sks_pu = button.getAttribute('data-sks_pu');

                var form = document.getElementById('formEditSks');
                form.action = "{{ route('matakuliah.index') }}/" + id + "/update-sks";

                document.getElementById('editSksNama').textContent = nama;
                document.getElementById('edit_only_sks_t').value = sks_t || '0';
                document.getElementById('edit_only_sks_pa').value = sks_pa || '0';
                document.getElementById('edit_only_sks_pu').value = sks_pu || '0';
                calculateSksOnly();
            });
        }

        // Populate Edit Modal

        var editMatakuliahModal = document.getElementById('editMatakuliahModal');
        if (editMatakuliahModal) {
            editMatakuliahModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                
                var id = button.getAttribute('data-id');
                var kode = button.getAttribute('data-kode_matakuliah');
                var nama = button.getAttribute('data-nama_matakuliah');
                var sks_t = button.getAttribute('data-sks_t');
                var sks_pa = button.getAttribute('data-sks_pa');
                var sks_pu = button.getAttribute('data-sks_pu');
                var jenis = button.getAttribute('data-jenis_matakuliah');
                var semester = button.getAttribute('data-semester');
                
                var action = "{{ route('matakuliah.store') }}";
                action = action.replace('matakuliah', 'matakuliah/' + id);
                
                var form = editMatakuliahModal.querySelector('form');
                form.setAttribute('action', action);
                
                var linkModul = button.getAttribute('data-link_modul');
                var linkRps = button.getAttribute('data-link_rps');
                var linkRtm = button.getAttribute('data-link_rtm');
                var dokumenTambahan = button.getAttribute('data-dokumen_tambahan');

                document.getElementById('edit_kode_matakuliah').value = kode || '';
                document.getElementById('edit_nama_matakuliah').value = nama || '';
                document.getElementById('edit_sks_t').value = sks_t || '0';
                document.getElementById('edit_sks_pa').value = sks_pa || '0';
                document.getElementById('edit_sks_pu').value = sks_pu || '0';
                calculateSksEdit();
                document.getElementById('edit_jenis_matakuliah').value = jenis || '';
                document.getElementById('edit_semester').value = semester || '';
                document.getElementById('edit_link_modul').value = linkModul || '';
                document.getElementById('edit_link_rps').value = linkRps || '';
                document.getElementById('edit_link_rtm').value = linkRtm || '';
                document.getElementById('edit_dokumen_tambahan').value = dokumenTambahan || '';
            });
        }

        // Chart.js Pie Chart for Jenis Matakuliah
        var ctx = document.getElementById('jenisPieChart').getContext('2d');
        var labels = {!! json_encode($matakuliahByJenis->pluck('jenis_matakuliah')) !!};
        var data = {!! json_encode($matakuliahByJenis->pluck('total')) !!};
        
        var colors = [
            '#ef4444', '#f59e0b', '#3b82f6', '#10b981'
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
