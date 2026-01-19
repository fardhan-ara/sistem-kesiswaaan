<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - Sistem Kesiswaan</title>

    <!-- Preload Critical Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
    <!-- Font Awesome with Fallback -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          onerror="this.onerror=null;this.href='https://use.fontawesome.com/releases/v6.4.0/css/all.css';">
    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        .brand-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 10px !important;
        }
        .brand-link .brand-image {
            display: block;
            margin: 0 auto;
        }
        .sidebar-dark-primary {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }
        .main-sidebar {
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .content-wrapper {
            background: #f4f6f9;
        }
        .small-box {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .small-box:hover {
            transform: translateY(-5px);
            transition: all 0.3s;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .btn {
            border-radius: 5px;
        }
        
        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table {
            min-width: 100%;
            font-size: 0.9rem;
        }
        .table td, .table th {
            padding: 0.5rem;
            vertical-align: middle;
            white-space: nowrap;
        }
        .table td.text-wrap, .table th.text-wrap {
            white-space: normal;
            word-wrap: break-word;
            max-width: 200px;
        }
        
        /* Responsive Card */
        .card-body {
            padding: 1rem;
        }
        @media (max-width: 768px) {
            .card-body {
                padding: 0.75rem;
            }
            .table {
                font-size: 0.8rem;
            }
            .table td, .table th {
                padding: 0.3rem;
            }
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
            }
        }
        
        /* Badge Responsive */
        .badge {
            font-size: 0.75rem;
            padding: 0.25em 0.5em;
        }
        
        /* Button Group Responsive */
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        /* Action Column */
        .action-column {
            min-width: 100px;
            text-align: center;
        }
        
        /* Pagination Responsive */
        .pagination {
            flex-wrap: wrap;
        }
        @media (max-width: 576px) {
            .pagination {
                font-size: 0.8rem;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <?php if(auth()->guard()->check()): ?>
            <!-- Notifikasi Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <?php if(Auth::user()->unreadNotifications->count() > 0): ?>
                        <span class="badge badge-danger navbar-badge"><?php echo e(Auth::user()->unreadNotifications->count()); ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header"><?php echo e(Auth::user()->unreadNotifications->count()); ?> Notifikasi Baru</span>
                    <div class="dropdown-divider"></div>
                    
                    <?php $__empty_1 = true; $__currentLoopData = Auth::user()->unreadNotifications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $data = $notification->data; ?>
                        <div class="dropdown-item" style="cursor: pointer;" onclick="document.getElementById('mark-read-<?php echo e($notification->id); ?>').submit();">
                            <i class="fas fa-bell mr-2"></i> <?php echo e($data['title']); ?>

                            <span class="float-right text-muted text-sm">
                                <?php echo e(\Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?>

                            </span>
                        </div>
                        <form id="mark-read-<?php echo e($notification->id); ?>" action="<?php echo e(route('notifications.read', $notification->id)); ?>" method="POST" class="d-none">
                            <?php echo csrf_field(); ?>
                        </form>
                        <div class="dropdown-divider"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <span class="dropdown-item text-muted">Tidak ada notifikasi baru</span>
                        <div class="dropdown-divider"></div>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('notifications.index')); ?>" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                </div>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> <?php echo e(optional(auth()->user())->nama); ?>

                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <?php if(auth()->user()->role !== 'admin'): ?>
                    <a href="<?php echo e(route('profile.index')); ?>" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <?php endif; ?>
                    <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
            <?php else: ?>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Login</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?php echo e(route('dashboard')); ?>" class="brand-link text-center">
            <span class="brand-text font-weight-light d-block" style="font-size: 18px; font-weight: bold;">SIKAP</span>
            <span class="brand-text font-weight-light d-block" style="font-size: 11px;">Sistem Informasi Kesiswaan</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <?php if(auth()->check() && auth()->user()->role === 'ortu'): ?>
                    <li class="nav-header">DATA ANAK</li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('ortu.profil')); ?>" class="nav-link <?php echo e(request()->routeIs('ortu.profil') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Profil Anak</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('ortu.pelanggaran')); ?>" class="nav-link <?php echo e(request()->routeIs('ortu.pelanggaran') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('ortu.prestasi')); ?>" class="nav-link <?php echo e(request()->routeIs('ortu.prestasi') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Prestasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('ortu.sanksi')); ?>" class="nav-link <?php echo e(request()->routeIs('ortu.sanksi') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Sanksi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('ortu.bimbingan')); ?>" class="nav-link <?php echo e(request()->routeIs('ortu.bimbingan') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Bimbingan Konseling</p>
                        </a>
                    </li>
                    
                    <li class="nav-header">KOMUNIKASI</li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('komunikasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('komunikasi.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Komunikasi Sekolah</p>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(auth()->check() && auth()->user()->role === 'bk'): ?>
                    <li class="nav-header">BIMBINGAN KONSELING</li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('bk.index')); ?>" class="nav-link <?php echo e(request()->routeIs('bk.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Sesi BK</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('siswa.index')); ?>" class="nav-link <?php echo e(request()->routeIs('siswa.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Data Siswa</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('pelanggaran.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pelanggaran.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Pelanggaran Siswa</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('prestasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('prestasi.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Prestasi Siswa</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('sanksi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('sanksi.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Sanksi</p>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'kepala_sekolah'])): ?>
                    <li class="nav-header">DATA MASTER</li>
                    
                    <?php if(in_array(auth()->user()->role, ['admin', 'kesiswaan'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('siswa.index')); ?>" class="nav-link <?php echo e(request()->routeIs('siswa.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Data Siswa</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('guru.index')); ?>" class="nav-link <?php echo e(request()->routeIs('guru.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Data Guru</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('kelas.index')); ?>" class="nav-link <?php echo e(request()->routeIs('kelas.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Data Kelas</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('tahun-ajaran.index')); ?>" class="nav-link <?php echo e(request()->routeIs('tahun-ajaran.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Tahun Ajaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('jenis-pelanggaran.index')); ?>" class="nav-link <?php echo e(request()->routeIs('jenis-pelanggaran.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Jenis Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('jenis-prestasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('jenis-prestasi.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-star"></i>
                            <p>Jenis Prestasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('biodata-ortu.index')); ?>" class="nav-link <?php echo e(request()->routeIs('biodata-ortu.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Orang Tua</p>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-header">KELOLA</li>
                    
                    <?php if(in_array(auth()->user()->role, ['admin', 'kesiswaan'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('pelanggaran.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pelanggaran.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('prestasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('prestasi.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Prestasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('sanksi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('sanksi.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Sanksi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('bk.index')); ?>" class="nav-link <?php echo e(request()->routeIs('bk.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Bimbingan Konseling</p>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'kepala_sekolah'])): ?>
                    <li class="nav-header">LAPORAN</li>
                    
                    <?php if(auth()->user()->role === 'kepala_sekolah'): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.monitoring-pelanggaran')); ?>" class="nav-link <?php echo e(request()->routeIs('kepala-sekolah.monitoring-pelanggaran') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-exclamation-circle"></i>
                            <p>Monitoring Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.monitoring-sanksi')); ?>" class="nav-link <?php echo e(request()->routeIs('kepala-sekolah.monitoring-sanksi') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Monitoring Sanksi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.monitoring-prestasi')); ?>" class="nav-link <?php echo e(request()->routeIs('kepala-sekolah.monitoring-prestasi') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Monitoring Prestasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('kepala-sekolah.laporan-executive')); ?>" class="nav-link <?php echo e(request()->routeIs('kepala-sekolah.laporan-executive') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Executive</p>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(in_array(auth()->user()->role, ['admin', 'kesiswaan'])): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('laporan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('laporan.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>Export Laporan</p>
                        </a>
                    </li>
                    <?php endif; ?>
                    

                    <?php endif; ?>

                    <?php if(auth()->check() && auth()->user()->role === 'admin'): ?>
                    <li class="nav-header">SYSTEM</li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.role-management')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.role-management*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Role Management</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('notifications.create')); ?>" class="nav-link <?php echo e(request()->routeIs('notifications.create') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-paper-plane"></i>
                            <p>Kirim Notifikasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('backup.index')); ?>" class="nav-link <?php echo e(request()->routeIs('backup.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-database"></i>
                            <p>Backup System</p>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Home</a></li>
                            <li class="breadcrumb-item active"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-check"></i> <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-ban"></i> <?php echo e(session('error')); ?>

                </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2025 <a href="#">SIKAP - Sistem Informasi Kesiswaan dan Prestasi</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
    <?php echo csrf_field(); ?>
</form>

<!-- jQuery with Fallback -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" 
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
        crossorigin="anonymous"
        onerror="this.onerror=null;this.src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js';"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Refresh CSRF token setiap 5 menit
setInterval(function() {
    fetch('/refresh-csrf', {
        method: 'GET',
        credentials: 'same-origin'
    }).then(response => response.json())
    .then(data => {
        if (data.token) {
            $('meta[name="csrf-token"]').attr('content', data.token);
            $('input[name="_token"]').val(data.token);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': data.token
                }
            });
        }
    }).catch(function(error) {
        console.log('CSRF refresh failed:', error);
    });
}, 300000); // 5 menit

// Handle AJAX error 419
$(document).ajaxError(function(event, jqxhr) {
    if (jqxhr.status === 419) {
        Swal.fire({
            icon: 'warning',
            title: 'Sesi Berakhir',
            text: 'Halaman akan dimuat ulang',
            timer: 2000,
            showConfirmButton: false
        }).then(function() {
            location.reload();
        });
    }
});

// Prevent double submit
var formSubmitting = false;
$('form').on('submit', function(e) {
    if (formSubmitting) {
        e.preventDefault();
        return false;
    }
    formSubmitting = true;
    setTimeout(function() {
        formSubmitting = false;
    }, 3000);
});

// Prevent browser auto-fill for email and password fields
$(document).ready(function() {
    // Add autocomplete=off to forms that shouldn't be auto-filled
    $('form').each(function() {
        const form = $(this);
        const hasEmailOrPassword = form.find('input[type="email"], input[type="password"]').length > 0;
        
        if (hasEmailOrPassword) {
            // Add autocomplete=off to the form itself
            form.attr('autocomplete', 'off');
            
            // Clear auto-filled values immediately
            form.find('input[type="email"], input[type="password"]').each(function() {
                const input = $(this);
                if (input.val() === 'admin@test.com' || input.val() === 'password') {
                    input.val('');
                }
            });
        }
    });
    
    // Clear auto-filled values on page load with delay
    setTimeout(function() {
        $('input[type="email"], input[type="password"]').each(function() {
            const input = $(this);
            if (input.val() === 'admin@test.com' || input.val() === 'password') {
                input.val('');
                input.trigger('change');
            }
        });
    }, 500);
    
    // Monitor for auto-fill changes with more aggressive clearing
    $('input[type="email"], input[type="password"]').on('input change keyup paste', function() {
        const input = $(this);
        if (input.val() === 'admin@test.com' || input.val() === 'password') {
            setTimeout(function() {
                input.val('');
            }, 10);
        }
    });
    
    // Prevent browser from storing form data
    $('form').on('submit', function() {
        $(this).find('input[type="email"], input[type="password"]').each(function() {
            $(this).attr('autocomplete', 'off');
        });
    });
});
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\sistem-kesiswaan\resources\views/layouts/app.blade.php ENDPATH**/ ?>