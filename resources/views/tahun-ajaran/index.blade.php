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
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="status_aktif" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status_aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status_aktif') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="semester" class="form-control">
                        <option value="">Semua Semester</option>
                        <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="genap" {{ request('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('tahun-ajaran.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped" id="tahunAjaranTable">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th width="20%">Aksi</th>
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
                        <button class="btn btn-info btn-sm" onclick="viewTahunAjaran({{ $item->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
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

@push('scripts')
<script>
function viewTahunAjaran(id) {
    $.get('/tahun-ajaran/' + id, function(data) {
        Swal.fire({
            title: 'Detail Tahun Ajaran',
            html: `
                <table class="table table-bordered text-left">
                    <tr><th>Tahun Ajaran</th><td>${data.tahun_ajaran}</td></tr>
                    <tr><th>Tahun Mulai</th><td>${data.tahun_mulai}</td></tr>
                    <tr><th>Tahun Selesai</th><td>${data.tahun_selesai}</td></tr>
                    <tr><th>Semester</th><td>${data.semester}</td></tr>
                    <tr><th>Status</th><td>${data.status_aktif}</td></tr>
                </table>
            `,
            width: 600,
            confirmButtonText: 'Tutup'
        });
    });
}
</script>
@endpush