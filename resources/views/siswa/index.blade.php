@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Daftar Siswa</h3>
        <div class="card-tools">
            <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Total Poin</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                @php
                    $totalPoin = $siswa->pelanggarans()->where('status_verifikasi', 'diverifikasi')->sum('poin');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $siswa->nis }}</strong></td>
                    <td>{{ $siswa->nama_siswa }}</td>
                    <td>
                        @if($siswa->jenis_kelamin == 'L')
                            <span class="badge badge-primary">Laki-laki</span>
                        @else
                            <span class="badge badge-danger">Perempuan</span>
                        @endif
                    </td>
                    <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                    <td>
                        @if($totalPoin >= 51)
                            <span class="badge badge-danger">{{ $totalPoin }} (Sangat Berat)</span>
                        @elseif($totalPoin >= 31)
                            <span class="badge badge-warning">{{ $totalPoin }} (Berat)</span>
                        @elseif($totalPoin >= 16)
                            <span class="badge badge-info">{{ $totalPoin }} (Sedang)</span>
                        @else
                            <span class="badge badge-success">{{ $totalPoin }} (Ringan)</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data siswa</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
