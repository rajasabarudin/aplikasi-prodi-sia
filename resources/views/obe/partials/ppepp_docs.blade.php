@php
    $cellKey = $kCode . '_' . $pCode;
    $docsInCell = isset($ppeppDocs) ? ($ppeppDocs->get($cellKey) ?? collect()) : collect();
@endphp

@foreach($docsInCell as $d)
<div class="bg-light border rounded p-1 mb-2 small d-flex justify-content-between align-items-center" style="border-radius: 6px;">
    <span class="text-truncate me-1 text-dark" style="max-width: 100px;" title="{{ $d->nama_dokumen }}">
        <i class="bi bi-file-earmark-pdf text-danger"></i> {{ $d->nama_dokumen }}
    </span>
    <div class="btn-group btn-group-sm">
        <a href="{{ route('obe.view-ppepp', $d->id) }}" target="_blank" class="btn btn-link p-0 text-info me-1" title="Lihat"><i class="bi bi-eye" style="font-size: 0.8rem;"></i></a>
        <a href="{{ route('obe.download-ppepp', $d->id) }}" class="btn btn-link p-0 text-success me-1" title="Download"><i class="bi bi-download" style="font-size: 0.8rem;"></i></a>
        <form action="{{ route('obe.delete-ppepp', $d->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link p-0 text-danger" onclick="return confirm('Hapus dokumen ini?')" title="Hapus"><i class="bi bi-trash" style="font-size: 0.8rem;"></i></button>
        </form>
    </div>
</div>
@endforeach

<!-- QUICK UPLOAD TRIGGER -->
<button type="button" class="btn btn-xs btn-outline-secondary w-100 py-0 border-dashed" style="border-style: dashed; font-size: 0.75rem;" onclick="openQuickUpload('{{ $kCode }}', '{{ $pCode }}', '{{ $kName }}', '{{ $pName }}')">
    <i class="bi bi-plus"></i> Tambah Dokumen
</button>
