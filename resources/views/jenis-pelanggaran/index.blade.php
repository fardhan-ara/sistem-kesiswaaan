@extends('layouts.app')

@section('title', 'Jenis Pelanggaran')
@section('page-title', 'Jenis Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Daftar Jenis Pelanggaran</h3>
        <div class="card-tools">
            <a href="{{ route('jenis-pelanggaran.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Jenis Pelanggaran
            </a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori Pelanggaran</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Bobot Poin</th>
                    <th>Tingkat</th>
                    <th>Sanksi Rekomendasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisPelanggarans as $index => $item)
                <tr>
                    <td>{{ $jenisPelanggarans->firstItem() + $index }}</td>
                    <td><strong>{{ $item->kelompok }}</strong></td>
                    <td>{{ $item->nama_pelanggaran }}</td>
                    <td><span class="badge badge-danger">{{ $item->poin }}</span></td>
                    <td>
                        @if($item->kategori === 'ringan')
                            <span class="badge badge-info">Ringan</span>
                        @elseif($item->kategori === 'sedang')
                            <span class="badge badge-warning">Sedang</span>
                        @elseif($item->kategori === 'berat')
                            <span class="badge badge-danger">Berat</span>
                        @else
                            <span class="badge badge-dark">Sangat Berat</span>
                        @endif
                    </td>
                    <td>{{ $item->sanksi_rekomendasi ?? '-' }}</td>
                    <td>
                        <a href="{{ route('jenis-pelanggaran.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('jenis-pelanggaran.destroy', $item) }}" method="POST" class="d-inline">
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
    @if($jenisPelanggarans->hasPages())
    <div class="card-footer">{{ $jenisPelanggarans->links() }}</div>
    @endif
</div>
@endsection
