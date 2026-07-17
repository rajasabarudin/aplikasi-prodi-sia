@extends('layouts.app')
@section('title', 'Berita & Pengumuman')
@section('content')
@if (session('success'))
    <div class="alert alert-success shadow-sm border-0 border-start border-success border-4 mb-4">
        <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
    </div>
@endif
<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-newspaper me-2"></i>Kelola Berita & Pengumuman</span>
        <a href="{{ route('berita.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Tambah Berita</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="40%">Judul Berita</th>
                        <th width="20%">Foto</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritas as $i => $berita)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($berita->tanggal)->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $berita->judul }}</td>
                        <td>
                            @if($berita->foto)
                                <img src="{{ asset('storage/' . $berita->foto) }}" class="img-thumbnail" style="height: 60px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">Tanpa Foto</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('berita.edit', $berita->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            <form action="{{ route('berita.destroy', $berita->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada berita.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
