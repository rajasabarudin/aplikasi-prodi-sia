@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail Kelas</h1>
    <div>
        <a href="{{ route('kelas.edit', $kela) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Nama Kelas</th>
                <td>{{ $kela->nama_kelas }}</td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $kela->created_at }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $kela->updated_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
