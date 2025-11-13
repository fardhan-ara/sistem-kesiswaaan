@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Edit Data Siswa</h4>
    </div>
    <div class="card-body">
        <x-alert />

        <form action="{{ route('siswa.update', $siswa) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis', $siswa->nis) }}" required>
                @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <input type="text" name="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
                @error('nama_siswa')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select @error('tahun_ajaran_id') is-invalid @enderror" required>
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $siswa->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama_tahun }} - {{ $ta->semester }}
                        </option>
                    @endforeach
                </select>
                @error('tahun_ajaran_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
