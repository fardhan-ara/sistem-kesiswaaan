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
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nama_kelas" class="form-control" placeholder="Cari nama kelas..." value="{{ request('nama_kelas') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="jurusan" class="form-control" placeholder="Cari jurusan..." value="{{ request('jurusan') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('kelas.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="kelasTable">
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
                    <td>{{ $item->waliKelas ? $item->waliKelas->nama_guru : '-' }}</td>
                    <td>{{ $item->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewKelas({{ $item->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
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

@push('scripts')
<script>
function viewKelas(id) {
    $.get('/kelas/' + id, function(data) {
        Swal.fire({
            title: 'Detail Kelas',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>Nama Kelas</th><td>${data.nama_kelas}</td></tr>
                    <tr><th>Jurusan</th><td>${data.jurusan || '-'}</td></tr>
                    <tr><th>Wali Kelas</th><td>${data.wali_kelas && data.wali_kelas.nama_guru ? data.wali_kelas.nama_guru : '-'}</td></tr>
                    <tr><th>Tahun Ajaran</th><td>${data.tahun_ajaran ? data.tahun_ajaran.tahun_ajaran : '-'}</td></tr>
                </table>
            `,
            width: 600,
            confirmButtonText: 'Tutup'
        });
    });
}
</script>
@endpush
