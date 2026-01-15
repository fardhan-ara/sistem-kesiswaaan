<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - SIKAP</title>
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
        .right-panel { flex: 1; background: white; display: flex; justify-content: center; align-items: center; padding: 40px; overflow-y: auto; }
        .register-form-container { width: 100%; max-width: 400px; animation: slideInRight 0.8s ease; }
        .register-form-container h2 { margin-bottom: 30px; color: #333; animation: fadeIn 1s ease 0.5s both; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
        .btn-primary { background: linear-gradient(45deg, #667eea, #764ba2); border: none; padding: 12px; font-size: 16px; transition: all 0.3s; }
        .btn-primary:hover { background: linear-gradient(45deg, #764ba2, #667eea); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
        .is-invalid { border-color: #dc3545; }
        .invalid-feedback { display: block; width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545; }
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
        <div class="register-form-container">
            <h2>Daftar Akun Baru</h2>

            @if(isset($isLoggedIn) && $isLoggedIn)
            <div class="alert alert-warning">
                <strong>Perhatian!</strong> Anda sudah login sebagai <strong>{{ $currentUser->nama }}</strong> ({{ $currentUser->email }}).
                <br>Jika Anda ingin mendaftar akun baru, silakan <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-register').submit();"><strong>logout terlebih dahulu</strong></a>.
                <form id="logout-form-register" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('signup.post') }}" method="post" id="signupForm" @if(isset($isLoggedIn) && $isLoggedIn) style="pointer-events: none; opacity: 0.6;" @endif>
                @csrf
                
                <input type="hidden" name="role" value="{{ request('role', 'siswa') }}">
                
                <div class="mb-3">
                    <label class="form-label">Mendaftar sebagai: <strong>{{ ucfirst(str_replace('_', ' ', request('role', 'siswa'))) }}</strong></label>
                </div>

                <div class="mb-3" id="mataPelajaranField" style="display:none;">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika">
                </div>

                <div class="mb-3" id="nisField" style="display:none;">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Siswa">
                </div>

                <div class="mb-3" id="kelasField" style="display:none;">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-control">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="tahunAjaranField" style="display:none;">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control">
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ ucfirst($ta->semester) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="jenisKelaminField" style="display:none;">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3" id="namaAnakField" style="display:none;">
                    <label class="form-label">Nama Anak (Siswa)</label>
                    <input type="text" name="nama_anak" class="form-control @error('nama_anak') is-invalid @enderror" placeholder="Nama lengkap anak" value="{{ old('nama_anak') }}">
                    @error('nama_anak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Tidak harus sama persis huruf besar/kecilnya</small>
                </div>

                <div class="mb-3" id="nisAnakField" style="display:none;">
                    <label class="form-label">NIS Anak <span class="text-danger">*</span></label>
                    <input type="text" name="nis_anak" class="form-control @error('nis_anak') is-invalid @enderror" placeholder="Nomor Induk Siswa anak" value="{{ old('nis_anak') }}">
                    @error('nis_anak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">NIS harus benar dan sesuai data siswa</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email" value="{{ old('email') }}" autocomplete="new-email" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" autocomplete="new-password" required minlength="6">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" autocomplete="new-password" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary w-100" @if(isset($isLoggedIn) && $isLoggedIn) disabled @endif>Daftar</button>
            </form>
            
            <div class="mt-3 text-center">
                <span>Sudah punya akun? </span>
                <a href="{{ route('landing') }}">Masuk</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
$(document).ready(function() {
    const role = '{{ request("role", "siswa") }}';
    
    $('#mataPelajaranField, #nisField, #kelasField, #tahunAjaranField, #jenisKelaminField, #namaAnakField, #nisAnakField').hide();
    
    if(role === 'guru') {
        $('#mataPelajaranField, #jenisKelaminField').show();
    } else if(role === 'siswa') {
        $('#nisField, #kelasField, #tahunAjaranField, #jenisKelaminField').show();
    } else if(role === 'ortu') {
        $('#namaAnakField, #nisAnakField').show();
    }
});
</script>
</body>
</html>
