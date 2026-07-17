<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIA Prodi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .logo-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 3.5rem;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.75rem;
            margin-top: 10px;
        }

        .login-subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-right: none !important;
            color: #94a3b8 !important;
            border-top-left-radius: 12px !important;
            border-bottom-left-radius: 12px !important;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-left: none !important;
            color: #ffffff !important;
            border-top-right-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            padding: 12px !important;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.2) !important;
            color: #ffffff !important;
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .form-check-label {
            color: #cbd5e1;
            font-size: 0.85rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
            border: none !important;
            color: #ffffff !important;
            padding: 12px !important;
            border-radius: 12px !important;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3) !important;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5) !important;
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .back-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 20px;
        }

        .back-link:hover {
            color: #ffffff;
        }

        .back-link i {
            transition: transform 0.2s;
        }

        .back-link:hover i {
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <div class="login-card shadow">
        <div class="logo-area">
            <i class="bi bi-mortarboard-fill logo-icon"></i>
            <h2 class="login-title">SIA Prodi</h2>
            <p class="login-subtitle">Sistem Informasi Akademik Program Studi</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success small mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Login (Username / Email) -->
            <div class="mb-3">
                <label for="login" class="form-label">Username atau Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="login" id="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required placeholder="Masukkan username atau email" autofocus>
                </div>
                @error('login')
                    <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password Anda">
                </div>
            </div>

            <!-- Remember Me -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>
        </form>

        <a href="{{ route('welcome') }}" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
