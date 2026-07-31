@extends('layouts.digital-twin')

@section('title', 'Peta Zonasi Risiko Ganoderma - Digital Twin')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">Virtual Grid & Peta Zonasi (AST-DSRA v2)</h2>
            <p class="text-muted mb-0">Pemetaan Spasial Probabilitas Infeksi Berbasis Sensor IoT (Episentrum)</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="d-block text-white-50">Suhu / Kelembaban IoT Terkini</small>
                        <span class="fw-bold fs-5">
                            {{ $currentIot->suhu_tanah_celcius ?? 0 }}°C / {{ $currentIot->kelembaban_tanah_persen ?? 0 }}%
                        </span>
                    </div>
                    <i class="fas fa-satellite-dish fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Panel Grid Interaktif -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-project-diagram text-success me-2"></i> Simulasi Sebaran Jarak Tanam (Segitiga 9x9m)</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="simulateSpread()"><i class="fas fa-play me-1"></i> Mulai Simulasi Penyebaran</button>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center bg-light rounded m-3" style="min-height: 500px; overflow: hidden; position: relative;">
                    <!-- Area Canvas untuk menggambar grid pohon -->
                    <canvas id="plantationGrid" width="600" height="500"></canvas>
                </div>
            </div>
        </div>

        <!-- Panel Informasi Pohon -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle text-info me-2"></i> Detail Node Pohon</h5>
                </div>
                <div class="card-body" id="nodeInfo">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-hand-pointer fs-1 mb-3 opacity-25"></i>
                        <p>Klik pada salah satu titik pohon di peta untuk melihat detail risiko penyebarannya.</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 border-bottom border-secondary pb-2">Legenda Peta Zonasi</h6>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge rounded-circle p-2 bg-black me-2" style="width: 15px; height: 15px;"></span>
                        <span>Episentrum (Pohon IoT Terinfeksi)</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge rounded-circle p-2 bg-danger me-2" style="width: 15px; height: 15px;"></span>
                        <span>Risiko Tinggi (Probabilitas > 75%)</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge rounded-circle p-2 bg-warning me-2" style="width: 15px; height: 15px;"></span>
                        <span>Risiko Sedang (Probabilitas 40% - 75%)</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge rounded-circle p-2 bg-success me-2" style="width: 15px; height: 15px;"></span>
                        <span>Risiko Rendah (Probabilitas < 40%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('plantationGrid');
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const scale = 40; // Pixel per satuan jarak
        const trees = [];
        
        // Data Mikroklimat dari Controller
        const suhu = {{ $currentIot->suhu_tanah_celcius ?? 28 }};
        const kelembaban = {{ $currentIot->kelembaban_tanah_persen ?? 70 }};
        
        // Fungsi sederhana untuk menghitung Bobot Mikroklimat (Dummy Fuzzy Logic)
        // Kelembaban tinggi (>80%) memperparah penyebaran.
        let microclimateWeight = 1.0;
        if(kelembaban > 80) microclimateWeight = 1.5;
        else if(kelembaban < 50) microclimateWeight = 0.5;

        // Generate pola Segitiga Sama Sisi
        // Ring 0 (Episentrum)
        trees.push({id: 'T-0', x: 0, y: 0, ring: 0, risk: 100, isCenter: true});
        
        // Generate Ring 1 s/d 3
        const rings = 3;
        for (let r = 1; r <= rings; r++) {
            const numNodes = r * 6;
            const angleStep = (2 * Math.PI) / numNodes;
            for (let i = 0; i < numNodes; i++) {
                const angle = i * angleStep;
                const distance = r; // Satuan jarak tanam (misal 1 unit = 9 meter)
                const px = Math.cos(angle) * distance;
                const py = Math.sin(angle) * distance;
                
                // Kalkulasi risiko berdasarkan fungsi AST-DSRA v2 (Versi Sederhana/Simulasi)
                // Risk = (1 / Jarak) * Bobot Lingkungan
                let riskScore = (1 / Math.pow(r, 1.2)) * microclimateWeight * 100;
                if(riskScore > 100) riskScore = 99; // Cap at 99% for neighbors
                
                trees.push({
                    id: `T-${r}-${i}`,
                    x: px,
                    y: py,
                    ring: r,
                    risk: Math.round(riskScore),
                    isCenter: false,
                    distanceUnit: r
                });
            }
        }

        // Fungsi Menggambar Grid & Pohon
        function drawTrees(animate = false) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Draw connections (akar/miselium)
            ctx.strokeStyle = 'rgba(0,0,0,0.05)';
            ctx.lineWidth = 1;
            for(let i=0; i<trees.length; i++) {
                for(let j=i+1; j<trees.length; j++) {
                    const dx = trees[i].x - trees[j].x;
                    const dy = trees[i].y - trees[j].y;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    if(dist <= 1.1) { // Hanya gambar garis ke tetangga terdekat
                        ctx.beginPath();
                        ctx.moveTo(centerX + trees[i].x * scale, centerY + trees[i].y * scale);
                        ctx.lineTo(centerX + trees[j].x * scale, centerY + trees[j].y * scale);
                        ctx.stroke();
                    }
                }
            }

            // Draw trees
            trees.forEach(tree => {
                const screenX = centerX + tree.x * scale;
                const screenY = centerY + tree.y * scale;
                
                ctx.beginPath();
                ctx.arc(screenX, screenY, tree.isCenter ? 12 : 8, 0, 2 * Math.PI);
                
                if (tree.isCenter) {
                    ctx.fillStyle = '#000000'; // Episentrum (Hitam)
                    // Pulse effect (simulasi sensor IoT)
                    ctx.shadowBlur = 15;
                    ctx.shadowColor = 'red';
                } else {
                    ctx.shadowBlur = 0;
                    if(animate) {
                        if (tree.risk > 75) ctx.fillStyle = '#dc3545'; // Tinggi (Merah)
                        else if (tree.risk > 40) ctx.fillStyle = '#ffc107'; // Sedang (Kuning)
                        else ctx.fillStyle = '#198754'; // Rendah (Hijau)
                    } else {
                        ctx.fillStyle = '#6c757d'; // Default abu-abu sebelum simulasi
                    }
                }
                
                ctx.fill();
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.stroke();
            });
        }
        
        drawTrees(false); // Gambar kondisi awal

        // Handle Klik pada Canvas
        canvas.addEventListener('click', function(event) {
            const rect = canvas.getBoundingClientRect();
            const clickX = event.clientX - rect.left;
            const clickY = event.clientY - rect.top;
            
            // Cari pohon terdekat yang diklik
            let clickedTree = null;
            trees.forEach(tree => {
                const screenX = centerX + tree.x * scale;
                const screenY = centerY + tree.y * scale;
                const dist = Math.sqrt(Math.pow(clickX - screenX, 2) + Math.pow(clickY - screenY, 2));
                if (dist <= 15) {
                    clickedTree = tree;
                }
            });

            if (clickedTree) {
                const infoDiv = document.getElementById('nodeInfo');
                if (clickedTree.isCenter) {
                    infoDiv.innerHTML = `
                        <div class="alert alert-dark mb-0">
                            <h5 class="fw-bold"><i class="fas fa-broadcast-tower me-2"></i>Pohon Episentrum</h5>
                            <p class="mb-1"><strong>Status:</strong> Terinfeksi Positif (Sumber)</p>
                            <p class="mb-1"><strong>Node IoT:</strong> Aktif Merekam Data</p>
                            <hr>
                            <p class="mb-0 small text-muted">Radius penyebaran berawal dari titik ini.</p>
                        </div>
                    `;
                } else {
                    let badgeClass = clickedTree.risk > 75 ? 'bg-danger' : (clickedTree.risk > 40 ? 'bg-warning text-dark' : 'bg-success');
                    infoDiv.innerHTML = `
                        <div class="p-3 border rounded">
                            <h6 class="fw-bold text-primary mb-3">ID Pohon Tetangga: ${clickedTree.id}</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Posisi Ring</td>
                                    <td class="fw-bold">: Ring ${clickedTree.ring} (${clickedTree.distanceUnit * 9} meter)</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status Risiko</td>
                                    <td>: <span class="badge ${badgeClass} fs-6">${clickedTree.risk}%</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kondisi Histori</td>
                                    <td>: <select class="form-select form-select-sm mt-1">
                                            <option>Aman (Tidak ada riwayat)</option>
                                            <option>Pernah terserang 2 thn lalu</option>
                                            <option>Ada sisa tunggul mati</option>
                                          </select>
                                    </td>
                                </tr>
                            </table>
                            <div class="mt-3">
                                <button class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-save me-1"></i> Simpan Histori Node</button>
                            </div>
                        </div>
                    `;
                }
            }
        });

        // Expose fungsi ke global agar bisa dipanggil tombol
        window.simulateSpread = function() {
            drawTrees(true);
        }
    });
</script>
@endpush
