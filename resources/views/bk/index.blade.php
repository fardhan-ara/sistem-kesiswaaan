@extends('layouts.app')

@section('title', 'Bimbingan Konseling')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Data Bimbingan Konseling</h4>
        <a href="{{ route('bk.create') }}" class="btn btn-primary">Tambah Bimbingan</a>
    </div>
    <div class="card-body">
        <x-alert />

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Guru BK</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingans as $bimbingan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $bimbingan->siswa->nama_siswa }}</td>
                    <td>{{ $bimbingan->siswa->kelas->nama_kelas }}</td>
                    <td>{{ $bimbingan->guru->nama_guru }}</td>
                    <td>{{ $bimbingan->tanggal->format('d/m/Y') }}</td>
                    <td>
                        @if($bimbingan->status == 'Selesai')
                            <span class="badge bg-success">{{ $bimbingan->status }}</span>
                        @elseif($bimbingan->status == 'Terjadwal')
                            <span class="badge bg-warning">{{ $bimbingan->status }}</span>
                        @else
                            <span class="badge bg-danger">{{ $bimbingan->status }}</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($bimbingan->catatan, 50) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data bimbingan konseling</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
