<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Sistem Kesiswaan</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        .brand-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i> {{ optional(auth()->user())->nama }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
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
            <i class="fas fa-graduation-cap fa-2x"></i>
            <span class="brand-text font-weight-light d-block">Sistem Kesiswaan</span>
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

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan']))
                    <li class="nav-header">DATA MASTER</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('siswa.index') }}" class="nav-link {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
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

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'guru', 'wali_kelas']))
                    <li class="nav-header">KELOLA</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('pelanggaran.index') }}" class="nav-link {{ request()->routeIs('pelanggaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ban"></i>
                            <p>Pelanggaran</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('prestasi.index') }}" class="nav-link {{ request()->routeIs('prestasi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Prestasi</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan']))
                    <li class="nav-item">
                        <a href="{{ route('sanksi.index') }}" class="nav-link {{ request()->routeIs('sanksi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Sanksi</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'wali_kelas']))
                    <li class="nav-item">
                        <a href="{{ route('bk.index') }}" class="nav-link {{ request()->routeIs('bk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>Bimbingan Konseling</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan']))
                    <li class="nav-header">LAPORAN</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>Export Laporan</p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <li class="nav-header">SYSTEM</li>
                    
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
        <strong>Copyright &copy; 2024 <a href="#">Sistem Kesiswaan</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
