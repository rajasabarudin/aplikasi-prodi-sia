import re

with open('resources/views/penelitian_dosen/show.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

buttons = """
        <div class="dropdown d-inline-block">
            <button class="btn btn-info text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-file-word"></i> Generate Dokumen
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('penelitian-dosen.generate-proposal', $penelitianDosen) }}"><i class="bi bi-file-earmark-word text-primary"></i> Generate Proposal</a></li>
                <li><a class="dropdown-item" href="{{ route('penelitian-dosen.generate-laporan', $penelitianDosen) }}"><i class="bi bi-file-earmark-word text-success"></i> Generate Laporan</a></li>
            </ul>
        </div>
        <a href="{{ route('penelitian-dosen.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
"""

content = content.replace('<a href="{{ route(\'penelitian-dosen.index\') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>', buttons)

with open('resources/views/penelitian_dosen/show.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
