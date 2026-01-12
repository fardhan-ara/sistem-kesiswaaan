<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIKAP - Sistem Informasi Kesiswaan dan Prestasi</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        .welcome-container { width: 100%; max-width: 450px; text-align: center; animation: slideInRight 0.8s ease; }
        .welcome-container h2 { margin-bottom: 20px; color: #333; font-size: 32px; animation: fadeIn 1s ease 0.5s both; }
        .welcome-container p { color: #666; margin-bottom: 40px; font-size: 16px; animation: fadeIn 1s ease 0.6s both; }
        .btn-action { width: 100%; padding: 15px; font-size: 18px; border-radius: 8px; margin-bottom: 15px; transition: all 0.3s; animation: fadeInUp 0.6s ease both; }
        .btn-login { background: linear-gradient(45deg, #667eea, #764ba2); border: none; color: white; animation-delay: 0.7s; }
        .btn-login:hover { background: linear-gradient(45deg, #764ba2, #667eea); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); color: white; }
        .btn-register { background: white; border: 2px solid #667eea; color: #667eea; animation-delay: 0.8s; }
        .btn-register:hover { background: #667eea; color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
        .features { margin-top: 40px; text-align: left; animation: fadeIn 1s ease 0.9s both; }
        .features h4 { color: #333; margin-bottom: 15px; font-size: 18px; }
        .feature-item { display: flex; align-items: center; margin-bottom: 12px; color: #666; }
        .feature-item i { color: #667eea; margin-right: 10px; font-size: 18px; }
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
        <div class="welcome-container">
            <h2>Selamat Datang</h2>
            <p>Silakan pilih untuk masuk ke akun Anda atau daftar akun baru</p>
            
            <button type="button" class="btn btn-action btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
            
            <button type="button" class="btn btn-action btn-register" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="fas fa-user-plus mr-2"></i> Daftar
            </button>

            <div class="features">
                <h4>Fitur Utama:</h4>
                <div class="feature-item">
                    <i class="fas fa-users"></i>
                    <span>Manajemen Data Siswa</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Pencatatan Pelanggaran</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-trophy"></i>
                    <span>Pencatatan Prestasi</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard & Laporan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="loginModalLabel"><i class="fas fa-sign-in-alt"></i> Login ke SIKAP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf
                <div class="modal-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('error') }}
                    </div>
                    @endif
                    @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('warning') }}
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(45deg, #667eea, #764ba2); border: none;">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="registerModalLabel"><i class="fas fa-user-plus"></i> Daftar Akun Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Silakan pilih jenis akun yang ingin Anda daftarkan:</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('signup', ['role' => 'siswa']) }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-user-graduate"></i> Daftar sebagai Siswa
                    </a>
                    <a href="{{ route('signup', ['role' => 'guru']) }}" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-chalkboard-teacher"></i> Daftar sebagai Guru
                    </a>
                    <a href="{{ route('signup', ['role' => 'kesiswaan']) }}" class="btn btn-outline-info btn-lg">
                        <i class="fas fa-user-tie"></i> Daftar sebagai Kesiswaan
                    </a>
                    <a href="{{ route('signup', ['role' => 'bk']) }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-comments"></i> Daftar sebagai Guru BK
                    </a>
                    <a href="{{ route('signup', ['role' => 'ortu']) }}" class="btn btn-outline-warning btn-lg">
                        <i class="fas fa-users"></i> Daftar sebagai Orang Tua
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto show modal if there's message from previous action
@if(session('error') || session('warning'))
    $(document).ready(function() {
        $('#loginModal').modal('show');
    });
@endif

@if(session('success'))
    $(document).ready(function() {
        // Show success message on page, not in modal
        const alertHtml = `
            <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ session('success') }}
            </div>
        `;
        $('body').append(alertHtml);
        setTimeout(function() {
            $('.alert-success').fadeOut();
        }, 5000);
    });
@endif
</script>
</body>
</html>
