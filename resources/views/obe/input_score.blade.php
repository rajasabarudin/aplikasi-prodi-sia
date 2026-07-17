@extends('layouts.app')

@section('title', 'Input Nilai CPMK Mahasiswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-diff me-2 text-warning"></i>Input Nilai CPMK (CPL Attainment)</h5>
                <a href="{{ route('obe.index') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left me-1"></i>Kembali ke Portal</a>
            </div>
            <div class="card-body">
                <p class="text-muted small">Silakan pilih mata kuliah (RPS), kelas, dan CPMK tujuan. Setelah data dipilih, daftar mahasiswa kelas tersebut akan tampil otomatis.</p>
                
                <form action="{{ route('obe.store-score') }}" method="POST" id="scoreForm">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pilih Mata Kuliah (RPS) <span class="text-danger">*</span></label>
                            <select name="rps_id" id="select_rps" class="form-select" required>
                                <option value="">-- Pilih MK --</option>
                                @foreach($rpsList as $rps)
                                <option value="{{ $rps->id }}" data-kode="{{ $rps->kode_matakuliah }}">{{ $rps->matakuliah?->nama_matakuliah }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pilih CPMK <span class="text-danger">*</span></label>
                            <select name="cpmk_id" id="select_cpmk" class="form-select" required disabled>
                                <option value="">-- Pilih CPMK --</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pilih Kelas <span class="text-danger">*</span></label>
                            <select name="kelas_id" id="select_kelas" class="form-select" required disabled>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Loader -->
                    <div id="loader" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted small mt-2">Memuat daftar mahasiswa...</p>
                    </div>

                    <!-- Student List Table -->
                    <div id="students_container" class="d-none">
                        <h6 class="fw-bold mb-3 text-primary border-bottom pb-2"><i class="bi bi-people-fill me-2"></i>Daftar Nilai Mahasiswa</h6>
                        <div class="table-responsive mb-4" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th class="text-center" style="width: 8%;">No</th>
                                        <th style="width: 25%;">NIM</th>
                                        <th style="width: 47%;">Nama Lengkap</th>
                                        <th class="text-center" style="width: 20%;">Nilai CPMK (0-100)</th>
                                    </tr>
                                </thead>
                                <tbody id="students_tbody">
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('obe.index') }}" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-success px-5 fw-semibold"><i class="bi bi-save me-2"></i>Simpan Nilai</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // 1. When RPS changes, load CPMKs
    $('#select_rps').change(function() {
        var rpsId = $(this).val();
        var cpmkSelect = $('#select_cpmk');
        
        cpmkSelect.html('<option value="">-- Pilih CPMK --</option>').attr('disabled', true);
        $('#select_kelas').val('').attr('disabled', true);
        $('#students_container').addClass('d-none');
        
        if (rpsId) {
            $.get('{{ url("admin/obe-portal/get-cpmks") }}/' + rpsId, function(data) {
                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        cpmkSelect.append('<option value="' + item.id + '">' + item.kode_cpmk + ' - ' + item.deskripsi_cpmk.substring(0, 50) + '...</option>');
                    });
                    cpmkSelect.removeAttr('disabled');
                } else {
                    cpmkSelect.html('<option value="">Belum ada CPMK untuk MK ini</option>');
                }
            }).fail(function() {
                cpmkSelect.html('<option value="">Gagal memuat CPMK</option>');
            });
        }
    });

    // 2. When CPMK changes, enable Kelas
    $('#select_cpmk').change(function() {
        var cpmkId = $(this).val();
        var kelasSelect = $('#select_kelas');
        
        kelasSelect.val('').attr('disabled', !cpmkId);
        $('#students_container').addClass('d-none');
    });

    // 3. When Kelas changes, load students & scores
    $('#select_kelas').change(function() {
        loadStudents();
    });

    function loadStudents() {
        var rpsId = $('#select_rps').val();
        var cpmkId = $('#select_cpmk').val();
        var kelasId = $('#select_kelas').val();
        
        var container = $('#students_container');
        var tbody = $('#students_tbody');
        var loader = $('#loader');
        
        container.addClass('d-none');
        tbody.empty();
        
        if (rpsId && cpmkId && kelasId) {
            loader.removeClass('d-none');
            
            $.post('{{ url("admin/obe-portal/get-students") }}', {
                _token: '{{ csrf_token() }}',
                rps_id: rpsId,
                cpmk_id: cpmkId,
                kelas_id: kelasId
            }, function(data) {
                loader.addClass('d-none');
                
                if (data && data.length > 0) {
                    data.forEach(function(student, index) {
                        var val = student.nilai !== null ? student.nilai : '';
                        tbody.append(
                            '<tr>' +
                            '<td class="text-center">' + (index + 1) + '</td>' +
                            '<td><code>' + student.nim + '</code></td>' +
                            '<td>' + student.nama + '</td>' +
                            '<td class="text-center">' +
                            '<input type="number" step="0.01" min="0" max="100" name="scores[' + student.id + ']" class="form-control text-center mx-auto" style="max-width: 100px;" value="' + val + '" placeholder="0-100" required>' +
                            '</td>' +
                            '</tr>'
                        );
                    });
                    container.removeClass('d-none');
                } else {
                    alert('Tidak ada mahasiswa terdaftar di kelas yang dipilih.');
                }
            }).fail(function() {
                loader.addClass('d-none');
                alert('Gagal memuat data mahasiswa.');
            });
        }
    }
});
</script>
@endpush
@endsection
