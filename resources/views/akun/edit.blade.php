@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="mb-0 fw-bold text-dark">Edit Akun</h1>
                <p class="text-muted mb-0">Ubah informasi akun di bawah ini.</p>
            </div>
            <a href="{{ route('akun.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Formulir Edit Akun</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('akun.update', $akun->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                            <input type="text" name="username" id="username" class="form-control border-start-0 @error('username') is-invalid @enderror" value="{{ old('username', $akun->username) }}" required placeholder="Masukkan username unik">
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
                            <input type="email" name="email" id="email" class="form-control border-start-0 @error('email') is-invalid @enderror" value="{{ old('email', $akun->email) }}" required placeholder="contoh@domain.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-secondary"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>
                        <div class="form-text text-muted">Biarkan kosong jika Anda tidak ingin mengubah password akun ini.</div>
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
                                 <option value="" disabled>Pilih Level Akun</option>
                                 <option value="king" {{ old('level', $akun->level) == 'king' ? 'selected' : '' }}>King</option>
                                 <option value="jendral" {{ old('level', $akun->level) == 'jendral' ? 'selected' : '' }}>Jendral</option>
                                 <option value="lecture" {{ old('level', $akun->level) == 'lecture' ? 'selected' : '' }}>Lecture</option>
                                 <option value="mhs" {{ old('level', $akun->level) == 'mhs' ? 'selected' : '' }}>Mhs</option>
                             </select>
                        </div>
                        @error('level')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div class="mb-4">
                        <label for="foto" class="form-label fw-semibold">Foto Profil</label>
                        @if ($akun->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $akun->foto) }}" alt="Preview" class="img-thumbnail" style="max-height: 80px; object-fit: cover;">
                            </div>
                        @endif
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-camera text-secondary"></i></span>
                            <input type="file" name="foto" id="foto" class="form-control border-start-0 @error('foto') is-invalid @enderror" accept="image/*">
                        </div>
                        <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: jpeg, png, jpg, gif (Maks. 2MB).</div>
                        @error('foto')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('akun.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
