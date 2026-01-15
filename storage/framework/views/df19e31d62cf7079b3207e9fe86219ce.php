<?php $__env->startSection('title', 'Jenis Prestasi'); ?>
<?php $__env->startSection('page-title', 'Jenis Prestasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-medal mr-1"></i> Daftar Jenis Prestasi</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('jenis-prestasi.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Jenis Prestasi
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama prestasi..." value="<?php echo e(request('nama')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="tingkat" class="form-control">
                        <option value="">Semua Tingkat</option>
                        <option value="sekolah" <?php echo e(request('tingkat') == 'sekolah' ? 'selected' : ''); ?>>Sekolah</option>
                        <option value="kecamatan" <?php echo e(request('tingkat') == 'kecamatan' ? 'selected' : ''); ?>>Kecamatan</option>
                        <option value="kota" <?php echo e(request('tingkat') == 'kota' ? 'selected' : ''); ?>>Kota</option>
                        <option value="provinsi" <?php echo e(request('tingkat') == 'provinsi' ? 'selected' : ''); ?>>Provinsi</option>
                        <option value="nasional" <?php echo e(request('tingkat') == 'nasional' ? 'selected' : ''); ?>>Nasional</option>
                        <option value="internasional" <?php echo e(request('tingkat') == 'internasional' ? 'selected' : ''); ?>>Internasional</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori_penampilan" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="solo" <?php echo e(request('kategori_penampilan') == 'solo' ? 'selected' : ''); ?>>Solo</option>
                        <option value="duo" <?php echo e(request('kategori_penampilan') == 'duo' ? 'selected' : ''); ?>>Duo</option>
                        <option value="trio" <?php echo e(request('kategori_penampilan') == 'trio' ? 'selected' : ''); ?>>Trio</option>
                        <option value="grup" <?php echo e(request('kategori_penampilan') == 'grup' ? 'selected' : ''); ?>>Grup</option>
                        <option value="tim" <?php echo e(request('kategori_penampilan') == 'tim' ? 'selected' : ''); ?>>Tim</option>
                        <option value="kolektif" <?php echo e(request('kategori_penampilan') == 'kolektif' ? 'selected' : ''); ?>>Kolektif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="<?php echo e(route('jenis-prestasi.index')); ?>" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Prestasi</th>
                    <th width="10%">Tingkat</th>
                    <th width="12%">Kategori</th>
                    <th width="8%">Poin</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $jenisPrestasiS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($jenisPrestasiS->firstItem() + $index); ?></td>
                    <td><strong><?php echo e($item->nama_prestasi); ?></strong></td>
                    <td><span class="badge badge-info"><?php echo e(ucfirst($item->tingkat)); ?></span></td>
                    <td><span class="badge badge-secondary"><?php echo e(ucfirst($item->kategori_penampilan)); ?></span></td>
                    <td><span class="badge badge-success"><?php echo e($item->poin_reward); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('jenis-prestasi.edit', $item)); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?php echo e(route('jenis-prestasi.destroy', $item)); ?>" method="POST" class="d-inline">
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
    <?php if($jenisPrestasiS->hasPages()): ?>
    <div class="card-footer"><?php echo e($jenisPrestasiS->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/jenis-prestasi/index.blade.php ENDPATH**/ ?>