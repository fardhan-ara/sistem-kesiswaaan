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
                <input type="text" name="nama_prestasi" class="form-control @error('nama_prestasi') is-invalid @enderror" value="{{ old('nama_prestasi') }}" placeholder="Contoh: Juara 1 Olimpiade Matematika" required>
                @error('nama_prestasi')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tingkat <span class="text-danger">*</span></label>
                        <select name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required>
                            <option value="">Pilih Tingkat</option>
                            <option value="sekolah" {{ old('tingkat') == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                            <option value="kecamatan" {{ old('tingkat') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                            <option value="kota" {{ old('tingkat') == 'kota' ? 'selected' : '' }}>Kota</option>
                            <option value="provinsi" {{ old('tingkat') == 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                            <option value="nasional" {{ old('tingkat') == 'nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="internasional" {{ old('tingkat') == 'internasional' ? 'selected' : '' }}>Internasional</option>
                        </select>
                        @error('tingkat')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori Penampilan <span class="text-danger">*</span></label>
                        <select name="kategori_penampilan" class="form-control @error('kategori_penampilan') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="solo" {{ old('kategori_penampilan') == 'solo' ? 'selected' : '' }}>Solo (Individu)</option>
                            <option value="duo" {{ old('kategori_penampilan') == 'duo' ? 'selected' : '' }}>Duo (2 Orang)</option>
                            <option value="trio" {{ old('kategori_penampilan') == 'trio' ? 'selected' : '' }}>Trio (3 Orang)</option>
                            <option value="grup" {{ old('kategori_penampilan') == 'grup' ? 'selected' : '' }}>Grup (4-10 Orang)</option>
                            <option value="tim" {{ old('kategori_penampilan') == 'tim' ? 'selected' : '' }}>Tim (11+ Orang)</option>
                            <option value="kolektif" {{ old('kategori_penampilan') == 'kolektif' ? 'selected' : '' }}>Kolektif (Massal)</option>
                        </select>
                        @error('kategori_penampilan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Poin Reward <span class="text-danger">*</span></label>
                <input type="number" name="poin_reward" class="form-control @error('poin_reward') is-invalid @enderror" value="{{ old('poin_reward') }}" min="1" max="100" placeholder="1-100" required>
                @error('poin_reward')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('jenis-prestasi.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection
