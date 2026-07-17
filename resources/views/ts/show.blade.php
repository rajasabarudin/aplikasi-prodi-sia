@extends('layouts.app')

@section('title', 'Detail TA')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Detail TA (Tahun Akademik)</h1>
    <div>
        <a href="{{ route('ts.edit', $ts) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('ts.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Tahun Akademik</th>
                <td>{{ $ts->tahun_sekarang }}</td>
            </tr>
            <tr>
                <th>Semester</th>
                <td>{{ $ts->semester }}</td>
            </tr>
            <tr>
                <th>Label TS</th>
                <td>
                    @if($ts->label_ts)
                        <span class="badge bg-success">{{ $ts->label_ts }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $ts->created_at }}</td>
            </tr>
            <tr>
                <th>Diperbarui</th>
                <td>{{ $ts->updated_at }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
