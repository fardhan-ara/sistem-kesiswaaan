<?php $__env->startSection('title', 'Data Pelanggaran'); ?>
<?php $__env->startSection('page-title', 'Data Pelanggaran'); ?>

<?php $__env->startPush('styles'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistik Cards -->
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo e($statistik['total']); ?></h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon"><i class="fas fa-list"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo e($statistik['menunggu']); ?></h3>
                <p>Menunggu Verifikasi</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo e($statistik['terverifikasi']); ?></h3>
                <p>Terverifikasi</p>
            </div>
            <div class="icon"><i class="fas fa-check"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo e($statistik['ditolak']); ?></h3>
                <p>Ditolak</p>
            </div>
            <div class="icon"><i class="fas fa-times"></i></div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Trend Pelanggaran (6 Bulan Terakhir)</h3>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Top 5 Jenis Pelanggaran</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <?php $__currentLoopData = $topJenisPelanggaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td width="5%"><?php echo e($index + 1); ?></td>
                                <td><?php echo e($jenis->nama_pelanggaran); ?></td>
                                <td width="20%" class="text-right">
                                    <span class="badge badge-primary"><?php echo e($jenis->total); ?></span>
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
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pelanggaran</h3>
        <div class="card-tools">
            <a href="<?php echo e(route('pelanggaran.create')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pelanggaran
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu" <?php echo e(request('status') == 'menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                        <option value="terverifikasi" <?php echo e(request('status') == 'terverifikasi' ? 'selected' : ''); ?>>Terverifikasi</option>
                        <option value="ditolak" <?php echo e(request('status') == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-control form-control-sm">
                        <option value="">Semua Kategori</option>
                        <option value="ringan" <?php echo e(request('kategori') == 'ringan' ? 'selected' : ''); ?>>Ringan</option>
                        <option value="sedang" <?php echo e(request('kategori') == 'sedang' ? 'selected' : ''); ?>>Sedang</option>
                        <option value="berat" <?php echo e(request('kategori') == 'berat' ? 'selected' : ''); ?>>Berat</option>
                        <option value="sangat_berat" <?php echo e(request('kategori') == 'sangat_berat' ? 'selected' : ''); ?>>Sangat Berat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="siswa" class="form-control form-control-sm" placeholder="Cari nama siswa..." value="<?php echo e(request('siswa')); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="<?php echo e(route('pelanggaran.index')); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Siswa</th>
                        <th style="width: 30%;">Jenis Pelanggaran</th>
                        <th style="width: 8%;">Kategori</th>
                        <th style="width: 7%;">Poin</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Tanggal</th>
                        <th class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pelanggarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pelanggaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($pelanggarans->firstItem() + $index); ?></td>
                        <td><?php echo e($pelanggaran->siswa->nama_siswa ?? 'N/A'); ?></td>
                        <td class="text-wrap">
                            <?php
                                $pelanggaranList = [];
                                if ($pelanggaran->pelanggaran_list) {
                                    $pelanggaranList = json_decode($pelanggaran->pelanggaran_list, true);
                                }
                            ?>
                            
                            <!-- Pelanggaran utama -->
                            <?php if($pelanggaran->jenisPelanggaran): ?>
                                <div><small>• <?php echo e($pelanggaran->jenisPelanggaran->nama_pelanggaran); ?> (<?php echo e($pelanggaran->jenisPelanggaran->poin); ?> poin)</small></div>
                            <?php endif; ?>
                            
                            <!-- Pelanggaran tambahan -->
                            <?php if(is_array($pelanggaranList) && count($pelanggaranList) > 0): ?>
                                <?php $__currentLoopData = $pelanggaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><small>• <?php echo e($item['nama']); ?> (<?php echo e($item['poin']); ?> poin)</small></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $kategori = $pelanggaran->jenisPelanggaran->kategori ?? '';
                                $badgeClass = match($kategori) {
                                    'ringan' => 'success',
                                    'sedang' => 'warning',
                                    'berat' => 'danger',
                                    'sangat_berat' => 'dark',
                                    default => 'secondary'
                                };
                            ?>
                            <span class="badge badge-<?php echo e($badgeClass); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $kategori))); ?></span>
                        </td>
                        <td><strong><?php echo e($pelanggaran->poin); ?></strong></td>
                        <td>
                            <?php switch($pelanggaran->status_verifikasi):
                                case ('diverifikasi'): ?>
                                <?php case ('terverifikasi'): ?>
                                <?php case ('verified'): ?>
                                    <span class="badge badge-success">Terverifikasi</span>
                                    <?php break; ?>
                                <?php case ('ditolak'): ?>
                                <?php case ('rejected'): ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                    <?php break; ?>
                                <?php default: ?>
                                    <span class="badge badge-warning">Menunggu</span>
                            <?php endswitch; ?>
                        </td>
                        <td><?php echo e($pelanggaran->tanggal_pelanggaran ? \Carbon\Carbon::parse($pelanggaran->tanggal_pelanggaran)->format('d/m/Y') : $pelanggaran->created_at->format('d/m/Y')); ?></td>
                        <td class="action-column">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-info" onclick="viewDetail(<?php echo e($pelanggaran->id); ?>)" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="<?php echo e(route('pelanggaran.edit', $pelanggaran->id)); ?>" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="/pelanggaran-delete-test/<?php echo e($pelanggaran->id); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pelanggaran ini?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <?php if(in_array(auth()->user()->role, ['admin', 'kesiswaan']) && $pelanggaran->status_verifikasi == 'menunggu'): ?>
                                <div class="btn-group btn-group-sm mt-1" role="group">
                                    <form action="<?php echo e(route('pelanggaran.verify', $pelanggaran)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-success" title="Setuju">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <button onclick="rejectPelanggaran(<?php echo e($pelanggaran->id); ?>)" class="btn btn-secondary" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr id="noData">
                        <td colspan="8" class="text-center">Tidak ada data pelanggaran</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($pelanggarans->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($pelanggarans->links()); ?>

    </div>
    <?php endif; ?>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelanggaran</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Trend Chart
const trendCtx = document.getElementById('trendChart');
if (trendCtx) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartLabels); ?>,
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: <?php echo json_encode($chartData); ?>,
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
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

$(document).ready(function() {
    // Fix untuk viewDetail function
    window.viewDetail = function(id) {
        $.get('/pelanggaran/' + id, function(data) {
            $('#detailContent').html(data);
            $('#detailModal').modal('show');
        }).fail(function() {
            $('#detailContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
            $('#detailModal').modal('show');
        });
    };
});

function rejectPelanggaran(id) {
    Swal.fire({
        title: 'Tolak Pelanggaran?',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value) return 'Alasan penolakan harus diisi!';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/pelanggaran/' + id + '/reject';
            form.innerHTML = '<?php echo csrf_field(); ?><input type="hidden" name="alasan_penolakan" value="' + result.value + '">';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/pelanggaran/index.blade.php ENDPATH**/ ?>