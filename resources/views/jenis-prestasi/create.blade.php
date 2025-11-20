@extends('layouts.app')

@section('title', 'Tambah Jenis Prestasi')
@section('page-title', 'Tambah Jenis Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus mr-1"></i> Form Tambah Jenis Prestasi</h3>
    </div>
    <form action="{{ route('jenis-prestasi.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Nama Prestasi <span class="text-danger">*</span></label>
                <input type="text" name="nama_prestasi" class="form-control @error('nama_prestasi') is-invalid @enderror" value="{{ old('nama_prestasi') }}" required>
                @error('nama_prestasi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Poin <span class="text-danger">*</span></label>
                <input type="number" name="poin" class="form-control @error('poin') is-invalid @enderror" value="{{ old('poin') }}" min="1" max="100" required>
                @error('poin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="akademik" {{ old('kategori') === 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="non_akademik" {{ old('kategori') === 'non_akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
                @error('kategori')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Penghargaan</label>
                <textarea name="penghargaan" class="form-control @error('penghargaan') is-invalid @enderror" rows="3">{{ old('penghargaan') }}</textarea>
                @error('penghargaan')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection
