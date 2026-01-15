<?php $__env->startSection('title', 'Laporan Executive'); ?>
<?php $__env->startSection('page-title', 'Laporan Executive'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-alt"></i> Comprehensive Report</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('kepala-sekolah.laporan-pdf', ['periode' => request('periode', 'bulan_ini')])); ?>" class="btn btn-danger btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <select name="periode" class="form-control" onchange="this.form.submit()">
                        <option value="hari_ini" <?php echo e(request('periode') == 'hari_ini' ? 'selected' : ''); ?>>Hari Ini</option>
                        <option value="minggu_ini" <?php echo e(request('periode') == 'minggu_ini' ? 'selected' : ''); ?>>Minggu Ini</option>
                        <option value="bulan_ini" <?php echo e(request('periode', 'bulan_ini') == 'bulan_ini' ? 'selected' : ''); ?>>Bulan Ini</option>
                        <option value="tahun_ini" <?php echo e(request('periode') == 'tahun_ini' ? 'selected' : ''); ?>>Tahun Ini</option>
                    </select>
                </div>
            </div>
        </form>
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pelanggaran</span>
                        <span class="info-box-number"><?php echo e($report['ringkasan']['total_pelanggaran'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Prestasi</span>
                        <span class="info-box-number"><?php echo e($report['ringkasan']['total_prestasi'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-gavel"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Sanksi</span>
                        <span class="info-box-number"><?php echo e($report['ringkasan']['total_sanksi'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Siswa Terlibat</span>
                        <span class="info-box-number"><?php echo e($report['ringkasan']['siswa_terlibat'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?php echo e($report['ringkasan']['total_siswa'] ?? 0); ?></h3>
                        <p>Total Siswa</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo e($report['ringkasan']['rasio_pelanggaran'] ?? 0); ?>%</h3>
                        <p>Rasio Siswa Bermasalah</p>
                    </div>
                    <div class="icon"><i class="fas fa-percentage"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo e(100 - ($report['ringkasan']['rasio_pelanggaran'] ?? 0)); ?>%</h3>
                        <p>Tingkat Disiplin</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">Top 5 Pelanggaran</h3>
                    </div>
                    <div class="card-body">
                        <?php if(isset($report['analytics']['top_pelanggaran']) && count($report['analytics']['top_pelanggaran']) > 0): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jenis Pelanggaran</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $report['analytics']['top_pelanggaran']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->nama_pelanggaran); ?></td>
                                    <td class="text-right"><span class="badge badge-danger"><?php echo e($item->total); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted">Tidak ada data</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger">
                        <h3 class="card-title">Top 5 Kelas Bermasalah</h3>
                    </div>
                    <div class="card-body">
                        <?php if(isset($report['analytics']['top_kelas']) && count($report['analytics']['top_kelas']) > 0): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $report['analytics']['top_kelas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->nama_kelas); ?></td>
                                    <td class="text-right"><span class="badge badge-warning"><?php echo e($item->total); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted">Tidak ada data</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Top 5 Prestasi</h3>
                    </div>
                    <div class="card-body">
                        <?php if(isset($report['analytics']['top_prestasi']) && count($report['analytics']['top_prestasi']) > 0): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Jenis Prestasi</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $report['analytics']['top_prestasi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->nama_prestasi); ?></td>
                                    <td class="text-right"><span class="badge badge-success"><?php echo e($item->total); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p class="text-center text-muted">Tidak ada data</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(isset($report['insights']) && count($report['insights']) > 0): ?>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-lightbulb"></i> Insights (Analisis Otomatis)</h3>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <?php $__currentLoopData = $report['insights']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($insight); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Rekomendasi (Input Manual)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-light" data-toggle="modal" data-target="#modalRekomendasi">
                        <i class="fas fa-plus"></i> Tambah Rekomendasi
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if(isset($report['rekomendasi']) && count($report['rekomendasi']) > 0): ?>
                <ul class="mb-0">
                    <?php $__currentLoopData = $report['rekomendasi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rek): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="mb-2">
                        <?php echo e($rek->rekomendasi); ?>

                        <?php if($rek->periode): ?>
                        <span class="badge badge-secondary"><?php echo e($rek->periode); ?></span>
                        <?php endif; ?>
                        <form action="<?php echo e(route('kepala-sekolah.rekomendasi.delete', $rek->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus rekomendasi ini?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger ml-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada rekomendasi. Klik tombol "Tambah Rekomendasi" untuk menambahkan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Rekomendasi -->
<div class="modal fade" id="modalRekomendasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('kepala-sekolah.rekomendasi.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rekomendasi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rekomendasi <span class="text-danger">*</span></label>
                        <textarea name="rekomendasi" class="form-control" rows="4" required placeholder="Masukkan rekomendasi untuk kepala sekolah..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Periode (Opsional)</label>
                        <input type="text" name="periode" class="form-control" placeholder="Contoh: Semester 1 2024">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/kepala-sekolah/laporan-executive.blade.php ENDPATH**/ ?>