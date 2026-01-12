@extends('layouts.app')

@section('title', 'Dashboard BK')
@section('page-title', 'Dashboard Bimbingan Konseling')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalBK }}</h3>
                <p>Total Sesi BK</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $bkBulanIni }}</h3>
                <p>BK Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $siswaBermasalah }}</h3>
                <p>Siswa Bermasalah</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalSiswa }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik BK 3 Bulan Terakhir</h3>
            </div>
            <div class="card-body">
                <canvas id="bkChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sesi BK Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('bk.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Kategori</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bkTerbaru as $bk)
                        <tr>
                            <td>{{ $bk->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $bk->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $bk->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td>
                                @if($bk->kategori === 'pribadi')
                                    <span class="badge badge-primary">Pribadi</span>
                                @elseif($bk->kategori === 'sosial')
                                    <span class="badge badge-info">Sosial</span>
                                @elseif($bk->kategori === 'belajar')
                                    <span class="badge badge-warning">Belajar</span>
                                @else
                                    <span class="badge badge-secondary">Karir</span>
                                @endif
                            </td>
                            <td>
                                @if($bk->status === 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($bk->status === 'proses')
                                    <span class="badge badge-warning">Proses</span>
                                @else
                                    <span class="badge badge-info">Terjadwal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data BK</td>
                        </tr>
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
const ctx = document.getElementById('bkChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($statistikBulanan['labels']),
        datasets: [{
            label: 'Sesi BK',
            data: @json($statistikBulanan['bk']),
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
</script>
@endpush
