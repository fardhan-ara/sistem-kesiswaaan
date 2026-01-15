<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e($totalSiswa); ?></h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="<?php echo e(route('siswa.index')); ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e($totalPelanggaran); ?></h3>
                <p>Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
            <a href="<?php echo e(route('pelanggaran.index')); ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e($totalPrestasi); ?></h3>
                <p>Prestasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-trophy"></i>
            </div>
            <a href="<?php echo e(route('prestasi.index')); ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e($sanksiAktif); ?></h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-gavel"></i>
            </div>
            <a href="<?php echo e(route('sanksi.index')); ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3><?php echo e($totalUsers); ?></h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?php echo e(route('users.index')); ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?php echo e($totalBK); ?></h3>
                <p>Sesi BK</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
            <a href="<?php echo e(route('bk.index')); ?>" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-md-6">
        <div class="card" style="min-height: 500px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Statistik Pelanggaran & Prestasi (Tahun <?php echo e(date('Y')); ?>)
                </h3>
            </div>
            <div class="card-body" style="height: 450px;">
                <canvas id="statistikChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card" style="min-height: 500px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-1"></i>
                    Rekapitulasi Bulanan
                </h3>
            </div>
            <div class="card-body p-0" style="height: 450px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="thead-light" style="position: sticky; top: 0; z-index: 10; background: #f8f9fa;">
                            <tr>
                                <th width="25%">Bulan</th>
                                <th width="25%" class="text-center">Pelanggaran</th>
                                <th width="25%" class="text-center">Prestasi</th>
                                <th width="25%" class="text-center">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                $totalPelanggaranTahun = 0;
                                $totalPrestasiTahun = 0;
                            ?>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <?php
                                    $jmlPelanggaran = $pelanggaranPerBulan[$i] ?? 0;
                                    $jmlPrestasi = $prestasiPerBulan[$i] ?? 0;
                                    $selisih = $jmlPrestasi - $jmlPelanggaran;
                                    $totalPelanggaranTahun += $jmlPelanggaran;
                                    $totalPrestasiTahun += $jmlPrestasi;
                                ?>
                                <tr>
                                    <td><strong><?php echo e($bulanNama[$i-1]); ?></strong></td>
                                    <td class="text-center">
                                        <span class="badge badge-danger"><?php echo e($jmlPelanggaran); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success"><?php echo e($jmlPrestasi); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php if($selisih > 0): ?>
                                            <span class="badge badge-success">+<?php echo e($selisih); ?></span>
                                        <?php elseif($selisih < 0): ?>
                                            <span class="badge badge-danger"><?php echo e($selisih); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">0</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                        <tfoot class="thead-light" style="position: sticky; bottom: 0; z-index: 10; background: #f8f9fa;">
                            <tr>
                                <th>Total</th>
                                <th class="text-center">
                                    <span class="badge badge-danger" style="font-size: 13px;"><?php echo e($totalPelanggaranTahun); ?></span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success" style="font-size: 13px;"><?php echo e($totalPrestasiTahun); ?></span>
                                </th>
                                <th class="text-center">
                                    <?php $totalSelisih = $totalPrestasiTahun - $totalPelanggaranTahun; ?>
                                    <?php if($totalSelisih > 0): ?>
                                        <span class="badge badge-success" style="font-size: 13px;">+<?php echo e($totalSelisih); ?></span>
                                    <?php elseif($totalSelisih < 0): ?>
                                        <span class="badge badge-danger" style="font-size: 13px;"><?php echo e($totalSelisih); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary" style="font-size: 13px;">0</span>
                                    <?php endif; ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row">
    <!-- Top Students -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Top 10 Siswa Paling Aktif (Prestasi & Pelanggaran)
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">NIS</th>
                                <th width="25%">Nama Siswa</th>
                                <th width="12%">Kelas</th>
                                <th width="12%" class="text-center">Prestasi</th>
                                <th width="12%" class="text-center">Pelanggaran</th>
                                <th width="14%" class="text-center">Total Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($index + 1); ?></strong></td>
                                <td><code><?php echo e($siswa->nis); ?></code></td>
                                <td><strong><?php echo e($siswa->nama_siswa); ?></strong></td>
                                <td>
                                    <span class="badge badge-info"><?php echo e($siswa->kelas->nama_kelas ?? 'N/A'); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success" style="font-size: 14px; padding: 5px 10px;">
                                        <i class="fas fa-trophy"></i> <?php echo e($siswa->prestasis_count); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger" style="font-size: 14px; padding: 5px 10px;">
                                        <i class="fas fa-exclamation-triangle"></i> <?php echo e($siswa->pelanggarans_count); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary" style="font-size: 14px; padding: 5px 10px;">
                                        <?php echo e($siswa->total_aktivitas); ?> aktivitas
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle"></i> Belum ada data siswa dengan aktivitas
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Chart untuk statistik bulanan
const ctx1 = document.getElementById('statistikChart');
if (ctx1) {
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pelanggaran',
                data: [
                    <?php echo e($pelanggaranPerBulan[1] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[2] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[3] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[4] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[5] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[6] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[7] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[8] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[9] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[10] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[11] ?? 0); ?>,
                    <?php echo e($pelanggaranPerBulan[12] ?? 0); ?>

                ],
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Prestasi',
                data: [
                    <?php echo e($prestasiPerBulan[1] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[2] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[3] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[4] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[5] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[6] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[7] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[8] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[9] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[10] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[11] ?? 0); ?>,
                    <?php echo e($prestasiPerBulan[12] ?? 0); ?>

                ],
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>