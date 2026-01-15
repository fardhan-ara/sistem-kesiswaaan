<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <?php if(isset($error)): ?>
                    <i class="fas fa-exclamation-triangle fa-5x text-danger mb-4"></i>
                    <h3>Terjadi Kesalahan</h3>
                    <p class="text-muted"><?php echo e($error); ?></p>
                <?php else: ?>
                    <i class="fas fa-home fa-5x text-primary mb-4"></i>
                    <h3>Selamat Datang di SIKAP</h3>
                    <p class="text-muted">Sistem Informasi Kesiswaan dan Prestasi</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/dashboard/index.blade.php ENDPATH**/ ?>