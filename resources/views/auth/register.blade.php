<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SIKAP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .right-panel { flex: 1; background: white; display: flex; justify-content: center; align-items: center; padding: 40px; overflow-y: auto; }
        .register-form-container { width: 100%; max-width: 450px; animation: slideInRight 0.8s ease; }
        .register-form-container h2 { margin-bottom: 30px; color: #333; animation: fadeIn 1s ease 0.5s both; }
        .mb-3 { animation: fadeInUp 0.6s ease both; }
        .mb-3:nth-child(1) { animation-delay: 0.6s; }
        .mb-3:nth-child(2) { animation-delay: 0.7s; }
        .mb-3:nth-child(3) { animation-delay: 0.8s; }
        .mb-3:nth-child(4) { animation-delay: 0.9s; }
        .mb-3:nth-child(5) { animation-delay: 1s; }
        button { animation: fadeInUp 0.6s ease 1.1s both; }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); transform: translateY(-2px); transition: all 0.3s; }
        .btn-primary { background: linear-gradient(45deg, #667eea, #764ba2); border: none; padding: 12px; font-size: 16px; transition: all 0.3s; }
        .btn-primary:hover { background: linear-gradient(45deg, #764ba2, #667eea); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        @media (max-width: 768px) { .split-container { flex-direction: column; } .left-panel { padding: 30px; min-height: 30vh; } .left-panel h1 { font-size: 28px; } .left-panel img { width: 100px; } }
    </style>
</head>
<body>
<div class="split-container">
    <div class="left-panel">
        <video autoplay muted loop playsinline>
            <source src="{{ asset('videos/school-bg.mp4') }}" type="video/mp4">
        </video>
        <img src="{{ asset('images/Gemini_Generated_Image_ntkz8ontkz8ontkz.png') }}" alt="SIKAP Logo">
        <h1>SIKAP</h1>
        <p>Sistem Informasi Kesiswaan dan Prestasi - Bergabunglah untuk mengelola data siswa, pelanggaran, prestasi, dan bimbingan konseling</p>
    </div>
    <div class="right-panel">
        <div class="register-form-container">
            <h2>Buat Akun Baru</h2>
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="kesiswaan">Kesiswaan</option>
                        <option value="guru">Guru</option>
                        <option value="bk">Guru BK</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="siswa">Siswa</option>
                        <option value="ortu">Orang Tua</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>
            <div class="mt-3 text-center">
                <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
