@extends('layouts.app')

@section('title', 'Sanksi Anak')
@section('page-title', 'Sanksi Anak')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</h3>
    </div>
    <div class="card-body">
        @if($sanksis->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Pelanggaran</th>
                        <th>Jenis Sanksi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sanksis as $index => $s)
                    <tr>
                        <td>{{ $sanksis->firstItem() + $index }}</td>
                        <td>{{ $s->pelanggaran->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                        <td>{{ $s->jenis_sanksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($s->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($s->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td>
                            @if($s->status_sanksi == 'aktif')
                                <span class="badge badge-danger">Aktif</span>
                            @elseif($s->status_sanksi == 'sedang_dilaksanakan')
                                <span class="badge badge-warning">Berjalan</span>
                            @else
                                <span class="badge badge-success">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $sanksis->links() }}
        </div>
        @else
        <p class="text-center text-muted">Belum ada data sanksi</p>
        @endif
    </div>
</div>
@endsection
