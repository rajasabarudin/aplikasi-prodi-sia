@extends('layouts.app')

@section('title', 'Detail Data PKS & IA')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Detail Data PKS & IA</h1>
                <p class="text-muted mb-0">Informasi rinci mengenai dokumen Perjanjian Kerja Sama (PKS) dan Implementation Agreement (IA).</p>
            </div>
            <div>
                <a href="{{ route('pks-ia.edit', $pksIa->id) }}" class="btn btn-warning shadow-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
                <a href="{{ route('pks-ia.index') }}" class="btn btn-secondary shadow-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Rincian Dokumen PKS & IA</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0 align-middle">
                    <tr>
                        <th style="width: 35%;" class="ps-4 py-3">Nama Mitra</th>
                        <td class="fw-bold text-dark py-3">{{ $pksIa->nama_mitra }}</td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Tanggal PKS</th>
                        <td class="py-3"><span class="badge bg-primary px-3 py-2" style="font-size: 11px;">{{ $pksIa->tgl_pks->format('d-m-Y') }}</span></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Nomor PKS UBSI</th>
                        <td class="py-3"><code class="text-dark fs-6">{{ $pksIa->no_pks_ubsi }}</code></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Nomor PKS Mitra</th>
                        <td class="py-3">
                            @if ($pksIa->no_pks_mitra)
                                <code class="text-secondary fs-6">{{ $pksIa->no_pks_mitra }}</code>
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Tema PKS</th>
                        <td class="py-3">{{ $pksIa->tema_pks }}</td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 bg-light text-primary fw-bold" colspan="2">
                            <i class="bi bi-file-earmark-check-fill me-1"></i> Detail IA (Implementation Agreement)
                        </th>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Tanggal IA</th>
                        <td class="py-3">
                            @if ($pksIa->tgl_ia)
                                <span class="badge bg-success px-3 py-2" style="font-size: 11px;">{{ $pksIa->tgl_ia->format('d-m-Y') }}</span>
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Nomor IA UBSI</th>
                        <td class="py-3">
                            @if ($pksIa->no_ia_ubsi)
                                <code class="text-dark fs-6">{{ $pksIa->no_ia_ubsi }}</code>
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Nomor IA Mitra</th>
                        <td class="py-3">
                            @if ($pksIa->no_ia_mitra)
                                <code class="text-secondary fs-6">{{ $pksIa->no_ia_mitra }}</code>
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Judul/Tema Kegiatan IA</th>
                        <td class="py-3">
                            @if ($pksIa->judul_ia)
                                {{ $pksIa->judul_ia }}
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Kategori</th>
                        <td class="py-3">
                            @if ($pksIa->kategori === 'Pendidikan')
                                <span class="badge bg-primary px-3 py-2">Pendidikan</span>
                            @elseif ($pksIa->kategori === 'Penelitian')
                                <span class="badge bg-info text-dark px-3 py-2">Penelitian</span>
                            @else
                                <span class="badge bg-success px-3 py-2">PKM</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Level PKS</th>
                        <td class="py-3">
                            @if ($pksIa->level_pks === 'Internasional')
                                <span class="badge bg-danger px-3 py-2">Internasional</span>
                            @elseif ($pksIa->level_pks === 'Nasional')
                                <span class="badge bg-warning text-dark px-3 py-2">Nasional</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">Lokal/Wilayah</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Berkas PKS</th>
                        <td class="py-3">
                            @if ($pksIa->file_pks)
                                <a href="{{ asset('storage/' . $pksIa->file_pks) }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Buka / Unduh Berkas PKS
                                </a>
                            @else
                                <span class="text-muted small italic">Belum diunggah</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Berkas IA</th>
                        <td class="py-3">
                            @if ($pksIa->file_ia)
                                <a href="{{ asset('storage/' . $pksIa->file_ia) }}" target="_blank" class="btn btn-sm btn-info text-dark rounded-pill px-3">
                                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Buka / Unduh Berkas IA
                                </a>
                            @else
                                <span class="text-muted small italic">Belum diunggah</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Berkas Tambahan</th>
                        <td class="py-3">
                            @if ($pksIa->file_tambahan)
                                <a href="{{ asset('storage/' . $pksIa->file_tambahan) }}" target="_blank" class="btn btn-sm btn-secondary rounded-pill px-3">
                                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Buka / Unduh Berkas Tambahan
                                </a>
                            @else
                                <span class="text-muted small italic">Belum diunggah</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Dibuat Pada</th>
                        <td class="text-muted small py-3">{{ $pksIa->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Diperbarui Pada</th>
                        <td class="text-muted small py-3">{{ $pksIa->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
