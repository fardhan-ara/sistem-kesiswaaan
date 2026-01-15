<?php $__env->startSection('title', 'Prestasi Anak'); ?>
<?php $__env->startSection('page-title', 'Prestasi Anak'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> <?php echo e($siswa->nama_siswa); ?> - <?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></h3>
    </div>
    <div class="card-body">
        <?php if($prestasis->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Jenis Prestasi</th>
                        <th>Tingkat</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $prestasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($prestasis->firstItem() + $index); ?></td>
                        <td><?php echo e($p->created_at->format('d/m/Y H:i')); ?></td>
                        <td><?php echo e($p->jenisPrestasi->nama_prestasi ?? '-'); ?></td>
                        <td>
                            <?php if($p->tingkat == 'internasional'): ?>
                                <span class="badge badge-danger">Internasional</span>
                            <?php elseif($p->tingkat == 'nasional'): ?>
                                <span class="badge badge-warning">Nasional</span>
                            <?php elseif($p->tingkat == 'provinsi'): ?>
                                <span class="badge badge-info">Provinsi</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php echo e(ucfirst($p->tingkat)); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge badge-success"><?php echo e($p->poin); ?></span></td>
                        <td><?php echo e($p->keterangan ?? '-'); ?></td>
                        <td><?php echo e($p->guru->nama_guru ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($prestasis->links()); ?>

        </div>
        <?php else: ?>
        <p class="text-center text-muted">Belum ada data prestasi</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/ortu/prestasi.blade.php ENDPATH**/ ?>