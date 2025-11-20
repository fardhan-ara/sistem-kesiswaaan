<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Kesiswaan</title>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Memuat CSS statis tanpa Vite -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
        <a href="#"><b>Sistem</b> Kesiswaan</a>
    </div>
    
    <div class="card">
        <div class="card-body login-card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="login-box-msg mb-0">Pilih peran Anda untuk memvalidasi akun, lalu login</p>
                <small class="text-muted">Pilih 1 role sebelum login</small>
            </div>

            <div class="roles-grid" id="rolesGrid">
                <div class="role-card" data-role="admin">
                    <div>
                        <div class="role-title"><i class="fas fa-user-shield role-icon"></i> Administrator</div>
                        <div class="role-features">Manajemen pengguna, konfigurasi sistem, dan laporan lengkap.</div>
                    </div>
                </div>

                <div class="role-card" data-role="guru">
                    <div>
                        <div class="role-title"><i class="fas fa-chalkboard-teacher role-icon"></i> Guru</div>
                        <div class="role-features">Input nilai, hadir, catatan pelanggaran, dan pengelolaan kelas.</div>
                    </div>
                </div>

                <div class="role-card" data-role="bk">
                    <div>
                        <div class="role-title"><i class="fas fa-hands-helping role-icon"></i> BK</div>
                        <div class="role-features">Menu & Fitur Utama: Data Siswa, Riwayat Konseling, Jadwal Konseling, Catatan & Laporan.</div>
                    </div>
                </div>

                <div class="role-card" data-role="verifikator">
                    <div>
                        <div class="role-title"><i class="fas fa-user-check role-icon"></i> Verifikator</div>
                        <div class="role-features">Verifikasi pengajuan, validasi data, dan proses approval.</div>
                    </div>
                </div>

                <div class="role-card" data-role="walikelas">
                    <div>
                        <div class="role-title"><i class="fas fa-user-tie role-icon"></i> Wali Kelas</div>
                        <div class="role-features">Pemantauan kelas, komunikasi wali, dan peringatan siswa.</div>
                    </div>
                </div>

                <div class="role-card" data-role="siswa">
                    <div>
                        <div class="role-title"><i class="fas fa-user role-icon"></i> Siswa</div>
                        <div class="role-features">Melihat nilai, pelanggaran, prestasi, dan informasi pribadi.</div>
                    </div>
                </div>

                <div class="role-card" data-role="ortu">
                    <div>
                        <div class="role-title"><i class="fas fa-user-friends role-icon"></i> Orang Tua</div>
                        <div class="role-features">Melihat data anak, nilai, pelanggaran, dan informasi sekolah anak.</div>
                    </div>
                </div>

                <div class="role-card" data-role="wali_guru">
                    <div>
                        <div class="role-title"><i class="fas fa-user-shield role-icon"></i> Wali Guru</div>
                        <div class="role-features">Fitur serupa Guru: memantau kelas dan memberikan catatan, namun dengan akses berbeda.</div>
                    </div>
                </div>
            </div>

            <div class="login-area">
                <p class="login-box-msg">Silakan masukkan kredensial Anda untuk melanjutkan</p>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="post" id="loginForm">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="">
                
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </div>
            </form>

            <div class="mt-2 text-right">
                <a href="#" id="forgotPasswordLink" data-url="{{ route('password.email') }}" data-toggle="modal" data-target="#forgotPasswordModal" onclick="try{ $('#forgotPasswordModal').modal('show'); }catch(e){}; return false;">Lupa password?</a>
            </div>

            <div class="mt-3">
                <small class="text-muted">Role terpilih: <strong id="roleName">(belum ada)</strong></small>
            </div>

                
                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt"></i> Sistem Kesiswaan v1.0<br>
                        Role-based Access Control
                    </small>
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

</body>
</html>

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
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <title>Login - Sistem Kesiswaan</title>

                <!-- Google Font -->
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
                <!-- Font Awesome -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <!-- AdminLTE -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
                <!-- Memuat CSS statis tanpa Vite -->
                <link rel="stylesheet" href="{{ asset('css/login.css') }}">
            </head>
            <body class="hold-transition login-page">
            <div class="login-box">
                <div class="login-logo">
                    <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                    <a href="#"><b>Sistem</b> Kesiswaan</a>
                </div>
    
                <div class="card">
                    <div class="card-body login-card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="login-box-msg mb-0">Pilih peran Anda untuk memvalidasi akun, lalu login</p>
                            <small class="text-muted">Pilih 1 role sebelum login</small>
                        </div>

                        <div class="roles-grid" id="rolesGrid">
                            <div class="role-card" data-role="admin">
                                <div>
                                    <div class="role-title"><i class="fas fa-user-shield role-icon"></i> Administrator</div>
                                    <div class="role-features">Manajemen pengguna, konfigurasi sistem, dan laporan lengkap.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="guru">
                                <div>
                                    <div class="role-title"><i class="fas fa-chalkboard-teacher role-icon"></i> Guru</div>
                                    <div class="role-features">Input nilai, hadir, catatan pelanggaran, dan pengelolaan kelas.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="bk">
                                <div>
                                    <div class="role-title"><i class="fas fa-hands-helping role-icon"></i> BK</div>
                                    <div class="role-features">Menu & Fitur Utama: Data Siswa, Riwayat Konseling, Jadwal Konseling, Catatan & Laporan.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="verifikator">
                                <div>
                                    <div class="role-title"><i class="fas fa-user-check role-icon"></i> Verifikator</div>
                                    <div class="role-features">Verifikasi pengajuan, validasi data, dan proses approval.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="walikelas">
                                <div>
                                    <div class="role-title"><i class="fas fa-user-tie role-icon"></i> Wali Kelas</div>
                                    <div class="role-features">Pemantauan kelas, komunikasi wali, dan peringatan siswa.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="siswa">
                                <div>
                                    <div class="role-title"><i class="fas fa-user role-icon"></i> Siswa</div>
                                    <div class="role-features">Melihat nilai, pelanggaran, prestasi, dan informasi pribadi.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="ortu">
                                <div>
                                    <div class="role-title"><i class="fas fa-user-friends role-icon"></i> Orang Tua</div>
                                    <div class="role-features">Melihat data anak, nilai, pelanggaran, dan informasi sekolah anak.</div>
                                </div>
                            </div>

                            <div class="role-card" data-role="wali_guru">
                                <div>
                                    <div class="role-title"><i class="fas fa-user-shield role-icon"></i> Wali Guru</div>
                                    <div class="role-features">Fitur serupa Guru: memantau kelas dan memberikan catatan, namun dengan akses berbeda.</div>
                                </div>
                            </div>
                        </div>

                        <div class="login-area">
                            <p class="login-box-msg">Silakan masukkan kredensial Anda untuk melanjutkan</p>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ $errors->first() }}
                        </div>
                        @endif

                        <form action="{{ route('login') }}" method="post" id="loginForm">
                            @csrf
                            <input type="hidden" name="role" id="selectedRole" value="">
                
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="remember" id="remember">
                                        <label for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-2 text-right">
                            <a href="#" id="forgotPasswordLink" data-url="{{ route('password.email') }}">Lupa password?</a>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">Role terpilih: <strong id="roleName">(belum ada)</strong></small>
                        </div>

                
                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt"></i> Sistem Kesiswaan v1.0<br>
                                    Role-based Access Control
                                </small>
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

            <!-- Modal: Forgot Password (diletakkan sebelum penutup body) -->
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
