import re

with open('resources/views/penghargaan-universitas/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# The edit modal block
modal_regex = r'(\s*<!-- Edit Modal -->\s*<div class="modal fade" id="editModal\{\{ \$item->id \}\}".*?<div class="modal-dialog">.*?<div class="modal-content">.*?<div class="modal-header">.*?<h5 class="modal-title" id="editModalLabel\{\{ \$item->id \}\}">Edit Penghargaan Universitas</h5>.*?<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>.*?</div>.*?<form action="\{\{ route\(\'penghargaan-universitas.update\', \$item->id\) \}\}" method="POST" enctype="multipart/form-data">.*?@csrf.*?@method\(\'PUT\'\).*?<div class="modal-body">.*?<div class="mb-3">.*?<label class="form-label">Tahun</label>.*?<input type="number" name="tahun" class="form-control" value="\{\{ \$item->tahun \}\}" required min="1900" max="2100">.*?</div>.*?<div class="mb-3">.*?<label class="form-label">Nama Mitra</label>.*?<input type="text" name="nama_mitra" class="form-control" value="\{\{ \$item->nama_mitra \}\}" required>.*?</div>.*?<div class="mb-3">.*?<label class="form-label">Nomor Penghargaan</label>.*?<input type="text" name="nomor_penghargaan" class="form-control" value="\{\{ \$item->nomor_penghargaan \}\}">.*?</div>.*?<div class="mb-3">.*?<label class="form-label">Upload Dokumen Penghargaan \(PDF, JPG, PNG\)</label>.*?@if\(\$item->link_dokumen_penghargaan\).*?<div class="mb-2">.*?<a href="\{\{ str_starts_with\(\$item->link_dokumen_penghargaan, \'http\'\) \? \$item->link_dokumen_penghargaan : asset\(\$item->link_dokumen_penghargaan\) \}\}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-file-earmark-text"></i> Lihat Dokumen Saat Ini</a>.*?</div>.*?@endif.*?<input type="file" name="link_dokumen_penghargaan" class="form-control" accept="\.pdf,\.jpg,\.jpeg,\.png">.*?<small class="text-muted">Biarkan kosong jika tidak ingin mengubah dokumen\.</small>.*?</div>.*?<div class="mb-3">.*?<label class="form-label">Link Berita</label>.*?<input type="url" name="link_berita" class="form-control" value="\{\{ \$item->link_berita \}\}">.*?<small class="text-muted">Misal: https://berita\.com/\.\.\.</small>.*?</div>.*?</div>.*?<div class="modal-footer">.*?<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>.*?<button type="submit" class="btn btn-primary">Simpan Perubahan</button>.*?</div>.*?</form>.*?</div>.*?</div>.*?</div>)'

match = re.search(modal_regex, content, re.DOTALL)
if match:
    modal_content = match.group(1)
    
    # Remove from table
    content = content.replace(modal_content, "")
    
    # Append to bottom just before Add Modal
    new_modals_loop = f"""
@foreach($penghargaanUniversitas as $item)
{modal_content}
@endforeach

<!-- Add Modal -->"""
    
    content = content.replace("<!-- Add Modal -->", new_modals_loop)
    
    with open('resources/views/penghargaan-universitas/index.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)
else:
    print("Modal regex not found")
