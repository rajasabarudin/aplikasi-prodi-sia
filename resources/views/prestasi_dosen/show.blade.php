@extends('layouts.app')

@section('title', 'Detail Prestasi Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail Prestasi Dosen</h1>
    <div>
        <a href="{{ route('prestasi-dosen.edit', $prestasiDosen) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('prestasi-dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Kode Dosen</th>
                <td>{{ $prestasiDosen->kode_dosen }}</td>
            </tr>
            <tr>
                <th>Nama Dosen</th>
                <td>{{ $prestasiDosen->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Nama Prestasi</th>
                <td>{{ $prestasiDosen->nama_prestasi }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $prestasiDosen->tahun }}</td>
            </tr>
            <tr>
                <th>TA</th>
                <td>{{ $prestasiDosen->ts->tahun_sekarang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Penyelenggara</th>
                <td>{{ $prestasiDosen->penyelenggara }}</td>
            </tr>
            <tr>
                <th>Level Prestasi</th>
                <td>
                    <span class="badge bg-{{ $prestasiDosen->level_prestasi == 'internasional' ? 'primary' : ($prestasiDosen->level_prestasi == 'nasional' ? 'success' : 'secondary') }}">
                        {{ ucfirst($prestasiDosen->level_prestasi) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Prestasi yang Diraih</th>
                <td>{{ $prestasiDosen->prestasi_diraih ?? 'Finalis' }}</td>
            </tr>
            <tr>
                <th>Link Dokumen Bukti</th>
                <td>
                    @if($prestasiDosen->link_dokumen)
                        <a href="{{ $prestasiDosen->link_dokumen }}" target="_blank">{{ $prestasiDosen->link_dokumen }}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @if ($prestasiDosen->hibah_penelitian_id)
            <tr>
                <th>Sumber Sinkronisasi</th>
                <td>
                    <span class="text-warning fw-semibold"><i class="bi bi-cash-coin me-1"></i> Disinkronkan Otomatis dari Hibah Penelitian Eksternal</span>
                    <a href="{{ route('hibah-penelitian.show', $prestasiDosen->hibah_penelitian_id) }}" class="btn btn-sm btn-outline-warning ms-2">
                        <i class="bi bi-eye"></i> Lihat Hibah Asal
                    </a>
                </td>
            </tr>
            @endif
            <tr>
                <th>Dibuat</th>
                <td>{{ $prestasiDosen->created_at }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $prestasiDosen->updated_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
