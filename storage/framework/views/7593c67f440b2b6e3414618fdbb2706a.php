<?php $__env->startSection('title', 'Data Siswa'); ?>
<?php $__env->startSection('page-title', 'Data Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Daftar Siswa</h3>
        <div class="card-tools">
            <a href="/siswa/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <select name="status_approval" class="form-control">
                        <option value="">Semua Status Approval</option>
                        <option value="pending" <?php echo e(request('status_approval') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="approved" <?php echo e(request('status_approval') == 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                        <option value="rejected" <?php echo e(request('status_approval') == 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>
                        <?php $__currentLoopData = \App\Models\Kelas::orderBy('nama_kelas')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama_kelas); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L" <?php echo e(request('jenis_kelamin') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e(request('jenis_kelamin') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama..." value="<?php echo e(request('nama')); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="nis" class="form-control" placeholder="Cari NIS..." value="<?php echo e(request('nis')); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block"><i class="fas fa-filter"></i> Filter</button>
                    <a href="<?php echo e(route('siswa.index')); ?>" class="btn btn-secondary btn-block mt-1"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="siswaTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Status Approval</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($siswas->firstItem() + $index); ?></td>
                    <td><strong><?php echo e($item->nis); ?></strong></td>
                    <td><?php echo e($item->nama_siswa); ?></td>
                    <td>
                        <?php if($item->jenis_kelamin === 'L'): ?>
                            <span class="badge badge-primary">Laki-laki</span>
                        <?php else: ?>
                            <span class="badge badge-info">Perempuan</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($item->kelas->nama_kelas ?? '-'); ?></td>
                    <td><?php echo e($item->tahunAjaran->tahun_ajaran ?? '-'); ?></td>
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
                        <button class="btn btn-info btn-sm" onclick="viewSiswa(<?php echo e($item->id); ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php if($item->status_approval === 'pending'): ?>
                        <form action="<?php echo e(route('siswa.approve', $item->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-sm" title="Setuju">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="<?php echo e(route('siswa.reject', $item->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin tolak dan hapus?')" title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="/siswa/<?php echo e($item->id); ?>/edit" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="/siswa/<?php echo e($item->id); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr class="no-data-row"><td colspan="8" class="text-center">Tidak ada data</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($siswas->hasPages()): ?>
    <div class="card-footer"><?php echo e($siswas->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function viewSiswa(id) {
    $.get('/siswa/' + id, function(data) {
        Swal.fire({
            title: 'Detail Siswa',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>NIS</th><td>${data.nis}</td></tr>
                    <tr><th>Nama</th><td>${data.nama_siswa}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td></tr>
                    <tr><th>Kelas</th><td>${data.kelas ? data.kelas.nama_kelas : '-'}</td></tr>
                    <tr><th>Tahun Ajaran</th><td>${data.tahun_ajaran ? data.tahun_ajaran.tahun_ajaran : '-'}</td></tr>
                    <tr><th>Email</th><td>${data.user ? data.user.email : '-'}</td></tr>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/siswa/index.blade.php ENDPATH**/ ?>