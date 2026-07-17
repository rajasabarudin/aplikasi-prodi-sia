@extends('layouts.app')

@block('title', 'Penyusunan Rencana Tugas Mahasiswa (RTM)')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4 fw-bold text-dark">Rencana Tugas Mahasiswa (RTM)</h3>
    <ol class="breadcrumb mb-4 small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Penyusunan RTM</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="m-0 fw-bold"><i class="bi bi-file-earmark-text-fill me-2"></i>Daftar Matakuliah & RTM</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width:5%">No</th>
                            <th>Matakuliah (RPS)</th>
                            <th>No. Dokumen RTM</th>
                            <th>Dosen Pengampu</th>
                            <th class="text-center">Status RTM</th>
                            <th class="text-center">Dokumen Terkait</th>
                            <th class="text-center" style="width:25%">Aksi RTM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rpsList as $r)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $r->matakuliah?->nama_matakuliah }}</span>
                                <small class="text-muted d-block">{{ $r->kode_matakuliah }} | SKS: {{ $r->matakuliah?->sks_t + $r->matakuliah?->sks_pa + $r->matakuliah?->sks_pu }}</small>
                            </td>
                            <td>{{ $r->rtm?->nomor_dokumen ?? '-' }}</td>
                            <td>{{ $r->rtm?->dosen_pengampu ?? '-' }}</td>
                            <td class="text-center">
                                @if($r->rtm)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Sudah Digenerate</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Belum Digenerate</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('penyusunan-rps.cetak', $r->id) }}" target="_blank" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.75rem;" title="Lihat RPS"><i class="bi bi-file-earmark-text me-1"></i>RPS</a>
                                    @php
                                        $silabusSystem = \App\Models\Silabus::where('rps_id', $r->id)->first();
                                    @endphp
                                    @if($silabusSystem)
                                        <a href="{{ route('penyusunan-silabus.cetak', $silabusSystem->id) }}" target="_blank" class="btn btn-xs btn-outline-success py-1 px-2 text-dark" style="font-size: 0.75rem;" title="Lihat Silabus"><i class="bi bi-file-earmark-medical me-1"></i>Silabus</a>
                                    @else
                                        <span class="btn btn-xs btn-outline-secondary py-1 px-2 disabled" style="font-size: 0.75rem;" title="Silabus belum digenerate"><i class="bi bi-file-earmark-medical me-1"></i>Silabus</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($r->rtm)
                                    <div class="btn-group">
                                        <a href="{{ route('penyusunan-rtm.cetak', $r->rtm->id) }}" target="_blank" class="btn btn-sm btn-info text-white" title="Cetak RTM (PDF)"><i class="bi bi-printer me-1"></i>Cetak</a>
                                        <a href="{{ route('penyusunan-rtm.edit', $r->rtm->id) }}" class="btn btn-sm btn-warning" title="Edit RTM"><i class="bi bi-pencil me-1"></i>Edit</a>
                                        <form action="{{ route('penyusunan-rtm.destroy', $r->rtm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen RTM ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route('penyusunan-rtm.generate', $r->id) }}" class="btn btn-sm btn-primary w-100 fw-bold"><i class="bi bi-cpu me-1"></i>Generate RTM</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data Penyusunan RPS. RTM hanya bisa digenerate dari matakuliah yang memiliki dokumen RPS.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
