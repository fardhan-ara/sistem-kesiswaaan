@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')

@section('content')
@if(!$siswa)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                @if(isset($status) && $status === 'pending')
                    <i class="fas fa-clock fa-5x text-warning mb-4"></i>
                    <h3>Menunggu Persetujuan Admin</h3>
                    <p class="text-muted">{{ $message }}</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-hourglass-half"></i> <strong>Status: PENDING</strong><br>
                        Akun Anda sedang dalam proses verifikasi oleh admin.<br>
                        Anda akan dapat mengakses data setelah admin menyetujui akun Anda.
                    </div>
                @elseif(isset($status) && $status === 'rejected')
                    <i class="fas fa-times-circle fa-5x text-danger mb-4"></i>
                    <h3>Akun Ditolak</h3>
                    <p class="text-danger">{{ $message }}</p>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Status: DITOLAK</strong><br>
                        Silakan hubungi admin untuk informasi lebih lanjut.
                    </div>
                @else
                    <i class="fas fa-user-slash fa-5x text-warning mb-4"></i>
                    <h3>Data Siswa Belum Terdaftar</h3>
                    <p class="text-muted">{{ $message ?? 'Data siswa Anda belum terdaftar dalam sistem.' }}</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Informasi:</strong><br>
                        Email: <strong>{{ Auth::user()->email ?? '-' }}</strong><br>
                        Hubungi bagian Kesiswaan atau Admin untuk melengkapi data Anda.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalPelanggaran }}</h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
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
                <h3>{{ $totalPoin }}</h3>
                <p>Total Poin Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $sanksiAktif }}</h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-gavel"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Profil Siswa</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">NIS</th>
                        <td>{{ $siswa->nis }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $siswa->nama_siswa }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Ajaran</th>
                        <td>{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status Poin</h3>
            </div>
            <div class="card-body">
                @if($totalPoin >= 51)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Sangat Berat!</strong><br>
                        Total poin Anda: <strong>{{ $totalPoin }}</strong><br>
                        Anda berada dalam kategori pelanggaran sangat berat.
                    </div>
                @elseif($totalPoin >= 31)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle"></i> <strong>Berat!</strong><br>
                        Total poin Anda: <strong>{{ $totalPoin }}</strong><br>
                        Anda berada dalam kategori pelanggaran berat.
                    </div>
                @elseif($totalPoin >= 16)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Sedang</strong><br>
                        Total poin Anda: <strong>{{ $totalPoin }}</strong><br>
                        Anda berada dalam kategori pelanggaran sedang.
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <strong>Baik!</strong><br>
                        Total poin Anda: <strong>{{ $totalPoin }}</strong><br>
                        Pertahankan perilaku baik Anda!
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Pelanggaran Terbaru -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Pelanggaran Terbaru</h3>
            </div>
            <div class="card-body">
                @if(isset($pelanggaranTerbaru) && $pelanggaranTerbaru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Pencatat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggaranTerbaru as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                <td>{{ $p->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                                <td><span class="badge badge-danger">{{ $p->poin }}</span></td>
                                <td>{{ $p->guru->nama_guru ?? '-' }}</td>
                                <td>
                                    @if($p->status_verifikasi == 'terverifikasi')
                                        <span class="badge badge-success">Terverifikasi</span>
                                    @elseif($p->status_verifikasi == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-muted">Belum ada data pelanggaran</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Prestasi Terbaru -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-trophy"></i> Riwayat Prestasi Terbaru</h3>
            </div>
            <div class="card-body">
                @if(isset($prestasiTerbaru) && $prestasiTerbaru->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Prestasi</th>
                                <th>Poin</th>
                                <th>Pencatat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prestasiTerbaru as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                                <td>{{ $p->jenisPrestasi->nama_prestasi ?? '-' }}</td>
                                <td><span class="badge badge-success">{{ $p->poin }}</span></td>
                                <td>{{ $p->guru->nama_guru ?? '-' }}</td>
                                <td>
                                    @if($p->status_verifikasi == 'terverifikasi')
                                        <span class="badge badge-success">Terverifikasi</span>
                                    @elseif($p->status_verifikasi == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-muted">Belum ada data prestasi</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Sanksi Aktif -->
@if(isset($sanksiAktifList) && $sanksiAktifList->count() > 0)
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card border-danger">
            <div class="card-header bg-danger">
                <h3 class="card-title"><i class="fas fa-gavel"></i> Sanksi yang Sedang Berjalan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelanggaran</th>
                                <th>Jenis Sanksi</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sanksiAktifList as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $s->pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                                <td>{{ $s->nama_sanksi }}</td>
                                <td>{{ \Carbon\Carbon::parse($s->tanggal_mulai)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($s->tanggal_selesai)->format('d/m/Y') }}</td>
                                <td>
                                    @if($s->status_sanksi == 'berjalan')
                                        <span class="badge badge-warning">Berjalan</span>
                                    @else
                                        <span class="badge badge-info">{{ ucfirst($s->status_sanksi) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif
@endsection
