@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Daftar User
                </h3>
                <div class="card-tools">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="role" class="form-control">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="kesiswaan" {{ request('role') == 'kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                                <option value="bk" {{ request('role') == 'bk' ? 'selected' : '' }}>BK</option>
                                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="ortu" {{ request('role') == 'ortu' ? 'selected' : '' }}>Orang Tua</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="nama" class="form-control" placeholder="Cari nama..." value="{{ request('nama') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="email" class="form-control" placeholder="Cari email..." value="{{ request('email') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i> Reset</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="usersTable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terakhir Login</th>
                                <th>Terdaftar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge badge-danger">Admin</span>
                                            @break
                                        @case('kepala_sekolah')
                                            <span class="badge badge-dark">Kepala Sekolah</span>
                                            @break
                                        @case('kesiswaan')
                                            <span class="badge badge-warning">Kesiswaan</span>
                                            @break
                                        @case('bk')
                                            <span class="badge badge-purple">BK</span>
                                            @break
                                        @case('guru')
                                            <span class="badge badge-info">Guru</span>
                                            @break
                                        @case('siswa')
                                            <span class="badge badge-success">Siswa</span>
                                            @break
                                        @case('ortu')
                                            <span class="badge badge-secondary">Orang Tua</span>
                                            @break
                                        @default
                                            <span class="badge badge-light">{{ ucfirst($user->role) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    @switch($user->status ?? 'approved')
                                        @case('approved')
                                            <span class="badge badge-success">Disetujui</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge badge-danger" title="{{ $user->rejection_reason }}">Ditolak</span>
                                            @break
                                        @case('pending')
                                            <span class="badge badge-warning">Menunggu</span>
                                            @break
                                    @endswitch
                                    @if($user->role === 'ortu')
                                        @if($user->nama_anak && $user->nis_anak)
                                            <br><small class="text-info">Anak: {{ $user->nama_anak }} ({{ $user->nis_anak }})</small>
                                        @else
                                            <br><small class="text-danger">⚠ Data anak belum lengkap</small>
                                        @endif
                                    @endif
                                    @if($user->role === 'guru' || $user->role === 'wali_kelas')
                                        @if($user->guru)
                                            <br><small class="text-success">✓ Data guru tersedia</small>
                                        @else
                                            <br><small class="text-danger">⚠ Belum terhubung</small>
                                        @endif
                                    @endif
                                    @if($user->role === 'siswa')
                                        @if($user->siswa)
                                            <br><small class="text-success">✓ Data siswa tersedia</small>
                                        @else
                                            <br><small class="text-danger">⚠ Belum terhubung</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($user->last_login_at)
                                        <span title="{{ $user->last_login_at->format('d/m/Y H:i:s') }}">
                                            {{ $user->last_login_at->diffForHumans() }}
                                        </span>
                                        @php
                                            $isOnline = $user->last_activity_at && $user->last_activity_at->diffInMinutes(now()) < 5;
                                        @endphp
                                        @if($isOnline)
                                            <br><span class="badge badge-success"><i class="fas fa-circle"></i> Online</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum pernah login</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if(($user->status ?? 'approved') === 'pending')
                                        <button type="button" class="btn btn-success btn-sm" title="Setujui" 
                                                onclick="confirmApprove({{ $user->id }}, '{{ $user->nama }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" title="Tolak" 
                                                onclick="showRejectModal({{ $user->id }}, '{{ $user->nama }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" title="Lihat Detail" 
                                                onclick="window.location.href='{{ route('users.edit', $user) }}'">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form id="approve-form-{{ $user->id }}" action="{{ route('users.approve', $user) }}" method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                    @else
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm" title="Edit" @if($user->email === 'admin@test.com') onclick="return confirm('Admin utama hanya bisa ubah password!')" @endif>
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id() && $user->email !== 'admin@test.com')
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->nama }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr class="no-data-row">
                                <td colspan="8" class="text-center">Tidak ada data user</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmApprove(userId, userName) {
    console.log('confirmApprove called', userId, userName);
    Swal.fire({
        title: 'Konfirmasi Setujui',
        html: `Apakah Anda yakin ingin menyetujui user <strong>${userName}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        console.log('Swal result:', result);
        if (result.isConfirmed) {
            console.log('Submitting form approve-form-' + userId);
            const form = document.getElementById('approve-form-' + userId);
            console.log('Form found:', form);
            if (form) {
                form.submit();
            } else {
                console.error('Form not found!');
                alert('Error: Form tidak ditemukan!');
            }
        }
    });
}

function showRejectModal(userId, userName) {
    console.log('showRejectModal called', userId, userName);
    Swal.fire({
        title: 'Tolak User',
        html: `<p>Tolak user <strong>${userName}</strong></p>`,
        input: 'textarea',
        inputLabel: 'Alasan Penolakan',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        inputAttributes: {
            'aria-label': 'Alasan penolakan'
        },
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
        console.log('Reject result:', result);
        if (result.isConfirmed) {
            console.log('Creating reject form');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/users/' + userId + '/reject';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = result.value;
            form.appendChild(reasonInput);
            
            document.body.appendChild(form);
            console.log('Submitting reject form');
            form.submit();
        }
    });
}

function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus user <strong>${userName}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
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
@endpush
@endsection