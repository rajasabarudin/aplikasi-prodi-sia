<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presensi Belum Dibuka - {{ $kegiatan->nama_kegiatan }}</title>
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
            text-align: center;
        }
        .card-header-gradient {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 30px;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="absensi-card">
        <div class="card-header-gradient">
            <i class="bi bi-clock-history fs-1 mb-2"></i>
            <h4 class="fw-bold mb-1">Presensi Belum Dibuka</h4>
            <span class="text-white-50 small">{{ $kegiatan->nama_kegiatan }}</span>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-warning border-0 shadow-sm mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>Akses presensi mandiri saat ini **belum dibuka** atau **sudah ditutup** oleh panitia.
            </div>

            <p class="text-muted small">
                Presensi mandiri online biasanya dibuka oleh panitia di bagian akhir sesi Zoom/Meet. Silakan tunggu informasi dari panitia pelaksana.
            </p>

            <div class="text-center mt-4 border-top pt-3">
                <a href="{{ route('portal.kegiatan') }}" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Portal Utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>
