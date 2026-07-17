@extends('layouts.app')

@section('title', 'Data Akun')

@section('content')
<div class="row">
    <!-- Kiri: Panel Statistik Akun -->
    <div class="col-lg-3 col-md-4 d-print-none mb-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header text-white py-3" style="background: #0f172a !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Akun</h5>
            </div>
            <div class="card-body">
                <!-- Total Akun -->
                <div class="mb-4 text-center py-3 stat-card-total">
                    <span class="text-white-50 small d-block mb-1">Total Akun</span>
                    <h2 class="fw-bold mb-0 text-white">{{ $totalAkun }}</h2>
                </div>

                <!-- Berdasarkan Level -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-success text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Berdasarkan Level</span>
                    </div>
                    <div class="ps-1">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">King</span>
                            <span class="badge bg-danger rounded-pill px-3">{{ $levelCounts['king'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Jendral</span>
                            <span class="badge bg-warning text-dark rounded-pill px-3">{{ $levelCounts['jendral'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Lecture</span>
                            <span class="badge bg-primary rounded-pill px-3">{{ $levelCounts['lecture'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                            <span class="text-dark fw-semibold">Mhs</span>
                            <span class="badge bg-info text-dark rounded-pill px-3">{{ $levelCounts['mhs'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Visualisasi Level -->
                <div class="mt-4 border-top pt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ef4444, #dc2626) !important;">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <span class="fw-bold text-dark">Visualisasi Level</span>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="akunPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanan: Tabel Data Akun -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Data Akun</h1>
                <p class="text-muted mb-0">Total: <strong>{{ $totalAkun }}</strong> akun terdaftar</p>
            </div>
            <div class="d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAkunModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Akun
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger d-print-none shadow-sm border-0 border-start border-danger border-4 mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success d-print-none shadow-sm border-0 border-start border-success border-4">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
            </div>
        @endif

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 8%;">No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="text-center" style="width: 15%;">Level</th>
                        <th class="text-center d-print-none" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($akuns as $a)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark">
                                <i class="bi bi-person-circle me-2 text-secondary"></i>{{ $a->username }}
                            </td>
                            <td>{{ $a->email }}</td>
                            <td class="text-center">
                                @if ($a->level === 'king')
                                    <span class="badge bg-danger px-3 py-2 text-uppercase shadow-sm" style="font-size: 10px; font-weight: 600; border-radius: 50px;">
                                        <i class="bi bi-crown-fill me-1"></i> King
                                    </span>
                                @elseif ($a->level === 'jendral')
                                    <span class="badge bg-warning text-dark px-3 py-2 text-uppercase shadow-sm" style="font-size: 10px; font-weight: 600; border-radius: 50px;">
                                        <i class="bi bi-shield-fill me-1"></i> Jendral
                                    </span>
                                @elseif ($a->level === 'lecture')
                                    <span class="badge bg-primary px-3 py-2 text-uppercase shadow-sm" style="font-size: 10px; font-weight: 600; border-radius: 50px;">
                                        <i class="bi bi-journal-bookmark-fill me-1"></i> Lecture
                                    </span>
                                @else
                                    <span class="badge bg-info text-dark px-3 py-2 text-uppercase shadow-sm" style="font-size: 10px; font-weight: 600; border-radius: 50px;">
                                        <i class="bi bi-people-fill me-1"></i> Mhs
                                    </span>
                                @endif
                            </td>
                            <td class="text-center d-print-none">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('akun.edit', $a->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('akun.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-people-fill display-6 d-block mb-2 text-secondary"></i>
                                Belum ada data akun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Akun -->
<div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('akun.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="tambahAkunModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Tambah Akun Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                            <input type="text" name="username" id="username" class="form-control border-start-0 @error('username') is-invalid @enderror" value="{{ old('username') }}" required placeholder="Masukkan username unik">
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-secondary"></i></span>
                            <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="contoh@domain.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-secondary"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" required placeholder="Minimal 6 karakter">
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Level -->
                    <div class="mb-3">
                        <label for="level" class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-secondary"></i></span>
                            <select name="level" id="level" class="form-select border-start-0 @error('level') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Level Akun</option>
                                <option value="king" {{ old('level') == 'king' ? 'selected' : '' }}>King</option>
                                <option value="jendral" {{ old('level') == 'jendral' ? 'selected' : '' }}>Jendral</option>
                                <option value="lecture" {{ old('level') == 'lecture' ? 'selected' : '' }}>Lecture</option>
                                <option value="mhs" {{ old('level') == 'mhs' ? 'selected' : '' }}>Mhs</option>
                            </select>
                        </div>
                        @error('level')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div class="mb-3">
                        <label for="foto" class="form-label fw-semibold">Foto Profil</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-camera text-secondary"></i></span>
                            <input type="file" name="foto" id="foto" class="form-control border-start-0 @error('foto') is-invalid @enderror" accept="image/*">
                        </div>
                        <div class="form-text text-muted">Format gambar: jpeg, png, jpg, gif (Maks. 2MB).</div>
                        @error('foto')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('tambahAkunModal'));
            myModal.show();
        @endif

        var ctx = document.getElementById('akunPieChart').getContext('2d');
        
        var labels = ['King', 'Jendral', 'Lecture', 'Mhs'];
        var data = [
            {{ $levelCounts['king'] }},
            {{ $levelCounts['jendral'] }},
            {{ $levelCounts['lecture'] }},
            {{ $levelCounts['mhs'] }}
        ];
        
        var colors = ['#dc3545', '#ffc107', '#0d6efd', '#0dcaf0']; // Red for King, Yellow for Jendral, Blue for Lecture, Cyan for Mhs
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: 'rgba(255, 255, 255, 0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                return ' ' + label + ': ' + value + ' Akun';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
