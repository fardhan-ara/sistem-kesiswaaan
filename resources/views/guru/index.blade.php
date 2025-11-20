@extends('layouts.app')

@section('title', 'Data Guru')
@section('page-title', 'Data Guru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chalkboard-teacher mr-1"></i> Daftar Guru</h3>
        <div class="card-tools">
            <a href="{{ route('guru.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Guru
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Email</th>
                    <th>Bidang Studi</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $index => $item)
                <tr>
                    <td>{{ $gurus->firstItem() + $index }}</td>
                    <td><strong>{{ $item->nip }}</strong></td>
                    <td>{{ $item->nama_guru }}</td>
                    <td>{{ $item->user->email ?? '-' }}</td>
                    <td>{{ $item->bidang_studi ?? '-' }}</td>
                    <td>
                        @if($item->status === 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('guru.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('guru.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($gurus->hasPages())
    <div class="card-footer">{{ $gurus->links() }}</div>
    @endif
</div>
@endsection
