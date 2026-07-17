@extends('layouts.app')

@section('title', 'Kartu Dosen - ' . $dosen->nama_dosen)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
    <h4><i class="bi bi-credit-card-2-front me-2"></i>Kartu Identitas Dosen</h4>
    <div>
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak</button>
        <a href="{{ route('dosen.show', $dosen) }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" id="dosen-card" style="width: 600px; border-radius: 20px; overflow: hidden; position: relative; background: #0f172a; color: #fff;">
        <!-- Abstract Background Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 250px; height: 250px; background: #3b82f6; border-radius: 50%; filter: blur(70px); opacity: 0.4;"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 300px; height: 300px; background: #8b5cf6; border-radius: 50%; filter: blur(90px); opacity: 0.3;"></div>
        
        <!-- Top Header -->
        <div style="position: relative; z-index: 10; padding: 25px 30px 15px 30px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; gap: 15px; align-items: center;">
                <div style="background: #fff; padding: 8px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="width: 45px; height: 45px; object-fit: contain;">
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 900; font-size: 22px; letter-spacing: 1px; color: #f8fafc; text-transform: uppercase; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">Universitas BSI</h4>
                    <div style="font-size: 11px; color: #cbd5e1; font-weight: 600; letter-spacing: 0.5px;">FAKULTAS TEKNIK DAN INFORMATIKA</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; font-weight: 800; color: #fbbf24; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2px;">KARTU DOSEN</div>
                <div style="font-size: 16px; font-weight: 800; color: #fff; font-family: 'Courier New', Courier, monospace; letter-spacing: 1px; background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 6px;">{{ $dosen->kode_dosen ?? '-' }}</div>
            </div>
        </div>

        <!-- Main Content -->
        <div style="position: relative; z-index: 10; padding: 15px 30px 30px 30px; display: flex; gap: 25px;">
            <!-- Photo -->
            <div style="position: relative; flex-shrink: 0;">
                <div style="width: 130px; height: 150px; border-radius: 16px; padding: 3px; background: linear-gradient(135deg, #fbbf24, #f59e0b); box-shadow: 0 8px 20px rgba(0,0,0,0.4);">
                    @if ($dosen->foto)
                        <img src="{{ asset('storage/' . $dosen->foto) }}" style="width: 100%; height: 100%; border-radius: 13px; object-fit: cover; background: #fff;">
                    @else
                        <div style="width: 100%; height: 100%; border-radius: 13px; background: #1e293b; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-fill" style="font-size: 60px; color: #64748b;"></i>
                        </div>
                    @endif
                </div>
                <!-- Decorative badge -->
                <div style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); background: #fbbf24; color: #78350f; font-size: 10px; font-weight: 900; padding: 4px 16px; border-radius: 20px; letter-spacing: 1px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); white-space: nowrap;">
                    DOSEN
                </div>
            </div>

            <!-- Details -->
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; padding-right: 15px;">
                <h2 style="margin: 0 0 4px 0; font-weight: 800; font-size: 22px; color: #fff; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">{{ strtoupper($dosen->nama_dosen) }}</h2>
                <div style="font-size: 12px; color: #93c5fd; font-weight: 600; margin-bottom: 15px; letter-spacing: 0.5px;">Program Studi {{ $dosen->homebase_dosen ?? '-' }}</div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px 15px; font-size: 12px;">
                    <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="color: #94a3b8; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">NIDN / NIP</div>
                        <div style="font-weight: 700; color: #f8fafc; font-size: 13px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $dosen->nip ?? '-' }}">{{ $dosen->nip ?? '-' }}</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="color: #94a3b8; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">Pendidikan</div>
                        <div style="font-weight: 700; color: #f8fafc; font-size: 13px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $dosen->pendidikan ?? '-' }}">{{ $dosen->pendidikan ?? '-' }}</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="color: #94a3b8; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">JFA</div>
                        <div style="font-weight: 700; color: #f8fafc; font-size: 13px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $dosen->jfa ?? '-' }}">{{ $dosen->jfa ?? '-' }}</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.05); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="color: #94a3b8; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">Kepangkatan</div>
                        <div style="font-weight: 700; color: #f8fafc; font-size: 13px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $dosen->kepangkatan ?? '-' }}">{{ $dosen->kepangkatan ?? '-' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- QR Code Right -->
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-left: 20px; border-left: 1px dashed rgba(255,255,255,0.2);">
                <div style="background: #fff; padding: 6px; border-radius: 12px; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&ecc=H&data={{ urlencode($qrUrl) }}" style="width: 80px; height: 80px; display: block; border-radius: 6px;">
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 24px; height: 24px; background: white; padding: 2px; border-radius: 6px;">
                </div>
                <div style="font-size: 9px; color: #cbd5e1; margin-top: 10px; font-weight: 700; letter-spacing: 1px; text-align: center;">SCAN ABSEN</div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body {
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #dosen-card {
        box-shadow: none !important;
        border: 2px solid #1e293b !important;
        page-break-inside: avoid;
        break-inside: avoid;
        margin: 0 auto;
    }
    .d-print-none {
        display: none !important;
    }
    .navbar, .bg-dark {
        display: none !important;
    }
    main {
        padding: 0 !important;
    }
    .d-flex > .bg-dark {
        display: none !important;
    }
}
</style>
@endpush
@endsection
