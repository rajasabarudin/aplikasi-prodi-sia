import re

with open('routes/web.php', 'r', encoding='utf-8') as f:
    content = f.read()

new_routes = """
    Route::get('penelitian-dosen/{penelitian_dosen}/generate-proposal', [PenelitianDosenController::class, 'generateProposal'])->name('penelitian-dosen.generate-proposal');
    Route::get('penelitian-dosen/{penelitian_dosen}/generate-laporan', [PenelitianDosenController::class, 'generateLaporan'])->name('penelitian-dosen.generate-laporan');
    Route::post('penelitian-dosen/{penelitian_dosen}/update-document'
"""

content = content.replace("Route::post('penelitian-dosen/{penelitian_dosen}/update-document'", new_routes.strip())

with open('routes/web.php', 'w', encoding='utf-8') as f:
    f.write(content)
