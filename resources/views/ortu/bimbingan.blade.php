@extends('layouts.app')

@section('title', 'Bimbingan Konseling Anak')
@section('page-title', 'Bimbingan Konseling Anak')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate"></i> {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</h3>
    </div>
    <div class="card-body">
        @if($bimbingans->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Permasalahan</th>
                        <th>Solusi</th>
                        <th>Tindak Lanjut</th>
                        <th>Konselor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bimbingans as $index => $b)
                    <tr>
                        <td>{{ $bimbingans->firstItem() + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->format('d/m/Y') }}</td>
                        <td>{{ $b->permasalahan }}</td>
                        <td>{{ $b->solusi ?? '-' }}</td>
                        <td>{{ $b->tindak_lanjut ?? '-' }}</td>
                        <td>{{ $b->guru->nama_guru ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $bimbingans->links() }}
        </div>
        @else
        <p class="text-center text-muted">Belum ada data bimbingan konseling</p>
        @endif
    </div>
</div>
@endsection
