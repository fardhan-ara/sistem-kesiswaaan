@extends('layouts.app')

@section('title', 'Data Prestasi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Data Prestasi</h4>
        <a href="{{ route('prestasi.create') }}" class="btn btn-primary">Tambah Prestasi</a>
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
                    <th>Jenis Prestasi</th>
                    <th>Poin Reward</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasis as $prestasi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $prestasi->siswa->nis }}</td>
                    <td>{{ $prestasi->siswa->nama_siswa }}</td>
                    <td>{{ $prestasi->siswa->kelas->nama_kelas }}</td>
                    <td>{{ $prestasi->jenisPrestasi->nama_prestasi }}</td>
                    <td><span class="badge bg-success">{{ $prestasi->jenisPrestasi->poin_reward }}</span></td>
                    <td>
                        @if($prestasi->status_verifikasi == 'verified')
                            <span class="badge bg-success">Verified</span>
                        @elseif($prestasi->status_verifikasi == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($prestasi->status_verifikasi == 'pending')
                            <form action="{{ route('prestasi.verify', $prestasi) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Verify</button>
                            </form>
                            <form action="{{ route('prestasi.reject', $prestasi) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @endif
                        <a href="{{ route('prestasi.edit', $prestasi) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('prestasi.destroy', $prestasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data prestasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
