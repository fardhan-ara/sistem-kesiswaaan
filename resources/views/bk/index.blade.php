@extends('layouts.app')

@section('title', 'Bimbingan Konseling')
@section('page-title', 'Bimbingan Konseling')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-comments mr-1"></i> Daftar Bimbingan Konseling</h3>
        <div class="card-tools">
            <a href="{{ route('bk.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah BK
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('bk.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="terjadwal" {{ request('status') == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="kategori" class="form-control form-control-sm">
                        <option value="">Semua Kategori</option>
                        <option value="pribadi" {{ request('kategori') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                        <option value="sosial" {{ request('kategori') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                        <option value="belajar" {{ request('kategori') == 'belajar' ? 'selected' : '' }}>Belajar</option>
                        <option value="karir" {{ request('kategori') == 'karir' ? 'selected' : '' }}>Karir</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="siswa" class="form-control form-control-sm" placeholder="Cari siswa..." value="{{ request('siswa') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="guru" class="form-control form-control-sm" placeholder="Cari guru..." value="{{ request('guru') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('bk.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="bkTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Kategori</th>
                    <th>Guru BK</th>
                    <th>Status</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bimbingans as $index => $item)
                <tr>
                    <td>{{ $bimbingans->firstItem() + $index }}</td>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $item->siswa->nama_siswa }}</td>
                    <td>{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        @if($item->kategori === 'pribadi')
                            <span class="badge badge-primary">Pribadi</span>
                        @elseif($item->kategori === 'sosial')
                            <span class="badge badge-info">Sosial</span>
                        @elseif($item->kategori === 'belajar')
                            <span class="badge badge-warning">Belajar</span>
                        @else
                            <span class="badge badge-secondary">Karir</span>
                        @endif
                    </td>
                    <td>{{ $item->guru->nama_guru }}</td>
                    <td>
                        @if($item->status === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($item->status === 'proses')
                            <span class="badge badge-warning">Proses</span>
                        @else
                            <span class="badge badge-info">Terjadwal</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewBK({{ $item->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('bk.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('bk.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="no-data-row"><td colspan="8" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bimbingans->hasPages())
    <div class="card-footer">{{ $bimbingans->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function viewBK(id) {
    $.get('/bk/' + id, function(data) {
        Swal.fire({
            title: 'Detail Bimbingan Konseling',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>Tanggal</th><td>${new Date(data.tanggal).toLocaleDateString('id-ID')}</td></tr>
                    <tr><th>Siswa</th><td>${data.siswa.nama_siswa}</td></tr>
                    <tr><th>Kelas</th><td>${data.siswa.kelas ? data.siswa.kelas.nama_kelas : '-'}</td></tr>
                    <tr><th>Kategori</th><td>${data.kategori === 'pribadi' ? 'Bimbingan Pribadi' : data.kategori === 'sosial' ? 'Bimbingan Sosial' : data.kategori === 'belajar' ? 'Bimbingan Belajar' : 'Bimbingan Karir'}</td></tr>
                    <tr><th>Guru BK</th><td>${data.guru.nama_guru}</td></tr>
                    <tr><th>Status</th><td>${data.status}</td></tr>
                    <tr><th>Catatan</th><td>${data.catatan}</td></tr>
                </table>
            `,
            width: 600,
            confirmButtonText: 'Tutup'
        });
    });
}
</script>
@endpush
