@extends('layouts.app')

@section('title', 'Detail Kegiatan Tendik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail Kegiatan Tendik</h1>
    <div>
        <a href="{{ route('kegiatan-tendik.edit', $KegiatanTendik) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('kegiatan-tendik.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Kode Tendik</th>
                <td>{{ $KegiatanTendik->nip_nik }}</td>
            </tr>
            <tr>
                <th>Nama Tendik</th>
                <td>{{ $KegiatanTendik->nama_tendik }}</td>
            </tr>
            <tr>
                <th>Nama Kegiatan</th>
                <td>{{ $KegiatanTendik->nama_kegiatan }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $KegiatanTendik->tahun }}</td>
            </tr>
            <tr>
                <th>TA</th>
                <td>{{ $KegiatanTendik->ts->tahun_sekarang ?? '-' }}</td>
            </tr>
            <tr>
                <th>Penyelenggara</th>
                <td>{{ $KegiatanTendik->penyelenggara }}</td>
            </tr>
            <tr>
                <th>Jenis Kegiatan</th>
                <td>
                    <span class="badge bg-{{ $KegiatanTendik->jenis == 'Internal' ? 'primary' : 'success' }}">
                        {{ $KegiatanTendik->jenis }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Link Dokumen Bukti</th>
                <td>
                    @if($KegiatanTendik->link_dokumen)
                        <a href="{{ $KegiatanTendik->link_dokumen }}" target="_blank">{{ $KegiatanTendik->link_dokumen }}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $KegiatanTendik->created_at }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $KegiatanTendik->updated_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
