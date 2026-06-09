<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Warung Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: #1B4332;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-card .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-card .brand i {
            font-size: 2.5rem;
            color: #1B4332;
        }
        .login-card .brand h4 {
            font-weight: 700;
            color: #1B4332;
            margin-top: 0.5rem;
        }
        .form-control-login {
            border: 2px solid #E5E0DB;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        .form-control-login:focus {
            border-color: #1B4332;
            box-shadow: 0 0 0 3px rgba(27,67,50,0.1);
        }
        .btn-login {
            background: #1B4332;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #2D6A4F; color: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <i class="bi bi-shop"></i>
            <h4>Warung Nusantara</h4>
            <p class="text-muted small mb-0">Panel Admin</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 small">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-600">Password Admin</label>
                <input type="password" name="password" class="form-control form-control-login"
                       placeholder="Masukkan password" required autofocus>
            </div>
            <button type="submit" class="btn btn-login mt-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <p class="text-center text-muted small mt-3 mb-0">Default: admin123</p>
    </div>
</body>
</html>