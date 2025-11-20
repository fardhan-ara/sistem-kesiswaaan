@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('content')
<!-- Welcome Card -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check"></i> Selamat Datang, {{ optional(auth()->user())->nama }}!</h5>
                    Anda login sebagai <strong>{{ ucfirst(optional(auth()->user())->role) }}</strong>. 
                    Gunakan menu di sidebar untuk mengelola pelanggaran dan prestasi siswa.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalPelanggaran }}</h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
            <a href="{{ route('pelanggaran.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalPrestasi }}</h3>
                <p>Total Prestasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-trophy"></i>
            </div>
            <a href="{{ route('prestasi.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pelanggaranBulanIni }}</h3>
                <p>Pelanggaran Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $prestasiBulanIni }}</h3>
                <p>Prestasi Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('pelanggaran.create') }}" class="btn btn-danger btn-block btn-lg">
                            <i class="fas fa-plus"></i><br>
                            Input Pelanggaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('prestasi.create') }}" class="btn btn-success btn-block btn-lg">
                            <i class="fas fa-plus"></i><br>
                            Input Prestasi
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('pelanggaran.index') }}" class="btn btn-warning btn-block btn-lg">
                            <i class="fas fa-list"></i><br>
                            Lihat Pelanggaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('prestasi.index') }}" class="btn btn-info btn-block btn-lg">
                            <i class="fas fa-list"></i><br>
                            Lihat Prestasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Cards -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Panduan Guru
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Input pelanggaran siswa dengan detail yang jelas</li>
                    <li><i class="fas fa-check text-success"></i> Catat prestasi siswa untuk apresiasi</li>
                    <li><i class="fas fa-check text-success"></i> Pantau perkembangan siswa secara berkala</li>
                    <li><i class="fas fa-check text-success"></i> Koordinasi dengan wali kelas untuk tindak lanjut</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Catatan Penting
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        <li>Pelanggaran dan prestasi perlu diverifikasi oleh kesiswaan</li>
                        <li>Pastikan data yang diinput akurat dan lengkap</li>
                        <li>Sanksi otomatis akan dibuat jika poin pelanggaran ≥ 100</li>
                        <li>Hubungi admin jika ada kendala sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection