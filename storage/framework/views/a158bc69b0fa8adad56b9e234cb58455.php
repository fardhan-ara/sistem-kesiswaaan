<?php $__env->startSection('title', 'Dashboard Executive'); ?>
<?php $__env->startSection('page-title', 'Dashboard Executive - Kepala Sekolah'); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e($overview['total_siswa']); ?></h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e($overview['total_pelanggaran']); ?></h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e($overview['total_prestasi']); ?></h3>
                <p>Total Prestasi</p>
            </div>
            <div class="icon"><i class="fas fa-trophy"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e($overview['sanksi_aktif']); ?></h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon"><i class="fas fa-gavel"></i></div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Efektivitas Sanksi</span>
                <span class="info-box-number"><?php echo e($kpi['efektivitas_sanksi']); ?>%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tingkat Disiplin</span>
                <span class="info-box-number"><?php echo e($kpi['tingkat_disiplin']); ?>%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-star"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rasio Prestasi</span>
                <span class="info-box-number"><?php echo e($kpi['rasio_prestasi']); ?>%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon 
                <?php if($kpi['trend_pelanggaran'] == 'naik'): ?> bg-danger
                <?php elseif($kpi['trend_pelanggaran'] == 'turun'): ?> bg-success
                <?php else: ?> bg-secondary
                <?php endif; ?>">
                <i class="fas 
                    <?php if($kpi['trend_pelanggaran'] == 'naik'): ?> fa-arrow-up
                    <?php elseif($kpi['trend_pelanggaran'] == 'turun'): ?> fa-arrow-down
                    <?php else: ?> fa-minus
                    <?php endif; ?>"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Trend Pelanggaran</span>
                <span class="info-box-number"><?php echo e(ucfirst($kpi['trend_pelanggaran'])); ?></span>
            </div>
        </div>
    </div>
</div>


<?php if(count($alerts) > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bell"></i> Alert & Notification</h3>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="alert alert-<?php echo e($alert['type']); ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-<?php echo e($alert['icon']); ?>"></i> Alert!</h5>
                    <?php echo e($alert['message']); ?>

                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Grafik Trend 12 Bulan</h3>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-link"></i> Menu Cepat</h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.monitoring-pelanggaran')); ?>" class="nav-link">
                            <i class="fas fa-exclamation-circle"></i> Monitoring Pelanggaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.monitoring-sanksi')); ?>" class="nav-link">
                            <i class="fas fa-gavel"></i> Monitoring Sanksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.monitoring-prestasi')); ?>" class="nav-link">
                            <i class="fas fa-trophy"></i> Monitoring Prestasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.laporan-executive')); ?>" class="nav-link">
                            <i class="fas fa-file-alt"></i> Laporan Executive
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trendChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($trendBulanan, 'bulan')); ?>,
        datasets: [{
            label: 'Pelanggaran',
            data: <?php echo json_encode(array_column($trendBulanan, 'pelanggaran')); ?>,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
        }, {
            label: 'Prestasi',
            data: <?php echo json_encode(array_column($trendBulanan, 'prestasi')); ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/kepala-sekolah/dashboard.blade.php ENDPATH**/ ?>