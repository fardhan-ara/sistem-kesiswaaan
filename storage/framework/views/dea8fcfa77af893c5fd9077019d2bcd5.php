<?php $__env->startSection('title', 'Sanksi Anak'); ?>
<?php $__env->startSection('page-title', 'Sanksi Anak'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> <?php echo e($siswa->nama_siswa); ?> - <?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></h3>
    </div>
    <div class="card-body">
        <?php if($sanksis->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Pelanggaran</th>
                        <th>Jenis Sanksi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $sanksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($sanksis->firstItem() + $index); ?></td>
                        <td><?php echo e($s->pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-'); ?></td>
                        <td><?php echo e($s->jenis_sanksi); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($s->tanggal_mulai)->format('d/m/Y')); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($s->tanggal_selesai)->format('d/m/Y')); ?></td>
                        <td>
                            <?php if($s->status_sanksi == 'aktif'): ?>
                                <span class="badge badge-danger">Aktif</span>
                            <?php elseif($s->status_sanksi == 'sedang_dilaksanakan'): ?>
                                <span class="badge badge-warning">Berjalan</span>
                            <?php else: ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($sanksis->links()); ?>

        </div>
        <?php else: ?>
        <p class="text-center text-muted">Belum ada data sanksi</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/ortu/sanksi.blade.php ENDPATH**/ ?>