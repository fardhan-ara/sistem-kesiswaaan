<?php $__env->startSection('title', 'Approval Biodata Orang Tua'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">Approval Biodata Orang Tua</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Orang Tua</th>
                        <th>Siswa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $biodatas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($b->created_at->format('d/m/Y')); ?></td>
                        <td><?php echo e($b->user->nama); ?></td>
                        <td><?php echo e($b->siswa->nama_siswa); ?> - <?php echo e($b->siswa->kelas->nama_kelas ?? '-'); ?></td>
                        <td>
                            <?php if($b->status_approval === 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif($b->status_approval === 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('biodata-ortu.show', $b->id)); ?>" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php echo e($biodatas->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/biodata_ortu/index.blade.php ENDPATH**/ ?>