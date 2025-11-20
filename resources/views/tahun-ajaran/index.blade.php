@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Tahun Ajaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Daftar Tahun Ajaran</h3>
        <div class="card-tools">
            <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Tahun Ajaran
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjarans as $index => $item)
                <tr>
                    <td>{{ $tahunAjarans->firstItem() + $index }}</td>
                    <td><strong>{{ $item->tahun_ajaran }}</strong></td>
                    <td><span class="badge badge-info">{{ ucfirst($item->semester) }}</span></td>
                    <td>
                        @if($item->status_aktif === 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('tahun-ajaran.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('tahun-ajaran.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tahunAjarans->hasPages())
    <div class="card-footer">{{ $tahunAjarans->links() }}</div>
    @endif
</div>
@endsection