<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presensi Mandiri - {{ $kegiatan->nama_kegiatan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: radial-gradient(circle at 10% 20%, rgba(240, 244, 255, 1) 0%, rgba(249, 245, 255, 1) 90.1%) !important;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .absensi-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .card-header-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.8);
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            border-color: #10b981;
        }
        .btn-submit {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="absensi-card">
        <div class="card-header-gradient">
            <i class="bi bi-laptop fs-1 mb-2"></i>
            <h4 class="fw-bold mb-1">Presensi Mandiri Online</h4>
            <span class="text-white-50 small">{{ $kegiatan->nama_kegiatan }}</span>
        </div>
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ route('portal.kegiatan.absensi-mandiri-submit', $kegiatan) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="identifier" class="form-label fw-semibold">NIM / NIP / Token Tiket Anda <span class="text-danger">*</span></label>
                    <input type="text" name="identifier" id="identifier" class="form-control" placeholder="Contoh: 12210001" required autofocus>
                    <div class="form-text text-muted small mt-2">
                        Masukkan NIM / NIP yang Anda gunakan saat mendaftar kegiatan ini. Presensi hanya perlu dilakukan 1x di akhir sesi Zoom/Meet.
                    </div>
                </div>

                <button type="submit" class="btn btn-submit w-100 py-2.5">
                    <i class="bi bi-patch-check-fill me-2"></i>Verifikasi & Kirim Kehadiran
                </button>
            </form>

            <div class="text-center mt-4 border-top pt-3">
                <a href="{{ route('portal.kegiatan') }}" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Portal Utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>
