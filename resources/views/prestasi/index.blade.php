@extends('layouts.app')

@section('title', 'Data Prestasi')
@section('page-title', 'Data Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-trophy"></i> Daftar Prestasi Siswa</h3>
        <div class="card-tools">
            @if(in_array(auth()->user()->role, ['admin', 'kesiswaan', 'guru', 'wali_kelas']))
            <a href="{{ route('prestasi.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Prestasi
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <form method="GET" action="{{ route('prestasi.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="tingkat" class="form-control form-control-sm">
                        <option value="">Semua Tingkat</option>
                        @foreach($tingkats as $tingkat)
                            <option value="{{ $tingkat }}" {{ request('tingkat') == $tingkat ? 'selected' : '' }}>
                                {{ ucfirst($tingkat) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="kelas" class="form-control form-control-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelass as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="siswa" class="form-control form-control-sm" placeholder="Cari siswa..." value="{{ request('siswa') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="prestasi" class="form-control form-control-sm" placeholder="Cari prestasi..." value="{{ request('prestasi') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-sm btn-block"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Mulai">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal_selesai" class="form-control form-control-sm" value="{{ request('tanggal_selesai') }}" placeholder="Tanggal Selesai">
                </div>
                <div class="col-md-3">
                    <a href="{{ route('prestasi.index') }}" class="btn btn-secondary btn-sm btn-block"><i class="fas fa-redo"></i> Reset Filter</a>
                </div>
            </div>
        </form>

        <!-- Statistics -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>Total: {{ $prestasis->total() }} prestasi</strong> | 
                    Menunggu: {{ $prestasis->where('status_verifikasi', 'pending')->count() }} | 
                    Terverifikasi: {{ $prestasis->where('status_verifikasi', 'verified')->count() }} | 
                    Ditolak: {{ $prestasis->where('status_verifikasi', 'rejected')->count() }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Siswa</th>
                        <th width="10%">Kelas</th>
                        <th width="20%">Jenis Prestasi</th>
                        <th width="8%">Tingkat</th>
                        <th width="7%">Poin</th>
                        <th width="10%">Status</th>
                        <th width="10%">Tanggal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasis as $index => $prestasi)
                    <tr>
                        <td>{{ $prestasis->firstItem() + $index }}</td>
                        <td>
                            <strong>{{ $prestasi->siswa->nama_siswa ?? 'N/A' }}</strong><br>
                            <small class="text-muted">NIS: {{ $prestasi->siswa->nis ?? '-' }}</small>
                        </td>
                        <td>{{ $prestasi->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>
                            <strong>{{ $prestasi->jenisPrestasi->nama_prestasi ?? 'N/A' }}</strong>
                            @if($prestasi->keterangan)
                            <br><small class="text-muted">{{ Str::limit($prestasi->keterangan, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($prestasi->jenisPrestasi->tingkat ?? '-') }}</span>
                        </td>
                        <td>
                            <span class="badge badge-success" style="font-size: 14px;">+{{ $prestasi->poin }}</span>
                        </td>
                        <td>
                            @switch($prestasi->status_verifikasi)
                                @case('verified')
                                    <span class="badge badge-success">✅ Terverifikasi</span>
                                    @if($prestasi->verifikator)
                                    <br><small class="text-muted">{{ $prestasi->verifikator->nama_guru }}</small>
                                    @endif
                                    @break
                                @case('rejected')
                                    <span class="badge badge-danger">❌ Ditolak</span>
                                    @break
                                @default
                                    <span class="badge badge-warning">⏳ Menunggu</span>
                            @endswitch
                        </td>
                        <td>{{ $prestasi->tanggal_prestasi ? \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->format('d/m/Y') : $prestasi->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('prestasi.show', $prestasi->id) }}" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(in_array(auth()->user()->role, ['admin', 'kesiswaan']) && $prestasi->status_verifikasi == 'pending')
                                    <form action="{{ route('prestasi.verify', $prestasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Setujui prestasi ini?')">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('prestasi.verify', $prestasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tolak prestasi ini?')">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-danger" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($prestasi->status_verifikasi == 'pending' && in_array(auth()->user()->role, ['admin', 'kesiswaan', 'guru', 'wali_kelas']))
                                    <a href="{{ route('prestasi.edit', $prestasi->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                
                                @if(in_array(auth()->user()->role, ['admin', 'kesiswaan']))
                                    <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus prestasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p>Tidak ada data prestasi</p>
                        </td>
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