@extends('layouts.app')

@section('title', 'Jenis Prestasi')
@section('page-title', 'Jenis Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-medal mr-1"></i> Daftar Jenis Prestasi</h3>
        <div class="card-tools">
            <a href="{{ route('jenis-prestasi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Jenis Prestasi
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Prestasi</th>
                    <th>Poin</th>
                    <th>Kategori</th>
                    <th>Penghargaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisPrestasiS as $index => $item)
                <tr>
                    <td>{{ $jenisPrestasiS->firstItem() + $index }}</td>
                    <td>{{ $item->nama_prestasi }}</td>
                    <td><span class="badge badge-success">{{ $item->poin }}</span></td>
                    <td>
                        @if($item->kategori === 'akademik')
                            <span class="badge badge-primary">Akademik</span>
                        @else
                            <span class="badge badge-info">Non-Akademik</span>
                        @endif
                    </td>
                    <td>{{ $item->penghargaan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('jenis-prestasi.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('jenis-prestasi.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jenisPrestasiS->hasPages())
    <div class="card-footer">{{ $jenisPrestasiS->links() }}</div>
    @endif
</div>
@endsection
