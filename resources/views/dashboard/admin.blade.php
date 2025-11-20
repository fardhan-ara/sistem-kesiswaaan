@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-2 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('siswa.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalPelanggaran }}</h3>
                <p>Pelanggaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-ban"></i>
            </div>
            <a href="{{ route('pelanggaran.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalPrestasi }}</h3>
                <p>Prestasi</p>
            </div>
            <div class="icon">
                <i class="fas fa-trophy"></i>
            </div>
            <a href="{{ route('prestasi.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $sanksiAktif }}</h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-gavel"></i>
            </div>
            <a href="{{ route('sanksi.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('users.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ $totalBK }}</h3>
                <p>Sesi BK</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
            <a href="{{ route('bk.index') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Statistik Pelanggaran & Prestasi
                </h3>
            </div>
            <div class="card-body">
                <canvas id="statistikChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Distribusi Data
                </h3>
            </div>
            <div class="card-body">
                <canvas id="pieChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row">
    <!-- Pending Verifikasi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Menunggu Verifikasi
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Siswa</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggaranBaru as $item)
                            <tr>
                                <td>
                                    <span class="badge badge-danger">Pelanggaran</span><br>
                                    <small>{{ $item->jenisPelanggaran->nama ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $item->siswa->nama ?? 'N/A' }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('pelanggaran.show', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            @endforelse
                            
                            @forelse($prestasiBaru as $item)
                            <tr>
                                <td>
                                    <span class="badge badge-success">Prestasi</span><br>
                                    <small>{{ $item->jenisPrestasi->nama ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $item->siswa->nama ?? 'N/A' }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('prestasi.show', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            @if($pelanggaranBaru->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data pending</td>
                            </tr>
                            @endif
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Students -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star mr-1"></i>
                    Top Siswa
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Prestasi</th>
                                <th>Pelanggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allStudents = collect($topPrestasi)->merge($topPelanggar)->unique('id')->take(5);
                            @endphp
                            @forelse($allStudents as $siswa)
                            <tr>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->kelas->nama ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-success">{{ $siswa->prestasis_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-danger">{{ $siswa->pelanggarans_count ?? 0 }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data siswa</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                    <div class="col-md-2">
                        <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('pelanggaran.create') }}" class="btn btn-danger btn-block">
                            <i class="fas fa-plus"></i> Input Pelanggaran
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('prestasi.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-plus"></i> Input Prestasi
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('laporan.index') }}" class="btn btn-info btn-block">
                            <i class="fas fa-file-pdf"></i> Export Laporan
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-users"></i> Kelola User
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('backup.index') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-database"></i> Backup Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart untuk statistik bulanan
const ctx1 = document.getElementById('statistikChart').getContext('2d');
const statistikChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Pelanggaran',
            data: [
                @for($i = 1; $i <= 12; $i++)
                    {{ $pelanggaranPerBulan[$i] ?? 0 }},
                @endfor
            ],
            borderColor: 'rgb(220, 53, 69)',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            tension: 0.1
        }, {
            label: 'Prestasi',
            data: [
                @for($i = 1; $i <= 12; $i++)
                    {{ $prestasiPerBulan[$i] ?? 0 }},
                @endfor
            ],
            borderColor: 'rgb(40, 167, 69)',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Pie chart untuk distribusi
const ctx2 = document.getElementById('pieChart').getContext('2d');
const pieChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Siswa', 'Pelanggaran', 'Prestasi', 'Sanksi Aktif'],
        datasets: [{
            data: [{{ $totalSiswa }}, {{ $totalPelanggaran }}, {{ $totalPrestasi }}, {{ $sanksiAktif }}],
            backgroundColor: [
                'rgba(23, 162, 184, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush