@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Data Tenaga Kependidikan</h1>
        <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahTendikModal">
            <i class="bi bi-plus-circle fa-sm text-white-50"></i> Tambah Tendik
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Tenaga Kependidikan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>NIP/NIK</th>
                            <th>Nama Lengkap</th>
                            <th>L/P</th>
                            <th>Pendidikan</th>
                            <th>Jabatan/Tugas</th>
                            <th>Status Pegawai</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tendiks as $index => $tendik)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tendik->nip_nik ?? '-' }}</td>
                            <td>{{ $tendik->nama_lengkap }}</td>
                            <td>{{ $tendik->jenis_kelamin }}</td>
                            <td>{{ $tendik->pendidikan_terakhir ?? '-' }}</td>
                            <td>{{ $tendik->jabatan_tugas ?? '-' }}</td>
                            <td>
                                @if($tendik->status_pegawai == 'Tetap')
                                    <span class="badge bg-success">Tetap</span>
                                @elseif($tendik->status_pegawai == 'Tidak Tetap')
                                    <span class="badge bg-warning text-dark">Tidak Tetap</span>
                                @else
                                    <span class="badge bg-secondary">{{ $tendik->status_pegawai ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#editTendikModal{{ $tendik->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('tendik.destroy', $tendik->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editTendikModal{{ $tendik->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Data Tenaga Kependidikan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('tendik.update', $tendik->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">NIP / NIK</label>
                                                    <input type="text" name="nip_nik" class="form-control" value="{{ $tendik->nip_nik }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $tendik->nama_lengkap }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                                    <select name="jenis_kelamin" class="form-select" required>
                                                        <option value="L" {{ $tendik->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                                                        <option value="P" {{ $tendik->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Pendidikan Terakhir</label>
                                                    <select name="pendidikan_terakhir" class="form-select">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach(['SMA/SMK/Sederajat', 'D1/D2/D3', 'D4/S1', 'S2', 'S3'] as $pendidikan)
                                                            <option value="{{ $pendidikan }}" {{ $tendik->pendidikan_terakhir == $pendidikan ? 'selected' : '' }}>{{ $pendidikan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Jabatan / TugasPokok</label>
                                                    <input type="text" name="jabatan_tugas" class="form-control" value="{{ $tendik->jabatan_tugas }}" placeholder="Contoh: Pustakawan, Teknisi, Tata Usaha">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Status Pegawai</label>
                                                    <select name="status_pegawai" class="form-select">
                                                        <option value="">-- Pilih --</option>
                                                        <option value="Tetap" {{ $tendik->status_pegawai == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                                        <option value="Tidak Tetap" {{ $tendik->status_pegawai == 'Tidak Tetap' ? 'selected' : '' }}>Tidak Tetap</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">Unit Kerja</label>
                                                    <input type="text" name="unit_kerja" class="form-control" value="{{ $tendik->unit_kerja }}" placeholder="Contoh: Fakultas Ilmu Komputer">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Update Data</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data tenaga kependidikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tambah Modal -->
<div class="modal fade" id="tambahTendikModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tenaga Kependidikan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tendik.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIP / NIK</label>
                            <input type="text" name="nip_nik" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="L">Laki-Laki (L)</option>
                                <option value="P">Perempuan (P)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach(['SMA/SMK/Sederajat', 'D1/D2/D3', 'D4/S1', 'S2', 'S3'] as $pendidikan)
                                    <option value="{{ $pendidikan }}">{{ $pendidikan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jabatan / Tugas Pokok</label>
                            <input type="text" name="jabatan_tugas" class="form-control" placeholder="Contoh: Pustakawan, Teknisi, Tata Usaha">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status Pegawai</label>
                            <select name="status_pegawai" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Tidak Tetap">Tidak Tetap</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Unit Kerja</label>
                            <input type="text" name="unit_kerja" class="form-control" placeholder="Contoh: Fakultas Ilmu Komputer">
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
