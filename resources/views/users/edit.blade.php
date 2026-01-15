@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-edit mr-1"></i>
                    Form Edit User
                </h3>
            </div>
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($user->email === 'admin@test.com')
                    <div class="alert alert-warning">
                        <i class="fas fa-lock"></i> Admin utama hanya dapat mengubah password.
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $user->nama) }}" {{ $user->email === 'admin@test.com' ? 'readonly' : '' }} required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" autocomplete="new-email" {{ $user->email === 'admin@test.com' ? 'readonly' : '' }} required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" {{ $user->email === 'admin@test.com' ? 'disabled' : '' }} required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kepala_sekolah" {{ old('role', $user->role) == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            <option value="kesiswaan" {{ old('role', $user->role) == 'kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                            <option value="bk" {{ old('role', $user->role) == 'bk' ? 'selected' : '' }}>BK</option>
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="ortu" {{ old('role', $user->role) == 'ortu' ? 'selected' : '' }}>Orang Tua</option>
                        </select>
                        @if($user->email === 'admin@test.com')
                        <input type="hidden" name="role" value="admin">
                        @endif
                        @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status', $user->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ old('status', $user->status ?? 'pending') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ old('status', $user->status ?? 'pending') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="siswa-field" style="display: none;">
                        <label for="siswa_id">Pilih Siswa (Anak) <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="form-control">
                            <option value="">Pilih Siswa</option>
                            @foreach(\App\Models\Siswa::with('kelas')->get() as $siswa)
                                <option value="{{ $siswa->id }}" 
                                    {{ old('siswa_id', json_decode($user->metadata, true)['siswa_id'] ?? '') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_siswa }} - {{ $siswa->nis }} ({{ $siswa->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr>
                    <p class="text-muted"><small>Kosongkan password jika tidak ingin mengubah</small></p>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi User
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Terdaftar:</th>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">Belum Verifikasi</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const roleSelect = document.getElementById('role');
const siswaField = document.getElementById('siswa-field');

function toggleSiswaField() {
    if (roleSelect.value === 'ortu') {
        siswaField.style.display = 'block';
        document.getElementById('siswa_id').required = true;
    } else {
        siswaField.style.display = 'none';
        document.getElementById('siswa_id').required = false;
    }
}

roleSelect.addEventListener('change', toggleSiswaField);
toggleSiswaField();

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection
