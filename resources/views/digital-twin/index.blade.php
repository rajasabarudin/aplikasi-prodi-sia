@extends('layouts.app')

@section('title', 'Digital Twin - Kebun Sawit')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">IoT Digital Twin Dashboard</h1>
            <p class="text-muted">Dataset Penelitian Perkebunan Sawit</p>
        </div>
        <div class="col-md-6 text-right d-flex justify-content-end align-items-center gap-2">
            <a href="{{ route('digital-twin.export') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export CSV
            </a>
            <form action="{{ route('digital-twin.sync') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-sync fa-sm text-white-50"></i> Tarik Data Sekarang
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Kelembaban Tanah -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4e73df;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Kelembapan Tanah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dataset->first()->kelembaban_tanah_persen ?? 0 }} %
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suhu Tanah -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Suhu Tanah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dataset->first()->suhu_tanah_celcius ?? 0 }} &deg;C
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-seedling fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suhu Udara -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Suhu Udara</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dataset->first()->suhu_udara_celcius ?? 0 }} &deg;C
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-temperature-high fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelembaban Udara -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kelembapan Udara</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dataset->first()->kelembaban_udara_persen ?? 0 }} %
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cloud fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Monitoring Gallery -->
    @if(isset($photos) && count($photos) > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Galeri Pemantauan Visual (IoT Camera)</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach(array_slice($photos, 0, 4) as $photo)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="{{ $photo['url_foto'] }}" class="card-img-top" alt="Pemantauan Sawit" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-3">
                            <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-camera me-1"></i> {{ $photo['device_id'] }}</h6>
                            <p class="small text-muted mb-2"><i class="fas fa-clock me-1"></i> {{ $photo['waktu'] }}</p>
                            <span class="badge badge-info">{{ $photo['status_cuaca'] }}</span>
                            <div class="mt-2 small text-muted">
                                <div>Kecerahan: {{ $photo['kecerahan'] }}</div>
                                <div>Kontras: {{ $photo['kontras'] }}</div>
                                <div>Saturasi: {{ $photo['saturasi'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if(count($photos) > 4)
            <div class="text-center mt-3">
                <button type="button" class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRekapFoto">
                    <i class="fas fa-images"></i> Lihat Rekap Semua Foto ({{ count($photos) }})
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Rekap Foto -->
    <div class="modal fade" id="modalRekapFoto" tabindex="-1" role="dialog" aria-labelledby="modalRekapFotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalRekapFotoLabel">Rekap Pemantauan Visual Keseluruhan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    @forelse($photosByDate as $date => $dailyPhotos)
                    <h6 class="border-bottom pb-2 mb-3 {{ $loop->first ? '' : 'mt-4' }} text-primary font-weight-bold">
                        <i class="fas fa-calendar-day me-1"></i> {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </h6>
                    <div class="row">
                        @foreach($dailyPhotos as $photo)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <a href="{{ $photo['url_foto'] }}" target="_blank">
                                    <img src="{{ $photo['url_foto'] }}" class="card-img-top" alt="Pemantauan Sawit" style="height: 150px; object-fit: cover;">
                                </a>
                                <div class="card-body p-2 text-center">
                                    <p class="small text-muted mb-1"><i class="fas fa-clock me-1"></i> {{ date('H:i', strtotime($photo['waktu'])) }} WIB</p>
                                    <span class="badge badge-info mb-2">{{ $photo['status_cuaca'] }}</span>
                                    <div>
                                        <a href="{{ $photo['url_foto'] }}" download="Foto_{{ $photo['device_id'] }}_{{ date('Ymd_His', strtotime($photo['waktu'])) }}.jpg" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-download"></i> Unduh Foto
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        Belum ada data foto.
                    </div>
                    @endforelse
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Histori Dataset (100 Data Terakhir)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Waktu (WIB)</th>
                            <th>Device ID</th>
                            <th>Suhu Udara (&deg;C)</th>
                            <th>Kelembapan Udara (%)</th>
                            <th>Suhu Tanah (&deg;C)</th>
                            <th>Kelembapan Tanah (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataset as $data)
                        <tr>
                            <td>{{ $data->waktu }}</td>
                            <td>{{ $data->device_id }}</td>
                            <td>{{ $data->suhu_udara_celcius ?? '-' }}</td>
                            <td>{{ $data->kelembaban_udara_persen ?? '-' }}</td>
                            <td>{{ $data->suhu_tanah_celcius ?? '-' }}</td>
                            <td>{{ $data->kelembaban_tanah_persen ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada dataset. Silakan klik tombol "Tarik Data Sekarang".</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
