import re

with open('resources/views/matakuliah/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

stats_html = """                <!-- Total SKS -->
                <div class="mb-4 text-center py-3" style="background: linear-gradient(135deg, #10b981, #059669) !important; color: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                    <span class="text-white-50 small d-block mb-1">Total SKS</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalSks }} SKS</h2>
                </div>

                <!-- Statistik Pembelajaran (PBL vs Reguler) -->
                <div class="mb-4 border-bottom border-light pb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-info text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #06b6d4, #0891b2) !important;">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">Sistem Pembelajaran</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-1 mt-3">
                        <span class="small fw-semibold text-dark">PBL & E-Learning</span>
                        <span class="small badge bg-info text-dark rounded-pill">{{ $totalPbl }} MK ({{ $persentasePbl }}%)</span>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persentasePbl }}%;" aria-valuenow="{{ $persentasePbl }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-dark">Reguler</span>
                        <span class="small badge bg-secondary rounded-pill">{{ $totalReguler }} MK ({{ $persentaseReguler }}%)</span>
                    </div>
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $persentaseReguler }}%;" aria-valuenow="{{ $persentaseReguler }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>"""

content = content.replace("""                <!-- Total SKS -->
                <div class="mb-4 text-center py-3" style="background: linear-gradient(135deg, #10b981, #059669) !important; color: #fff; border-radius: 14px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                    <span class="text-white-50 small d-block mb-1">Total SKS</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalSks }} SKS</h2>
                </div>""", stats_html)

with open('resources/views/matakuliah/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
