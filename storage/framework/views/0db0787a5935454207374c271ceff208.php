<?php $__env->startSection('title', 'Manajemen User'); ?>
<?php $__env->startSection('page-title', 'Manajemen User'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Daftar User
                </h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="role" class="form-control">
                                <option value="">Semua Role</option>
                                <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="kepala_sekolah" <?php echo e(request('role') == 'kepala_sekolah' ? 'selected' : ''); ?>>Kepala Sekolah</option>
                                <option value="kesiswaan" <?php echo e(request('role') == 'kesiswaan' ? 'selected' : ''); ?>>Kesiswaan</option>
                                <option value="bk" <?php echo e(request('role') == 'bk' ? 'selected' : ''); ?>>BK</option>
                                <option value="guru" <?php echo e(request('role') == 'guru' ? 'selected' : ''); ?>>Guru</option>
                                <option value="siswa" <?php echo e(request('role') == 'siswa' ? 'selected' : ''); ?>>Siswa</option>
                                <option value="ortu" <?php echo e(request('role') == 'ortu' ? 'selected' : ''); ?>>Orang Tua</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="nama" class="form-control" placeholder="Cari nama..." value="<?php echo e(request('nama')); ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="email" class="form-control" placeholder="Cari email..." value="<?php echo e(request('email')); ?>">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="usersTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terakhir Login</th>
                                <th>Terdaftar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($users->firstItem() + $index); ?></td>
                                <td><?php echo e($user->nama); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <?php switch($user->role):
                                        case ('admin'): ?>
                                            <span class="badge badge-danger">Admin</span>
                                            <?php break; ?>
                                        <?php case ('kepala_sekolah'): ?>
                                            <span class="badge badge-dark">Kepala Sekolah</span>
                                            <?php break; ?>
                                        <?php case ('kesiswaan'): ?>
                                            <span class="badge badge-warning">Kesiswaan</span>
                                            <?php break; ?>
                                        <?php case ('bk'): ?>
                                            <span class="badge badge-purple">BK</span>
                                            <?php break; ?>
                                        <?php case ('guru'): ?>
                                            <span class="badge badge-info">Guru</span>
                                            <?php break; ?>
                                        <?php case ('siswa'): ?>
                                            <span class="badge badge-success">Siswa</span>
                                            <?php break; ?>
                                        <?php case ('ortu'): ?>
                                            <span class="badge badge-secondary">Orang Tua</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge badge-light"><?php echo e(ucfirst($user->role)); ?></span>
                                    <?php endswitch; ?>
                                </td>
                                <td>
                                    <?php switch($user->status ?? 'approved'):
                                        case ('approved'): ?>
                                            <span class="badge badge-success">Disetujui</span>
                                            <?php break; ?>
                                        <?php case ('rejected'): ?>
                                            <span class="badge badge-danger" title="<?php echo e($user->rejection_reason); ?>">Ditolak</span>
                                            <?php break; ?>
                                        <?php case ('pending'): ?>
                                            <span class="badge badge-warning">Menunggu</span>
                                            <?php break; ?>
                                    <?php endswitch; ?>
                                    <?php if($user->role === 'ortu'): ?>
                                        <?php if($user->nama_anak && $user->nis_anak): ?>
                                            <br><small class="text-info">Anak: <?php echo e($user->nama_anak); ?> (<?php echo e($user->nis_anak); ?>)</small>
                                        <?php else: ?>
                                            <br><small class="text-danger">⚠ Data anak belum lengkap</small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($user->role === 'guru' || $user->role === 'wali_kelas'): ?>
                                        <?php if($user->guru): ?>
                                            <br><small class="text-success">✓ Data guru tersedia</small>
                                        <?php else: ?>
                                            <br><small class="text-danger">⚠ Belum terhubung</small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($user->role === 'siswa'): ?>
                                        <?php if($user->siswa): ?>
                                            <br><small class="text-success">✓ Data siswa tersedia</small>
                                        <?php else: ?>
                                            <br><small class="text-danger">⚠ Belum terhubung</small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->last_login_at): ?>
                                        <?php
                                            $lastLogin = is_string($user->last_login_at) ? \Carbon\Carbon::parse($user->last_login_at) : $user->last_login_at;
                                        ?>
                                        <span title="<?php echo e($lastLogin->format('d/m/Y H:i:s')); ?>">
                                            <?php echo e($lastLogin->diffForHumans()); ?>

                                        </span>
                                        <?php
                                            $lastActivity = $user->last_activity_at ? (is_string($user->last_activity_at) ? \Carbon\Carbon::parse($user->last_activity_at) : $user->last_activity_at) : null;
                                            $isOnline = $lastActivity && $lastActivity->diffInMinutes(now()) < 5;
                                        ?>
                                        <?php if($isOnline): ?>
                                            <br><span class="badge badge-success"><i class="fas fa-circle"></i> Online</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Belum pernah login</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->created_at): ?>
                                        <?php
                                            $createdAt = is_string($user->created_at) ? \Carbon\Carbon::parse($user->created_at) : $user->created_at;
                                        ?>
                                        <?php echo e($createdAt->format('d/m/Y H:i')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(($user->status ?? 'approved') === 'pending'): ?>
                                        <button type="button" class="btn btn-success btn-sm" title="Setujui" 
                                                onclick="confirmApprove(<?php echo e($user->id); ?>, '<?php echo e($user->nama); ?>')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Tolak" 
                                                onclick="showRejectModal(<?php echo e($user->id); ?>, '<?php echo e($user->nama); ?>')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" title="Lihat Detail" 
                                                onclick="window.location.href='<?php echo e(route('users.edit', $user)); ?>'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form id="approve-form-<?php echo e($user->id); ?>" action="<?php echo e(route('users.approve', $user)); ?>" method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                        </form>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-warning btn-sm" title="Edit" <?php if($user->email === 'admin@test.com'): ?> onclick="return confirm('Admin utama hanya bisa ubah password!')" <?php endif; ?>>
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($user->id !== auth()->id() && $user->email !== 'admin@test.com'): ?>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                                onclick="confirmDelete(<?php echo e($user->id); ?>, '<?php echo e($user->nama); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-<?php echo e($user->id); ?>" action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="no-data-row">
                                <td colspan="8" class="text-center">Tidak ada data user</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if($users->hasPages()): ?>
            <div class="card-footer">
                <?php echo e($users->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmApprove(userId, userName) {
    console.log('confirmApprove called', userId, userName);
    Swal.fire({
        title: 'Konfirmasi Setujui',
        html: `Apakah Anda yakin ingin menyetujui user <strong>${userName}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        console.log('Swal result:', result);
        if (result.isConfirmed) {
            console.log('Submitting form approve-form-' + userId);
            const form = document.getElementById('approve-form-' + userId);
            console.log('Form found:', form);
            if (form) {
                form.submit();
            } else {
                console.error('Form not found!');
                alert('Error: Form tidak ditemukan!');
            }
        }
    });
}

function showRejectModal(userId, userName) {
    console.log('showRejectModal called', userId, userName);
    Swal.fire({
        title: 'Tolak User',
        html: `<p>Tolak user <strong>${userName}</strong></p>`,
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        inputAttributes: {
            'aria-label': 'Alasan penolakan'
        },
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan penolakan harus diisi!'
            }
        }
    }).then((result) => {
        console.log('Reject result:', result);
        if (result.isConfirmed) {
            console.log('Creating reject form');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/users/' + userId + '/reject';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = result.value;
            form.appendChild(reasonInput);
            
            document.body.appendChild(form);
            console.log('Submitting reject form');
            form.submit();
        }
    });
}

function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus user <strong>${userName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}

<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/users/index.blade.php ENDPATH**/ ?>