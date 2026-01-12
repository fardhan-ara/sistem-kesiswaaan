@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h2><i class="fas fa-user"></i> Detail Siswa</h2>
        <a href="{{ route('wali-kelas.siswa') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Biodata Siswa --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">Biodata Siswa</h3>
                </div>
                <div class="card-body">
                    <p><strong>NIS:</strong> {{ $siswa->nis }}</p>
                    <p><strong>Nama:</strong> {{ $siswa->nama_siswa }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    <p><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas }}</p>
                    <p><strong>Tahun Ajaran:</strong> {{ $siswa->tahunAjaran->nama ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalPoinPelanggaran }}</h3>
                            <p>Total Poin Pelanggaran</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalPoinPrestasi }}</h3>
                            <p>Total Poin Prestasi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Pelanggaran --}}
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title">Riwayat Pelanggaran</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa->pelanggarans as $p)
                        <tr>
                            <td>{{ $p->tanggal_pelanggaran ? \Carbon\Carbon::parse($p->tanggal_pelanggaran)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $p->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                            <td><span class="badge badge-danger">{{ $p->poin }}</span></td>
                            <td>
                                @if($p->status_verifikasi === 'diverifikasi')
                                <span class="badge badge-success">Diverifikasi</span>
                                @elseif($p->status_verifikasi === 'ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                                @else
                                <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada pelanggaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Riwayat Prestasi --}}
    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title text-white">Riwayat Prestasi</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Prestasi</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa->prestasis as $p)
                        <tr>
                            <td>{{ $p->tanggal_prestasi ? \Carbon\Carbon::parse($p->tanggal_prestasi)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $p->jenisPrestasi->nama_prestasi ?? '-' }}</td>
                            <td><span class="badge badge-success">{{ $p->poin }}</span></td>
                            <td>
                                @if($p->status_verifikasi === 'verified')
                                <span class="badge badge-success">Diverifikasi</span>
                                @elseif($p->status_verifikasi === 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                                @else
                                <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada prestasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
