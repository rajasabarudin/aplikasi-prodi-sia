@extends('layouts.app')

@section('title', 'Data Alumni & Tracer Study')

@section('content')
<div class="row">
    <!-- Panel Statistik Tracer Study -->
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #0f172a !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Statistik Alumni</h5>
            </div>
            <div class="card-body">
                <!-- Total Alumni -->
                <div class="mb-3 text-center py-3" style="background: linear-gradient(135deg, #10b981, #059669) !important; border-radius: 12px;">
                    <span class="text-white-50 small d-block mb-1">Total Alumni Terdata</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $alumnis->count() }}</h2>
                </div>

                @php
                    $bekerja = $alumnis->filter(function($a) { return $a->tracerStudy && $a->tracerStudy->status_kerja === 'Bekerja'; })->count();
                    $wirausaha = $alumnis->filter(function($a) { return $a->tracerStudy && $a->tracerStudy->status_kerja === 'Wirausaha'; })->count();
                    $studi = $alumnis->filter(function($a) { return $a->tracerStudy && $a->tracerStudy->status_kerja === 'Melanjutkan Studi'; })->count();
                    $belum = $alumnis->filter(function($a) { return $a->tracerStudy && $a->tracerStudy->status_kerja === 'Belum Bekerja'; })->count();
                @endphp

                <ul class="list-group list-group-flush small mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Bekerja
                        <span class="badge bg-primary rounded-pill">{{ $bekerja }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Wirausaha
                        <span class="badge bg-success rounded-pill">{{ $wirausaha }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Melanjutkan Studi
                        <span class="badge bg-info rounded-pill">{{ $studi }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        Belum Bekerja
                        <span class="badge bg-danger rounded-pill">{{ $belum }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Panel Data Alumni -->
    <div class="col-lg-9 col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-mortarboard-fill text-primary me-2"></i>Daftar Alumni & Tracer Study</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAlumniModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Data
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th style="width: 25%">Identitas Alumni</th>
                                <th class="text-center" style="width: 15%">Tahun Lulus</th>
                                <th style="width: 25%">Status Tracer Study</th>
                                <th class="text-center" style="width: 15%">Waktu Tunggu</th>
                                <th class="text-center" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumnis as $index => $alumni)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong class="d-block text-primary">{{ $alumni->nama }}</strong>
                                    <span class="small text-muted">NIM: {{ $alumni->nim }}</span>
                                </td>
                                <td class="text-center">
                                    <strong class="text-dark">{{ $alumni->tahun_lulus }}</strong><br>
                                    <span class="badge bg-success mt-1">Studi: {{ (int)$alumni->tahun_lulus - (int)$alumni->tahun_masuk }} Tahun</span>
                                </td>
                                <td>
                                    @if($alumni->tracerStudy)
                                        @if($alumni->tracerStudy->status_kerja === 'Bekerja')
                                            <span class="badge bg-primary mb-1">Bekerja</span>
                                        @elseif($alumni->tracerStudy->status_kerja === 'Wirausaha')
                                            <span class="badge bg-success mb-1">Wirausaha</span>
                                        @elseif($alumni->tracerStudy->status_kerja === 'Melanjutkan Studi')
                                            <span class="badge bg-info mb-1">Studi Lanjut</span>
                                        @else
                                            <span class="badge bg-danger mb-1">Belum Bekerja</span>
                                        @endif
                                        <div class="small text-muted">{{ $alumni->tracerStudy->nama_perusahaan ?? '-' }}</div>
                                    @else
                                        <span class="badge bg-secondary">Belum Mengisi</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($alumni->tracerStudy && $alumni->tracerStudy->waktu_tunggu !== null)
                                        {{ $alumni->tracerStudy->waktu_tunggu }} Bulan
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAlumniModal{{ $alumni->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('tracer-study.destroy', $alumni->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alumni ini beserta rekam tracer study-nya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada data alumni dan tracer study.
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahAlumniModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('tracer-study.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Data Alumni & Tracer Study</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="row">
                        <!-- Data Diri Alumni -->
                        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Identitas Alumni</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control" required placeholder="NIM Mahasiswa">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap Lulusan">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tahun Masuk <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_masuk" class="form-control" min="2000" max="{{ date('Y') }}" required placeholder="Contoh: 2018">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tahun Lulus <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_lulus" class="form-control" min="2000" max="{{ date('Y')+1 }}" required placeholder="Contoh: 2022">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">IPK Akhir <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="ipk" class="form-control" min="0" max="4" required placeholder="Contoh: 3.75">
                        </div>

                        <!-- Data Publik / Kisah Sukses -->
                        <h6 class="fw-bold mt-3 mb-3 text-info border-bottom pb-2">Profil Publik (Opsional - Untuk Beranda)</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Foto Alumni (Max 2MB)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">URL Instagram</label>
                            <input type="url" name="instagram_url" class="form-control" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Testimoni / Kesan Pesan</label>
                            <textarea name="testimoni" class="form-control" rows="3" placeholder="Pesan inspiratif dari alumni untuk ditampilkan di beranda..."></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured_add" value="1">
                                <label class="form-check-label fw-semibold" for="is_featured_add">Tampilkan Profil ini di Beranda Utama (Alumni Inspiratif)</label>
                            </div>
                        </div>

                        <!-- Data Tracer Study -->
                        <h6 class="fw-bold mt-3 mb-3 text-success border-bottom pb-2">Kuesioner Tracer Study (Kriteria C9)</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Saat Ini <span class="text-danger">*</span></label>
                            <select name="status_kerja" class="form-select status-kerja-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Bekerja">Bekerja</option>
                                <option value="Wirausaha">Wirausaha</option>
                                <option value="Melanjutkan Studi">Melanjutkan Studi</option>
                                <option value="Belum Bekerja">Belum Bekerja / Mencari Kerja</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Waktu Tunggu Mendapatkan Pekerjaan (Bulan)</label>
                            <input type="number" name="waktu_tunggu" class="form-control" min="0" placeholder="Kosongkan jika belum/studi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kesesuaian Bidang Kerja</label>
                            <select name="kesesuaian_bidang" class="form-select">
                                <option value="">-- Pilih Tingkat Kesesuaian --</option>
                                <option value="Sangat Sesuai">Tinggi / Sangat Sesuai</option>
                                <option value="Sesuai">Sedang / Sesuai</option>
                                <option value="Kurang Sesuai">Rendah / Kurang Sesuai</option>
                                <option value="Tidak Sesuai">Tidak Sesuai</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tingkat Tempat Kerja</label>
                            <select name="tingkat_tempat_kerja" class="form-select">
                                <option value="">-- Pilih Skala Perusahaan --</option>
                                <option value="Lokal">Lokal / Wilayah</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Multinasional / Internasional</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Perusahaan / Institusi / Usaha</label>
                            <input type="text" name="nama_perusahaan" class="form-control" placeholder="Tempat Bekerja">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gaji / Pendapatan Pertama (Rp)</label>
                            <input type="number" name="pendapatan_pertama" class="form-control" placeholder="Contoh: 5000000">
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

@foreach($alumnis as $alumni)
<!-- Modal Edit Data -->
<div class="modal fade" id="editAlumniModal{{ $alumni->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg text-start">
        <div class="modal-content">
            <form action="{{ route('tracer-study.update', $alumni->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Data Alumni & Tracer Study</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Data Diri Alumni -->
                        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Identitas Alumni</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NIM (Tidak bisa diubah)</label>
                            <input type="text" class="form-control bg-light" value="{{ $alumni->nim }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ $alumni->nama }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tahun Masuk <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_masuk" class="form-control" value="{{ $alumni->tahun_masuk }}" min="2000" max="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Tahun Lulus <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_lulus" class="form-control" value="{{ $alumni->tahun_lulus }}" min="2000" max="{{ date('Y')+1 }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">IPK Akhir <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="ipk" class="form-control" value="{{ $alumni->ipk }}" min="0" max="4" required>
                        </div>

                        <!-- Data Publik / Kisah Sukses -->
                        <h6 class="fw-bold mt-3 mb-3 text-info border-bottom pb-2">Profil Publik (Opsional - Untuk Beranda)</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Foto Alumni (Max 2MB)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            @if($alumni->foto)
                                <div class="mt-2 small text-muted">
                                    <i class="bi bi-check-circle text-success"></i> Sudah ada foto. Unggah baru untuk mengganti.
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">URL Instagram</label>
                            <input type="url" name="instagram_url" class="form-control" value="{{ $alumni->instagram_url }}" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Testimoni / Kesan Pesan</label>
                            <textarea name="testimoni" class="form-control" rows="3" placeholder="Pesan inspiratif dari alumni untuk ditampilkan di beranda...">{{ $alumni->testimoni }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured_edit_{{ $alumni->id }}" value="1" {{ $alumni->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_featured_edit_{{ $alumni->id }}">Tampilkan Profil ini di Beranda Utama (Alumni Inspiratif)</label>
                            </div>
                        </div>

                        <!-- Data Tracer Study -->
                        <h6 class="fw-bold mt-3 mb-3 text-success border-bottom pb-2">Kuesioner Tracer Study (Kriteria C9)</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Saat Ini <span class="text-danger">*</span></label>
                            <select name="status_kerja" class="form-select status-kerja-select" required onchange="toggleTracerFields(this)">
                                <option value="">-- Pilih Status --</option>
                                <option value="Bekerja" {{ ($alumni->tracerStudy->status_kerja ?? '') == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                                <option value="Wirausaha" {{ ($alumni->tracerStudy->status_kerja ?? '') == 'Wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                                <option value="Melanjutkan Studi" {{ ($alumni->tracerStudy->status_kerja ?? '') == 'Melanjutkan Studi' ? 'selected' : '' }}>Melanjutkan Studi</option>
                                <option value="Belum Bekerja" {{ ($alumni->tracerStudy->status_kerja ?? '') == 'Belum Bekerja' ? 'selected' : '' }}>Belum Bekerja / Mencari Kerja</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Waktu Tunggu Mendapatkan Pekerjaan (Bulan)</label>
                            <input type="number" name="waktu_tunggu" class="form-control" value="{{ $alumni->tracerStudy->waktu_tunggu ?? '' }}" min="0" placeholder="Kosongkan jika belum/studi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kesesuaian Bidang Kerja</label>
                            <select name="kesesuaian_bidang" class="form-select">
                                <option value="">-- Pilih Tingkat Kesesuaian --</option>
                                <option value="Sangat Sesuai" {{ ($alumni->tracerStudy->kesesuaian_bidang ?? '') == 'Sangat Sesuai' ? 'selected' : '' }}>Tinggi / Sangat Sesuai</option>
                                <option value="Sesuai" {{ ($alumni->tracerStudy->kesesuaian_bidang ?? '') == 'Sesuai' ? 'selected' : '' }}>Sedang / Sesuai</option>
                                <option value="Kurang Sesuai" {{ ($alumni->tracerStudy->kesesuaian_bidang ?? '') == 'Kurang Sesuai' ? 'selected' : '' }}>Rendah / Kurang Sesuai</option>
                                <option value="Tidak Sesuai" {{ ($alumni->tracerStudy->kesesuaian_bidang ?? '') == 'Tidak Sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tingkat Tempat Kerja</label>
                            <select name="tingkat_tempat_kerja" class="form-select">
                                <option value="">-- Pilih Skala Perusahaan --</option>
                                <option value="Lokal" {{ ($alumni->tracerStudy->tingkat_tempat_kerja ?? '') == 'Lokal' ? 'selected' : '' }}>Lokal / Wilayah</option>
                                <option value="Nasional" {{ ($alumni->tracerStudy->tingkat_tempat_kerja ?? '') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ ($alumni->tracerStudy->tingkat_tempat_kerja ?? '') == 'Internasional' ? 'selected' : '' }}>Multinasional / Internasional</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Perusahaan / Institusi / Usaha</label>
                            <input type="text" name="nama_perusahaan" class="form-control" value="{{ $alumni->tracerStudy->nama_perusahaan ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gaji / Pendapatan Pertama (Rp)</label>
                            <input type="number" name="pendapatan_pertama" class="form-control" value="{{ $alumni->tracerStudy->pendapatan_pertama ?? '' }}" placeholder="Contoh: 5000000">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function toggleTracerFields(selectElement) {
        // Logic to hide/show fields based on status_kerja can go here if needed.
        // For now, all fields are shown but we can enhance it later.
    }
</script>
@endpush
@endsection
