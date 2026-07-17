@extends('layouts.app')

@section('title', 'Detail Data Kerjasama')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Detail Data Kerjasama</h1>
                <p class="text-muted mb-0">Informasi rinci mengenai MoU kerjasama dengan mitra.</p>
            </div>
            <div>
                <a href="{{ route('kerjasama.edit', $kerjasama->id) }}" class="btn btn-warning shadow-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
                <a href="{{ route('kerjasama.index') }}" class="btn btn-secondary shadow-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i>Rincian MoU Kerjasama</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0 align-middle">
                    <tr>
                        <th style="width: 35%;" class="ps-4 py-3">Nama Mitra</th>
                        <td class="fw-bold text-dark py-3">{{ $kerjasama->nama_mitra }}</td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Tahun MoU</th>
                        <td class="py-3">
                            <span class="badge bg-primary px-3 py-2" style="font-size: 11px;">Mulai: {{ $kerjasama->tahun_mou }}</span>
                            @if ($kerjasama->tahun_berakhir)
                                <span class="badge bg-danger px-3 py-2" style="font-size: 11px;">Berakhir: {{ $kerjasama->tahun_berakhir }}</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2" style="font-size: 11px;">Berakhir: Seterusnya</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Nomor MoU UBSI</th>
                        <td class="py-3"><code class="text-dark fs-6">{{ $kerjasama->nomor_mou_ubsi }}</code></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Nomor MoU Mitra</th>
                        <td class="py-3">
                            @if ($kerjasama->nomor_mou_mitra)
                                <code class="text-secondary fs-6">{{ $kerjasama->nomor_mou_mitra }}</code>
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Ketua / Yang Mewakili</th>
                        <td class="py-3">{{ $kerjasama->ketua_mewakili }}</td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">No. WhatsApp Mitra</th>
                        <td class="py-3">
                            @if ($kerjasama->no_wa_mitra)
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $kerjasama->no_wa_mitra);
                                    if (strpos($cleanPhone, '0') === 0) {
                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                    }
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold">{{ $kerjasama->no_wa_mitra }}</span>
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                        <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
                                    </a>
                                </div>
                            @else
                                <span class="text-muted small italic">Tidak ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Dokumen MoU</th>
                        <td class="py-3">
                            @if ($kerjasama->file_mou)
                                <a href="{{ asset('storage/' . $kerjasama->file_mou) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Buka / Unduh Dokumen
                                </a>
                            @else
                                <span class="text-muted small italic">Dokumen belum diunggah</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Dibuat Pada</th>
                        <td class="text-muted small py-3">{{ $kerjasama->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3">Diperbarui Pada</th>
                        <td class="text-muted small py-3">{{ $kerjasama->updated_at->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
