@extends('layouts.app')

@section('title', 'Tambah Guru')
@section('page-title', 'Tambah Guru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Guru</h3>
    </div>
    <form action="{{ route('guru.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>User/Akun <span class="text-danger">*</span></label>
                <select name="users_id" class="form-control @error('users_id') is-invalid @enderror" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('users_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->nama }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('users_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <small class="form-text text-muted">Pilih user dengan role Guru atau Wali Kelas</small>
            </div>
            
            <div class="form-group">
                <label>NIP <span class="text-danger">*</span></label>
                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                    value="{{ old('nip') }}" placeholder="Masukkan NIP" required>
                @error('nip')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Bidang Studi</label>
                <input type="text" name="bidang_studi" class="form-control @error('bidang_studi') is-invalid @enderror" 
                    value="{{ old('bidang_studi') }}" placeholder="Contoh: Matematika, IPA, Bahasa Indonesia">
                @error('bidang_studi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ old('status') === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('guru.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection
