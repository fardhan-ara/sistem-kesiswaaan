<?php $__env->startSection('title', 'Detail Biodata Orang Tua'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2>Detail Biodata Orang Tua</h2>
        <a href="<?php echo e(route('biodata-ortu.index')); ?>" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header"><h5>Data Siswa</h5></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><td width="150">Nama</td><td>: <?php echo e($biodata->siswa->nama_siswa); ?></td></tr>
                        <tr><td>NIS</td><td>: <?php echo e($biodata->siswa->nis); ?></td></tr>
                        <tr><td>Kelas</td><td>: <?php echo e($biodata->siswa->kelas->nama_kelas ?? '-'); ?></td></tr>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5>Data Orang Tua</h5></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <?php if($biodata->nama_ayah): ?>
                        <tr><td width="150">Nama Ayah</td><td>: <?php echo e($biodata->nama_ayah); ?></td></tr>
                        <tr><td>Telp Ayah</td><td>: <?php echo e($biodata->telp_ayah); ?></td></tr>
                        <?php endif; ?>
                        <?php if($biodata->nama_ibu): ?>
                        <tr><td width="150">Nama Ibu</td><td>: <?php echo e($biodata->nama_ibu); ?></td></tr>
                        <tr><td>Telp Ibu</td><td>: <?php echo e($biodata->telp_ibu); ?></td></tr>
                        <?php endif; ?>
                        <?php if($biodata->nama_wali): ?>
                        <tr><td width="150">Nama Wali</td><td>: <?php echo e($biodata->nama_wali); ?></td></tr>
                        <tr><td>Telp Wali</td><td>: <?php echo e($biodata->telp_wali); ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5>Dokumen</h5></div>
                <div class="card-body text-center">
                    <h6>Foto Kartu Keluarga</h6>
                    <img src="<?php echo e(asset('storage/' . $biodata->foto_kk)); ?>" class="img-fluid border" style="max-height: 400px;">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5>Status</h5></div>
                <div class="card-body">
                    <?php if($biodata->status_approval === 'pending'): ?>
                        <span class="badge bg-warning mb-3">Pending</span>
                        <form action="<?php echo e(route('biodata-ortu.approve', $biodata->id)); ?>" method="POST" class="mb-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit" class="btn btn-success w-100">Setujui</button>
                        </form>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">Tolak</button>
                    <?php elseif($biodata->status_approval === 'approved'): ?>
                        <span class="badge bg-success">Approved</span>
                        <p class="mt-2"><small>Oleh: <?php echo e($biodata->approver->nama); ?></small></p>
                    <?php else: ?>
                        <span class="badge bg-danger">Rejected</span>
                        <div class="alert alert-danger mt-2"><?php echo e($biodata->rejection_reason); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('biodata-ortu.reject', $biodata->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Biodata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/biodata_ortu/show.blade.php ENDPATH**/ ?>