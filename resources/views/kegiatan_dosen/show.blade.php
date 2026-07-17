@extends('layouts.app')

@section('title', 'Detail Kegiatan Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail Kegiatan Dosen</h1>
    <div>
        <a href="{{ route('kegiatan-dosen.edit', $kegiatanDosen) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('kegiatan-dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Kode Dosen</th>
                <td>{{ $kegiatanDosen->kode_dosen }}</td>
            </tr>
            <tr>
                <th>Nama Dosen</th>
                <td>{{ $kegiatanDosen->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Nama Kegiatan</th>
                <td>{{ $kegiatanDosen->nama_kegiatan }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $kegiatanDosen->tahun }}</td>
            </tr>
            <tr>
                <th>TA</th>
                <td>{{ $kegiatanDosen->ts->tahun_sekarang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Penyelenggara</th>
                <td>{{ $kegiatanDosen->penyelenggara }}</td>
            </tr>
            <tr>
                <th>Jenis Kegiatan</th>
                <td>
                    <span class="badge bg-{{ $kegiatanDosen->jenis == 'Internal' ? 'primary' : 'success' }}">
                        {{ $kegiatanDosen->jenis }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Link Dokumen Bukti</th>
                <td>
                    @if($kegiatanDosen->link_dokumen)
                        <a href="{{ $kegiatanDosen->link_dokumen }}" target="_blank">{{ $kegiatanDosen->link_dokumen }}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $kegiatanDosen->created_at }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $kegiatanDosen->updated_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
