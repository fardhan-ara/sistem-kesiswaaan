@extends('layouts.app')

@section('title', 'Dashboard Executive')
@section('page-title', 'Dashboard Executive - Kepala Sekolah')

@section('content')
{{-- Overview Keseluruhan --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $overview['total_siswa'] }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $overview['total_pelanggaran'] }}</h3>
                <p>Total Pelanggaran</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $overview['total_prestasi'] }}</h3>
                <p>Total Prestasi</p>
            </div>
            <div class="icon"><i class="fas fa-trophy"></i></div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $overview['sanksi_aktif'] }}</h3>
                <p>Sanksi Aktif</p>
            </div>
            <div class="icon"><i class="fas fa-gavel"></i></div>
        </div>
    </div>
</div>

{{-- KPI (Key Performance Indicator) --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Efektivitas Sanksi</span>
                <span class="info-box-number">{{ $kpi['efektivitas_sanksi'] }}%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tingkat Disiplin</span>
                <span class="info-box-number">{{ $kpi['tingkat_disiplin'] }}%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-star"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rasio Prestasi</span>
                <span class="info-box-number">{{ $kpi['rasio_prestasi'] }}%</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon 
                @if($kpi['trend_pelanggaran'] == 'naik') bg-danger
                @elseif($kpi['trend_pelanggaran'] == 'turun') bg-success
                @else bg-secondary
                @endif">
                <i class="fas 
                    @if($kpi['trend_pelanggaran'] == 'naik') fa-arrow-up
                    @elseif($kpi['trend_pelanggaran'] == 'turun') fa-arrow-down
                    @else fa-minus
                    @endif"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Trend Pelanggaran</span>
                <span class="info-box-number">{{ ucfirst($kpi['trend_pelanggaran']) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Alert & Notification --}}
@if(count($alerts) > 0)
<div class="row">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bell"></i> Alert & Notification</h3>
            </div>
            <div class="card-body">
                @foreach($alerts as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-{{ $alert['icon'] }}"></i> Alert!</h5>
                    {{ $alert['message'] }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- Grafik Trend --}}
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Grafik Trend 12 Bulan</h3>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-link"></i> Menu Cepat</h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('kepala-sekolah.monitoring-pelanggaran') }}" class="nav-link">
                            <i class="fas fa-exclamation-circle"></i> Monitoring Pelanggaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepala-sekolah.monitoring-sanksi') }}" class="nav-link">
                            <i class="fas fa-gavel"></i> Monitoring Sanksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepala-sekolah.monitoring-prestasi') }}" class="nav-link">
                            <i class="fas fa-trophy"></i> Monitoring Prestasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kepala-sekolah.laporan-executive') }}" class="nav-link">
                            <i class="fas fa-file-alt"></i> Laporan Executive
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trendChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($trendBulanan, 'bulan')) !!},
        datasets: [{
            label: 'Pelanggaran',
            data: {!! json_encode(array_column($trendBulanan, 'pelanggaran')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
        }, {
            label: 'Prestasi',
            data: {!! json_encode(array_column($trendBulanan, 'prestasi')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush
