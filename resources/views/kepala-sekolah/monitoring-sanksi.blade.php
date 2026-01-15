@extends('layouts.app')

@section('title', 'Monitoring Sanksi')
@section('page-title', 'Monitoring Sanksi')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-gavel"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Sanksi</span>
                <span class="info-box-number">{{ $efektivitas['total_sanksi'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sanksi Selesai</span>
                <span class="info-box-number">{{ $efektivitas['sanksi_selesai'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sanksi Aktif</span>
                <span class="info-box-number">{{ $efektivitas['sanksi_aktif'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tingkat Kepatuhan</span>
                <span class="info-box-number">{{ $efektivitas['tingkat_kepatuhan'] }}%</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-gavel"></i> Sanksi Aktif</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
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
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Sanksi</th>
                        <th>Poin</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sanksis as $index => $sanksi)
                    <tr>
                        <td>{{ $sanksis->firstItem() + $index }}</td>
                        <td>{{ $sanksi->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $sanksi->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $sanksi->nama_sanksi }}</td>
                        <td><span class="badge badge-danger">{{ $sanksi->total_poin }}</span></td>
                        <td>{{ $sanksi->tanggal_mulai->format('d/m/Y') }}</td>
                        <td>
                            @if($sanksi->status_sanksi == 'aktif')
                                <span class="badge badge-warning">Aktif</span>
                            @else
                                <span class="badge badge-success">Selesai</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $total = $sanksi->pelaksanaanSanksis->count();
                                $selesai = $sanksi->pelaksanaanSanksis->where('status', 'selesai')->count();
                                $progress = $total > 0 ? round(($selesai / $total) * 100) : 0;
                            @endphp
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%">{{ $progress }}%</div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $sanksis->links() }}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Efektivitas Per Kategori</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Total</th>
                            <th>Selesai</th>
                            <th>Efektivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($efektivitas['efektivitas_per_kategori'] as $item)
                        <tr>
                            <td><span class="badge badge-info">{{ $item->kategori_poin }}</span></td>
                            <td>{{ $item->total }}</td>
                            <td>{{ $item->selesai }}</td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $item->efektivitas }}%">
                                        {{ $item->efektivitas }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Kasus Eskalasi (Sanksi Berulang)</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jumlah Sanksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasusEskalasi as $siswa)
                        <tr>
                            <td>{{ $siswa->nama_siswa }}</td>
                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td><span class="badge badge-danger">{{ $siswa->sanksis_count }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada kasus eskalasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
