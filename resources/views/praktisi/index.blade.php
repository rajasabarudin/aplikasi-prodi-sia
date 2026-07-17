@extends('layouts.app')

@section('title', 'Data Praktisi')

@section('content')
<div class="row">
    <!-- Kiri: Panel Statistik Praktisi -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #000 !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Praktisi</h5>
            </div>
            <div class="card-body">
                <!-- Total Praktisi -->
                <div class="mb-3 text-center py-3" style="background: linear-gradient(135deg, #f59e0b, #d97706) !important; border-radius: 12px;">
                    <span class="text-white-50 small d-block mb-1">Total Praktisi</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $praktisi->count() }}</h2>
                </div>

                <!-- Detail Statistik Mini -->
                <div class="row g-2 mb-4 text-center">
                    <div class="col-6">
                        <div class="bg-white border rounded p-2 shadow-sm">
                            <span class="text-muted small d-block mb-1">Matakuliah</span>
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-bookmark text-primary me-1"></i>{{ $totalUniqueMatakuliah }}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-white border rounded p-2 shadow-sm">
                            <span class="text-muted small d-block mb-1">Total SKS</span>
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-award text-success me-1"></i>{{ $totalSksTaught }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Berdasarkan TS -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-warning text-dark rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #f59e0b, #d97706) !important;">
                            <i class="bi bi-tags-fill text-white"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan TS</span>
                    </div>
                    <div class="ps-1">
                        @forelse ($labelTsPraktisi as $labelTs => $count)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                <span class="text-dark fw-semibold small">{{ $labelTs }}</span>
                                <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">{{ $count }} Praktisi</span>
                            </div>
                        @empty
                            <span class="text-muted small">Belum ada data TS.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Berdasarkan TA -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-primary text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan TA</span>
                    </div>
                    <div class="ps-1">
                        @forelse ($praktisiPerTs as $tsName => $count)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                                <span class="text-dark fw-semibold small">{{ $tsName }}</span>
                                <span class="badge bg-primary rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">{{ $count }} Praktisi</span>
                            </div>
                        @empty
                            <span class="text-muted small">Belum ada data TA.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="alert alert-warning mb-0 border-0 shadow-sm" style="background-color: #fffbeb; color: #b45309;">
                    <small>
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Data Praktisi memuat rekam jejak praktisi mengajar profesional yang berkontribusi dalam pengajaran di program studi (mendukung lebih dari satu matakuliah & kelas) pada Tahun Akademik (TA) tertentu.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data Praktisi -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data Praktisi</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $praktisi->count() }}</strong> praktisi terdaftar</p>
            </div>
            <div class="d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPraktisiModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Praktisi
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
                        <th class="text-center" style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama Praktisi</th>
                        <th style="width: 15%;">Tahun Akademik (TA)</th>
                        <th style="width: 30%;">Matakuliah yang Diampu (SKS)</th>
                        <th style="width: 10%;">Kelas</th>
                        <th class="text-center" style="width: 12%;">Dokumen Pendukung</th>
                        <th class="text-center d-print-none" style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($praktisi as $p)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">
                                {{ $p->nama_praktisi }}
                                @if($p->pendidikan_terakhir)
                                    <span class="d-block text-muted small fw-normal mt-1"><i class="bi bi-mortarboard-fill me-1"></i>{{ $p->pendidikan_terakhir }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm" style="font-size: 13px;">
                                    {{ $p->ts ? ($p->ts->label_ts ? $p->ts->label_ts . ' (' . $p->ts->tahun_sekarang . ')' : $p->ts->tahun_sekarang) : 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <ul class="ps-3 mb-0">
                                    @forelse($p->matakuliahs as $mk)
                                        <li class="mb-1">
                                            <span class="fw-semibold text-dark">{{ $mk->nama_matakuliah }}</span>
                                            <small class="text-muted d-block">Kode: {{ $mk->kode_matakuliah }} | <strong>{{ $mk->sks }} SKS</strong></small>
                                        </li>
                                    @empty
                                        @foreach($p->kode_matakuliah ?? [] as $code)
                                            <li class="text-danger fw-semibold">{{ $code }}</li>
                                        @endforeach
                                    @endforelse
                                </ul>
                                @if($p->matakuliahs->isNotEmpty())
                                    <div class="mt-2 pt-2 border-top text-primary fw-bold d-flex justify-content-between align-items-center" style="font-size: 11px;">
                                        <span><i class="bi bi-book-half me-1"></i> {{ $p->matakuliahs->count() }} Matakuliah</span>
                                        <span class="badge bg-primary text-white">{{ $p->matakuliahs->sum('sks') }} SKS</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse ($p->kelas ?? [] as $kls)
                                        <span class="badge bg-secondary px-2 py-1">{{ $kls }}</span>
                                    @empty
                                        <span class="text-muted small">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    @if($p->link_ijazah)
                                        <a href="{{ filter_var($p->link_ijazah, FILTER_VALIDATE_URL) ? $p->link_ijazah : asset('storage/' . $p->link_ijazah) }}" target="_blank" class="btn btn-xs btn-primary text-white py-0.5 px-2 text-decoration-none small w-100 mb-1" style="font-size: 11px;">
                                            <i class="bi bi-eye-fill me-1"></i>Ijazah
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 small upload-btn w-100 mb-1" style="font-size: 11px;" 
                                            data-id="{{ $p->id }}" 
                                            data-nama="{{ $p->nama_praktisi }}" 
                                            data-tipe="link_ijazah" 
                                            data-label="Ijazah" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#uploadDokumenModal">
                                            <i class="bi bi-upload me-1"></i>Ijazah
                                        </button>
                                    @endif

                                    @if($p->link_sertifikasi)
                                        <a href="{{ filter_var($p->link_sertifikasi, FILTER_VALIDATE_URL) ? $p->link_sertifikasi : asset('storage/' . $p->link_sertifikasi) }}" target="_blank" class="btn btn-xs btn-success text-white py-0.5 px-2 text-decoration-none small w-100 mb-1" style="font-size: 11px;">
                                            <i class="bi bi-patch-check-fill me-1"></i>Sertifikat
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-success py-0.5 px-2 small upload-btn w-100 mb-1" style="font-size: 11px;" 
                                            data-id="{{ $p->id }}" 
                                            data-nama="{{ $p->nama_praktisi }}" 
                                            data-tipe="link_sertifikasi" 
                                            data-label="Sertifikat Kompetensi" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#uploadDokumenModal">
                                            <i class="bi bi-upload me-1"></i>Sertifikat
                                        </button>
                                    @endif

                                    @if($p->link_dokumen)
                                        <a href="{{ filter_var($p->link_dokumen, FILTER_VALIDATE_URL) ? $p->link_dokumen : asset('storage/' . $p->link_dokumen) }}" target="_blank" class="btn btn-xs btn-info text-white py-0.5 px-2 text-decoration-none small w-100 mb-1" style="font-size: 11px;">
                                            <i class="bi bi-link-45deg me-1"></i>Dokumen
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-info py-0.5 px-2 small upload-btn w-100 mb-1" style="font-size: 11px;" 
                                            data-id="{{ $p->id }}" 
                                            data-nama="{{ $p->nama_praktisi }}" 
                                            data-tipe="link_dokumen" 
                                            data-label="Dokumen Pendukung Lainnya" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#uploadDokumenModal">
                                            <i class="bi bi-upload me-1"></i>Dokumen
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('praktisi.edit', $p) }}" class="btn btn-sm btn-warning" title="Edit Praktisi"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('praktisi.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data praktisi {{ $p->nama_praktisi }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data praktisi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Praktisi -->
<div class="modal fade" id="tambahPraktisiModal" tabindex="-1" aria-labelledby="tambahPraktisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('praktisi.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahPraktisiModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Data Praktisi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nama Praktisi -->
                    <div class="mb-3">
                        <label for="nama_praktisi" class="form-label fw-semibold">Nama Praktisi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_praktisi" id="nama_praktisi" class="form-control" required placeholder="Masukkan Nama Praktisi">
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div class="mb-3">
                        <label for="pendidikan_terakhir" class="form-label fw-semibold">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-control" placeholder="Contoh: S2 Teknik Informatika">
                    </div>

                    <!-- TA (Tahun Akademik) -->
                    <div class="mb-3">
                        <label for="ts_id" class="form-label fw-semibold">Tahun Akademik (TA) <span class="text-danger">*</span></label>
                        <select name="ts_id" id="ts_id" class="form-select" required>
                            <option value="">-- Pilih TA --</option>
                            @foreach($tsList as $ts)
                                <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Matakuliah (Multiple Checkbox) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Matakuliah yang Diampu <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-white" style="max-height: 200px; overflow-y: auto;">
                            @forelse($matakuliahList as $mk)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="kode_matakuliah[]" value="{{ $mk->kode_matakuliah }}" id="mk_{{ $mk->kode_matakuliah }}">
                                    <label class="form-check-label text-dark small" for="mk_{{ $mk->kode_matakuliah }}">
                                        <strong>{{ $mk->kode_matakuliah }}</strong> - {{ $mk->nama_matakuliah }} ({{ $mk->sks }} SKS)
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada data matakuliah.</span>
                            @endforelse
                        </div>
                        <div class="form-text text-muted">Dapat memilih lebih dari 1 matakuliah.</div>
                    </div>

                    <!-- Kelas (Multiple Checkbox) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas Mengajar <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                            @forelse($kelasList as $kls)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="kelas[]" value="{{ $kls->nama_kelas }}" id="kelas_{{ $kls->nama_kelas }}">
                                    <label class="form-check-label text-dark small" for="kelas_{{ $kls->nama_kelas }}">
                                        {{ $kls->nama_kelas }}
                                    </label>
                                </div>
                            @empty
                                <span class="text-muted small">Belum ada data kelas.</span>
                            @endforelse
                        </div>
                        <div class="form-text text-muted">Dapat memilih lebih dari 1 kelas.</div>
                    </div>

                    <!-- Link Ijazah -->
                    <div class="mb-3">
                        <label for="link_ijazah" class="form-label fw-semibold">Link Ijazah <span class="text-muted">(Optional)</span></label>
                        <input type="url" name="link_ijazah" id="link_ijazah" class="form-control" placeholder="https://example.com/link-ijazah">
                        <div class="form-text">Harus berupa format URL valid.</div>
                    </div>

                    <!-- Link Sertifikasi -->
                    <div class="mb-3">
                        <label for="link_sertifikasi" class="form-label fw-semibold">Link Sertifikasi Kompetensi <span class="text-muted">(Optional)</span></label>
                        <input type="url" name="link_sertifikasi" id="link_sertifikasi" class="form-control" placeholder="https://example.com/link-sertifikasi">
                        <div class="form-text">Harus berupa format URL valid.</div>
                    </div>

                    <!-- Link Dokumen Lainnya -->
                    <div class="mb-3">
                        <label for="link_dokumen" class="form-label fw-semibold">Link Dokumen Pendukung Lainnya <span class="text-muted">(Optional)</span></label>
                        <input type="url" name="link_dokumen" id="link_dokumen" class="form-control" placeholder="https://example.com/link-dokumen-lain">
                        <div class="form-text">Harus berupa format URL valid.</div>
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
            var myModal = new bootstrap.Modal(document.getElementById('tambahPraktisiModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

<!-- Modal Upload Dokumen -->
<div class="modal fade" id="uploadDokumenModal" tabindex="-1" aria-labelledby="uploadDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="uploadDokumenForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="uploadDokumenModalLabel"><i class="bi bi-upload me-2"></i>Upload <span id="uploadLabel">Dokumen</span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Mengunggah dokumen untuk praktisi: <strong id="uploadNamaPraktisi"></strong></p>
                    
                    <input type="hidden" name="tipe_dokumen" id="uploadTipeDokumen">
                    
                    <div class="mb-3">
                        <label for="file_dokumen" class="form-label fw-semibold">Pilih File Dokumen <span class="text-danger">*</span></label>
                        <input type="file" name="file_dokumen" id="file_dokumen" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                        <div class="form-text text-muted">Format: PDF, Word, Gambar, ZIP (Maks. 5MB).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Mulai Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadButtons = document.querySelectorAll('.upload-btn');
        const uploadForm = document.getElementById('uploadDokumenForm');
        const uploadLabel = document.getElementById('uploadLabel');
        const uploadNamaPraktisi = document.getElementById('uploadNamaPraktisi');
        const uploadTipeDokumen = document.getElementById('uploadTipeDokumen');

        uploadButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const tipe = this.getAttribute('data-tipe');
                const label = this.getAttribute('data-label');

                uploadLabel.textContent = label;
                uploadNamaPraktisi.textContent = nama;
                uploadTipeDokumen.value = tipe;
                
                uploadForm.action = `{{ url('admin/praktisi') }}/${id}/upload-dokumen`;
            });
        });
    });
</script>
@endpush
@endsection
