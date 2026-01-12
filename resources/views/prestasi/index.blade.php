@extends('layouts.app')

@section('title', 'Data Prestasi')
@section('page-title', 'Data Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Prestasi</h3>
        <div class="card-tools">
            <a href="/prestasi-create-test" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Prestasi
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('prestasi.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="siswa" class="form-control form-control-sm" placeholder="Cari nama siswa..." value="{{ request('siswa') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="prestasi" class="form-control form-control-sm" placeholder="Cari jenis prestasi..." value="{{ request('prestasi') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('prestasi.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Jenis Prestasi</th>
                        <th>Poin</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasis as $index => $prestasi)
                    <tr>
                        <td>{{ $prestasis->firstItem() + $index }}</td>
                        <td>{{ $prestasi->siswa->nama_siswa ?? 'N/A' }}</td>
                        <td>{{ $prestasi->jenisPrestasi->nama_prestasi ?? 'N/A' }}</td>
                        <td><span class="badge badge-success">{{ $prestasi->poin }}</span></td>
                        <td>
                            @switch($prestasi->status_verifikasi)
                                @case('terverifikasi')
                                @case('verified')
                                    <span class="badge badge-success">Terverifikasi</span>
                                    @break
                                @case('ditolak')
                                @case('rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @break
                                @default
                                    <span class="badge badge-warning">Menunggu</span>
                            @endswitch
                        </td>
                        <td>{{ $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d/m/Y') : $prestasi->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('prestasi.show', $prestasi->id) }}" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'kesiswaan']) && $prestasi->status_verifikasi == 'pending')
                                <form action="{{ route('prestasi.verify', $prestasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Verifikasi prestasi ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" title="Verifikasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('prestasi.reject', $prestasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirmReject(event, {{ $prestasi->id }})">
                                    @csrf
                                    <input type="hidden" name="alasan_penolakan" id="alasan_{{ $prestasi->id }}">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                            @if(in_array($prestasi->status_verifikasi, ['pending']))
                                <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            @if(in_array(auth()->user()->role, ['admin', 'kesiswaan']))
                                <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus prestasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data prestasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($prestasis->hasPages())
    <div class="card-footer">
        {{ $prestasis->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmReject(event, id) {
    event.preventDefault();
    Swal.fire({
        title: 'Tolak Prestasi',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value) {
                return 'Alasan penolakan harus diisi!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('alasan_' + id).value = result.value;
            event.target.submit();
        }
    });
    return false;
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