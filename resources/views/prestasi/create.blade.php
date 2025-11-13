@extends('layouts.app')

@section('title', 'Tambah Prestasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Tambah Data Prestasi</h4>
    </div>
    <div class="card-body">
        <x-alert />

        <form action="{{ route('prestasi.store') }}" method="POST">
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
                <label class="form-label">Jenis Prestasi</label>
                <select name="jenis_prestasi_id" class="form-select @error('jenis_prestasi_id') is-invalid @enderror" required>
                    <option value="">Pilih Jenis Prestasi</option>
                    @foreach($jenisPrestasis as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_prestasi_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_prestasi }} (Poin: {{ $jenis->poin_reward }})
                        </option>
                    @endforeach
                </select>
                @error('jenis_prestasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('prestasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
