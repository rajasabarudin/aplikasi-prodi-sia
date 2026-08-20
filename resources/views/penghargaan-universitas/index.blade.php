@extends('layouts.app')
@section('title', 'Penghargaan Universitas')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data Penghargaan Universitas</h1>
                <p class="text-muted mb-0">Kelola daftar penghargaan yang diraih oleh Universitas</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Penghargaan
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th style="width: 10%">Tahun</th>
                                <th>Nama Mitra</th>
                                <th>Nomor Penghargaan</th>
                                <th class="text-center">Link Dokumen</th>
                                <th class="text-center">Link Berita</th>
                                <th class="text-center" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penghargaan as $item)
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $item->tahun }}</td>
                                <td class="fw-semibold text-dark">{{ $item->nama_mitra }}</td>
                                <td>{{ $item->nomor_penghargaan ?? '-' }}</td>
                                <td class="text-center">
                                    @if($item->link_dokumen_penghargaan)
                                        <a href="{{ str_starts_with($item->link_dokumen_penghargaan, 'http') ? $item->link_dokumen_penghargaan : asset($item->link_dokumen_penghargaan) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Dokumen"><i class="bi bi-file-earmark-text"></i> Dokumen</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->link_berita)
                                        <a href="{{ $item->link_berita }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Berita"><i class="bi bi-newspaper"></i> Berita</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <form action="{{ route('penghargaan-universitas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data penghargaan universitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@foreach($penghargaanUniversitas as $item)

                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Penghargaan Universitas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('penghargaan-universitas.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Tahun</label>
                                                    <input type="number" name="tahun" class="form-control" value="{{ $item->tahun }}" required min="1900" max="2100">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Mitra</label>
                                                    <input type="text" name="nama_mitra" class="form-control" value="{{ $item->nama_mitra }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nomor Penghargaan</label>
                                                    <input type="text" name="nomor_penghargaan" class="form-control" value="{{ $item->nomor_penghargaan }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Upload Dokumen Penghargaan (PDF, JPG, PNG)</label>
                                                    @if($item->link_dokumen_penghargaan)
                                                        <div class="mb-2">
                                                            <a href="{{ str_starts_with($item->link_dokumen_penghargaan, 'http') ? $item->link_dokumen_penghargaan : asset($item->link_dokumen_penghargaan) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-text"></i> Lihat Dokumen Saat Ini</a>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="link_dokumen_penghargaan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah dokumen.</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Link Berita</label>
                                                    <input type="url" name="link_berita" class="form-control" value="{{ $item->link_berita }}">
                                                    <small class="text-muted">Misal: https://berita.com/...</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
@endforeach

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Penghargaan Universitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penghargaan-universitas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" class="form-control" required min="1900" max="2100" value="{{ date('Y') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mitra</label>
                        <input type="text" name="nama_mitra" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Penghargaan</label>
                        <input type="text" name="nomor_penghargaan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Dokumen Penghargaan (PDF, JPG, PNG)</label>
                        <input type="file" name="link_dokumen_penghargaan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Berita</label>
                        <input type="url" name="link_berita" class="form-control">
                        <small class="text-muted">Misal: https://berita.com/...</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
