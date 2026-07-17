@extends('layouts.app')

@section('title', 'Detail Rekognisi Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail Rekognisi Dosen</h1>
    <div>
        <a href="{{ route('rekognisi-dosen.edit', $rekognisiDosen) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('rekognisi-dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Kode Dosen</th>
                <td>{{ $rekognisiDosen->kode_dosen }}</td>
            </tr>
            <tr>
                <th>Nama Dosen</th>
                <td>{{ $rekognisiDosen->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Nama Rekognisi</th>
                <td>{{ $rekognisiDosen->nama_rekognisi }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $rekognisiDosen->tahun }}</td>
            </tr>
            <tr>
                <th>TA</th>
                <td>{{ $rekognisiDosen->ts->tahun_sekarang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Level</th>
                <td>
                    <span class="badge bg-{{ $rekognisiDosen->level == 'internasional' ? 'primary' : ($rekognisiDosen->level == 'nasional' ? 'success' : 'secondary') }}">
                        {{ ucfirst($rekognisiDosen->level) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Link Dokumen</th>
                <td>
                    @if ($rekognisiDosen->link_dokumen)
                        <a href="{{ $rekognisiDosen->link_dokumen }}" target="_blank">{{ $rekognisiDosen->link_dokumen }}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @if ($rekognisiDosen->penelitian_dosen_id)
            <tr>
                <th>Sumber Sinkronisasi</th>
                <td>
                    <span class="text-info fw-semibold"><i class="bi bi-journal-text me-1"></i> Disinkronkan Otomatis dari Penelitian Dosen</span>
                    <a href="{{ route('penelitian-dosen.show', $rekognisiDosen->penelitian_dosen_id) }}" class="btn btn-sm btn-outline-info ms-2">
                        <i class="bi bi-eye"></i> Lihat Penelitian Asal
                    </a>
                </td>
            </tr>
            @endif
            @if ($rekognisiDosen->hibah_penelitian_id)
            <tr>
                <th>Sumber Sinkronisasi</th>
                <td>
                    <span class="text-warning fw-semibold"><i class="bi bi-cash-coin me-1"></i> Disinkronkan Otomatis dari Hibah Penelitian</span>
                    <a href="{{ route('hibah-penelitian.show', $rekognisiDosen->hibah_penelitian_id) }}" class="btn btn-sm btn-outline-warning ms-2">
                        <i class="bi bi-eye"></i> Lihat Hibah Asal
                    </a>
                </td>
            </tr>
            @endif
            @if ($rekognisiDosen->hki_id)
            <tr>
                <th>Sumber Sinkronisasi</th>
                <td>
                    <span class="text-success fw-semibold"><i class="bi bi-shield-check me-1"></i> Disinkronkan Otomatis dari HKI</span>
                </td>
            </tr>
            @endif
            @if ($rekognisiDosen->prestasi_dosen_id)
            <tr>
                <th>Sumber Sinkronisasi</th>
                <td>
                    <span class="text-danger fw-semibold"><i class="bi bi-trophy me-1"></i> Disinkronkan Otomatis dari Prestasi Dosen</span>
                    <a href="{{ route('prestasi-dosen.show', $rekognisiDosen->prestasi_dosen_id) }}" class="btn btn-sm btn-outline-danger ms-2">
                        <i class="bi bi-eye"></i> Lihat Prestasi Asal
                    </a>
                </td>
            </tr>
            @endif
            <tr>
                <th>Dibuat</th>
                <td>{{ $rekognisiDosen->created_at }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $rekognisiDosen->updated_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
