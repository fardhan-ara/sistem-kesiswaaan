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
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status_approval" class="form-control">
                        <option value="">Semua Status Approval</option>
                        <option value="pending" {{ request('status_approval') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status_approval') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status_approval') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="nama" class="form-control" placeholder="Cari nama guru..." value="{{ request('nama') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('guru.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="guruTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Jenis Kelamin</th>
                    <th>Email</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Status Approval</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $index => $item)
                <tr>
                    <td>{{ $gurus->firstItem() + $index }}</td>
                    <td><strong>{{ $item->nip }}</strong></td>
                    <td>{{ $item->nama_guru }}</td>
                    <td>
                        @if($item->jenis_kelamin === 'L')
                            <span class="badge badge-primary">Laki-laki</span>
                        @elseif($item->jenis_kelamin === 'P')
                            <span class="badge badge-info">Perempuan</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
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
                        @if($item->status_approval === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($item->status_approval === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewGuru({{ $item->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        @if($item->status_approval === 'pending')
                        <form action="{{ route('guru.approve', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm" title="Setuju">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('guru.reject', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin tolak dan hapus?')" title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('guru.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('guru.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($gurus->hasPages())
    <div class="card-footer">{{ $gurus->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function viewGuru(id) {
    $.get('/guru/' + id, function(data) {
        Swal.fire({
            title: 'Detail Guru',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>NIP</th><td>${data.nip}</td></tr>
                    <tr><th>Nama</th><td>${data.nama_guru}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>${data.jenis_kelamin === 'L' ? 'Laki-laki' : data.jenis_kelamin === 'P' ? 'Perempuan' : '-'}</td></tr>
                    <tr><th>Email</th><td>${data.user.email}</td></tr>
                    <tr><th>Mata Pelajaran</th><td>${data.bidang_studi || '-'}</td></tr>
                    <tr><th>Status</th><td>${data.status}</td></tr>
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
