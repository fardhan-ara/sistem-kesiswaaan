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
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama pelanggaran..." value="{{ request('nama') }}">
                </div>
                <div class="col-md-4">
                    <select name="kategori" class="form-control">
                        <option value="">Semua Kategori</option>
                        <option value="ketertiban" {{ request('kategori') == 'ketertiban' ? 'selected' : '' }}>Ketertiban</option>
                        <option value="kehadiran" {{ request('kategori') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                        <option value="pakaian" {{ request('kategori') == 'pakaian' ? 'selected' : '' }}>Pakaian</option>
                        <option value="sikap" {{ request('kategori') == 'sikap' ? 'selected' : '' }}>Sikap & Etika</option>
                        <option value="akademik" {{ request('kategori') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="fasilitas" {{ request('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="kriminal" {{ request('kategori') == 'kriminal' ? 'selected' : '' }}>Kriminal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('jenis-pelanggaran.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Kategori</th>
                    <th>Nama Pelanggaran</th>
                    <th width="8%">Poin</th>
                    <th>Sanksi Rekomendasi</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisPelanggarans as $index => $item)
                <tr>
                    <td>{{ $jenisPelanggarans->firstItem() + $index }}</td>
                    <td><span class="badge badge-primary">{{ ucfirst($item->kategori) }}</span></td>
                    <td><strong>{{ $item->nama_pelanggaran }}</strong></td>
                    <td><span class="badge badge-danger">{{ $item->poin }}</span></td>
                    <td>{{ $item->sanksi_rekomendasi ?? '-' }}</td>
                    <td>
                        <a href="{{ route('jenis-pelanggaran.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('jenis-pelanggaran.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus jenis pelanggaran ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <button class="btn btn-info btn-sm" onclick="showUsage({{ $item->id }}, '{{ addslashes($item->nama_pelanggaran) }}')">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jenisPelanggarans->hasPages())
    <div class="card-footer">{{ $jenisPelanggarans->links() }}</div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 5000,
        showConfirmButton: true
    });
@endif

function showUsage(jenisId, namaPelanggaran) {
    fetch(`/api/jenis-pelanggaran/${jenisId}/usage`)
        .then(res => res.json())
        .then(data => {
            let html = `<p><strong>${namaPelanggaran}</strong></p>`;
            html += `<p>Digunakan pada <strong>${data.count}</strong> data pelanggaran</p>`;
            if (data.count > 0) {
                html += '<hr><small>Siswa yang terkait:</small><ul class="text-left">';
                data.siswas.forEach(s => {
                    html += `<li>${s.nama_siswa} (${s.kelas})</li>`;
                });
                html += '</ul>';
            }
            Swal.fire({
                title: 'Info Penggunaan',
                html: html,
                icon: 'info'
            });
        });
}
</script>
@endsection
