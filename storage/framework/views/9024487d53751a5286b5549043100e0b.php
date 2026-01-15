<?php $__env->startSection('title', 'Data Kelas'); ?>
<?php $__env->startSection('page-title', 'Data Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-school mr-1"></i> Daftar Kelas</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('kelas.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nama_kelas" class="form-control" placeholder="Cari nama kelas..." value="<?php echo e(request('nama_kelas')); ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="jurusan" class="form-control" placeholder="Cari jurusan..." value="<?php echo e(request('jurusan')); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="<?php echo e(route('kelas.index')); ?>" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="kelasTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($kelas->firstItem() + $index); ?></td>
                    <td><strong><?php echo e($item->nama_kelas); ?></strong></td>
                    <td><?php echo e($item->jurusan ?? '-'); ?></td>
                    <td><?php echo e($item->waliKelas ? $item->waliKelas->nama_guru : '-'); ?></td>
                    <td><?php echo e($item->tahunAjaran->tahun_ajaran ?? '-'); ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewKelas(<?php echo e($item->id); ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="<?php echo e(route('kelas.edit', $item)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('kelas.destroy', $item)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($kelas->hasPages()): ?>
    <div class="card-footer"><?php echo e($kelas->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function viewKelas(id) {
    $.get('/kelas/' + id, function(data) {
        Swal.fire({
            title: 'Detail Kelas',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>Nama Kelas</th><td>${data.nama_kelas}</td></tr>
                    <tr><th>Jurusan</th><td>${data.jurusan || '-'}</td></tr>
                    <tr><th>Wali Kelas</th><td>${data.wali_kelas && data.wali_kelas.nama_guru ? data.wali_kelas.nama_guru : '-'}</td></tr>
                    <tr><th>Tahun Ajaran</th><td>${data.tahun_ajaran ? data.tahun_ajaran.tahun_ajaran : '-'}</td></tr>
                </table>
            `,
            width: 600,
            confirmButtonText: 'Tutup'
        });
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/kelas/index.blade.php ENDPATH**/ ?>