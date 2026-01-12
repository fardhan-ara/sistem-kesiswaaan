<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistem Kesiswaan</title>

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
    
    @stack('styles')
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
                <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @auth
            <!-- Notifikasi Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="badge badge-danger navbar-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ Auth::user()->unreadNotifications->count() }} Notifikasi Baru</span>
                    <div class="dropdown-divider"></div>
                    
                    @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                        @php $data = $notification->data; @endphp
                        <div class="dropdown-item" style="cursor: pointer;" onclick="document.getElementById('mark-read-{{ $notification->id }}').submit();">
                            <i class="fas fa-bell mr-2"></i> {{ $data['title'] }}
                            <span class="float-right text-muted text-sm">
                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        <form id="mark-read-{{ $notification->id }}" action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <div class="dropdown-divider"></div>
                    @empty
                        <span class="dropdown-item text-muted">Tidak ada notifikasi baru</span>
                        <div class="dropdown-divider"></div>
                    @endforelse
                    
                    <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                </div>
            </li>
            
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> {{ optional(auth()->user())->nama }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @if(auth()->user()->role !== 'admin')
                    <a href="{{ route('profile.index') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    @endif
                    <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
            @else
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('login') }}" class="nav-link">Login</a>
            </li>
            @endauth
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <span class="brand-text font-weight-light d-block" style="font-size: 18px; font-weight: bold;">SIKAP</span>
            <span class="brand-text font-weight-light d-block" style="font-size: 11px;">Sistem Informasi Kesiswaan</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'kepala_sekolah']))
                    <li class="nav-header">DATA MASTER</li>
                    
                    <li class="nav-item">
                        <a href="/siswa" class="nav-link {{ request()->is('siswa*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Siswa</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('kelas.index') }}" class="nav-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Kelas</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('guru.index') }}" class="nav-link {{ request()->routeIs('guru.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Guru</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('jenis-pelanggaran.index') }}" class="nav-link {{ request()->routeIs('jenis-pelanggaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Jenis Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('jenis-prestasi.index') }}" class="nav-link {{ request()->routeIs('jenis-prestasi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-medal"></i>
                            <p>Jenis Prestasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('tahun-ajaran.index') }}" class="nav-link {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Tahun Ajaran</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'guru', 'wali_kelas', 'bk', 'kepala_sekolah']))
                    <li class="nav-header">KELOLA</li>
                    
                    <li class="nav-item">
                        <a href="/pelanggaran" class="nav-link {{ request()->is('pelanggaran*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ban"></i>
                            <p>Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="/prestasi" class="nav-link {{ request()->is('prestasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Prestasi</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'kepala_sekolah']))
                    <li class="nav-item">
                        <a href="{{ route('sanksi.index') }}" class="nav-link {{ request()->routeIs('sanksi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Sanksi</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'wali_kelas', 'bk', 'kepala_sekolah']))
                    <li class="nav-item">
                        <a href="{{ route('bk.index') }}" class="nav-link {{ request()->routeIs('bk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Bimbingan Konseling</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && auth()->user()->is_wali_kelas)
                    <li class="nav-header">WALI KELAS</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('wali-kelas.dashboard') }}" class="nav-link {{ request()->routeIs('wali-kelas.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Dashboard Kelas</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('wali-kelas.siswa') }}" class="nav-link {{ request()->routeIs('wali-kelas.siswa*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Siswa Kelas</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('wali-kelas.pelanggaran.create') }}" class="nav-link {{ request()->routeIs('wali-kelas.pelanggaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ban"></i>
                            <p>Input Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('wali-kelas.prestasi.create') }}" class="nav-link {{ request()->routeIs('wali-kelas.prestasi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-medal"></i>
                            <p>Input Prestasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('wali-kelas.komunikasi') }}" class="nav-link {{ request()->routeIs('wali-kelas.komunikasi') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Komunikasi Ortu</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['kesiswaan', 'wali_kelas', 'bk']))
                    <li class="nav-item">
                        <a href="{{ route('komunikasi.index') }}" class="nav-link {{ request()->routeIs('komunikasi.*') && !request()->is('komunikasi/panggilan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Pesan & Pembinaan</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('komunikasi.panggilan') }}" class="nav-link {{ request()->is('komunikasi/panggilan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>Panggilan Ortu</p>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->check() && auth()->user()->role === 'ortu')
                    <li class="nav-item">
                        <a href="{{ route('komunikasi.index') }}" class="nav-link {{ request()->routeIs('komunikasi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>Pesan & Pembinaan</p>
                            @php
                                $unreadCount = \App\Models\KomunikasiOrtu::where('penerima_id', auth()->id())
                                    ->where('status', 'terkirim')->count();
                                $panggilanCount = \App\Models\PanggilanOrtu::whereHas('siswa.biodataOrtu', function($q) {
                                    $q->where('user_id', auth()->id());
                                })->where('status', 'menunggu')->count();
                                $totalBadge = $unreadCount + $panggilanCount;
                            @endphp
                            @if($totalBadge > 0)
                            <span class="badge badge-warning right">{{ $totalBadge }}</span>
                            @endif
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'kepala_sekolah']))
                    <li class="nav-header">LAPORAN</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>Export Laporan</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('biodata-ortu.index') }}" class="nav-link {{ request()->routeIs('biodata-ortu.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Approval Biodata Ortu</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-header">SYSTEM</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.role-management') }}" class="nav-link {{ request()->routeIs('admin.role-management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Role Management</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('notifications.create') }}" class="nav-link {{ request()->routeIs('notifications.create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-paper-plane"></i>
                            <p>Kirim Notifikasi</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Manage Users</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('backup.index') }}" class="nav-link {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>Backup System</p>
                        </a>
                    </li>
                    @endif

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
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">@yield('page-title', 'Dashboard')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-ban"></i> {{ session('error') }}
                </div>
                @endif

                @yield('content')
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

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
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
</script>

@stack('scripts')
</body>
</html>
