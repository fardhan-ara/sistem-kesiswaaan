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
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama prestasi..." value="{{ request('nama') }}">
                </div>
                <div class="col-md-3">
                    <select name="tingkat" class="form-control">
                        <option value="">Semua Tingkat</option>
                        <option value="sekolah" {{ request('tingkat') == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                        <option value="kecamatan" {{ request('tingkat') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                        <option value="kota" {{ request('tingkat') == 'kota' ? 'selected' : '' }}>Kota</option>
                        <option value="provinsi" {{ request('tingkat') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                        <option value="nasional" {{ request('tingkat') == 'nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="internasional" {{ request('tingkat') == 'internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori_penampilan" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="solo" {{ request('kategori_penampilan') == 'solo' ? 'selected' : '' }}>Solo</option>
                        <option value="duo" {{ request('kategori_penampilan') == 'duo' ? 'selected' : '' }}>Duo</option>
                        <option value="trio" {{ request('kategori_penampilan') == 'trio' ? 'selected' : '' }}>Trio</option>
                        <option value="grup" {{ request('kategori_penampilan') == 'grup' ? 'selected' : '' }}>Grup</option>
                        <option value="tim" {{ request('kategori_penampilan') == 'tim' ? 'selected' : '' }}>Tim</option>
                        <option value="kolektif" {{ request('kategori_penampilan') == 'kolektif' ? 'selected' : '' }}>Kolektif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Prestasi</th>
                    <th width="10%">Tingkat</th>
                    <th width="12%">Kategori</th>
                    <th width="8%">Poin</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisPrestasiS as $index => $item)
                <tr>
                    <td>{{ $jenisPrestasiS->firstItem() + $index }}</td>
                    <td><strong>{{ $item->nama_prestasi }}</strong></td>
                    <td><span class="badge badge-info">{{ ucfirst($item->tingkat) }}</span></td>
                    <td><span class="badge badge-secondary">{{ ucfirst($item->kategori_penampilan) }}</span></td>
                    <td><span class="badge badge-success">{{ $item->poin_reward }}</span></td>
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
