@extends('layouts.app')
@section('title', 'Edit Berita')
@section('content')
<div class="card shadow-sm border-0">
    @if ($errors->any())
        <div class="alert alert-danger m-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card-header bg-dark text-white fw-bold">
        Edit Berita & Pengumuman
    </div>
    <div class="card-body">
        <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-bold">Judul Berita <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $berita->judul) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $berita->tanggal ? \Carbon\Carbon::parse($berita->tanggal)->format('Y-m-d') : '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Foto <small class="text-muted">(Opsional, max 2MB. Kosongkan jika tidak ingin mengubah foto.)</small></label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                @if($berita->foto)
                    <div class="mt-2">
                        <small class="d-block text-muted mb-1">Foto Saat Ini:</small>
                        <img src="{{ asset('storage/' . $berita->foto) }}" class="img-thumbnail" style="height: 100px;">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Isi Berita <span class="text-danger">*</span></label>
                <textarea name="isi" id="isi" class="form-control" rows="8">{{ old('isi', $berita->isi) }}</textarea>
            </div>
            
            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('berita.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Perbarui Berita</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: 'textarea#isi',
            plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons template',
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | fullscreen',
            menubar: 'file edit view insert format tools table help',
            height: 450,
            promotion: false,
            branding: false
        });
    });
</script>
@endpush
