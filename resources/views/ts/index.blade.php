@extends('layouts.app')

@section('title', 'Data Akademik (Tahun Akademik)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Data Akademik (Tahun Akademik)</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahTSModal">Tambah TA</button>
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

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Tahun Akademik</th>
                <th>Semester</th>
                <th>Label TS</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ts as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tahun_sekarang }}</td>
                    <td>{{ $item->semester }}</td>
                    <td>
                        @if($item->label_ts)
                            <a href="{{ route('ts.edit', $item) }}" class="btn btn-sm btn-success">{{ $item->label_ts }}</a>
                        @else
                            <a href="{{ route('ts.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus"></i> Tambah TS</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ts.show', $item) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('ts.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('ts.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada data TA.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($errors->any())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('tambahTSModal'));
            myModal.show();
        });
    </script>
    @endpush
@endif

<!-- Modal Tambah TA -->
<div class="modal fade" id="tambahTSModal" tabindex="-1" aria-labelledby="tambahTSModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ts.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahTSModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Data Akademik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_tahun_sekarang" class="form-label">Tahun Akademik <span class="text-danger">*</span></label>
                        <input type="text" name="tahun_sekarang" id="modal_tahun_sekarang" class="form-control" required placeholder="Contoh: TA-0, TA-1, TA-2">
                    </div>
                    <div class="mb-3">
                        <label for="modal_semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="semester" id="modal_semester" class="form-select" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="Gasal">Gasal</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_label_ts" class="form-label">Label TS (Opsional)</label>
                        <input type="text" name="label_ts" id="modal_label_ts" class="form-control" placeholder="Contoh: TS, TS-1, TS-2">
                        <small class="text-muted">Isi jika Tahun Akademik ini ingin dijadikan referensi TS tertentu.</small>
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
