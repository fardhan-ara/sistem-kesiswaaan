<?php $__env->startSection('title', 'Komunikasi dengan Orang Tua'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-comments"></i> Komunikasi & Pembinaan</h2>
        <div>
            <?php if(in_array(Auth::user()->role, ['kesiswaan', 'wali_kelas', 'bk', 'ortu'])): ?>
            <a href="<?php echo e(route('komunikasi.create')); ?>" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Pesan
            </a>
            <?php endif; ?>
            
            <?php if(in_array(Auth::user()->role, ['kesiswaan', 'wali_kelas', 'bk'])): ?>
            <a href="<?php echo e(route('komunikasi.panggilan')); ?>" class="btn btn-warning">
                <i class="fas fa-bell"></i> Panggilan Ortu
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(Auth::user()->role === 'ortu'): ?>
    
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#semua" role="tab">Semua Pesan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#panggilan" role="tab">
                        Panggilan Ortu
                        <?php
                            $biodata = \App\Models\BiodataOrtu::where('user_id', Auth::id())->first();
                            $panggilanMenunggu = $biodata ? \App\Models\PanggilanOrtu::where('siswa_id', $biodata->siswa_id)
                                ->where('status', 'menunggu')->count() : 0;
                        ?>
                        <?php if($panggilanMenunggu > 0): ?>
                        <span class="badge badge-danger"><?php echo e($panggilanMenunggu); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                
                <div class="tab-pane fade show active" id="semua" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50"></th>
                                    <th>Jenis</th>
                                    <th>Subjek</th>
                                    <th>Kepada/Pengirim</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $komunikasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="<?php echo e($k->status === 'terkirim' && $k->penerima_id == Auth::id() ? 'table-warning' : ''); ?>">
                                    <td>
                                        <?php if($k->status === 'terkirim' && $k->penerima_id == Auth::id()): ?>
                                        <i class="fas fa-envelope text-warning"></i>
                                        <?php else: ?>
                                        <i class="fas fa-envelope-open text-muted"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($k->jenis === 'pesan'): ?>
                                        <span class="badge badge-primary">Pesan</span>
                                        <?php elseif($k->jenis === 'laporan_pembinaan'): ?>
                                        <span class="badge badge-info">Laporan Pembinaan</span>
                                        <?php else: ?>
                                        <span class="badge badge-success">Konsultasi</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo e($k->subjek); ?></strong></td>
                                    <td>
                                        <?php if($k->penerima_id == Auth::id()): ?>
                                        Dari: <?php echo e($k->pengirim->nama); ?>

                                        <?php else: ?>
                                        Kepada: <?php echo e($k->penerima->nama); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($k->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <?php if($k->status === 'terkirim'): ?>
                                        <span class="badge badge-secondary">Terkirim</span>
                                        <?php elseif($k->status === 'dibaca'): ?>
                                        <span class="badge badge-info">Dibaca</span>
                                        <?php else: ?>
                                        <span class="badge badge-success">Dibalas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('komunikasi.show', $k->id)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <form action="<?php echo e(route('komunikasi.destroy', $k->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesan ini?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada pesan</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($komunikasis->links()); ?>

                </div>

                
                <div class="tab-pane fade" id="panggilan" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Siswa</th>
                                    <th>Tempat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $panggilan = $biodata ? \App\Models\PanggilanOrtu::where('siswa_id', $biodata->siswa_id)
                                        ->with(['siswa', 'pembuatPanggilan'])
                                        ->orderBy('tanggal_panggilan', 'desc')
                                        ->get() : collect();
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $panggilan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="<?php echo e($p->status === 'menunggu' ? 'table-danger' : ''); ?>">
                                    <td><?php echo e(\Carbon\Carbon::parse($p->tanggal_panggilan)->format('d/m/Y H:i')); ?></td>
                                    <td><strong><?php echo e($p->judul); ?></strong></td>
                                    <td><?php echo e($p->siswa->nama_siswa); ?></td>
                                    <td><?php echo e($p->tempat); ?></td>
                                    <td>
                                        <?php if($p->status === 'menunggu'): ?>
                                        <span class="badge badge-danger">Menunggu Konfirmasi</span>
                                        <?php elseif($p->status === 'dikonfirmasi'): ?>
                                        <span class="badge badge-warning">Dikonfirmasi</span>
                                        <?php else: ?>
                                        <span class="badge badge-success">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($p->status === 'menunggu'): ?>
                                        <button class="btn btn-sm btn-success" onclick="if(confirm('Konfirmasi kehadiran?')) document.getElementById('konfirmasi-<?php echo e($p->id); ?>').submit();">
                                            <i class="fas fa-check"></i> Konfirmasi
                                        </button>
                                        <form id="konfirmasi-<?php echo e($p->id); ?>" action="<?php echo e(route('komunikasi.konfirmasi-panggilan', $p->id)); ?>" method="POST" class="d-none">
                                            <?php echo csrf_field(); ?>
                                        </form>
                                        <?php else: ?>
                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailPanggilan<?php echo e($p->id); ?>">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                
                                
                                <div class="modal fade" id="detailPanggilan<?php echo e($p->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Panggilan</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Judul:</strong> <?php echo e($p->judul); ?></p>
                                                <p><strong>Siswa:</strong> <?php echo e($p->siswa->nama_siswa); ?></p>
                                                <p><strong>Tanggal:</strong> <?php echo e(\Carbon\Carbon::parse($p->tanggal_panggilan)->format('d/m/Y H:i')); ?></p>
                                                <p><strong>Tempat:</strong> <?php echo e($p->tempat); ?></p>
                                                <p><strong>Keterangan:</strong><br><?php echo e($p->keterangan); ?></p>
                                                <?php if($p->catatan_hasil): ?>
                                                <hr>
                                                <p><strong>Catatan Hasil:</strong><br><?php echo e($p->catatan_hasil); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada panggilan</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50"></th>
                            <th>Jenis</th>
                            <th>Subjek</th>
                            <th>Siswa</th>
                            <th>Kepada</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $komunikasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="<?php echo e($k->status === 'terkirim' && $k->penerima_id == Auth::id() ? 'table-warning' : ''); ?>">
                            <td>
                                <?php if($k->status === 'terkirim' && $k->penerima_id == Auth::id()): ?>
                                <i class="fas fa-envelope text-warning"></i>
                                <?php else: ?>
                                <i class="fas fa-envelope-open text-muted"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($k->jenis === 'pesan'): ?>
                                <span class="badge badge-primary">Pesan</span>
                                <?php elseif($k->jenis === 'laporan_pembinaan'): ?>
                                <span class="badge badge-info">Laporan Pembinaan</span>
                                <?php else: ?>
                                <span class="badge badge-success">Konsultasi</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo e($k->subjek); ?></strong></td>
                            <td><?php echo e($k->siswa->nama_siswa); ?></td>
                            <td><?php echo e($k->penerima->nama); ?></td>
                            <td><?php echo e($k->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <?php if($k->status === 'terkirim'): ?>
                                <span class="badge badge-secondary">Terkirim</span>
                                <?php elseif($k->status === 'dibaca'): ?>
                                <span class="badge badge-info">Dibaca</span>
                                <?php else: ?>
                                <span class="badge badge-success">Dibalas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('komunikasi.show', $k->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <form action="<?php echo e(route('komunikasi.destroy', $k->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus pesan ini?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pesan</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($komunikasis->links()); ?>

        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/komunikasi/index.blade.php ENDPATH**/ ?>