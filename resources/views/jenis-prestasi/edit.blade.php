@extends('layouts.app')

@section('title', 'Edit Jenis Prestasi')
@section('page-title', 'Edit Jenis Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Jenis Prestasi</h3>
    </div>
    <form action="{{ route('jenis-prestasi.update', $jenisPrestasi) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Nama Prestasi <span class="text-danger">*</span></label>
                <input type="text" name="nama_prestasi" class="form-control @error('nama_prestasi') is-invalid @enderror" value="{{ old('nama_prestasi', $jenisPrestasi->nama_prestasi) }}" required>
                @error('nama_prestasi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Poin <span class="text-danger">*</span></label>
                <input type="number" name="poin" class="form-control @error('poin') is-invalid @enderror" value="{{ old('poin', $jenisPrestasi->poin) }}" min="1" max="100" required>
                @error('poin')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    <option value="akademik" {{ old('kategori', $jenisPrestasi->kategori) === 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="non_akademik" {{ old('kategori', $jenisPrestasi->kategori) === 'non_akademik' ? 'selected' : '' }}>Non-Akademik</option>
                </select>
                @error('kategori')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Penghargaan</label>
                <textarea name="penghargaan" class="form-control @error('penghargaan') is-invalid @enderror" rows="3">{{ old('penghargaan', $jenisPrestasi->penghargaan) }}</textarea>
                @error('penghargaan')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection
