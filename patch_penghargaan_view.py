import re

with open('resources/views/penghargaan-universitas/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Display Link
content = content.replace(
    '''<a href="{{ $item->link_dokumen_penghargaan }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Dokumen"><i class="bi bi-file-earmark-text"></i> Dokumen</a>''',
    '''<a href="{{ str_starts_with($item->link_dokumen_penghargaan, 'http') ? $item->link_dokumen_penghargaan : asset($item->link_dokumen_penghargaan) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Lihat Dokumen"><i class="bi bi-file-earmark-text"></i> Dokumen</a>'''
)

# 2. Update Edit Form Enctype
content = content.replace(
    '''<form action="{{ route('penghargaan-universitas.update', $item->id) }}" method="POST">''',
    '''<form action="{{ route('penghargaan-universitas.update', $item->id) }}" method="POST" enctype="multipart/form-data">'''
)

# 3. Update Edit Input
old_edit_input = '''<label class="form-label">Link Dokumen Penghargaan</label>
                                                    <input type="url" name="link_dokumen_penghargaan" class="form-control" value="{{ $item->link_dokumen_penghargaan }}">
                                                    <small class="text-muted">Misal: https://drive.google.com/...</small>'''

new_edit_input = '''<label class="form-label">Upload Dokumen Penghargaan (PDF, JPG, PNG)</label>
                                                    @if($item->link_dokumen_penghargaan)
                                                        <div class="mb-2">
                                                            <a href="{{ str_starts_with($item->link_dokumen_penghargaan, 'http') ? $item->link_dokumen_penghargaan : asset($item->link_dokumen_penghargaan) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-text"></i> Lihat Dokumen Saat Ini</a>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="link_dokumen_penghargaan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah dokumen.</small>'''
content = content.replace(old_edit_input, new_edit_input)

# 4. Update Add Form Enctype
content = content.replace(
    '''<form action="{{ route('penghargaan-universitas.store') }}" method="POST">''',
    '''<form action="{{ route('penghargaan-universitas.store') }}" method="POST" enctype="multipart/form-data">'''
)

# 5. Update Add Input
old_add_input = '''<label class="form-label">Link Dokumen Penghargaan</label>
                        <input type="url" name="link_dokumen_penghargaan" class="form-control">
                        <small class="text-muted">Misal: https://drive.google.com/...</small>'''

new_add_input = '''<label class="form-label">Upload Dokumen Penghargaan (PDF, JPG, PNG)</label>
                        <input type="file" name="link_dokumen_penghargaan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">'''
content = content.replace(old_add_input, new_add_input)

with open('resources/views/penghargaan-universitas/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
