@extends('layouts.app')

@section('title', 'Monitoring Pelanggaran')
@section('page-title', 'Monitoring Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Data Real-time Pelanggaran</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="periode" class="form-control">
                        <option value="">Semua Periode</option>
                        <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
                <div class="col-md-4">
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
                        <th>Jenis Pelanggaran</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggarans as $index => $p)
                    <tr>
                        <td>{{ $pelanggarans->firstItem() + $index }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $p->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                        <td><span class="badge badge-danger">{{ $p->poin }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $pelanggarans->links() }}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Siswa Pelanggaran Berat (Poin ≥ 100)</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Total Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaBerat as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td><span class="badge badge-danger">{{ $siswa->pelanggarans_sum_poin }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada siswa dengan poin >= 100</td>
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
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Trend Pelanggaran (6 Bulan)</h3>
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
        labels: {!! json_encode(array_column($trendBulanan, 'bulan')) !!},
        datasets: [{
            label: 'Pelanggaran',
            data: {!! json_encode(array_column($trendBulanan, 'total')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
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
