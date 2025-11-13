@extends('layouts.app')

@section('title', 'Tambah Bimbingan Konseling')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Tambah Bimbingan Konseling</h4>
    </div>
    <div class="card-body">
        <x-alert />

        <form action="{{ route('bk.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Siswa</label>
                <select name="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($siswas as $siswa)
                        <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                            {{ $siswa->nis }} - {{ $siswa->nama_siswa }} ({{ $siswa->kelas->nama_kelas }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Guru BK</label>
                <select name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                    <option value="">Pilih Guru BK</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama_guru }}
                        </option>
                    @endforeach
                </select>
                @error('guru_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4" required>{{ old('catatan') }}</textarea>
                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">Pilih Status</option>
                    <option value="Terjadwal" {{ old('status') == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ old('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('bk.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
