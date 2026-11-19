<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – TitikJual POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            border: 0;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-header {
            background: linear-gradient(135deg, #1e3a5f, #0d2137);
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="login-header">
            <i class="bi bi-shop text-warning fs-1"></i>
            <h4 class="text-white mt-2 mb-0">TitikJual POS</h4>
            <p class="text-white-50 small">Point of Sale System</p>
        </div>
        <div class="card-body p-4">
            <h5 class="mb-4 text-center text-muted">Masuk ke Akun Anda</h5>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" value="{{ old('username') }}"
                               class="form-control @error('username') is-invalid @enderror"
                               placeholder="Masukkan username" autofocus required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

