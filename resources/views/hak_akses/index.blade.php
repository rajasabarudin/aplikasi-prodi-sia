@extends('layouts.app')

@section('title', 'Pengaturan Hak Akses')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Pengaturan Hak Akses</h1>
                <p class="text-muted mb-0">Atur menu apa saja yang boleh diakses oleh level Jendral, Lecture, dan Mhs.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success d-print-none shadow-sm border-0 border-start border-success border-4">
                <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Matriks Hak Akses Menu</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('hak-akses.store') }}" method="POST">
                    @csrf

                    <div class="table-responsive rounded shadow-sm mb-4">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 25%;">Level / Role</th>
                                    @foreach ($menus as $key => $label)
                                        <th class="text-center" style="font-size: 0.75rem; padding: 12px 6px !important;">
                                            {{ $label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="fw-bold text-dark text-capitalize py-3">
                                            <i class="bi bi-person-badge-fill me-2 text-secondary"></i>{{ $role }}
                                        </td>
                                        @foreach ($menus as $menuKey => $menuLabel)
                                            <td class="text-center">
                                                <!-- Hidden input so that unchecked checkboxes still submit a '0' value -->
                                                <input type="hidden" name="permissions[{{ $role }}][{{ $menuKey }}]" value="0">
                                                <div class="form-check d-inline-block">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="permissions[{{ $role }}][{{ $menuKey }}]" 
                                                           value="1" 
                                                           style="width: 1.25rem; height: 1.25rem; cursor: pointer;"
                                                           {{ isset($activePermissions[$role][$menuKey]) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="fw-bold text-dark text-capitalize py-3">
                                        <i class="bi bi-crown-fill me-2 text-danger"></i>King <span class="text-muted small fw-normal">(Super Admin)</span>
                                    </td>
                                    @foreach ($menus as $menuKey => $menuLabel)
                                        <td class="text-center bg-light">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="checkbox" checked disabled 
                                                       style="width: 1.25rem; height: 1.25rem; background-color: #dc3545; border-color: #dc3545;">
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info border-0 bg-info bg-opacity-10 text-dark small d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2 fs-5 text-info"></i>
                        <div>
                            <strong>Catatan:</strong> Level <strong>King</strong> secara default memiliki akses penuh ke seluruh menu di aplikasi dan tidak dapat dikurangi hak aksesnya.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Hak Akses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
