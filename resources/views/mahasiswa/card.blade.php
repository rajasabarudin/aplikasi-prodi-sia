@extends('layouts.app')

@section('title', 'KTM Mahasiswa - ' . $mahasiswa->nama)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
    <h4><i class="bi bi-credit-card-2-front me-2"></i>Kartu Tanda Mahasiswa (KTM)</h4>
    <div>
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Cetak</button>
        <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0" id="mahasiswa-card" style="width: 600px; border-radius: 20px; overflow: hidden; position: relative; background: #0369a1; color: #fff;">
        <!-- Abstract Background Elements -->
        <div style="position: absolute; top: -50px; right: -50px; width: 250px; height: 250px; background: #38bdf8; border-radius: 50%; filter: blur(70px); opacity: 0.5;"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 300px; height: 300px; background: #0ea5e9; border-radius: 50%; filter: blur(90px); opacity: 0.4;"></div>
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.05;"></div>
        
        <!-- Top Header -->
        <div style="position: relative; z-index: 10; padding: 25px 30px 15px 30px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="display: flex; gap: 15px; align-items: center;">
                <div style="background: #fff; padding: 8px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="width: 45px; height: 45px; object-fit: contain;">
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 900; font-size: 22px; letter-spacing: 1px; color: #f8fafc; text-transform: uppercase; text-shadow: 0 2px 4px rgba(0,25,80,0.5);">Universitas BSI</h4>
                    <div style="font-size: 11px; color: #e0f2fe; font-weight: 600; letter-spacing: 0.5px;">FAKULTAS TEKNIK DAN INFORMATIKA</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; font-weight: 800; color: #bae6fd; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2px;">KARTU MAHASISWA</div>
                <div style="font-size: 16px; font-weight: 800; color: #fff; font-family: 'Courier New', Courier, monospace; letter-spacing: 1px; background: rgba(255,255,255,0.15); padding: 4px 10px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">{{ $mahasiswa->nim ?? '-' }}</div>
            </div>
        </div>

        <!-- Main Content -->
        <div style="position: relative; z-index: 10; padding: 15px 30px 30px 30px; display: flex; gap: 25px;">
            <!-- Photo -->
            <div style="position: relative; flex-shrink: 0;">
                <div style="width: 130px; height: 150px; border-radius: 16px; padding: 3px; background: linear-gradient(135deg, #38bdf8, #0284c7); box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                    <div style="width: 100%; height: 100%; border-radius: 13px; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-fill" style="font-size: 70px; color: #94a3b8;"></i>
                    </div>
                </div>
                <!-- Decorative badge -->
                <div style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); background: #38bdf8; color: #082f49; font-size: 10px; font-weight: 900; padding: 4px 16px; border-radius: 20px; letter-spacing: 1px; box-shadow: 0 4px 10px rgba(0,0,0,0.2); white-space: nowrap;">
                    MAHASISWA
                </div>
            </div>

            <!-- Details -->
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; padding-right: 10px;">
                <h2 style="margin: 0 0 4px 0; font-weight: 800; font-size: 22px; color: #fff; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,50,100,0.4);">{{ strtoupper($mahasiswa->nama) }}</h2>
                <div style="font-size: 12px; color: #e0f2fe; font-weight: 600; margin-bottom: 15px; letter-spacing: 0.5px;">Sistem Informasi Akuntansi (D3)</div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px 15px; font-size: 12px;">
                    <div style="background: rgba(255,255,255,0.1); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                        <div style="color: #bae6fd; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">Nomor Induk</div>
                        <div style="font-weight: 800; color: #fff; font-size: 13px; font-family: monospace;">{{ $mahasiswa->nim }}</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                        <div style="color: #bae6fd; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">Kelas</div>
                        <div style="font-weight: 800; color: #fff; font-size: 13px;">{{ $mahasiswa->kelas }}</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                        <div style="color: #bae6fd; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">Status</div>
                        <div style="font-weight: 800; color: #10b981; font-size: 13px; display: flex; align-items: center;"><i class="bi bi-patch-check-fill me-1"></i> Aktif</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); padding: 8px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                        <div style="color: #bae6fd; font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 2px;">Terdaftar</div>
                        <div style="font-weight: 800; color: #fff; font-size: 13px;">{{ $mahasiswa->created_at->format('M Y') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- QR Code Right -->
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; padding-left: 20px; border-left: 1px dashed rgba(255,255,255,0.3);">
                <div style="background: #fff; padding: 6px; border-radius: 12px; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&ecc=H&data={{ urlencode($qrUrl) }}" style="width: 80px; height: 80px; display: block; border-radius: 6px;">
                    <img src="{{ asset('img/logo_ubsi.png') }}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 24px; height: 24px; background: white; padding: 2px; border-radius: 6px;">
                </div>
                <div style="font-size: 9px; color: #e0f2fe; margin-top: 10px; font-weight: 800; letter-spacing: 1px; text-align: center;">KTM VALID</div>
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
    #mahasiswa-card {
        box-shadow: none !important;
        border: 2px solid #1e3a8a !important;
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
