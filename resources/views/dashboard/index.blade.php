@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>0</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('siswa.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>0</h3>
                <p>Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
            <a href="{{ route('pelanggaran.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>0</h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-gavel"></i>
            </div>
            <a href="{{ route('sanksi.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>0</h3>
                <p>Prestasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-trophy"></i>
            </div>
            <a href="{{ route('prestasi.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Info boxes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Selamat Datang di Sistem Kesiswaan
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Informasi!</h5>
                    Sistem Kesiswaan siap digunakan. Silakan gunakan menu di sidebar untuk navigasi.
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-gradient-info">
                            <span class="info-box-icon"><i class="fas fa-user-graduate"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kelola Siswa</span>
                                <span class="info-box-number">Data Master</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box bg-gradient-danger">
                            <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Input Pelanggaran</span>
                                <span class="info-box-number">Kelola</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Input Prestasi</span>
                                <span class="info-box-number">Kelola</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-file-pdf"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Export Laporan</span>
                                <span class="info-box-number">Reporting</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
