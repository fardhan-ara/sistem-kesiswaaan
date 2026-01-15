<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
            <source src="<?php echo e(asset('videos/school-bg.mp4')); ?>" type="video/mp4">
        </video>
        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="SIKAP Logo">
        <h1>SIKAP</h1>
        <p>Sistem Informasi Kesiswaan dan Prestasi - Mengelola data siswa, pelanggaran, prestasi, dan bimbingan konseling secara terintegrasi</p>
    </div>
    <div class="right-panel">
        <div class="register-form-container">
            <h2>Daftar Akun Baru</h2>

            <?php if(isset($isLoggedIn) && $isLoggedIn): ?>
            <div class="alert alert-warning">
                <strong>Perhatian!</strong> Anda sudah login sebagai <strong><?php echo e($currentUser->nama); ?></strong> (<?php echo e($currentUser->email); ?>).
                <br>Jika Anda ingin mendaftar akun baru, silakan <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form-register').submit();"><strong>logout terlebih dahulu</strong></a>.
                <form id="logout-form-register" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form action="<?php echo e(route('signup.post')); ?>" method="post" id="signupForm" <?php if(isset($isLoggedIn) && $isLoggedIn): ?> style="pointer-events: none; opacity: 0.6;" <?php endif; ?>>
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="role" value="<?php echo e(request('role', 'siswa')); ?>">
                
                <div class="mb-3">
                    <label class="form-label">Mendaftar sebagai: <strong><?php echo e(ucfirst(str_replace('_', ' ', request('role', 'siswa')))); ?></strong></label>
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
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k->id); ?>"><?php echo e($k->nama_kelas); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-3" id="tahunAjaranField" style="display:none;">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control">
                        <option value="">Pilih Tahun Ajaran</option>
                        <?php $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ta->id); ?>"><?php echo e($ta->tahun_mulai); ?>/<?php echo e($ta->tahun_selesai); ?> - <?php echo e(ucfirst($ta->semester)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <input type="text" name="nama_anak" class="form-control <?php $__errorArgs = ['nama_anak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Nama lengkap anak" value="<?php echo e(old('nama_anak')); ?>">
                    <?php $__errorArgs = ['nama_anak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">Tidak harus sama persis huruf besar/kecilnya</small>
                </div>

                <div class="mb-3" id="nisAnakField" style="display:none;">
                    <label class="form-label">NIS Anak <span class="text-danger">*</span></label>
                    <input type="text" name="nis_anak" class="form-control <?php $__errorArgs = ['nis_anak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Nomor Induk Siswa anak" value="<?php echo e(old('nis_anak')); ?>">
                    <?php $__errorArgs = ['nis_anak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">NIS harus benar dan sesuai data siswa</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Masukkan nama lengkap" value="<?php echo e(old('nama')); ?>" required>
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Masukkan email" value="<?php echo e(old('email')); ?>" autocomplete="new-email" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Minimal 6 karakter" autocomplete="new-password" required minlength="6">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" autocomplete="new-password" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary w-100" <?php if(isset($isLoggedIn) && $isLoggedIn): ?> disabled <?php endif; ?>>Daftar</button>
            </form>
            
            <div class="mt-3 text-center">
                <span>Sudah punya akun? </span>
                <a href="<?php echo e(route('landing')); ?>">Masuk</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
$(document).ready(function() {
    const role = '<?php echo e(request("role", "siswa")); ?>';
    
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
<?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/auth/register_public.blade.php ENDPATH**/ ?>