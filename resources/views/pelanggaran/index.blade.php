@extends('layouts.app')

@section('title', 'Data Pelanggaran')
@section('page-title', 'Data Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-ban mr-1"></i> Daftar Pelanggaran</h3>
        <div class="card-tools">
            <a href="{{ route('pelanggaran.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pelanggaran
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check"></i> {{ session('success') }}
        </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Poin</th>
                    <th>Guru Pencatat</th>
                    <th>Status</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggarans as $pelanggaran)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $pelanggaran->siswa->nama_siswa }}</strong><br>
                        <small class="text-muted">{{ $pelanggaran->siswa->nis }}</small>
                    </td>
                    <td>{{ $pelanggaran->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran }}</td>
                    <td><span class="badge badge-danger">{{ $pelanggaran->poin }}</span></td>
                    <td>{{ $pelanggaran->guru->nama_guru ?? '-' }}</td>
                    <td>
                        @if($pelanggaran->status_verifikasi == 'diverifikasi')
                            <span class="badge badge-success">Diverifikasi</span>
                        @elseif($pelanggaran->status_verifikasi == 'ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'kesiswaan']) && $pelanggaran->status_verifikasi == 'menunggu')
                            <form action="{{ route('pelanggaran.verify', $pelanggaran) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Verifikasi">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('pelanggaran.reject', $pelanggaran) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('pelanggaran.edit', $pelanggaran) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('pelanggaran.destroy', $pelanggaran) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
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

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Keterangan Status</h3>
            </div>
            <div class="card-body">
                <span class="badge badge-warning">Menunggu</span> = Pelanggaran menunggu verifikasi dari Kesiswaan<br>
                <span class="badge badge-success">Diverifikasi</span> = Pelanggaran sudah diverifikasi dan poin dihitung<br>
                <span class="badge badge-danger">Ditolak</span> = Pelanggaran ditolak dan poin tidak dihitung
            </div>
        </div>
    </div>
</div>
@endsection
