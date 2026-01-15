@extends('layouts.app')

@section('title', 'Prestasi Anak')
@section('page-title', 'Prestasi Anak')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</h3>
    </div>
    <div class="card-body">
        @if($prestasis->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Jenis Prestasi</th>
                        <th>Tingkat</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Pencatat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prestasis as $index => $p)
                    <tr>
                        <td>{{ $prestasis->firstItem() + $index }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->jenisPrestasi->nama_prestasi ?? '-' }}</td>
                        <td>
                            @if($p->tingkat == 'internasional')
                                <span class="badge badge-danger">Internasional</span>
                            @elseif($p->tingkat == 'nasional')
                                <span class="badge badge-warning">Nasional</span>
                            @elseif($p->tingkat == 'provinsi')
                                <span class="badge badge-info">Provinsi</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($p->tingkat) }}</span>
                            @endif
                        </td>
                        <td><span class="badge badge-success">{{ $p->poin }}</span></td>
                        <td>{{ $p->keterangan ?? '-' }}</td>
                        <td>{{ $p->guru->nama_guru ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $prestasis->links() }}
        </div>
        @else
        <p class="text-center text-muted">Belum ada data prestasi</p>
        @endif
    </div>
</div>
@endsection
