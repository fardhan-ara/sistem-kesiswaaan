@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')
@section('page-title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalGuru }}</h3>
                <p>Total Guru</p>
            </div>
            <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalPelanggaran }}</h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalPrestasi }}</h3>
                <p>Total Prestasi</p>
            </div>
            <div class="icon"><i class="fas fa-trophy"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $totalKelas }}</h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon"><i class="fas fa-school"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $sanksiAktif }}</h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon"><i class="fas fa-gavel"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>{{ $totalBK }}</h3>
                <p>Sesi BK</p>
            </div>
            <div class="icon"><i class="fas fa-comments"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Grafik Pelanggaran & Prestasi (6 Bulan Terakhir)</h3>
            </div>
            <div class="card-body">
                <canvas id="mainChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pelanggaran per Kategori</h3>
            </div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title">Top 5 Siswa Bermasalah</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Pelanggaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaBermasalah as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td><span class="badge badge-danger">{{ $siswa->pelanggarans_count }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Top 5 Siswa Berprestasi</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Prestasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaBerprestasi as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td><span class="badge badge-success">{{ $siswa->prestasis_count }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Main Chart
const ctx1 = document.getElementById('mainChart');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Pelanggaran',
            data: @json($pelanggaranPerBulan),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }, {
            label: 'Prestasi',
            data: @json($prestasiPerBulan),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Category Chart
const ctx2 = document.getElementById('categoryChart');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: @json($pelanggaranPerKategori->pluck('kategori')),
        datasets: [{
            data: @json($pelanggaranPerKategori->pluck('total')),
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>
@endpush
