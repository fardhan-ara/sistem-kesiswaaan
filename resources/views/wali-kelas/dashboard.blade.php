@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-chalkboard-teacher"></i> Dashboard Wali Kelas - {{ $kelas->nama_kelas }}</h2>
        <a href="{{ route('wali-kelas.laporan') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export Laporan Kelas
        </a>
    </div>

    {{-- Statistik Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalSiswa }}</h3>
                    <p>Total Siswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
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
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalPelanggaran }}</h3>
                    <p>Total Pelanggaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pelanggaranMenunggu }}</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('wali-kelas.siswa') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> Lihat Siswa Kelas
                    </a>
                    <a href="{{ route('wali-kelas.pelanggaran.create') }}" class="btn btn-warning">
                        <i class="fas fa-ban"></i> Input Pelanggaran
                    </a>
                    <a href="{{ route('wali-kelas.prestasi.create') }}" class="btn btn-success">
                        <i class="fas fa-medal"></i> Input Prestasi
                    </a>
                    <a href="{{ route('wali-kelas.komunikasi') }}" class="btn btn-info">
                        <i class="fas fa-comments"></i> Komunikasi Ortu
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Siswa dengan Poin --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list"></i> Daftar Siswa & Poin</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>JK</th>
                            <th>Pelanggaran</th>
                            <th>Poin Pelanggaran</th>
                            <th>Prestasi</th>
                            <th>Poin Prestasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $index => $siswa)
                        <tr class="{{ $siswa['total_poin_pelanggaran'] >= 100 ? 'table-danger' : '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $siswa['nis'] }}</td>
                            <td><strong>{{ $siswa['nama'] }}</strong></td>
                            <td>{{ $siswa['jenis_kelamin'] }}</td>
                            <td>{{ $siswa['jumlah_pelanggaran'] }}</td>
                            <td>
                                <span class="badge badge-{{ $siswa['total_poin_pelanggaran'] >= 100 ? 'danger' : 'warning' }}">
                                    {{ $siswa['total_poin_pelanggaran'] }}
                                </span>
                            </td>
                            <td>{{ $siswa['jumlah_prestasi'] }}</td>
                            <td>
                                <span class="badge badge-success">{{ $siswa['total_poin_prestasi'] }}</span>
                            </td>
                            <td>
                                <a href="{{ route('wali-kelas.siswa.show', $siswa['id']) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada siswa di kelas ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
