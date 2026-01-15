@extends('layouts.app')

@section('title', 'Dashboard BK')
@section('page-title', 'Dashboard Bimbingan Konseling')

@section('content')
<!-- Statistik Cards -->
<div class="row">
    <!-- Total Sesi BK -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalBK ?? 0 }}</h3>
                <p>Total Sesi BK</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
            <a href="{{ route('bk.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- BK Bulan Ini -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $bkBulanIni ?? 0 }}</h3>
                <p>BK Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <a href="{{ route('bk.create') }}" class="small-box-footer">
                Tambah Sesi BK <i class="fas fa-plus-circle"></i>
            </a>
        </div>
    </div>
    
    <!-- Siswa Bermasalah -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $siswaBermasalah ?? 0 }}</h3>
                <p>Siswa Bermasalah</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('pelanggaran.index') }}" class="small-box-footer">
                Lihat Pelanggaran <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <!-- Total Siswa -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalSiswa ?? 0 }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('siswa.index') }}" class="small-box-footer">
                Lihat Data Siswa <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Grafik dan Tabel -->
<div class="row">
    <!-- Grafik Statistik BK -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Statistik BK 3 Bulan Terakhir
                </h3>
            </div>
            <div class="card-body">
                <canvas id="bkChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Sesi BK Terbaru -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Sesi BK Terbaru
                </h3>
                <div class="card-tools">
                    <a href="{{ route('bk.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 90px;">Tanggal</th>
                            <th>Siswa</th>
                            <th style="width: 70px;">Kelas</th>
                            <th style="width: 80px;">Kategori</th>
                            <th style="width: 80px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bkTerbaru ?? [] as $bk)
                        <tr>
                            <td><small>{{ $bk->tanggal->format('d/m/Y') }}</small></td>
                            <td><small>{{ Str::limit($bk->siswa->nama_siswa ?? '-', 20) }}</small></td>
                            <td><small>{{ $bk->siswa->kelas->nama_kelas ?? '-' }}</small></td>
                            <td>
                                @if($bk->kategori === 'pribadi')
                                    <span class="badge badge-primary badge-sm">Pribadi</span>
                                @elseif($bk->kategori === 'sosial')
                                    <span class="badge badge-info badge-sm">Sosial</span>
                                @elseif($bk->kategori === 'belajar')
                                    <span class="badge badge-warning badge-sm">Belajar</span>
                                @else
                                    <span class="badge badge-secondary badge-sm">Karir</span>
                                @endif
                            </td>
                            <td>
                                @if($bk->status === 'selesai')
                                    <span class="badge badge-success badge-sm">Selesai</span>
                                @elseif($bk->status === 'proses')
                                    <span class="badge badge-warning badge-sm">Proses</span>
                                @else
                                    <span class="badge badge-info badge-sm">Terjadwal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <i class="fas fa-inbox text-muted"></i>
                                <p class="text-muted mb-0">Belum ada data BK</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-1"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('bk.create') }}" class="btn btn-app bg-success w-100">
                            <i class="fas fa-plus"></i> Tambah Sesi BK
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('bk.index') }}" class="btn btn-app bg-info w-100">
                            <i class="fas fa-list"></i> Daftar BK
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('siswa.index') }}" class="btn btn-app bg-primary w-100">
                            <i class="fas fa-users"></i> Data Siswa
                        </a>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <a href="{{ route('pelanggaran.index') }}" class="btn btn-app bg-warning w-100">
                            <i class="fas fa-exclamation-circle"></i> Pelanggaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('bkChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($statistikBulanan['labels'] ?? []),
        datasets: [{
            label: 'Sesi BK',
            data: @json($statistikBulanan['bk'] ?? []),
            borderColor: 'rgb(23, 162, 184)',
            backgroundColor: 'rgba(23, 162, 184, 0.1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgb(23, 162, 184)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                padding: 12,
                titleFont: {
                    size: 14
                },
                bodyFont: {
                    size: 13
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                },
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endpush
