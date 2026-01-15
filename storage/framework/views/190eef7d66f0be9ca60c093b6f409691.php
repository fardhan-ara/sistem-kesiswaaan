<?php $__env->startSection('title', 'Data Guru'); ?>
<?php $__env->startSection('page-title', 'Data Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chalkboard-teacher mr-1"></i> Daftar Guru</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('guru.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Guru
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status_approval" class="form-control">
                        <option value="">Semua Status Approval</option>
                        <option value="pending" <?php echo e(request('status_approval') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(request('status_approval') == 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                        <option value="rejected" <?php echo e(request('status_approval') == 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="tidak_aktif" <?php echo e(request('status') == 'tidak_aktif' ? 'selected' : ''); ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama guru..." value="<?php echo e(request('nama')); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="<?php echo e(route('guru.index')); ?>" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="guruTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Jenis Kelamin</th>
                    <th>Email</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Status Approval</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $gurus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($gurus->firstItem() + $index); ?></td>
                    <td><strong><?php echo e($item->nip); ?></strong></td>
                    <td><?php echo e($item->nama_guru); ?></td>
                    <td>
                        <?php if($item->jenis_kelamin === 'L'): ?>
                            <span class="badge badge-primary">Laki-laki</span>
                        <?php elseif($item->jenis_kelamin === 'P'): ?>
                            <span class="badge badge-info">Perempuan</span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($item->user->email ?? '-'); ?></td>
                    <td><?php echo e($item->bidang_studi ?? '-'); ?></td>
                    <td>
                        <?php if($item->status === 'aktif'): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($item->status_approval === 'pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php elseif($item->status_approval === 'approved'): ?>
                            <span class="badge badge-success">Disetujui</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Ditolak</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewGuru(<?php echo e($item->id); ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if($item->status_approval === 'pending'): ?>
                        <form action="<?php echo e(route('guru.approve', $item->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-sm" title="Setuju">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="<?php echo e(route('guru.reject', $item->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin tolak dan hapus?')" title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="<?php echo e(route('guru.edit', $item)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('guru.destroy', $item)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($gurus->hasPages()): ?>
    <div class="card-footer"><?php echo e($gurus->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function viewGuru(id) {
    $.get('/guru/' + id, function(data) {
        Swal.fire({
            title: 'Detail Guru',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>NIP</th><td>${data.nip}</td></tr>
                    <tr><th>Nama</th><td>${data.nama_guru}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>${data.jenis_kelamin === 'L' ? 'Laki-laki' : data.jenis_kelamin === 'P' ? 'Perempuan' : '-'}</td></tr>
                    <tr><th>Email</th><td>${data.user.email}</td></tr>
                    <tr><th>Mata Pelajaran</th><td>${data.bidang_studi || '-'}</td></tr>
                    <tr><th>Status</th><td>${data.status}</td></tr>
                    <tr><th>Status Approval</th><td>${data.status_approval}</td></tr>
                </table>
            `,
            width: 600,
            confirmButtonText: 'Tutup'
        });
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/guru/index.blade.php ENDPATH**/ ?>