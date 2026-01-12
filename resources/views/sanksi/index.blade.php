@extends('layouts.app')

@section('title', 'Data Sanksi')
@section('page-title', 'Data Sanksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-gavel mr-1"></i> Daftar Sanksi</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('sanksi.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-control form-control-sm">
                        <option value="">Semua Kategori</option>
                        <option value="berat" {{ request('kategori') == 'berat' ? 'selected' : '' }}>Berat</option>
                        <option value="sangat_berat" {{ request('kategori') == 'sangat_berat' ? 'selected' : '' }}>Sangat Berat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="siswa" class="form-control form-control-sm" placeholder="Cari nama siswa..." value="{{ request('siswa') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('sanksi.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Kategori</th>
                        <th>Total Poin</th>
                        <th>Jenis Sanksi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sanksis as $index => $sanksi)
                    <tr>
                        <td>{{ $sanksis->firstItem() + $index }}</td>
                        <td>{{ $sanksi->siswa->nama_siswa ?? ($sanksi->pelanggaran->siswa->nama_siswa ?? 'N/A') }}</td>
                        <td>{{ $sanksi->siswa->kelas->nama_kelas ?? ($sanksi->pelanggaran->siswa->kelas->nama_kelas ?? 'N/A') }}</td>
                        <td>
                            @if($sanksi->kategori_poin == 'sangat_berat')
                                <span class="badge badge-dark">Sangat Berat</span>
                            @elseif($sanksi->kategori_poin == 'berat')
                                <span class="badge badge-danger">Berat</span>
                            @else
                                <span class="badge badge-secondary">-</span>
                            @endif
                        </td>
                        <td><span class="badge badge-danger">{{ $sanksi->total_poin ?? 0 }} Poin</span></td>
                        <td>{{ $sanksi->nama_sanksi }}</td>
                        <td>{{ $sanksi->tanggal_mulai ? \Carbon\Carbon::parse($sanksi->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $sanksi->tanggal_selesai ? \Carbon\Carbon::parse($sanksi->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($sanksi->status_sanksi == 'aktif')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif($sanksi->status_sanksi == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($sanksi->status_sanksi) }}</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="showDetail({{ $sanksi->id }})" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($sanksi->status_sanksi == 'aktif')
                            <form action="{{ route('sanksi.destroy', $sanksi) }}" method="POST" class="d-inline" onsubmit="return confirm('Selesaikan sanksi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-success btn-sm" title="Selesai">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data sanksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sanksis->hasPages())
    <div class="card-footer">
        {{ $sanksis->links() }}
    </div>
    @endif
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Sanksi</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showDetail(id) {
    const sanksi = @json($sanksis->items()).find(s => s.id === id);
    if (!sanksi) return;
    
    const siswa = sanksi.siswa || sanksi.pelanggaran?.siswa;
    const kategoriLabel = sanksi.kategori_poin === 'sangat_berat' ? 'Sangat Berat' : (sanksi.kategori_poin === 'berat' ? 'Berat' : '-');
    
    const content = `
        <table class="table table-bordered">
            <tr>
                <th width="30%">Siswa</th>
                <td>${siswa?.nama_siswa || 'N/A'}</td>
            </tr>
            <tr>
                <th>NIS</th>
                <td>${siswa?.nis || 'N/A'}</td>
            </tr>
            <tr>
                <th>Kelas</th>
                <td>${siswa?.kelas?.nama_kelas || 'N/A'}</td>
            </tr>
            <tr>
                <th>Kategori Poin</th>
                <td><span class="badge badge-${sanksi.kategori_poin === 'sangat_berat' ? 'dark' : 'danger'}">${kategoriLabel}</span></td>
            </tr>
            <tr>
                <th>Total Poin Pelanggaran</th>
                <td><span class="badge badge-danger">${sanksi.total_poin || 0} Poin</span></td>
            </tr>
            <tr>
                <th>Jenis Sanksi</th>
                <td><strong>${sanksi.nama_sanksi}</strong></td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>${sanksi.tanggal_mulai ? new Date(sanksi.tanggal_mulai).toLocaleDateString('id-ID') : '-'}</td>
            </tr>
            <tr>
                <th>Tanggal Selesai</th>
                <td>${sanksi.tanggal_selesai ? new Date(sanksi.tanggal_selesai).toLocaleDateString('id-ID') : '-'}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td><span class="badge badge-${sanksi.status_sanksi === 'aktif' ? 'warning' : 'success'}">${sanksi.status_sanksi === 'aktif' ? 'Aktif' : 'Selesai'}</span></td>
            </tr>
        </table>
    `;
    
    $('#modalContent').html(content);
    $('#detailModal').modal('show');
}

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
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection
