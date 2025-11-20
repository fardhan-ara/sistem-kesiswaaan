@extends('layouts.app')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-school mr-1"></i> Daftar Kelas</h3>
        <div class="card-tools">
            <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $index => $item)
                <tr>
                    <td>{{ $kelas->firstItem() + $index }}</td>
                    <td><strong>{{ $item->nama_kelas }}</strong></td>
                    <td>{{ $item->jurusan ?? '-' }}</td>
                    <td>{{ $item->waliKelas->nama_guru ?? '-' }}</td>
                    <td>{{ $item->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                    <td>
                        <a href="{{ route('kelas.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('kelas.destroy', $item) }}" method="POST" class="d-inline">
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
    @if($kelas->hasPages())
    <div class="card-footer">{{ $kelas->links() }}</div>
    @endif
</div>
@endsection