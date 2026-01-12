@extends('layouts.app')

@section('title', 'Edit Bimbingan Konseling')
@section('page-title', 'Edit Bimbingan Konseling')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Bimbingan Konseling</h3>
    </div>
    <form action="{{ route('bk.update', $bk) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Siswa <span class="text-danger">*</span></label>
                <select name="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}" {{ old('siswa_id', $bk->siswa_id) == $siswa->id ? 'selected' : '' }}>
                            {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Guru BK <span class="text-danger">*</span></label>
                <select name="guru_id" class="form-control @error('guru_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Guru BK --</option>
                    @forelse($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id', $bk->guru_id) == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama_guru }} ({{ $guru->bidang_studi }})
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada guru BK tersedia</option>
                    @endforelse
                </select>
                @error('guru_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <small class="form-text text-muted">Hanya guru dengan bidang studi BK yang dapat dipilih</small>
            </div>
            
            <div class="form-group">
                <label>Kategori Konseling <span class="text-danger">*</span></label>
                <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="pribadi" {{ old('kategori', $bk->kategori) === 'pribadi' ? 'selected' : '' }}>Bidang Bimbingan Pribadi</option>
                    <option value="sosial" {{ old('kategori', $bk->kategori) === 'sosial' ? 'selected' : '' }}>Bidang Bimbingan Sosial</option>
                    <option value="belajar" {{ old('kategori', $bk->kategori) === 'belajar' ? 'selected' : '' }}>Bidang Bimbingan Belajar</option>
                    <option value="karir" {{ old('kategori', $bk->kategori) === 'karir' ? 'selected' : '' }}>Bidang Bimbingan Karir</option>
                </select>
                @error('kategori')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                    value="{{ old('tanggal', $bk->tanggal->format('Y-m-d')) }}" required>
                @error('tanggal')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="terjadwal" {{ old('status', $bk->status) === 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="proses" {{ old('status', $bk->status) === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ old('status', $bk->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            
            <div class="form-group">
                <label>Catatan <span class="text-danger">*</span></label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                    rows="5" placeholder="Masukkan catatan bimbingan..." required>{{ old('catatan', $bk->catatan) }}</textarea>
                @error('catatan')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('bk.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection
