@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Silabus: {{ $silabus->rps->matakuliah->nama_matakuliah ?? $silabus->rps->kode_matakuliah }}</h1>
        <a href="{{ route('penyusunan-silabus.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Data Silabus</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('penyusunan-silabus.update', $silabus->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label font-weight-bold">Kode Dokumen</label>
                    <div class="col-sm-9">
                        <input type="text" name="kode_dokumen" class="form-control" value="{{ $silabus->kode_dokumen }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label font-weight-bold">CPMK</label>
                    <div class="col-sm-9">
                        <textarea name="cpmk" class="form-control" rows="5">{{ $silabus->cpmk }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label font-weight-bold">Sub CPMK</label>
                    <div class="col-sm-9">
                        <textarea name="sub_cpmk" class="form-control" rows="5">{{ $silabus->sub_cpmk }}</textarea>
                    </div>
                </div>

                <hr>
                <h5 class="font-weight-bold mb-3">Materi Pembelajaran per Pertemuan</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th width="10%">Pertemuan</th>
                                <th>Materi Pembelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($silabus->materis as $m)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $m->pertemuan }}</td>
                                <td>
                                    <textarea name="materi[{{ $m->id }}]" class="form-control" rows="3">{{ $m->materi }}</textarea>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
