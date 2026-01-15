@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit"></i> Edit Profil</h3>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    <div class="form-group text-center">
                        <div class="mb-3">
                            @if($user->foto)
                                <img id="preview" src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="img-circle" style="width: 150px; height: 150px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=667eea&color=fff&size=150'">
                            @else
                                <img id="preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&background=667eea&color=fff&size=150" alt="Foto Profil" class="img-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="custom-file" style="max-width: 400px; margin: 0 auto;">
                            <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                            <label class="custom-file-label" for="foto">Pilih foto profil</label>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Format: JPG, PNG. Max: 2MB</small>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="username" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}">
                        @error('no_telp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h5>Ubah Password (Opsional)</h5>
                    <p class="text-muted">Kosongkan jika tidak ingin mengubah password</p>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Catatan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Perubahan nama akan otomatis memperbarui data terkait</li>
                            <li>Pastikan email yang digunakan masih aktif</li>
                            <li>Jika mengubah password, Anda akan diminta login ulang</li>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
        
        // Update label
        const fileName = file.name;
        event.target.nextElementSibling.textContent = fileName;
    }
}

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 5000
    });
@endif

@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>'
    });
@endif
</script>
@endsection
