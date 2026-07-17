@extends('layouts.app')

@section('title', 'Detail Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Detail Akun</h1>
                <p class="text-muted mb-0">Informasi lengkap mengenai akun terdaftar.</p>
            </div>
            <a href="{{ route('akun.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-bounding-box me-2"></i>Informasi Akun</h5>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <div class="bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border: 2px solid #e2e8f0;">
                            <i class="bi bi-person-fill text-secondary display-4"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="fw-bold mb-1 text-dark">{{ $akun->username }}</h4>
                        <p class="text-muted mb-0"><i class="bi bi-envelope me-1"></i>{{ $akun->email }}</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="bg-light fw-bold text-dark">ID Akun</th>
                                <td class="text-dark">#{{ $akun->id }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-bold text-dark">Username</th>
                                <td class="text-dark fw-semibold">{{ $akun->username }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-bold text-dark">Email</th>
                                <td class="text-dark">{{ $akun->email }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-bold text-dark">Level / Hak Akses</th>
                                <td>
                                    @if ($akun->level === 'king')
                                        <span class="badge bg-danger px-3 py-2 text-uppercase shadow-sm" style="font-size: 11px; font-weight: 600; border-radius: 50px;">
                                            <i class="bi bi-crown-fill me-1"></i> King
                                        </span>
                                    @elseif ($akun->level === 'jendral')
                                        <span class="badge bg-warning text-dark px-3 py-2 text-uppercase shadow-sm" style="font-size: 11px; font-weight: 600; border-radius: 50px;">
                                            <i class="bi bi-shield-fill me-1"></i> Jendral
                                        </span>
                                    @else
                                        <span class="badge bg-primary px-3 py-2 text-uppercase shadow-sm" style="font-size: 11px; font-weight: 600; border-radius: 50px;">
                                            <i class="bi bi-journal-bookmark-fill me-1"></i> Lecture
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-bold text-dark">Tanggal Dibuat</th>
                                <td class="text-muted">{{ $akun->created_at->format('d F Y, H:i') }} ({{ $akun->created_at->diffForHumans() }})</td>
                            </tr>
                            <tr>
                                <th class="bg-light fw-bold text-dark">Terakhir Diperbarui</th>
                                <td class="text-muted">{{ $akun->updated_at->format('d F Y, H:i') }} ({{ $akun->updated_at->diffForHumans() }})</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('akun.edit', $akun->id) }}" class="btn btn-warning px-4">
                        <i class="bi bi-pencil-square me-1"></i> Edit Akun
                    </a>
                    <form action="{{ route('akun.destroy', $akun->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash me-1"></i> Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
