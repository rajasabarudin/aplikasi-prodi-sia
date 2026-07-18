@extends('layouts.app')

@section('title', 'Data Beasiswa Mahasiswa')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Data Beasiswa Mahasiswa</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Beasiswa Mahasiswa</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Data
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-end mb-3">
                <form action="{{ route('beasiswa-mahasiswa.index') }}" method="GET" class="d-flex gap-2" style="width: 300px;">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari NIM, Nama..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('beasiswa-mahasiswa.index') }}" class="btn btn-outline-danger" title="Reset"><i class="bi bi-x-lg"></i></a>
                    @endif
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jenis Beasiswa</th>
                            <th>Kategori Beasiswa</th>
                            <th class="text-center">Link Dokumen</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beasiswas as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $item->nim }}</span></td>
                            <td class="fw-bold">{{ $item->mahasiswa->nama ?? 'Data Tidak Ditemukan' }}</td>
                            <td>
                                @if($item->jenis_beasiswa == 'internal')
                                    <span class="badge bg-primary rounded-pill px-3">Internal</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">Eksternal</span>
                                @endif
                            </td>
                            <td>{{ $item->kategori_beasiswa }}</td>
                            <td class="text-center">
                                @if($item->link_dokumen)
                                    <a href="{{ $item->link_dokumen }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                        <i class="bi bi-link-45deg"></i> Buka Link
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>


                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data beasiswa mahasiswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $beasiswas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Render Modals Outside Table -->
@foreach($beasiswas as $item)
<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('beasiswa-mahasiswa.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="editModalLabel{{ $item->id }}">Edit Data Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM Mahasiswa</label>
                        <div class="input-group">
                            <input type="text" class="form-control edit-nim-input" name="nim" value="{{ $item->nim }}" data-id="{{ $item->id }}" required>
                            <button class="btn btn-outline-secondary edit-cek-nim-btn" type="button" data-id="{{ $item->id }}">Cek</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" class="form-control bg-light edit-nama-input" id="edit-nama-{{ $item->id }}" value="{{ $item->mahasiswa->nama ?? '' }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Beasiswa</label>
                        <select class="form-select" name="jenis_beasiswa" required>
                            <option value="internal" {{ $item->jenis_beasiswa == 'internal' ? 'selected' : '' }}>Internal</option>
                            <option value="eksternal" {{ $item->jenis_beasiswa == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Beasiswa</label>
                        <input type="text" class="form-control" name="kategori_beasiswa" value="{{ $item->kategori_beasiswa }}" required placeholder="Contoh: KIP-Kuliah, Beasiswa Prestasi, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Dokumen (Opsional)</label>
                        <input type="url" class="form-control" name="link_dokumen" value="{{ $item->link_dokumen }}" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-5">
                <div class="text-danger mb-4">
                    <i class="bi bi-exclamation-circle" style="font-size: 4rem;"></i>
                </div>
                <h4 class="mb-3 fw-bold">Konfirmasi Hapus</h4>
                <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus data beasiswa mahasiswa <b>{{ $item->mahasiswa->nama ?? $item->nim }}</b>?</p>
                <form action="{{ route('beasiswa-mahasiswa.destroy', $item->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Add Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('beasiswa-mahasiswa.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="tambahModalLabel">Tambah Data Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM Mahasiswa</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nim" id="tambah-nim" required placeholder="Masukkan NIM">
                            <button class="btn btn-outline-secondary" type="button" id="btn-cek-nim">Cek</button>
                        </div>
                        <small id="nim-feedback" class="text-danger d-none">Mahasiswa tidak ditemukan.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Mahasiswa</label>
                        <input type="text" class="form-control bg-light" id="tambah-nama" readonly placeholder="Nama akan otomatis terisi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Beasiswa</label>
                        <select class="form-select" name="jenis_beasiswa" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="internal">Internal</option>
                            <option value="eksternal">Eksternal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori Beasiswa</label>
                        <input type="text" class="form-control" name="kategori_beasiswa" required placeholder="Contoh: KIP-Kuliah, Beasiswa Prestasi, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Dokumen (Opsional)</label>
                        <input type="url" class="form-control" name="link_dokumen" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="btn-submit-tambah" disabled>Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cek NIM Tambah Data
        const btnCekNim = document.getElementById('btn-cek-nim');
        const inputNim = document.getElementById('tambah-nim');
        const inputNama = document.getElementById('tambah-nama');
        const btnSubmit = document.getElementById('btn-submit-tambah');
        const feedback = document.getElementById('nim-feedback');

        btnCekNim.addEventListener('click', function() {
            const nim = inputNim.value;
            if(!nim) return;
            
            btnCekNim.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            
            fetch(`/admin/beasiswa-mahasiswa/get-mahasiswa/${nim}`)
                .then(res => res.json())
                .then(data => {
                    btnCekNim.innerHTML = 'Cek';
                    if(data.success) {
                        inputNama.value = data.nama;
                        feedback.classList.add('d-none');
                        btnSubmit.removeAttribute('disabled');
                        inputNim.classList.remove('is-invalid');
                        inputNim.classList.add('is-valid');
                    } else {
                        inputNama.value = '';
                        feedback.classList.remove('d-none');
                        btnSubmit.setAttribute('disabled', 'true');
                        inputNim.classList.add('is-invalid');
                        inputNim.classList.remove('is-valid');
                    }
                })
                .catch(err => {
                    btnCekNim.innerHTML = 'Cek';
                    console.error(err);
                });
        });

        // Cek NIM Edit Data
        const btnCekEdit = document.querySelectorAll('.edit-cek-nim-btn');
        btnCekEdit.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const inputNimEdit = document.querySelector(`.edit-nim-input[data-id="${id}"]`);
                const inputNamaEdit = document.getElementById(`edit-nama-${id}`);
                const nim = inputNimEdit.value;
                if(!nim) return;
                
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                
                fetch(`/admin/beasiswa-mahasiswa/get-mahasiswa/${nim}`)
                    .then(res => res.json())
                    .then(data => {
                        this.innerHTML = 'Cek';
                        if(data.success) {
                            inputNamaEdit.value = data.nama;
                            inputNimEdit.classList.remove('is-invalid');
                            inputNimEdit.classList.add('is-valid');
                        } else {
                            inputNamaEdit.value = 'Tidak ditemukan';
                            inputNimEdit.classList.add('is-invalid');
                            inputNimEdit.classList.remove('is-valid');
                        }
                    })
                    .catch(err => {
                        this.innerHTML = 'Cek';
                        console.error(err);
                    });
            });
        });
    });
</script>
@endpush
