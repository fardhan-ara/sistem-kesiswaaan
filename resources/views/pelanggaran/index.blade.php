@extends('layouts.app')

@section('title', 'Data Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Data Pelanggaran</h4>
        <a href="{{ route('pelanggaran.create') }}" class="btn btn-primary">Tambah Pelanggaran</a>
    </div>
    <div class="card-body">
        <x-alert />

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Poin</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggarans as $pelanggaran)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pelanggaran->siswa->nis }}</td>
                    <td>{{ $pelanggaran->siswa->nama_siswa }}</td>
                    <td>{{ $pelanggaran->siswa->kelas->nama_kelas }}</td>
                    <td>{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran }}</td>
                    <td><span class="badge bg-danger">{{ $pelanggaran->poin }}</span></td>
                    <td>
                        @if($pelanggaran->status_verifikasi == 'verified')
                            <span class="badge bg-success">Verified</span>
                        @elseif($pelanggaran->status_verifikasi == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pelanggaran.edit', $pelanggaran) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('pelanggaran.destroy', $pelanggaran) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pelanggaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
