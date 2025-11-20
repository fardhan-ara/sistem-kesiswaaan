@extends('layouts.app')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-plus mr-1"></i> Form Tambah Siswa</h3>
    </div>
    <form action="{{ route('siswa.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>NIS <span class="text-danger">*</span></label>
                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" 
                    value="{{ old('nis') }}" placeholder="Masukkan NIS" required>
                @error('nis')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Nama Siswa <span class="text-danger">*</span></label>
                <input type="text" name="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror" 
                    value="{{ old('nama_siswa') }}" placeholder="Masukkan nama lengkap" required>
                @error('nama_siswa')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Kelas <span class="text-danger">*</span></label>
                <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="tahun_ajaran_id" class="form-control @error('tahun_ajaran_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" 
                            {{ old('tahun_ajaran_id', $ta->status_aktif == 'aktif' ? $ta->id : '') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun_ajaran }} - {{ ucfirst($ta->semester) }}
                            @if($ta->status_aktif == 'aktif') (Aktif) @endif
                        </option>
                    @endforeach
                </select>
                @error('tahun_ajaran_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
