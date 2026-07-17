<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Barcode/QR - {{ $kegiatan->nama_kegiatan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            color: #f8fafc;
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }
        .scanner-container {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            background: #000;
        }
        .btn-check:checked + .btn-outline-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
            border-color: transparent !important;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }
        .btn-check:checked + .btn-outline-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            border-color: transparent !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        #reader {
            width: 100%;
        }
        /* Styling scan overlay */
        .scan-laser {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #ef4444;
            box-shadow: 0 0 12px #ef4444;
            animation: laserScan 2s infinite linear;
            z-index: 10;
            pointer-events: none;
        }
        @keyframes laserScan {
            0% { top: 0%; }
            50% { top: 100%; }
            100% { top: 0%; }
        }
        .log-item {
            animation: slideIn 0.3s ease-out forwards;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-4">
                <a href="{{ route('kegiatan.show', $kegiatan) }}" class="btn btn-outline-light btn-sm mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Kegiatan
                </a>
                <h2 class="fw-bold text-white mb-1"><i class="bi bi-qr-code-scan me-2 text-info"></i>Presensi Barcode/QR Code</h2>
                <h5 class="text-white-50 fw-normal">{{ $kegiatan->nama_kegiatan }}</h5>
                <span class="badge bg-secondary px-3 py-1.5 rounded-pill"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d M Y') }}</span>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Kiri: Mode Presensi & Scanner -->
            <div class="col-lg-6">
                <div class="glass-panel p-4 h-100">
                    <h5 class="fw-bold text-white mb-3 text-center">1. Pilih Mode Kehadiran</h5>
                    
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <input type="radio" class="btn-check" name="tipe_presensi" id="mode_masuk" value="masuk" checked autocomplete="off">
                        <label class="btn btn-outline-primary py-3 px-4 w-50 fw-bold" for="mode_masuk">
                            <i class="bi bi-box-arrow-in-right me-2 fs-5"></i>Presensi Masuk
                        </label>

                        <input type="radio" class="btn-check" name="tipe_presensi" id="mode_pulang" value="pulang" autocomplete="off">
                        <label class="btn btn-outline-success py-3 px-4 w-50 fw-bold" for="mode_pulang">
                            <i class="bi bi-box-arrow-left me-2 fs-5"></i>Presensi Pulang (Makan)
                        </label>
                    </div>

                    <h5 class="fw-bold text-white mb-3 text-center">2. Arahkan Barcode/QR ke Kamera</h5>

                    <div class="scanner-container">
                        <div class="scan-laser" id="laser"></div>
                        <div id="reader"></div>
                    </div>

                    <!-- Manual Input Form -->
                    <div class="mt-4">
                        <div class="input-group">
                            <input type="text" id="manual_token" class="form-control bg-dark text-white border-secondary" placeholder="Atau ketik Barcode Token manual disini...">
                            <button class="btn btn-primary" type="button" id="btn_submit_manual">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan: Status & Log Aktivitas -->
            <div class="col-lg-6">
                <div class="glass-panel p-4 h-100 d-flex flex-column">
                    <h5 class="fw-bold text-white mb-3 text-center"><i class="bi bi-activity me-2 text-warning"></i>Hasil Scan Terbaru</h5>

                    <!-- Status Display -->
                    <div id="scan_status_box" class="alert alert-secondary py-4 text-center border-0 mb-4" style="border-radius: 12px; background: rgba(255,255,255,0.02)">
                        <i class="bi bi-qr-code fs-1 d-block mb-2 text-white-50"></i>
                        <span class="text-white-50">Menunggu scan barcode atau QR code dari peserta...</span>
                    </div>

                    <h5 class="fw-bold text-white mb-3"><i class="bi bi-clock-history me-2 text-info"></i>Riwayat Scan Hari Ini</h5>
                    <!-- Riwayat list -->
                    <div class="flex-grow-1 overflow-auto bg-black bg-opacity-25 rounded p-3" style="max-height: 250px;" id="scan_history_list">
                        <div class="text-white-50 text-center py-4 small" id="history_placeholder">Belum ada riwayat presensi dalam sesi ini.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner library CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // Web Audio API Sound generator for scan feedback (no external file files needed)
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        
        function playBeep(success = true) {
            try {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                if (success) {
                    osc.frequency.setValueAtTime(800, audioCtx.currentTime); // High pitch for success
                    gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.15);
                } else {
                    osc.frequency.setValueAtTime(150, audioCtx.currentTime); // Low pitch for error
                    gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.4);
                }
            } catch (e) {
                console.log("Audio play error", e);
            }
        }

        // Presence scan function
        let isProcessing = false;

        function processScan(barcodeToken) {
            if (isProcessing) return;
            isProcessing = true;

            const mode = document.querySelector('input[name="tipe_presensi"]:checked').value;
            const statusBox = document.getElementById('scan_status_box');
            
            // Show scanning state
            statusBox.className = 'alert alert-info py-4 text-center border-0 mb-4';
            statusBox.style.borderRadius = "12px";
            statusBox.innerHTML = `
                <div class="spinner-border text-primary mb-2" role="status"></div>
                <div class="fw-bold text-white">Memproses token: ${barcodeToken}...</div>
            `;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('kegiatan.presensi.submit', $kegiatan) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    barcode_token: barcodeToken,
                    tipe: mode
                })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status >= 200 && status < 300) {
                    playBeep(true);
                    
                    // Display success
                    statusBox.className = 'alert alert-success py-4 text-center border-0 mb-4';
                    statusBox.innerHTML = `
                        <i class="bi bi-check-circle-fill fs-1 d-block mb-2 text-success"></i>
                        <h4 class="fw-bold text-white mb-1">${body.peserta.nama}</h4>
                        <span class="badge bg-success mb-2">${body.peserta.identifier}</span>
                        <div class="text-white-50 small">${body.message}</div>
                    `;

                    // Add to log list
                    addLogToList(body.peserta.nama, body.peserta.identifier, mode, true, body.message);
                } else {
                    playBeep(false);
                    
                    // Display error
                    statusBox.className = 'alert alert-danger py-4 text-center border-0 mb-4';
                    statusBox.innerHTML = `
                        <i class="bi bi-exclamation-triangle-fill fs-1 d-block mb-2 text-danger"></i>
                        <h5 class="fw-bold text-white mb-1">Gagal Presensi</h5>
                        <div class="text-white-50 small">${body.message || 'Terjadi kesalahan sistem.'}</div>
                    `;

                    addLogToList('Gagal', barcodeToken, mode, false, body.message || 'Error');
                }
            })
            .catch(err => {
                playBeep(false);
                statusBox.className = 'alert alert-danger py-4 text-center border-0 mb-4';
                statusBox.innerHTML = `
                    <i class="bi bi-x-circle-fill fs-1 d-block mb-2 text-danger"></i>
                    <h5 class="fw-bold text-white mb-1">Koneksi Error</h5>
                    <div class="text-white-50 small">Gagal menghubungi server. Pastikan server aktif.</div>
                `;
                console.error("Fetch error:", err);
            })
            .finally(() => {
                // Cool down to prevent duplicate scans
                setTimeout(() => {
                    isProcessing = false;
                }, 2000);
            });
        }

        // Add scan items to local UI history logs
        function addLogToList(nama, nipNim, mode, success, message) {
            const list = document.getElementById('scan_history_list');
            const placeholder = document.getElementById('history_placeholder');
            if (placeholder) placeholder.remove();

            const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const badgeColor = mode === 'masuk' ? 'primary' : 'success';
            const modeText = mode === 'masuk' ? 'MASUK' : 'PULANG';

            const item = document.createElement('div');
            item.className = `log-item d-flex justify-content-between align-items-center p-2 mb-2 rounded border border-light bg-dark bg-opacity-50 small`;
            item.innerHTML = `
                <div>
                    <span class="badge bg-${badgeColor} me-2">${modeText}</span>
                    <strong class="${success ? 'text-white' : 'text-danger'}">${nama}</strong> 
                    <span class="text-white-50">(${nipNim})</span>
                </div>
                <div class="text-white-50 small">${time}</div>
            `;
            list.insertBefore(item, list.firstChild);
        }

        // Handle camera scan QR/Barcode
        function onScanSuccess(decodedText, decodedResult) {
            // decodedText is the barcode value
            processScan(decodedText);
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            }
        );
        html5QrcodeScanner.render(onScanSuccess);

        // Handle manual input submit button
        document.getElementById('btn_submit_manual').addEventListener('click', function() {
            const input = document.getElementById('manual_token');
            const val = input.value.trim();
            if (val) {
                processScan(val);
                input.value = '';
            }
        });

        document.getElementById('manual_token').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('btn_submit_manual').click();
            }
        });
    </script>
</body>
</html>
