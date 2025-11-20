@extends('layouts.app')

@section('title', 'Edit Guru')
@section('page-title', 'Edit Guru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Guru</h3>
    </div>
    <form action="{{ route('guru.update', $guru) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>User/Akun <span class="text-danger">*</span></label>
                <select name="users_id" class="form-control @error('users_id') is-invalid @enderror" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('users_id', $guru->users_id) == $user->id ? 'selected' : '' }}>
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
                    value="{{ old('nip', $guru->nip) }}" placeholder="Masukkan NIP" required>
                @error('nip')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Nama Guru <span class="text-danger">*</span></label>
                <input type="text" name="nama_guru" class="form-control @error('nama_guru') is-invalid @enderror" 
                    value="{{ old('nama_guru', $guru->nama_guru) }}" placeholder="Masukkan nama lengkap" required>
                @error('nama_guru')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Bidang Studi</label>
                <input type="text" name="bidang_studi" class="form-control @error('bidang_studi') is-invalid @enderror" 
                    value="{{ old('bidang_studi', $guru->bidang_studi) }}" placeholder="Contoh: Matematika, IPA, Bahasa Indonesia">
                @error('bidang_studi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="aktif" {{ old('status', $guru->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ old('status', $guru->status) === 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('guru.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection
