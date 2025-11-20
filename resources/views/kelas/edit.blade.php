@extends('layouts.app')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Kelas</h3>
    </div>
    <form action="{{ route('kelas.update', $kelas) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" 
                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}" placeholder="Contoh: X RPL 1, XI TKJ 2" required>
                @error('nama_kelas')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Jurusan</label>
                <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror" 
                    value="{{ old('jurusan', $kelas->jurusan) }}" placeholder="Contoh: RPL, TKJ, Multimedia">
                @error('jurusan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Wali Kelas</label>
                <select name="wali_kelas_id" class="form-control @error('wali_kelas_id') is-invalid @enderror">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama_guru }} ({{ $guru->bidang_studi ?? 'Umum' }})
                        </option>
                    @endforeach
                </select>
                @error('wali_kelas_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="tahun_ajaran_id" class="form-control @error('tahun_ajaran_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $kelas->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
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
                <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
