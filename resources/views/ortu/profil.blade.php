@extends('layouts.app')

@section('title', 'Profil Anak')
@section('page-title', 'Profil Anak')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate"></i> Data Siswa</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">NIS</th>
                        <td>{{ $siswa->nis }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $siswa->nama_siswa }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <td>{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }} ({{ $siswa->tahunAjaran->semester ?? '-' }})</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $siswa->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistik</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pelanggaran</span>
                                <span class="info-box-number">{{ $stats['total_pelanggaran'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Prestasi</span>
                                <span class="info-box-number">{{ $stats['total_prestasi'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-star"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Poin</span>
                                <span class="info-box-number">{{ $stats['total_poin'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-gavel"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sanksi Aktif</span>
                                <span class="info-box-number">{{ $stats['sanksi_aktif'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-id-card"></i> Data Orang Tua</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Nama Ayah</th>
                        <td>{{ $biodata->nama_ayah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $biodata->nama_ibu ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $biodata->no_telp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $biodata->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
