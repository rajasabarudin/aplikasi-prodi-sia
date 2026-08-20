import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

integration_html = """                <!-- Statistik Integrasi OBE -->
                <div class="mb-4 border-bottom border-light pb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">Integrasi (OBE)</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-1 mt-3">
                        <span class="small fw-semibold text-dark">Penelitian</span>
                        <span class="small badge bg-success rounded-pill">{{ $totalPenelitian }} MK ({{ $persentasePenelitian }}%)</span>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentasePenelitian }}%;" aria-valuenow="{{ $persentasePenelitian }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-dark">PkM</span>
                        <span class="small badge bg-info text-dark rounded-pill">{{ $totalPkm }} MK ({{ $persentasePkm }}%)</span>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persentasePkm }}%;" aria-valuenow="{{ $persentasePkm }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Berdasarkan Jenis -->"""

content = content.replace("                <!-- Berdasarkan Jenis -->", integration_html)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
