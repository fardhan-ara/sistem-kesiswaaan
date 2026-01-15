<?php $__env->startSection('title', 'Pelanggaran Anak'); ?>
<?php $__env->startSection('page-title', 'Pelanggaran Anak'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> <?php echo e($siswa->nama_siswa); ?> - <?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></h3>
        <div class="card-tools">
            <span class="badge badge-danger">Total Poin: <?php echo e($totalPoin); ?></span>
        </div>
    </div>
    <div class="card-body">
        <?php if($pelanggarans->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Pencatat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pelanggarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($pelanggarans->firstItem() + $index); ?></td>
                        <td><?php echo e($p->created_at->format('d/m/Y H:i')); ?></td>
                        <td><?php echo e($p->jenisPelanggaran->nama_pelanggaran ?? '-'); ?></td>
                        <td><span class="badge badge-danger"><?php echo e($p->poin); ?></span></td>
                        <td><?php echo e($p->keterangan ?? '-'); ?></td>
                        <td><?php echo e($p->guru->nama_guru ?? '-'); ?></td>
                        <td>
                            <?php if($p->status_verifikasi == 'terverifikasi'): ?>
                                <span class="badge badge-success">Terverifikasi</span>
                            <?php else: ?>
                                <span class="badge badge-info">Diverifikasi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($pelanggarans->links()); ?>

        </div>
        <?php else: ?>
        <p class="text-center text-muted">Belum ada data pelanggaran</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/ortu/pelanggaran.blade.php ENDPATH**/ ?>