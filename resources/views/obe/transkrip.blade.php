@extends('layouts.app')

@section('title', 'Transkrip CPL Individu Mahasiswa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-person me-2 text-warning"></i>Transkrip Portofolio CPL Mahasiswa</h5>
                <a href="{{ route('obe.index') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left me-1"></i>Kembali ke Portal</a>
            </div>
            <div class="card-body">
                <p class="text-muted small">Cari dan pilih nama atau NIM mahasiswa untuk memvisualisasikan capaian nilai CPL secara individual.</p>
                
                <div class="row g-3 align-items-end mb-4">
                    <div class="col-md-8 position-relative">
                        <label class="form-label fw-semibold">Cari Mahasiswa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="student_search_input" class="form-control border-start-0" placeholder="Ketik NIM atau Nama mahasiswa..." autocomplete="off">
                        </div>
                        <input type="hidden" id="select_student" name="mahasiswa_id">
                        
                        <!-- Autocomplete Suggestions List -->
                        <div id="autocomplete_list" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 1050; max-height: 220px; overflow-y: auto; margin-top: 2px;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btn_search" class="btn btn-primary w-100 py-2 fw-semibold" style="border-radius: 8px;">
                            <i class="bi bi-graph-up me-2"></i>Tampilkan Grafik
                        </button>
                    </div>
                </div>

                <!-- Loader -->
                <div id="loader" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2">Memuat capaian CPL mahasiswa...</p>
                </div>

                <!-- Result Layout -->
                <div id="result_container" class="d-none mt-4">
                    <div class="row border-top pt-4">
                        <!-- Student Profile Info -->
                        <div class="col-md-12 mb-4">
                            <div class="bg-light p-3 rounded border">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>NIM:</strong> <span id="student_nim" class="text-primary font-monospace"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Nama:</strong> <span id="student_nama" class="fw-bold"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Kelas:</strong> <span id="student_kelas"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Radar Chart -->
                        <div class="col-md-6 mb-3">
                            <div class="card border shadow-none p-3 h-100">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-radar me-2 text-primary"></i>Radar Capaian CPL</h6>
                                <div style="height: 300px; position: relative;">
                                    <canvas id="studentCplChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Scores Table -->
                        <div class="col-md-6 mb-3">
                            <div class="card border shadow-none p-0 h-100">
                                <div class="card-header bg-light py-2 px-3 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-list-task me-2 text-primary"></i>Rincian Nilai per Kompetensi Lulusan</h6>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0" style="font-size: 0.9rem;">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="text-center" style="width: 25%;">Kode CPL</th>
                                                <th class="text-center" style="width: 35%;">Nilai Mahasiswa</th>
                                                <th class="text-center" style="width: 40%;">Status Penguasaan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="scores_tbody">
                                            <!-- Dynamically populated -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    var studentChart = null;
    var studentsList = {!! json_encode($students) !!};
    
    // Live Search Autocomplete
    $('#student_search_input').on('input', function() {
        var query = $(this).val().toLowerCase().trim();
        var list = $('#autocomplete_list');
        list.empty().addClass('d-none');
        $('#select_student').val(''); // Clear ID if user modifies text
        
        if (query.length >= 2) {
            var matches = studentsList.filter(function(student) {
                return student.nim.toLowerCase().includes(query) || student.nama.toLowerCase().includes(query);
            });
            
            if (matches.length > 0) {
                matches.forEach(function(student) {
                    var item = $('<button type="button" class="list-group-item list-group-item-action text-start py-2 px-3">' +
                        '<strong>' + student.nim + '</strong> - ' + student.nama + ' <span class="badge bg-secondary-subtle text-secondary float-end">Kelas: ' + (student.kelas || '-') + '</span>' +
                        '</button>');
                    
                    item.click(function() {
                        $('#student_search_input').val(student.nim + ' - ' + student.nama + ' (' + (student.kelas || '-') + ')');
                        $('#select_student').val(student.id);
                        list.empty().addClass('d-none');
                    });
                    
                    list.append(item);
                });
                list.removeClass('d-none');
            }
        }
    });

    // Close autocomplete when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('#student_search_input, #autocomplete_list').length) {
            $('#autocomplete_list').addClass('d-none');
        }
    });

    $('#btn_search').click(function() {
        var studentId = $('#select_student').val();
        if (!studentId) {
            alert('Silakan pilih mahasiswa dari hasil pencarian terlebih dahulu.');
            return;
        }

        $('#loader').removeClass('d-none');
        $('#result_container').addClass('d-none');

        $.get('{{ url("admin/obe-portal/get-student-cpl") }}', {
            mahasiswa_id: studentId
        }, function(data) {
            $('#loader').addClass('d-none');
            
            // Fill profile
            $('#student_nim').text(data.nim);
            $('#student_nama').text(data.nama);
            $('#student_kelas').text(data.kelas);

            // Populate table
            var tbody = $('#scores_tbody');
            tbody.empty();
            
            data.labels.forEach(function(label, index) {
                var score = data.scores[index];
                var statusClass = 'bg-danger';
                var statusText = 'Kurang (< 60)';
                
                if (score >= 80) {
                    statusClass = 'bg-success';
                    statusText = 'Sangat Baik (>= 80)';
                } else if (score >= 70) {
                    statusClass = 'bg-primary';
                    statusText = 'Baik (>= 70)';
                } else if (score >= 60) {
                    statusClass = 'bg-warning text-dark';
                    statusText = 'Cukup (>= 60)';
                }

                tbody.append(
                    '<tr>' +
                    '<td class="text-center fw-bold text-primary">' + label + '</td>' +
                    '<td class="text-center fw-bold">' + score + '</td>' +
                    '<td class="text-center"><span class="badge ' + statusClass + '" style="border-radius: 30px; font-size: 0.75rem;">' + statusText + '</span></td>' +
                    '</tr>'
                );
            });

            $('#result_container').removeClass('d-none');

            // Render Chart
            var ctx = document.getElementById('studentCplChart').getContext('2d');
            
            if (studentChart) {
                studentChart.destroy();
            }

            studentChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Nilai CPL Mahasiswa',
                        data: data.scores,
                        backgroundColor: 'rgba(251, 191, 36, 0.2)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            suggestedMin: 0,
                            suggestedMax: 100
                        }
                    }
                }
            });

        }).fail(function() {
            $('#loader').addClass('d-none');
            alert('Gagal mengambil data CPL mahasiswa.');
        });
    });
});
</script>
@endpush
@endsection
