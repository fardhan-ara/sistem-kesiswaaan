@extends('layouts.app')

@section('title', 'Pelanggaran Anak')
@section('page-title', 'Pelanggaran Anak')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</h3>
        <div class="card-tools">
            <span class="badge badge-danger">Total Poin: {{ $totalPoin }}</span>
        </div>
    </div>
    <div class="card-body">
        @if($pelanggarans->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Pencatat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggarans as $index => $p)
                    <tr>
                        <td>{{ $pelanggarans->firstItem() + $index }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                        <td><span class="badge badge-danger">{{ $p->poin }}</span></td>
                        <td>{{ $p->keterangan ?? '-' }}</td>
                        <td>{{ $p->guru->nama_guru ?? '-' }}</td>
                        <td>
                            @if($p->status_verifikasi == 'terverifikasi')
                                <span class="badge badge-success">Terverifikasi</span>
                            @else
                                <span class="badge badge-info">Diverifikasi</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $pelanggarans->links() }}
        </div>
        @else
        <p class="text-center text-muted">Belum ada data pelanggaran</p>
        @endif
    </div>
</div>
@endsection
