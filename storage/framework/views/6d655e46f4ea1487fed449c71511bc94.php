<?php $__env->startSection('title', 'Edit User'); ?>
<?php $__env->startSection('page-title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-edit mr-1"></i>
                    Form Edit User
                </h3>
            </div>
            <form action="<?php echo e(route('users.update', $user)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="card-body">
                    <?php if($user->email === 'admin@test.com'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-lock"></i> Admin utama hanya dapat mengubah password.
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('nama', $user->nama)); ?>" <?php echo e($user->email === 'admin@test.com' ? 'readonly' : ''); ?> required>
                        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('email', $user->email)); ?>" autocomplete="new-email" <?php echo e($user->email === 'admin@test.com' ? 'readonly' : ''); ?> required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-control <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($user->email === 'admin@test.com' ? 'disabled' : ''); ?> required>
                            <option value="">Pilih Role</option>
                            <option value="admin" <?php echo e(old('role', $user->role) == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="kepala_sekolah" <?php echo e(old('role', $user->role) == 'kepala_sekolah' ? 'selected' : ''); ?>>Kepala Sekolah</option>
                            <option value="kesiswaan" <?php echo e(old('role', $user->role) == 'kesiswaan' ? 'selected' : ''); ?>>Kesiswaan</option>
                            <option value="bk" <?php echo e(old('role', $user->role) == 'bk' ? 'selected' : ''); ?>>BK</option>
                            <option value="guru" <?php echo e(old('role', $user->role) == 'guru' ? 'selected' : ''); ?>>Guru</option>
                            <option value="siswa" <?php echo e(old('role', $user->role) == 'siswa' ? 'selected' : ''); ?>>Siswa</option>
                            <option value="ortu" <?php echo e(old('role', $user->role) == 'ortu' ? 'selected' : ''); ?>>Orang Tua</option>
                        </select>
                        <?php if($user->email === 'admin@test.com'): ?>
                        <input type="hidden" name="role" value="admin">
                        <?php endif; ?>
                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="pending" <?php echo e(old('status', $user->status ?? 'pending') == 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                            <option value="approved" <?php echo e(old('status', $user->status ?? 'pending') == 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                            <option value="rejected" <?php echo e(old('status', $user->status ?? 'pending') == 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group" id="siswa-field" style="display: none;">
                        <label for="siswa_id">Pilih Siswa (Anak) <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="form-control">
                            <option value="">Pilih Siswa</option>
                            <?php $__currentLoopData = \App\Models\Siswa::with('kelas')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($siswa->id); ?>" 
                                    <?php echo e(old('siswa_id', json_decode($user->metadata, true)['siswa_id'] ?? '') == $siswa->id ? 'selected' : ''); ?>>
                                    <?php echo e($siswa->nama_siswa); ?> - <?php echo e($siswa->nis); ?> (<?php echo e($siswa->kelas->nama_kelas ?? '-'); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <hr>
                    <p class="text-muted"><small>Kosongkan password jika tidak ingin mengubah</small></p>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autocomplete="new-password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi User
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td><?php echo e($user->id); ?></td>
                    </tr>
                    <tr>
                        <th>Terdaftar:</th>
                        <td><?php echo e($user->created_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php if($user->email_verified_at): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Belum Verifikasi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const roleSelect = document.getElementById('role');
const siswaField = document.getElementById('siswa-field');

function toggleSiswaField() {
    if (roleSelect.value === 'ortu') {
        siswaField.style.display = 'block';
        document.getElementById('siswa_id').required = true;
    } else {
        siswaField.style.display = 'none';
        document.getElementById('siswa_id').required = false;
    }
}

roleSelect.addEventListener('change', toggleSiswaField);
toggleSiswaField();

<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/users/edit.blade.php ENDPATH**/ ?>