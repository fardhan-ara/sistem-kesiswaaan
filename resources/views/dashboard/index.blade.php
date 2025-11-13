@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Total Pelanggaran</h5>
                <h2>{{ $totalPelanggaran }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Prestasi</h5>
                <h2>{{ $totalPrestasi }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Sanksi Aktif</h5>
                <h2>{{ $sanksiAktif }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Siswa</h5>
                <h2>{{ $topPelanggar->count() + $topPrestasi->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Grafik Pelanggaran, Prestasi & Sanksi</h5>
            </div>
            <div class="card-body">
                <canvas id="chartPelanggaranPrestasi"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Top 5 Pelanggar</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPelanggar as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td><span class="badge bg-danger">{{ $siswa->pelanggarans_count }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Top 5 Siswa Berprestasi</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jumlah Prestasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPrestasi as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td><span class="badge bg-success">{{ $siswa->prestasis_count }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPelanggaranPrestasi');
const pelanggaranData = @json(array_values(array_replace(array_fill(1, 12, 0), $pelanggaranPerBulan->toArray())));
const prestasiData = @json(array_values(array_replace(array_fill(1, 12, 0), $prestasiPerBulan->toArray())));
const sanksiData = @json(array_values(array_replace(array_fill(1, 12, 0), $sanksiPerBulan->toArray())));

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Pelanggaran',
            data: pelanggaranData,
            borderColor: 'rgb(220, 53, 69)',
            backgroundColor: 'rgba(220, 53, 69, 0.1)',
            tension: 0.3
        }, {
            label: 'Prestasi',
            data: prestasiData,
            borderColor: 'rgb(25, 135, 84)',
            backgroundColor: 'rgba(25, 135, 84, 0.1)',
            tension: 0.3
        }, {
            label: 'Sanksi Aktif',
            data: sanksiData,
            borderColor: 'rgb(255, 193, 7)',
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Statistik Pelanggaran, Prestasi & Sanksi Per Bulan'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
