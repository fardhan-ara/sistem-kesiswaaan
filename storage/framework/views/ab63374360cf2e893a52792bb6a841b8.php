<?php $__env->startSection('title', 'Backup & Restore Database'); ?>
<?php $__env->startSection('page-title', 'Backup & Restore Database'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-database"></i> Manajemen Backup Database</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="createBackup()">
                        <i class="fas fa-plus"></i> Buat Backup Manual
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Informasi Backup Otomatis:</h5>
                    <ul class="mb-0">
                        <li><strong>Daily Backup:</strong> Setiap hari pukul 00:00 (disimpan 30 hari)</li>
                        <li><strong>Weekly Backup:</strong> Setiap Minggu pukul 02:00 (disimpan 8 minggu)</li>
                        <li><strong>Monthly Backup:</strong> Akhir bulan pukul 23:00 (disimpan permanen)</li>
                    </ul>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Tipe</th>
                                <th>Ukuran</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $backups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $backup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($backup['name']); ?></td>
                                <td>
                                    <?php switch($backup['type']):
                                        case ('daily'): ?>
                                            <span class="badge badge-info">Daily</span>
                                            <?php break; ?>
                                        <?php case ('weekly'): ?>
                                            <span class="badge badge-primary">Weekly</span>
                                            <?php break; ?>
                                        <?php case ('monthly'): ?>
                                            <span class="badge badge-success">Monthly</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge badge-secondary">Manual</span>
                                    <?php endswitch; ?>
                                </td>
                                <td><?php echo e($backup['size']); ?></td>
                                <td><?php echo e($backup['date']); ?></td>
                                <td>
                                    <a href="<?php echo e(route('backup.download', $backup['name'])); ?>" class="btn btn-success btn-sm" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="restoreBackup('<?php echo e($backup['name']); ?>')" title="Restore">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <form action="<?php echo e(route('backup.delete', $backup['name'])); ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus backup ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada backup</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function createBackup() {
    Swal.fire({
        title: 'Buat Backup Database',
        text: 'Proses ini akan membuat backup lengkap database',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Buat Backup!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Membuat Backup...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("backup.create")); ?>';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function restoreBackup(fileName) {
    Swal.fire({
        title: 'Restore Database?',
        html: '<strong class="text-danger">PERINGATAN!</strong><br>Proses ini akan mengganti seluruh data database dengan backup:<br><strong>' + fileName + '</strong><br><br>Data saat ini akan hilang!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Restore!',
        cancelButtonText: 'Batal',
        input: 'checkbox',
        inputValue: 0,
        inputPlaceholder: 'Saya memahami risikonya',
        inputValidator: (result) => {
            return !result && 'Anda harus mencentang untuk melanjutkan'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Restoring Database...',
                text: 'Mohon tunggu, jangan tutup halaman ini',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("backup.restore")); ?>';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrf);
            
            const fileInput = document.createElement('input');
            fileInput.type = 'hidden';
            fileInput.name = 'backup_file';
            fileInput.value = fileName;
            form.appendChild(fileInput);
            
            document.body.appendChild(form);
            form.submit();
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
        timer: 5000
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/backup/index.blade.php ENDPATH**/ ?>