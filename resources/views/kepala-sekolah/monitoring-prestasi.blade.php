@extends('layouts.app')

@section('title', 'Monitoring Prestasi')
@section('page-title', 'Monitoring Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-trophy"></i> Data Prestasi Siswa</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <select name="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis Prestasi</th>
                        <th>Tingkat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasis as $index => $p)
                    <tr>
                        <td>{{ $prestasis->firstItem() + $index }}</td>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td>{{ $p->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $p->jenisPrestasi->nama_prestasi ?? '-' }}</td>
                        <td><span class="badge badge-success">{{ $p->jenisPrestasi->tingkat ?? '-' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $prestasis->links() }}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-star"></i> Top 10 Siswa Berprestasi</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Prestasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSiswa as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td><span class="badge badge-success">{{ $siswa->prestasis_count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Trend Prestasi (6 Bulan)</h3>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="200"></canvas>
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
        labels: {!! json_encode(array_column($trendPrestasi, 'bulan')) !!},
        datasets: [{
            label: 'Prestasi',
            data: {!! json_encode(array_column($trendPrestasi, 'total')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
