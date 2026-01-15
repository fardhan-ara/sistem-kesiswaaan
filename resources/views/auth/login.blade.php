<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIKAP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        body { margin: 0; font-family: 'Source Sans Pro', sans-serif; height: 100vh; overflow: hidden; }
        .split-container { display: flex; height: 100vh; }
        .left-panel { flex: 1; position: relative; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 60px; overflow: hidden; }
        .left-panel::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(102, 126, 234, 0.7); z-index: 1; }
        .left-panel video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; }
        .left-panel > * { position: relative; z-index: 2; }
        .left-panel img { width: 150px; margin-bottom: 30px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3)); animation: fadeInDown 1s ease; }
        .left-panel h1 { font-size: 42px; font-weight: bold; margin-bottom: 15px; animation: fadeInUp 1s ease 0.2s both; }
        .left-panel p { font-size: 18px; text-align: center; line-height: 1.6; max-width: 500px; animation: fadeInUp 1s ease 0.4s both; }
        .right-panel { flex: 1; background: white; display: flex; justify-content: center; align-items: center; padding: 40px; }
        .login-form-container { width: 100%; max-width: 400px; animation: slideInRight 0.8s ease; }
        .login-form-container h2 { margin-bottom: 30px; color: #333; animation: fadeIn 1s ease 0.5s both; }
        .form-control, .form-check, button { animation: fadeInUp 0.6s ease both; }
        .form-control:nth-child(1) { animation-delay: 0.6s; }
        .form-control:nth-child(2) { animation-delay: 0.7s; }
        .form-check { animation-delay: 0.8s; }
        button { animation-delay: 0.9s; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); transform: translateY(-2px); transition: all 0.3s; }
        .btn-primary { background: linear-gradient(45deg, #667eea, #764ba2); border: none; padding: 12px; font-size: 16px; transition: all 0.3s; }
        .btn-primary:hover { background: linear-gradient(45deg, #764ba2, #667eea); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        @media (max-width: 768px) { .split-container { flex-direction: column; } .left-panel { padding: 30px; } .left-panel h1 { font-size: 28px; } .left-panel img { width: 100px; } }
    </style>
</head>
<body>
<div class="split-container">
    <div class="left-panel">
        <video autoplay muted loop playsinline>
            <source src="{{ asset('videos/school-bg.mp4') }}" type="video/mp4">
        </video>
        <img src="{{ asset('images/logo.png') }}" alt="SIKAP Logo">
        <h1>SIKAP</h1>
        <p>Sistem Informasi Kesiswaan dan Prestasi - Mengelola data siswa, pelanggaran, prestasi, dan bimbingan konseling secara terintegrasi</p>
    </div>
    <div class="right-panel">
        <div class="login-form-container">
            <h2>Masuk ke Akun Anda</h2>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            
            <form action="{{ route('login.post') }}" method="post" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="" autocomplete="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="" autocomplete="current-password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
            
            <div class="mt-3 text-center">
                <a href="#" id="forgotPasswordLink" data-url="{{ route('password.email') }}">Lupa password?</a>
            </div>
            <div class="mt-2 text-center">
                <span>Belum punya akun? </span>
                <div class="dropdown d-inline">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Daftar</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('signup', ['role' => 'kesiswaan']) }}">Staff Kesiswaan</a>
                        <a class="dropdown-item" href="{{ route('signup', ['role' => 'guru']) }}">Guru</a>
                        <a class="dropdown-item" href="{{ route('signup', ['role' => 'wali_kelas']) }}">Wali Kelas</a>
                        <a class="dropdown-item" href="{{ route('signup', ['role' => 'siswa']) }}">Siswa</a>
                        <a class="dropdown-item" href="{{ route('signup', ['role' => 'ortu']) }}">Orang Tua</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- Memuat JS statis tanpa Vite -->
    <script src="{{ asset('js/login.js') }}"></script>

    <!-- Modal: Forgot Password -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="fpAlert"></div>
                    <form id="forgotPasswordForm">
                        <div class="form-group">
                            <label for="fpEmail">Masukkan email terdaftar</label>
                            <input type="email" class="form-control" id="fpEmail" name="email" placeholder="email@domain" required>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </body>
    </html>
