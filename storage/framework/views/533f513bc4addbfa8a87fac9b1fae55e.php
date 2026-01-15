<?php $__env->startSection('title', 'Bimbingan Konseling Anak'); ?>
<?php $__env->startSection('page-title', 'Bimbingan Konseling Anak'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> <?php echo e($siswa->nama_siswa); ?> - <?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></h3>
    </div>
    <div class="card-body">
        <?php if($bimbingans->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Permasalahan</th>
                        <th>Solusi</th>
                        <th>Tindak Lanjut</th>
                        <th>Konselor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $bimbingans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($bimbingans->firstItem() + $index); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($b->tanggal_bimbingan)->format('d/m/Y')); ?></td>
                        <td><?php echo e($b->permasalahan); ?></td>
                        <td><?php echo e($b->solusi ?? '-'); ?></td>
                        <td><?php echo e($b->tindak_lanjut ?? '-'); ?></td>
                        <td><?php echo e($b->guru->nama_guru ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($bimbingans->links()); ?>

        </div>
        <?php else: ?>
        <p class="text-center text-muted">Belum ada data bimbingan konseling</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/ortu/bimbingan.blade.php ENDPATH**/ ?>