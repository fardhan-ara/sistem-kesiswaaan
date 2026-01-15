<?php $__env->startSection('title', 'Profil Saya'); ?>
<?php $__env->startSection('page-title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <?php if($user->foto): ?>
                        <img class="profile-user-img img-fluid img-circle" src="<?php echo e(asset('storage/' . $user->foto)); ?>" alt="Foto Profil" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->nama)); ?>&background=667eea&color=fff&size=128'">
                    <?php else: ?>
                        <img class="profile-user-img img-fluid img-circle" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->nama)); ?>&background=667eea&color=fff&size=128" alt="Foto Profil">
                    <?php endif; ?>
                </div>

                <h3 class="profile-username text-center"><?php echo e($user->nama); ?></h3>

                <p class="text-muted text-center">
                    <?php switch($user->role):
                        case ('siswa'): ?> <span class="badge badge-info">Siswa</span> <?php break; ?>
                        <?php case ('guru'): ?> <span class="badge badge-success">Guru</span> <?php break; ?>
                        <?php case ('wali_kelas'): ?> <span class="badge badge-primary">Wali Kelas</span> <?php break; ?>
                        <?php case ('bk'): ?> <span class="badge badge-warning">BK</span> <?php break; ?>
                        <?php case ('ortu'): ?> <span class="badge badge-secondary">Orang Tua</span> <?php break; ?>
                        <?php case ('kesiswaan'): ?> <span class="badge badge-info">Kesiswaan</span> <?php break; ?>
                        <?php case ('kepala_sekolah'): ?> <span class="badge badge-danger">z Sekolah</span> <?php break; ?>
                    <?php endswitch; ?>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right"><?php echo e($user->email); ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>No. Telepon</b> <a class="float-right"><?php echo e($user->no_telp ?? '-'); ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <span class="float-right">
                            <?php if($user->status === 'approved'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php elseif($user->status === 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Ditolak</span>
                            <?php endif; ?>
                        </span>
                    </li>
                </ul>

                <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Edit Profil</a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <?php if($profileData['type'] === 'siswa' && $profileData['data']): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-graduate"></i> Data Siswa</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">NIS</th>
                            <td><?php echo e($profileData['data']->nis); ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td><?php echo e($profileData['data']->nama_siswa); ?></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td><?php echo e($profileData['data']->kelas->nama_kelas ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <td><?php echo e($profileData['data']->tahunAjaran->tahun_ajaran ?? '-'); ?> (<?php echo e($profileData['data']->tahunAjaran->semester ?? '-'); ?>)</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?php echo e($profileData['data']->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pelanggaran</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_pelanggaran']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Prestasi</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_prestasi']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Poin</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_poin']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif($profileData['type'] === 'guru' && $profileData['data']): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chalkboard-teacher"></i> Data Guru</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">NIP</th>
                            <td><?php echo e($profileData['data']->nip); ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td><?php echo e($profileData['data']->nama_guru); ?></td>
                        </tr>
                        <tr>
                            <th>Bidang Studi</th>
                            <td><?php echo e($profileData['data']->bidang_studi ?? '-'); ?></td>
                        </tr>
                        <?php if($profileData['kelas_wali']): ?>
                        <tr>
                            <th>Wali Kelas</th>
                            <td><span class="badge badge-primary"><?php echo e($profileData['kelas_wali']->nama_kelas); ?></span></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if($profileData['data']->status === 'aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pelanggaran Dicatat</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_pelanggaran']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-award"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Prestasi Dicatat</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_prestasi']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif($profileData['type'] === 'ortu' && $profileData['data']): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Data Orang Tua</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Ayah</th>
                            <td><?php echo e($profileData['data']->nama_ayah ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Telp Ayah</th>
                            <td><?php echo e($profileData['data']->telp_ayah ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Nama Ibu</th>
                            <td><?php echo e($profileData['data']->nama_ibu ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Telp Ibu</th>
                            <td><?php echo e($profileData['data']->telp_ibu ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?php echo e($profileData['data']->alamat ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Status Approval</th>
                            <td>
                                <?php if($profileData['data']->status_approval === 'approved'): ?>
                                    <span class="badge badge-success">Disetujui</span>
                                <?php elseif($profileData['data']->status_approval === 'pending'): ?>
                                    <span class="badge badge-warning">Menunggu</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if($profileData['data']->status_approval === 'approved' && $profileData['data']->siswa): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-child"></i> Data Anak</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">NIS</th>
                            <td><?php echo e($profileData['data']->siswa->nis); ?></td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td><?php echo e($profileData['data']->siswa->nama_siswa); ?></td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td><?php echo e($profileData['data']->siswa->kelas->nama_kelas ?? '-'); ?></td>
                        </tr>
                    </table>

                    <?php if($profileData['stats']): ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pelanggaran</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_pelanggaran']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Prestasi</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_prestasi']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Poin</span>
                                    <span class="info-box-number"><?php echo e($profileData['stats']['total_poin']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        <?php elseif($profileData['type'] === 'staff'): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-tie"></i> Informasi Akun</h3>
                </div>
                <div class="card-body">
                    <p>Anda login sebagai <strong><?php echo e(ucfirst($user->role)); ?></strong></p>
                    <p>Akun Anda aktif dan dapat mengakses sistem sesuai dengan hak akses yang diberikan.</p>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <?php echo e($profileData['message'] ?? 'Data profil tidak ditemukan'); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('info')): ?>
    Swal.fire({
        icon: 'info',
        title: 'Informasi',
        text: '<?php echo e(session('info')); ?>',
        timer: 3000
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/profile/index.blade.php ENDPATH**/ ?>