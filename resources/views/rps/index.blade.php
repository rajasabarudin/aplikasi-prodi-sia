@extends('layouts.app')
@section('title', 'Data Penyusunan RPS')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data Penyusunan RPS</h1>
                <p class="text-muted mb-0">Daftar Rencana Pembelajaran Semester (RPS) yang telah disusun</p>
            </div>
            <a href="{{ route('penyusunan-rps.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Buat RPS Baru
            </a>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width:5%">No</th>
                            <th>Matakuliah</th>
                            <th>Dosen Pengembang</th>
                            <th>Nomor Dokumen</th>
                            <th class="text-center" style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rps as $r)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $r->matakuliah?->nama_matakuliah }}</span>
                                <small class="text-muted d-block">{{ $r->kode_matakuliah }}</small>
                            </td>
                            <td>
                                @if($r->dosen_pengembang)
                                    @php
                                        $listDosen = array_map('trim', explode(',', $r->dosen_pengembang));
                                    @endphp
                                    @foreach($listDosen as $d)
                                        <span class="badge bg-primary mb-1 fw-normal" style="font-size: 0.8rem;"><i class="bi bi-person me-1"></i>{{ $d }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $r->nomor_dokumen ?? '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('penyusunan-rps.cetak', $r->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Cetak/Preview PDF"><i class="bi bi-printer"></i></a>
                                    <a href="{{ route('penyusunan-rps.edit', $r->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('penyusunan-rps.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen RPS ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada dokumen RPS yang dibuat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
