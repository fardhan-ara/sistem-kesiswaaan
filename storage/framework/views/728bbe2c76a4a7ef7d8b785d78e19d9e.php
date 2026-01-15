<?php $__env->startSection('title', 'Dashboard Orang Tua'); ?>
<?php $__env->startSection('page-title', 'Dashboard Orang Tua'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('biodata_ortu.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php if(!$siswa): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <?php if(isset($status) && $status === 'pending'): ?>
                    <i class="fas fa-clock fa-5x text-warning mb-4"></i>
                    <h3>Biodata Sedang Ditinjau</h3>
                    <p class="text-muted"><?php echo e($message); ?></p>
                    <div class="alert alert-warning">
                        <i class="fas fa-hourglass-half"></i> <strong>Status: PENDING</strong><br>
                        Biodata Anda sedang dalam proses verifikasi oleh admin.<br>
                        Anda akan dapat mengakses data anak setelah admin menyetujui biodata Anda.
                    </div>
                <?php elseif(isset($status) && $status === 'rejected'): ?>
                    <i class="fas fa-times-circle fa-5x text-danger mb-4"></i>
                    <h3>Biodata Ditolak</h3>
                    <p class="text-danger"><?php echo e($message); ?></p>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Status: DITOLAK</strong><br>
                        Silakan lengkapi biodata kembali dengan perbaikan.
                    </div>
                <?php else: ?>
                    <i class="fas fa-user-slash fa-5x text-warning mb-4"></i>
                    <h3>Data Anak Belum Terhubung</h3>
                    <p class="text-muted"><?php echo e($message ?? 'Silakan lengkapi biodata terlebih dahulu.'); ?></p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Informasi:</strong><br>
                        Email: <strong><?php echo e(Auth::user()->email ?? '-'); ?></strong><br>
                        Silakan lengkapi biodata untuk menghubungkan akun Anda dengan data siswa.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> Selamat datang di Dashboard Orang Tua. Anda dapat memantau perkembangan anak Anda di sini.
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e($totalPelanggaran); ?></h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e($totalPrestasi); ?></h3>
                <p>Total Prestasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e($totalPoin); ?></h3>
                <p>Total Poin Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e($sanksiAktif); ?></h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-gavel"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-child"></i> Profil Anak</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">NIS</th>
                        <td><?php echo e($siswa->nis); ?></td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td><?php echo e($siswa->nama_siswa); ?></td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td><?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <td><?php echo e($siswa->tahunAjaran->tahun_ajaran ?? '-'); ?> (<?php echo e($siswa->tahunAjaran->semester ?? '-'); ?>)</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td><?php echo e($siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-id-card"></i> Dokumen Orang Tua</h3>
            </div>
            <div class="card-body text-center">
                <?php if(isset($biodata) && $biodata->foto_kk): ?>
                    <p class="text-muted mb-2"><strong>Foto Kartu Keluarga (KK)</strong></p>
                    <img src="<?php echo e(asset('storage/' . $biodata->foto_kk)); ?>" alt="Foto KK" class="img-fluid" style="max-height: 300px; border: 1px solid #ddd; border-radius: 5px;">
                    <div class="mt-2">
                        <a href="<?php echo e(asset('storage/' . $biodata->foto_kk)); ?>" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Lihat Full Size
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Foto KK tidak tersedia</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status Poin</h3>
            </div>
            <div class="card-body">
                <?php if($totalPoin >= 51): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Sangat Berat!</strong><br>
                        Total poin: <strong><?php echo e($totalPoin); ?></strong><br>
                        Anak Anda berada dalam kategori pelanggaran sangat berat.
                    </div>
                <?php elseif($totalPoin >= 31): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i> <strong>Berat!</strong><br>
                        Total poin: <strong><?php echo e($totalPoin); ?></strong><br>
                        Anak Anda berada dalam kategori pelanggaran berat.
                    </div>
                <?php elseif($totalPoin >= 16): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Sedang</strong><br>
                        Total poin: <strong><?php echo e($totalPoin); ?></strong><br>
                        Anak Anda berada dalam kategori pelanggaran sedang.
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <strong>Baik!</strong><br>
                        Total poin: <strong><?php echo e($totalPoin); ?></strong><br>
                        Anak Anda memiliki perilaku yang baik!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Pelanggaran -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Pelanggaran Terbaru</h3>
            </div>
            <div class="card-body">
                <?php if(isset($pelanggaranTerbaru) && $pelanggaranTerbaru->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Pencatat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pelanggaranTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($p->created_at->format('d/m/Y')); ?></td>
                                <td><?php echo e($p->jenisPelanggaran->nama_pelanggaran ?? '-'); ?></td>
                                <td><span class="badge badge-danger"><?php echo e($p->poin); ?></span></td>
                                <td><?php echo e($p->guru->nama_guru ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-center text-muted">Belum ada data pelanggaran</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Prestasi -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trophy"></i> Riwayat Prestasi Terbaru</h3>
            </div>
            <div class="card-body">
                <?php if(isset($prestasiTerbaru) && $prestasiTerbaru->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Prestasi</th>
                                <th>Poin</th>
                                <th>Pencatat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $prestasiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($p->created_at->format('d/m/Y')); ?></td>
                                <td><?php echo e($p->jenisPrestasi->nama_prestasi ?? '-'); ?></td>
                                <td><span class="badge badge-success"><?php echo e($p->poin); ?></span></td>
                                <td><?php echo e($p->guru->nama_guru ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-center text-muted">Belum ada data prestasi</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Sanksi Aktif -->
<?php if(isset($sanksiAktifList) && $sanksiAktifList->count() > 0): ?>
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card border-danger">
            <div class="card-header bg-danger">
                <h3 class="card-title"><i class="fas fa-gavel"></i> Sanksi yang Sedang Berjalan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelanggaran</th>
                                <th>Jenis Sanksi</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $sanksiAktifList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($s->pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-'); ?></td>
                                <td><?php echo e($s->jenis_sanksi); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($s->tanggal_mulai)->format('d/m/Y')); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($s->tanggal_selesai)->format('d/m/Y')); ?></td>
                                <td>
                                    <?php if($s->status_sanksi == 'sedang_dilaksanakan'): ?>
                                        <span class="badge badge-warning">Berjalan</span>
                                    <?php else: ?>
                                        <span class="badge badge-info"><?php echo e(ucfirst($s->status_sanksi)); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/dashboard/ortu.blade.php ENDPATH**/ ?>