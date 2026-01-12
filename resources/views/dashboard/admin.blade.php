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
        <div class="card" style="min-height: 500px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Statistik Pelanggaran & Prestasi (Tahun {{ date('Y') }})
                </h3>
            </div>
            <div class="card-body" style="height: 450px;">
                <canvas id="statistikChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card" style="min-height: 500px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-1"></i>
                    Rekapitulasi Bulanan
                </h3>
            </div>
            <div class="card-body p-0" style="height: 450px; overflow-y: auto;">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="thead-light" style="position: sticky; top: 0; z-index: 10; background: #f8f9fa;">
                            <tr>
                                <th width="25%">Bulan</th>
                                <th width="25%" class="text-center">Pelanggaran</th>
                                <th width="25%" class="text-center">Prestasi</th>
                                <th width="25%" class="text-center">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                $totalPelanggaranTahun = 0;
                                $totalPrestasiTahun = 0;
                            @endphp
                            @for($i = 1; $i <= 12; $i++)
                                @php
                                    $jmlPelanggaran = $pelanggaranPerBulan[$i] ?? 0;
                                    $jmlPrestasi = $prestasiPerBulan[$i] ?? 0;
                                    $selisih = $jmlPrestasi - $jmlPelanggaran;
                                    $totalPelanggaranTahun += $jmlPelanggaran;
                                    $totalPrestasiTahun += $jmlPrestasi;
                                @endphp
                                <tr>
                                    <td><strong>{{ $bulanNama[$i-1] }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">{{ $jmlPelanggaran }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $jmlPrestasi }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($selisih > 0)
                                            <span class="badge badge-success">+{{ $selisih }}</span>
                                        @elseif($selisih < 0)
                                            <span class="badge badge-danger">{{ $selisih }}</span>
                                        @else
                                            <span class="badge badge-secondary">0</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                        <tfoot class="thead-light" style="position: sticky; bottom: 0; z-index: 10; background: #f8f9fa;">
                            <tr>
                                <th>Total</th>
                                <th class="text-center">
                                    <span class="badge badge-danger" style="font-size: 13px;">{{ $totalPelanggaranTahun }}</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success" style="font-size: 13px;">{{ $totalPrestasiTahun }}</span>
                                </th>
                                <th class="text-center">
                                    @php $totalSelisih = $totalPrestasiTahun - $totalPelanggaranTahun; @endphp
                                    @if($totalSelisih > 0)
                                        <span class="badge badge-success" style="font-size: 13px;">+{{ $totalSelisih }}</span>
                                    @elseif($totalSelisih < 0)
                                        <span class="badge badge-danger" style="font-size: 13px;">{{ $totalSelisih }}</span>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 13px;">0</span>
                                    @endif
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row">
    <!-- Top Students -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Top 10 Siswa Paling Aktif (Prestasi & Pelanggaran)
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">NIS</th>
                                <th width="25%">Nama Siswa</th>
                                <th width="12%">Kelas</th>
                                <th width="12%" class="text-center">Prestasi</th>
                                <th width="12%" class="text-center">Pelanggaran</th>
                                <th width="14%" class="text-center">Total Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSiswa as $index => $siswa)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td><code>{{ $siswa->nis }}</code></td>
                                <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                                <td>
                                    <span class="badge badge-info">{{ $siswa->kelas->nama_kelas ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success" style="font-size: 14px; padding: 5px 10px;">
                                        <i class="fas fa-trophy"></i> {{ $siswa->prestasis_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger" style="font-size: 14px; padding: 5px 10px;">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $siswa->pelanggarans_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary" style="font-size: 14px; padding: 5px 10px;">
                                        {{ $siswa->total_aktivitas }} aktivitas
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle"></i> Belum ada data siswa dengan aktivitas
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
// Chart untuk statistik bulanan
const ctx1 = document.getElementById('statistikChart');
if (ctx1) {
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pelanggaran',
                data: [
                    {{ $pelanggaranPerBulan[1] ?? 0 }},
                    {{ $pelanggaranPerBulan[2] ?? 0 }},
                    {{ $pelanggaranPerBulan[3] ?? 0 }},
                    {{ $pelanggaranPerBulan[4] ?? 0 }},
                    {{ $pelanggaranPerBulan[5] ?? 0 }},
                    {{ $pelanggaranPerBulan[6] ?? 0 }},
                    {{ $pelanggaranPerBulan[7] ?? 0 }},
                    {{ $pelanggaranPerBulan[8] ?? 0 }},
                    {{ $pelanggaranPerBulan[9] ?? 0 }},
                    {{ $pelanggaranPerBulan[10] ?? 0 }},
                    {{ $pelanggaranPerBulan[11] ?? 0 }},
                    {{ $pelanggaranPerBulan[12] ?? 0 }}
                ],
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Prestasi',
                data: [
                    {{ $prestasiPerBulan[1] ?? 0 }},
                    {{ $prestasiPerBulan[2] ?? 0 }},
                    {{ $prestasiPerBulan[3] ?? 0 }},
                    {{ $prestasiPerBulan[4] ?? 0 }},
                    {{ $prestasiPerBulan[5] ?? 0 }},
                    {{ $prestasiPerBulan[6] ?? 0 }},
                    {{ $prestasiPerBulan[7] ?? 0 }},
                    {{ $prestasiPerBulan[8] ?? 0 }},
                    {{ $prestasiPerBulan[9] ?? 0 }},
                    {{ $prestasiPerBulan[10] ?? 0 }},
                    {{ $prestasiPerBulan[11] ?? 0 }},
                    {{ $prestasiPerBulan[12] ?? 0 }}
                ],
                borderColor: 'rgb(40, 167, 69)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });
}
</script>
@endpush
