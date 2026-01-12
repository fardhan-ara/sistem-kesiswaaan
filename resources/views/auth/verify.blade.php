<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - Sistem Kesiswaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-3">
                <i class="fas fa-envelope-open-text fa-4x text-warning"></i>
            </div>
            <h4 class="text-center">Verifikasi Email Anda</h4>
            <p class="text-center">Sebelum melanjutkan, silakan cek email Anda untuk link verifikasi.</p>
            
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-block">Kirim Ulang Email Verifikasi</button>
            </form>

            <div class="text-center mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>