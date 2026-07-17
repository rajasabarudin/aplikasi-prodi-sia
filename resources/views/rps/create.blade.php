@extends('layouts.app')
@section('title', 'Buat RPS Baru')
@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <form action="{{ route('penyusunan-rps.store') }}" method="POST">
            @csrf
            
            <!-- HEADER RPS -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Informasi Umum RPS</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Matakuliah <span class="text-danger">*</span></label>
                            <select name="kode_matakuliah" class="form-select" required>
                                <option value="">-- Pilih Matakuliah --</option>
                                @foreach($matakuliahs as $mk)
                                <option value="{{ $mk->kode_matakuliah }}">{{ $mk->nama_matakuliah }} (Semester {{ $mk->semester }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Dokumen</label>
                            <input type="text" name="nomor_dokumen" class="form-control" placeholder="Contoh: UBSI/SIA/RPS.006.PNK.2/2024">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Penyusunan</label>
                            <input type="date" name="tanggal_penyusunan" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Koordinator Mata Kuliah</label>
                            <input type="text" name="koordinator" list="dosen_list" class="form-control" placeholder="Nama Koordinator">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Ketua Program Studi</label>
                            <input type="text" name="kaprodi" list="dosen_list" class="form-control" placeholder="Nama Kaprodi">
                        </div>

                        <!-- Datalist untuk dropdown Koordinator & Kaprodi -->
                        <datalist id="dosen_list">
                            @foreach($dosens as $d)
                            <option value="{{ $d->nama_dosen }}"></option>
                            @endforeach
                        </datalist>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Dosen Pengembang RPS <span class="text-danger">*</span></label>
                            
                            <div class="card border-1 shadow-none mb-2">
                                <div class="card-header bg-light py-2 px-3 border-bottom">
                                    <small class="fw-bold text-dark"><i class="bi bi-person-check-fill me-1"></i>Pilih Dosen Internal</small>
                                </div>
                                <div class="card-body p-3" style="max-height: 180px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($dosens as $dosen)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="dosen_internal[]" value="{{ $dosen->nama_dosen }}" id="dosen_{{ $dosen->id }}">
                                                <label class="form-check-label" for="dosen_{{ $dosen->id }}" style="font-size: 0.9rem;">
                                                    {{ $dosen->nama_dosen }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <label class="form-label fw-semibold small mt-2">Dosen Eksternal / Tambahan <span class="text-muted">(Opsional)</span></label>
                            <textarea name="dosen_eksternal" class="form-control" rows="2" placeholder="Nama dosen lain (bisa lebih dari satu, pisahkan dengan titik koma ';')"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi Singkat Mata Kuliah</label>
                            <textarea name="deskripsi_singkat" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bahan Kajian (Materi Ajar)</label>
                            <div id="bahan_kajian_container" class="border p-3 rounded bg-light" style="min-height: 80px;">
                                <span class="text-muted small fst-italic">Pilih matakuliah terlebih dahulu untuk menampilkan daftar bahan kajian secara otomatis.</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-journal-bookmark-fill me-2"></i>Integrasi Hasil Penelitian & PkM dalam Pembelajaran (OBE)</h6>
                    <p class="text-muted small">Hubungkan hasil penelitian dan pengabdian kepada masyarakat (PkM) dosen pengampu ke dalam mata kuliah ini sebagai bagian dari rujukan atau bahan ajar.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-1 shadow-none">
                                <div class="card-header bg-light py-2 px-3 border-bottom">
                                    <small class="fw-bold text-dark"><i class="bi bi-search me-1"></i>Pilih Hasil Penelitian Dosen</small>
                                </div>
                                <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                                    @if($penelitians->count() > 0)
                                        @foreach($penelitians as $penelitian)
                                        <div class="mb-3 border-bottom pb-2">
                                            <div class="form-check">
                                                <input class="form-check-input penelitian-checkbox" type="checkbox" name="penelitian_ids[]" value="{{ $penelitian->id }}" id="penelitian_{{ $penelitian->id }}" data-id="{{ $penelitian->id }}">
                                                <label class="form-check-label fw-semibold" for="penelitian_{{ $penelitian->id }}" style="font-size: 0.85rem; cursor: pointer;">
                                                    <strong>[{{ $penelitian->kode_dosen }}] {{ $penelitian->nama_dosen }}</strong><br>
                                                    Jurnal: {{ $penelitian->nama_jurnal }} ({{ $penelitian->jenis_jurnal }})
                                                </label>
                                            </div>
                                            <div class="mt-1 ps-4 d-none" id="integrasi_penelitian_container_{{ $penelitian->id }}">
                                                <input type="text" name="penelitian_integrasi[{{ $penelitian->id }}]" class="form-control form-control-sm" placeholder="Bentuk integrasi (misal: Bahan ajar Bab 3 / Studi kasus UTS)">
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted small fst-italic">Belum ada data penelitian dosen yang terdaftar.</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-1 shadow-none">
                                <div class="card-header bg-light py-2 px-3 border-bottom">
                                    <small class="fw-bold text-dark"><i class="bi bi-search me-1"></i>Pilih Hasil PkM Dosen</small>
                                </div>
                                <div class="card-body p-3" style="max-height: 250px; overflow-y: auto;">
                                    @if($pkms->count() > 0)
                                        @foreach($pkms as $pkm)
                                        <div class="mb-3 border-bottom pb-2">
                                            <div class="form-check">
                                                <input class="form-check-input pkm-checkbox" type="checkbox" name="pkm_ids[]" value="{{ $pkm->id }}" id="pkm_{{ $pkm->id }}" data-id="{{ $pkm->id }}">
                                                <label class="form-check-label fw-semibold" for="pkm_{{ $pkm->id }}" style="font-size: 0.85rem; cursor: pointer;">
                                                    <strong>[{{ $pkm->kode_dosen }}] {{ $pkm->nama_dosen }}</strong><br>
                                                    Tema: {{ $pkm->tema_pkm }} (Mitra: {{ $pkm->mitra }})
                                                </label>
                                            </div>
                                            <div class="mt-1 ps-4 d-none" id="integrasi_pkm_container_{{ $pkm->id }}">
                                                <input type="text" name="pkm_integrasi[{{ $pkm->id }}]" class="form-control form-control-sm" placeholder="Bentuk integrasi (misal: Rujukan praktikum mandiri)">
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted small fst-italic">Belum ada data PkM dosen yang terdaftar.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="fw-bold mb-3 text-primary">Komponen Penilaian & Asesmen</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Kehadiran/Partisipasi (%)</label>
                            <input type="number" name="bobot_kehadiran" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tugas (%)</label>
                            <input type="number" name="bobot_tugas" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Project (%)</label>
                            <input type="number" name="bobot_project" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Praktek/Observasi (%)</label>
                            <input type="number" name="bobot_praktek" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kuis (%)</label>
                            <input type="number" name="bobot_kuis" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">UTS (%)</label>
                            <input type="number" name="bobot_uts" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">UAS (%)</label>
                            <input type="number" name="bobot_uas" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unjuk Kerja/Presentasi (%)</label>
                            <input type="number" name="bobot_presentasi" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label fw-semibold d-block">Metode Asesmen</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="asesmen_tertulis" value="1" id="as_tertulis">
                                <label class="form-check-label" for="as_tertulis">Tes Tertulis</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="asesmen_lisan" value="1" id="as_lisan">
                                <label class="form-check-label" for="as_lisan">Tes Lisan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="asesmen_kinerja" value="1" id="as_kinerja">
                                <label class="form-check-label" for="as_kinerja">Tes Kinerja/Praktik</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="asesmen_portofolio" value="1" id="as_porto">
                                <label class="form-check-label" for="as_porto">Portofolio</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-list-task me-2"></i>Rincian Pertemuan (1-16)</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle-fill me-2"></i>Rincian 16 pertemuan akan otomatis diekstrak dan disimpan dari dokumen RPS berformat PDF berdasarkan Matakuliah yang dipilih setelah Anda menekan tombol "Generate RPS". Anda dapat mengedit rincian tersebut setelah RPS berhasil dibuat.
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mb-5">
                <a href="{{ route('penyusunan-rps.index') }}" class="btn btn-secondary btn-lg px-4">Batal</a>
                <button type="submit" class="btn btn-primary btn-lg px-5"><i class="bi bi-magic me-2"></i>Generate RPS</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle input integrasi penelitian
    $('.penelitian-checkbox').change(function() {
        var id = $(this).data('id');
        if ($(this).is(':checked')) {
            $('#integrasi_penelitian_container_' + id).removeClass('d-none');
            $('#integrasi_penelitian_container_' + id + ' input').attr('required', true);
        } else {
            $('#integrasi_penelitian_container_' + id).addClass('d-none');
            $('#integrasi_penelitian_container_' + id + ' input').removeAttr('required').val('');
        }
    });

    // Toggle input integrasi pkm
    $('.pkm-checkbox').change(function() {
        var id = $(this).data('id');
        if ($(this).is(':checked')) {
            $('#integrasi_pkm_container_' + id).removeClass('d-none');
            $('#integrasi_pkm_container_' + id + ' input').attr('required', true);
        } else {
            $('#integrasi_pkm_container_' + id).addClass('d-none');
            $('#integrasi_pkm_container_' + id + ' input').removeAttr('required').val('');
        }
    });

    $('select[name="kode_matakuliah"]').change(function() {
        var kode = $(this).val();
        var container = $('#bahan_kajian_container');
        
        if(kode) {
            container.html('<span class="text-muted small"><i class="spinner-border spinner-border-sm me-2"></i>Memuat bahan kajian...</span>');
            $.get('{{ url("admin/penyusunan-rps/get-bahan-kajian") }}/' + kode, function(data) {
                if (data && data.length > 0) {
                    var html = '<ol class="mb-0 ps-3">';
                    data.forEach(function(item) {
                        html += '<li>' + item.topik + '</li>';
                    });
                    html += '</ol>';
                    container.html(html);
                } else {
                    container.html('<span class="text-muted small fst-italic">Belum ada data bahan kajian untuk matakuliah ini.</span>');
                }
            }).fail(function() {
                container.html('<span class="text-danger small fst-italic">Gagal memuat bahan kajian.</span>');
            });
        } else {
            container.html('<span class="text-muted small fst-italic">Pilih matakuliah terlebih dahulu untuk menampilkan daftar bahan kajian secara otomatis.</span>');
        }
    });
});
</script>
@endpush
@endsection
