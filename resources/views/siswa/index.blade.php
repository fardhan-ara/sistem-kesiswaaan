@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-graduate mr-1"></i> Daftar Siswa</h3>
        <div class="card-tools">
            <a href="/siswa/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <select name="status_approval" class="form-control">
                        <option value="">Semua Status Approval</option>
                        <option value="pending" {{ request('status_approval') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status_approval') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status_approval') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="kelas_id" class="form-control">
                        <option value="">Semua Kelas</option>
                        @foreach(\App\Models\Kelas::orderBy('nama_kelas')->get() as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama..." value="{{ request('nama') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="nis" class="form-control" placeholder="Cari NIS..." value="{{ request('nis') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary btn-block mt-1"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="siswaTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Status Approval</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $index => $item)
                <tr>
                    <td>{{ $siswas->firstItem() + $index }}</td>
                    <td><strong>{{ $item->nis }}</strong></td>
                    <td>{{ $item->nama_siswa }}</td>
                    <td>
                        @if($item->jenis_kelamin === 'L')
                            <span class="badge badge-primary">Laki-laki</span>
                        @else
                            <span class="badge badge-info">Perempuan</span>
                        @endif
                    </td>
                    <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $item->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                    <td>
                        @if($item->status_approval === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($item->status_approval === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewSiswa({{ $item->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($item->status_approval === 'pending')
                        <form action="{{ route('siswa.approve', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Setuju">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('siswa.reject', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin tolak dan hapus?')" title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @else
                        <a href="/siswa/{{ $item->id }}/edit" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="/siswa/{{ $item->id }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="no-data-row"><td colspan="8" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($siswas->hasPages())
    <div class="card-footer">{{ $siswas->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function viewSiswa(id) {
    $.get('/siswa/' + id, function(data) {
        Swal.fire({
            title: 'Detail Siswa',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>NIS</th><td>${data.nis}</td></tr>
                    <tr><th>Nama</th><td>${data.nama_siswa}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>${data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td></tr>
                    <tr><th>Kelas</th><td>${data.kelas ? data.kelas.nama_kelas : '-'}</td></tr>
                    <tr><th>Tahun Ajaran</th><td>${data.tahun_ajaran ? data.tahun_ajaran.tahun_ajaran : '-'}</td></tr>
                    <tr><th>Email</th><td>${data.user ? data.user.email : '-'}</td></tr>
                    <tr><th>Status Approval</th><td>${data.status_approval}</td></tr>
                </table>
            `,
            width: 600,
            confirmButtonText: 'Tutup'
        });
    });
}
</script>
@endpush
