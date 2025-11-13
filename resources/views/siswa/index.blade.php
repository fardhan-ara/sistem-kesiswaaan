@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Data Siswa</h4>
        <a href="{{ route('siswa.create') }}" class="btn btn-primary">Tambah Siswa</a>
    </div>
    <div class="card-body">
        <x-alert />

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                @php
                    $totalPoin = $siswa->pelanggarans()->where('status_verifikasi', 'verified')->sum('poin');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $siswa->nis }}</td>
                    <td>{{ $siswa->nama_siswa }}</td>
                    <td>{{ $siswa->kelas->nama_kelas }}</td>
                    <td><span class="badge bg-danger">{{ $totalPoin }}</span></td>
                    <td>
                        <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data siswa</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
